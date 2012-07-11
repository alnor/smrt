<?php

namespace core;

/**
 * class Smrt_Controller
 * 
 */
abstract class Smrt_Controller extends \core\Smrt_Common
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access private
	 */
	private $view;


	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function __construct( ) {

		\core\Smrt_Registry::setController( $this );
				
		$controller = \core\Smrt_Registry::getParam("controller");
		$action 	= \core\Smrt_Registry::getParam("action");
		$tpl 		= SMRT_APP_PATH."/views/".$controller."/".$action.".tpl";
		
		$this->view = new \core\Smrt_View( $tpl );
	} // end of member function __construct

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getView( ) {
		
		$action = \core\Smrt_Registry::getParam("action");

		if (!is_callable(array($this, $action))){
			throw new \core\Smrt_Exception($this->lang("error", "lost_action"));
		}
		
		if (method_exists($this, "common")){
			$this->common();
		}		
		
		$this->$action();
		
		return $this->view->render();
	} // end of member function getView

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function set( $key, $value ) {
		$this->view->set($key, $value);
	} // end of member function set

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function post( $key ) {
		$post = \core\Smrt_Registry::getFormParam($key);
		
		if (!is_null($post)){
			return $post;
		}
		
		return false;
	} // end of member function post	


	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getParam( $key ) {
		$ret = \core\Smrt_Registry::getParam($key);
		
		if (!is_null($ret)){
			return $ret;
		}
		
		return false;
	} // end of member function getParam
	
	/**
	 * Загружаем модуль.
	 * Загрузка ленивая. Модуль подгружается только в случае вызова этого метода.
	 * Устанавливает имя модуля для использования в классе Smrt_View
	 *
	 * @return объект модуля
	 * @access public
	 */
	public function loadModule( $module ){
		require_once SMRT_DOCUMENT_ROOT."/modules/".$module."/".$module.".php";
		$moduleName = "\\modules\\".$module;
		$moduleObj = new $moduleName( $this );		
		$this->setModuleName( $module );
		return $moduleObj;
	}	
		
	/**
	 * 
	 * Перенаправляем запросы в Smrt_View. 
	 * Любые действия в action, связанные с представлением, установкой шаблонов и других тегов - отлавливаются тут
	 * 
	 * @return 
	 * @access public
	 */
	public function __call( $method, $args=array() ) {
		if (!is_callable(array($this->view, $method))){
			throw new \core\Smrt_Exception($this->lang("error", "No method"));
		}
				
		return call_user_func_array(array($this->view, $method), $args);
	} // end of member function __call
	
	
	/**
	 * 
	 * Перенаправляем запросы в Smrt_View. 
	 * Любые действия в action, связанные с представлением, установкой шаблонов и других тегов - отлавливаются тут
	 * 
	 * @return 
	 * @access public
	 */
	public function __get( $property ) {
		
		$class = "\\app\\models\\".ucfirst($property);
		
		if (isset($this->$class)){
			return $this->$class;
		}
	
		$filepath = SMRT_APP_PATH."/models/".(ucfirst($property)).".php";
		
		if (!file_exists($filepath)){
			throw new \core\Smrt_Exception($this->lang("error", "lost_path"));
		}
		
		require_once $filepath;
		
		if (!class_exists($class)){
			throw new \core\Smrt_Exception($this->lang("error", "lost_class"));
		}
		
		$table =  strtolower($property)."s";
		$this->$property = new $class( );
		$this->$property->setModel( $property );
		$this->$property->setTable( $table );
		
		return $this->$property;
	} // end of member function __get	
	

} // end of Smrt_Controller


?>
