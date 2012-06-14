<?php


/**
 * class MemcacheStrategy
 * 
 */
class MemcacheStrategy extends GetProxyStrategy
{

	/** Aggregations: */

	/** Compositions: */
	var $m_;

	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $_key;

	/**
	 * 
	 * @access private
	 */
	private $memcached;
	
	/**
	 * 
	 * @access private
	 */
	private $seacrhEngine;	


	/**
	 * 
	 *
	 * @param string _key 

	 * @return 
	 * @access public
	 */
	public function __construct( $_key, $searchEngine, array $param=array() ) {
		
		$this->_key = $_key;
		$this->searchEngine = $searchEngine;
		
    	$this->memcached=new BlockingMemcache();    	
   		$this->memcached->connect('localhost', 11211);
   		
   		parent::__construct($param);
   					
	} // end of member function __construct

	/**
	 * 
	 * Меняем прокси
	 * @return 
	 * @access public
	 */
	public function nextProxy( ) {
		
		$proxies = $this->memcached->getAfterLock($this->_key);
			
		$proxies[$this->proxy_index]["bann"][$this->searchEngine]=array("cookie"=>"");		
		
		if ($this->searchEngine == "Yandex"){
	    	$this->bannedProxy[$this->proxy_index] = $proxies[$this->proxy_index];
		}		
			
		$this->memcached->set($this->_key, $proxies, false, 86400);

		$this->memcached->delBlock($this->_key);		
				
		$ind = rand(0, count($proxies)-1);		
		
		$proxy=$proxies[$ind];

		$this->proxy_index 	= 	$ind;
	    $this->ip			=	$proxy["ip"];
	    $this->user			=	$proxy["user"];
	    $this->password		=	$proxy["password"];
	    $this->cookie 		= 	(	isset($proxies[$ind]["bann"][$this->searchEngine]["cookie"]) &&
	    							!empty($proxies[$ind]["bann"][$this->searchEngine]["cookie"])) ? $proxies[$ind]["bann"][$this->searchEngine]["cookie"]."; ".$this->cookie_peace : $this->cookie_peace;		
		
	    $this->actualProxy = $proxy;
	    
	} // end of member function nexProxy

	/**
	 * 
	 * Устанавливаем прокси
	 * @return 
	 * @access public
	 */
	public function setProxy(){
		
		$proxies = $this->memcached->getWithUnlock($this->_key);	
				
		$ind = rand(0, count($proxies)-1);		
		
		$proxy=$proxies[$ind];

		$this->proxy_index 	= 	$ind;
	    $this->ip			=	$proxy["ip"];
	    $this->user			=	$proxy["user"];
	    $this->password		=	$proxy["password"];
	    $this->cookie 		= 	(	isset($proxies[$ind]["bann"][$this->searchEngine]["cookie"]) &&
	    							!empty($proxies[$ind]["bann"][$this->searchEngine]["cookie"])) ? $proxies[$ind]["bann"][$this->searchEngine]["cookie"]."; ": "";		

	    $this->actualProxy = $proxies[$this->proxy_index];
	    
	} // end of member function setProxy
	
	/**
	 * 
	 * Устанавливаем поисковую систему
	 * @return 
	 * @access public
	 */
	public function setSearchEngine($se){
		
		$this->searchEngine = $se;
	    
	} // end of member function setSearchEngine

	
} // end of MemcacheStrategy
?>
