<?php

namespace smrt\core;

/**
 * class Smrt_View
 * 
 */
class Smrt_View
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access private
	 */
	private $var=array();

	/**
	 * 
	 * @access private
	 */
	private $tpl;

	/**
	 * Результат обработки
	 * @access private
	 */
	private $view;
	
	
	/**
	 * Имя шаблона по умолчанию
	 * @access public
	 */
	public $themeName="index";
	
	/**
	 * Содержимое шаблона по умолчанию
	 * @access public
	 */
	public $theme;	

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function __construct( $tpl ) {
		$this->tpl = $tpl;
	} // end of member function __construct

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function render( $action=null ) {
		
		if (!is_null($action)){
			$this->setView( $action );
		}
		
		$this->loadTheme();
		$this->loadTemplate();

		$output = str_replace('{content}', $this->view, $this->theme);	

		if (strpos($output, "[element=")){
			$currentObject=$this;
			$output = preg_replace_callback("/\[element=(\w+)\]/", 
											function($matches) use (&$currentObject){
												return $currentObject->getElement($matches[1]);
											},
											$output);
		}

		foreach($this->var as $key=>$value){
			$output = str_replace($key, $value, $output);
		}
		
		return $output;
	} // end of member function render

	/**
	 * 
	 * Загружаем шаблон для представления
	 * @return 
	 * @access public
	 */
	public function loadTemplate( ) {
		ob_start();
		
		if (!file_exists($this->tpl)){
			throw new \smrt\core\SmrtException("No action");
		}
		
		require_once $this->tpl;
		
		$this->view =ob_get_contents();
		ob_end_clean();	
	} // end of member function loadTemplate

	
	/**
	 * 
	 * Загружаем внешний шаблон
	 * @return 
	 * @access public
	 */
	public function loadTheme( ) {
		ob_start();
		
		if (!file_exists(SMRT_DOCUMENT_ROOT."/theme/".$this->themeName.".tpl")){
			throw new \smrt\core\SmrtException("No theme");
		}		
		
		require_once SMRT_DOCUMENT_ROOT."/theme/".$this->themeName.".tpl";
		
		$this->theme =ob_get_contents();
		ob_end_clean();	
	} // end of member function loadMainTemplate	
	
	/**
	 * 
	 * Устанавливает другой внешний шаблон для предаствления
	 * @return 
	 * @access public
	 */
	public function setTheme( $name ) {
		$this->themeName = $name;
	} // end of member function setMainTemplate	
	
	/**
	 * 
	 * Устанавливаем другой шаблон для представления
	 * @return 
	 * @access public
	 */
	public function setView( $tpl ) {
		$view = SMRT_APP_PATH."/views/".(\smrt\core\Smrt_Registry::getParam("controller"))."/".$tpl.".tpl";		
		$this->tpl = $view;
	} // end of member function setView

	/**
	 * Устанавливаем title
	 * @return 
	 * @access public
	 */
	public function setTitle( $value ) {
		$this->var['{title}'] = $value;
	} // end of member function set	
	
	/**
	 * Устанавливаем переменные 
	 * представления
	 * @return 
	 * @access public
	 */
	public function set( $key, $value ) {
		$this->var[$key] = $value;
	} // end of member function set


	/**
	 * Устанавливаем элементы
	 * @return 
	 * @access public
	 */
	public function getElement( $element ) {
		ob_start();

		if (!file_exists(SMRT_APP_PATH."/views/elements/".$element.".tpl")){
			throw new \smrt\core\SmrtException("No element");
		}		
		
		require_once SMRT_APP_PATH."/views/elements/".$element.".tpl";
		
		$ret =ob_get_contents();
		ob_end_clean();	
		
		return $ret;
	} // end of member function getElement


} // end of Smrt_View
?>
