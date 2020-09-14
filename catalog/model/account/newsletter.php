<?php
class ModelAccountNewsletter extends Model {
	public function getFormSendAll($data) {
	    $sql = "SELECT * FROM `" . DB_PREFIX . "newsletter_form_description`  WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "' AND alias IN ('". implode("','",$data) ."')";

	    $query = $this->db->query($sql);

		return $query->rows;
	}
	public function getTypeAll() {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "newsletter_type_description`  WHERE `language_id` = '" . (int)$this->config->get('config_language_id') . "'");

		return $query->rows;
	}
	public function addNewsletter($data) {
        $this->db->query("DELETE FROM  `" . DB_PREFIX . "newsletter_module`  WHERE `customer_id` = '" . (int)$this->customer->getId() . "'");
        foreach ($data as $item){
            $sql = "INSERT INTO  `" . DB_PREFIX . "newsletter_module`  SET `form_alias`='".$item['form_alias']."',`type_alias`='".$item['type_alias']."',`customer_id` = '" . (int)$this->customer->getId() . "'";

            $this->db->query($sql);
        }
	}
}