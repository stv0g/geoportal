<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Dynamic KML File for Google Earth
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/kmllink.php $
 * @package		core
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 */

require_once 'include/init.php';

header('Content-Type: application/vnd.google-earth.kml+xml');

echo '<?xml version="1.0" encoding="UTF-8"?>
	<kml xmlns="http://earth.google.com/kml/2.1">
		<NetworkLink>
			<open>1</open>
			<name>' . $c->general->title . '</name>
			<description>' . $c->general->description . '</description>
			<open>1</open>
			<visibility>1</visibility>
			<refreshVisibility>0</refreshVisibility>
			<Link>
				<href>http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . dirname($_SERVER['REQUEST_URI']) . '/driver/output/kml.php</href>
				<refreshInterval>2</refreshInterval>
				<viewRefreshMode>onStop</viewRefreshMode>
				<viewRefreshTime>1</viewRefreshTime>
			</Link>
		</NetworkLink>
	</kml>';
	
?>