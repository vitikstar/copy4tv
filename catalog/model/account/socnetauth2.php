<?php
class ModelAccountSocnetauth2 extends Model 
{
	public function checkNew($data)
	{
		if( empty($data['identity']) ) exit("EMPTY ID");
	
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
		
		for($i=0; $i<count($identitis); $i++)
		{
			$identitis[$i] = " identity='".$this->db->escape($identitis[$i])."' ";
		}
		
		$wh = implode(" OR ", $identitis);
		
		$check = $this->db->query("SELECT * FROM `" . DB_PREFIX . "socnetauth2_customer2account` sc
								   JOIN `" . DB_PREFIX . "customer` c
								   ON c.customer_id=sc.customer_id
								   WHERE ".$wh);
								   
		if( empty($check->rows) && $this->config->get('socnetauth2_dobortype') == 'one' )
		{
			return false;
		}
		elseif( !empty( $check->row ) )
		{
			$upd = '';
			
			if( !empty($data['firstname']) )
			{
				$upd .= " firstname = '".$this->db->escape($data['firstname'])."', ";
			}
			
			if( !empty($data['lastname']) )
			{
				$upd .= " lastname = '".$this->db->escape($data['lastname'])."', ";
			}
			
			if( !empty($data['telephone']) )
			{
				$upd .= " telephone = '".$this->db->escape($data['telephone'])."', ";
			}
			
			if( !empty($data['email']) )
			{
				$upd .= " email = '".$this->db->escape($data['email'])."', ";
			}
			
			$this->db->query("UPDATE " . DB_PREFIX . "customer 
							  SET
							  ". $upd ."
							  
								ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'
							  WHERE
							    socnetauth2_identity = '" .$this->db->escape($data['identity']) . "'");
			
			$this->db->query("UPDATE " . DB_PREFIX . "socnetauth2_customer2account 
							  SET
								data = '".$this->db->escape($data['data'])."'
							  WHERE
							    identity = '" .$this->db->escape($data['identity']) . "'");
			
			
			$customer_data = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer`
								   WHERE customer_id = '" .$this->db->escape($check['customer_id']) . "'");	

			if( !empty($customer_data->row['cart']) && is_string($customer_data->row['cart']) ) {
				$cart = unserialize($customer_data->row['cart']);
				
				foreach ($cart as $key => $value) {
					if (!array_key_exists($key, $this->session->data['cart'])) {
						$this->session->data['cart'][$key] = $value;
					} else {
						$this->session->data['cart'][$key] += $value;
					}
				}			
			}

			if ( !empty($customer_data->row['wishlist']) && is_string($customer_data->row['wishlist'])) {
				if (!isset($this->session->data['wishlist'])) {
					$this->session->data['wishlist'] = array();
				}
								
				$wishlist = unserialize($customer_data->row['wishlist']);
			
				foreach ($wishlist as $product_id) {
					if (!in_array($product_id, $this->session->data['wishlist'])) {
						$this->session->data['wishlist'][] = $product_id;
					}
				}			
			}

			return $customer_data->row['customer_id'];
		}
		else
		{
			return false;
		}
	}

	public function addCustomerAfterConfirm($data)
	{
		$query = $this->db->query("SELECT * 
									   FROM `" . DB_PREFIX . "customer`
									   WHERE `email`='".$this->db->escape($data['email'])."'");
			
		if( !empty($query->row) )
		{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account` 
								SET
								 identity = '" .$this->db->escape($data['identity']) . "',
								 provider = '".$this->db->escape($data['provider']) ."',
								 data = '".$this->db->escape($data['data'])."',
								 link = '".$this->db->escape($data['link'])."',
								 email = '".$this->db->escape($data['email'])."',
								 customer_id = '".(int)$query->row['customer_id']."'");
		}
		
		return $query->row['customer_id'];
	}
	
	public function addCustomer($data)
	{
		$fields = array("firstname", "lastname", "email", "telephone", "company", "postcode",
		"country", "zone", "city", "address_1", "link" );
		
		foreach($fields as $field)
		{
			if( !isset($data[$field]) )
			{
				$data[$field] = '';
			}
		}

		$customer_group_id = $this->config->get('socnetauth2_'.$data['provider'].'_customer_group_id');
		
		if( !$customer_group_id )
		$customer_group_id = $this->config->get('config_customer_group_id');
		
		$customer_id = '';
		
		if( !empty($data['data']) ) 
		{
			$data['data'] = preg_replace("/[\\\]+\'/", "'", $data['data']);			
		}
		
		if( $this->config->get('socnetauth2_email_auth') == 'noconfirm' && 
			!empty( $data['email'] ) )
		{
			$query = $this->db->query("SELECT * 
									   FROM `" . DB_PREFIX . "customer`
									   WHERE `email`='".$this->db->escape($data['email'])."'");
			
			if( !empty($query->row) )
			{
				$this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account` 
								SET
								 identity = '" .$this->db->escape($data['identity']) . "',
								 provider = '".$this->db->escape($data['provider']) ."',
								 data = '".$this->db->escape($data['data'])."',
								 link = '".$this->db->escape($data['link'])."',
								 email = '".$this->db->escape($data['email'])."',
								 customer_id = '".(int)$query->row['customer_id']."'");
			
				$customer_id = $query->row['customer_id'];
			}
		}
		else
		{
			if( empty($data['customer_id']) )
			{
				$this->db->query("INSERT INTO " . DB_PREFIX . "customer 
							  SET
								firstname = '".$this->db->escape($data['firstname'])."',
								lastname = '".$this->db->escape($data['lastname'])."',
								email = '".$this->db->escape($data['email'])."',
								telephone = '".$this->db->escape($data['telephone'])."',
								ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "',
								customer_group_id = '" . (int)$customer_group_id . "', 
								status = '1', 
								date_added = NOW()");
				
				
				$customer_id = $this->db->getLastId();
				
				$this->sendNewCustomerMail($data['email'], $customer_group_id);
								
				$this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_customer2account` 
							  SET
								 identity = '" .$this->db->escape($data['identity']) . "',
								 provider = '".$this->db->escape($data['provider']) ."',
								 data = '".$this->db->escape($data['data'])."',
								 link = '".$this->db->escape($data['link'])."',
								 email = '".$this->db->escape($data['email'])."',
								 customer_id = '".(int)$customer_id."'");
			
			}
			else
			{
				$this->db->query("UPDATE " . DB_PREFIX . "customer 
							  SET
								firstname = '".$this->db->escape($data['firstname'])."',
								lastname = '".$this->db->escape($data['lastname'])."',
								email = '".$this->db->escape($data['email'])."',
								telephone = '".$this->db->escape($data['telephone'])."',
							    ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'
							  WHERE 
								customer_id='".(int)$data['customer_id']."'");
				$customer_id = $data['customer_id'];
			}
		}

		if( $this->config->get('socnetauth2_save_to_addr')!='customer_only' )
		{
			if( empty($data['customer_id']) )
			{
			
				$this->db->query("INSERT INTO " . DB_PREFIX . "address 
				SET 
				customer_id = '" . (int)$customer_id . "', 
				firstname = '" . $this->db->escape($data['firstname']) . "', 
				lastname = '" . $this->db->escape($data['lastname']) . "', 
				company = '" . $this->db->escape($data['company']) . "', 
				address_1 = '" . $this->db->escape($data['address_1']) . "', 
				postcode = '" . $this->db->escape($data['postcode']) . "', 
				city = '" . $this->db->escape($data['city']) . "', 
				zone_id = '" . (int)$data['zone'] . "', 
				country_id = '" . (int)$data['country'] . "'");
		
				$address_id = $this->db->getLastId();
		
				$this->db->query("UPDATE " . DB_PREFIX . "customer 
						  SET address_id = '" . (int)$address_id . "' 
						  WHERE customer_id = '" . (int)$customer_id . "'");
			}
			else
			{
				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer 
										   WHERE customer_id='".(int)$data['customer_id']."'");
				
				if( !empty( $query->row['address_id'] ) )
				{
					$this->db->query("UPDATE " . DB_PREFIX . "address 
						SET  
							firstname = '" . $this->db->escape($data['firstname']) . "', 
							lastname = '" . $this->db->escape($data['lastname']) . "', 
							company = '" . $this->db->escape($data['company']) . "', 
							address_1 = '" . $this->db->escape($data['address_1']) . "', 
							postcode = '" . $this->db->escape($data['postcode']) . "', 
							city = '" . $this->db->escape($data['city']) . "', 
							zone_id = '" . (int)$data['zone'] . "', 
							country_id = '" . (int)$data['country'] . "'
						WHERE
							address_id = '" . (int)$query->row['address_id'] . "'");
				}
			}
		}
		
		return $customer_id;
	}
	

	public function sendNewCustomerMail($email, $customer_group_id)
	{
		$this->load->model('account/customer_group');
		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
		$this->load->language('mail/register');
		$subject = sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$message = sprintf($this->language->get('text_welcome'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')) . "\n\n";
		$message .= $this->language->get('text_login') . "\n";
		$message .= $this->url->link('account/login', '', true) . "\n\n";
		$message .= $this->language->get('text_thanks') . "\n";
		$message .= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
		
		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

		// Send to main admin email if new account email is enabled
		if (in_array('account', (array)$this->config->get('config_mail_alert'))) {
			
			$message = $this->language->get('text_signup') . "\n";
			$message .= $this->language->get('text_email') ." ". $email ."\n";

			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($this->language->get('text_new_customer'), ENT_QUOTES, 'UTF-8'));
			$mail->setHtml(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();

			// Send to additional alert emails if new account email is enabled
			$emails = explode(',', $this->config->get('config_mail_alert_email'));

			foreach ($emails as $email) {
				if (utf8_strlen($email) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}	
	}

	public function checkUniqEmail($email)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer` WHERE email='".$this->db->escape($email)."'");
		
		if( $query->row ) 
			return false;
		else 
			return true;
	}
	
	public function getOldDoborData($loginza_data)
	{
		$RES = array(
			"firstname" => "", 
			"lastname" => "", 
			"email" => "", 
			"telephone" => "",
			"company" => "", 
			"address_1" => "", 
			"postcode" => "", 
			"city" => "", 
			"zone" => "", 
			"country" => ""
		);

		$query = $this->db->query("SELECT * 
								   FROM `" . DB_PREFIX . "customer` c 
								   JOIN `" . DB_PREFIX . "socnetauth2_customer2account` sc
								   ON c.customer_id=sc.customer_id
								   WHERE 
									sc.identity='".$this->db->escape($loginza_data['data']['identity'])."'
								");
		
		if( empty($query->row) ) return;
		
		$RES['telephone'] = $query->row['telephone'];
		$RES['email'] = $query->row['email'];
		$RES['firstname'] = $query->row['firstname'];
		$RES['lastname'] = $query->row['lastname'];
		
		if( !empty($query->row['address_id']) )
		{
			$query_address = $this->db->query("SELECT * 
								   FROM `" . DB_PREFIX . "address` 
								   WHERE 
									address_id='".(int)$query->row['address_id']."'
								");
			
			if( !empty($query_address->row) )
			{
				
				$RES['company'] = $query_address->row['company'];
				$RES['address_1'] = $query_address->row['address_1'];
				$RES['postcode'] = $query_address->row['postcode'];
				
				$RES['city'] = $query_address->row['city'];
				$RES['zone'] = $query_address->row['zone_id'];
				$RES['country'] = $query_address->row['country_id'];
			}
		}
		
		return $RES;
	}
	
	public function sendConfirmEmail($data)
	{
		$res = $this->db->query("SHOW TABLES");
		$res = $res->rows;
		$installed = 0;
		foreach($res as $key=>$val)
		{
			foreach($val as $k=>$v)
			{
				if( $v == DB_PREFIX . 'socnetauth2_customer2account' )
				{
					$installed = 1;
				}
			}
		}
		
		if( $installed == 0 )
		{
			$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "socnetauth2_precode` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`identity` varchar(300) NOT NULL,
				`code` varchar(300) NOT NULL,
				`cdate` DATETIME NOT NULL,
				PRIMARY KEY (`id`)
				) ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
			$this->db->query($sql);
		}
		
		$code = md5( rand() );
		
		$this->db->query("INSERT INTO `" . DB_PREFIX . "socnetauth2_precode`
						  SET 
							`identity` = '".$this->db->escape($data['identity'])."',
							`code` = '".$this->db->escape($code)."',
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
		
		return $code;
	}
	
	public function checkConfirmCode($identity, $code)
	{
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "socnetauth2_precode` 
								   WHERE identity='".$this->db->escape($identity)."' 
								   AND code='".$this->db->escape($code)."'");
		
		$this->db->query("DELETE FROM `" . DB_PREFIX . "socnetauth2_precode` 
						  WHERE DATE_ADD(cdate, INTERVAL 1 DAY) < NOW() ");
		
		if( $query->row ) 
			return true;
		else 
			return false;
	}
	
}

?>