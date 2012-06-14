<?php

/**
 * class Request
 * 
 */
class Request
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access private
	 */
	public $param=array();


	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function __construct( ) {
		$this->init();
	} // end of member function __construct

	/**
	 * 
	 *
	 * @param string key 

	 * @return 
	 * @access public
	 */
	public function getParam( $key ) {
		return $this->param[$key];
	} // end of member function getParam



	/**
	 * 
	 *
	 * @return 
	 * @access private
	 */
	private function init( ) {
		if (!isset($_REQUEST)){
			throw new ScanException("Empty request");
		}
		
		if (!isset($_REQUEST["id"])){
			throw new ScanException("Project not selected");
		}
		
		$this->param = $_REQUEST;
		$this->param["ids"] = explode(",", $this->param["id"]);
		
		if (isset($this->param['selected_keys']) && !empty($this->param['selected_keys'])){
			$this->param['selected_keys'] = explode(",", $this->param['selected_keys']);
		}
		
		if (isset($this->param['selected_se']) && !empty($this->param['selected_se'])){
			$this->param['selected_se'] = explode(",", $this->param['selected_se']);
		}
	} // end of member function init



} // end of Request
?>
