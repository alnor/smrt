<?php

require_once 'Smrt_Registry.php';
require_once 'Smrt_Request.php';
require_once 'Smrt_Model.php';
require_once 'Smrt_Controller.php';
require_once 'Smrt_View.php';
require_once 'Smrt_Module.php';
require_once 'Smrt_Exception.php';
require_once 'Smrt_DBConfig.php';
require_once 'app/AppController.php';

/**
 * class Smrt_FrontController
 * 
 */
class Smrt_FrontController
{

	/** Aggregations: */

	/** Compositions: */
	var $m_;

	 /*** Attributes: ***/

	/**
	 * 
	 * @access private
	 */
	private $controller;

	/**
	 * 
	 * @static
	 * @access private
	 */
	private static $instance;



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
	 * @access public
	 */
	public function init( ) {
	} // end of member function init

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
	 * @access public
	 */
	public function dispatch( ) {
		try{
		$request = new \smrt\core\Smrt_Request();
		
		if (is_null($request->getParam("controller"))){
			$request->setParam("controller", "projects");
			//throw new SmrtException(Smrt_LangCommon::get("error", "lost_controller"));
		}
		
		if (is_null($request->getParam("action"))){
			$request->setParam("action", "index");
		}
		
		$class = "\\smrt\\app\\controllers\\".$request->getParam("controller")."Controller";
		
		$filepath = SMRT_APP_PATH."/controllers/".ucfirst($request->getParam("controller"))."Controller.php";
		
		if (!file_exists($filepath)){
			throw new \smrt\core\SmrtException(\smrt\core\Smrt_LangCommon::get("error", "lost_path"));
		}
		
		require_once($filepath);
		
		if (!class_exists($class)){
			throw new \smrt\core\SmrtException(\smrt\core\Smrt_LangCommon::get("error", "lost_class"));
		}
		
		$this->controller = new $class();
		
		
			print($this->invoke());
		}catch(\smrt\core\SmrtException $e){
			\smrt\core\Smrt_Registry::getView()->setTag("{content}", $e->getMessage());
			print(\smrt\core\Smrt_Registry::getView()->render());
		}
	} // end of member function dispatch


	/**
	 * 
	 *
	 * @return 
	 * @access private
	 */
	private function invoke( ) {
		return $this->controller->getView();
	} // end of member function invoke



} // end of Smrt_FrontController
?>
