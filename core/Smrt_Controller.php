<?php

namespace smrt\app\controllers;

require_once 'Smrt_View.php';

/**
 * class Smrt_Controller
 * 
 */
abstract class Smrt_Controller
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
		$controller = \smrt\core\Smrt_Registry::getParam("controller");
		$action 	= \smrt\core\Smrt_Registry::getParam("action");
		$tpl 		= SMRT_APP_PATH."/views/".$controller."/".$action.".tpl";
		$this->view = new \smrt\core\Smrt_View( $tpl );
	} // end of member function __construct

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getView( ) {
		
		$action = \smrt\core\Smrt_Registry::getParam("action");

		if (!is_callable(array($this, $action))){
			throw new \smrt\core\SmrtException(\smrt\core\Smrt_LangCommon::get("error", "lost_action"));
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
		$post = \smrt\core\Smrt_Registry::getParam($key);
		if (!is_null($post)){
			return $post;
		}
		
		return false;
	} // end of member function getParam	


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
			throw new \smrt\core\SmrtException("No method error");
		}
				
		call_user_func_array(array($this->view, $method), $args);
	} // end of member function __call

} // end of Smrt_Controller


?>
