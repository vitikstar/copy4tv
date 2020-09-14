<?php
/*
@author	Artem Serbulenko
@link	https://cmsshop.com.ua
@link	https://opencartforum.com/profile/762296-bn174uk/
@email 	serfbots@gmail.com
*/
class ModelExtensionModuleArtAqaProduct extends Model {
	public function addAqaProduct($data) {					
		$this->db->query("INSERT INTO " . DB_PREFIX . "art_aqa_product SET email = '" . $this->db->escape($data['f_email']) . "',theme = '" . $this->db->escape($data['theme']) . "', phone = '" . $this->db->escape($data['f_telephone']) . "', product_id = '" . (int)$data['product_id'] . "', question = '" . $this->db->escape($data['f_question']) . "', name = '" . $this->db->escape($data['f_name']) . "', communication = '" . $this->db->escape($data['f_communication']) . "', ip = '" . $this->db->escape($data['ip']) . "', forwarded_ip = '" . $this->db->escape($data['forwarded_ip']) . "', user_agent = '" . $this->db->escape($data['user_agent']) . "', accept_language = '" . $this->db->escape($data['accept_language']) . "', language_id = '" . (int)$this->config->get('config_language_id') . "', date_added = NOW(), date_fake = NOW()");
		
		$aqa_id = $this->db->getLastId();		

		$this->load->language('extension/module/art_aqa_product');
		
		$art_aqa_product = array(
			'f_name',
			'f_email',
			'f_telephone',
			'f_question',
			'f_communication'
		);

		$message = $this->language->get('text_header') . ' ' . $this->request->server['HTTP_HOST'] . "\n";

		$lang_id = $this->config->get('config_language_id');

		foreach ($art_aqa_product as $key) {
			if (isset($data[$key])) {
   				$text = isset($this->config->get('module_art_aqa_product_text_' . $key)[$lang_id]) ? $this->config->get('module_art_aqa_product_text_' . $key)[$lang_id] : '';

   				if (!empty($text)) {
					$title_text = $text;
				} else {
					$title_text = $this->language->get('text_' . $key);
				}
				$message .= '<b>' . $title_text . ': </b>' . $data[$key] . '<br>';
			}
		}	
		
		$subject = $this->config->get('config_name') . ': '. $this->language->get('text_header') . ' "' . $data['product_name'] . '"';	

		$message .= '<b>' . $this->language->get('text_product_name') . ': </b>'  . $data['product_name'] . "<br>";
		$message .= '<b>' . $this->language->get('text_link') . ': </b>' . $this->url->link('product/product', 'product_id=' . (int)$data['product_id']) . "<br>";


		$emails = $this->config->get('module_art_aqa_product_email');

		if (strlen($emails) == 0) {
			$emails = $this->config->get('config_email');
		}

		if (strlen($emails) > 0) {
		    $mail = new Mail($this->config->get('config_mail_engine'));
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
			$mail->smtp_username = $this->config->get('config_mail_smtp_username');
			$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
			$mail->smtp_port = $this->config->get('config_mail_smtp_port');
			$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');  

		    $mail->setFrom($this->config->get('config_email'));
		    $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		    $mail->setSubject($subject);
		    $mail->setHtml($message);

			$emails = explode(',', $emails);

			foreach ($emails as $email) {
				if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$mail->setTo($email);
					$mail->send();
				}
			}
		}

		$telegra_id = $this->config->get('module_art_aqa_product_id');

		if (strlen($telegra_id) > 0) {
		    $message = str_replace('<br>', PHP_EOL , $message);
	    	$link = 'https://api.telegram.org/bot';
	    
	        $bot_token = $this->config->get('module_art_aqa_product_token');
	        $chat_ids = explode(",", $telegra_id);

	        $sendToTelegram = $link . $bot_token;
	        foreach ($chat_ids as $chat_id) {
	            $chat_id = trim($chat_id);
				$params = [
				    'chat_id' => $chat_id,
				    'text' => $message,
				    'parse_mode' =>'html'
				];
				$ch = curl_init($sendToTelegram . '/sendMessage');
				curl_setopt($ch, CURLOPT_HEADER, false);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
				curl_setopt($ch, CURLOPT_TIMEOUT, 10);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, ($params));
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_exec($ch);
				curl_close($ch);
	        }
	    }

	    if (!empty($data['f_email'])) {
			if ($this->config->get('module_art_aqa_product_mail_question_status')) {
				$subject = $this->config->get('module_art_aqa_product_mail_question_title')[$lang_id];
				$template = $this->config->get('module_art_aqa_product_mail_question')[$lang_id];
				$message_temp = array();

				$info_question = array(
					'aqa_id',
					'store_name',
					'store_url',
					'date_added',
					'firstname',
					'question',
					'product',
					'product_link'
				);

				$store_id = $this->config->get('config_store_id');

				if ($store_id) {
					$store_url = $this->config->get('config_url');
				} else {
					if ($this->request->server['HTTPS']) {
						$store_url = HTTPS_SERVER;
					} else {
						$store_url = HTTP_SERVER;
					}
				}

				$message_temp['aqa_id'] = $aqa_id;
				$message_temp['store_name'] = $this->config->get('config_name');
				$message_temp['store_url'] = '<a href="' . $store_url . '">' . $this->config->get('config_name') . '</a>';;
				$message_temp['date_added'] = date("m.d.Y");
				$message_temp['firstname'] = $data['f_name'];
				$message_temp['question'] = $data['f_question'];
				$message_temp['product'] = $data['product_name'];
				$message_temp['product_link'] = '<a href="' . $this->url->link('product/product', 'product_id=' . (int)$data['product_id']) . '">' . $data['product_name'] . '</a>';

				foreach ($info_question as $key => $value) {
					$info_question[$key] = '{'.$value.'}';
				}

				if (!empty($template)) {
					$message = html_entity_decode(str_replace($info_question, $message_temp, $template));
					$subject = html_entity_decode(str_replace($info_question, $message_temp, $subject));
					
					$mail = new Mail($this->config->get('config_mail_engine'));
					$mail->parameter = $this->config->get('config_mail_parameter');
					$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
					$mail->smtp_username = $this->config->get('config_mail_smtp_username');
					$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
					$mail->smtp_port = $this->config->get('config_mail_smtp_port');
					$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout'); 

					$mail->setTo($data['f_email']);
				    $mail->setFrom($this->config->get('config_email'));
				    $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				    $mail->setSubject($subject);
				    $mail->setHtml($message);
				   	$mail->send();
			   	}
			}
		}
	}

	public function getAqaProduct($product_id, $start = 0, $limit = 20) {
		
		if ($start < 0) {
			$start = 0;
		}

		if ($limit < 1) {
			$limit = 20;
		}

		$query = $this->db->query("SELECT name, question, answer_name, answer, image, date_added, date_fake, date_answer FROM " . DB_PREFIX . "art_aqa_product WHERE product_id = '" . (int)$product_id . "' AND status = 2 ORDER BY date_fake DESC LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}

	public function getTotalQuestion($product_id) {
		$query = $this->db->query("SELECT COUNT(*) as total FROM " . DB_PREFIX . "art_aqa_product WHERE product_id = '" . (int)$product_id . "' AND status = 2");

		return $query->row['total'];
	}

}