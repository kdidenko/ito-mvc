<?php

require_once 'com/itoglobal/db/sql/mysql/SQLClient.php';

class DBClientHandler {
	
	private $client = null;
	
	private $connection = null;
	
	private static $instance = null;
	
	/**
	 * Private constructor 
	 */
	private function __construct() {
	}
	
	/**
	 * Private destructor handling the connection to be properly closed  
	 */
	public function __destruct() {
		if ($this->client != null && $this->connection != null) {
			$this->client->disconnect ( $this->connection );
		}
	}
	
	/**
	 * DB Connection initialization method.
	 * @param string $db_name the database name
	 * @param string $db_host the database host
	 * @param string $db_user the username
	 * @param string $db_password the password
	 * @param string $charset [Optional] the client charset
	 */
	public function init($db_name, $db_host, $db_user, $db_password, $charset = null) {
		$this->client = new SQLClient ();
		$this->connection = $this->client->connect ( $db_name, $db_host, $db_user, $db_password, $charset );
	}
	
	/**
	 * Object instance getter
	 * @return the DBClientHandler Object
	 */
	public static function getInstance() {
		return (self::$instance === NULL) ? self::$instance = new self () : self::$instance;
	}
	
	/* (non-PHPdoc)
	 * @see com/itoglobal/db/sql/SQLClientInterface#exec($sql)
	 */
	public function exec($sql) {
		$ref = $this->client->exec ( $sql, $this->connection );
		$result = array ();
		while ( $row = mysql_fetch_assoc ( $ref ) ) {
			array_push ( $result, $row );
		}
		return $result;
	}
	
	/* (non-PHPdoc)
	 * @see com/itoglobal/db/sql/SQLClientInterface#execInsert($fields, $values, $into)
	 */
	public function execInsert($fields, $values, $into) {
		return $this->client->execInsert ( $fields, $values, $into, $this->connection );
	}
	
	/* (non-PHPdoc)
	 * @see com/itoglobal/db/sql/SQLClientInterface#execSelect($fields, $from, $where, $groupBy, $orderBy, $limit)
	 */
	public function execSelect($fields, $from, $where, $groupBy, $orderBy, $limit) {
		return $this->client->execSelect ( $fields, $from, $where, $groupBy, $orderBy, $limit, $this->connection );
	}
	
	/* (non-PHPdoc)
	 * @see com/itoglobal/db/sql/SQLClientInterface#execUpdate($fields, $from, $vals, $where, $orderBy, $limit)
	 */
	public function execUpdate($fields, $from, $vals, $where, $orderBy, $limit) {
		return $this->client->execUpdate ( $fields, $from, $vals, $where, $orderBy, $limit, $this->connection );
	}
	
	/* (non-PHPdoc)
	 * @see com/itoglobal/db/sql/SQLClientInterface#execDelete($from, $where, $orderBy, $limit)
	 */
	public function execDelete($from, $where, $orderBy, $limit) {
		return $this->client->execDelete ( $from, $where, $orderBy, $limit, $this->connection );
	}

}

?>