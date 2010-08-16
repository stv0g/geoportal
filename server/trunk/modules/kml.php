<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Google KML generator
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/modules/kml.php $
 * @package		modules
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 */

require_once '../../include/init.php';

header('Content-Type: application/vnd.google-earth.kml+xml');

$bbox = explode(',', $_GET['BBOX']);
$sql = 'SELECT id,
				lat,
				lng,
				prefs,
				type
		FROM ' . $c->db->table->markers . '
		WHERE lat <= ' . (float) $bbox[3] . '
				AND lat >= ' . (float) $bbox[1] . '
				AND lng >= ' . (float) $bbox[0] . '
				AND lng <= ' . (float) $bbox[2];
$result = $db->query($sql , $c->api->markers->max_per_request);

$kml = new TKml;

foreach ($result as $row) {
	$kml->addMarker($row['id'], $row['lat'], $row['lng'], $row['type'], $row['data']);
}

echo $kml->getXml();

?>