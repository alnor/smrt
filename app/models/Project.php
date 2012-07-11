<?php

namespace app\models;

/**
 * class UsersController
 * 
 */
class Project extends \core\Smrt_Model
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/
	
	/**
	 * 
	 * @access protected
	 */
	protected $belongsTo = array(	array(	"model"	=>	"User",
											"join"	=>	"LEFT JOIN"),
									array(	"model"	=>	"Service",
											"join"	=>	"LEFT JOIN")
								);

	/**
	 * 
	 * @access protected
	 */
	protected $hasMany = array(	"model"	=>	"Keyword"
								);	
					

	function getTabsList(){
		return array(	array(	"name"=>$this->lang("tab", "Common"),
								"href"=>"projects/commontab"),
						array(	"name"=>$this->lang("tab", "Second"),
								"href"=>"projects/secondtab"),
						array(	"name"=>$this->lang("tab", "Settings"),
								"href"=>"projects/settingstab"),						
					);
	}	

	function getViewContent( $id, $dataBrowser ){
		$this->fields = array("Project.name", "User.name as user_name", "Service.name as service_name", "Project.created_on");															
		$dataBrowser->innerView("Project", $id);
		return $dataBrowser->getView();		
	}


} // end of UsersController
?>
