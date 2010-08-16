<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Language class
 *
 * PHP 5
 *
 * @package		classes
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		36
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 * @modifedby	$LastChangedBy: steffen $
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/language.php $
 */

class TLanguage extends TNestedArrayObject {
	private $lang;
	
	public function __construct($lang = 'en') {
		$this->lang = $lang;
	}
	
	public function __get($name) {
		if (!isset($this->$name))
			$this->load($name);
		
		return parent::__get($name);
	}
	
	private function load($module) {
		$filename = 'lang/' . $this->lang . '/' . $module . '.php';
		if (file_exists($filename)) {
			require $filename;
			$this->$module = new TNestedArrayObject($lang);
		}
	}
}

?>