<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Google KML generation
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/kml.php $
 * @package		classes
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 */

class TKml {
	
	function __construct($xmlns = 'http://www.opengis.net/kml/2.2') {
		$this->dom = new DOMDocument('1.0', 'UTF-8');
		$this->dom->formatOutput = true;
		
		$kml = $this->dom->createElement('kml');
		$kml->setAttribute('xmlns', $xmlns);
		$this->dom->appendChild($kml);
		
		$this->document = $this->dom->createElement('Document');
		$kml->appendChild($this->document);
	}
	
	function addMarker($id, $lat, $lng, $title, $description, $icon = 'deficon.png') {
		$marker = $this->dom->createElement('Placemark');
		$marker->setAttribute('id', $id);
		
		$name = $this->dom->createElement('name' , $title);
		$marker->appendChild($name);
		
		$desc = $this->dom->createElement('description' , $description);
		$marker->appendChild($desc);
		
		$point = $this->dom->createElement('Point');
		$coordinates = $this->dom->createElement('coordinates', $lng . ',' . $lat);
		$point->appendChild($coordinates);
		$marker->appendChild($point);
		
		$this->document->appendChild($marker);
	}
	
	function getXml() {
		return $this->dom->saveXML();
	}

}

?>