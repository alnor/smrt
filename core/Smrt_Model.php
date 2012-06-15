<?php

namespace smrt\app\models;

require_once 'config/database.ini.php';

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
	protected $relation;	

	/**
	 * 
	 * @return 
	 * @access public
	 */
	public function __construct(  ) {
		$this->db 		= \smrt\core\Smrt_Registry::getConnection();
		$this->table 	= \smrt\core\Smrt_Registry::getParam("controller");
		$this->relation	= $this->getRelation();
	} // end of member function __construct	

	
	/**
	 * 
	 * Поиск
	 * @return 
	 * @access public
	 */
	public function find( $conditions=array() ) {
		
		$query = "SELECT * FROM ".$this->table;
		
		if (!empty($conditions)){
			$query.= " WHERE ";
			$keys = array_keys($conditions);

			$cond=array();
			
			foreach($keys as $key=>$value){
				$cond[] = $value."=:".$value;
				$conditions[":".$value] = $conditions[$value];
			}
			
			$query.= join(" AND ", $cond);
		}

		return $this->execute( $query, $conditions );
	} // end of member function find
	
	
	/**
	 * 
	 * Поиск
	 * @return 
	 * @access public
	 */
	public function update( $fields, $conditions=array() ) {
		
		$query = "UPDATE ".$this->table." SET ";
		
		$data=array();
		
		if (!empty($fields)){
			$keys = array_keys($fields);

			$cond=array();
			
			foreach($keys as $key=>$value){
				$cond[] = $value."=:".$value;
				$data[":".$value] = $fields[$value];
			}
			
			$query.= join(" , ", $cond);
		}
		
		if (!empty($conditions)){
			
			$query.= " WHERE ";
			
			$keys = array_keys($conditions);

			$cond=array();
			
			foreach($keys as $key=>$value){
				$cond[] = $value."=:".$value;
				$data[":".$value] = $conditions[$value];
			}
			
			$query.= join(" , ", $cond);
		}		

		return $this->execute( $query, $data);
	} // end of member function find	
	

	/**
	 * 
	 * Магический поиск
	 * @return 
	 * @access public
	 */
	public function __call( $method, $arg ) {
		
		if (is_array($arg[0])){
			throw new \smrt\core\SmrtException("Args array");
		}		

		if (strpos($method, "findBy")!==false){
			$field = strtolower(substr($method, 6));
			return $this->find(array($field=>$arg[0]));
		}
		
		if (strpos($method, "update")!==false){
			$field = strtolower(substr($method, 6));
			return $this->update(array($field=>$arg[0]));
		}		
		
		
		throw new \smrt\core\SmrtException("No method");	
		
	} // end of member function __call	
	
	/**
	 * 
	 * Простой запрос
	 * @return 
	 * @access public
	 */
	public function query( $query, $values ) {
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
		
		return $stmt->fetchAll();
		
	} // end of member function execute
	
} // end of Smrt_Model
?>
