<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * gc.com database import
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/modules/import/geocaches.php $
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

ini_set("MEMORY_LIMIT", "128M");

$state = (isset($_GET['state'])) ? $_GET['state'] : 'Germany';
$url = (isset($_GET['url'])) ? $_GET['url'] : 'http://geocaching.bplaced.net/gc';

file_put_contents(sys_get_temp_dir() . '/' . $state . '.db.zip', file_get_contents($url . '/' . $state . '.db.zip'));

$zipFile = new ZipArchive;
$zipFile->open(sys_get_temp_dir() . '/' . $state . '.db.zip');
$gcDb = $zipFile->getStream($state . '.db');
if ($gcDb) {
	$db->execute('DELETE FROM ' . $c->db->table->markers . ' WHERE cat_id <= 11');
	
	$caches = 0;

	$originalSql = 'INSERT INTO ' . $c->db->table->markers . ' (lat, lng, cat_id, data, usr_id, added) VALUES ';
	$sql = $originalSql;
	
	while (($row = fgetcsv($gcDb, 1000, ' ', '"')) !== false) {
		$caches++;

		switch ($row[7]) {
			case 'Unknown Cache':
				$cat_id = 1;
				break;
			case 'Multi-cache':
				$cat_id = 2;
				break;
			case 'Traditional Cache':
				$cat_id = 3;
				break;
			case 'Event Cache':
				$cat_id = 4;
				break;
			case 'Webcam Cache':
				$cat_id = 6;
				break;
			case 'Letterbox Hybrid':
				$cat_id = 7;
				break;
			case 'Virtual Cache':
				$cat_id = 9;
				break;
			case 'Cache In Trash Out Event':
				$cat_id = 10;
				break;
			case 'Earthcache':
				$cat_id = 11;
				break;
			case 'Wherigo Cache':
				$cat_id = 1;
				break;
			default:
				echo 'FOUND NEW CACHE TYPE: ' . $row[7];
				$cat_id = 1;
		}

		$sql .= '(' . (float) $row[5] . ', ' . (float) $row['6'] . ', ' . (int) $cat_id . ', \'<data><id>' . $row[9] . '</id><name>' . $row[1] . '</name><size>' . $row[8] . '</size><guid>' . $row[10] . '</guid></data>\', 1, \'' . (int) $row[2] . '-' . (int) $row[3] . '-' . (int) $row[4] . '\'), ';
		
		if ($caches % 1000 == 0) {
			$sql = substr($sql, 0, -2);
			$db->execute($sql);
			$sql = $originalSql;
			echo '.';
			flush();
		}
	}
	echo '<br />' . $caches . ' Caches hinzugefuegt!';
}
else {
	die('Fehler!');
}

?>