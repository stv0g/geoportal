<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Configuration
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/config.php $
 * @package		config
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 43 $
 * @lastmodifed	$Date: 2009-04-26 17:39:01 +0200 (So, 26 Apr 2009) $
 */

/*
 * Database settings
 */
$config['db']		= array('backend'		=> 'mysql',
							'user'			=> 'geoportal',
							'pw'			=> 'testme',
							'host'			=> 'localhost',
							'db'			=> 'st_geoportal',

							'table'			=> array('prefix'		=> '',
													'markers'		=> $config['db']['table']['prefix'] . 'markers',
													'categories'	=> $config['db']['table']['prefix'] . 'categories',
													'users'			=> $config['db']['table']['prefix'] . 'users'));

/*
 * XML settings
 */
$config['xml']		= array('version' 		=> '1.0',
							'encoding'		=> 'UTF-8');

/*
 * API settings
 */
$config['api']['markers']['max_per_request']	= 100;

/*
 * Slippy map settings
 */
$config['map']['home']['lat']					= 49.861915;
$config['map']['home']['lon']					= 8.5683703;
$config['map']['home']['zoom']					= 15;
$config['map']['default_icon']					= 'deficon.png';

/*
 * Generall settings
 */
$config['general']	= array('title'			=> 'GeoPortal',
							'description'	=> 'GeoPortal is a webbased portal for geospatial data',
							'language'		=> 'de',
							'template'		=> 'default');

/*
 * Google Maps & Google Earth specific settings
 */
$config['google']['key']						= 'ABQIAAAA_W-Ke-iVOU-RFtiVGjLiOBTm8kGP-LZM02K8D2klsHL-q2zvhBSDLf49PiG6EX3bulVarh5osBzEww';

/*
 * Session settings
 */
$config['session']	= array('cookie_path'	=> '/',
							'session_name'	=> 'sess_id',
							'cookie_timeout'=> 60 * 100, // 100 minutes
							'garbage_timeout'=> 24 * 60 * 60); // 1 day
?>
