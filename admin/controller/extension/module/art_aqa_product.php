<?php
/*
@author	Artem Serbulenko
@link	https://cmsshop.com.ua
@link	https://opencartforum.com/profile/762296-bn174uk/
@email 	serfbots@gmail.com
*/
class ControllerExtensionModuleArtAqaProduct extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/art_aqa_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting'); 

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_art_aqa_product', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if ($this->request->post['art_aqa_product_apply']) {
				$this->response->redirect($this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'], true));
			}
			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}		

		$data = array();

		$data = $this->getList();

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true),
		);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('marketplace/modification', 'user_token=' . $this->session->data['user_token'], true),
		);
		$data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'], true),
		);

		$data['action'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'], true);
		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'], true);
		$data['delete'] = $this->url->link('extension/module/art_aqa_product/delete', 'user_token=' . $this->session->data['user_token'], true);

		$data['user_token'] = $this->session->data['user_token'];

		$data_mas = array(
			'id',
			'token',
			'send',
			'status',
			'email',
			'send_success',
			'f_captcha',
			'td_name',
			'td_email',
			'td_question',
			'td_answer',
			'td_phone',
			'td_communication',
			'td_status',
			'td_date_added',
			'td_ip',
			'td_user_agent',
			'td_accept_language',
			'td_forwarded_ip',
			'mail_title',
			'mail_question',
			'mail_answer',
			'mail_question_title',
			'mail_answer_title',
			'mail_question_status',
			'mail_answer_status',
			'aqa_id',
            'store_name',
            'store_url',
            'date_added',
            'firstname',
            'question',
            'answer',
            'product',
            'product_link',
            'limit',
            'title_tab',
            'form',
            'default_title',
		);

		$data_mas_text = array(
			'f_name',
			'f_email',
			'f_telephone',
			'f_question',
			'f_personal_data',
			'f_communication',
			'f_select'
		);

		if (isset($this->request->post['module_art_aqa_product_default_image'])) {
			$data['module_art_aqa_product_default_image'] = $this->request->post['module_art_aqa_product_default_image'];
		} else {
			$data['module_art_aqa_product_default_image'] = $this->config->get('module_art_aqa_product_default_image');
		}

		$this->load->model('tool/image');

		$data['entry_default_image'] = $this->language->get('entry_default_image');

		if (is_file(DIR_IMAGE . $data['module_art_aqa_product_default_image'])) {
			$data['module_art_aqa_product_default_image_cache'] = $this->model_tool_image->resize($data['module_art_aqa_product_default_image'], 100, 100);
		} else {
			$data['module_art_aqa_product_default_image_cache'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		foreach ($data_mas as $key => $value) {
			if (isset($this->request->post['module_art_aqa_product_' . $value])) {
				$data['module_art_aqa_product_' . $value] = $this->request->post['module_art_aqa_product_' . $value];
			} else {
				$data['module_art_aqa_product_' . $value] = $this->config->get('module_art_aqa_product_' . $value);
			}
			$data['entry_' . $value] = $this->language->get('entry_' . $value);
		}

		foreach ($data_mas as $key => $value) {
			if (isset($this->request->post['art_aqa_product_'.$value])) {
				$data['module_art_aqa_product_'.$value] = $this->request->post['module_art_aqa_product_'.$value];
			} else {
				$data['module_art_aqa_product_'.$value] = $this->config->get('module_art_aqa_product_'.$value);
			}
		}

		foreach ($data_mas_text as $key => $value) {
			if (isset($this->request->post['art_aqa_product_'.$value])) {
				$data['module_art_aqa_product_'.$value] = $this->request->post['module_art_aqa_product_'.$value];
			} else {
				$data['module_art_aqa_product_'.$value] = $this->config->get('module_art_aqa_product_'.$value);
			}
			if (isset($this->request->post['art_aqa_product_text_'.$value])) {
				$data['module_art_aqa_product_text_'.$value] = $this->request->post['module_art_aqa_product_text_'.$value];
			} else {
				$data['module_art_aqa_product_text_'.$value] = $this->config->get('module_art_aqa_product_text_'.$value);
			}
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/art_aqa_product', $data));
	}

	public function getList() {
		$this->load->model('extension/module/art_aqa_product');
		
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'aqa.date_added';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['filter_status'])) {
			$filter_status = $this->request->get['filter_status'];
		} else {
			$filter_status = null;
		}

		if (isset($this->request->get['filter_product_id'])) {
			$filter_product_id = $this->request->get['filter_product_id'];
		} else {
			$filter_product_id = null;
		}
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		$data['art_aqa_products'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'filter_status' => $filter_status,
			'filter_product_id' => $filter_product_id,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
		
		$aqa_product_total = $this->model_extension_module_art_aqa_product->getTotalAqaProducts($filter_data);
		
		$results = $this->model_extension_module_art_aqa_product->getAqaProducts($filter_data);

    	foreach ($results as $result) {
    		switch ($result['status']) {
    			case '0':
    				$status = '<span style="background:#f00;padding:4px;border-radius:4px;color:#fff;">' . $this->language->get('text_in_processing') . '</span>';
    				break;
    			case '2':
    				$status = '<span style="background:#8fbb6c;padding:4px;border-radius:4px;color:#fff;">' . $this->language->get('text_publish') . '</span>';
    				break;
    			default:
    				$status = '<span style="background:#000;padding:4px;border-radius:4px;color:#fff;">' . $this->language->get('text_processed') . '</span>';
    				break;
    		}
    		
			$data['art_aqa_products'][] = array(
				'aqa_product_id'	=> $result['aqa_product_id'],
				'theme'		=> $result['theme'],
				'name'				=> $result['user_name'],
				'email'          	=> $result['email'],
				'phone'          	=> $result['phone'],
				'question'          => $result['question'],
				'answer'            => strip_tags(html_entity_decode($result['answer'])),
				'communication' 	=> $result['communication'],
				'status'            => $status,
				'ip'                => $result['ip'],
				'user_agent'        => $result['user_agent'],
				'accept_language'   => $result['accept_language'],
				'forwarded_ip'      => $result['forwarded_ip'],
				'date_added' 	 	=> $result['date_added'],
				'edit'				=> $this->url->link('extension/module/art_aqa_product/edit', 'user_token=' . $this->session->data['user_token'] . '&aqa_product_id=' . $result['aqa_product_id'] . $url, true),
				'delete'			=> $this->url->link('extension/module/art_aqa_product/delete', 'user_token=' . $this->session->data['user_token'] . '&aqa_product_id=' . $result['aqa_product_id'] . $url, true),
			);
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_theme'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . '&sort=theme' . $url, true);
		$data['sort_name'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . '&sort=aqa.name' . $url, true);
		$data['sort_email'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . '&sort=aqa.email' . $url, true);
		$data['sort_phone'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . '&sort=aqa.phone' . $url, true);
		$data['sort_date_added'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . '&sort=aqa.date_added' . $url, true);
		$data['sort_aqa_id'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . '&sort=aqa.aqa_product_id' . $url, true);
		$data['sort_ip'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . '&sort=aqa.ip' . $url, true);
		$data['sort_status'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . '&sort=aqa.status' . $url, true);

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}
		
		$pagination = new Pagination();
		$pagination->total = $aqa_product_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($aqa_product_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($aqa_product_total - $this->config->get('config_limit_admin'))) ? $aqa_product_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $aqa_product_total, ceil($aqa_product_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;

		$data['filter_status'] = $filter_status;
		$data['filter_product_id'] = $filter_product_id;

		return $data;
	}

	public function edit() {
		$this->language->load('extension/module/art_aqa_product');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('extension/module/art_aqa_product');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_extension_module_art_aqa_product->editAqaProduct($this->request->get['aqa_product_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success_product');

			if ($this->request->post['notification']) {
				$this->sendMessage($this->request->get['aqa_product_id']);
			}

			if ($this->request->post['art_aqa_product_apply']) {
				$this->response->redirect($this->url->link('extension/module/art_aqa_product/edit', 'user_token=' . $this->session->data['user_token'] . '&aqa_product_id=' . $this->request->get['aqa_product_id']));
			}

			$this->response->redirect($this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'], true));
		}

		$this->getForm();
	}

	public function delete() {
		$this->load->model('extension/module/art_aqa_product');

		if (isset($this->request->post['selected']) && $this->validate()) {
			foreach ($this->request->post['selected'] as $aqa_product_id) {
				$this->model_extension_module_art_aqa_product->deleteAqaProduct($aqa_product_id);
			}
		} else {
			if (isset($this->request->get['aqa_product_id']) && $this->validate()) {
				$this->model_extension_module_art_aqa_product->deleteAqaProduct($this->request->get['aqa_product_id']);
			}
		}		
		
		$this->response->redirect($this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'], true));
	}

	protected function getForm() {
	    $data['entry_name'] = $this->language->get('entry_f_name');
        $data['entry_question'] = $this->language->get('entry_f_question');
        $data['entry_email'] = $this->language->get('entry_f_email');
        $data['entry_telephone'] = $this->language->get('entry_f_telephone');
        $data['entry_answer'] = $this->language->get('entry_f_answer');
        $data['entry_answer_name'] = $this->language->get('entry_f_answer_name');
        $data['entry_image'] = $this->language->get('entry_f_image');
			
		$data['user_token'] = $this->session->data['user_token'];

		$url = '';

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['filter_status'])) {
			$url .= '&filter_status=' . $this->request->get['filter_status'];
		}

		if (isset($this->request->get['filter_product_id'])) {
			$url .= '&filter_product_id=' . $this->request->get['filter_product_id'];
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('marketplace/modification', 'user_token=' . $this->session->data['user_token'] . '&type=module', true),
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['action'] = $this->url->link('extension/module/art_aqa_product/edit', 'user_token=' . $this->session->data['user_token'] . '&aqa_product_id=' . $this->request->get['aqa_product_id'] . $url, true);
		$data['cancel'] = $this->url->link('extension/module/art_aqa_product', 'user_token=' . $this->session->data['user_token'] . $url, true);	

		$aqa_product_info = '';

		if (isset($this->request->get['aqa_product_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$aqa_product_info = $this->model_extension_module_art_aqa_product->getAqaProduct($this->request->get['aqa_product_id']);
		}	

		$this->load->model('tool/image');

		if (!empty($aqa_product_info) && is_file(DIR_IMAGE . $aqa_product_info['image'])) {
			$data['thumb'] = $this->model_tool_image->resize($aqa_product_info['image'], 100, 100);
		} else {
			$image = $this->config->get('module_art_aqa_product_default_image');
			if (!empty($image) && empty($aqa_product_info['answer'])) {
				$data['thumb'] = $this->model_tool_image->resize($image, 100, 100);
			} else {
				$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
			}
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);

		if ($aqa_product_info) {
			$data['question'] = $aqa_product_info['question'];
			$data['answer'] = $aqa_product_info['answer'];
			$data['theme'] = $aqa_product_info['theme'];
			$data['answer_name'] = !empty($aqa_product_info['answer_name']) ? $aqa_product_info['answer_name'] : $this->config->get('module_art_aqa_product_default_title');
			$data['image'] = !empty($aqa_product_info['image']) ? $aqa_product_info['image'] : $this->config->get('module_art_aqa_product_default_image');
			$data['status'] = $aqa_product_info['status'];
			$data['name'] = $aqa_product_info['user_name'];
			$data['phone'] = $aqa_product_info['phone'];
			$data['email'] = $aqa_product_info['email'];
			$data['date_added'] = $aqa_product_info['date_added'];
			$data['date_fake'] = $aqa_product_info['date_fake'] != '0000-00-00 00:00:00' ? $aqa_product_info['date_fake'] : date("Y-m-d");
			$data['date_answer'] = $aqa_product_info['date_answer'] != '0000-00-00 00:00:00' ? $aqa_product_info['date_answer'] : date("Y-m-d");
			$data['communication'] = $aqa_product_info['communication'];
			$data['ip'] = $aqa_product_info['ip'];
			$data['forwarded_ip'] = $aqa_product_info['forwarded_ip'];
			$data['user_agent'] = $aqa_product_info['user_agent'];
			$data['accept_language'] = $aqa_product_info['accept_language'];
		} else {
			$data['question'] = '';
			$data['answer'] = '';
			$data['answer_name'] = '';
			$data['image'] = '';
			$data['status'] = '';
			$data['name'] = '';
			$data['phone'] = '';
			$data['email'] = '';
			$data['date_added'] = '';
			$data['date_fake'] = '';
			$data['date_answer'] = '';
			$data['communication'] = '';
			$data['ip'] = '';
			$data['forwarded_ip'] = '';
			$data['user_agent'] = '';
			$data['date_fake'] = '';
			$data['accept_language'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/art_aqa_product_edit', $data));
	}

	public function install() {
		$this->load->model('extension/module/art_aqa_product');
		$this->model_extension_module_art_aqa_product->createTables();
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/art_aqa_product')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}	

		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}

	protected function sendMessage($aqa_product_id) {
		$this->load->model('extension/module/art_aqa_product');
		$aqa_product_info = $this->model_extension_module_art_aqa_product->getAqaProduct($aqa_product_id);

		$lang_id = $aqa_product_info['language_id'];

		if (!empty($aqa_product_info['email'])) {
			if ($this->config->get('module_art_aqa_product_mail_answer_status')) {
				$subject = $this->config->get('module_art_aqa_product_mail_answer_title')[$lang_id];
				$template = $this->config->get('module_art_aqa_product_mail_answer')[$lang_id];
				$message_temp = array();

				$info_question = array(
					'aqa_id',
					'store_name',
					'store_url',
					'date_added',
					'firstname',
					'question',
					'answer',
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

				$message_temp['aqa_id'] = $aqa_product_id;
				$message_temp['store_name'] = $this->config->get('config_name');
				$message_temp['store_url'] = '<a href="' . $store_url . '">' . $this->config->get('config_name') . '</a>';;
				$message_temp['date_added'] = $aqa_product_info['date_added'];
				$message_temp['firstname'] = $aqa_product_info['user_name'];
				$message_temp['question'] = $aqa_product_info['question'];
				$message_temp['answer'] = $aqa_product_info['answer'];
				$message_temp['theme'] = $aqa_product_info['theme'];
				$message_temp['product_link'] = '';

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

					$mail->setTo($aqa_product_info['email']);
				    $mail->setFrom($this->config->get('config_email'));
				    $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
				    $mail->setSubject($subject);
				    $mail->setHtml($message);
				   	$mail->send();
			   	}
			}
		}
	}
}
?>