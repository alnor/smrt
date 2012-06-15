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
		
		$test = $this->Project->findByName("Semcrm");
		$test1 = $this->Project->update(array("name"=>"Smrt_sec"), array("id"=>1));

		$this->set("menu", $menu);
		$this->set("test", $test);

	}
	
	function result(){
		//$this->setView("index");
		$this->setTheme("my", "second");
		
		$form = $this->post("form");
		
		if (!empty($form["test"])){
			print_r($form);
			$this->setTag("{test}", $form["test"]);
			$this->setTag("{name}", $form["name"]);
			
		} else {
			$this->set("message", "Пустые данные формы");
		}
		
		$this->setTitle("Title for result page");

	}	

	
} // end of ProjectsController
?>
