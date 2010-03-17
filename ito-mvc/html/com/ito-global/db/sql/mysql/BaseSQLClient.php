<?php
require_once 'html/com/ito-global/db/sql/SQLClientInterface.php';

/**
 * @author kdidenko
 *
 * BasicSQLClient is a basic implementation of SQLClientInterface
 * including some basic functionality like INSERT DELETE or UPDATE
 * statements.
 *
 */
class BaseSQLClient implements SQLClientInterface {

	/*private static $db;
	private static $host;
	private static $user;
	private static $password;
*/

    /**
     * Creates the DB connnection and returns its link indetifier
     * or the error string if any errors were duiring connection.
     * @param $db_name string - the DB name to connect.
     * @param $db_host string - the DB host to connect.
     * @param $db_user string - the user name to use for connection.
     * @param $db_password string - the password to use for connection.
     * @return mixed - connection link or <b>string</b> the error messge.
     */
    public static function connect($db_name, $db_host, $db_user, $db_password) {
		$lnk = mysql_connect($db_host, $db_user, $db_password);
		if($lnk) {
		    $lnk = mysql_select_db($db_name, $lnk);
		}
		return $lnk ? $lnk : mysql_error();
	}
	/**
	 * Disconnects the previousely created connection
	 * @param $lnk mixed - connection link indentifier
	 * @return void
	 */
	public static function disconnect($lnk){
		mysql_close($lnk);
	}
	/* (non-PHPdoc)
	 * @see com/ito-global/db/sql/SQLClientInterface#exec($sql, $lnk)
	 */
	public static function exec($sql, $lnk){
		$result = mysql_db_query($sql, $lnk);
		return $result;
	}
	/* (non-PHPdoc)
	 * @see com/ito-global/db/sql/SQLClientInterface#execInsert($fields, $values, $into, $lnk)
	 */
	public static function execInsert($fields, $values, $into, $lnk){
		$sql = self::INSERT . self::INTO . $into . "(" . $fields .") VALUES (" . $values . ")";
		$result = self::exec($sql, $lnk);
        return $result;
	}

	public static function execSelect($flds, $tbl, $cond = null, $limit, $lnk){
	    $sql = sellf::SELECT . $flds . self::FROM . $tbl;
		$sql .= $cond ? self::WHERE . $cond : '';
		$sql .= $limit;
		$result = self::exec($sql, $lnk);
		while($row = mysql_fetch_assoc($result)){
			array_push($result, $row);
        }
        return $result;
	}

	public static function execUpdate($flds, $vals, $tbl, $cond = null, $link){
		if($cond != NULL) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBService::UPDATE . $tbl . "SET" . $flds . "=" . $vals
		$result = self::exec($sql);
		return $result;
	}

	public static function execDelete($tbl, $cond = null){
		if($cond != NULL) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBService::DELETE . $tbl
		$result = self::exec($sql);
		return $result;
	}

}
?>