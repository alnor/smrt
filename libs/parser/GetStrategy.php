<?php

require_once 'GetStrategy/CurlStrategy.php';
require_once 'GetStrategy/FileGetStrategy.php';


/**
 * class GetStrategy
 * 
 */
abstract class GetStrategy
{

	/** Aggregations: */

	/** Compositions: */
	var $m_;

	 /*** Attributes: ***/

	/**
	 * 
	 * @access private
	 */
	protected $proxyStrategy;
	
	/**
	 * 
	 * @access private
	 */
	protected $useragent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322)";


	/**
	 * 
	 *
	 * @param GetProxyStrategy gps 

	 * @return 
	 * @access public
	 */
	public function setProxyStrategy( GetProxyStrategy $gps ) {
		$this->proxyStrategy = $gps;
	} // end of member function __construct
	
	/**
	 * 
	 *
	 * @param string url 

	 * @return string
	 * @abstract
	 * @access public
	 */
	abstract public function getContent( $url );





} // end of GetStrategy
?>
