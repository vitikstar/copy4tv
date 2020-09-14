<?php
class ModelExtensionModulePReview extends Model {
	public function getReview($p_review_id) {
		$query = $this->db->query("SELECT pr.*, pr.name AS name, pd.name AS product, p.image AS product_image FROM " . DB_PREFIX . "p_review pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (pr.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "p_review_to_store pr2s ON (pr.p_review_id = pr2s.p_review_id) WHERE pr.p_review_id = '" . (int)$p_review_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pr.status = '1'");
		
		if ($query->num_rows) {
			return array(
				'title'      	=> $query->row['title'],
				'city'       	=> $query->row['city'],
				'name'       	=> $query->row['name'],
				'text'       	=> $query->row['text'],
				'good'       	=> $query->row['good'],
				'bad'        	=> $query->row['bad'],
				'comment'    	=> $query->row['comment'],
				'rating'     	=> $query->row['rating'],
				'date_added' 	=> $query->row['date_added'],
				'avatar'     	=> $query->row['avatar'],
				'image'      	=> $query->row['image'],
				'product_id'    => $query->row['product_id'],
				'product'      	=> $query->row['product'],
				'product_image'	=> $query->row['product_image'],
			);
		} else {
			return false;
		}
	}
	
	public function getReviews($product_id, $data = array()) {
		if ($product_id) {
			$product = "pr.product_id='" . (int)$product_id . "' AND ";
		} else {
			$product = "";
		}
		
		if (!$this->config->get('module_p_review_language')) {
			$language = "pr.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ";
		} else {
			$language = '';
		}
		
		if ($this->config->get('module_p_review_rating') && ($data['rating'] != 'all')) {
			if ($data['filter_rating']) {
				$rating = "pr.rating = '" . (int)$data['rating'] . "' AND ";
			} else {
				$rating = "pr.rating >= '" . (int)$data['rating'] . "' AND ";
			}
		} else {
			$rating = '';
		}
		
		$sql = "SELECT pr.*, pr.name AS name, pd.name AS product, p.image AS product_image FROM " . DB_PREFIX . "p_review pr LEFT JOIN " . DB_PREFIX . "product p ON (pr.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_description pd ON (pr.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "p_review_to_store pr2s ON (pr.p_review_id = pr2s.p_review_id) WHERE " . $language . $rating . $product . "pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pr.status = '1' AND pr2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		$sort_data = array(
			'pr.date_added',
			'pr.rating'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $data['sort'];
		} else {
			$sql .= " ORDER BY pr.date_added";
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

			$sql .= " LIMIT " . (int)$data['start'] . ", " . (int)$data['limit'];
		}
		
		$query = $this->db->query($sql);

		return $query->rows;
	}
	
	public function getTotalReviews($product_id, $data = array()) {
		if ($product_id) {
			$product = "pr.product_id='" . (int)$product_id . "' AND ";
		} else {
			$product = "";
		}
		
		if (!$this->config->get('module_p_review_language')) {
			$language = "pr.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ";
		} else {
			$language = '';
		}
		
		if ($this->config->get('module_p_review_rating') && isset($data['rating']) && ($data['rating'] != 'all')) {
			if ($this->config->get('module_p_review_filter_rating')) {
				$rating = "pr.rating = '" . (int)$data['rating'] . "' AND ";
			} else {
				$rating = "pr.rating >= '" . (int)$data['rating'] . "' AND ";
			}
		} else {
			$rating = '';
		}
		
		$query = $this->db->query("SELECT COUNT(DISTINCT pr.p_review_id) AS total FROM " . DB_PREFIX . "p_review pr LEFT JOIN " . DB_PREFIX . "p_review_to_store pr2s ON (pr.p_review_id = pr2s.p_review_id) WHERE " . $language .  $rating . $product . "pr.status = '1' AND pr2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");
			
		return $query->row['total'];
	}
	
	public function addReview($data) {
		$sql = "";
		
		if ($this->config->get('module_p_review_title') && isset($data['title']) && $data['title']) { 
			$sql .= "title = '" . $this->db->escape($data['title']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_city') && isset($data['city']) && $data['city']) { 
			$sql .= "city = '" . $this->db->escape($data['city']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_email') && isset($data['email']) && $data['email']) { 
			$sql .= "email='" . $this->db->escape($data['email']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_name') && isset($data['name']) && $data['name']) { 
			$sql .= "name='" . $this->db->escape($data['name']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_text') && isset($data['text']) && $data['text']) { 
			$sql .= "text='" . $this->db->escape($data['text']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_good') && isset($data['good']) && $data['good']) { 
			$sql .= "good='" . $this->db->escape($data['good']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_bad') && isset($data['bad']) && $data['bad']) { 
			$sql .= "bad='" . $this->db->escape($data['bad']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_rating') && isset($data['rating']) && $data['rating']) { 
			$sql .= "rating='" . $this->db->escape($data['rating']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_avatar') && isset($data['avatar']) && $data['avatar']) { 
			$sql .= "avatar='" . $this->db->escape($data['avatar']) . "', "; 
		}
		
		if ($this->config->get('module_p_review_image') && isset($data['image']) && $data['image']) { 
			$sql .= "image='" . $this->db->escape($data['image']) . "', "; 
		}
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "p_review SET " . $sql . "store_name = '" . $this->db->escape($data['store_name']) . "', store_url = '" . $this->db->escape($data['store_url']) . "', product_id = '" . (int)$data['product_id'] . "', status = '" . (int)$data['status'] . "', language_id = '" . (int)$this->config->get('config_language_id') . "', date_added = NOW()");
		
		$p_review_id = $this->db->getLastId();
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "p_review_to_store SET p_review_id = '" . (int)$p_review_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "'");
		
		$seo_url = $this->config->get('module_p_review_seo_url');
		
		$this->db->query("INSERT INTO " . DB_PREFIX . "seo_url SET store_id = '" . (int)$this->config->get('config_store_id') . "', language_id = '" . (int)$this->config->get('config_language_id') . "', query = 'p_review_id=" . (int)$p_review_id . "', keyword = '" . $this->db->escape($seo_url[$this->config->get('config_store_id')][$this->config->get('config_language_id')]) . "-" . (int)$p_review_id . "'");

		if ($this->config->get('module_p_review_mail')) {
			$this->load->language('mail/p_review');
			
			$this->load->model('catalog/product');
			
			$product_info = $this->model_catalog_product->getProduct($data['product_id']);

			$subject = sprintf($this->language->get('text_subject'), html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8'));
			
			$text = $this->language->get('text_product') . " " . html_entity_decode($product_info['name'], ENT_QUOTES, 'UTF-8') . "\n\n";
			$text .= $this->language->get('text_link') . " " . $data['store_url'] . 'index.php?route=product/product&product_id=' . $data['product_id'] . "\n\n";
			
			$html['subject'] = sprintf($this->language->get('text_subject'), $product_info['name']);
			$html['text_product'] = $this->language->get('text_product');
			$html['product'] = $product_info['name'];
			$html['link'] = $data['store_url'] . 'index.php?route=product/product&product_id=' . $data['product_id'];
			
			$html['logo'] = $this->config->get('config_url') . 'image/' . $this->config->get('config_logo');
			$html['store_name'] = $data['store_name'];
			$html['store_url'] = $data['store_url'];
			
			if ($this->config->get('module_p_review_title') && isset($data['title']) && $data['title']) {
				$text .= $this->language->get('text_title') . " " . $data['title'] . "\n";
				
				$html['text_title'] = $this->language->get('text_title');
				$html['title'] = $this->db->escape($data['title']);
			}
			
			if ($this->config->get('module_p_review_city') && isset($data['city']) && $data['city']) {
				$text .= $this->language->get('text_city') . " " . $data['city'] . "\n";
				
				$html['text_city'] = $this->language->get('text_city');
				$html['city'] = $data['city'];
			}
			
			if ($this->config->get('module_p_review_name') && isset($data['name']) && $data['name']) {
				$text .= $this->language->get('text_name') . " " . $data['name'] . "\n";
				
				$html['text_name'] = $this->language->get('text_name');
				$html['name'] = $data['name'];
			}
			
			if ($this->config->get('module_p_review_email') && isset($data['email']) && $data['email']) {
				$text .= $this->language->get('text_email') . " " . $data['email'] . "\n";
				
				$html['text_email'] = $this->language->get('text_email');
				$html['email'] = $data['email'];
			}
			
			if ($this->config->get('module_p_review_rating') && isset($data['rating']) && $data['rating']) {
				$text .= $this->language->get('text_rating') . " " . $data['rating'] . "\n";
				
				$html['text_rating'] = $this->language->get('text_rating');
				$html['rating'] = $data['rating'];
			}
			
			if ($this->config->get('module_p_review_text') && isset($data['text']) && $data['text']) {
				$text .= "\n" . $this->language->get('text_text') . "\n" . $this->clearBBCode($data['text']) . "\n";
				
				$html['text_text'] = $this->language->get('text_text');
				$html['text'] = $this->config->get('module_p_review_editor') ? $this->replaceBBCode($data['text']) : $data['text'];
			}
			
			if ($this->config->get('module_p_review_good') && isset($data['good']) && $data['good']) {
				$text .= "\n" . $this->language->get('text_good') . "\n" . $this->clearBBCode($data['good']) . "\n";
				
				$html['text_good'] = $this->language->get('text_good');
				$html['good'] = $data['good'];
			}
			
			if ($this->config->get('module_p_review_bad') && isset($data['bad']) && $data['bad']) {
				$text .= "\n" . $this->language->get('text_bad') . "\n" . $this->clearBBCode($data['bad']) . "\n";
				
				$html['text_bad'] = $this->language->get('text_bad');
				$html['bad'] = $data['bad'];
			}
			
			$this->load->model('tool/image');
			
			if ($this->config->get('module_p_review_avatar') && isset($data['avatar']) && $data['avatar']) {
				$html['text_avatar'] = $this->language->get('text_avatar');
				$html['avatar'] = $this->model_tool_image->resize($data['avatar'], 100, 100);
			}
			
			if ($this->config->get('module_p_review_image') && isset($data['image']) && $data['image']) {
				$html['image'] = array();
				$html['text_image'] = $this->language->get('text_image');
				
				foreach (explode('|', $data['image']) as $value) {
					$html['image'][] = $this->model_tool_image->resize($value, 100, 100);
				}
			}

			$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');
	
			$mail->setTo($this->config->get('config_email'));
			$mail->setFrom($this->config->get('config_email'));
			$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
			$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
			$mail->setHtml($this->load->view('mail/p_review', $html));
			$mail->setText($text);
			$mail->send();
	
			// Send to additional alert emails
			$emails = explode(',', $this->config->get('config_mail_alert_email'));
	
			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}	
	}
	
	public function getRating($product_id) {
		if (!$this->config->get('module_p_review_language')) {
			$language = "pr.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ";
		} else {
			$language = '';
		}
		
		$sql = "SELECT AVG(rating) AS total FROM " . DB_PREFIX . "p_review pr LEFT JOIN " . DB_PREFIX . "p_review_to_store pr2s ON (pr.p_review_id = pr2s.p_review_id) WHERE " . $language . "pr.product_id = '" . (int)$product_id . "' AND pr.rating > 0 AND pr.status = '1' AND pr2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		$query = $this->db->query($sql);

		if (isset($query->row['total'])) {
			return round($query->row['total']);
		} else {
			return 0;
		}
	}
	
	public function getRatingTotal($product_id) {
		if (!$this->config->get('module_p_review_language')) {
			$language = "pr.language_id = '" . (int)$this->config->get('config_language_id') . "' AND ";
		} else {
			$language = '';
		}
		
		$sql = "SELECT COUNT(rating) AS total FROM " . DB_PREFIX . "p_review pr LEFT JOIN " . DB_PREFIX . "p_review_to_store pr2s ON (pr.p_review_id = pr2s.p_review_id) WHERE " . $language . "pr.product_id = '" . (int)$product_id . "' AND pr.rating > 0 AND pr.status = '1' AND pr2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		$query = $this->db->query($sql);

		if (isset($query->row['total'])) {
			return $query->row['total'];
		} else {
			return 0;
		}
	}
	
	private function getRandomFileName($path, $filename) {
		do {
			$filename = $path . token(32) . '.' . pathinfo($filename, PATHINFO_EXTENSION);
		} while ( 
			file_exists(DIR_IMAGE . $filename) 
		);
		
		return $filename;
	}
	
	public function uploadFile($folder, $file) {
		$folder = 'catalog/p_review/' . $folder . '/';
		$date_folder = date('Y') . '/' . date('m') . '/' . date('d') . '/';
		$path = $folder . $date_folder;
		
		if (!file_exists(DIR_IMAGE . $path)) {  
			$current = array(); 
			
			foreach (explode('/', $date_folder) as $value) { 
				
				if (!empty($value)) {
					$current[] = $value; 
					
					if (!file_exists(DIR_IMAGE . $folder . implode('/', $current))) {
						mkdir(DIR_IMAGE . $folder . implode('/', $current), 0766); 
					}
				}
			}  
		}
		
		$image_original = $this->getRandomFileName($path, $file['name']);
		
		move_uploaded_file($file['tmp_name'], DIR_IMAGE . $image_original);
		
		list($width, $height) = getimagesize(DIR_IMAGE . $image_original);
		
		$image = new Image(DIR_IMAGE . $image_original);
		$image->resize($width, $height);
		$image_new = $this->getRandomFileName($path, $image_original);
		$image->save(DIR_IMAGE . $image_new);
		
		unlink(DIR_IMAGE . $image_original);
		
		return $image_new;
	}
	
	public function clearBBCode($text_post) {
		$str_search = array();
		$str_replace = array();
						
		$smile_list = $this->getSmiles();
		
		$str_search = array(
			"#\[video\](.+?)\[\/video\]#is",
		    "#\\\n#is",
		    "#\[b\](.+?)\[\/b\]#is",
		    "#\[i\](.+?)\[\/i\]#is",
		    "#\[u\](.+?)\[\/u\]#is",
			"#\[s\](.+?)\[\/s\]#is",
		    "#\[quote\](.+?)\[\/quote\]#is",
		    "#\[url=(.+?)\](.+?)\[\/url\]#is",
		    "#\[url\](.+?)\[\/url\]#is",
		    "#\[size=(.+?)\](.+?)\[\/size\]#is",
		    "#\[color=(.+?)\](.+?)\[\/color\]#is",
		    "#\[list\](.+?)\[\/list\]#is",
		    "#\[list=1](.+?)\[\/list\]#is",
		    "#\[\*\](.+?)\[\/\*\]#"
		);
		
		foreach ($smile_list as $smile) {
			$str_search[] = "#\:" . $smile['name'] . "\:#";
		}
		
		$str_replace = array(
			"",
			" ",
			"\\1",
			"\\1",
			"\\1",
			"\\1",
			"\\1",
			"\\2",
			"\\1",
			"\\2",
			"\\2",
			"",
			"",
			"\\1"
		);
		
		foreach ($smile_list as $smile) {
			$str_replace[] = "";
		}
    
		return preg_replace($str_search, $str_replace, $text_post);
    }
	
	public function replaceBBCode($text_post) {
		$str_search = array();
		$str_replace = array();
						
		$smile_list = $this->getSmiles();

		$str_search = array(
			"#\[video\](.+?)\[\/video\]#is",
		    "#\\\n#is",
		    "#\[b\](.+?)\[\/b\]#is",
		    "#\[i\](.+?)\[\/i\]#is",
		    "#\[u\](.+?)\[\/u\]#is",
			"#\[s\](.+?)\[\/s\]#is",
		    "#\[quote\](.+?)\[\/quote\]#is",
		    "#\[url=(.+?)\](.+?)\[\/url\]#is",
		    "#\[url\](.+?)\[\/url\]#is",
		    "#\[size=(.+?)\](.+?)\[\/size\]#is",
		    "#\[color=(.+?)\](.+?)\[\/color\]#is",
		    "#\[list\](.+?)\[\/list\]#is",
		    "#\[list=1](.+?)\[\/list\]#is",
		    "#\[\*\](.+?)\[\/\*\]#"
		);
		
		foreach ($smile_list as $smile) {
			$str_search[] = "#\:" . $smile['name'] . "\:#";
		}
		
		$str_replace = array(
			"<iframe width=\"320\" height=\"240\" src=\"https://www.youtube.com/embed/\\1\" frameborder=\"0\"model_extension_module_p_reviewow=\"autoplay\" encrypted-media\"model_extension_module_p_reviewowfullscreen></iframe>",
			"<br />",
			"<b>\\1</b>",
			"<i>\\1</i>",
			"<span style=\"text-decoration:underline\">\\1</span>",
			"<span style=\"text-decoration:line-through\">\\1</span>",
			"<blockquote class=\"blockquote\">\\1</blockquote>",
			"<a href=\"\\1\">\\2</a>",
			"<a href=\"\\1\">\\1</a>",
			"<span style=\"font-size:\\1%\">\\2</span>",
			"<span style=\"color:\\1\">\\2</span>",
			"<ul>\\1</ul>",
			"<ol>\\1</ol>",
			"<li>\\1</li>",
		);
		
		foreach ($smile_list as $smile) {
			$str_replace[] = "<img class=\"smile\" src=\"" . $smile['url'] . "\">";
		}
    
		return preg_replace($str_search, $str_replace, $text_post);
    }
	
	public function getSmiles() {
		$theme = $this->config->get('module_p_review_smile_theme');
		
		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}
		
		$server .= 'image/catalog/p_review/smile/' . $theme;
		
		$folder = DIR_IMAGE . 'catalog/p_review/smile/' . $theme;
		
		$filename_list = array_diff(scandir($folder), array('..', '.'));
		
		$smile_list = array();
		
		if ($filename_list) {
			foreach ($filename_list as $filename) {
				if (is_file($folder . '/' . $filename)) {
					$path = $server . '/' . $filename;
					
					$smile_list[] = array( 
						'url'  => $path,
						'name' => pathinfo($path, PATHINFO_FILENAME)
					);
				}
			}
		}
		
		return $smile_list;
	}
	
	public function getReviewLayoutId($p_review_id) {
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "p_review_to_layout WHERE p_review_id = '" . (int)$p_review_id . "' AND store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			return (int)$query->row['layout_id'];
		} else {
			return 0;
		}
	}
}