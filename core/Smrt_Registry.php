<?php

namespace core;

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
		$db_config = \core\Smrt_DBConfig::get();
		$factory = new \core\Smrt_DBFactory($db_config);
		self::$connection= $factory->get();

	} // end of member function getConnection
	
	/**
	 * 
	 *
	 * @param Smrt_Request request 

	 * @return 
	 * @static
	 * @access public
	 */
	public static function setRequest( \core\Smrt_Request $request ) {
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
	 * @param \smrt\core\Smrt_View view 

	 * @return 
	 * @static
	 * @access public
	 */
	public static function setView( \core\Smrt_View $view ) {
		return self::getInstance()->set("viewObject", $view);
	} // end of member function setView		

	/**
	 * @return 
	 * @static
	 * @access public
	 */
	public static function getView( ) {
		return self::getInstance()->get("viewObject");
	} // end of member function getView	

	
	/**
	 * 
	 *
	 * @param \smrt\core\Smrt_Controller controller 

	 * @return 
	 * @static
	 * @access public
	 */
	public static function setController( \core\Smrt_Controller $controller ) {
		//self::getInstance()->requirePlugins($controller);
		return self::getInstance()->set("controllerObject", $controller);
	} // end of member function setView		

	/**
	 * @return 
	 * @static
	 * @access public
	 */
	public static function getController( ) {
		return self::getInstance()->get("controllerObject");
	} // end of member function getView	
	

	/**
	 * 
	 *
	 * @param \smrt\app\models\Smrt_Model model 

	 * @return 
	 * @static
	 * @access public
	 */
	public static function setModel( \core\Smrt_Model $model ) {
		return self::getInstance()->set("modelObject", $model);
	} // end of member function setView		

	/**
	 * @return 
	 * @static
	 * @access public
	 */
	public static function getModel( ) {
		return self::getInstance()->get("modelObject");
	} // end of member function getView	
		
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
	 * @static
	 * @access public
	 */
	public static function getFormParam( $key ) {
		return self::getInstance()->get("request")->getFormParam( $key );
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
