<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Markers module
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/modules/core/markers.php $
 * @package		modules
 * @subpackage	core
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 */

header("Content-type: application/xml");

require_once '../../include/init.php';

// create answer node
$xml = new DOMDocument($c->xml->version, $c->xml->encoding);
$xml->formatOutput = true;
$xmlResult = $xml->createElement('result');
$xml->appendChild($xmlResult);

try {

	if ($_GET['action'] == 'add') {
		$xmlData = new DOMDocument();
		$xmlData->loadXML($_POST['data']);
		$marker = TMarker::create(new TLatLon($_GET['lat'], $_GET['lon']), $_GET['alt'], $xmlData, new TUser($_GET['user']), new TCategory($_GET['cat']), $db);
	}
	else {
		$filter = TFilter::getFromString(rawurldecode($_GET['filter']));
		$markers = TMarker::getFromFilter($filter, $db);

		if ($_GET['action'] == 'get') {
			$xmlMarkers = $xml->createElement('markers');
			$xmlMarkers->setAttribute('count', count($markers));
			$xmlResult->appendChild($xmlMarkers);
		}

		foreach ($markers as $marker) {
			switch ($_GET['action']) {
				case 'edit':
					$xmlData = new DOMDocument();
					$xmlData->loadXML($_POST['data']);

					$marker->update(new TLatLon((float) $_GET['lat'], (float) $_GET['lon'], (float) $_GET['alt']), $xmlData, new TUser($_GET['user']), new TCategory($_GET['cat'], $db));
					break;

				case 'get':
					$xmlMarkers->appendChild($marker->toXml($xml));
					break;

				case 'delete':
					$marker->delete($db);
					break;
			}
		}
	}
}
catch (EBase $e) {
	$xmlResult->appendChild($e->toXml($xml, true));
}

echo $xml->saveXML();
?>