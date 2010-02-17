<?php
require_once 'html/com/ito-global/db/BaseSQLDBService.php';

class BaseSQLDBServiceImpl implements BaseSQLDBService{
	
	private static $db;
	private static $host;
	private static $user;
	private static $password;
	
	private function connection($db, $host, $user, $password){
		$db_connect = mysql_connect($host, $user, $password);
		if(!$db_connect) die(mysql_error());
		$db_selected = mysql_select_db($db, $db_connect);
		if(!$db_selected) die(mysql_error());
		return $db_connect;
	}
	
	private function disconnect($db_connection){
		mysql_close($db_connection);
	}
	
	private function exec($sql){
		//BaseSQLDBServiceImpl::config(); 
		$db_connection = self::connect(self::$db, self::$host, self::$user, self::$password);
		$result = mysql_db_query($sql, $db_connect);
		if(!$result) die(mysql_error());
		self::disconnect($db_connection);
		return $result;
	}
	
	public function execInsert($flds, $vals, $tbl){
		$sql = BaseSQLDBService::INSERT . "INTO" . $tbl . "(" . $flds .") VALUES (" . $vals . ")";
		$result = self::exec($sql);
        return $result;
	}
	
	public function execSelect($flds, $tbl, $cond = null){
		if($cond != NULL) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBService::SELECT . $flds . "FROM" . $tbl 
		$result = self::exec($sql);
		while($row = mysql_fetch_assoc($result)){
			array_push($result, $row);
        }
        return $result;
	}
	
	public function execUpdate($flds, $vals, $tbl, $cond = null){
		if($cond != NULL) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBService::UPDATE . $tbl . "SET" . $flds . "=" . $vals
		$result = self::exec($sql);
		return $result;
	}
	
	public function execDelete($tbl, $cond = null){
		if($cond != NULL) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBService::DELETE . $tbl
		$result = self::exec($sql);
		return $result;
	}
	
}
?>