<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Marker abstraction
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/marker.php $
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

class TMarker extends ADatabaseObject {
	public $pos, $xml, $user, $category;
	
	function __construct($id, TDatabase $db) {
		global $c;

		parent::__construct($id, $c->db->table->markers, $db);

		$this->pos = new TLatLon((float) $this->lat, (float) $this->lon, (float) $this->alt);
		$this->xml = new DOMDocument();
		$this->xml->loadXML($this->data);
		$this->user = new TUser((int) $this->usr_id, $db);
		$this->category = new TCategory($this->cat_id, $db);
	}

	public function toXml(DOMDocument $xml) {
		$xmlMarker = $xml->createElement('marker');
		$xmlMarker->setAttribute('id', $this->id);

		$lat = $xml->createElement('lat', (float) $this->pos->lat);
		$xmlMarker->appendChild($lat);

		$lon = $xml->createElement('lon', (float) $this->pos->lon);
		$xmlMarker->appendChild($lon);

		$alt = $xml->createElement('alt', (float) $this->pos->alt);
		$xmlMarker->appendChild($alt);

		$data = $xml->importNode($this->xml->documentElement, true);
		$xmlMarker->appendChild($data);

		$user = $xml->createElement('user', $this->user->id);
		$xmlMarker->appendChild($user);

		$category = $xml->createElement('category', $this->category->id);
		$xmlMarker->appendChild($category);

		$icon = $xml->createElement('icon', $this->category->icon);
		$xmlMarker->appendChild($icon);

		return $xmlMarker;
	}
	
	static function create(TLatLon $pos, DOMDocument $data, TUser $user, TCategory $cat) {
		global $c;

		$sql = 'INSERT ' . $this->table . '
				SET lat = ' . $pos->lat . ',
					lon = ' . $pos->lon . ',
					alt = ' . $pos->alt . ',
					data = \'' . $db->escape($data->saveXML()) . '\',
					usr_rd = ' . $user->id . ',
					cat_id = ' . $cat->id;
		$db->execute($sql);
		
		return new TMarker($this->db->insertId());
	}
	
	public function update(TLatLon $pos, DOMDocument $data, TUser $user, TCategory $cat) {
		global $c;

		$sql = 'UPDATE ' . $this->table . '
				SET lat = ' . $pos->lat . ',
					lon = ' . $pos->lon . ',
					alt = ' . $pos->alt . ',
					data = \'' . $db->escape($data->saveXML()) . '\',
					usr_rd = ' . $user->id . ',
					cat_id = ' . $cat->id . '
				WHERE id = ' . $this->id;
		$this->db->execute($sql);
		//TODO update object
	}

	static function getFromFilter(TFilter $filter, TDatabase $db) {
		global $c;

		$sql =  'SELECT ' . $this->table . '.id
			FROM ' . $c->db->table->markers . '
			LEFT JOIN ' . $c->db->table->categories . ' ON ' . $c->db->table->markers . '.cat_id = ' . $c->db->table->categories . '.id
			LEFT JOIN ' . $c->db->table->users . ' ON ' . $c->db->table->markers . '.usr_id = ' . $c->db->table->users . '.id
			WHERE ' . $filter . '
			ORDER BY ' . $c->db->table->markers . '.updated DESC';

		$markers = $db->query($sql, $c->api->markers->max_per_request);
		$markerObjs = array();
		foreach ($markers as $marker) {
			$markerObjs[] = new TMarker($marker['id'], $db);
		}

		return $markerObjs;
	}
}

?>