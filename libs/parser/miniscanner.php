<?php

	define('_VALID_ACCESS',1);
	define('DATA_DIR','data');
	
	$path = dirname(dirname(dirname(__FILE__)));

	ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.$path);	
	
	require 'data/config.php';
	require 'semcrm_defines.php';
	require 'libs/adodb/adodb-errorhandler.inc.php';
	require 'libs/adodb/adodb.inc.php';
	require 'include/database_pe.php';
	
	if ((!isset($_REQUEST["_token_"])) || ($_REQUEST["_token_"] != BILLING_TOKEN)) die('Access denied');
	
	function getParser($_parser){
			
			$parser=$_parser;
	        switch($_parser):
	    		case 1:
	    			$parser="Yandex";
	    		break;	
	    		case 2:
	    			$parser="Google";    			
	    		break;
	    		case 3:
	    			$parser="Yahoo";    			
	    		break;
	    		case 4:
	    			$parser="MSN";    			
	    		break;
	    		case 5:
	    			$parser="Rambler";    			
	    		break;
	    		case 6:
	    			$parser="Mail";    			
	    		break;    		    		    		    		    		
	        endswitch;	
	        
	        if ($parser=="Bing") $parser="MSN";
	        
	        return $parser;
	}
	
	$theme_dir = DATA_DIR.'/Base_Theme/templates/default/';
	
	$selected_keys 	= array();
	$selected_se	= array();	
	
	$me_id			= $_REQUEST["me_id"];
	$id 			= $_REQUEST['id'];
	
	if (isset($_REQUEST['selected_keys']) && !empty($_REQUEST['selected_keys'])){
		$selected_keys 	= explode(",", $_REQUEST['selected_keys']);
	}

	if (isset($_REQUEST['selected_se']) && !empty($_REQUEST['selected_se'])){
		$selected_se 	= explode(",", $_REQUEST['selected_se']);
	}
	
	$memcached=new Memcache(); 	
	$memcached->connect('localhost', 11211);
	
	/**
	 * Проверяем тип системы: коробка/не коробка
	 */
	
	if(!defined('BOXED')):
		$balance = BALANCE;
	else:
		$balance = 10000;
	endif;
	
	$ret = Array();
	
	$count_of_keywords=$count_of_patterns=0;
	
	if (!empty($selected_keys)){
		$count_of_keywords = count($selected_keys);
	} else {
		$count_of_keywords += DB::GetOne("	SELECT count(id)
											FROM semcrm_keywords_data_1
											WHERE f_project=".$id);
	}
	
	if (!empty($selected_se)){
		$count_of_patterns = count($selected_se);		
	} else {
		$count_of_patterns += DB::GetOne("	SELECT count(id)
											FROM semcrm_seo_pattern_data_1
											WHERE f_project_name=".$id);		
	}
	
	$suffix = $id;
	$url="";
		
	$key=md5(BILLING_TOKEN.$id);	
					
	if (file_exists("./tmp/cache/".SBD."/".$key)):
		unlink("./tmp/cache/".SBD."/".$key);
	endif;	
	
	if (($count_of_keywords>0) && ($count_of_patterns>0)):
		
		$keys=array();
		$ic = 0;
			
		$se_pattern = DB::GetRow("	SELECT t1.f_search_engines, t1.f_smart_search, t1.f_use_proxy
									FROM semcrm_seo_pattern_data_1 t1
									WHERE t1.f_project_name=".$id);		
		//file_put_contents("./tmp/rt.txt", var_export($se_pattern, true));
		
		if (!is_null($se_pattern['f_use_proxy'])){
			$individProxy[] = $se_pattern['f_use_proxy'];
		}
		
		if (empty($selected_se)) {
			$selected_se = array_slice(explode("__", $se_pattern["f_search_engines"]),1,-1);
		}
		
		
		
		foreach($selected_se as $key=>$val){
			if (strpos($val, "-")){
				$m = explode("-",$val);
				$se_id = $m[0];
				$loc_id = $m[1];
				
				$se = DB::GetRow("	SELECT * 
									FROM semcrm_se_pattern_data_1
									WHERE id=".$se_id);
				$loc = DB::GetRow("	SELECT t1.*, t2.value as loc
									FROM semcrm_locals t1
									JOIN utils_commondata_tree t2 on t2.id=t1.local 
									WHERE t1.id=%d", array($loc_id));
								
				$parser = getParser($se["f_parser"]);
				$ind = strtolower($parser);
				$local_field = $loc[$ind];
				$seid = $val;
				$search_engine_name = $se['f_search_engine_name']." (".$loc['loc'].")";
				
			} else {
				$se = DB::GetRow("	SELECT * 
									FROM semcrm_se_pattern_data_1
									WHERE id=".$val);
				
				$parser = getParser($se["f_parser"]);
				$local_field = "";	
				$seid = $val;	
				$search_engine_name = $se['f_search_engine_name'];				
			}
				
			$cond = "";
			if (!empty($selected_keys)){
				$cond = "AND t1.id IN (".$_REQUEST['selected_keys'].")";
			}
			$keywords = DB::GetAll("	SELECT t1.id, t1.f_keyword, t3.f_project_url as url, t2.position, IFNULL(t2.position,0) as position
										FROM semcrm_keywords_data_1 t1
										LEFT JOIN last_keywords_positions t2 ON t2.key_id_se_id = CONCAT( t1.id, '_', '".$seid."' )
										JOIN premium_projects_data_1 t3 ON t3.id = t1.f_project
										WHERE t1.f_project=%d AND t1.active=%d ".$cond."
										GROUP BY t1.id", array($id, 1)
									);	

			foreach ($keywords as $ks=>$keyw){

				$ret[] = array(
									"search_engine_name"	=>	$search_engine_name,
									"parser"				=>	$parser,
									"local"					=>	$local_field,
									"keyword"				=>	$keyw["f_keyword"],
									"key_id"				=>	$keyw["id"],
									"last_position"			=>	$keyw["position"],
									"se_id"					=>	$seid,
									"request"				=>	html_entity_decode($se['f_request']),
									"url"					=>	$keyw["url"],
									"smart_search"			=>	$se_pattern["f_smart_search"],
									"proxy_group"			=>	$se_pattern['f_use_proxy'],
									"index"					=>	$ic
								);
				$ic++;				
			}					
		}							

		   	 	
		foreach ($ret as $key=>$val):
			$keys_massive[] = $key;
		endforeach;
			
		shuffle($keys_massive);
			
		foreach ($keys_massive as $key=>$val):
			$newret[$key] = $ret[$val];
		endforeach;  
			
		$_key = uniqid(BILLING_TOKEN);
		$memcached->set($_key, $newret, false, 86400);
		
		/** $individProxy - массив групп проксей
		 * Проверяем наличие файла с массивом проксей в кеше системы. Если есть - инклюдим.
		 */
		
		if (isset($individProxy)):
		
			$individProxy = array_unique($individProxy);	
		
			foreach($individProxy as $gk=>$proxy_group):
			
				if (file_exists("./tmp/proxy/".SBD."/client_proxies_".$proxy_group.".php")):
					include("./tmp/proxy/".SBD."/client_proxies_".$proxy_group.".php");
				else:
					$_proxies = DB::GetAll("SELECT * 
											FROM personal_proxy_list
											WHERE group_id=%d", 
											array($proxy_group));
					
					$proxies=array();
					
					if (!empty($_proxies)):
						foreach($_proxies as $k=>$proxy):
							$proxies[]=array(	'proxy_group'	=>$proxy_group,
												'ip'			=>$proxy['ip'],
												'user'			=>$proxy['user'],
												'password'		=>$proxy['password']);
						endforeach;
						
						$p = var_export($proxies,true);
						$pr="<?php \$proxies=".$p."?>";
						
						$fp = fopen("./tmp/proxy/".SBD."/client_proxies_".$proxy_group.".php", "w+");
							    	
						if (flock($fp, LOCK_EX)):
							fwrite($fp, $pr);	
						endif;
									
						flock($fp, LOCK_UN);
						fclose($fp);
					endif;		
				endif;	
					
				if (count($proxies)!=0):
					$memcached->set($_key."_".$proxy_group."_proxy", $proxies, false, 86400);	
				endif;	
				
			endforeach;
			
			$memcached->set($_key."_individ_proxy", $individProxy, false, 86400);
			
		endif;
		
		$memcached->set($_key."_actual", true, false, 86400);
		
		$count_of_scans = count($newret);
	    $price = $count_of_scans*0.1;       
		$diff = $price-$balance;
		$billing_token = BILLING_TOKEN;
		
		$antigate_key = DB::GetOne("SELECT antigate_key
									FROM semcrm_antigate_key");		
	
echo <<<HTML

<script>
	var balance = parseFloat("{$balance}".replace(",", "."));
	var price = parseFloat("{$price}".replace(",", "."));
	
	if (balance<price){
		dff = parseFloat("{$diff}".replace(",", "."));
		alert("Не хватает "+dff+"руб. для сканирования всех элементов. Всего элементов - "+{$count_of_scans}+". Общая сумма - "+price+".");
		\$j('#actpanel_{$suffix}').remove();document.getElementById('miniscan_container').style.bottom = String(Number(document.getElementById('miniscan_container').style.bottom.replace('px',''))+26)+'px';
		if(document.getElementById('miniscan_list').innerHTML == ''){\$j('#scan_status').empty();\$j('#miniscan_container').fadeOut();}
	}else{
		\$j('#actpanel_{$suffix}').data('token', '{$billing_token}');
		\$j('#actpanel_{$suffix}').data('antigate_key', '{$antigate_key}');
		scan_ajaxUpdater("{$suffix}", {$count_of_scans}, "{$_REQUEST['id']}", "{$_key}");
	}
</script>

	<div id="count" style="display: none;"></div>

	<div class="scan" onmouseover="\$j('#scan_result_tbl_{$suffix}').draggable({handle: '#draggable_header_{$suffix}'});" onmousedown="\$j('.above_all').removeClass('above_all'); \$j('#miniscan_container').addClass('above_all'); \$j('.pop_win', this).addClass('above_all');">
		<div class="subpanel">
			<div class="openwin">	
				<a class="restore" title="Детали сканирования" href="#" onclick="\$j('#scan_result_tbl_{$suffix}').show(); \$j('#scan_result_tbl_{$suffix}').draggable({handle: '#draggable_header_{$suffix}'}); return false;"></a>
				<span id="remove_{$suffix}_span" class="remove" title="Закрыть"></span>
				<a id="remove_{$suffix}_link" class="remove" title="Закрыть" href="javascript:void(0)" onclick="closeScan('{$suffix}')"></a>
			</div>		
			<span class="project_name">{$url}</span>		
			<div id="pb_{$suffix}" class="progress_bar">
				<div id="bar_{$suffix}"></div>
			</div>
			<div class="terminator"></div>
			<div class="loader" style="display: none;">
				<div class="loader_bar"><div id="loading_{$suffix}" style="display:none;text-align: center; width: 100%"><img src="{$theme_dir}Semcrm/Seo/loader.gif" alt="Loading" /></div></div>
			</div>		
		</div>
		
		<div class="pop_win" id="scan_result_tbl_{$suffix}">
			<div class="tbl_header" id="draggable_header_{$suffix}">
				<div class="win_name">Детали сканирования (всего элементов: {$count_of_scans})</div>
				<div class="win_actions">
					<a class="hide_details" href="#" title="Свернуть" onclick="\$j('#scan_result_tbl_{$suffix}').hide();return false;"></a>
					<span id="remove_{$suffix}_span2" class="remove" title="Закрыть"></span>
					<a id="remove_{$suffix}_link2" class="remove" title="Закрыть" href="javascript:void(0)" onclick="closeScan('{$suffix}')"></a>
				</div>
			</div>
			<table cellpadding="0" cellspacing="0" style="width: 790px; margin: 5px 5px 0px 5px;">			
				<thead>
					<tr>
						<th>Ключевое слово</th>
						<th width="220">Поисковая система</th>
						<th width="80">Позиция</span></th>
						<th width="150">Предыдущая позиция</th>				                
					</tr>
				</thead>
			</table>
			<div class="tbl_wrapp">
				<table cellpadding="0" cellspacing="0" style="width: 775px;">	
				    <tbody>	
						<tr>
							<td colspan="4">
								<table class="results_table" border="0" cellpadding="0" cellspacing="0" id="result_{$suffix}">
								
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

HTML;

else:

endif;

?>
