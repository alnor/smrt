<?php

namespace smrt\config;

/**
 * class DBConfig
 * 
 */
class DBConfig
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function get( ) {
		
		return array(	'driver' => 'mysql',
						'persistent' => false,
						'host' => 'localhost',
						'login' => 'root',
						'password' => 'root12',
						'database' => 'smrt',
						'prefix' => '',
			);
			
	} // end of member function get





} // end of DBConfig
?>