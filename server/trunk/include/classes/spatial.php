<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Abstraction class for spatial objects
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/spatial.php $
 * @package		classes
 * @subpackage	spatial
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		30
 * @version		$Revision: 43 $
 * @lastmodifed	$Date: 2009-04-26 17:39:01 +0200 (So, 26 Apr 2009) $
 */

class TLatLon {
	public $lat, $lon, $alt;

	function __construct($lat, $lon, $alt = 0) {
		if ($lat > 90 || $lat < -90) throw new EBase('Out of Range!'); else $this->lat = (float) $lat;
		if ($lon > 90 || $lon < -90) throw new EBase('Out of Range!'); else $this->lon = (float) $lon;
		$this->alt = (float) $alt;
	}

	/**
	 * @brief calculates distance between two coordinates
	 * @param $to
	 * @link http://williams.best.vwh.net/avform.htm
	 * @since 33
	 * @return float distance in kilometers to $pos
	 */
	function distance(TLatLon $to) {
		return 6371.01 * $this->greatCircleDistance($to);
	}
	
	/**
	 * @brief calculates course between two coordinates
	 * @param $to
	 * @link http://williams.best.vwh.net/avform.htm
	 * @since 33
	 * @return float course in degree oriented towards the true northpole
	 */
	function course(TLatLon $to) {
		$lat1 = deg2rad($this->lat);
		$lon1 = deg2rad($this->lon);
		
		$lat2 = deg2rad($to->lat);
		$lon2 = deg2rad($to->lon);
		
		$d = $this->greatCircleDistance($to);
		$course = acos((sin($lat2) - sin($lat1) * cos($d)) / (sin($d) * cos($lat1)));
		
		if (sin($lon2 - $lon1) < 0) {
			$course = 2 * pi() - $course;
		}
		
		return rad2deg($course);
	}
	
	/**
	 * @brief calculates human readable course information
	 * @param $to
	 * @since 33
	 * @return string
	 */
	function courseLetters($to) {
		//TODO added windroses for diffrent languages
		$windrose = array('N', 'NE', 'E', 'SE', 'S', 'SW', 'W', 'NW');
		
		$angle = $this->course($to); 
		return $windrose[round($angle / 45)];
	}
	
	/**
	 * @brief calculates the angle between the two coordinates and the earth's centerpoint
	 * @param $to
	 * @since 33
	 * @return float great circle distance in radians
	 */
	private function greatCircleDistance(TLatLon $to) {
		$lat1 = deg2rad($this->lat);
		$lon1 = deg2rad($this->lon);
		
		$lat2 = deg2rad($to->lat);
		$lon2 = deg2rad($to->lon);

		return acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($lon2 - $lon1));
	}
}

class TTrack {
	public $coords = array();

	function __construct($start, $end) {
	}

	/**
	 * @brief calculate length of the line
	 * @return float length in kilometers
	 */
	function length() {

	}
}

class TPolygon {
	public $points; // of TLatLon
}

?>