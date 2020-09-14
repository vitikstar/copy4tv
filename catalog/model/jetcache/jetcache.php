<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// http://opencartadmin.com © 2011-2017 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
if (!class_exists('ModelJetcacheJetcache')) {
class ModelJetcacheJetcache extends Model
{
	public function getSettings($table, $key) {
		$sql = "SELECT value_db FROM " . DB_PREFIX . $table. " WHERE `key_db` = '" . $this->db->escape($key) . "' AND `time_expire_db` > ".time()."";
		$query = $this->db->query($sql);
		if (isset($query->row['value_db']))	return $query->row['value_db'];
		else
		return false;
	}

	public function setSettings($table, $key, $value, $time) {
		$sql = "INSERT INTO " . DB_PREFIX . $table . " (key_db, value_db, time_expire_db) VALUES('".$this->db->escape($key)."','".$this->db->escape($value)."','".$this->db->escape($time)."')";
		$this->db->query($sql);
	}

	public function deleteSettings($table, $key) {
		$sql = "DELETE FROM " . DB_PREFIX . $table. " WHERE `key_db` = '" . $this->db->escape($key) . "'";
		$query = $this->db->query($sql);
	}
	public function clearSettings($table) {
		$sql = "TRUNCATE TABLE " . DB_PREFIX . $table. "";
		$query = $this->db->query($sql);
	}

}
}
