<?php

namespace smrt\app\models;


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
	 * @access protected
	 */
	protected $conditions=array();	


	/**
	 * 
	 * @return 
	 * @access public
	 */
	public function __construct( $table ) {
		
		\smrt\core\Smrt_Registry::setModel( $this );
		
		$this->db 		= \smrt\core\Smrt_Registry::getConnection();
		$this->table 	= $table;
		$this->relation	= $this->getRelation();
	} // end of member function __construct	

	
	/**
	 * 
	 * Поиск
	 * @return 
	 * @access public
	 */
	public function find( $params = array( "fields"=>false, "conditions"=>array(), "order"=>false, "group"=>false, "limit"=>false ) ) {
		print_r($params);
		if (!$params["fields"]){		
			$params["fields"] = array();			
		}
		
		$this->fields = array_merge($this->fields, $params["fields"]);
		$this->fields = (empty($this->fields)) ? "*" : join(" , ", $this->fields);
		
		$query = "SELECT ".$this->fields." FROM ".$this->table;
		
		if (!empty($params["conditions"])){
			
			$this->conditions = array_merge($this->conditions, $params["conditions"]);
			
			$query.= " WHERE ";
			$keys = array_keys($this->conditions);

			$cond=array();
			
			foreach($keys as $key=>$value){
				$cond[] = $value."=:".$value;
				$this->conditions[":".$value] = $this->conditions[$value];
			}
			
			$query.= join(" AND ", $cond);
		}		
		
		if ($params["group"]){
			$query.= " GROUP BY ".$params["group"];
		}			
		
		if ($params["order"]){
			$query.= " ORDER BY ".$params["order"];
		}

		if ($params["limit"]){
			$query.= " LIMIT ".$params["limit"];
		}		
echo $query;
		return $this->execute( $query, $this->conditions );
	} // end of member function find
	
	
	/**
	 * 
	 * Update
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
	} // end of member function update	
	
	/**
	 * 
	 * Поиск
	 * @return 
	 * @access public
	 */
	public function save( $fields ) {
		
		$query = "INSERT INTO ".$this->table." ( ";
		
		$data=array();
		
		if (!empty($fields)){
			$keys = array_keys($fields);
			
			$query.= join(" , ", $keys);
			$query.= " ) VALUES ( ";
			
			$values=array();
			
			foreach($keys as $key=>$value){
				$values[] = ":".$value;
				$data[":".$value] = $fields[$value];
			}
			
			$query.= join(" , ", $values);
			$query.= " ) ";
		}		

		return $this->execute( $query, $data);
	} // end of member function save	
	
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
			return $this->find(array("conditions"=>array($field=>$arg[0])));
		}
		
		if (strpos($method, "update")!==false){
			$field = strtolower(substr($method, 6));
			return $this->update(array($field=>$arg[0]));
		}		
		
		
		throw new \smrt\core\SmrtException("No method");	
		
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
