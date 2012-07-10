<?php

namespace app\models;

/**
 * class UsersController
 * 
 */
class Service extends \core\Smrt_Model
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	function getRelation(){
		return array(	"type"	=>	"hasOne",
						"model"	=>	"User"
					);
	}




} // end of UsersController
?>
