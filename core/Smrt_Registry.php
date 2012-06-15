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
	 * @static
	 * @access private
	 */
	private static $dsn;	
	
	/**
	 * 
	 * @static
	 * @access private
	 */
	private static $connection;	
	

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
	 * @return 
	 * @static
	 * @access public
	 */
	public static function getConnection( ) {
		if (!self::$connection){
			self::getInstance()->setConnection();
		}
		
		return self::$connection;
	} // end of member function getConnection
	
	
	/**
	 * 
	 *
	 * @return 
	 * @static
	 * @access public
	 */
	private static function setConnection( ) {
		$db_config = \smrt\config\DBConfig::get();
		self::$dsn = $db_config["driver"].":dbname=".$db_config["database"].";host=".$db_config["host"];
		self::$connection = new \PDO(self::$dsn, $db_config["login"], $db_config["password"]);
	} // end of member function getConnection
	
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
