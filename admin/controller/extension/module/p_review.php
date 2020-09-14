<?php
class ControllerExtensionModulePReview extends Controller {
	private $error = array(); 
	
	public function index() {  
		$this->load->language('extension/module/p_review');
		
		$this->load->model('extension/module/p_review');

		$this->document->setTitle(strip_tags($this->language->get('heading_title')));

		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('module_p_review', $this->request->post);
			
			$this->model_extension_module_p_review->editReviewSeoUrl($this->request->post['module_p_review_seo_url']);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));
		}
		
		$data['version'] = '3.0.0 beta 3';
		$data['text_version'] = $this->language->get('text_version');
		
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_form'] = $this->language->get('tab_form');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_position'] = $this->language->get('tab_position');
		$data['tab_seo'] = $this->language->get('tab_seo');
		$data['tab_contact'] = $this->language->get('tab_contact');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled_dashboard'] = $this->language->get('text_enabled_dashboard');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_language_all'] = $this->language->get('text_language_all');
		$data['text_language_select'] = $this->language->get('text_language_select');
		$data['text_required'] = $this->language->get('text_required');
		$data['text_no_required'] = $this->language->get('text_no_required');
		$data['text_wysibb'] = $this->language->get('text_wysibb');
		$data['text_no_editor'] = $this->language->get('text_no_editor');
		$data['text_simple'] = $this->language->get('text_simple');
		$data['text_accordeon'] = $this->language->get('text_accordeon');
		$data['text_keyword'] = $this->language->get('text_keyword');
		$data['text_contact'] = $this->language->get('text_contact');
		$data['text_select_rating'] = $this->language->get('text_select_rating');
		$data['text_min_rating'] = $this->language->get('text_min_rating');
		
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_link'] = $this->language->get('entry_link');
		$data['entry_guest'] = $this->language->get('entry_guest');
		$data['entry_mail'] = $this->language->get('entry_mail');
		$data['entry_moderation'] = $this->language->get('entry_moderation');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_limit'] = $this->language->get('entry_limit');
		$data['entry_filter_rating'] = $this->language->get('entry_filter_rating');
		$data['entry_font_awesome'] = $this->language->get('entry_font_awesome');
		$data['entry_image_limit'] = $this->language->get('entry_image_limit');
		$data['entry_field'] = $this->language->get('entry_field');
		$data['entry_cut'] = $this->language->get('entry_cut');
		$data['entry_text_limit'] = $this->language->get('entry_text_limit');
		$data['entry_title_p_review'] = $this->language->get('entry_title_p_review');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['entry_meta_description'] = $this->language->get('entry_meta_description');
		$data['entry_meta_keyword'] = $this->language->get('entry_meta_keyword');
		$data['entry_editor'] = $this->language->get('entry_editor');
		$data['entry_smile_theme'] = $this->language->get('entry_smile_theme');
		$data['entry_form'] = $this->language->get('entry_form');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_bad'] = $this->language->get('entry_bad');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_avatar'] = $this->language->get('entry_avatar');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_captcha'] = $this->language->get('entry_captcha');
		$data['entry_upload_avatar'] = $this->language->get('entry_upload_avatar');
		$data['entry_upload_image'] = $this->language->get('entry_upload_image');
		$data['entry_avatar_size'] = $this->language->get('entry_avatar_size');
		$data['entry_thumbnail'] = $this->language->get('entry_thumbnail');
		$data['entry_thumb'] = $this->language->get('entry_thumb');
		$data['entry_avatar_info'] = $this->language->get('entry_avatar_info');
		$data['entry_thumbnail_info'] = $this->language->get('entry_thumbnail_info');
		$data['entry_product_image'] = $this->language->get('entry_product_image');
		$data['entry_product_image_info'] = $this->language->get('entry_product_image_info');
		$data['entry_width'] = $this->language->get('entry_width');
		$data['entry_height'] = $this->language->get('entry_height');
		$data['entry_position_tab'] = $this->language->get('entry_position_tab');
		$data['entry_position_content'] = $this->language->get('entry_position_content');
		$data['entry_position_total'] = $this->language->get('entry_position_total');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['limit'])) {
			$data['error_limit'] = $this->error['limit'];
		} else {
			$data['error_limit'] = '';
		}
		
		if (isset($this->error['text_limit'])) {
			$data['error_text_limit'] = $this->error['text_limit'];
		} else {
			$data['error_text_limit'] = '';
		}
		
		if (isset($this->error['image_limit'])) {
			$data['error_image_limit'] = $this->error['image_limit'];
		} else {
			$data['error_image_limit'] = '';
		}
		
		if (isset($this->error['upload_avatar_width'])) {
			$data['error_upload_avatar_width'] = $this->error['upload_avatar_width'];
		} else {
			$data['error_upload_avatar_width'] = '';
		}

		if (isset($this->error['upload_avatar_height'])) {
			$data['error_upload_avatar_height'] = $this->error['upload_avatar_height'];
		} else {
			$data['error_upload_avatar_height'] = '';
		}
		
		if (isset($this->error['upload_image_width'])) {
			$data['error_upload_image_width'] = $this->error['upload_image_width'];
		} else {
			$data['error_upload_image_width'] = '';
		}

		if (isset($this->error['upload_image_height'])) {
			$data['error_upload_image_height'] = $this->error['upload_image_height'];
		} else {
			$data['error_upload_image_height'] = '';
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
		
		if (isset($this->error['thumb_width'])) {
			$data['error_thumb_width'] = $this->error['thumb_width'];
		} else {
			$data['error_thumb_width'] = '';
		}

		if (isset($this->error['thumb_height'])) {
			$data['error_thumb_height'] = $this->error['thumb_height'];
		} else {
			$data['error_thumb_height'] = '';
		}
		
		if (isset($this->error['avatar_info_width'])) {
			$data['error_avatar_info_width'] = $this->error['avatar_info_width'];
		} else {
			$data['error_avatar_info_width'] = '';
		}

		if (isset($this->error['avatar_info_height'])) {
			$data['error_avatar_info_height'] = $this->error['avatar_info_height'];
		} else {
			$data['error_avatar_info_height'] = '';
		}
		
		if (isset($this->error['thumbnail_info_width'])) {
			$data['error_thumbnail_info_width'] = $this->error['thumbnail_info_width'];
		} else {
			$data['error_thumbnail_info_width'] = '';
		}

		if (isset($this->error['thumbnail_info_height'])) {
			$data['error_thumbnail_info_height'] = $this->error['thumbnail_info_height'];
		} else {
			$data['error_thumbnail_info_height'] = '';
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
		
		if (isset($this->error['product_image_info_width'])) {
			$data['error_product_image_info_width'] = $this->error['product_image_info_width'];
		} else {
			$data['error_product_image_info_width'] = '';
		}

		if (isset($this->error['product_image_info_height'])) {
			$data['error_product_image_info_height'] = $this->error['product_image_info_height'];
		} else {
			$data['error_product_image_info_height'] = '';
		}
		
		if (isset($this->error['position_tab'])) {
			$data['error_position_tab'] = $this->error['position_tab'];
		} else {
			$data['error_position_tab'] = '';
		}
		
		if (isset($this->error['position_content'])) {
			$data['error_position_content'] = $this->error['position_content'];
		} else {
			$data['error_position_content'] = '';
		}
		
		if (isset($this->error['position_total'])) {
			$data['error_position_total'] = $this->error['position_total'];
		} else {
			$data['error_position_total'] = '';
		}
		
		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
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

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/p_review', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/p_review', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);
		
		if (isset($this->request->post['module_p_review_status'])) {
			$data['module_p_review_status'] = $this->request->post['module_p_review_status'];
		} else {
			$data['module_p_review_status'] = $this->config->get('module_p_review_status');
		}
		
		if (isset($this->request->post['module_p_review_link'])) {
			$data['module_p_review_link'] = $this->request->post['module_p_review_link'];
		} else {
			$data['module_p_review_link'] = $this->config->get('module_p_review_link');
		}

		if (isset($this->request->post['module_p_review_guest'])) {
			$data['module_p_review_guest'] = $this->request->post['module_p_review_guest'];
		} else {
			$data['module_p_review_guest'] = $this->config->get('module_p_review_guest');
		}

		if (isset($this->request->post['module_p_review_mail'])) {
			$data['module_p_review_mail'] = $this->request->post['module_p_review_mail'];
		} else {
			$data['module_p_review_mail'] = $this->config->get('module_p_review_mail');
		}
		
		if (isset($this->request->post['module_p_review_moderation'])) {
			$data['module_p_review_moderation'] = $this->request->post['module_p_review_moderation'];
		} else {
			$data['module_p_review_moderation'] = $this->config->get('module_p_review_moderation');
		}
		
		if (isset($this->request->post['module_p_review_language'])) {
			$data['module_p_review_language'] = $this->request->post['module_p_review_language'];
		} else {
			$data['module_p_review_language'] = $this->config->get('module_p_review_language');
		}
		
		if (isset($this->request->post['module_p_review_date_added'])) {
			$data['module_p_review_date_added'] = $this->request->post['module_p_review_date_added'];
		} else {
			$data['module_p_review_date_added'] = $this->config->get('module_p_review_date_added');
		}
		
		if (isset($this->request->post['module_p_review_limit'])) {
			$data['module_p_review_limit'] = $this->request->post['module_p_review_limit'];
		} elseif ($this->config->get('module_p_review_limit')) {
			$data['module_p_review_limit'] = $this->config->get('module_p_review_limit');
		} else {
			$data['module_p_review_limit'] = '10,20,30,40,50';
		}
		
		if (isset($this->request->post['module_p_review_field'])) {
			$data['module_p_review_field'] = $this->request->post['module_p_review_field'];
		} else {
			$data['module_p_review_field'] = $this->config->get('module_p_review_field');
		}
		
		if (isset($this->request->post['module_p_review_cut'])) {
			$data['module_p_review_cut'] = $this->request->post['module_p_review_cut'];
		} else {
			$data['module_p_review_cut'] = $this->config->get('module_p_review_cut');
		}
		
		if (isset($this->request->post['module_p_review_text_limit'])) {
			$data['module_p_review_text_limit'] = $this->request->post['module_p_review_text_limit'];
		} elseif ($this->config->get('module_p_review_text_limit')) {
			$data['module_p_review_text_limit'] = $this->config->get('module_p_review_text_limit');
		} else {
			$data['module_p_review_text_limit'] = 500;
		}
		
		if (isset($this->request->post['module_p_review_filter_rating'])) {
			$data['module_p_review_filter_rating'] = $this->request->post['module_p_review_filter_rating'];
		} else {
			$data['module_p_review_filter_rating'] = $this->config->get('module_p_review_filter_rating');
		}
		
		if (isset($this->request->post['module_p_review_font_awesome'])) {
			$data['module_p_review_font_awesome'] = $this->request->post['module_p_review_font_awesome'];
		} else {
			$data['module_p_review_font_awesome'] = $this->config->get('module_p_review_font_awesome');
		}
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['module_p_review_description'])) {
			$data['module_p_review_description'] = $this->request->post['module_p_review_description'];
		} else {
			$data['module_p_review_description'] = $this->config->get('module_p_review_description');
		}
		
		if (isset($this->request->post['module_p_review_editor'])) {
			$data['module_p_review_editor'] = $this->request->post['module_p_review_editor'];
		} else {
			$data['module_p_review_editor'] = $this->config->get('module_p_review_editor');
		}
		
		if (isset($this->request->post['module_p_review_smile_theme'])) {
			$data['module_p_review_smile_theme'] = $this->request->post['module_p_review_smile_theme'];
		} else {
			$data['module_p_review_smile_theme'] = $this->config->get('module_p_review_smile_theme');
		}
		
		$folder = DIR_IMAGE . 'catalog/p_review/smile/';

		$smile_themes = array_diff(scandir($folder), array('..', '.'));
		$data['smile_themes'] = array();
		
		if ($smile_themes) {
			foreach ($smile_themes as $smile_theme) {
				if (is_dir($folder . $smile_theme)) {
					$data['smile_themes'][] = $smile_theme;
				}
			}
		}
		
		if (isset($this->request->post['module_p_review_form'])) {
			$data['module_p_review_form'] = $this->request->post['module_p_review_form'];
		} else {
			$data['module_p_review_form'] = $this->config->get('module_p_review_form');
		}
		
		if (isset($this->request->post['module_p_review_title'])) {
			$data['module_p_review_title'] = $this->request->post['module_p_review_title'];
		} else {
			$data['module_p_review_title'] = $this->config->get('module_p_review_title');
		}
		
		if (isset($this->request->post['module_p_review_city'])) {
			$data['module_p_review_city'] = $this->request->post['module_p_review_city'];
		} else {
			$data['module_p_review_city'] = $this->config->get('module_p_review_city');
		}
		
		if (isset($this->request->post['module_p_review_name'])) {
			$data['module_p_review_name'] = $this->request->post['module_p_review_name'];
		} else {
			$data['module_p_review_name'] = $this->config->get('module_p_review_name');
		}
		
		if (isset($this->request->post['module_p_review_email'])) {
			$data['module_p_review_email'] = $this->request->post['module_p_review_email'];
		} else {
			$data['module_p_review_email'] = $this->config->get('module_p_review_email');
		}
		
		if (isset($this->request->post['module_p_review_text'])) {
			$data['module_p_review_text'] = $this->request->post['module_p_review_text'];
		} else {
			$data['module_p_review_text'] = $this->config->get('module_p_review_text');
		}
		
		if (isset($this->request->post['module_p_review_good'])) {
			$data['module_p_review_good'] = $this->request->post['module_p_review_good'];
		} else {
			$data['module_p_review_good'] = $this->config->get('module_p_review_good');
		}
		
		if (isset($this->request->post['module_p_review_bad'])) {
			$data['module_p_review_bad'] = $this->request->post['module_p_review_bad'];
		} else {
			$data['module_p_review_bad'] = $this->config->get('module_p_review_bad');
		}
		
		if (isset($this->request->post['module_p_review_rating'])) {
			$data['module_p_review_rating'] = $this->request->post['module_p_review_rating'];
		} else {
			$data['module_p_review_rating'] = $this->config->get('module_p_review_rating');
		}
		
		if (isset($this->request->post['module_p_review_avatar'])) {
			$data['module_p_review_avatar'] = $this->request->post['module_p_review_avatar'];
		} else {
			$data['module_p_review_avatar'] = $this->config->get('module_p_review_avatar');
		}
		
		if (isset($this->request->post['module_p_review_image'])) {
			$data['module_p_review_image'] = $this->request->post['module_p_review_image'];
		} else {
			$data['module_p_review_image'] = $this->config->get('module_p_review_image');
		}
		
		if (isset($this->request->post['module_p_review_image_limit'])) {
			$data['module_p_review_image_limit'] = $this->request->post['module_p_review_image_limit'];
		} elseif ($this->config->get('module_p_review_image_limit')) {
			$data['module_p_review_image_limit'] = $this->config->get('module_p_review_image_limit');
		} else {
			$data['module_p_review_image_limit'] = 5;
		}
		
		if (isset($this->request->post['module_p_review_captcha'])) {
			$data['module_p_review_captcha'] = $this->request->post['module_p_review_captcha'];
		} else {
			$data['module_p_review_captcha'] = $this->config->get('module_p_review_captcha');
		}
		
		if (isset($this->request->post['module_p_review_upload_avatar_width'])) {
			$data['module_p_review_upload_avatar_width'] = $this->request->post['module_p_review_upload_avatar_width'];
		} elseif ($this->config->get('module_p_review_upload_avatar_width')) {
			$data['module_p_review_upload_avatar_width'] = $this->config->get('module_p_review_upload_avatar_width');
		} else {
			$data['module_p_review_upload_avatar_width'] = 1440;
		}
		
		if (isset($this->request->post['module_p_review_upload_avatar_height'])) {
			$data['module_p_review_upload_avatar_height'] = $this->request->post['module_p_review_upload_avatar_height'];
		} elseif ($this->config->get('module_p_review_upload_avatar_height')) {
			$data['module_p_review_upload_avatar_height'] = $this->config->get('module_p_review_upload_avatar_height');
		} else {
			$data['module_p_review_upload_avatar_height'] = 900;
		}
		
		if (isset($this->request->post['module_p_review_upload_image_width'])) {
			$data['module_p_review_upload_image_width'] = $this->request->post['module_p_review_upload_image_width'];
		} elseif ($this->config->get('module_p_review_upload_image_width')) {
			$data['module_p_review_upload_image_width'] = $this->config->get('module_p_review_upload_image_width');
		} else {
			$data['module_p_review_upload_image_width'] = 1440;
		}
		
		if (isset($this->request->post['module_p_review_upload_image_height'])) {
			$data['module_p_review_upload_image_height'] = $this->request->post['module_p_review_upload_image_height'];
		} elseif ($this->config->get('module_p_review_upload_image_height')) {
			$data['module_p_review_upload_image_height'] = $this->config->get('module_p_review_upload_image_height');
		} else {
			$data['module_p_review_upload_image_height'] = 900;
		}
		
		if (isset($this->request->post['module_p_review_avatar_width'])) {
			$data['module_p_review_avatar_width'] = $this->request->post['module_p_review_avatar_width'];
		} elseif ($this->config->get('module_p_review_avatar_width')) {
			$data['module_p_review_avatar_width'] = $this->config->get('module_p_review_avatar_width');
		} else {
			$data['module_p_review_avatar_width'] = 100;
		}
		
		if (isset($this->request->post['module_p_review_avatar_height'])) {
			$data['module_p_review_avatar_height'] = $this->request->post['module_p_review_avatar_height'];
		} elseif ($this->config->get('module_p_review_avatar_height')) {
			$data['module_p_review_avatar_height'] = $this->config->get('module_p_review_avatar_height');
		} else {
			$data['module_p_review_avatar_height'] = 100;
		}
		
		if (isset($this->request->post['module_p_review_thumbnail_width'])) {
			$data['module_p_review_thumbnail_width'] = $this->request->post['module_p_review_thumbnail_width'];
		} elseif ($this->config->get('module_p_review_thumbnail_width')) {
			$data['module_p_review_thumbnail_width'] = $this->config->get('module_p_review_thumbnail_width');
		} else {
			$data['module_p_review_thumbnail_width'] = 100;
		}
		
		if (isset($this->request->post['module_p_review_thumbnail_height'])) {
			$data['module_p_review_thumbnail_height'] = $this->request->post['module_p_review_thumbnail_height'];
		} elseif ($this->config->get('module_p_review_thumbnail_height')) {
			$data['module_p_review_thumbnail_height'] = $this->config->get('module_p_review_thumbnail_height');
		} else {
			$data['module_p_review_thumbnail_height'] = 100;
		}
		
		if (isset($this->request->post['module_p_review_thumb_width'])) {
			$data['module_p_review_thumb_width'] = $this->request->post['module_p_review_thumb_width'];
		} elseif ($this->config->get('module_p_review_thumb_width')) {
			$data['module_p_review_thumb_width'] = $this->config->get('module_p_review_thumb_width');
		} else {
			$data['module_p_review_thumb_width'] = 500;
		}
		
		if (isset($this->request->post['module_p_review_thumb_height'])) {
			$data['module_p_review_thumb_height'] = $this->request->post['module_p_review_thumb_height'];
		} elseif ($this->config->get('module_p_review_thumb_height')) {
			$data['module_p_review_thumb_height'] = $this->config->get('module_p_review_thumb_height');
		} else {
			$data['module_p_review_thumb_height'] = 500;
		}
		
		if (isset($this->request->post['module_p_review_avatar_info_width'])) {
			$data['module_p_review_avatar_info_width'] = $this->request->post['module_p_review_avatar_info_width'];
		} elseif ($this->config->get('module_p_review_avatar_info_width')) {
			$data['module_p_review_avatar_info_width'] = $this->config->get('module_p_review_avatar_info_width');
		} else {
			$data['module_p_review_avatar_info_width'] = 100;
		}
		
		if (isset($this->request->post['module_p_review_avatar_info_height'])) {
			$data['module_p_review_avatar_info_height'] = $this->request->post['module_p_review_avatar_info_height'];
		} elseif ($this->config->get('module_p_review_avatar_info_height')) {
			$data['module_p_review_avatar_info_height'] = $this->config->get('module_p_review_avatar_info_height');
		} else {
			$data['module_p_review_avatar_info_height'] = 100;
		}
		
		if (isset($this->request->post['module_p_review_thumbnail_info_width'])) {
			$data['module_p_review_thumbnail_info_width'] = $this->request->post['module_p_review_thumbnail_info_width'];
		} elseif ($this->config->get('module_p_review_thumbnail_info_width')) {
			$data['module_p_review_thumbnail_info_width'] = $this->config->get('module_p_review_thumbnail_info_width');
		} else {
			$data['module_p_review_thumbnail_info_width'] = 100;
		}
		
		if (isset($this->request->post['module_p_review_thumbnail_info_height'])) {
			$data['module_p_review_thumbnail_info_height'] = $this->request->post['module_p_review_thumbnail_info_height'];
		} elseif ($this->config->get('module_p_review_thumbnail_info_height')) {
			$data['module_p_review_thumbnail_info_height'] = $this->config->get('module_p_review_thumbnail_info_height');
		} else {
			$data['module_p_review_thumbnail_info_height'] = 100;
		}
		
		if (isset($this->request->post['module_p_review_product_image_width'])) {
			$data['module_p_review_product_image_width'] = $this->request->post['module_p_review_product_image_width'];
		} elseif ($this->config->get('module_p_review_product_image_width')) {
			$data['module_p_review_product_image_width'] = $this->config->get('module_p_review_product_image_width');
		} else {
			$data['module_p_review_product_image_width'] = 200;
		}
		
		if (isset($this->request->post['module_p_review_product_image_height'])) {
			$data['module_p_review_product_image_height'] = $this->request->post['module_p_review_product_image_height'];
		} elseif ($this->config->get('module_p_review_product_image_height')) {
			$data['module_p_review_product_image_height'] = $this->config->get('module_p_review_product_image_height');
		} else {
			$data['module_p_review_product_image_height'] = 200;
		}
		
		if (isset($this->request->post['module_p_review_product_image_info_width'])) {
			$data['module_p_review_product_image_info_width'] = $this->request->post['module_p_review_product_image_info_width'];
		} elseif ($this->config->get('module_p_review_product_image_info_width')) {
			$data['module_p_review_product_image_info_width'] = $this->config->get('module_p_review_product_image_info_width');
		} else {
			$data['module_p_review_product_image_info_width'] = 200;
		}
		
		if (isset($this->request->post['module_p_review_product_image_info_height'])) {
			$data['module_p_review_product_image_info_height'] = $this->request->post['module_p_review_product_image_info_height'];
		} elseif ($this->config->get('module_p_review_product_image_info_height')) {
			$data['module_p_review_product_image_info_height'] = $this->config->get('module_p_review_product_image_info_height');
		} else {
			$data['module_p_review_product_image_info_height'] = 200;
		}
		
		if (isset($this->request->post['module_p_review_position_tab'])) {
			$data['module_p_review_position_tab'] = $this->request->post['module_p_review_position_tab'];
		} elseif ($this->config->get('module_p_review_position_tab') >= 0) {
			$data['module_p_review_position_tab'] = $this->config->get('module_p_review_position_tab');
		}
		
		if (isset($this->request->post['module_p_review_position_content'])) {
			$data['module_p_review_position_content'] = $this->request->post['module_p_review_position_content'];
		} elseif ($this->config->get('module_p_review_position_content') >= 0) {
			$data['module_p_review_position_content'] = $this->config->get('module_p_review_position_content');
		}
		
		if (isset($this->request->post['module_p_review_position_total'])) {
			$data['module_p_review_position_total'] = $this->request->post['module_p_review_position_total'];
		} elseif ($this->config->get('module_p_review_position_total') >= 0) {
			$data['module_p_review_position_total'] = $this->config->get('module_p_review_position_total');
		}
		
		$this->load->model('setting/store');

		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}
		
		if (isset($this->request->post['module_p_review_seo_url'])) {
			$data['module_p_review_seo_url'] = $this->request->post['module_p_review_seo_url'];
		} else {
			$data['module_p_review_seo_url'] = $this->config->get('module_p_review_seo_url');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/p_review', $data));
	}
	
	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/p_review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->request->post['module_p_review_limit']) {
			$this->error['limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['module_p_review_text_limit']) {
			$this->error['text_limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['module_p_review_image_limit']) {
			$this->error['image_limit'] = $this->language->get('error_limit');
		}
		
		if (!$this->request->post['module_p_review_upload_avatar_width']) {
			$this->error['upload_avatar_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_upload_avatar_height']) {
			$this->error['upload_avatar_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_p_review_upload_image_width']) {
			$this->error['upload_image_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_upload_image_height']) {
			$this->error['upload_image_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_p_review_avatar_width']) {
			$this->error['avatar_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_avatar_height']) {
			$this->error['avatar_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_p_review_thumbnail_width']) {
			$this->error['thumbnail_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_thumbnail_height']) {
			$this->error['thumbnail_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_p_review_thumb_width']) {
			$this->error['thumb_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_thumb_height']) {
			$this->error['thumb_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_p_review_avatar_info_width']) {
			$this->error['avatar_info_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_avatar_info_height']) {
			$this->error['avatar_info_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_p_review_thumbnail_info_width']) {
			$this->error['thumbnail_info_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_thumbnail_info_height']) {
			$this->error['thumbnail_info_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_p_review_product_image_width']) {
			$this->error['product_image_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_product_image_height']) {
			$this->error['product_image_height'] = $this->language->get('error_height');
		}
		
		if (!$this->request->post['module_p_review_product_image_info_width']) {
			$this->error['product_image_info_width'] = $this->language->get('error_width');
		}

		if (!$this->request->post['module_p_review_product_image_info_height']) {
			$this->error['product_image_info_height'] = $this->language->get('error_height');
		}
		
		if (utf8_strlen($this->request->post['module_p_review_position_tab']) == 0) {
			$this->error['position_tab'] = $this->language->get('error_position_tab');
		}
		
		if (utf8_strlen($this->request->post['module_p_review_position_content']) == 0) {
			$this->error['position_content'] = $this->language->get('error_position_content');
		}

		if (utf8_strlen($this->request->post['module_p_review_position_total']) == 0) {
			$this->error['position_total'] = $this->language->get('error_position_total');
		}
		
		if ($this->request->post['module_p_review_seo_url']) {
			$this->load->model('design/seo_url');
			
			foreach ($this->request->post['module_p_review_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						if (count(array_keys($language, $keyword)) > 1) {
							$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
						}						
						
						$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);
						
						foreach ($seo_urls as $seo_url) {
							if (($seo_url['store_id'] == $store_id) && $seo_url['query'] != 'extension/module/p_review/all') {
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');
								
								break;
							}
						}
					}
				}
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		
		return !$this->error;
	}
	
	public function review_list() {
		$this->load->language('extension/module/p_review/catalog');

		$this->document->setTitle($this->language->get('heading_title'));
		 
		$this->load->model('extension/module/p_review');

		$this->getList();
	}
	
	public function add() {
		$this->load->language('extension/module/p_review/catalog');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('extension/module/p_review');
				
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_p_review->addReview($this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->response->redirect($this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}

		$this->getForm();
	}

	public function edit() {
		$this->load->language('extension/module/p_review/catalog');

		$this->document->setTitle( $this->language->get('heading_title') );
		
		$this->load->model('extension/module/p_review');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_extension_module_p_review->editReview($this->request->get['p_review_id'], $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->response->redirect($this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . $url, true));
		}
		
		$this->getForm();
	}
 
	public function delete() {
		$this->load->language('extension/module/p_review/catalog');

		$this->document->setTitle( $this->language->get('heading_title'));
		
		$this->load->model('extension/module/p_review');
		
		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			
			foreach ($this->request->post['selected'] as $p_review_id) {
				$this->model_extension_module_p_review->deleteReview($p_review_id);
			}
			
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->response->redirect($this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . $url, true));

		}

		$this->getList();
	}

	private function getList() {
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'date_added';
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
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		$data['add'] = $this->url->link('extension/module/p_review/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		$data['delete'] = $this->url->link('extension/module/p_review/delete', 'user_token=' . $this->session->data['user_token'] . $url, true);

		$data['p_reviews'] = array();

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start' => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' => $this->config->get('config_limit_admin')
		);
		
		if ($this->config->get('module_p_review_field')) {
			$data['field_title'] = $this->config->get('module_p_review_title');
			$data['field_city'] = $this->config->get('module_p_review_city');
			$data['field_name'] = $this->config->get('module_p_review_name');
			$data['field_email'] = $this->config->get('module_p_review_email');
			$data['field_text'] = $this->config->get('module_p_review_text');
			$data['field_good'] = $this->config->get('module_p_review_good');
			$data['field_bad'] = $this->config->get('module_p_review_bad');
			$data['field_rating'] = $this->config->get('module_p_review_rating');
			$data['field_avatar'] = $this->config->get('module_p_review_avatar');
			$data['field_image'] = $this->config->get('module_p_review_image');
		} else {
			$data['field_title'] = 1;
			$data['field_city'] = 1;
			$data['field_name'] = 1;
			$data['field_email'] = 1;
			$data['field_text'] = 1;
			$data['field_good'] = 1;
			$data['field_bad'] = 1;
			$data['field_rating'] = 1;
			$data['field_avatar'] = 1;
			$data['field_image'] = 1;
		}
		
		$p_review_total = $this->model_extension_module_p_review->getTotalReviews();
		$data['p_reviewtotal'] = $p_review_total;
		
		$results = $this->model_extension_module_p_review->getReviews($filter_data);
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		$this->load->model('tool/image');

    	foreach ($results as $result) {	
			
			if (is_file(DIR_IMAGE . $result['avatar'])) {
				$avatar = $this->model_tool_image->resize($result['avatar'], 40, 40);
			} else {
				$avatar = $this->model_tool_image->resize('no_image.png', 40, 40);
			}
			
			$image = array();
			
			if ($result['image']) {
				foreach (explode('|', $result['image']) as $value) {
					$image[] = $this->model_tool_image->resize($value, 40, 40);
				}
			}
			
			$text = $this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->clearBBCode($result['text']) : $result['text'];
			
			if (mb_strlen($text,'UTF-8') > 100){
				$text = utf8_substr($text, 0, 100) . '...';
			}
			
			$good = $this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->clearBBCode($result['good']) : $result['good'];
			
			if (mb_strlen($good,'UTF-8') > 100){
				$good = utf8_substr($good, 0, 100) . '...';
			}
			
			$bad = $this->config->get('module_p_review_editor') ? $this->model_extension_module_p_review->clearBBCode($result['bad']) : $result['bad'];
			
			if (mb_strlen($bad,'UTF-8') > 100){
				$bad = utf8_substr($bad, 0, 100) . '...';
			}
			
			$data['p_reviews'][] = array(
				'p_review_id' => $result['p_review_id'],
				'product'        => $result['product'],
				'title'          => $result['title'],
				'text'			 => $text,
				'good'			 => $good,
				'bad'			 => $bad,
				'name'			 => $result['name'],
				'city'			 => $result['city'],
				'avatar'		 => $avatar,
				'image'	     	 => $image,
				'rating'		 => $result['rating'],
				'language_id'	 => $result['language_id'],
				'email'		     => $result['email'],
				'date_added' 	 => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'status' 		 => $result['status'],			
				'edit'     	     => $this->url->link('extension/module/p_review/edit', 'user_token=' . $this->session->data['user_token'] . '&p_review_id=' . $result['p_review_id'] . $url, true)
			);
		}	
	
		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_list'] = $this->language->get('text_list');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_confirm'] = $this->language->get('text_confirm');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');

		$data['column_product'] = $this->language->get('column_product');
		$data['column_title'] = $this->language->get('column_title');
		$data['column_text'] = $this->language->get('column_text');
		$data['column_good'] = $this->language->get('column_good');
		$data['column_bad'] = $this->language->get('column_bad');
		$data['column_name'] = $this->language->get('column_name');		
		$data['column_city'] = $this->language->get('column_city');
		$data['column_avatar'] = $this->language->get('column_avatar');
		$data['column_image'] = $this->language->get('column_image');		
		$data['column_rating'] = $this->language->get('column_rating');
		$data['column_language'] = $this->language->get('column_language');
		$data['column_email'] = $this->language->get('column_email');
		$data['column_date_added'] = $this->language->get('column_date_added');
		$data['column_status'] = $this->language->get('column_status');
		$data['column_action'] = $this->language->get('column_action');		
		
		$data['button_add'] = $this->language->get('button_add');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}

		$url = '';

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['sort_product'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pd.name' . $url, true);
		$data['sort_title'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.title' . $url, true);		
		$data['sort_text'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.text' . $url, true);
		$data['sort_good'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.good' . $url, true);
		$data['sort_bad'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.bad' . $url, true);
		$data['sort_name'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.name' . $url, true);
		$data['sort_city'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.city' . $url, true);
		$data['sort_rating'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.rating' . $url, true);
		$data['sort_language_id'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.language_id' . $url, true);
		$data['sort_email'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.email' . $url, true);			
		$data['sort_date_added'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.date_added' . $url, true);		
		$data['sort_status'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . '&sort=pr.status' . $url, true);	
		
		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
												
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $p_review_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', true);
		
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($p_review_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($p_review_total - $this->config->get('config_limit_admin'))) ? $p_review_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $p_review_total, ceil($p_review_total / $this->config->get('config_limit_admin')));

		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/p_review/list', $data));
	}

	private function getForm() {
		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['p_review_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_default'] = $this->language->get('text_default');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_keyword'] = $this->language->get('text_keyword');

		$data['entry_product'] = $this->language->get('entry_product');
		$data['entry_title'] = $this->language->get('entry_title');
		$data['entry_city'] = $this->language->get('entry_city');
		$data['entry_email'] = $this->language->get('entry_email');
		$data['entry_name'] = $this->language->get('entry_name');
		$data['entry_text'] = $this->language->get('entry_text');
		$data['entry_good'] = $this->language->get('entry_good');
		$data['entry_bad'] = $this->language->get('entry_bad');
		$data['entry_alert'] = $this->language->get('entry_alert');
		$data['entry_comment'] = $this->language->get('entry_comment');
		$data['entry_rating'] = $this->language->get('entry_rating');
		$data['entry_avatar'] = $this->language->get('entry_avatar');
		$data['entry_image'] = $this->language->get('entry_image');
		$data['entry_language'] = $this->language->get('entry_language');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_date_added'] = $this->language->get('entry_date_added');
		$data['entry_store'] = $this->language->get('entry_store');
		$data['entry_keyword'] = $this->language->get('entry_keyword');
		$data['entry_layout'] = $this->language->get('entry_layout');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');
		
		$data['tab_general'] = $this->language->get('tab_general');
		$data['tab_data'] = $this->language->get('tab_data');
		$data['tab_image'] = $this->language->get('tab_image');
		$data['tab_seo'] = $this->language->get('tab_seo');
		$data['tab_design'] = $this->language->get('tab_design');
		
		$data['editor'] = $this->config->get('module_p_review_editor');
		$data['smiles'] = $this->model_extension_module_p_review->getSmiles();
		$data['wysibb_language'] = substr($this->config->get('config_language'), 0, 2);

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['product'])) {
			$data['error_product'] = $this->error['product'];
		} else {
			$data['error_product'] = '';
		}
		
		if (isset($this->error['title'])) {
			$data['error_title'] = $this->error['title'];
		} else {
			$data['error_title'] = '';
		}
		
		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}
		
		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}
		
		if (isset($this->error['name'])) {
			$data['error_name'] = $this->error['name'];
		} else {
			$data['error_name'] = '';
		}
		
		if (isset($this->error['text'])) {
			$data['error_text'] = $this->error['text'];
		} else {
			$data['error_text'] = '';
		}
		
		if (isset($this->error['good'])) {
			$data['error_good'] = $this->error['good'];
		} else {
			$data['error_good'] = '';
		}
		
		if (isset($this->error['bad'])) {
			$data['error_bad'] = $this->error['bad'];
		} else {
			$data['error_bad'] = '';
		}
		
		if (isset($this->error['rating'])) {
			$data['error_rating'] = $this->error['rating'];
		} else {
			$data['error_rating'] = '';
		}
		
		if (isset($this->error['avatar'])) {
			$data['error_avatar'] = $this->error['avatar'];
		} else {
			$data['error_avatar'] = '';
		}
		
		if (isset($this->error['image'])) {
			$data['error_image'] = $this->error['image'];
		} else {
			$data['error_image'] = '';
		}
		
		if (isset($this->error['keyword'])) {
			$data['error_keyword'] = $this->error['keyword'];
		} else {
			$data['error_keyword'] = '';
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

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . $url, true)
		);

		if (!isset($this->request->get['p_review_id'])) {
			$data['action'] = $this->url->link('extension/module/p_review/add', 'user_token=' . $this->session->data['user_token'] . $url, true);
		} else {
			$data['action'] = $this->url->link('extension/module/p_review/edit', 'user_token=' . $this->session->data['user_token'] . '&p_review_id=' . $this->request->get['p_review_id'] . $url, true);
		}

		$data['cancel'] = $this->url->link('extension/module/p_review/review_list', 'user_token=' . $this->session->data['user_token'] . $url, true);
		
		if (isset($this->request->get['p_review_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
			$p_review_info = $this->model_extension_module_p_review->getReview($this->request->get['p_review_id']);
		}
		
		if ($this->config->get('module_p_review_field')) {
			$data['field_title'] = $this->config->get('module_p_review_title');
			$data['field_city'] = $this->config->get('module_p_review_city');
			$data['field_name'] = $this->config->get('module_p_review_name');
			$data['field_email'] = $this->config->get('module_p_review_email');
			$data['field_text'] = $this->config->get('module_p_review_text');
			$data['field_good'] = $this->config->get('module_p_review_good');
			$data['field_bad'] = $this->config->get('module_p_review_bad');
			$data['field_rating'] = $this->config->get('module_p_review_rating');
			$data['field_avatar'] = $this->config->get('module_p_review_avatar');
			$data['field_image'] = $this->config->get('module_p_review_image');
		} else {
			$data['field_title'] = $this->config->get('module_p_review_title') ? $this->config->get('module_p_review_title') : 1;
			$data['field_city'] = $this->config->get('module_p_review_title') ? $this->config->get('module_p_review_title') : 1;
			$data['field_name'] = $this->config->get('module_p_review_name') ? $this->config->get('module_p_review_name') : 1;
			$data['field_email'] = $this->config->get('module_p_review_email') ? $this->config->get('module_p_review_email') : 1;
			$data['field_text'] = $this->config->get('module_p_review_text') ? $this->config->get('module_p_review_text') : 1;
			$data['field_good'] = $this->config->get('module_p_review_good') ? $this->config->get('module_p_review_good') : 1;
			$data['field_bad'] = $this->config->get('module_p_review_bad') ? $this->config->get('module_p_review_bad') : 1;
			$data['field_rating'] = $this->config->get('module_p_review_rating') ? $this->config->get('module_p_review_rating') : 1;
			$data['field_avatar'] = $this->config->get('module_p_review_avatar') ? $this->config->get('module_p_review_avatar') : 1;
			$data['field_image'] = $this->config->get('module_p_review_image') ? $this->config->get('module_p_review_image') : 1;
		}
		
		$data['user_token'] = $this->session->data['user_token'];

		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$data['product_id'] = $this->request->post['product_id'];
		} elseif (!empty($p_review_info)) {
			$data['product_id'] = $p_review_info['product_id'];
		} else {
			$data['product_id'] = '';
		}

		if (isset($this->request->post['product'])) {
			$data['product'] = $this->request->post['product'];
		} elseif (!empty($p_review_info)) {
			$data['product'] = $p_review_info['product'];
		} else {
			$data['product'] = '';
		}
		
		if (isset($this->request->post['title'])) {
			$data['title'] = $this->request->post['title'];
		} elseif (isset($p_review_info)) {
			$data['title'] = $p_review_info['title'];
		} else {
			$data['title'] = '';
		} 
		
		if (isset($this->request->post['city'])) {
			$data['city'] = $this->request->post['city'];
		} elseif (isset($p_review_info)) {
			$data['city'] = $p_review_info['city'];
		} else {
			$data['city'] = '';
		}
		
		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (isset($p_review_info)) {
			$data['email'] = $p_review_info['email'];
		} else {
			$data['email'] = '';
		}
		
		if (isset($this->request->post['name'])) {
			$data['name'] = $this->request->post['name'];
		} elseif (isset($p_review_info)) {
			$data['name'] = $p_review_info['name'];
		} else {
			$data['name'] = '';
		}
		
		if (isset($this->request->post['text'])) {
			$data['text'] = $this->request->post['text'];
		} elseif (isset($p_review_info)) {
			$data['text'] = $p_review_info['text'];
		} else {
			$data['text'] = '';
		}
		
		if (isset($this->request->post['good'])) {
			$data['good'] = $this->request->post['good'];
		} elseif (isset($p_review_info)) {
			$data['good'] = $p_review_info['good'];
		} else {
			$data['good'] = '';
		}
		
		if (isset($this->request->post['bad'])) {
			$data['bad'] = $this->request->post['bad'];
		} elseif (isset($p_review_info)) {
			$data['bad'] = $p_review_info['bad'];
		} else {
			$data['bad'] = '';
		}
		
		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} elseif (isset($p_review_info)) {
			$data['comment'] = $p_review_info['comment'];
		} else {
			$data['comment'] = '';
		}
		
		if (isset($this->request->post['rating'])) {
			$data['rating'] = $this->request->post['rating'];
		} elseif (isset($p_review_info)) {
			$data['rating'] = $p_review_info['rating'];
		} else {
			$data['rating'] = '5';
		}
		
		if (isset($this->request->post['avatar'])) {
			$data['avatar'] = $this->request->post['avatar'];
		} elseif (!empty($p_review_info)) {
			$data['avatar'] = $p_review_info['avatar'];
		} else {
			$data['avatar'] = '';
		}

		$this->load->model('tool/image');

		if (isset($this->request->post['avatar']) && is_file(DIR_IMAGE . $this->request->post['avatar'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['avatar'], 100, 100);
		} elseif (!empty($p_review_info) && is_file(DIR_IMAGE . $p_review_info['avatar'])) {
			$data['thumb'] = $this->model_tool_image->resize($p_review_info['avatar'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['image'])) {
			$data['image'] = array();
			
			if (array_diff($this->request->post['image'], array(''))) {
				foreach ($this->request->post['image'] as $value) {
					$data['image'][] = array(
						'thumb' => $this->model_tool_image->resize($value, 100, 100),
						'image' => $value
					);
				}
			}
		} elseif (isset($p_review_info)) {
			$data['image'] = array();
			
			if ($p_review_info['image']) {
				foreach (explode('|', $p_review_info['image']) as $value) {
					$data['image'][] = array(
						'thumb' => $this->model_tool_image->resize($value, 100, 100),
						'image' => $value
					);
				}
			}
		} else {
			$data['image'] = array();
		}

		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		if (isset($this->request->post['date_added'])) {
			$data['date_added'] = $this->request->post['date_added'];
		} elseif (isset($p_review_info)) {
			$data['date_added'] = $p_review_info['date_added'];
		} else {
			$data['date_added'] = date("Y-m-d");
		}

		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		if (isset($this->request->post['language_id'])) {
			$data['language_id'] = $this->request->post['language_id'];
		} elseif (isset($p_review_info)) {
			$data['language_id'] = $p_review_info['language_id'];
		} else {
			$data['language_id'] = '';
		}

		if (isset($this->request->post['status'])) {
			$data['status'] = $this->request->post['status'];
		} elseif (isset($p_review_info)) {
			$data['status'] = $p_review_info['status'];
		} else {
			$data['status'] = 1;
		}
		
		$this->load->model('setting/store');

		$data['stores'] = array();
		
		$data['stores'][] = array(
			'store_id' => 0,
			'name'     => $this->language->get('text_default')
		);
		
		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $store) {
			$data['stores'][] = array(
				'store_id' => $store['store_id'],
				'name'     => $store['name']
			);
		}

		if (isset($this->request->post['p_review_store'])) {
			$data['p_review_store'] = $this->request->post['p_review_store'];
		} elseif (isset($this->request->get['p_review_id'])) {
			$data['p_review_store'] = $this->model_extension_module_p_review->getReviewStores($this->request->get['p_review_id']);
		} else {
			$data['p_review_store'] = array(0);
		}
		
		if (isset($this->request->post['p_review_seo_url'])) {
			$data['p_review_seo_url'] = $this->request->post['p_review_seo_url'];
		} elseif (isset($this->request->get['p_review_id'])) {
			$data['p_review_seo_url'] = $this->model_extension_module_p_review->getReviewSeoUrls($this->request->get['p_review_id']);
		} else {
			$data['p_review_seo_url'] = array();
		}
		
		if (isset($this->request->post['store_name'])) {
			$data['store_name'] = $this->request->post['store_name'];
		} elseif (isset($p_review_info)) {
			$data['store_name'] = $p_review_info['store_name'];
		} else {
			$data['store_name'] = '';
		}
		
		if (isset($this->request->post['store_url'])) {
			$data['store_url'] = $this->request->post['store_url'];
		} elseif (isset($p_review_info)) {
			$data['store_url'] = $p_review_info['store_url'];
		} else {
			$data['store_url'] = '';
		}
		
		if (isset($this->request->post['module_p_review_layout'])) {
			$data['module_p_review_layout'] = $this->request->post['module_p_review_layout'];
		} elseif (isset($this->request->get['p_review_id'])) {
			$data['module_p_review_layout'] = $this->model_extension_module_p_review->getReviewLayouts($this->request->get['p_review_id']);
		} else {
			$data['module_p_review_layout'] = array();
		}

		$this->load->model('design/layout');

		$data['layouts'] = $this->model_design_layout->getLayouts();
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/p_review/form', $data));
	}

	private function validateForm() {
		if (!$this->user->hasPermission('modify', 'extension/module/p_review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if ($this->config->get('module_p_review_title') == 2) {
			if ((utf8_strlen($this->request->post['title']) < 1) || (utf8_strlen($this->request->post['title']) > 255)) {
				$this->error['title'] = $this->language->get('error_title');
			}
		}
		
		if ($this->config->get('module_p_review_city') == 2) {
			if ((utf8_strlen($this->request->post['city']) < 1) || (utf8_strlen($this->request->post['city']) > 255)) {
				$this->error['city'] = $this->language->get('error_city');
			}
		}
		
		if ($this->config->get('module_p_review_name') == 2) {
			if ((utf8_strlen($this->request->post['name']) < 1) || (utf8_strlen($this->request->post['name']) > 255)) {
				$this->error['name'] = $this->language->get('error_name');
			}
		}
		
		if ($this->config->get('module_p_review_email') == 2) {
			if (utf8_strlen($this->request->post['email']) > 96 || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
				$this->error['email'] = $this->language->get('error_email');
			}
		}
		
		if ($this->config->get('module_p_review_text') == 2) {
			if (utf8_strlen($this->request->post['text']) < 1) {
				$this->error['text'] = $this->language->get('error_text');
			}
		}
			
		if ($this->config->get('module_p_review_good') == 2) {
			if (utf8_strlen($this->request->post['good']) < 1) {
				$this->error['good'] = $this->language->get('error_good');
			}
		}
			
		if ($this->config->get('module_p_review_bad') == 2) {
			if (utf8_strlen($this->request->post['bad']) < 1) {
				$this->error['bad'] = $this->language->get('error_bad');
			}
		}
		
		if ($this->config->get('module_p_review_rating') == 2) {
			if (empty($this->request->post['rating']) || $this->request->post['rating'] < 0 || $this->request->post['rating'] > 5) {
				$this->error['rating'] = $this->language->get('error_rating');
			}
		}

		if ($this->config->get('module_p_review_avatar') == 3) {
			if (utf8_strlen($this->request->post['avatar']) == 0) {
				$this->error['avatar'] = $this->language->get('error_avatar');
			}
		}
		
		if ($this->config->get('module_p_review_image') == 3) {
			if (!array_diff($this->request->post['image'], array(''))) {
				$this->error['image'] = $this->language->get('error_image');
			}
		}
		
		if ($this->request->post['p_review_seo_url']) {
			$this->load->model('design/seo_url');
			
			foreach ($this->request->post['p_review_seo_url'] as $store_id => $language) {
				foreach ($language as $language_id => $keyword) {
					if (!empty($keyword)) {
						if (count(array_keys($language, $keyword)) > 1) {
							$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_unique');
						}						
						
						$seo_urls = $this->model_design_seo_url->getSeoUrlsByKeyword($keyword);
						
						foreach ($seo_urls as $seo_url) {
							if (($seo_url['store_id'] == $store_id) && (!isset($this->request->get['p_review_id']) || (($seo_url['query'] != 'p_review_id=' . $this->request->get['p_review_id'])))) {
								$this->error['keyword'][$store_id][$language_id] = $this->language->get('error_keyword');
								
								break;
							}
						}
					}
				}
			}
		}
		
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
			
		return !$this->error;
	}

	private function validateDelete() {
		if (!$this->user->hasPermission('modify', 'extension/module/p_review')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
	
	public function install() {
		$this->load->model('extension/module/p_review');
		$this->model_extension_module_p_review->createDatabaseTables();
	}
}