<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Test script for spatial calculations
 * 
 * PHP 5 
 *
 * @package		tests
 * @subpackage	spatial
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		33
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/tools/tests/spatial.php $
 */

include '../../include/init.php';

echo '<html><head><title>Spatial Test</title></head><body>';

echo '<p>Your home coordinates are: ' . $c->map->home->lat . ', ' . $c->map->home->lon . '</p>';

echo '<form action="spatial.php" method="post">
		<p>Calculate course and distance to this point: <input type="text" name="lat" value="' . $_POST['lat'] . '" /><input type="text" name="lon" value="' . $_POST['lon'] . '" /> <input type="submit" value="caclulate"/></p>';

if ($_POST) {
	$home = new TLatLon($c->map->home->lat, $c->map->home->lon);
	$destination = new TLatLon((float) $_POST['lat'], (float) $_POST['lon']);
	
	echo '<h5>Results</h5>
			<p>Course: ' . $home->course($destination) . '&deg; (' . $home->courseLetters($destination) . ')</p>
			<p>Distance: ' . $home->distance($destination) . 'km </p>';
}

echo '</body></html>';

?>