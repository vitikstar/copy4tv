<?php
/*
@author	Artem Serbulenko
@link	https://cmsshop.com.ua
@link	https://opencartforum.com/profile/762296-bn174uk/
@email 	serfbots@gmail.com
*/
class ModelExtensionModuleArtAqaProduct extends Model {
		
	public function getAqaProducts($data = array()) {
		$sql = "SELECT *, aap.name as user_name FROM " . DB_PREFIX . "art_aqa_product aap";
			
		$sort_data = array(	
			'aqa_product_id',
			'email',
			'phone',
			'name',
			'status',
			'ip',
			'date_added'
		);		
	
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " WHERE status = '" . (int)$data['filter_status'] . "'";
		}

		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];	
		} else {
			$sql .= " ORDER BY date_added";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
	
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}		

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}	
		
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}	
		
		$query = $this->db->query($sql);
		
		return $query->rows;
	}
	
	public function getAqaProduct($aqa_product_id) {
		$query = $this->db->query("SELECT *, aap.name as user_name FROM " . DB_PREFIX . "art_aqa_product aap WHERE  aqa_product_id = '" . (int)$aqa_product_id . "'");
		
		return $query->row;
	}

	public function getTotalAqaProducts($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "art_aqa_product";
		
		if (isset($data['filter_status']) && !is_null($data['filter_status'])) {
			$sql .= " WHERE status = '" . (int)$data['filter_status'] . "'";
		}

		$query = $this->db->query($sql);

		return $query->row['total'];
	}

	public function deleteAqaProduct($aqa_product_id){
		$this->db->query("DELETE FROM `" . DB_PREFIX . "art_aqa_product` WHERE aqa_product_id = '" . (int)$aqa_product_id . "'");
	}

	public function editAqaProduct($aqa_product_id, $data){
		$this->db->query("UPDATE `" . DB_PREFIX . "art_aqa_product` SET name = '" . $this->db->escape($data['name']) . "', status = '" . (int)$data['status'] . "', email = '" . $this->db->escape($data['email']) . "', phone = '" . $this->db->escape($data['phone']) . "', question = '" . $this->db->escape($data['question']) . "', answer = '" . $this->db->escape($data['answer']) . "', answer_name = '" . $this->db->escape($data['answer_name']) . "', image = '" . $this->db->escape($data['image']) . "', date_fake = '" . $this->db->escape($data['date_fake']) . "', date_answer = '" . $this->db->escape($data['date_answer']) . "', date_modified = NOW() WHERE aqa_product_id = '" . (int)$aqa_product_id . "'");
	}

	public function getTotalAqaProductsStatus() {
		if (!$this->tableExists()) {
			$this->createTables();
		}
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "art_aqa_product WHERE status = 0");
		
		return $query->row['total'];
	}

	public function tableExists() {
		$query = $this->db->query("SHOW TABLES LIKE '" . DB_PREFIX . "art_aqa_product'");

		return $query->num_rows;
	}

	public function createTables() {
		$sql  = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "art_aqa_product` ( ";
		$sql .= "`aqa_product_id` int(11) NOT NULL AUTO_INCREMENT, ";
		$sql .= "`product_id` int(11) NOT NULL, ";
		$sql .= "`name` varchar(256) NOT NULL, ";
		$sql .= "`email` varchar(96) NOT NULL, ";
		$sql .= "`communication` varchar(32) NOT NULL, ";
		$sql .= "`question` text NOT NULL, ";
		$sql .= "`phone` varchar(32) NOT NULL, ";
		$sql .= "`answer` text NOT NULL, ";
		$sql .= "`answer_name` varchar(90) NOT NULL, ";
		$sql .= "`image` varchar(255) DEFAULT NULL, ";
		$sql .= "`status` tinyint(1) NOT NULL, ";
		$sql .= "`ip` varchar(40) NOT NULL, ";
		$sql .= "`forwarded_ip` varchar(40) NOT NULL, ";
		$sql .= "`user_agent` varchar(255) NOT NULL, ";
		$sql .= "`accept_language` varchar(255) NOT NULL, ";
		$sql .= "`language_id` tinyint(1) NOT NULL, ";
		$sql .= "`date_added` datetime NOT NULL, ";
		$sql .= "`date_answer` datetime NOT NULL, ";
		$sql .= "`date_fake` datetime NOT NULL, ";
		$sql .= "`date_modified` datetime NOT NULL, ";
		$sql .= "PRIMARY KEY (`aqa_product_id`) ";
		$sql .= ") ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		$this->db->query($sql);
	}
}