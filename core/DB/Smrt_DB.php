<?php

namespace core\DB;

/**
 * class Smrt_DB
 * 
 */
abstract class Smrt_DB
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/

	/**
	 * 
	 * @access protected
	 */
	protected $dbConfig;

	
	/**
	 * 
	 * @access protected
	 */
	protected $table;
	
	/**
	 * 
	 * @access protected
	 */
	protected $model;
	
	/**
	 * 
	 * @access protected
	 */
	protected $modelObject;	

	
	/**
	 * 
	 * @access protected
	 */
	protected $conditions=array();


	/**
	 * 
	 * @access protected
	 */
	protected $unbinded=array();		
		
	/**
	 * 
	 * @access protected
	 */
	protected $hasOne;

	/**
	 * 
	 * @access protected
	 */
	protected $hasMany;	
	
	/**
	 * 
	 * @access protected
	 */
	protected $belongsTo;	

	/**
	 * 
	 *
	 * @param dbConfig 

	 * @return 
	 * @access public
	 */
	public function __construct( $dbConfig ) {
		$this->dbConfig = $dbConfig;
		$this->modelObject	= \core\Smrt_Registry::getModel();
		$this->belongsTo	= $this->modelObject->getBelongsToRelation();
		$this->hasMany		= $this->modelObject->getHasManyRelation();
		$this->hasOne		= $this->modelObject->getHasOneRelation();
		$this->init();
	} // end of member function __construct
	
	

	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function setModel( $model ) {
		$this->model = $model;
	} // end of member function setModel
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function setTable( $table ) {
		$this->table = $table;
	} // end of member function setTable
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getTable( ) {
		return $this->table;
	} // end of member function setTable
		

		
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function unbind( $models ) {
		
		if (!is_array($models)){
			$models = array($models);
		}
		
		foreach($models as $model){
			$this->unbinded[] = $model;
		}	
		
	} // end of member function unbind	
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function is_unbinded( $model ) {
		return in_array( $model, $this->unbinded );
	} // end of member function unbind	
	

	/**
	 * 
	 *
	 * @return 
	 * @abstract
	 * @access protected
	 */
	abstract protected function init( );




} // end of Smrt_DB
?>
