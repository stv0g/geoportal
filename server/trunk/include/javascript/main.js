/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Main javascript
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/javascript/main.js $
 * @package		javascript
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 */

/*
 * Global variables
 */

var map;
var loadTimeout;
var updateTnterval;
var currentPopup;

/*
 * Main routine
 */
var main = function() {
	var map = new TOLMap();
	//var position = new TGeoLocation();
	
	map.setCenter(49.869, 8.62, 13);
}

$(document).ready(main);