<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * Filters for Markers, Users and Categories
 * 
 * PHP 5 
 *
 * @package		classes
 * @subpackage	filter
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		31
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/filter.php $
 */


/**
 * @brief Filter class to combine several filter rules
 * @author steffen
 */
class TFilter {
	private $rules = array();
	
	function __construct() {
		foreach (func_get_args() as $rule) {
			if (get_parent_class($rule) == 'ARule')
				$this->addRule($rule);
		}
	}
	
	public function addRule(ARule $rule) {
		array_push($this->rules, $rule);
	}
	
	public function getSql(){
		$sql = 'TRUE';
		foreach ($this->rules as $rule) {
			$sql .= ' && ' . $rule->getSql();
		}
		return $sql;
	}
	
	public function __toString() {
		return $this->getSql();
	}
	
	static function getFromString($filterStr) {
		$filter = new TFilter();
		
		preg_replace_callback("/[+ ](\w+)([<>!]?=|<|>)([^+ <>=]+)/", array($filter, 'parse'), $filterStr);
		
		return $filter;
	}
	
	public function parse($array) {
		//TODO fix this global access
		global $db;
		
		$op = ($array[2] == '!=') ? '<>' : $array[2]; // for SQL compability
		
		switch ($array[1]) {
			case 'marker':
				$markers = explode(',', $array[3]);
				foreach ($markers as $marker) {
					$this->addRule(new TRuleMarker(new TMarker($marker, $db), $op));
				}
			break;
			
			case 'cat':
				$cats = explode(',', $array[3]);
				foreach ($cats as $cat) {
					$this->addRule(new TRuleCat(new TCategory($cat, $db), $op));
				}
			break;
			
			case 'user':
				$users = explode(',', $array[3]);
				foreach ($users as $user) {
					$this->addRule(new TRuleUser(new TUser($user, $db), $op));
				}
			break;
			
			case 'circle':
				$data = explode(',', $array[3]);
				$center = new TLatLon($data[0], $data[1]);
				$this->addRule(new TRuleCircle($center, $data[2], $op));
			break;
			
			case 'bbox':
				$bbox = explode(',', $array[3]);
				$this->addRule(new TRuleBbox($bbox, $op));
			break;
			
			case 'time':
				$this->addRule(new TRuleTime($array[3], $op));
			break;
			default:
				throw new EBase('unknown filter: ' . $array[1]);
		}
	}
}

/**
 * @brief Rule template
 * @author steffen
 * @abstract
 *
 */
abstract class ARule {
	abstract function getSql();
	public function __toString() {
		return $this->getSql();
	}
	
	private $op;
}

class TRuleMarker extends ARule {
	private $marker;
	
	function __construct(TMarker $marker, $op = '=') {
		$allowed = array('=', '!=');
		if (in_array($op, $allowed))
			$this->op = $op;
		else
			throw new EBase('invalid operator: ' . $op);
		
		$this->marker = $marker;
	}
	
	public function getSql() {
		global $c;
		return $c->db->table->markers . '.id ' . $this->op . ' ' . $this->marker->id;
	}
}

class TRuleCat extends ARule {
	private $cat;
	
	function __construct(TCategory $cat, $op = '=') {
		$allowed = array('=', '<>');
		if (in_array($op, $allowed))
			$this->op = $op;
		else
			throw new EBase('invalid operator: ' . $op);
			
		$this->cat = $cat;
	}
	
	public function getSql() {
		global $c;
		return $c->db->table->categories . '.id ' . $this->op . ' ' . $this->cat->id;
	}
}

class TRuleUser extends ARule {
	private $user;
	
	function __construct(TUser $user, $op = '=') {
		$allowed = array('=', '!=');
		if (in_array($op, $allowed))
			$this->op = $op;
		else
			throw new EBase('invalid operator: ' . $op);
			
		$this->user = $user;
	}
	
	public function getSql() {
		global $c;
		return $c->db->table->users . '.id ' . $this->op . ' ' . $this->user->id;
	}
}

class TRuleCircle extends ARule {
	private $center, $distance;
	
	function __construct(TLatLon $center, $distance, $op = '<') {
		if ($op == '=')
			$this->mode = 'include';
		elseif ($op == '<>')
			$this->mode = 'exclude';
		else
			throw new EBase('invalid operator: ' . $op);
			

		$this->center = $center;
		$this->distance = (float) $distance;
	}
	
	public function getSql() {
		global $c;
		
		// http://williams.best.vwh.net/avform.htm
		$radius = '6371.01 * ACOS(SIN(RADIANS(' . $c->db->table->markers . '.lat)) * SIN(RADIANS(' . $this->center->lat . ')) + COS(RADIANS(' . $c->db->table->markers . '.lat)) * COS(RADIANS(' . $this->center->lat . ')) * COS(RADIANS(' . $this->center->lon . ' - ' . $c->db->table->markers . '.lon)))';
		
		return '(' . $radius . ') ' . (($this->mode == 'include') ? '<=' : '>') . ' ' . $this->distance;
	}
}

class TRuleBbox extends ARule {
	private $bbox;
	
	function __construct($bbox, $op = '=') {
		if ($op == '=')
			$this->mode = 'include';
		elseif ($op = '<>')
			$this->mode = 'exclude';
		else
			throw new EBase('invalid operator: ' . $op);
			
		$this->bbox = $bbox;
	}
	
	public function getSql() {
		global $c;
		return $c->db->table->markers . '.lat ' . (($this->mode == 'include') ? '>=' : '<=') . ' ' . (float) $this->bbox[1] . '
				&& ' . $c->db->table->markers . '.lat ' . (($this->mode == 'include') ? '<=' : '>=') . ' ' . (float) $this->bbox[3] . '
				&& ' . $c->db->table->markers . '.lon ' . (($this->mode == 'include') ? '>=' : '<=') . ' ' . (float) $this->bbox[0] . '
				&& ' . $c->db->table->markers . '.lon ' . (($this->mode == 'include') ? '<=' : '>=') . ' ' . (float) $this->bbox[2];
	}
}

class TRuleTime extends ARule {
	private $ts;
	
	function __construct($ts, $op = '>') {
		$this->mode = $op;
		$this->ts = $ts;
	}
	
	public function getSql() {
		global $c;
		return $c->db->table->markers . '.last_updated ' . $this->op . ' ' . $db->escape($this->ts);
	}
}

class TRuleCustom extends ARule {
	private $sql;
	
	function __construct($sql) {
		$this->sql = $sql;
	}
	
	public function getSql() {
		return $this->getSql();
	}
}

?>