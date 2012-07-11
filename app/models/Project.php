<?php

namespace app\models;

/**
 * class UsersController
 * 
 */
class Project extends \core\Smrt_Model
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/
	
	/**
	 * 
	 * @access protected
	 */
	protected $belongsTo = array(	array(	"model"	=>	"User",
											"join"	=>	"LEFT JOIN"),
									array(	"model"	=>	"Service",
											"join"	=>	"LEFT JOIN")
								);

	/**
	 * 
	 * @access protected
	 */
	protected $hasMany = array(	"model"	=>	"Keyword"
								);	
					

	function getTabsList(){
		return array(	array(	"name"=>"Common",
								"href"=>"projects/tb1"),
						array(	"name"=>"Second",
								"href"=>"projects/tb2"),
					);
	}								


} // end of UsersController
?>
