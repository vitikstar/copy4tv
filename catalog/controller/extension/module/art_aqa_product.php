<?php
/*
@author	Artem Serbulenko
@link	https://cmsshop.com.ua
@link	https://opencartforum.com/profile/762296-bn174uk/
@email 	serfbots@gmail.com
*/
class ControllerExtensionModuleArtAqaProduct extends Controller { 
    public function sendMessage() {
    	$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			$this->load->language('extension/module/art_aqa_product');
			
			$fields = array(
				'f_name',
				'f_email',
				'f_telephone',
				'f_question',
				'f_communication',
				'product_id',
				'theme',
				'product_name'
			);

			foreach ($fields as $key) {
				if (isset($this->request->post[$key])) {
					$data[$key] = $this->request->post[$key];			
				} else {
					$data[$key] = '';
				}
			}

			$data['ip'] = $this->request->server['REMOTE_ADDR'];

			if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR'];
			} elseif (!empty($this->request->server['HTTP_CLIENT_IP'])) {
				$data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];
			} else {
				$data['forwarded_ip'] = '';
			}

			if (isset($this->request->server['HTTP_USER_AGENT'])) {
				$data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];
			} else {
				$data['user_agent'] = '';
			}

			if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
				$data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];
			} else {
				$data['accept_language'] = '';
			}


   			$json = json_decode($this->validForm($this->request->post));

   			if (!isset($json->error)) {

				$this->load->model('extension/module/art_aqa_product');
				$this->model_extension_module_art_aqa_product->addAqaProduct($data);
				
				$json['success'] = true;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
    }

    public function success() {
		$this->load->language('extension/module/art_aqa_product');
			
		$data['text_art_aqa_product_title'] = $this->language->get('text_art_aqa_product_title');

		$lang_id = $this->config->get('config_language_id');
		$data['text_success'] = isset($this->config->get('module_art_aqa_product_send_success')[$lang_id]) ? $this->config->get('module_art_aqa_product_send_success')[$lang_id] : '';
		
		if (empty($success)) {
			$data['text_success'] = $this->language->get('text_success');
		}

		$this->response->setOutput($this->load->view('extension/module/art_aqa_product_success', $data));
	}

    protected function validForm($data){
		
		$this->load->language('extension/module/art_aqa_product');

		$json = array();

		if ($this->config->get('module_art_aqa_product_f_name') == 2) {
			if (utf8_strlen($data['f_name']) < 1) {
				$json['error'] = $this->language->get('error_name');
			}
		}

		if ($this->config->get('module_art_aqa_product_f_question') == 2) {
			if (utf8_strlen($data['f_question']) < 10 || (utf8_strlen($data['f_question']) > 1000)) {
				$json['error'] = $this->language->get('error_comment');
			}
		}
		
		if ($this->config->get('module_art_aqa_product_f_select')) {
			if ((utf8_strlen($data['f_email']) > 96 || !filter_var($data['f_email'], FILTER_VALIDATE_EMAIL)) && ((utf8_strlen($data['f_telephone']) < 3) || (utf8_strlen($data['f_telephone']) > 32))) {
				$json['error'] = $this->language->get('error_select');
			}
		} else {
			if ($this->config->get('module_art_aqa_product_f_email') == 2) {
				if (utf8_strlen($data['f_email']) > 96 || !filter_var($data['f_email'], FILTER_VALIDATE_EMAIL)) {
					$json['error'] = $this->language->get('error_email');
				}
			}	
			if ($this->config->get('module_art_aqa_product_f_telephone') == 2) {
				if ((utf8_strlen($data['f_telephone']) < 3) || (utf8_strlen($data['f_telephone']) > 32)) {
					$json['error'] = $this->language->get('error_telephone');
				}
			}					
		}

		if ($this->config->get('module_art_aqa_product_f_personal_data') == 2) {
			if (!isset($data['f_personal_data'])) {
				$json['error'] = $this->language->get('error_personal_data');
			}
		}

		if ($this->config->get('module_art_aqa_product_f_captcha') == 2) {
			$captcha = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha') . '/validate');

			if ($captcha) {
				$json['error'] = $captcha;
			}
		}

		return json_encode($json);
	}

	public function getForm($arg = '') {
		if ($this->config->get('module_art_aqa_product_status') == 1) {

			$this->load->language('extension/module/art_aqa_product');
			$data['art_aqa_product_title'] = $this->language->get('text_art_aqa_product_title');
			$data['button_continue'] = $this->language->get('button_continue');
			$data['text_loading'] = $this->language->get('text_loading');


			$fields = array(
				'f_name',
				'f_email',
				'f_telephone',
				'f_question',
				'f_personal_data',
				'f_communication',
				'f_select',
			);

			$form_input = array();
			$form = array();
			$customer_logged = false;

			foreach ($fields as $key) {
				$form_input[$key] = $this->config->get('module_art_aqa_product_' . $key) ? $this->config->get('module_art_aqa_product_' . $key) : 0;
			}

			if ($this->customer->isLogged()) {
				$customer_logged = true;
			}

		   	foreach ($form_input as $key => $value) {
	   			if ($value) {
	   				$lang_id = $this->config->get('config_language_id');
	   				$text = isset($this->config->get('module_art_aqa_product_text_' . $key)[$lang_id]) ? $this->config->get('module_art_aqa_product_text_' . $key)[$lang_id] : '';

	   				if (!empty($text)) {
						$title_text = $text;
					} else {
						$title_text = $this->language->get('text_' . $key);
					}

					$val = '';
					if ($customer_logged) {
						switch ($key) {
							case 'f_name':
								$val = $this->customer->getFirstName();
								break;
							case 'f_telephone':
								$val = $this->customer->getTelephone();
								break;
							case 'f_email':
								$val = $this->customer->getEmail();
								break;
						}
					}

					$form[$key] = array(
						'title' 	=> $title_text,
						'name'		=> $key,
						'required' 	=> $value,
						'value'		=> $val,
					);
	   			}
		   	}

		   	if ($this->config->get('module_art_aqa_product_f_captcha')) {
				$form['f_captcha'] = $this->load->controller('extension/captcha/' . $this->config->get('config_captcha'));
			} else {
				$form['f_captcha'] = '';
			}

			$data['art_aqa_product_form_type'] = $this->config->get('module_art_aqa_product_form');

			$data['art_aqa_product_form'] = $form;

			$data['art_form'] = $this->load->view('extension/module/art_aqa_product_form', $data);
			
			$data['product_question'] = $this->getQuestion();

			return $this->load->view('extension/module/art_aqa_product', $data);
		} 
	}

	public function getQuestion($product_id = 0){
		$this->load->model('extension/module/art_aqa_product');

		$this->load->model('tool/image');

		if (isset($this->request->get['art_product_id'])) {
			$art_product_id = $this->request->get['art_product_id'];
		} else {
			$art_product_id = $product_id;
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (!empty($this->config->get('module_art_aqa_product_limit'))) {
			$limit = $this->config->get('module_art_aqa_product_limit');
		} else {
			$limit = 20;
		}

		$questions = $this->model_extension_module_art_aqa_product->getAqaProduct($art_product_id, ($page - 1) * $limit, $limit);
		$question_total = $this->model_extension_module_art_aqa_product->getTotalQuestion($art_product_id);

		$data['product_question'] = array();

		foreach ($questions as $question) {
			if ($question['image']) {
				$image = $this->model_tool_image->resize($question['image'], 40, 40);
			} else {
				$image = '';
			}

			if ($question['date_fake'] == '0000-00-00 00:00:00'){
				$question['date_fake'] = $question['date_added'];
			}

			$data['product_question'][] = array(
				'name' 		  => $question['name'],
				'question'    => $question['question'],
				'answer_name' => $question['answer_name'],
				'answer' 	  => html_entity_decode($question['answer'], ENT_QUOTES, 'UTF-8'),
				'logo'		  => $image,
				'date_added'  => date($this->language->get('date_format_short'), strtotime($question['date_fake'])),
				'date_answer'  => date($this->language->get('date_format_short'), strtotime($question['date_answer'])),
			);
		}

		$pagination = new Pagination();
		$pagination->total = $question_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		$pagination->url = $this->url->link('extension/module/art_aqa_product/getQuestion', 'art_product_id=' . $art_product_id . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($question_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($question_total - $limit)) ? $question_total : ((($page - 1) * $limit) + $limit), $question_total, ceil($question_total / $limit));

		if (isset($this->request->get['art_product_id'])) {
			return $this->response->setOutput($this->load->view('extension/module/art_aqa_product_questions', $data));
		} else {
			return $this->load->view('extension/module/art_aqa_product_questions', $data);
		}
	}
}
			
					

