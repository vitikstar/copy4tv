<?php
class ControllerExtensionModulePMReview extends Controller {
	private $error = array(); 
	
	public function index() {  

		$this->load->language('extension/module/pm_review');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));
		
		$this->load->model('setting/module');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			if (!isset($this->request->get['module_id'])) {
				$this->model_setting_module->addModule('pm_review', $this->request->post);
			} else {
				$this->model_setting_module->editModule($this->request->get['module_id'], $this->request->post);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_title_p_review'] = $this->language->get('entry_title_p_review');
		$data['entry_product_image'] = $this->language->get('entry_product_image');
		$data['entry_product_image_size'] = $this->language->get('entry_product_image_size');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_name_customer'] = $this->language->get('entry_name_customer');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_bad'] = $this->language->get('entry_bad');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_avatar'] = $this->language->get('entry_avatar');
		$data['entry_avatar_size'] = $this->language->get('entry_avatar_size');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_thumbnail'] = $this->language->get('entry_thumbnail');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_image_limit'] = $this->language->get('entry_image_limit');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_readmore'] = $this->language->get('entry_readmore');
		$data['entry_show_all'] = $this->language->get('entry_show_all');
		$data['entry_sort'] = $this->language->get('entry_sort');
		$data['entry_filter_rating'] = $this->language->get('entry_filter_rating');
		$data['entry_select_rating'] = $this->language->get('entry_select_rating');
		$data['entry_carousel'] = $this->language->get('entry_carousel');
		$data['entry_carousel_item'] = $this->language->get('entry_carousel_item');
		$data['entry_status'] = $this->language->get('entry_status');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_date_added'] = $this->language->get('text_date_added');
		$data['text_rating'] = $this->language->get('text_rating');
		$data['text_select_rating'] = $this->language->get('text_select_rating');
		$data['text_min_rating'] = $this->language->get('text_min_rating');
		$data['text_no_star'] = $this->language->get('text_no_star');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['product_image_width'])) {
			$data['error_product_image_width'] = $this->error['product_image_width'];
		} else {
			$data['error_product_image_width'] = '';
		}

		if (isset($this->error['product_image_height'])) {
			$data['error_product_image_height'] = $this->error['product_image_height'];
		} else {
			$data['error_product_image_height'] = '';
		}
		
		if (isset($this->error['avatar_width'])) {
			$data['error_avatar_width'] = $this->error['avatar_width'];
		} else {
			$data['error_avatar_width'] = '';
		}

		if (isset($this->error['avatar_height'])) {
			$data['error_avatar_height'] = $this->error['avatar_height'];
		} else {
			$data['error_avatar_height'] = '';
		}
		
		if (isset($this->error['thumbnail_width'])) {
			$data['error_thumbnail_width'] = $this->error['thumbnail_width'];
		} else {
			$data['error_thumbnail_width'] = '';
		}

		if (isset($this->error['thumbnail_height'])) {
			$data['error_thumbnail_height'] = $this->error['thumbnail_height'];
		} else {
			$data['error_thumbnail_height'] = '';
		}
		
		if (isset($this->error['image_limit'])) {
			$data['error_image_limit'] = $this->error['image_limit'];
		} else {
			$data['error_image_limit'] = '';
		}
		
		if (isset($this->error['limit'])) {
			$data['error_limit'] = $this->error['limit'];
		} else {
			$data['error_limit'] = '';
		}
		
		if (isset($this->error['carousel_item'])) {
			$data['error_carousel_item'] = $this->error['carousel_item'];
		} else {
			$data['error_carousel_item'] = '';
		}
				
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		if (!isset($this->request->get['module_id'])) {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/pm_review', 'user_token=' . $this->session->data['user_token'], true)
			);
		} else {
			$data['breadcrumbs'][] = array(
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/module/pm_review', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true)
			);
		}

		if (!isset($this->request->get['module_id'])) {
			$data['action'] = $this->url->link('extension/module/pm_review', 'user_token=' . $this->session->data['user_token'], true);
		} else {
			$data['action'] = $this->url->link('extension/module/pm_review', 'user_token=' . $this->session->data['user_token'] . '&module_id=' . $this->request->get['module_id'], true);
		}

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

		if (isset($this->request->get['module_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$module_info = $this->model_setting_module->getModule($this->request->get['module_id']);
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (!empty($module_info)) {
			$data['name'] = $module_info['name'];
		} else {
			$data['name'] = '';
		}
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['title_p_review'])) {
			$data['title_p_review'] = $this->request->post['title_p_review'];
		} elseif (!empty($module_info)) {
			$data['title_p_review'] = $module_info['title_p_review'];
		} else {
			$data['title_p_review'] = array();
		}
		
		if (isset($this->request->post['product_image'])) {
			$data['product_image'] = $this->request->post['product_image'];
		} elseif (!empty($module_info)) {
			$data['product_image'] = $module_info['product_image'];
		} else {
			$data['product_image'] = '';
		}
		
		if (isset($this->request->post['product_image_width'])) {
			$data['product_image_width'] = $this->request->post['product_image_width'];
		} elseif (!empty($module_info)) {
			$data['product_image_width'] = $module_info['product_image_width'];
		} else {
			$data['product_image_width'] = 200;
		}
		
		if (isset($this->request->post['product_image_height'])) {
			$data['product_image_height'] = $this->request->post['product_image_height'];
		} elseif (!empty($module_info)) {
			$data['product_image_height'] = $module_info['product_image_height'];
		} else {
			$data['product_image_height'] = 200;
		}
		
		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (!empty($module_info)) {
			$data['title'] = $module_info['title'];
		} else {
			$data['title'] = '';
		}
		
		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} elseif (!empty($module_info)) {
			$data['city'] = $module_info['city'];
		} else {
			$data['city'] = '';
		}
		
		if (isset($this->request->post['name_customer'])) {
			$data['name_customer'] = $this->request->post['name_customer'];
		} elseif (!empty($module_info)) {
			$data['name_customer'] = $module_info['name_customer'];
		} else {
			$data['name_customer'] = '';
		}
		
		if (isset($this->request->post['text'])) {
			$data['text'] = $this->request->post['text'];
		} elseif (!empty($module_info)) {
			$data['text'] = $module_info['text'];
		} else {
			$data['text'] = '';
		}

		if (isset($this->request->post['good'])) {
			$data['good'] = $this->request->post['good'];
		} elseif (!empty($module_info)) {
			$data['good'] = $module_info['good'];
		} else {
			$data['good'] = '';
		}
		
		if (isset($this->request->post['bad'])) {
			$data['bad'] = $this->request->post['bad'];
		} elseif (!empty($module_info)) {
			$data['bad'] = $module_info['bad'];
		} else {
			$data['bad'] = '';
		}
		
		if (isset($this->request->post['rating'])) {
			$data['rating'] = $this->request->post['rating'];
		} elseif (!empty($module_info)) {
			$data['rating'] = $module_info['rating'];
		} else {
			$data['rating'] = '';
		}
		
		if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (!empty($module_info)) {
			$data['date_added'] = $module_info['date_added'];
		} else {
			$data['date_added'] = '';
		}
		
		if (isset($this->request->post['avatar'])) {
			$data['avatar'] = $this->request->post['avatar'];
		} elseif (!empty($module_info)) {
			$data['avatar'] = $module_info['avatar'];
		} else {
			$data['avatar'] = '';
		}
		
		if (isset($this->request->post['avatar_width'])) {
			$data['avatar_width'] = $this->request->post['avatar_width'];
		} elseif (!empty($module_info)) {
			$data['avatar_width'] = $module_info['avatar_width'];
		} else {
			$data['avatar_width'] = 200;
		}
		
		if (isset($this->request->post['avatar_height'])) {
			$data['avatar_height'] = $this->request->post['avatar_height'];
		} elseif (!empty($module_info)) {
			$data['avatar_height'] = $module_info['avatar_height'];
		} else {
			$data['avatar_height'] = 200;
		}
		
		if (isset($this->request->post['image'])) {
			$data['image'] = $this->request->post['image'];
		} elseif (!empty($module_info)) {
			$data['image'] = $module_info['image'];
		} else {
			$data['image'] = '';
		}
		
		if (isset($this->request->post['image_limit'])) {
			$data['image_limit'] = $this->request->post['image_limit'];
		} elseif (!empty($module_info)) {
			$data['image_limit'] = $module_info['image_limit'];
		} else {
			$data['image_limit'] = 4;
		}

		if (isset($this->request->post['thumbnail_width'])) {
			$data['thumbnail_width'] = $this->request->post['thumbnail_width'];
		} elseif (!empty($module_info)) {
			$data['thumbnail_width'] = $module_info['thumbnail_width'];
		} else {
			$data['thumbnail_width'] = 50;
		}
		
		if (isset($this->request->post['thumbnail_height'])) {
			$data['thumbnail_height'] = $this->request->post['thumbnail_height'];
		} elseif (!empty($module_info)) {
			$data['thumbnail_height'] = $module_info['thumbnail_height'];
		} else {
			$data['thumbnail_height'] = 50;
		}	
		
		if (isset($this->request->post['limit'])) {
			$data['limit'] = $this->request->post['limit'];
		} elseif (!empty($module_info)) {
			$data['limit'] = $module_info['limit'];
		} else {
			$data['limit'] = 4;
		}
		
		if (isset($this->request->post['readmore'])) {
			$data['readmore'] = $this->request->post['readmore'];
		} elseif (!empty($module_info)) {
			$data['readmore'] = $module_info['readmore'];
		} else {
			$data['readmore'] = '';
		}
		
		if (isset($this->request->post['show_all'])) {
			$data['show_all'] = $this->request->post['show_all'];
		} elseif (!empty($module_info)) {
			$data['show_all'] = $module_info['show_all'];
		} else {
			$data['show_all'] = '';
		}
		
		if (isset($this->request->post['sort'])) {
			$data['sort'] = $this->request->post['sort'];
		} elseif (!empty($module_info)) {
			$data['sort'] = $module_info['sort'];
		} else {
			$data['sort'] = 'pr.date_added';
		}
		
		if (isset($this->request->post['filter_rating'])) {
			$data['filter_rating'] = $this->request->post['filter_rating'];
		} elseif (!empty($module_info)) {
			$data['filter_rating'] = $module_info['filter_rating'];
		} else {
			$data['filter_rating'] = 0;
		}
		
		if (isset($this->request->post['select_rating'])) {
			$data['select_rating'] = $this->request->post['select_rating'];
		} elseif (!empty($module_info)) {
			$data['select_rating'] = $module_info['select_rating'];
		} else {
			$data['select_rating'] = 0;
		}
		
		if (isset($this->request->post['carousel'])) {
			$data['carousel'] = $this->request->post['carousel'];
		} elseif (!empty($module_info)) {
			$data['carousel'] = $module_info['carousel'];
		} else {
			$data['carousel'] = '';
		}
		
		if (isset($this->request->post['carousel_item'])) {
			$data['carousel_item'] = $this->request->post['carousel_item'];
		} elseif (!empty($module_info)) {
			$data['carousel_item'] = $module_info['carousel_item'];
		} else {
			$data['carousel_item'] = 4;
		}
		
		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (!empty($module_info)) {
			$data['status'] = $module_info['status'];
		} else {
			$data['status'] = '';
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/pm_review', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/pm_review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 64)) {
			$this->error['name'] = $this->language->get('error_name');
		}
		
		if (!$this->request->post['avatar_width']) {
			$this->error['avatar_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['avatar_height']) {
			$this->error['avatar_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['thumbnail_width']) {
			$this->error['thumbnail_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['thumbnail_height']) {
			$this->error['thumbnail_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['image_limit']) {
			$this->error['image_limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['limit']) {
			$this->error['limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['carousel_item']) {
			$this->error['carousel_item'] = $this->language->get('error_limit');
		}
		
		return !$this->error;
	}
}