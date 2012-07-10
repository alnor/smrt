<?php

namespace DB;

/**
 * class Smrt_DB
 * 
 */
abstract class Smrt_DB
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $dbConfig;


	/**
	 * 
	 *
	 * @param dbConfig 

	 * @return 
	 * @access public
	 */
	public function __construct( $dbConfig ) {
		$this->dbConfig = $dbConfig;
		$this->init();
	} // end of member function __construct


	/**
	 * 
	 *
	 * @return 
	 * @abstract
	 * @access protected
	 */
	abstract protected function init( );




} // end of Smrt_DB
?>
