<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * MySQL implemantation
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/mysql.php $
 * @package		classes
 * @subpackage	database
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 */


/**
 * @brief base exception for mysql queries
 */
class EMySql extends EDatabase
{
	function __construct() {
		parent::__construct(mysql_error(), mysql_errno());
	}
}

/**
 * @brief resultset of a mysql query
 */
class TMySqlResult extends TDatabaseResultSet
{
	/**
	 * @param resource $resource mysql resultset
	 */
	function __construct($resource) {
		while ($row = mysql_fetch_assoc($resource)) {
			$this->_rows[] = $row;
			++$this->_num_rows;
		}
	}
}

/**
 * @brief mysql layer
 */
class TMySql extends TDatabase {
	/**
	 * @param string $host IP or domain of the database host
	 * @param string $name database name
	 * @param string $user user
	 * @param string $passwd password
	 */
	function __construct($host, $user, $pw, $db) {
		$this->connect($host, $user, $pw);
		$this->select($db);
	}
 
	function __destruct() {
		$this->close();
	}
 
	/**
	 * @brief create database connection
	 * @param string $host IP or domain of the database host
	 * @param string $user user
	 * @param string $passwd password
	 */
	public function connect($host, $user, $pw) {
		$this->close();
		$__er = error_reporting(E_ERROR);
		if (!$this->resource = mysql_connect($host, $user, rawurlencode($pw))) {
			error_reporting($__er);
			throw new EMySql();
		}
		error_reporting($__er);
	}

	/**
	 * @brief close database connection
	 */
	public function close() {
		if (!$this->resource)
			return;
		mysql_close($this->resource);
		$this->resource = false;
	}

	/**
	 * @brief select database
	 * @param string $name database name
	 */
	public function select($name) {
		if (!mysql_select_db($name, $this->resource))
			throw new EMySql();
		$this->database = $name;
	}

	/**
	 * @brief execute query
	 * @param string $sql query
	 * @return mixed
	 */
	public function execute($sql) {
		$this->statements[] = $sql;
		if (!($result = mysql_unbuffered_query($sql, $this->resource)))
			throw new EMySql();
		return $result;
	}

	/**
	 * @brief mysql query
	 * @param string $sql query
	 * @param int $offset
	 * @param int $limit
	 * @return TDatabaseResultSet
	 */
	public function query($sql, $limit = -1, $offset = 0) {
		if ($limit != -1)
			$sql .= sprintf(' LIMIT %d, %d', $offset, $limit);
		return new TMySqlResult($this->execute($sql));
	}
	
	/**
	 * @brief mysql escape
	 * @param string $string
	 * @return string
	 */
	public function escape($string) {
		return mysql_real_escape_string($string, $this->resource);
	}
	
	/**
	 * @return integer the last id
	 */
	public function insertId() {
		return mysql_insert_id($db->resource);
	}
}

?>