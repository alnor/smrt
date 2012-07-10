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
	 * @access protected
	 */
	protected $conditions=array();	


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
		
		$this->db->$method( $arg );
		
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
		
		$this->$property = $value;	
		
	} // end of member function __set	
	
	
	/**
	 * 
	 * Магический получатель
	 * @return 
	 * @access public
	 */
	public function __get( $property ) {
		
		if (isset($this->$property)){
			return $this->$property;
		}
		
		return null;	
		
	} // end of member function __set	
		
		
	/**
	 * 
	 * Простой запрос
	 * @return 
	 * @access public
	 */
	public function query( $query, $values=array() ) {
		return $this->execute( $query, $values );		
	} // end of member function query
		

	/**
	 * 
	 * Извлечение
	 * @return 
	 * @access public
	 */
	public function execute( $query, $values ) {

		try{
			
			$stmt = $this->db->prepare($query);
			$stmt->execute($values);
			
		} catch(PDOException $e) {
			
			echo $e->getMessage();
			
		}
		
		return $stmt->fetchAll( \PDO::FETCH_ASSOC );
		
	} // end of member function execute
	
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
	public function getColumns( ) {
		$ret = $this->query("SELECT * FROM information_schema.columns WHERE table_name = '".$this->table."' ");
		return $ret;
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

		
} // end of Smrt_Model
?>
