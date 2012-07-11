<?php

namespace app\controllers;

/**
 * class ProjectsController
 * 
 */
use app\modules\DataBrowser;

class ProjectsController extends \app\AppController
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/


	function index(){
		
		$this->setTitle("Title for first page");
		$this->set("mainMenu", array(	"href"	=>	"projects/seo",
										"id"	=>	"seo",
										"name"	=>	\core\Smrt_Lang::get("menu", "Search Engine Optimisation")
									));	
														
		$dataBrowser = $this->loadModule( "DataBrowser" );								
		$dataBrowser->listView("Project", array("fields"=>array("Project.name", "User.name as user_name", "Service.name as service_name"), "conditions"=>array("Project.service_id"=>1)));			
		$this->setTag("{listView}", $dataBrowser->getView());									

	}
	
	function seo(){
		$this->set("mainMenu", array(	"href"	=>	"projects/seo",
										"id"	=>	"seo",
										"name"	=>	"Search Engine Optimisation"
									));	
		
		$this->Project->fields = array("Project.name", "User.name as user_name", "Service.name as service_name", "Project.created_on");							
		$dataBrowser = $this->loadModule( "DataBrowser" );								
		$dataBrowser->listView("Project", array("conditions"=>array("Project.service_id"=>1)));			
		$this->setTag("{listView}", $dataBrowser->getView());		
	}	
	
	function view(){
		
		$id = $this->getParam("id");
		
		if (!$id){
			throw new \core\Smrt_Exception("No id");
		}	
		
		$dataBrowser = $this->loadModule( "DataBrowser" );	
		$result = $this->Project->getViewContent( $id, $dataBrowser );
			
		$this->setTag("{innerView}", $result);
	}	
	
	function new_project(){
		$services = $this->Service->find();
		$this->set("services", $services);
		
		$form = $this->post("form");
		
		if ($form){
			$form["created_on"] = date("Y-m-d");
			$this->Project->save($form);
			$this->set("message", "Все удачно чувак");
		}
	}
	
	function getTabsList(){
		return $this->Project->getTabsList();
	}

	function commontab(){
		$this->setBlankTheme();
	}	
	
	function secondtab(){
	}		
	
	function settingstab(){
	}	
	
} // end of ProjectsController
?>
