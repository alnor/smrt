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
	public function __construct( $tpl ) {
		$this->tpl = $tpl;
	} // end of member function __construct

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function render( ) {
		$this->loadTemplate();

		foreach($this->var as $key=>$value){
			$this->view = str_replace($key, $value, $this->view);
		}
		
		return $this->view;
	} // end of member function render

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function loadTemplate( ) {
		ob_start();
		require_once $this->tpl;
		$this->view =ob_get_contents();
		ob_end_clean();	
	} // end of member function loadTemplate

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function setView( $tpl ) {
		$this->tpl = $tpl;
	} // end of member function setView

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function set( $key, $value ) {
		$this->var[$key] = $value;
	} // end of member function set





} // end of Smrt_View
?>
