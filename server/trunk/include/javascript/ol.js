/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * OpenLayers
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/javascript/ol.js $
 * @package		javascripts
 * @subpackage	maps
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 */

function TOLMap() {
	/*
	 * Constructor
	 */
	var map = new OpenLayers.Map('map', {
		controls : [ new OpenLayers.Control.Navigation(),
				new OpenLayers.Control.KeyboardDefaults(),
				new OpenLayers.Control.PanZoomBar(),
				new OpenLayers.Control.LayerSwitcher(),
				new OpenLayers. Control. ArgParser(),
				new OpenLayers.Control.ScaleLine() ],
		displayProjection :new OpenLayers.Projection("EPSG:4326"),
		eventListeners : {
			'moveend' :moveend,
			'movestart' :movestart
		}
	});

	// create OSM layers
	var osmMapnikLayer = new OpenLayers.Layer.OSM.Mapnik(
			"OpenStreetMap (Mapnik)");
	var osmarenderLayer = new OpenLayers.Layer.OSM.Osmarender(
			"OpenStreetMap (Tiles@Home)");

	// create marker layer
	var markerLayer = new OpenLayers.Layer.Markers("Markers");

	map.addLayers( [ osmMapnikLayer, osmarenderLayer, markerLayer, ]);

	/*
	 * Event handlers
	 */
	function moveend() {
		// loadTimeout = window.setTimeout("getMarkers()", 1500);
	}

	function movestart() {
		window.clearTimeout(loadTimeout);
		markerLayer.clearMarkers();
	}

	/*
	 * Member functions
	 */
	this.setCenter = function(lat, lon, zoom) {
		var lonLat = new OpenLayers.LonLat(lon, lat).transform(
				new OpenLayers.Projection("EPSG:4326"), map
						.getProjectionObject())
		map.setCenter(lonLat, zoom);
	}
}
