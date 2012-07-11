<?php

namespace modules;

/**
 * class DataBrowser
 * 
 */
class DataBrowser extends \core\Smrt_Module
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/
	
	/**
	 * Шаблон list view
	 * Определяет общий вид в виде таблицы
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

	/**
	 * Шаблон inner view
	 * Определяет вид элемента
	 * @return 
	 * @access public
	 */
	public function innerView( $model, $id){
		$data = $this->controller->$model->findById( $id );	

		$tabs = $this->controller->getTabsList();

		$this->controller->setModuleView("innerView");
		$this->controller->set("data", $data);
		$this->controller->set("tabs", $tabs);
	}	

} // end of DataBrowser


?>
