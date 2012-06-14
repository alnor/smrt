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
		
		$this->setTitle("Title for first page");
		
		$menu = array("/projects/result"=>"Result", "/users"=>"Users");
		
		$this->set("menu", $menu);
		
		$test = $this->Project->getTest();

		$this->set("{test}", $test);
	}
	
	function result(){
		
		$this->setTheme("second");
		
		if ($this->post("form")){
			$form = $this->post("form");
			$this->set("{test}", $form["test"]);
			$this->set("{name}", $form["name"]);
		}	
		
		$this->setTitle("Title for result page");

	}	

	
} // end of ProjectsController
?>
