<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Category abstraction
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/category.php $
 * @package		classes
 * @subpackage	objects
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 */

class TCategory extends ADatabaseObject {
	public $user;
	
	public function __construct($identifier, TDatabase $db) {
		global $c;

		if (ctype_digit((string) $identifier))
			parent::__construct($identifier, $c->db->table->categories, $db);
		elseif (is_string($identifier)) {
			$sql = 'SELECT *
				FROM ' . $c->db->table->categories . '
				WHERE name = \'' . $db->escape($identifier) . "'";

			$result = $db->query($sql, 1);
			$cat = $result->current();
			parent::__construct($cat['id'], $c->db->table->categories, $db);
		}
		else
			throw new EBase('unknown type');
			
		$this->user = new TUser($this->usr_id, $db);
	}

	public function toXml(DOMDocument $xml) {
		$category = $xml->createElement('category');
		$category->setAttribute('id', $this->id);

		$name = $xml->createElement('name', $this->name);
		$category->appendChild($name);

		$decription = $xml->createElement('description', $this->description);
		$category->appendChild($decription);

		$schema = $xml->createElement('schema', $this->schema);
		$category->appendChild($schema);

		$icon = $xml->createElement('icon', $this->icon);
		$category->appendChild($icon);

		return $category;
	}

	static public function create($name, $decription, DOMDocument $schema, $icon) {
		global $c;

		$sql = 'INSERT ' . $c->db->table->categories . '
				SET name = \'' . $db->escape($name) . '\',
					description = \'' . $db->escape($description) . '\',
					schema = \'' . $db->escape($schema->saveXML()) . '\',
					icon = \'' . $db->escape($icon) . '\'';
		$this->db->execute($sql);

		return new TCategory($this->db->insertId(), $this->db);
	}

	public function delete() {
		global $c;

		$sql = 'DELETE FROM ' . $c->db->table->categories . '
				WHERE id = ' . $this->id;
		$this->db->execute($sql);
	}
	
	static function update($name, $decription, DOMDocument $schema, $icon) {
		global $c;

		$sql = 'UPDATE ' . $c->db->table->categories . '
				SET name = \'' . $this->db->escape($name) . '\',
					description = \'' . $this->db->escape($description) . '\',
					schema = \'' . $this->db->escape($schema->saveXML()) . '\',
					icon = \'' . $this->db->escape($icon) . '\'';
		$this->db->execute($sql);
		
		//TODO update object
	}

	static function getFromFilter(TFilter $filter, TDatabase $db) {
		global $c;

		$sql =  'SELECT id
			FROM ' . $c->db->table->categories . '
			WHERE ' . $filter->getSql() . '
			ORDER BY updated DESC';

		$categories = $db->query($sql, $c->api->markers->max_per_request);
		$categoryObjs = array();
		foreach ($categories as $category) {
			$categoryObjs[] = new TMarker($category['id']);
		}

		return $categoryObjs;
	}
}

?>