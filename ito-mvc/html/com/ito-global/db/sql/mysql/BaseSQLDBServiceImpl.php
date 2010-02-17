<?php
require_once 'html/com/ito-global/db/BaseSQLDBService.php';

class BaseSQLDBServiceImpl implements BaseSQLDBService{
	
	public static $db;
	public static $host;
	public static $user;
	public static $password;
	
	private function connect($db, $host, $user, $password){
		$db_connect = mysql_connect($host, $user, $password);
		if(!$db_connect) die(mysql_error());
		$db_selected = mysql_select_db($db, $db_connect);
		if(!$db_selected) die(mysql_error());
		return $db_connect;
	}
	
	/* 
	public function config(){
		include_once '';	//include file with DB configurations 
		BaseSQLDBServiceImpl::$db = $db_name;
		BaseSQLDBServiceImpl::$host = $db_host; 
		BaseSQLDBServiceImpl::$user = $db_user;
		BaseSQLDBServiceImpl::$password = $db_password;
	}
	*/
	
	private function close($db_connect){
		mysql_close($db_connect);
	}
	
	public function exec($sql){
		$db_connect = BaseSQLDBServiceImpl::connect(BaseSQLDBServiceImpl::$db, BaseSQLDBServiceImpl::$host, BaseSQLDBServiceImpl::$user, BaseSQLDBServiceImpl::$password);
		$result = mysql_db_query($sql, $db_connect);
		if(!$result) die(mysql_error());
		BaseSQLDBServiceImpl::close($db_connect);
		return $result;
	}
	
	public function execInsert($flds, $vals, $tbl){
		$sql = BaseSQLDBServiceImpl::INSERT . "INTO" . $tbl . "(" . $flds .") VALUES (" . $vals . ")";
		$result = BaseSQLDBServiceImpl::exec($sql);
        return $result;
	}
	
	public function execSelect($flds, $tbl, $cond = null){
		if(isset($cond)) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBServiceImpl::SELECT . $flds . "FROM" . $tbl 
		$result = BaseSQLDBServiceImpl::exec($sql);
		while($row = mysql_fetch_assoc($result)){
			array_push($result, $row);
        }
        return $result;
	}
	
	public function execUpdate($flds, $vals, $tbl, $cond = null){
		if(isset($cond)) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBServiceImpl::UPDATE . $tbl . "SET" . $flds . "=" . $vals . $cond;
		$result = BaseSQLDBServiceImpl::exec($sql);
		return $result;
	}
	
	public function execDelete($tbl, $cond = null){
		if(isset($cond)) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBServiceImpl::DELETE . $tbl . "WERE" . $cond;
		$result = BaseSQLDBServiceImpl::exec($sql);
		return $result;
	}
	
}
?>