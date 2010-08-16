<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Class for creating nested array objects
 *
 * PHP 5
 *
 * @package		classes
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		36
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/nested.php $
 */

class TNestedArrayObject {
	private $container = array();

	function __construct($values) {
		if (is_array($values)) {
			foreach ($values as $name => $value) {
				if (is_array($value)) {
					$this->$name = new TConfig($value);
				}
				else {
					$this->$name = $value;
				}
			}
		}
	}

	public function __set($name, $value) {
		$this->container[$name] = $value;
	}
	public function __isset($name) {
		return isset($this->container[$name]);
	}
	public function __unset($name) {
		unset($this->container[$name]);
	}
	public function __get($name) {
		return isset($this->container[$name]) ? $this->container[$name] : null;
	}
}

?>