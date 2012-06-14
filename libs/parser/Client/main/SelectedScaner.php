<?php

/**
 * class SelectedScaner
 * 
 */
class SelectedScaner extends ScanerClient
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/



	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function setKeywordsCount( ) {
		if (!isset($this->request->param["selected_keys"])){
			parent::setKeywordsCount();
			return;
		} 
		
		parent::$count_of_keywords = count($this->request->param["selected_keys"]);

	} // end of member function setKeywordsCount

	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function setPatternsCount( ) {
		if (!isset($this->request->param["selected_se"])){
			parent::setPatternsCount();
			return;
		} 
		parent::$count_of_patterns = count($this->request->param["selected_se"]);

	} // end of member function setPatternsCount

	/**
	 * 
	 *
	 * @return 
	 * @access protected
	 */
	protected function setSearchEngines( ) {
		if (!isset($this->request->param["selected_se"])) {
			parent::setSearchEngines();
			return;
		}
		$this->search_engines = $this->request->param["selected_se"];
	} // end of member function setSearchEngines

	/**
	 * 
	 *
	 * @param int id 

	 * @return 
	 * @access protected
	 */
	protected function setKeywords( $id ) {
		if (isset($this->request->param["selected_keys"])) {
			$keys = implode(",", $this->request->param["selected_keys"]);
			$this->conditions = "AND t1.id IN (".$keys.")";
		}
		parent::setKeywords($id);
	} // end of member function setKeywords




} // end of SelectedScaner
?>
