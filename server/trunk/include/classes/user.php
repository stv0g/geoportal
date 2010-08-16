<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * GeoPortal Server
 *
 * User abstraction
 *
 * @filesource	$HeadURL: http://svn.griesm.de/geo/server/trunk/include/classes/user.php $
 * @package		classes
 * @subpackage	access
 * @author		Steffen Vogel (info@steffenvogel.de)
 * @modifedby	$LastChangedBy: steffen $
 * @copyright	Copyright (c) 2009 Steffen Vogel (info@steffenvogel.de)
 * @license		http://www.gnu.org/licenses/gpl.html
 * @link		http://www.geoportal.griesm.de
 * @since		1
 * @version		$Revision: 44 $
 * @lastmodifed	$Date: 2009-04-26 21:07:58 +0200 (So, 26 Apr 2009) $
 */

class TUser extends ADatabaseObject {
	function __construct($identifier, TDatabase $db) {
		global $c;
				
		if (ctype_digit((string) $identifier)) {
			parent::__construct($identifier, $c->db->table->users, $db);
		}
		elseif (is_string($identifier)) {
			$sql = 'SELECT id
				FROM ' . $c->db->table->users . '
				WHERE nick = \'' . $db->escape($identifier) . "'";
			$result = $db->query($sql, 1);
			$user = $result->current();
			parent::__construct($user['id'], $c->db->table->users, $db);
		}
		else
			throw new EBase('unknown type');
	}

	public function toXml(DOMDocument $xml) {
		$user = $xml->createElement('user');
		$user->setAttribute('id', $this->id);

		$nick = $xml->createElement('nick', $this->nick);
		$user->appendChild($name);

		$preName = $xml->createElement('prename', $this->prename);
		$user->appendChild($preName);

		$lastName = $xml->createElement('lastname', $this->lastname);
		$user->appendChild($lastName);

		$eMail = $xml->createElement('email', $this->email);
		$user->appendChild($eMail);

		return $user;
	}

	static function getFromFilter(TFilter $filter, TDatabase $db) {
		global $c;

		$sql =  'SELECT id
			FROM ' . $c->db->table->users . '
			WHERE ' . $filter->getSql() . '
			ORDER BY joined DESC';

		$users = $db->query($sql);
		$userObjs = array();
		foreach ($users as $user) {
			$userObjs[] = new TUser($user['id']);
		}

		return $userObjs;
	}

	static function create($nick, $preName, $lastName, $eMail, TDatabase $db) {
		global $c;

		$sql = 'INSERT ' . $c->db->table->users . '
				SET nick = \'' . $db->escape($nick) . '\',
					prename = \'' . $db->escape($preName) . '\',
					lastname = \'' . $db->escape($lastName) . '\',
					email = \'' . $db->escape($eMail);
		$db->execute($sql);

		return new TUser($db->insertId());
	}

	public function delete() {
		global $c;

		$sql = 'DELETE FROM ' . $c->db->table->users . '
				WHERE id = ' . $this->id;
		$this->db->execute($sql);
	}

	public function update($nick, $preName, $lastName, $eMail) {
		global $c;

		$sql = 'UPDATE ' . $c->db->table->users . '
				SET nick = \'' . $this->db->escape($nick) . '\',
					prename = \'' . $this->db->escape($preName) . '\',
					lastname = \'' . $this->db->escape($lastName) . '\',
					email = \'' . $this->db->escape($eMail) . '\'
				WHERE id = ' . $this->id;
		$this->db->execute($sql);
		//TODO update object
	}

	/**
	 * @brief checks if the password is correct
	 * @param $pw
	 * @return bool
	 */
	public function checkPassword($pw) {
		if (hashPassword($pw) == $this->password) {
			return true;
		}
	}

	/**
	 * @brief hashes the password with selected algorithm
	 * @static
	 */
	public static function hashPassword($pw) {
		return sha1($pw);
	}

	/**
	 * @brief changes the user password
	 * @param
	 */
	public function changePassword($oldPw, $newPw, $db) {
		global $c;

		if (checkPassword($oldPw)) {
			$this->password = $this->hashPassword($newPw);
		}
	}
}
?>