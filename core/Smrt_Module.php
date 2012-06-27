<?php

namespace smrt\modules;

/**
 * class Smrt_Request
 * 
 */
use smrt\app\controllers\Smrt_Controller;

class Smrt_Module
{

	/** Aggregations: */

	/** Compositions: */
	
	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $controller;
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function __construct( \smrt\app\controllers\Smrt_Controller $controller) {
		$this->controller = $controller;
	} // end of member function __construct
	
	

} // end of Smrt_Request
?>
