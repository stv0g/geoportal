<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Start site
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/index.php $
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

echo '<?xml version="1.0" encoding="utf-8 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html" />
		<title>GeoPortal</title>
		<link rel="stylesheet" type="text/css" href="include/css/style.css" />
		<script type="text/javascript" src="include/javascript/jquery.js"></script>
		<script type="text/javascript" src="include/javascript/openlayers/OpenLayers.js"></script>
		<script type="text/javascript" src="include/javascript/openlayers/OpenStreetmap.js"></script>
		<script type="text/javascript" src="include/javascript/ol.js"></script>
		<script type="text/javascript" src="include/javascript/marker.js"></script>
		<script type="text/javascript" src="include/javascript/geolocation.js"></script>
		<script type="text/javascript" src="include/javascript/main.js"></script>
	</head>
	<body>
		<div id="top_tb">
			<img id="logo" src="images/logo.gif" alt="GeoPortal" />
			<div id="login">
				<fieldset>
					<legend>Login</legend>
					<table>
						<tr><td><label for="user">Username</label></td><td><input type="text" name="user" size="15" /></td><td><input type="checkbox" name="cookie" /><label for="cookie">Set Cookie</label></td></tr>
						<tr><td><label for="password">Password</label></td><td><input type="password" name="password" size="15" /></td><td><input type="button" name="login" value="login" /></td></tr>
					</table>
				</fieldset>
			</div>
		</div>
		<div id="map">
			<div id="bottom_tb">
				<span id="credits">
					Map Data <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a> 2008 <a href="http://openstreetmap.org">OpenStreetMap.org</a>, 
					Map API <a href="http://openlayers.org">OpenLayers.org</a>,
					GeoPortal <a href="http://geoportal.griesm.de">GeoPortal.griesm.de</a>
				</span>
				<span id="status">
					Status (placeholder)
				</span>
			</div>
			<div id="bottom_tb_bg"></div>
		</div>
	</body>
</html>';
?>
