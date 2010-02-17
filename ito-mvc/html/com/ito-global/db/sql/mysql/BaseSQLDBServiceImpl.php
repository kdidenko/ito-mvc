<?php
require_once 'html/com/ito-global/db/BaseSQLDBService.php';

class BaseSQLDBServiceImpl implements BaseSQLDBService{
	
	private function connet($db, $table, $user, $password, $host){
		$db_connect = mysql_connect($host, $user, $password);
		if(!$db_connect) die(mysql_error());
		$db_selected = mysql_select_db($db, $db_connect);
		if(!$db_selected) die(mysql_error());
		return $db_connect;
	}
	
	private function close($db_connect){
		mysql_close($db_connect);
	}
	
	private function exec($sql){
		$result = mysql_db_query($sql, $db_connect);
		if(!$result) die(mysql_error());
		return $result;
		
	}
	
	public function execInsert($flds, $vals, $tbl){
		$sql = "INSERT INTO" . $tbl . "(" . $flds .") VALUES (" . $vals . ")";
		BaseSQLDBServiceImpl::exec($sql);
		while($row = mysql_fetch_assoc($result)){
			array_push($result, $row);
        }
	}
	
	public function execSelect($flds, $tbl, $cond){
		$sql = "SELECT" . $flds . "FROM" . $tbl . $cond;
		BaseSQLDBServiceImpl::exec($sql);
	}
	
	public function execUpdate($flds, $vals, $tbl, $cond){
		$sql = "UPDATE" . $tbl . "SET" . $flds . "=" . $vals . $cond;
		BaseSQLDBServiceImpl::exec($sql);
	}
	
	public function execDelete($tbl, $cond){
		$sql = "DELETE FROM" . $tbl . "WERE" . $cond;
		BaseSQLDBServiceImpl::exec($sql);
	}
	
	
}



?>