<?php
class ModelCheckoutServiceDelivery extends Model {
	public function getServices() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "service_delivery`");
		return $query->rows;
	}
}