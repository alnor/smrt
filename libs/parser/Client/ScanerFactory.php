<?php

require_once 'main/ScanerClient.php';


/**
 * class ScanerFactory
 * 
 */
class ScanerFactory
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	/**
	 * 
	 *
	 * @param Request _request 

	 * @return 
	 * @access public
	 */
	public function getScaner( $request ) {
		$class = ucfirst($request->param["type"])."Scaner";
		return new $class( $request );
	} // end of member function getScaner





} // end of ScanerFactory
?>
