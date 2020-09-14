<?php
namespace Cart;
class Customer {
	private $customer_id;
	private $firstname;
	private $lastname;
	private $customer_group_id;
	private $email;
	private $telephone;
	private $newsletter;
	private $address_id;


	public function __construct($registry) {
		$this->config = $registry->get('config');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->session = $registry->get('session');

		if (isset($this->session->data['customer_id'])) {
			$customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND status = '1'");

			if ($customer_query->num_rows) {
				$this->customer_id = $customer_query->row['customer_id'];
				$this->firstname = $customer_query->row['firstname'];
				$this->lastname = $customer_query->row['lastname'];
				$this->customer_group_id = $customer_query->row['customer_group_id'];
				$this->email = $customer_query->row['email'];
				$this->telephone = $customer_query->row['telephone'];
				$this->newsletter = $customer_query->row['newsletter'];
				$this->address_id = $customer_query->row['address_id'];

				$this->db->query("UPDATE " . DB_PREFIX . "customer SET language_id = '" . (int)$this->config->get('config_language_id') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");

				$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_ip WHERE customer_id = '" . (int)$this->session->data['customer_id'] . "' AND ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "'");

				if (!$query->num_rows) {
					$this->db->query("INSERT INTO " . DB_PREFIX . "customer_ip SET customer_id = '" . (int)$this->session->data['customer_id'] . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', date_added = NOW()");
				}
			} else {
				$this->logout();
			}
		}
	}

  public function login($email_or_telephone, $password, $override = false) { //$email_or_telephone сюда поступают мило або телефон если $override=true то вход без пароля разрешон как для емайл так по телефону
      if (preg_match("/[0-9a-z]+@[a-z]/", $email_or_telephone)) {
          if ($override) {
              $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email_or_telephone)) . "' AND status = '1' AND email!=''";
          } else {
              $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email_or_telephone)) . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'";
          }
      }else{
		  $email_or_telephone = preg_replace("/[^0-9]/", '', $email_or_telephone);
         if ($override) {
              $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE telephone = '" . $email_or_telephone . "' AND status = '1' AND telephone!=''";
          } else {
              $sql = "SELECT * FROM " . DB_PREFIX . "customer WHERE telephone = '" . $email_or_telephone . "' AND (password = SHA1(CONCAT(salt, SHA1(CONCAT(salt, SHA1('" . $this->db->escape($password) . "'))))) OR password = '" . $this->db->escape(md5($password)) . "') AND status = '1'";
          }
	  }
	  
		$customer_query = $this->db->query($sql);
		if ($customer_query->num_rows) {
			$this->session->data['customer_id'] = $customer_id  = $customer_query->row['customer_id'];

			$this->customer_id = $customer_query->row['customer_id'];
			$this->firstname = $customer_query->row['firstname'];
			$this->lastname = $customer_query->row['lastname'];
			$this->customer_group_id = $customer_query->row['customer_group_id'];
			$this->email = $customer_query->row['email'];
			$this->telephone = $customer_query->row['telephone'];
			$this->newsletter = $customer_query->row['newsletter'];
			$this->address_id = $customer_query->row['address_id'];
		
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET language_id = '" . (int)$this->config->get('config_language_id') . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "' WHERE customer_id = '" . (int)$this->customer_id . "'");
			$result = $this->db->query("SELECT * FROM `" . DB_PREFIX . "customer_looked` WHERE customer_id='" . $customer_id . "' GROUP BY product_id ORDER BY looked_id DESC");

			$arr = array();

			if ($result->num_rows) {
				foreach ($result->rows as $product) {
					$arr[] = $product['product_id'];
				}
			}

			$this->session->data['matrosite']['looked_customer_login'] = $arr;
			return true;
		} else {
			return false;
		}
	}
    public function loginEmptyPassword($email){
        $customer_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer WHERE LOWER(email) = '" . $this->db->escape(utf8_strtolower($email)) . "' AND status = '1' AND password=''");
        return ($customer_query->num_rows) ? true : false;
    }
	public function logout() {
		
		unset($this->session->data['matrosite']['looked']);
		unset($this->session->data['customer_id']);

		$this->customer_id = '';
		$this->firstname = '';
		$this->lastname = '';
		$this->customer_group_id = '';
		$this->email = '';
		$this->telephone = '';
		$this->newsletter = '';
		$this->address_id = '';
	}

	public function isLogged() {
		return $this->customer_id;
	}

	public function getId() {
		return $this->customer_id;
	}

	public function getFirstName() {
		return $this->firstname;
	}
	public function getFirstNameFb() {
		$customer_query = $this->db->query("SELECT firstname_fb FROM " . DB_PREFIX . "customer WHERE relation_fb = '" . $this->customer_id . "' AND status = '1'");

		if($customer_query->num_rows){
			return $customer_query->row['firstname_fb'];
		}else{
			return $this->firstname;
		}
	}
	public function getFirstNameGmail() {
		$customer_query = $this->db->query("SELECT firstname_google FROM " . DB_PREFIX . "customer WHERE relation_google = '" . $this->customer_id . "' AND status = '1'");

		if($customer_query->num_rows){
			return $customer_query->row['firstname_google'];
		}else{
			return $this->firstname;
		}
	}


    public function getTelephone() {
        return $this->telephone;
    }

	public function getLastName() {
		return $this->lastname;
	}
	public function getFullContactsForCheckout() {
		return $this->lastname." ".$this->firstname.", ".$this->telephone.", ".$this->email;
	}

	public function getGroupId() {
		return $this->customer_group_id;
	}

	public function getEmail() {
		return $this->email;
	}



	public function getNewsletter() {
		return $this->newsletter;
	}

	public function getAddressId() {
		return $this->address_id;
	}

	public function getBalance() {
		$query = $this->db->query("SELECT SUM(amount) AS total FROM " . DB_PREFIX . "customer_transaction WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}

	public function getRewardPoints() {
		$query = $this->db->query("SELECT SUM(points) AS total FROM " . DB_PREFIX . "customer_reward WHERE customer_id = '" . (int)$this->customer_id . "'");

		return $query->row['total'];
	}
}
