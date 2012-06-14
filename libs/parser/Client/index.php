<?php

	define('_VALID_ACCESS',1);
	define('DATA_DIR','data');
	
	$path = dirname(dirname(dirname(dirname(__FILE__))));

	ini_set('include_path', ini_get('include_path').PATH_SEPARATOR.$path);		
	
	require 'data/config.php';
	require 'semcrm_defines.php';
	require 'libs/adodb/adodb-errorhandler.inc.php';
	require 'libs/adodb/adodb.inc.php';
	require 'include/database_pe.php';
	require 'Request.php';
	require 'ScanerFactory.php';
	
	if ((!isset($_REQUEST["_token_"])) || ($_REQUEST["_token_"] != BILLING_TOKEN)) die('Access denied');
	
	$theme_dir = DATA_DIR.'/Base_Theme/templates/default/';
	
	try {
		$request = new Request();
		$scaner = ScanerFactory::getScaner($request);
		
		$scaner->process();
	} catch(ScanException $e){
		echo $e->getMessage();
	}
	
	if(!defined('BOXED')):
		$balance = BALANCE;
	else:
		$balance = 10000;
	endif;	
	
	$count_of_scans = count($scaner->getResult());
	$suffix = $scaner->getSuffix();
	$price = $count_of_scans*0.1;       
	$diff = $price-$balance;
	$billing_token = BILLING_TOKEN;	
?>	

<script>
	var balance = parseFloat("<?php echo $balance; ?>".replace(",", "."));
	var price = parseFloat("<?php echo $price; ?>".replace(",", "."));
	
	if (balance<price){
		dff = parseFloat("<?php echo $diff; ?>".replace(",", "."));
		alert("Не хватает "+dff+"руб. для сканирования всех элементов. Всего элементов - "+<?php echo $count_of_scans; ?>+". Общая сумма - "+price+".");
		$j('#actpanel_<?php echo $suffix; ?>').remove();document.getElementById('miniscan_container').style.bottom = String(Number(document.getElementById('miniscan_container').style.bottom.replace('px',''))+26)+'px';
		if(document.getElementById('miniscan_list').innerHTML == ''){
			$j('#scan_status').empty();
			$j('#miniscan_container').fadeOut();
		}
	}else{
		$j('#actpanel_<?php echo $suffix; ?>').data('token', '<?php echo BILLING_TOKEN; ?>');
		$j('#actpanel_<?php echo $suffix; ?>').data('antigate_key', '<?php echo $scaner->getAntigateKey(); ?>');
		scan_ajaxUpdater("<?php echo $suffix; ?>", <?php echo $count_of_scans; ?>, "<?php echo $_REQUEST['id']; ?>", "<?php echo $scaner->getKey(); ?>");
	}
</script>

	<div id="count" style="display: none;"></div>

	<div class="scan" onmouseover="\$j('#scan_result_tbl_<?php echo $suffix; ?>').draggable({handle: '#draggable_header_<?php echo $suffix; ?>'});" onmousedown="\$j('.above_all').removeClass('above_all'); \$j('#miniscan_container').addClass('above_all'); \$j('.pop_win', this).addClass('above_all');">
		<div class="subpanel">
			<div class="openwin">	
				<a class="restore" title="Детали сканирования" href="#" onclick="\$j('#scan_result_tbl_<?php echo $suffix; ?>').show(); \$j('#scan_result_tbl_<?php echo $suffix; ?>').draggable({handle: '#draggable_header_<?php echo $suffix; ?>'}); return false;"></a>
				<span id="remove_<?php echo $suffix; ?>_span" class="remove" title="Закрыть"></span>
				<a id="remove_<?php echo $suffix; ?>_link" class="remove" title="Закрыть" href="javascript:void(0)" onclick="closeScan('<?php echo $suffix; ?>')"></a>
			</div>		
			<span class="project_name"><?php echo $scaner->getTitle(); ?></span>		
			<div id="pb_<?php echo $suffix; ?>" class="progress_bar">
				<div id="bar_<?php echo $suffix; ?>"></div>
			</div>
			<div class="terminator"></div>
			<div class="loader" style="display: none;">
				<div class="loader_bar"><div id="loading_<?php echo $suffix; ?>" style="display:none;text-align: center; width: 100%"></div></div>
			</div>		
		</div>
		
		<div class="pop_win" id="scan_result_tbl_<?php echo $suffix; ?>">
			<div class="tbl_header" id="draggable_header_<?php echo $suffix; ?>">
				<div class="win_name">Детали сканирования (всего элементов: <?php echo $count_of_scans; ?>)</div>
				<div class="win_actions">
					<a class="hide_details" href="#" title="Свернуть" onclick="\$j('#scan_result_tbl_<?php echo $suffix; ?>').hide();return false;"></a>
					<span id="remove_<?php echo $suffix; ?>_span2" class="remove" title="Закрыть"></span>
					<a id="remove_<?php echo $suffix; ?>_link2" class="remove" title="Закрыть" href="javascript:void(0)" onclick="closeScan('<?php echo $suffix; ?>')"></a>
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
								<table class="results_table" border="0" cellpadding="0" cellspacing="0" id="result_<?php echo $suffix; ?>">
								
								</table>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
