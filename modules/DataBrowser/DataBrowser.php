<?php

namespace smrt\modules;

/**
 * class DataBrowser
 * 
 */
class DataBrowser extends Smrt_Module
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/
	
	/**
	 * 
	 * @return 
	 * @access public
	 */	
	public function listView( $model, $params=array( )){
		$data = $this->controller->$model->find( $params );
		
		$columns = isset($params["fields"]) ? $params["fields"] : false;
		
		if (!$columns){
			$columns = $this->controller->$model->query("SHOW COLUMNS FROM ".$this->controller->$model->getTable());
		}	

		$this->controller->setModuleView("listView");
		$this->controller->set("data", $data);
		$this->controller->set("columns", $columns);
	}

	

} // end of AppModel


?>
