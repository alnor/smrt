<?php

namespace core\DB\drivers;

/**
 * class Smrt_Mysql
 * 
 */
class Smrt_Mysql extends \core\DB\Smrt_DB
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
	 *
	 * @return 
	 * @access protected
	 */
	protected function init( ) {
				
		$dsn 		= $this->dbConfig["driver"].":dbname=".$this->dbConfig["database"].";host=".$this->dbConfig["host"];
		$this->db 	= new \PDO($dsn, $this->dbConfig["login"], $this->dbConfig["password"]);
		$this->db->exec('SET NAMES utf8');

	} // end of member function init

	/**
	 * 
	 * Поиск по таблице в базе.
	 * Возможные аргументы метода:  "fields", "conditions", "order", "group", "limit"
	 * @param array 
	 * @return 
	 * @access public
	 */
	public function find( $params = array( ) ) {
		if (!isset($params["fields"])){		
			$params["fields"] = array();			
		}
		
		if (!isset($this->fields)){		
			$this->fields = array();			
		}		
		
		$this->fields = array_merge($this->fields, $params["fields"]);
		$this->fields = (empty($this->fields)) ? $this->model.".*" : join(" , ", $this->fields);
		
		$query = "SELECT ".$this->fields." FROM ".$this->table." ".$this->model." ";
		
		if (!empty($this->belongsTo)){
			$query .= $this->getBelongsToRelation();
		}	
				
		if (!empty($this->hasOne)){
			$query .= $this->getHasOneRelation();
		}
		
		if (!empty($this->hasMany)){
			$query .= $this->getHasManyRelation();
		}		
		
		if (isset($params["conditions"])){

			if (!isset($this->conditions)){		
				$this->conditions = array();			
			}			
			
			$this->conditions = array_merge($this->conditions, $params["conditions"]);
			
			$query.= " WHERE ";
			$keys = array_keys($this->conditions);

			$cond=array();
			
			foreach($keys as $key=>$value){
				$cond[] = $value."=?";		
			}

			$query.= join(" AND ", $cond);
		}		
		
		if (isset($params["group"])){
			$query.= " GROUP BY ".$params["group"];
		}			
		
		if (isset($params["order"])){
			$query.= " ORDER BY ".$params["order"];
		}

		if (isset($params["limit"])){
			$query.= " LIMIT ".$params["limit"];
		}		
		//print_r($query);
		return $this->execute( $query, array_values($this->conditions) );
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
				$cond[] = $value."=?";
				$data[] = $fields[$value];
			}
			
			$query.= join(" , ", $cond);
		}
		
		if (!empty($conditions)){
			
			$query.= " WHERE ";
			
			$keys = array_keys($conditions);

			$cond=array();
			
			foreach($keys as $key=>$value){
				$cond[] = $value."=?";
				$data[] = $conditions[$value];
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
				$values[] = "?";
				$data[] = $fields[$value];
			}
			
			$query.= join(" , ", $values);
			$query.= " ) ";
		}		

		return $this->execute( $query, $data);
	} // end of member function save
	
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getHasOneRelation( ) {
		if (!is_array($this->hasOne[0])){
			$this->hasOne = array($this->hasOne);
		}
		
		$str="";
		
		foreach($this->hasOne as $hasOne){
			if (!isset($hasOne["model"])){
				throw new \core\SmrtException("No model");
			}		

			if ($this->is_unbinded($hasOne["model"])){
				continue;
			}			
			
			if (!isset($hasOne["join"])){
				$hasOne["join"] = "JOIN";
			}
			if (!isset($hasOne["table"])){
				$hasOne["table"] = strtolower($hasOne["model"])."s";
			}		
			if (!isset($hasOne["foreign_key"])){
				$hasOne["foreign_key"] = strtolower($this->model)."_id";
			}						
			$model = $hasOne["model"];
			
			$str .= $hasOne["join"]." ".$hasOne["table"]." as ".$hasOne["model"]." ON ".$this->model.".id = ".$hasOne["model"].".".$hasOne["foreign_key"]." ";
			
		}
		
		return $str;
	} // end of member function getHasOneRelation	
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getBelongsToRelation( ) {
		if (!is_array($this->belongsTo[0])){
			$this->belongsTo = array($this->belongsTo);
		}
		
		$str="";
		
		foreach($this->belongsTo as $belongsTo){
			if (!isset($belongsTo["model"])){
				throw new \core\SmrtException("No model");
			}		

			if ($this->is_unbinded($belongsTo["model"])){
				continue;
			}		
			
			if (!isset($belongsTo["join"])){
				$belongsTo["join"] = "JOIN";
			}
			if (!isset($belongsTo["table"])){
				$belongsTo["table"] = strtolower($belongsTo["model"])."s";
			}		
			if (!isset($hasOne["foreign_key"])){
				$belongsTo["foreign_key"] = strtolower($belongsTo["model"])."_id";
			}						
			$model = $belongsTo["model"];
			
			$str .= $belongsTo["join"]." ".$belongsTo["table"]." as ".$belongsTo["model"]." ON ".$belongsTo["model"].".id = ".$this->model.".".$belongsTo["foreign_key"]." ";
			
		}
		
		return $str;
	} // end of member function getBelongsToRelation	
	
	
	/**
	 * 
	 *
	 * @return 
	 * @access public
	 */
	public function getHasManyRelation( ) {
		if (!isset($this->hasMany[0])){
			$this->hasMany = array($this->hasMany);
		}
		
		$str="";
		
		foreach($this->hasMany as $hasMany){
			if (!isset($hasMany["model"])){
				throw new \core\SmrtException("No model");
			}			
			
			if ($this->is_unbinded($hasMany["model"])){
				continue;
			}
			
			if (!isset($hasMany["join"])){
				$hasMany["join"] = "LEFT JOIN";
			}
			if (!isset($hasMany["table"])){
				$hasMany["table"] = strtolower($hasMany["model"])."s";
			}		
			if (!isset($hasMany["foreign_key"])){
				$hasMany["foreign_key"] = strtolower($this->model)."_id";
			}						
			$model = $hasMany["model"];
			
			$str .= $hasMany["join"]." ".$hasMany["table"]." as ".$hasMany["model"]." ON ".$this->model.".id = ".$hasMany["model"].".".$hasMany["foreign_key"]." ";
			
		}
		
		return $str;
	} // end of member function getHasManyRelation		
	
		
		
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
	

} // end of Smrt_Mysql
?>
