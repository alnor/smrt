<?php

namespace smrt\app\models;

/**
 * class UsersController
 * 
 */
class Project extends Smrt_Model
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	function setRelation(){
		return array(	"type"	=>	"hasOne",
						"model"	=>	"User"
					);
	}




} // end of UsersController
?>
