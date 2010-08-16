<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Categories module
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/modules/core/categories.php $
 * @package		modules
 * @subpackage	core
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 */

header("Content-type: application/xml");

require_once '../../include/init.php';

switch ($_GET['command']) {
	case 'edit':
	
	break;
	
	case 'add':
		
	break;
	
	case 'delete':
		$db->execute('DELETE FROM ' . $c->db->table->categories . ' WHERE id = ' . (int) $_GET['id']);
	break;
	
	case 'get':
		$sql = 'SELECT c.id,
						c.name,
						c.description,
						c.schema,
						c.icon
				FROM ' . $c->db->table->categories . ' AS c';
		$categories = $db->query($sql);

		$xml = new DOMDocument($c->xml->version, $c->xml->encoding);
		$xmlCategories = $xml->createElement('categories');
		$xml->appendChild($xmlCategories);
		
		foreach ($categories as $category) {
			$xmlCategory = new TCategory($category['id'], $category['name'], $category['description'], $category['schema'], $category['icon']);
			$xmlCategories->appendChild($xmlCategory->getXML($xml));
		}
		
		echo $xml->saveXML();
	break;
	
	default:
		//TODO implement error handling
		echo 'error unhandled command!';
	break;
}
?>