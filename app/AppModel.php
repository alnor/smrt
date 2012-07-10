<?php

namespace app;

/**
 * class AppModel
 * 
 */
class AppModel extends \core\Smrt_Model
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access private
	 */
	private $view;
	
	function common( ){
		$mainMenu = array();
		
		$mainMenu = array(	array(	"name"	=>"Главная",
									"href"	=>"/",
									"id"	=>"main"
								),
							array(	"name"	=>"Проекты",
									"href"	=>"/projects",
									"id"	=>"projects"
								),
							array(	"name"	=>"CRM",
									"submenu"=>array(	array(	"name"	=>"Компании",
																"href"	=>"/projects",
																"id"	=>"projects"
														),	
														array(	"name"	=>"Контакты",
																"href"	=>"/projects",
																"id"	=>"projects"
														),																				
												)
								)																
							);
		
		$this->set("mainMenu", $mainMenu);
		
		$this->setTag("{title}", "Smrt-тестируем фреймворк");
	}

	

} // end of AppModel


?>
