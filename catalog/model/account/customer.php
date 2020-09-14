<?php
class ModelAccountCustomer extends Model {
	public function addCustomer($data,$code_register=0,$register_email=0) {
		if(isset($data['email'])){
			if(!empty($data['email'])){
					$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE email LIKE '%" . $this->db->escape($data['email']) . "%'LIMIT 1");
					if($query->row){
								$customer_id = $query->row['customer_id'];
								$telephone = preg_replace("/[^0-9]/", '', $data['telephone']);
								$sql = "SELECT * FROM " . DB_PREFIX . "customer_telephone WHERE telephone LIKE '%" . $this->db->escape($telephone) . "%' AND customer_id='". (int)$customer_id ."' LIMIT 1";
								$query = $this->db->query($sql);
                                if (!$query->row) {
									$this->db->query("INSERT INTO " . DB_PREFIX . "customer_telephone SET telephone = '" . $this->db->escape($telephone) . "', customer_id = '" . (int)$customer_id . "'");
									return $customer_id;
								}
					}
			}
		}
		if (isset($data['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($data['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $data['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$this->load->model('account/customer_group');

		$customer_group_info = $this->model_account_customer_group->getCustomerGroup($customer_group_id);


		if(isset($data['name_and_surname_val'])){
            list($lastname, $firstname) = explode(" ",$data['name_and_surname_val']);
        }else{
		    $firstname = $data['firstname'];
		    $lastname = $data['lastname'];
        }
            $email = (isset($data['email_login_val'])) ? $data['email_login_val'] : $data['email'];
            $telephone = (isset($data['telephone_login_val'])) ? $data['telephone_login_val'] : $data['telephone'];
            $telephone = preg_replace('/[^0-9]/', '', $telephone);
            $password = isset($data['password']) ? $data['password'] : $code_register;



		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET register_email = '" . (int)$register_email . "', customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($firstname) . "', lastname = '" . $this->db->escape($lastname) . "', email = '" . $this->db->escape($email) . "', telephone = '" . $this->db->escape($telephone) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', date_added = NOW(),access_code='".$code_register."'");
		//$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_group_id = '" . (int)$customer_group_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape($data['telephone']) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "', salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', newsletter = '" . (isset($data['newsletter']) ? (int)$data['newsletter'] : 0) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '" . (int)!$customer_group_info['approval'] . "', date_added = NOW(),access_code='".$code_register."'");
		$customer_id = $this->db->getLastId();

		if ($customer_group_info['approval']) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'customer', date_added = NOW()");
		}


		return $customer_id;
	}

	public function editCustomerAvatar($customer_id, $avatar) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET avatar = '" . $this->db->escape($avatar) . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}
	public function editCustomer($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', email = '" . $this->db->escape($data['email']) . "', telephone = '" . $this->db->escape(preg_replace('/[^0-9]/', '', $data['telephone'])) . "', custom_field = '" . $this->db->escape(isset($data['custom_field']['account']) ? json_encode($data['custom_field']['account']) : '') . "' WHERE customer_id = '" . (int)$customer_id . "'");
		if(isset($data['address_id'])){

            $row = $this->db->query("SELECT * FROM " . DB_PREFIX . "address  WHERE address_id = '" . (int)$data['address_id'] . "'");

            if($row->row){
                $sql = "UPDATE " . DB_PREFIX . "address SET city = '" . $this->db->escape($data['city']) . "', country_id = '" . $this->db->escape($data['region']) . "', zone_id = '" . $this->db->escape($data['zone']) . "', address_1 = '" . $this->db->escape($data['address']) . "' WHERE address_id = '" . (int)$data['address_id'] . "'";
                $this->db->query($sql);
            }else{
                $sql = "INSERT INTO " . DB_PREFIX . "address SET firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "',city = '" . $this->db->escape($data['city']) . "', country_id = '" . $this->db->escape($data['region']) . "', zone_id = '" . $this->db->escape($data['zone']) . "', address_1 = '" . $this->db->escape($data['address']) . "', address_id = '" . (int)$data['address_id'] . "', customer_id='".$customer_id."'";
                $this->db->query($sql);
                $this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . $this->db->getLastId() . "' WHERE customer_id = '" . (int)$customer_id . "'");
            }
        }

	}
	public function emptyCheckEmptyPassword($customer_id) {
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE password = '' AND customer_id = '" . (int)$customer_id . "'");

        return ($query->row) ? true : false;
	}

	public function getAvatar($customer_id = ''){
		if(empty($customer_id)) $customer_id = $this->customer->getId();
	    $result = $this->db->query("SELECT avatar FROM " . DB_PREFIX . "customer WHERE  customer_id = '" . (int)$customer_id . "'");
	    return ($result->row) ? $result->row['avatar'] : 'placeholder.png';
    }

	public function checkRelationFb(){
	    $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE  customer_id = '" . (int)$this->customer->getId() . "' AND relation_fb!=0");
	    return ($result->row) ? true : false;
    }
	public function checkRelationGmail(){
	    $result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE  customer_id = '" . (int)$this->customer->getId() . "' AND relation_google!=0");
        return ($result->row) ? true : false;
    }
	public function addRelationFb($email,$telephone,$avatar='',$userdata=array()){
				if($email){
					$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE  email='" . $this->db->escape($email) . "'");
				}
				if(!$result->row and $telephone){
					$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE  telephone='" . $this->db->escape($telephone) . "'");
				}

			$customer_id = ($result->row) ? $result->row['customer_id'] : 0;
			if($customer_id){
				$sql = "UPDATE " . DB_PREFIX . "customer SET firstname_fb='". $userdata['first_name'] ."',relation_fb='" . $customer_id . "',socnetauth2_identity=1,avatar_fb = '" . $this->db->escape($avatar) . "' WHERE customer_id='" . $customer_id . "'";
				$this->db->query($sql);
				//$this->db->query("UPDATE " . DB_PREFIX . "customer SET avatar = '" . $this->db->escape($avatar) . "' WHERE customer_id='" . $customer_id . "' AND (avatar IS NULL OR avatar='')");
			}		
	}
	
	public function cancelRelationFb(){
	    $this->db->query("UPDATE " . DB_PREFIX . "customer SET relation_fb=0,avatar_fb='',firstname_fb='',socnetauth2_identity='' WHERE  customer_id = '" . (int)$this->customer->getId() . "'");
    }
	public function cancelRelationGmail(){
		 $this->db->query("UPDATE " . DB_PREFIX . "customer SET relation_google=0,avatar_google='',firstname_google='' WHERE  customer_id = '" . (int)$this->customer->getId() . "'");
    }

	public function addRelationGmail($email,$userdata=array()){
		if ($email) {
				$result = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE  email='" . $this->db->escape($email) . "'");
			}
		$customer_id = ($result->row) ? $result->row['customer_id'] : 0;

	    if($email==$this->customer->getEmail()) $this->db->query("UPDATE " . DB_PREFIX . "customer SET firstname_google='". $userdata['first_name'] ."',relation_google='" . (int)$customer_id . "' WHERE email='".$this->customer->getEmail()."' AND customer_id = '" . (int)$this->customer->getId() . "'");
    }

	public function editPassword($email, $password) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET salt = '" . $this->db->escape($salt = token(9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($password)))) . "', code = '' WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editAddressId($customer_id, $address_id) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");
	}
	
	public function editCode($email, $code) {
		$this->db->query("UPDATE `" . DB_PREFIX . "customer` SET code = '" . $this->db->escape($code) . "' WHERE LCASE(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}

	public function editNewsletter($newsletter) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer SET newsletter = '" . (int)$newsletter . "' WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getCustomer($customer_id) {
		$query1 = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		$query2 = $this->db->query("SELECT telephone FROM " . DB_PREFIX . "customer_telephone WHERE customer_id = '" . (int)$customer_id . "'");
		$customer_info = $query1->row;
		$telephones = array();
		$telephones[] = $customer_info['telephone'];
		foreach ($query2->rows as $row) {
			$telephones[]=$row['telephone'];
		}
		$customer_info = array_merge($customer_info,array('telephones'=>$telephones));
		return $customer_info;
	}

	public function getCustomerByEmail($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

    /**
     * @param $telephone +380XXXXXXXXX
     * @return mixed
     */
    public function getCustomerByPhone($telephone) {
		$telephone = preg_replace('/[^0-9]/', '', $telephone);
        $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE telephone = '" . $this->db->escape($telephone) . "' and status=1";
		$query = $this->db->query($sql);

		if(!$query->row){
				$sql = "SELECT * FROM  " . DB_PREFIX . "customer_telephone  WHERE telephone LIKE '%" . $this->db->escape($telephone) . "%' LIMIT 1";
				$query = $this->db->query($sql);
				if ($query->row) {
						$customer_id = $query->row['customer_id'];
						$sql = "SELECT * FROM  " . DB_PREFIX . "customer  WHERE customer_id='" . $customer_id . "' and status=1 LIMIT 1";
						$query = $this->db->query($sql);
				}
		}

		return $query->row;
	}
    public function getCheckCustomerByPhone($telephone) {
		$query = $this->db->query("SELECT  COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE telephone = '" . $this->db->escape(preg_replace('/[^0-9]/', '', $telephone)) . "'");

		return $query->row['total'];
	}

    public function getCustomerById($customer_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row;
	}

	public function checkCustomerByEmail($email){
        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");
        $customer_id = ($query->row) ? $query->row['customer_id'] : 0;
        return $customer_id;
    }
	public function getCustomerByCode($code) {
		$query = $this->db->query("SELECT customer_id, firstname, lastname, email FROM `" . DB_PREFIX . "customer` WHERE code = '" . $this->db->escape($code) . "' AND code != ''");

		return $query->row;
	}

	public function getCustomerByToken($token) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE token = '" . $this->db->escape($token) . "' AND token != ''");

		$this->db->query("UPDATE " . DB_PREFIX . "customer SET token = ''");

		return $query->row;
	}
	
	public function getTotalCustomersByEmail($email) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row['total'];
	}

	public function addTransaction($customer_id, $description, $amount = '', $order_id = 0) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_transaction SET customer_id = '" . (int)$customer_id . "', order_id = '" . (float)$order_id . "', description = '" . $this->db->escape($description) . "', amount = '" . (float)$amount . "', date_added = NOW()");
	}

	public function deleteTransactionByOrderId($order_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");
	}

	public function getTransactionTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}
	
	public function getTotalTransactionsByOrderId($order_id) {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer_transaction WHERE order_id = '" . (int)$order_id . "'");

		return $query->row['total'];
	}
	
	public function getRewardTotal($customer_id) {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->row['total'];
	}

	public function getIps($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_ip` WHERE customer_id = '" . (int)$customer_id . "'");

		return $query->rows;
	}

	public function addLoginAttempt($email) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_login WHERE email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

		if (!$query->num_rows) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_login SET email = '" . $this->db->escape(utf8_strtolower((string)$email)) . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', total = 1, date_added = '" . $this->db->escape(date('Y-m-d H:i:s')) . "', date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "'");
		} else {
			$this->db->query("UPDATE " . DB_PREFIX . "customer_login SET total = (total + 1), date_modified = '" . $this->db->escape(date('Y-m-d H:i:s')) . "' WHERE customer_login_id = '" . (int)$query->row['customer_login_id'] . "'");
		}
	}

	public function getLoginAttempts($email) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");

		return $query->row;
	}

	public function deleteLoginAttempts($email) {
		$this->db->query("DELETE FROM `" . DB_PREFIX . "customer_login` WHERE email = '" . $this->db->escape(utf8_strtolower($email)) . "'");
	}
	
	public function addAffiliate($customer_id, $data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer_affiliate SET `customer_id` = '" . (int)$customer_id . "', `company` = '" . $this->db->escape($data['company']) . "', `website` = '" . $this->db->escape($data['website']) . "', `tracking` = '" . $this->db->escape(token(64)) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape($data['tax']) . "', `payment` = '" . $this->db->escape($data['payment']) . "', `cheque` = '" . $this->db->escape($data['cheque']) . "', `paypal` = '" . $this->db->escape($data['paypal']) . "', `bank_name` = '" . $this->db->escape($data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape($data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape($data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($data['bank_account_number']) . "', `status` = '" . (int)!$this->config->get('config_affiliate_approval') . "'");
		
		if ($this->config->get('config_affiliate_approval')) {
			$this->db->query("INSERT INTO `" . DB_PREFIX . "customer_approval` SET customer_id = '" . (int)$customer_id . "', type = 'affiliate', date_added = NOW()");
		}		
	}
		
	public function editAffiliate($customer_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "customer_affiliate SET `company` = '" . $this->db->escape($data['company']) . "', `website` = '" . $this->db->escape($data['website']) . "', `commission` = '" . (float)$this->config->get('config_affiliate_commission') . "', `tax` = '" . $this->db->escape($data['tax']) . "', `payment` = '" . $this->db->escape($data['payment']) . "', `cheque` = '" . $this->db->escape($data['cheque']) . "', `paypal` = '" . $this->db->escape($data['paypal']) . "', `bank_name` = '" . $this->db->escape($data['bank_name']) . "', `bank_branch_number` = '" . $this->db->escape($data['bank_branch_number']) . "', `bank_swift_code` = '" . $this->db->escape($data['bank_swift_code']) . "', `bank_account_name` = '" . $this->db->escape($data['bank_account_name']) . "', `bank_account_number` = '" . $this->db->escape($data['bank_account_number']) . "' WHERE `customer_id` = '" . (int)$customer_id . "'");
	}
	
	public function getAffiliate($customer_id) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `customer_id` = '" . (int)$customer_id . "'");

		return $query->row;
	}
	
	public function getAffiliateByTracking($tracking) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_affiliate` WHERE `tracking` = '" . $this->db->escape($tracking) . "'");

		return $query->row;
	}
    public function getCountriesByCustomerId($customer_id) {

        $query = $this->db->query("SELECT co.name,co.country_id FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "address a ON(c.customer_id=a.customer_id) LEFT JOIN " . DB_PREFIX . "country co ON(a.country_id=co.country_id) WHERE c.customer_id = '".$customer_id."' AND c.address_id = a.address_id");

        return $query->row;
    }
    public function getZoneByCustomerId($customer_id) {

        $query = $this->db->query("SELECT z.name,z.zone_id,a.address_id FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "address a ON(c.customer_id=a.customer_id) LEFT JOIN " . DB_PREFIX . "zone z ON(a.zone_id=z.zone_id) WHERE c.customer_id = '".$customer_id."' AND c.address_id = a.address_id");

        return $query->row;
    }
    public function getCityByCustomerId($customer_id) {

        $query = $this->db->query("SELECT a.city FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "address a ON(c.customer_id=a.customer_id) WHERE c.customer_id = '".$customer_id."' AND c.address_id = a.address_id");

        return ($query->row) ? $query->row['city'] : '';
    }
    public function getAddressByCustomerId($customer_id) {

        $query = $this->db->query("SELECT a.address_1,a.address_2 FROM " . DB_PREFIX . "customer c LEFT JOIN " . DB_PREFIX . "address a ON(c.customer_id=a.customer_id) WHERE c.customer_id = '".$customer_id."' AND c.address_id = a.address_id");

        $address_1 = (isset($query->row['address_1'])) ? $query->row['address_1'] : '';
        $address_2 = (isset($query->row['address_2'])) ? $query->row['address_2'] : '';

        return ($address_1) ? $address_1 : $address_2 ;
    }
}