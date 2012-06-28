<?php

namespace smrt\app\controllers;

/**
 * class ProjectsController
 * 
 */
use smrt\app\modules\DataBrowser;

class ProjectsController extends AppController
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	function index(){
		
		$this->setTitle("Title for first page");
		$this->set("mainMenu", array(	"href"	=>	"projects/seo",
										"id"	=>	"seo",
										"name"	=>	"Search Engine Optimisation"
									));	
														
		$dataBrowser = $this->loadModule( "DataBrowser" );								
		$dataBrowser->listView("Project", array("fields"=>array("Project.name", "User.name as user_name", "Service.name as service_name"), "conditions"=>array("Project.service_id"=>1)));			
		$this->setTag("{listView}", $dataBrowser->getView());									

	}
	
	function result(){
		//$this->setView("index");
		$this->setTheme("my", "second");
		
		$form = $this->post("form");
		
		if ($form){
			
			$this->setTag("{test}", $form["test"]);
			$this->setTag("{name}", $form["name"]);
			
		} else {
			$this->set("message", "Пустые данные формы");
		}
		
		$this->setTitle("Title for result page");

	}	
	
	function seo(){
		$this->set("mainMenu", array(	"href"	=>	"projects/seo",
										"id"	=>	"seo",
										"name"	=>	"Search Engine Optimisation"
									));	
		
		$this->Project->fields = array("Project.*", "User.name as user_name", "Service.name as service_name");							
		$dataBrowser = $this->loadModule( "DataBrowser" );								
		$dataBrowser->listView("Project", array("conditions"=>array("Project.service_id"=>1)));			
		$this->setTag("{listView}", $dataBrowser->getView());		
	}	
	
	function view(){
		$this->Project->fields = array("Project.*", "User.name as user_name", "Service.name as service_name");							
		
		$id = $this->getParam("id");
		
		$project = $this->Project->find(array("conditions"=>array("Project.id"=>$id)));
		print_r($project);
	}	
	
	function new_project(){
		$services = $this->Service->find();
		$this->set("services", $services);
		
		$form = $this->post("form");
		
		if ($form){
			$this->Project->save($form);
			$this->set("message", "Все удачно чувак");
		}
	}

	
} // end of ProjectsController
?>
