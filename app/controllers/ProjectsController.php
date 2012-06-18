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
		//$test1 = $this->Project->update(array("name"=>"Smrt_sec"), array("id"=>1));
		
		//$a = $this->render("result");

		$this->set("menu", $menu);
		$this->set("test", $test);
		//$this->setTag("{a}", $a);

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
		$projects = $this->Project->find(array("service_id"=>1));
		print_r($projects);
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
