<?php
require_once 'com/ito-global/db/sql/SQLClientInterface.php';
/**
 * SQLClient is a basic implementation of SQLClientInterface providing simplification for
 * some of native MySQL Extension's functions, connection errors handling and SQL statement composing.
 */
class SQLClient implements SQLClientInterface {
    /**
     * Connects to database and returns connection link indetifier
     * or stops the execution if any error occured.     *
     * @param $db_name string - db name
     * @param $db_host string - db host to connect
     * @param $db_user string - db user name
     * @param $db_password string - db user password
     * @param $charset string - [OPTIONAL] a character set to use for connection
     * @return mixed - connection identifier.
     */
    public static function connect ($db_name, $db_host, $db_user, $db_password, $charset = null) {
        $link = mysql_connect($db_host, $db_user, $db_password);
        if ($link) {
            if ($charset) {
                mysql_query("SET NAMES $charset", $link);
            }
            $selected = mysql_select_db($db_name, $link);
            if (! $selected) {
                //TODO: change die into exception handling/logging!
                die(mysql_error());
            }
        } else {
            //TODO: change die into exception handling/logging!
            die(mysql_error());
        }
        return $link;
    }
    /**
     * Closes the connection by connection identifier.
     * @param $link mixed - connection indentifier
     * @return void
     */
    public static function disconnect ($link) {
        mysql_close($link);
    }
    /* (non-PHPdoc)
	 * @see com/ito-global/db/sql/SQLClientInterface#exec($sql, $link)
	 */
    public static function exec ($sql, $link) {
        $result = mysql_query($sql, $link);
        return $result;
    }
    /* (non-PHPdoc)
	 * @see com/ito-global/db/sql/SQLClientInterface#execInsert($fields, $values, $into, $link)
	 */
    public static function execInsert ($fields, $values, $into, $link) {
        echo 'test';
        $sql = self::INSERT . self::INTO . $into . "(" . $fields . ") VALUES (" . $values . ")";
        $result = self::exec($sql, $link);
        return $result;
    }
    public static function execSelect ($fields, $from, $where, $groupBy, $orderBy, $limit, $link) {
        $sql = self::SELECT . $fields . self::FROM . $from;
        $sql .= $where ? self::WHERE . $where : '';
        $sql .= $groupBy ? self::GROUOPBY . $groupBy : '';
        $sql .= $orderBy ? self::ORDERBY . $orderBy : '';
        $sql .= $limit ? self::LIMIT . $limit : '';
        $ref = self::exec($sql, $link);
        $result = array();
        while ($row = mysql_fetch_assoc($ref)) {
            array_push($result, $row);
        }
        return $result;
    }
    public static function execUpdate ($fields, $from, $vals, $where, $orderBy, $limit, $link) {
        $sql = self::UPDATE . $fields . self::FROM . $from . self::SET . $vals;
        $sql .= $where ? ' ' . self::WHERE . $where : '';
        $result = self::exec($sql, $link);
        return $result;
    }
    public static function execDelete ($from, $where, $orderBy, $limit, $link) {/*if($cond != NULL) $sql = "WHERE" . $cond;
		$sql .= BaseSQLDBService::DELETE . $tbl
		$result = self::exec($sql);
		return $result;*/
}
}
?>