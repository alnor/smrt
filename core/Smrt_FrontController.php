<?php

namespace core;

/**
 * class Smrt_FrontController
 * 
 */
class Smrt_FrontController extends \core\Smrt_Common
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
	 * Синглтон
	 * Обеспечиваем единственность экземпляра
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
	 * Точка входа
	 * Разбираем параметры, создаем необходимые экземпляры запроса и тд
	 * @return 
	 * @access public
	 */
	public function dispatch( ) {
		try{
			$request = new \core\Smrt_Request();
			
			if (is_null($request->getParam("controller"))){
				$request->setParam("controller", "projects");
				//throw new SmrtException(Smrt_LangCommon::get("error", "lost_controller"));
			}
			
			if (is_null($request->getParam("action"))){
				$request->setParam("action", "index");
			}
			
			$class = "\\app\\controllers\\".$request->getParam("controller")."Controller";
			
			$filepath = SMRT_APP_PATH."/controllers/".ucfirst($request->getParam("controller"))."Controller.php";
			
			if (!file_exists($filepath)){
				throw new \core\Smrt_Exception($this->lang("error", "lost_path"));
			}
			
			require_once($filepath);
			
			if (!class_exists($class)){
				throw new \core\Smrt_Exception($this->lang("error", "lost_class"));
			}
			
			$this->controller = new $class();
		
		
			print($this->invoke());
		}catch(\core\Smrt_Exception $e){
			\core\Smrt_Registry::getView()->setTag("{content}", $e->getMessage());
			print(\core\Smrt_Registry::getView()->render());
		}
	} // end of member function dispatch


	/**
	 * Представление
	 * Получем результат обработки запроса для вывода на экран. 
	 * @return 
	 * @access private
	 */
	private function invoke( ) {
		return $this->controller->getView();
	} // end of member function invoke	


} // end of Smrt_FrontController
?>

