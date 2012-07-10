<?php

namespace core;

/**
 * class Smrt_Request
 * 
 */
class Smrt_Request
{

	/** Aggregations: */

	/** Compositions: */
	var $m_;

	 /*** Attributes: ***/

	/**
	 * 
	 * @access private
	 */
	private $params;

	/**
	 * 
	 * @access private
	 */
	private $formParams;
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function __construct( ) {
		$this->init();
		\core\Smrt_Registry::setRequest($this);
	} // end of member function __construct

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function setParam( $key, $value ) {
		$this->params[$key] = $value;
	} // end of member function setParam

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getParam( $key ) {
		if (isset($this->params[$key])){
			return $this->params[$key];
		}
		
		return null;
	} // end of member function getParam


	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getFormParam( $key ) {
		if (isset($this->formParams[$key])){
			return $this->formParams[$key];
		}
		
		return null;
	} // end of member function getParam
	
	/**
	 * 
	 *
	 * @return 
	 * @access private
	 */
	private function init( ) {
		$this->params = $_REQUEST;
		$this->formParams = $_POST;
	} // end of member function init



} // end of Smrt_Request
?>
