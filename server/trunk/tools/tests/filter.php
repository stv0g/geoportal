<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Test script for spatial calculations
 *
 * PHP 5
 *
 * @package		tests
 * @subpackage	filter
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		33
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/tools/tests/spatial.php $
 */

require_once '../../include/init.php';

	if (isset($_GET['filter'])) {
		$filter = TFilter::getFromString(rawurldecode($_GET['filter']));
	}
	else {
		$filter = new TFilter(new TRuleMarker(new TMarker(375923, $db)));
	}
	echo $filter;

?>