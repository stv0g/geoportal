<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Initialize environment
 *
 * PHP 5
 *
 * @package		core
 * @subpackage
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/init.php $
 */

$root = dirname(dirname(__FILE__));

require_once $root . '/include/config.php';

function __autoload($classname) {
	global $root;
	
	$classes = $root . '/include/classes/';
	$classmap = array(	'TErrorHandler'			=> 'exception',
						'TNestedArrayObject'	=> 'nested',
						'EDatabase'				=> 'db',
						'TLatLon'				=> 'spatial');
	
	$file = $classes . strtolower(substr($classname, 1)) . '.php';
	if (file_exists($file)) {
		require_once $file;
	}
	else {
		//echo $classname;
		require_once $classes . $classmap[$classname] . '.php';
	}
}

error_reporting(E_ALL);
TErrorHandler::initialize();

// load configuration
$c = new TConfig($config);

// configure environment
ini_set('session.gc_maxlifetime', $c->session->garbage_timeout);
ini_set('session.cookie_lifetime', $c->session->cookie_timeout);
ini_set('session.cookie_path', $c->session->cookie_path);
ini_set('session.name', $c->session->name);
ini_set('session.use_only_cookies', true);

// initialize context
//$r = new TRun();

// intitialize language
$l = new TLanguage($c->general->language);

// initialize database connection
require_once $root . '/include/classes/' . $c->db->backend . '.php';
$db = new TMySql($c->db->host, $c->db->user, $c->db->pw, $c->db->db);

// Against session highjacking
if(empty($_SESSION['ip']) || empty($_SESSION['ua'])) {
	$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	$_SESSION['ua'] = $_SERVER['HTTP_USER_AGENT'];
}

if(substr($_SESSION['ip'], 0, strrpos($_SESSION['ip'], '.')) != substr($_SERVER['REMOTE_ADDR'], 0, strrpos($_SERVER['REMOTE_ADDR'], '.'))) {
	trigger_error('Session cookie hijacked: IP subset changed', E_USER_ERROR);
	exit('Session cookie hijacked: IP subset changed! Killing myself...');
}

if ($_SESSION['ua'] != $_SERVER['HTTP_USER_AGENT'] && !empty($_SESSION['usr_id'])) {
	trigger_error('Session cookie hijacked: Useragent changed', E_USER_ERROR);
	exit('Session cookie hijacked: Useragent changed! Killing myself...');
}

?>