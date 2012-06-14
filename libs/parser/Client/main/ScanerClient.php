<?php 
require_once 'CommonScaner.php';
require_once 'SelectedScaner.php';

class ScanException extends Exception{}

/**
 * class ScanerClient
 * 
 */
abstract class ScanerClient
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $request;

	/**
	 * 
	 * @static
	 * @access protected
	 */
	protected static $count_of_keywords;

	/**
	 * 
	 * @static
	 * @access protected
	 */
	protected static $count_of_patterns;

	/**
	 * 
	 * @access protected
	 */
	protected $cacke_key;

	/**
	 * 
	 * @access protected
	 */
	protected $search_engines;

	/**
	 * 
	 * @access protected
	 */
	protected $se_pattern;

	/**
	 * 
	 * @access protected
	 */
	protected $search_engine_name;

	/**
	 * 
	 * @access protected
	 */
	protected $keywords;

	/**
	 * 
	 * @access protected
	 */
	protected $conditions;

	/**
	 * 
	 * @access protected
	 */
	protected $seid;

	/**
	 * 
	 * @access protected
	 */
	const SBD=SBD;
	
	/**
	 * 
	 * @access protected
	 */
	const BILLING_TOKEN=BILLING_TOKEN;	

	/**
	 * 
	 * @access protected
	 */
	protected $result;

	/**
	 * 
	 * @access protected
	 */
	protected $_key;

	/**
	 * 
	 * @access protected
	 */
	protected $individProxy;

	/**
	 * 
	 * @access protected
	 */
	protected $antigate_key;	
	
	/**
	 * 
	 * @access protected
	 */
	protected $parser;
	/**
	 * 
	 *
	 * @param Request request 

	 * @return 
	 * @access public
	 */
	public function __construct( $request ) {
		$this->request = $request;
		$this->memcached = new Memcache();
		$this->memcached->connect('localhost', 11211);
		
		$this->setAntigateKey();
		
		$this->_key = uniqid(BILLING_TOKEN);
	} // end of member function __construct
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getKey( ) {
		return $this->_key;
	} // end of member function getKey	

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function process( ) {
		if (empty($this->request->param["ids"])){
			throw new ScanException("Not selected project");
		}
		
		$this->prepare();
		
		if ((self::$count_of_keywords==0) || (self::$count_of_patterns==0)){
			throw new ScanException("Create pattern or input keywords");
		}
		
		$ic = 0;
		
		foreach($this->request->param["ids"] as $k=>$id){
			$this->setSePattern($id);
			$this->setIndividualProxy();
			$this->setSearchEngines();
			foreach($this->search_engines as $s=>$seid){
				$this->setParameters($seid);
				$this->setKeywords($id);
				foreach($this->keywords as $i=>$keyword){
					$ret[] = array(	"search_engine_name"	=>	$this->search_engine_name,
									"parser"				=>	$this->parser,
									"local"					=>	$this->local_field,
									"keyword"				=>	$keyword["f_keyword"],
									"key_id"				=>	$keyword["id"],
									"last_position"			=>	$keyword["position"],
									"se_id"					=>	$this->seid,
									"request"				=>	html_entity_decode($this->se['f_request']),
									"url"					=>	$keyword["url"],
									"smart_search"			=>	$this->se_pattern["f_smart_search"],
									"proxy_group"			=>	$this->se_pattern['f_use_proxy'],
									"index"					=>	$ic
									);
					$ic++;		
				}
			}
		}
		
		foreach ($ret as $key=>$val){
			$keys_massive[] = $key;
		}
					
		shuffle($keys_massive);
					
		foreach ($keys_massive as $key=>$val){
			$this->result[$key] = $ret[$val];
		}

		$this->memcached->set($this->_key, $this->result, false, 86400);
		
		if (!empty($this->individProxy)){
			$this->saveIndividualProxy();
		}
		
	} // end of member function process

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getAntigateKey( ) {
		return $this->antigate_key;
	} // end of member function getAntigateKey

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getResult( ) {
		return $this->result;
	} // end of member function getResult
		
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getTitle( ) {
		if (count($this->request->param["ids"])>1){
			return "Массовое сканирование";
		}
		
		$url = DB::GetOne("	SELECT f_project_url 
							FROM premium_projects_data_1
							WHERE id=%d", array($this->request->param["ids"][0]));
		
		return $url ;	
	} // end of member function getTitle

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getSuffix( ) {
		if (count($this->request->param["ids"])>1){
			$postfix = implode("_", $this->request->param["ids"]);
			return "mass_".$postfix;
		}
		
		return $suffix =$this->request->param["ids"][0];
	} // end of member function getSuffix


	/**
	 * 
	 *
	 * @param mixed _item 

	 * @return 
	 * @access protected
	 */
	protected function setParser( $item ) {
		
		 $this->parser = $item;
		
		 switch($item){
			case 1:
				$this->parser = "Yandex";	
				break;
			case 2:
			    $this->parser = "Google";    			
			    break;
			case 3:
				$this->parser = "Yahoo";  
				break;  		
			case 4:
				$this->parser = "MSN";    			
				break;
			case 5:
				$this->parser = "Rambler";    			
				break;
			case 6:
				$this->parser = "Mail";    
				break;
		}	
			        
		if ($item=="Bing") $this->parser =  "MSN";
	} // end of member function getParser

	/**
	 * 
	 *
	 * @param int id 

	 * @return 
	 * @access protected
	 */
	protected function setKeywordsCount( $id ) {
		self::$count_of_keywords += DB::GetOne("	SELECT count(id)
														FROM semcrm_keywords_data_1
														WHERE f_project=".$id);
	} // end of member function setKeywordsCount

	/**
	 * 
	 *
	 * @param int id 

	 * @return 
	 * @access protected
	 */
	protected function setPatternsCount( $id ) {
		self::$count_of_patterns += DB::GetOne("	SELECT count(id)
													FROM semcrm_seo_pattern_data_1
													WHERE f_project_name=".$id);
	} // end of member function setPatternsCount

	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function cleanCache( ) {
		if (!file_exists("./tmp/cache/".SBD."/".$this->cache_key)){
			return;
		}
		unlink("./tmp/cache/".SBD."/".$this->cache_key);
	} // end of member function cleanCache

	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function prepare( ) {
		foreach($this->request->param["ids"] as $key=>$id){	
			$this->setKeywordsCount($id);
			$this->setPatternsCount($id);
			
			$this->cache_key = md5(BILLING_TOKEN.$id);	
			
			$this->cleanCache($id);
		}	
	} // end of member function prepare

	/**
	 * 
	 *
	 * @param int id 

	 * @return 
	 * @access protected
	 */
	protected function setParameters( $id ) {
		if (strpos($id, "-")){
			$m = explode("-",$id);
			$se_id = $m[0];
			$loc_id = $m[1];
						
			$this->se = DB::GetRow("SELECT * 
									FROM semcrm_se_pattern_data_1
									WHERE id=".$se_id);
			
			$loc = DB::GetRow("	SELECT t1.*, t2.value as loc
								FROM semcrm_locals t1
								JOIN utils_commondata_tree t2 on t2.id=t1.local 
								WHERE t1.id=%d", array($loc_id));
										
			$this->setParser($this->se["f_parser"]);
			$ind = strtolower($this->parser);
			
			$this->local_field 			= $loc[$ind];
			$this->seid 				= $id;
			$this->search_engine_name 	= $this->se['f_search_engine_name']." (".$loc['loc'].")";
						
		} else {
			$this->se = DB::GetRow("SELECT * 
									FROM semcrm_se_pattern_data_1
									WHERE id=".$id);
						
			$this->setParser($this->se["f_parser"]);
			
			$this->local_field 			= "";	
			$this->seid 				= $id;	
			$this->search_engine_name 	= $this->se['f_search_engine_name'];				
		}
	} // end of member function setProperties

	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function setSearchEngines( ) {
		 $this->search_engines = array_slice(explode("__", $this->se_pattern["f_search_engines"]),1,-1);
	} // end of member function setSearchEngines

	/**
	 * 
	 *
	 * @param int id 

	 * @return 
	 * @access protected
	 */
	protected function setSePattern( $id ) {
		$this->se_pattern = DB::GetRow("	SELECT t1.f_search_engines, t1.f_smart_search, t1.f_use_proxy
											FROM semcrm_seo_pattern_data_1 t1
											WHERE t1.f_project_name=".$id);
	} // end of member function setSePattern

	/**
	 * 
	 *
	 * @param int id 

	 * @return 
	 * @access protected
	 */
	protected function setKeywords( $id ) {
		$this->keywords = DB::GetAll("	SELECT t1.id, t1.f_keyword, t3.f_project_url as url, t2.position, IFNULL(t2.position,0) as position
										FROM semcrm_keywords_data_1 t1
										LEFT JOIN last_keywords_positions t2 ON t2.key_id_se_id = CONCAT( t1.id, '_', '".$this->seid."' )
										JOIN premium_projects_data_1 t3 ON t3.id = t1.f_project
										WHERE t1.f_project=%d AND t1.active=%d ".$this->conditions."
										GROUP BY t1.id", array($id, 1)
									);	
	} // end of member function setKeywords

	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function setAntigateKey( ) {
		$this->antigate_key = DB::GetOne("	SELECT antigate_key
											FROM semcrm_antigate_key");
	} // end of member function setAntigateKey

	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function setIndividualProxy( ) {
		if (!is_null($this->se_pattern['f_use_proxy'])){
			$this->individProxy[] = $this->se_pattern['f_use_proxy'];
		}
	} // end of member function setIndividualProxy

	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function saveIndividualProxy( ) {
		$this->individProxy = array_unique($this->individProxy);	
		foreach($this->individProxy as $gk=>$proxy_group){
			if (file_exists("tmp/proxy/".SBD."/client_proxies_".$proxy_group.".php")){
				include("tmp/proxy/".SBD."/client_proxies_".$proxy_group.".php");
			} else{ 
				$_proxies = DB::GetAll("SELECT * 
										FROM personal_proxy_list
										WHERE group_id=%d", 
										array($proxy_group));
								
				$proxies=array();
				if (!empty($_proxies)){
					foreach($_proxies as $k=>$proxy){
						$proxies[]=array(	'proxy_group'	=>$proxy_group,
											'ip'			=>$proxy['ip'],
											'user'			=>$proxy['user'],
											'password'		=>$proxy['password']);
					}
					
					$p = var_export($proxies,true);
					$pr="<?php \$proxies=".$p."?>";
					$fp = 	fopen("tmp/proxy/".SBD."/client_proxies_".$proxy_group.".php", "w+");
					
					if (flock($fp, LOCK_EX)){
						fwrite($fp, $pr);	
					}
			
					flock($fp, LOCK_UN);
					fclose($fp);
			
				}		
			}
							
			if (count($proxies)!=0){
				$this->memcached->set($this->_key."_".$proxy_group."_proxy", $proxies, false, 86400);	
			}
						
		}
					
		$this->memcached->set($this->_key."_individ_proxy", $this->individProxy, false, 86400);
	} // end of member function saveIndividualProxy




} // end of ScanerClient
?>
