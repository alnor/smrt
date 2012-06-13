<?php

namespace smrt\app\controllers;

/**
 * class ProjectsController
 * 
 */
class ProjectsController extends Smrt_Controller
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	function index(){
	}
	
	function result(){
		
		if ($this->post("form")){
			$form = $this->post("form");
			$this->set("{test}", $form["test"]);
			$this->set("{name}", $form["name"]);
		}	

	}	

	
} // end of ProjectsController
?>
