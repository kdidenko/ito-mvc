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
	private function __destruct() {
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
	
	/**
	 * DB connection client getter
	 * @return Object of SQLClientInterface implementation 
	 */
	public function getClient() {
		return $this->client;
	}

}

?>