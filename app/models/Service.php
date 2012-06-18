<?php

namespace smrt\app\models;

/**
 * class UsersController
 * 
 */
class Service extends Smrt_Model
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
