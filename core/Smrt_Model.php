<?php

namespace core;


/**
 * class Smrt_Model
 * 
 */
abstract class Smrt_Model
{

	/** Aggregations: */

	/** Compositions: */

	 /*** Attributes: ***/
	
	/**
	 * 
	 * @access private
	 */
	private $db;
		
		
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
	 * @return 
	 * @access public
	 */
	public function __construct( ) {
		
		\core\Smrt_Registry::setModel( $this );
		
		$this->db = \core\Smrt_Registry::getConnection();

	} // end of member function __construct	

	
	/**
	 * 
	 * Магический поиск
	 * @return 
	 * @access public
	 */
	public function __call( $method, $arg ) {

		return call_user_func_array(array($this->db, $method), $arg);
		
		/*
		if (is_array($arg[0])){
			throw new \core\SmrtException("Args array");
		}		

		if (strpos($method, "findBy")!==false){
			$field = strtolower(substr($method, 6));
			return $this->find(array("conditions"=>array($field=>$arg[0])));
		}
		
		if (strpos($method, "update")!==false){
			$field = strtolower(substr($method, 6));
			return $this->update(array($field=>$arg[0]));
		}		
		
		
		throw new \core\SmrtException("No method");	
		*/
	} // end of member function __call	
	
	
	/**
	 * 
	 * Магический установщик
	 * @return 
	 * @access public
	 */
	public function __set( $property, $value ) {
		
		$this->db->$property = $value;	
		
	} // end of member function __set	
	
	
	/**
	 * 
	 * Магический получатель
	 * @return 
	 * @access public
	 */
	public function __get( $property ) {
		
		return $this->db->$property;
		/*
		if (isset($this->$property)){
			return $this->$property;
		}
		
		return null;	
		*/
	} // end of member function __set	
	
	
	/**
	 * 
	 * Возвращает отношение belongs to
	 * @return 
	 * @access public
	 */
	public function getBelongsToRelation( ) {
		
		return $this->belongsTo;	
		
	} // end of member function getBelongsToRelation	
	
	/**
	 * 
	 * Возвращает отношение has many
	 * @return 
	 * @access public
	 */
	public function getHasManyRelation( ) {
		
		return $this->hasMany;	
		
	} // end of member function getHasManyRelation
	
	/**
	 * 
	 * Возвращает отношение has one
	 * @return 
	 * @access public
	 */
	public function getHasOneRelation( ) {
		
		return $this->hasOne;	
		
	} // end of member function getHasOneRelation
		
		
} // end of Smrt_Model
?>
