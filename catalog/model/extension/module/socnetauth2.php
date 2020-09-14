<?php
class ModelExtensionModuleSocnetauth2 extends Model
{

    public function getRecord($state)
    {
        $result = $this->db->query("
			SELECT * FROM `" . DB_PREFIX . "socnetauth2_records` 
			WHERE state='" . $this->db->escape($state) . "'");

        return $result->row;
    }

    public function setRecord($state, $redirect)
    {
        $this->db->query("DELETE FROM `" . DB_PREFIX . "socnetauth2_records` 
			WHERE DATE_ADD(cdate, INTERVAL 15 MINUTE)<NOW()");

        $this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_records` 
			SET 
				`state` = '" . $this->db->escape($state) . "',
				`redirect` = '" . $this->db->escape($redirect) . "',
				`cdate` = NOW()");
    }

    public function  checkByEmail($data, $is_add = 0)
    {
        $result = $this->db->query("SELECT * 
									FROM `" . DB_PREFIX . "customer` 
									WHERE email='" . $this->db->escape($data['email']) . "'");

        if (!$result) {
            $result = $this->db->query("SELECT customer_id 
									FROM `" . DB_PREFIX . "customer` ORDER BY customer_id DESC LIMIT 1");
            $customer_id = $result->row['customer_id']+1;
        }else{
            if (isset($result->row['customer_id'])) {
                $customer_id = $result->row['customer_id'];
            } else {
                $customer_id = false;
            }
        }

        if ($is_add) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account`
                               SET 
                                `customer_id` = '" . $customer_id . "',
                                `identity` = '" . $this->db->escape($data['identity']) . "',
                                `link` = '" . $this->db->escape($data['link']) . "',
                                `provider` = '" . $this->db->escape($data['provider']) . "',
                                `data` = '" . $this->db->escape($data['data']) . "',
                                `email` = '" . $this->db->escape($data['email']) . "'");
        }
        return $customer_id;
    }


    public function checkDB()
    {
        $res = $this->db->query("SHOW TABLES");
        $res = $res->rows;
        $installed = 0;
        foreach ($res as $key => $val) {
            foreach ($val as $k => $v) {
                if ($v == DB_PREFIX . 'socnetauth2_customer2account') {
                    $installed = 1;
                }
            }
        }

        if ($installed == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "socnetauth2_customer2account` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`customer_id` varchar(100) NOT NULL,
				`identity` varchar(300) NOT NULL,
				`link` varchar(300) NOT NULL,
				`provider` varchar(300) NOT NULL,
				`email` varchar(300) NOT NULL,
				`data` TEXT NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";

            $this->db->query($sql);


            $query = $this->db->query("SELECT * 
							   FROM `" . DB_PREFIX . "customer` 
							   WHERE socnetauth2_identity!=''");
            if (!empty($query->rows)) {
                foreach ($query->rows as $customer) {
                    $this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account`
									SET 
									`customer_id` = '" . (int) $customer['customer_id'] . "',
									`identity` = '" . $this->db->escape($customer['socnetauth2_identity']) . "',
									`link` = '" . $this->db->escape($customer['socnetauth2_link']) . "',
									`provider` = '" . $this->db->escape($customer['socnetauth2_provider']) . "',
									`data` = '" . $this->db->escape($customer['socnetauth2_data']) . "',
									`email` = '" . $this->db->escape($customer['email']) . "'");
                }
            }
        } else {
            $todel = $this->db->query("SELECT sc.id, c.customer_id 
								  FROM `" . DB_PREFIX . "socnetauth2_customer2account` sc
								  LEFT JOIN `" . DB_PREFIX . "customer` c
								  ON sc.customer_id=c.customer_id
								  WHERE c.customer_id IS NULL");
            $todel = $todel->rows;
            if (!empty($todel)) {
                foreach ($todel as $item) {
                    $this->db->query("DELETE FROM `" . DB_PREFIX . "socnetauth2_customer2account` 
								  WHERE id=" . (int) $item['id']);
                }
            }
        }
    }

    public function sendConfirmEmail($data)
    {
        $res = $this->db->query("SHOW TABLES");
        $res = $res->rows;
        $installed = 0;
        foreach ($res as $key => $val) {
            foreach ($val as $k => $v) {
                if ($v == DB_PREFIX . 'socnetauth2_customer2account') {
                    $installed = 1;
                }
            }
        }

        if ($installed == 0) {
            $sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "socnetauth2_precode` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`identity` varchar(300) NOT NULL,
				`code` varchar(300) NOT NULL,
				`cdate` DATETIME NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
            $this->db->query($sql);
        }

        $code = md5(rand());
        $this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_precode`
						  SET 
							`identity` = '" . $this->db->escape($data['identity']) . "',
							`code` = '" . $this->db->escape($code) . "',
							`cdate`=NOW()");

        $this->session->data['controlled_email'] = $data['email'];

        $this->load->language('account/socnetauth2');

        $subject = $this->language->get('text_mail_subject');
        $message = $this->language->get('text_mail_body');
        $message = str_replace("%", $code, $message);

        $mail = new Mail($this->config->get('config_mail_engine'));
        $mail->parameter = $this->config->get('config_mail_parameter');
        $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
        $mail->smtp_username = $this->config->get('config_mail_smtp_username');
        $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
        $mail->smtp_port = $this->config->get('config_mail_smtp_port');
        $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

        $mail->setTo($data['email']);
        $mail->setFrom($this->config->get('config_email'));
        $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
        $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
        $mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
        $mail->send();
    }

    public function checkNew($data)
    {
        if (empty($data['identity'])) exit('EMPTY ID');
        $identitis = array();

        $identitis[] = $data['identity'];
        $identitis[] = str_replace("http://", "https://", $data['identity']);
        $identitis[] = str_replace("https://", "http://", $data['identity']);
        $identitis[] = str_replace("https://", "https://www.", $data['identity']);
        $identitis[] = str_replace("http://", "http://www.", $data['identity']);
        $identitis[] = str_replace("http://www.", "http://", $data['identity']);
        $identitis[] = str_replace("https://www.", "https://", $data['identity']);
        $identitis[] = str_replace("https://www.", "", $data['identity']);
        $identitis[] = str_replace("https://", "", $data['identity']);
        $identitis[] = str_replace("http://www.", "", $data['identity']);
        $identitis[] = str_replace("http://", "", $data['identity']);
        $identitis[] = str_replace("https://www.", "http://", $data['identity']);



        for ($i = 0; $i < count($identitis); $i++) {
            $identitis[$i] = " sc.identity='" . $this->db->escape($identitis[$i]) . "' ";
        }

        $wh = implode(" OR ", $identitis);
        $check1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "socnetauth2_customer2account sc
									JOIN " . DB_PREFIX . "customer c ON sc.email=c.email
                                   WHERE " . $wh);



                                   if($check1->row['status']==0){
                                       
                                       $check2 = $this->db->query("SELECT customer_id_main FROM " . DB_PREFIX . "customer_relation WHERE customer_id_child='". $check1->row['customer_id'] ."'");
                                       if($check2->num_rows){
                                                    $check1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id='". $check2->row['customer_id_main'] ."'");
                                                    $check1 = $check1->row;
                                                    $customer_id = $check1['customer_id'];
                                       }else{
                                                    $check1 = array();
                                                    $customer_id = 0;
                                       }
                                   }else{
                                        $check1 = $check1->row;
                                        $customer_id = $check1['customer_id'];
                                   }

        if (empty($check1) && $this->config->get('socnetauth2_dobortype') == 'one') {
            return false;
        } elseif (!empty($check1)) {


            $this->db->query("UPDATE `" . DB_PREFIX . "socnetauth2_customer2account` 
							  SET
								data = '" . $this->db->escape($data['data']) . "'
							  WHERE
							    identity = '" . $this->db->escape($data['identity']) . "'");

            $this->db->query("UPDATE `" . DB_PREFIX . "customer` 
							  SET
							  		ip = '" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "'
							  WHERE
                                customer_id = '" . $this->db->escape($check1['customer_id']) . "'");

                                $check4 = $this->db->query("SELECT telephone FROM " . DB_PREFIX . "customer_telephone WHERE telephone='" . $data['telephone'] . "'");

                                if(!$check4->rows){
            $this->db->query("INSERT INTO `" . DB_PREFIX . "customer_telephone`
                                        SET
                                                    telephone = '" . $data['telephone'] . "',
                                                    customer_id = '" . $this->db->escape($check1['customer_id']) . "'");
                                }
            /* start specific block: system/library/customer.php */
            
            if (!empty($check1['cart']) && is_string($check1['cart'])) {

                $cart = unserialize($check1['cart']);

                foreach ($cart as $key => $value) {
                    if (!array_key_exists($key, $this->session->data['cart'])) {
                        $this->session->data['cart'][$key] = $value;
                    } else {
                        $this->session->data['cart'][$key] += $value;
                    }
                }
            }

            if (!empty($check1['wishlist']) && is_string($check1['wishlist'])) {
                if (!isset($this->session->data['wishlist'])) {
                    $this->session->data['wishlist'] = array();
                }

                $wishlist = unserialize($check1['wishlist']);

                foreach ($wishlist as $product_id) {
                    if (!in_array($product_id, $this->session->data['wishlist'])) {
                        $this->session->data['wishlist'][] = $product_id;
                    }
                }
            }

            return $check1['customer_id'];

        } else {
            return false;
        }
    }


    public function isNeedConfirm($data)
    {

        $confirm_data = array();

        if ($this->config->get('socnetauth2_confirm_firstname_status') == 2 || ($this->config->get('socnetauth2_confirm_firstname_status') == 1 && empty($data['firstname']))) {
            $confirm_data['firstname'] = $data['firstname'];
        }

        if ($this->config->get('socnetauth2_confirm_lastname_status') == 2 || ($this->config->get('socnetauth2_confirm_lastname_status') == 1 && empty($data['lastname']))) {
            $confirm_data['lastname'] = $data['lastname'];
        }

        if ($this->config->get('socnetauth2_confirm_email_status') == 2 || ($this->config->get('socnetauth2_confirm_email_status') == 1 && empty($data['email']))) {
            $confirm_data['email'] = $data['email'];
        }

        if ($this->config->get('socnetauth2_confirm_telephone_status') == 2 || ($this->config->get('socnetauth2_confirm_telephone_status') == 1 && empty($data['telephone']))) {
            $confirm_data['telephone'] = $data['telephone'];
        }
        /* kin insert metka: c1 */
        if ($this->config->get('socnetauth2_confirm_company_status') == 2 || ($this->config->get('socnetauth2_confirm_company_status') == 1 && empty($data['company']))) {
            $confirm_data['company'] = '';
        }

        if ($this->config->get('socnetauth2_confirm_address_1_status') == 2 || ($this->config->get('socnetauth2_confirm_address_1_status') == 1 && empty($data['address_1']))) {
            $confirm_data['address_1'] = '';
        }

        if ($this->config->get('socnetauth2_confirm_postcode_status') == 2 || ($this->config->get('socnetauth2_confirm_postcode_status') == 1 && empty($data['postcode']))) {
            $confirm_data['postcode'] = '';
        }

        if ($this->config->get('socnetauth2_confirm_city_status') == 2 || ($this->config->get('socnetauth2_confirm_city_status') == 1 && empty($data['city']))) {
            $confirm_data['city'] = '';
        }

        if ($this->config->get('socnetauth2_confirm_zone_status') == 2 || ($this->config->get('socnetauth2_confirm_zone_status') == 1 && empty($data['zone']))) {
            $confirm_data['zone'] = '';
        }

        if ($this->config->get('socnetauth2_confirm_country_status') == 2 || ($this->config->get('socnetauth2_confirm_country_status') == 1 && empty($data['country']))) {
            $confirm_data['country'] = '';
        }

        if (!$confirm_data) {
            return false;
        } else {
            return $confirm_data;
        }
    }


    public function addCustomer($data, $relation = false, $img_avatar_src='')
    {
        $fields = array(
            "firstname", "lastname", "email", "telephone", "company", "postcode",
            "country", "zone", "city", "address_1", "link", "photo"
        );

        foreach ($fields as $field) {
            if (!isset($data[$field])) {
                $data[$field] = '';
            }
        }

        $customer_group_id = $this->config->get('socnetauth2_' . $data['provider'] . '_customer_group_id', 'socnetauth2');


        if (!$customer_group_id)
            $customer_group_id = $this->config->get('config_customer_group_id', 'config');


        $customer_id = "";
        if(isset($data['customer_id'])){
                if (!empty($data['customer_id'])) {
                       $customer_id = $data['customer_id'];
                 }
        }

        if(empty($customer_id)){
            $sql = (!empty($data['telephone'])) ? "SELECT * FROM " . DB_PREFIX . "customer WHERE telephone='" . $data['telephone'] ."'" : "SELECT * FROM " . DB_PREFIX . "customer WHERE email='" . $data['email'] ."'" ;     
            $check1 = $this->db->query($sql);
                        if ($check1->rows) {
                            $customer_id = $check1->row['customer_id'];
                        }
        }



        if (empty($customer_id)) {
            $sql = "INSERT INTO " . DB_PREFIX . "customer 
							  SET
								firstname = '" . $this->db->escape($data['firstname']) . "',
								lastname = '" . $this->db->escape($data['lastname']) . "',
								email = '" . $this->db->escape($data['email']) . "',
                                telephone = '" . $this->db->escape($data['telephone']) . "',
								ip = '" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "',
								customer_group_id = '" . (int) $customer_group_id . "', 
								status = '1',";
            if ($relation) $sql .= $relation . "= '1',";
            if ($data['photo']) $sql .= "avatar_fb= '". $data['photo'] ."',";

            $sql .= "date_added = NOW()";

            $this->db->query($sql);
            $customer_id = $this->db->getLastId();



            


            $this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account` 
							  SET
								 identity = '" . $this->db->escape($data['identity']) . "',
								 provider = '" . $this->db->escape($data['provider']) . "',
								 data = '" . $this->db->escape($data['data']) . "',
								 link = '" . $this->db->escape($data['link']) . "',
                                 avatar = '" . $this->db->escape($data['photo']) . "',
								 email = '" . $this->db->escape($data['email']) . "',
								 customer_id = '" . (int) $customer_id . "'");
        } else {
            // $sql = "UPDATE " . DB_PREFIX . "customer 
			// 				  SET
			// 					firstname = '" . $this->db->escape($data['firstname']) . "',
			// 					lastname = '" . $this->db->escape($data['lastname']) . "',
			// 					email = '" . $this->db->escape($data['email']) . "',
			// 					telephone = '" . $this->db->escape($data['telephone']) . "',
			// 				    ip = '" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "'
			// 				  WHERE 
            //                     customer_id='" . $customer_id . "'";
                                $sql = "UPDATE " . DB_PREFIX . "customer SET
								email = '" . $this->db->escape($data['email']) . "',
								telephone = '" . $this->db->escape($data['telephone']) . "',
							    ip = '" . $this->db->escape($_SERVER['REMOTE_ADDR']) . "'
							  WHERE
                                customer_id='" . $customer_id . "'";

            $this->db->query($sql);
        }
        if ($this->config->get('socnetauth2_save_to_addr') != 'customer_only') {
            if (empty($data['customer_id'])) {

                $this->db->query("INSERT INTO " . DB_PREFIX . "address 
				SET 
				customer_id = '" . (int) $customer_id . "', 
				firstname = '" . $this->db->escape($data['firstname']) . "', 
				lastname = '" . $this->db->escape($data['lastname']) . "', 
				company = '" . $this->db->escape($data['company']) . "', 
				address_1 = '" . $this->db->escape($data['address_1']) . "', 
				postcode = '" . $this->db->escape($data['postcode']) . "', 
				city = '" . $this->db->escape($data['city']) . "', 
				zone_id = '" . (int) $data['zone'] . "', 
				country_id = '" . (int) $data['country'] . "'");

                $address_id = $this->db->getLastId();

                $this->db->query("UPDATE " . DB_PREFIX . "customer 
						  SET address_id = '" . (int) $address_id . "' 
						  WHERE customer_id = '" . (int) $customer_id . "'");
            } else {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer 
										   WHERE customer_id='" . (int) $data['customer_id'] . "'");

                if (!empty($query->row['address_id'])) {
                    $this->db->query("UPDATE " . DB_PREFIX . "address 
						SET  
							firstname = '" . $this->db->escape($data['firstname']) . "', 
							lastname = '" . $this->db->escape($data['lastname']) . "', 
							company = '" . $this->db->escape($data['company']) . "', 
							address_1 = '" . $this->db->escape($data['address_1']) . "', 
							postcode = '" . $this->db->escape($data['postcode']) . "', 
							city = '" . $this->db->escape($data['city']) . "', 
							zone_id = '" . (int) $data['zone'] . "', 
							country_id = '" . (int) $data['country'] . "'
						WHERE
							address_id = '" . (int) $query->row['address_id'] . "'");
                }
            }
        }

        return $customer_id;
    }
}
