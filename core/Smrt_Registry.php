<?php

namespace smrt\core;

/**
 * class Smrt_Registry
 * 
 */
class Smrt_Registry
{

	/** Aggregations: */

	/** Compositions: */
	var $m_;

	 /*** Attributes: ***/

	/**
	 * 
	 * @static
	 * @access private
	 */
	private static $instance;
	
	/**
	 * 
	 * @static
	 * @access private
	 */
	private static $values=array();	
	

	/**
	 * 
	 *
	 * @return 
	 * @access private
	 */
	private function __construct( ) {
	} // end of member function __construct	

	/**
	 * 
	 *
	 * @return 
	 * @static
	 * @access public
	 */
	public static function getInstance( ) {
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	} // end of member function getInstance

	/**
	 * 
	 *
	 * @param Smrt_Request request 

	 * @return 
	 * @static
	 * @access public
	 */
	public static function setRequest( \smrt\core\Smrt_Request $request ) {
		return self::getInstance()->set("request", $request);
	} // end of member function setRequest

	/**
	 * 
	 *
	 * @return 
	 * @static
	 * @access public
	 */
	public static function getRequest( ) {
		return self::getInstance()->get("request");
	} // end of member function getRequest
	
	/**
	 * 
	 *
	 * @return 
	 * @static
	 * @access public
	 */
	public static function getParam( $key ) {
		return self::getInstance()->get("request")->getParam( $key );
	} // end of member function getRequest	

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function set( $key, $value ) {
		$this->values[$key] = $value;
	} // end of member function set

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function get( $key) {
		if (isset($this->values[$key])){
			return $this->values[$key];
		}
		
		return null;
	} // end of member function get



} // end of Smrt_Registry
?>
