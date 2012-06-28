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
		
		$columns = array_keys($data[0]);	

		$this->controller->setModuleView("listView");
		$this->controller->set("data", $data);
		$this->controller->set("columns", $columns);
	}
	

} // end of AppModel


?>
