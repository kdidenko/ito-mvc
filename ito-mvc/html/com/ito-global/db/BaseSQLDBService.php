<?php
/**
 * @author Kostyantyn Didenko
 *
 * @version 0.1
 *
 * Defines the list of methods required for basic interaction with common SQL DB Servers.
 *
 */
interface BaseSQLDBService {

    const INSERT = 'INSERT ';

    const SELECT = 'SELECT ';

    const UPDATE = 'UPDATE ';

    const DELETE = 'DELETE ';

    /**
     * Executes the SQL query.
     * @param $sql to execute
     * @return int
     */
    public static function exec($sql);

    /**
     * Executes the INSERT query.
     * @param $flds - fields list to be populated with values.
     * @param $vals - values to be inserted.
     * @param $tbl  - tebale to use for insert operation.
     * @return int
     */
    public static function execInsert($flds, $vals, $tbl);

    /**
     * @param $flds
     * @param $tbl
     * @param $cond
     * @return unknown_type
     */
    public static function execSelect($flds, $tbl, $cond);

    public static function execUpdate($flds, $vals, $tbl, $cond);

    public static function execDelete($tbl, $cond);

}
?>