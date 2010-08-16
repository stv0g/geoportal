/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Javascript Marker handling
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/javascript/marker.js $
 * @package		javascripts
 * @subpackage	markers
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
 * Marker handling
 */

function getMarkers() {
	$.ajax( {
		type :"GET",
		dataType :"xml",
		async :true,
		url :"driver/output/getmarkers.php?BBOX="
				+ map.getExtent().toBBOX(),
		success :drawMarkers
	});
}

function markerClick(evt) {
	if (this.popup == null) {
		this.popup = this.createPopup(true);
		map.addPopup(this.popup);
		this.popup.hide();
	}

	this.popup.toggle();

	OpenLayers.Event.stop(evt);
}

function drawMarkers(xml) {
	xmlMarkers = xml.getElementsByTagName("marker");
	markerLayer = map.getLayersByName("Markers")[0];

	for ( var i = 0; i < xmlMarkers.length; i++) {
		var id = parseInt(xmlMarkers[i].getAttribute("id"));
		var lat = parseFloat(xmlMarkers[i].getElementsByTagName("lat")[0].firstChild.nodeValue);
		var lon = parseFloat(xmlMarkers[i].getElementsByTagName("lon")[0].firstChild.nodeValue);
		var alt = parseFloat(xmlMarkers[i].getElementsByTagName("alt")[0].firstChild.nodeValue);
		var data = xmlMarkers[i].getElementsByTagName("data")[0].firstChild.nodeValue;
		var iconUrl = xmlMarkers[i].getElementsByTagName("icon")[0].firstChild.nodeValue;
		var icon = new OpenLayers.Icon('images/icons/' + iconUrl,
				new OpenLayers.Size(16, 16));
		var lonLat = new OpenLayers.LonLat(lon, lat).transform(
				new OpenLayers.Projection("EPSG:4326"), map
						.getProjectionObject());

		var feature = new OpenLayers.Feature(markerLayer, lonLat);
		feature.data.popupContentHTML = '<pre>' + data + '</pre>';
		var marker = new OpenLayers.Marker(lonLat, icon);

		marker.events.register("mousedown", feature, markerClick);
		markerLayer.addMarker(marker);
	}
}
