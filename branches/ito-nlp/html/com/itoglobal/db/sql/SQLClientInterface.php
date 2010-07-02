<?php
/**
 * @author Kostyantyn Didenko
 *
 * Defines the list of client's methods required for basic interaction with common SQL DB Servers.
 *
 */
interface SQLClientInterface {
    /**
     * @var string SQL INSERT statement identifier.
     */
    const INSERT = 'INSERT ';
    /**
     * @var string SQL SELECT statement identifier.
     */
    const SELECT = 'SELECT ';
    /**
     * @var string SQL UPDATE statement identifier.
     */
    const UPDATE = 'UPDATE ';
    /**
     * @var string SQL DELETE statement identifier.
     */
    const DELETE = 'DELETE ';
    /**
     * @var string SQL INTO statement identifier.
     */
    const INTO = 'INTO ';
    /**
     * @var string SQL FROM statement identifier.
     */
    const FROM = ' FROM ';
    /**
     * @var string SQL WHERE statement identifier.
     */
    const WHERE = ' WHERE ';
    /**
     * @var string SQL LIMIT statement identifier.
     */
    const LIMIT = ' LIMIT ';
    /**
     * @var string SQL SET statement identifier.
     */
    const SET = ' SET ';
    /**
     * @var string SQL JOIN statement identifier.
     */
    const JOIN = ' JOIN ';
    /**
     * @var string SQL ON statement identifier.
    */
    const ON = ' ON ';
    /**
     * @var string SQL LEFT statement identifier.
     */
    const LEFT = ' LEFT ';
    /**
     * @var string SQL RIGHT statement identifier.
     */
    const RIGHT = ' RIGHT ';
    /**
     * @var string SQL GROUP BY statement identifier.
     */
    const GROUOPBY = ' GROUP BY ';
    /**
     * @var string SQL GROUP BY statement identifier.
     */
    const ORDERBY = ' ORDER BY ';
    /**
     * @var string SQL ASC statement identifier.
     */
    const ASC = 'ASC';
    /**
     * @var string SQL DESC statement identifier.
     */
    const DESC = 'DESC';
	/**
	 * @var string SQL NOT statement identifier.
	 */
	const NOT = ' NOT ';
	/**
	 * @var string SQL IN statement identifier.
	 */
	const IN = ' IN ';
    /**
     * @var string SQL COUNT statement identifier.
     */
    const COUNT = ' COUNT';
    /**
     * @var string SQL AS statement identifier.
     */
    const SQL_AS = ' AS ';
    /**
     * @var string SQL IS NULL statement identifier.
     */
    const IS_NULL = ' IS NULL ';
    /**
     * Executes the SQL query and returns the execution result status.
     * @param $sql string - query string to execute.
     * @param $into mixed - db link to use for execution
     * @return integer - zero if execution was successful. Non-zero if an error occurred.
     */
    public static function exec ($sql, $lnk);
    /**
     * Executes SQL INSERT statement.
     * @param $fields string - comma separated fields list of inserted values.
     * @param $values string - comma separated values list to be inserted.
     * @param $into string - tebale name to insert values.
     * @param $into mixed - db link to use for execution
     * @return integer - zero if execution was successful. Non-zero if an error occurred.
     */
    public static function execInsert ($fields, $values, $into, $lnk);
    /**
     * Prepares and executes SQL SELECT statement.
     * @param $fields string - comma separated list of fields to select.
     * @param $from string - comma separated list of table names to use for select query.
     * @param $where string - SELECT query condition expression.
     * @param $groupBy string - SELECT query GROUP BY expression string.
     * @param $orderBy string - SELECT query ORDER BY expression string.
     * @param $limit string - SELECT query LIMIT expression.
     * @return mixed - array if execution was successful. NULL if an error occured.
     */
    public static function execSelect ($fields, $from, $where, $groupBy, $orderBy, $limit, $link);

    /**
     * @param $fields string - comma separated list of fields to select.
     * @param $from string - comma separated list of table names to use for select query.
     * @param $vals string - comma separated list of new values to set.
     * @param $where string - SELECT query condition expression.
     * @param $orderBy string - SELECT query ORDER BY expression string.
     * @param $limit string - SELECT query LIMIT expression.
     * @return unknown_type
     */
    public static function execUpdate ($fields, $from, $vals, $where, $orderBy, $limit, $link);

    /**
     * Prepares and executes SQL DELETE statement.
     * @param $from
     * @param $where string - if given, specifies the conditions that identify which rows to delete.
     * 							With no $wher parameter, all rows are deleted.
     * @param $orderBy string - if given, the rows are deleted in the order that is specified.
     * @param $limit string - places a limit on the number of rows that can be deleted.
     * @return unknown_type
     */
    public static function execDelete ($from, $where, $orderBy, $limit, $link);
}
?>