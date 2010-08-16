<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Run class
 * 
 * PHP 5 
 *
 * @package		class
 * @subpackage	core
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		37
 * @version		$Revision: 43 $
 * @lastmodifed	$Date: 2009-04-26 17:39:01 +0200 (So, 26 Apr 2009) $
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/run.php $
 */

class TRun {
	public $start;
	
	public function __construct() {
		global $c;
		
		$this->start = microtime(true);
		
		session_start();
		setcookie($c->session->name, session_id(), time() + $c->session->cookie_timeout, $c->session->cookie_path);	// refresh session cookie
		
		ob_start(array($this, 'bufferCallback'));
	}
	
	private function bufferCallback($buffer) {
		$buffer = str_replace('{time}', round((microtime(true) - $this->start) * 1000, 3) . ' ms', $buffer);
		return $buffer;
	}
	
	public function __destruct() {
		ob_end_flush();
	}
}

?>