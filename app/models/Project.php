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
					



} // end of UsersController
?>
