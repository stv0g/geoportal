/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Geolocation
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/javascript/geolocation.js $
 * @package		javascripts
 * @subpackage	geolocation
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 */

var TGeoLocation = function () {
	function getPosition() {
		$.ajax( {
			dataType :"jsonp",
			url :"http://localhost:6302/getposition",
			success :updatePosition
		});
	}
	
	function updatePosition(json) {
		var lonLat = new OpenLayers.LonLat(json.lon, json.lat).transform(
				new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject())
		map.panTo(lonLat);
		
		updateTimeout = window.setTimeout("getPosition()", 10000);
	}
	
	getPosition();
}