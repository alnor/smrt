<?php

require_once 'GetProxyStrategy/MemcacheStrategy.php';
require_once 'GetProxyStrategy/FileStrategy.php';

/**
 * class GetProxyStrategy
 * 
 */
abstract class GetProxyStrategy
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $ip;

	/**
	 * 
	 * @access protected
	 */
	protected $user;

	/**
	 * 
	 * @access protected
	 */
	protected $password;

	/**
	 * 
	 * @access protected
	 */
	protected $proxy_index;

	/**
	 * 
	 * @access protected
	 */
	protected $cookie;

	/**
	 * 
	 * @access protected
	 */
	protected $cookie_peace;

	/**
	 * 
	 * @access protected
	 */
	protected $actualProxy=array();
	
	/**
	 * 
	 * @access protected
	 */
	protected $bannedProxy=array();	

	/**
	 * 
	 *
	 * @param array params 

	 * @return 
	 * @access public
	 */
	public function __construct( $params=array() ) {
		if (empty($params)){
			$this->setProxy();
		} else {
			$this->ip 			= $params["ip"];
			$this->user 		= $params["user"];
			$this->password 	= $params["password"];
			$this->proxy_index 	= $params["proxy_index"];
			$this->cookie 		= $params["cookie"];
		}
	} // end of member function __construct

	/**
	 * 
	 *
	 * @return 
	 * @abstract
	 * @access public
	 */
	abstract public function nextProxy( );
	
	/**
	 * 
	 *
	 * @return 
	 * @abstract
	 * @access public
	 */
	abstract public function setProxy( );	

	/**
	 * 
	 *
	 * @return string
	 * @access public
	 */
	public function getIp( ) {
		return $this->ip;
	} // end of member function getIp

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getUser( ) {
		return $this->user;
	} // end of member function getUser

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getPassword( ) {
		return $this->password;
	} // end of member function getPassword

	/**
	 * 
	 * Возвращаем куку
	 * @return 
	 * @access public
	 */
	public function getCookie( ) {
		return $this->cookie;
	} // end of member function getCookie
	
	/**
	 * 
	 * Возвращаем индекс прокси в массиве
	 * @return 
	 * @access public
	 */
	public function getProxyIndex( ) {
		return $this->proxy_index;
	} // end of member function getProxyIndex	
	
	/**
	 * 
	 * Возвращаем прокси, использующуюся в данный момент
	 * @return 
	 * @access public
	 */
	public function getActualProxy( ) {
		return $this->actualProxy;
	} // end of member function getActualProxy
	
	/**
	 * 
	 * Возвращаем прокси, использующуюся в данный момент
	 * @return 
	 * @access public
	 */
	public function getBannedProxy( ) {
		return $this->bannedProxy;
	} // end of member function getActualProxy	
	
	/**
	 * 
	 * Устанавливаем доп. куку
	 * @return 
	 * @access public
	 */
	public function setCookiePeace( $cookie_peace ) {
		$this->cookie_peace = $cookie_peace;
	} // end of member function setCookiePeace
	
	/**
	 * 
	 * Собираем куку для парсера
	 * @return 
	 * @access public
	 */
	public function modCookie( ) {
		$this->cookie .= $this->cookie_peace;
	} // end of member function modCookie	

} // end of GetProxyStrategy
?>
