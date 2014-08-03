<?php

// "the singleton pattern" in PHP

class DB {
	private static $_instance = null; // to store instance of our database
	private $_pdo, 
			$_query, 
			$_error = false, 
			$_results, 
			$_count = 0;

	private function __construct() {
		try {
			$this->_pdo = new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
			
		} catch(PDOException $e) {
			die($e->getMessage());
		}
	}

	// check if we have already instantiated our object (and therefore connected)
	// if instantiated return our instance
	// avoid connecting more than once
	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new DB();
		}
		return self::$_instance;
	}

	public function query($sql, $params = array()) {
		$this->_error = false; // we don't want to return an error from a previous query

		$this->_query = $this->_pdo->prepare($sql)
		
		// check there parameters that we will bind
		$x = 1;
		if(count($params)) {
			foreach($params as $param) {
				$this->_query->bindValue($x, $param);
				$x++;
			}
		}

		// execute the query regardless if there are any paramaters
		if($this->_query->execute()) {
			$this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
			$this->_count = $this->_query->rowCount();
		} else {
			$this->_error = true;
		}
		

		return $this; // allows us to chain on error() method
	}

	public function action($action, $table, $where = array()) {
		if(count($where) === 3) {  //need field, operator, value
			$operators = array('=', '>', '<', '>=', '<='); // set allowed operators

			$field 		= $where[0];
			$operator 	= $where[1];
			$value 		= $where[2];

			if(in_array($operator, $operators)) { // check if operator is allowed
				$sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";

				if(!$this->query($sql, array($value))->error()) {
					return $this;
				}
			}
		}

		return false;
	}

	public function get($table, $where) {
		return $this->action('SELECT *', $table, $where);
	}

	public function delete($table, $where) {
		return $this->action('DELETE', $table, $where);
	}

	public function insert($table, $fields = array()) {

		$keys = array_keys($fields);
		$values = "";
		$x = 1;

		foreach($fields as $field) {
			$values .= "?";
			if($x < count($fields)) {
				$values .= ", ";
			}
			$x++;
		}

		$sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) .  "`) VALUES ({$values})"; // INSERT INTO users (`username`, `password`, `salt`) VALUES (?, ?, ?)

		// perform the query
		if(!$this->query($sql, $fields)->error()) {
			echo "success";
			return true;
		}

		return false;
	}

	public function update($table, $id, $fields) {
		$set = "";
		$x = 1;

		foreach($fields as $name => $value) {
			$set .= "{$name} = ?";
			if($x < count($fields)) {
				$set .= ", ";
			}
			$x++;

		}

		$sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

		// perform the query
		if(!$this->query($sql, $fields)->error()) {
			return true;
		}

		return false;
	}

	public function results() {
		return $this->_results;
	}

	// public function first() {
	// 	return $this->results()[0]; // need newer PHP version for this
	// }

	public function error() {
		return $this->_error;
	}

	// count of results (if any) returned
	public function count() {
		return $this->_count;
	}
}