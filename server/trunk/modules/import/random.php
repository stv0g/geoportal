<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * random import
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/modules/import/random.php $
 * @package		modules
 * @subpackage	import
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 42 $
 * @lastmodifed	$Date: 2009-04-25 10:23:48 +0200 (Sa, 25 Apr 2009) $
 */

require_once '../../include/init.php';

$count = (isset($_GET['count'])) ? (int) $_GET['count'] : 50000;

$max = mt_getrandmax();

/*
 * @brief returns a random number between -1 and 1
 */
function random() {
	global $max;
	return -1 + 2*(mt_rand() / $max);
}

$originalSql = 'INSERT INTO ' . $c->db->table->markers . ' (lat, lng, data, cat_id, added) VALUES ';
$sql = $originalSql;

$markers = 0;

while ($markers < $count) {
	$markers++;
	$sql .= '(' . sprintf('%f', random() * 90) . ', '  . sprintf('%f', random() * 180) . ', \'<data><name>Test Marker</name></data>\', 24, NOW()), ';
	if ($markers % 1000 == 0 || $markers == $count) {
		$sql = substr($sql, 0, -2);
		$db->execute($sql);
		$sql = $originalSql;
		echo '.';
		flush();
	}
}
echo 'Added ' . $markers . ' markers by random!';

?>