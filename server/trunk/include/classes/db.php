<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Database interface
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/db.php $
 * @package		classes
 * @subpackage	database
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 43 $
 * @lastmodifed	$Date: 2009-04-26 17:39:01 +0200 (So, 26 Apr 2009) $
 */

/**
 * @brief base exception for database queries
 */
class EDatabase extends EBase {
}

/**
 * @brief abstract resultset definition
 */
abstract class TDatabaseResultSet implements Iterator {
	/**
	 * @brief rowcount of the result
	 * @var int
	 */
	protected $_num_rows = 0;

	/**
	 * @brief result
	 * @var array
	 */
	protected $_rows = array();

	/**
	 * @param resource $resource database resource
	 */
	abstract function __construct($resource);

	/**
	 * @brief current element (iterator)
	 * @return array
	 */
	public function current() {
		return current($this->_rows);
	}

	/**
	 * @brief next element (iterator)
	 * @return array
	 */
	public function next() {
		return next($this->_rows);
	}

	/**
	 * @brief index of current element (iterator)
	 * @return array
	 */
	public function key() {
		return key($this->_rows);
	}

	/**
	 * @brief first element (pointer reset, iterator)
	 * @return array
	 */
	public function rewind() {
		return reset($this->_rows);
	}

	/**
	 * @brief check current element (iterator)
	 * @return bool
	 */
	public function valid() {
		return (bool) is_array($this->current());
	}
	
	/**
	 * @brief count the results
	 * @return int
	 */
	public function count() {
		return count($this->_rows);
	}
}

/**
 * @brief interface database definition
 */
interface IDatabase {
	/**
	 * @brief create database connection
	 * @param string $host IP or domain of the database host
	 * @param string $user user
	 * @param string $passwd password
	 */
	public function connect($host, $user, $pw);

	/**
	 * @brief close database connection
	 */
	public function close();

	/**
	 * @brief select database
	 * @param string $name name of database
	 */
	public function select($db);

	/**
	 * @brief execute query
	 * @param string $sql query
	 * @return mixed
	 */
	public function execute($sql);

	/**
	 * @brief query
	 * @param string $sql
	 * @param int $offset
	 * @param int $limit
	 * @return TDatabaseResultSet
	 */
	public function query($sql, $limit = -1, $offset = 0);
	
	/**
	 * @brief escape
	 * @param string $string
	 * @return string
	 */
	public function escape($string);
	
	/**
	 * @return integer the last id
	 */
	public function insertId();
}

/**
 * @brief abstract database layer definition
 */
abstract class TDatabase implements IDatabase {
	/**
	 * @brief current database
	 * @var string
	 */
	protected $database = '';

	/**
	 * @brief database handle
	 * @var resource
	 */
	protected $resource = false;

	/**
	 * @brief container with exectuted queries
	 * @var array
	 */
	protected $statements = array();
}

?>