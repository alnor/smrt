<?php

namespace core;

/**
 * class DBFactory
 * 
 */

class Smrt_DBFactory
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
	} // end of member function __construct

	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function get( ) {
		switch ($this->dbConfig["driver"]){
			case "mysqli":
			case "mysql":
				return new \DB\drivers\Smrt_Mysql( $this->dbConfig );
				break;
			case "mongodb":
				return new \DB\drivers\Smrt_Mongo( $this->dbConfig );
				break;
		}
	} // end of member function get





} // end of DBFactory
?>
