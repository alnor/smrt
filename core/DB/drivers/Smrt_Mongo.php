<?php

namespace core\DB\drivers;

/**
 * class Smrt_Mongo
 * 
 */
class Smrt_Mongo extends \core\DB\Smrt_DB
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access private
	 */
	private $dbh;

	/**
	 * 
	 * @access private
	 */
	private $db;


	/**
	 * 
	 *
	 * @param array params 

	 * @return 
	 * @access public
	 */
	public function find( $params ) {
	} // end of member function find


	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function init( ) {
		$this->dbh = new \Mongo("mongodb://{$this->dbConfig["login"]}:{$this->dbConfig["password"]}@{$this->dbConfig["host"]}:{$this->dbConfig["port"]}/{$this->dbConfig["database"]}");
		
		$database = $this->dbConfig["database"];
		
		$this->db = $this->dbh->$database;
	} // end of member function init




} // end of Smrt_Mongo
?>
