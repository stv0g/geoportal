<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Database object abstraction
 * 
 * PHP 5 
 *
 * @package		
 * @subpackage	
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy$
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl-2.0.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision$
 * @lastmodifed	$Date$
 * @filesource	$HeadURL$
 */

abstract class ADatabaseObject {
	private $data, $db, $table;
	
	public function __construct($id, $table, TDatabase $db) {
		global $c;

		$this->db = $db;
		$this->table = $table;
		
		$sql = 'SELECT *
				FROM ' . $this->table . '
				WHERE id = ' . (int) $id;
		
		$result = $this->db->query($sql, 1);
		if ($result->count() > 0)
			$this->data = $result->current();
		else
			throw new EBase('invalid id: ' . $id, 1);
	}
	
	public function delete() {
		$sql = 'DELETE FROM ' . $this->table . '
				WHERE id = ' . $this->id;
		$this->db->execute($sql);
	}
	
	abstract static function getFromFilter(TFilter $filter, TDatabase $db);
	abstract public function toXml(DOMDocument $xml);
	
	public function __get($column) {
		return $this->data[$column];
	}
	
	public function __set($column, $value) {
		$sql = 'UPDATE ' . $this->table . ' SET ' . $column . ' = ' . ((is_numeric($value)) ? $value : "'" . $this->db->escape($value) . "'");
		$this->db->execute($sql);
	}
}

?>