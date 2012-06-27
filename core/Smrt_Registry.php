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
		$db_config 			= \smrt\core\Smrt_DBConfig::get();
		self::$dsn 			= $db_config["driver"].":dbname=".$db_config["database"].";host=".$db_config["host"];
		self::$connection 	= new \PDO(self::$dsn, $db_config["login"], $db_config["password"]);
		self::$connection->exec('SET NAMES utf8');
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
	 * @param \smrt\core\Smrt_View view 

	 * @return 
	 * @static
	 * @access public
	 */
	public static function setView( \smrt\core\Smrt_View $view ) {
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
	public static function setController( \smrt\app\controllers\Smrt_Controller $controller ) {
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
	 * @return 
	 * @static
	 * @access public
	 */
	public static function requirePlugins( $controller) {
		foreach($controller->loadPlugin() as $plugin){
			require_once SMRT_DOCUMENT_ROOT.'/modules/'.$plugin."/".$plugin.".php";
		}
	} // end of member function getView		

	/**
	 * 
	 *
	 * @param \smrt\app\models\Smrt_Model model 

	 * @return 
	 * @static
	 * @access public
	 */
	public static function setModel( \smrt\app\models\Smrt_Model $model ) {
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
