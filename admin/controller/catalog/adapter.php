<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class ControllerCatalogAdapter extends Controller
{
	private $error = array();
	protected $data;

	public function index() {
		$this->config->set("blog_work", true);
        if (SC_VERSION > 23) {
        	$this->data['token_name'] = 'user_token';
        } else {
        	$this->data['token_name'] = 'token';
        }
        if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
        	$url_link_ssl = true;
        } else {
        	if (SC_VERSION < 20) {
        		$url_link_ssl = 'NONSSL';
        	} else {
        		$url_link_ssl = false;
        	}
        }
        $this->data['token'] = $this->session->data[$this->data['token_name']];
		$this->language->load('module/blog');
		$this->data['oc_version'] = str_pad(str_replace(".", "", VERSION), 7, "0");
		$this->load->model('setting/setting');
		$this->data['blog_version']       = '*';
		$this->data['blog_version_model'] = '';
		$settings_admin                   = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) {
			$this->data['blog_version'] = $value;
		}
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) {
			$this->data['blog_version_model'] = $value;
		}
		$this->data['blog_version'] = $this->data['blog_version'] . ' ' . $this->data['blog_version_model'];
		$this->language->load('seocms/catalog/adapter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->data['tab_general']      = $this->language->get('tab_general');
		$this->data['tab_list']         = $this->language->get('tab_list');
		$this->data['url_modules_text'] = $this->language->get('url_modules_text');
		$this->data['url_modules']      = $this->url->link('extension/module', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_options']      = $this->url->link('module/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_schemes']      = $this->url->link('module/blog/schemes', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_widgets']      = $this->url->link('module/blog/widgets', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_back']         = $this->url->link('module/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_back_text']    = $this->language->get('url_back_text');
		$this->data['url_record']       = $this->url->link('catalog/record', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_record_text']  = $this->language->get('url_record_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_blog']         = $this->url->link('catalog/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_comment']      = $this->url->link('catalog/comment', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_blog_text']    = $this->language->get('url_blog_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text']  = $this->language->get('url_create_text');
		$this->load->model('catalog/adapter');

        if (SC_VERSION > 23) {
	        $template_engine = $this->config->get('template_engine');
    	    $this->config->set('template_engine', 'template');
        }


		if (SC_VERSION > 23) {
			$this->config->set('template_engine', $template_engine);
	    }

		$this->getList();
	}

	private function getList() {
        if (SC_VERSION > 23) {
        	$this->data['token_name'] = 'user_token';
        } else {
        	$this->data['token_name'] = 'token';
        }
        if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
        	$url_link_ssl = true;
        } else {
        	if (SC_VERSION < 20) {
        		$url_link_ssl = 'NONSSL';
        	} else {
        		$url_link_ssl = false;
        	}
        }
        $this->data['token'] = $this->session->data[$this->data['token_name']];
		if (file_exists(DIR_APPLICATION . 'view/stylesheet/seocmspro.css')) {
			$this->document->addStyle('view/stylesheet/seocmspro.css');
		}
		$this->data['url_back']      = $this->url->link('module/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_back_text'] = $this->language->get('url_back_text');
		$url                         = '';
		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']] . $url, $url_link_ssl),
			'separator' => ' :: '
		);

		$this->data['adapter_list']  = $this->getThemes();
		$this->load->model('tool/image');
		$this->data['adapter'] = array();
		$this->load->model('setting/setting');
		$store_info                 = $this->model_setting_setting->getSetting('config', 0);


		if (SC_VERSION > 21 && !$this->config->get('config_template')) {
			$directory = $this->config->get('config_theme').'_directory';
			$this->config->set('config_template', $this->config->get($directory));
		}
        /*
		if (SC_VERSION < 22) {
			$this->data['config_theme'] = $store_info['config_template'];
		} else {
			$this->data['config_theme'] = str_replace('theme_', '', $store_info['config_theme']);
		}
        */
        $this->data['config_theme'] = $this->config->get('config_template');

		foreach ($this->data['adapter_list'] as $id => $theme) {

			$result['adapter_id']   = $id;
			$result['adapter_name'] = $theme;

			if (file_exists(DIR_IMAGE . 'templates/' . $theme . '.png')) {
				$result['adapter_image'] = 'templates/' . $theme . '.png';
			} else {
				$result['adapter_image'] = 'no_image.jpg';
			}
			if (isset($result['adapter_image']) && $result['adapter_image'] && file_exists(DIR_IMAGE . $result['adapter_image'])) {
				$image = $this->model_tool_image->resize($result['adapter_image'], 120, 80);
			} else {
				$image = $this->model_tool_image->resize('no_image.jpg', 120, 80);
			}

			$action                 = array();

			$this->data['adapter'][] = array(
				'adapter_id' => (isset($result['adapter_id']) ? $result['adapter_id'] : ''),
				'adapter_name' => (isset($result['adapter_name']) ? $result['adapter_name'] : ''),
				'adapter_image' => $image,
				'selected' => isset($this->request->post['selected']) && in_array($result['adapter_id'], $this->request->post['selected']),
				'action' => $action
			);
		}

		$this->data['heading_title']      = $this->language->get('heading_title');
		$this->data['text_enabled']       = $this->language->get('text_enabled');
		$this->data['text_disabled']      = $this->language->get('text_disabled');
		$this->data['text_no_results']    = $this->language->get('text_no_results');
		$this->data['text_image_manager'] = $this->language->get('text_image_manager');
		$this->data['column_image']       = $this->language->get('column_image');
		$this->data['column_name']        = $this->language->get('column_name');
		$this->data['column_status']      = $this->language->get('column_status');
		$this->data['column_action']      = $this->language->get('column_action');

		$this->data[$this->data['token_name']]              = $this->session->data[$this->data['token_name']];
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

        require_once(DIR_SYSTEM . 'library/iblog.php');
        $this->data['agoo_widgets'] = iBlog::searchdir(DIR_APPLICATION . "controller/agoo", 'DIRS');
        foreach ($this->data['agoo_widgets'] as $nm => $agoo_widget) {
        		if (file_exists(DIR_APPLICATION . "controller/agoo/".$agoo_widget."/".$agoo_widget.".php")) {
	        		$this->control('agoo/'.$agoo_widget.'/'.$agoo_widget);
	        		$controller_agoo = 'controller_agoo_'.$agoo_widget.'_'.$agoo_widget;

	             	if (method_exists($this->registry->get($controller_agoo), 'adapter_button'))
	             	$this->data = $this->$controller_agoo->adapter_button($this->data);
        	}
        }

      	$this->cont('agooa/adminmenu');
       	$this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();
       	$this->data['agoo_header'] = $this->controller_agooa_adminmenu->admin_header($this->data);

        if (SC_VERSION > 23) {
	        $template_engine = $this->config->get('template_engine');
    	    $this->config->set('template_engine', 'template');
        }

		if (SC_VERSION > 23) {
			$this->template         = 'catalog/adapter_list';
		} else {
			$this->template         = 'catalog/adapter_list.tpl';
		}

		$this->children         = array(
			'common/header',
			'common/footer'
		);
		$this->data['registry'] = $this->registry;
		$this->data['language'] = $this->language;
		$this->data['config']   = $this->config;
		if (SC_VERSION < 20) {
			$this->data['column_left'] = '';
			$html                      = $this->render();
		} else {
			if (SC_VERSION > 23) {
				$this->config->set('template_engine', $template_engine);
	        }
			$this->data['header']      = $this->load->controller('common/header');
			$this->data['footer']      = $this->load->controller('common/footer');
			$this->data['column_left'] = $this->load->controller('common/column_left');
            if (SC_VERSION > 23) {
	            $this->config->set('template_engine', 'template');
	        }
			$html                      = $this->load->view($this->template, $this->data);
		}
		$this->response->setOutput($html);
	}

	private function getForm() {
		$this->config->set("blog_work", true);
        if (SC_VERSION > 23) {
        	$this->data['token_name'] = 'user_token';
        } else {
        	$this->data['token_name'] = 'token';
        }
        if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
        	$url_link_ssl = true;
        } else {
        	if (SC_VERSION < 20) {
        		$url_link_ssl = 'NONSSL';
        	} else {
        		$url_link_ssl = false;
        	}
        }
        $this->data['token'] = $this->session->data[$this->data['token_name']];
		$this->language->load('module/blog');
		$this->data['oc_version'] = str_pad(str_replace(".", "", VERSION), 7, "0");
		$this->load->model('setting/setting');
		$this->data['blog_version']       = '*';
		$this->data['blog_version_model'] = '*';
		$settings_admin                   = $this->model_setting_setting->getSetting('ascp_version', 'ascp_version');
		foreach ($settings_admin as $key => $value) {
			$this->data['blog_version'] = $value;
		}
		$settings_admin_model = $this->model_setting_setting->getSetting('ascp_version_model', 'ascp_version_model');
		foreach ($settings_admin_model as $key => $value) {
			$this->data['blog_version_model'] = $value;
		}

		if (file_exists(DIR_APPLICATION . 'view/stylesheet/seocmspro.css')) {
			$this->document->addStyle('view/stylesheet/seocmspro.css');
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/jquery/tabs.js')) {
			$this->document->addScript('view/javascript/jquery/tabs.js');
		} else {
			if (file_exists(DIR_APPLICATION . 'view/javascript/blog/tabs/tabs.js')) {
				$this->document->addScript('view/javascript/blog/tabs/tabs.js');
			}
		}
		if (file_exists(DIR_APPLICATION . 'view/javascript/blog/seocmspro.js')) {
			$this->document->addScript('view/javascript/blog/seocmspro.js');
		}

		if (isset($this->request->get['adapter_theme'])) {
			$theme = $this->data['theme'] = $this->request->get['adapter_theme'];
		} else {
			$theme = $this->data['theme'] = '';
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}
		$url = '';
		$this->data['no_image']        = '';
		$this->language->load('seocms/catalog/adapter');

        if (SC_VERSION > 23) {
	        $template_engine = $this->config->get('template_engine');
    	    $this->config->set('template_engine', 'template');
        }
        $this->cont('agooa/adminmenu');
        $this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();
		if (SC_VERSION > 23) {
			$this->config->set('template_engine', $template_engine);
	    }
		$this->data['config_language'] 	= $this->config->get('config_language');

		$this->data['blog_version']     = $this->data['blog_version'] . ' ' . $this->data['blog_version_model'];
		$this->data['tab_general']      = $this->language->get('tab_general');
		$this->data['tab_list']         = $this->language->get('tab_list');
		$this->data['url_modules_text'] = $this->language->get('url_modules_text');
		$this->data['url_back_text']    = $this->language->get('url_back_text');
		$this->data['url_record_text']  = $this->language->get('url_record_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');
		$this->data['url_blog_text']    = $this->language->get('url_blog_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');
		$this->data['url_comment_text'] = $this->language->get('url_comment_text');
		$this->data['url_create_text']  = $this->language->get('url_create_text');
		$this->data['heading_title']    = $this->language->get('heading_title');
		$this->data['text_enabled']     = $this->language->get('text_enabled');
		$this->data['text_disabled']    = $this->language->get('text_disabled');
		$this->data['text_none']        = $this->language->get('text_none');
		$this->data['text_yes']         = $this->language->get('text_yes');
		$this->data['text_no']          = $this->language->get('text_no');
		$this->data['text_select_all']  = $this->language->get('text_select_all');
		$this->data['text_unselect_all']= $this->language->get('text_unselect_all');
		$this->data['url_back_text']    = $this->language->get('url_back_text');
		$this->data['url_adapter_text'] = $this->language->get('url_adapter_text');

		$this->data['url_record']       = $this->url->link('catalog/record', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_blog']         = $this->url->link('catalog/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_comment']      = $this->url->link('catalog/comment', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_back']         = $this->url->link('module/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_blog']         = $this->url->link('catalog/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_adapter']      = $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_comment']      = $this->url->link('catalog/comment', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_modules']      = $this->url->link('extension/module', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_options']      = $this->url->link('module/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_schemes']      = $this->url->link('module/blog/schemes', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_widgets']      = $this->url->link('module/blog/widgets', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
		$this->data['url_back']         = $this->url->link('module/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);

        require_once(DIR_SYSTEM . 'library/iblog.php');
        $this->data['agoo_widgets'] = iBlog::searchdir(DIR_APPLICATION . "controller/agoo", 'DIRS');
        foreach ($this->data['agoo_widgets'] as $nm => $agoo_widget) {
        		if (file_exists(DIR_APPLICATION . "controller/agoo/".$agoo_widget."/".$agoo_widget.".php")) {
	        		$this->control('agoo/'.$agoo_widget.'/'.$agoo_widget);
	        		$controller_agoo = 'controller_agoo_'.$agoo_widget.'_'.$agoo_widget;

	             	if (method_exists($this->registry->get($controller_agoo), 'adapter_form'))
	             	$this->data = $this->$controller_agoo->adapter_form($this->data);
        	}
        }

      	$this->cont('agooa/adminmenu');
       	$this->data['agoo_menu'] = $this->controller_agooa_adminmenu->index();
       	$this->data['agoo_header'] = $this->controller_agooa_adminmenu->admin_header($this->data);

/***************************************************/

		$this->template = $this->data['template'];

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl),
			'separator' => false
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']] . $url, $url_link_ssl),
			'separator' => ' :: '
		);



		$this->data['cancel'] = $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']] . $url, $url_link_ssl);
		$this->data[$this->data['token_name']]  = $this->session->data[$this->data['token_name']];


		$this->load->model('localisation/language');
		$this->data['languages'] = $this->model_localisation_language->getLanguages();

        if (SC_VERSION > 23) {
	        $template_engine = $this->config->get('template_engine');
    	    $this->config->set('template_engine', 'template');
        }

		$this->children          = array(
			'common/header',
			'common/footer'
		);
		$this->data['registry']  = $this->registry;
		$this->data['language']  = $this->language;
		$this->data['config']    = $this->config;
		if (SC_VERSION < 20) {
			$this->data['column_left'] = '';
			$html                      = $this->render();
		} else {
			if (SC_VERSION > 23) {
				$this->config->set('template_engine', $template_engine);
	        }
			$this->data['header']      = $this->load->controller('common/header');
			$this->data['footer']      = $this->load->controller('common/footer');
			$this->data['column_left'] = $this->load->controller('common/column_left');
            if (SC_VERSION > 23) {
	            $this->config->set('template_engine', 'template');
	        }
			$html                      = $this->load->view($this->template, $this->data);
			if (SC_VERSION > 23) {
				$this->config->set('template_engine', $template_engine);
	        }
		}
		$this->response->setOutput($html);
	}

	public function update() {
        if (SC_VERSION > 23) {
        	$this->data['token_name'] = 'user_token';
        } else {
        	$this->data['token_name'] = 'token';
        }
        $this->data['token'] = $this->session->data[$this->data['token_name']];

        if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
        	$url_link_ssl = true;
        } else {
        	if (SC_VERSION < 20) {
        		$url_link_ssl = 'NONSSL';
        	} else {
        		$url_link_ssl = false;
        	}
        }
        if (SC_VERSION > 23) {
	        $template_engine = $this->config->get('template_engine');
    	    $this->config->set('template_engine', 'template');
        }

		$this->language->load('seocms/catalog/adapter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/adapter');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {

            if (isset($this->request->get['widget'])) {
            	$this->data['agoo_widgets'] = Array(0 => $this->request->get['widget']);
            } else {
            	require_once(DIR_SYSTEM . 'library/iblog.php');
	        	$this->data['agoo_widgets'] = iBlog::searchdir(DIR_APPLICATION . "controller/agoo", 'DIRS');
	        }

	        foreach ($this->data['agoo_widgets'] as $nm => $agoo_widget) {
	        		if (file_exists(DIR_APPLICATION . "controller/agoo/".$agoo_widget."/".$agoo_widget.".php")) {
		        		$this->control('agoo/'.$agoo_widget.'/'.$agoo_widget);
		        		$controller_agoo = 'controller_agoo_'.$agoo_widget.'_'.$agoo_widget;

		             	if (method_exists($this->registry->get($controller_agoo), 'adapter_update'))
		             	$this->data = $this->$controller_agoo->adapter_update($this->data);
	        	}
	        }
		}

 		if (SC_VERSION > 23) {
			$this->config->set('template_engine', $template_engine);
	    }
		$this->getForm();
	}

	public function loaddata() {
        if (SC_VERSION > 23) {
        	$this->data['token_name'] = 'user_token';
        } else {
        	$this->data['token_name'] = 'token';
        }
        if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
        	$url_link_ssl = true;
        } else {
        	if (SC_VERSION < 20) {
        		$url_link_ssl = 'NONSSL';
        	} else {
        		$url_link_ssl = false;
        	}
        }
        $this->data['token'] = $this->session->data[$this->data['token_name']];
		$this->language->load('seocms/catalog/adapter');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('catalog/adapter');
		if ($this->validate()) {
            if (isset($this->request->get['widget'])) {
            	$this->data['agoo_widgets'] = Array(0 => $this->request->get['widget']);
            } else {
            	require_once(DIR_SYSTEM . 'library/iblog.php');
	        	$this->data['agoo_widgets'] = iBlog::searchdir(DIR_APPLICATION . "controller/agoo", 'DIRS');
	        }

	        foreach ($this->data['agoo_widgets'] as $nm => $agoo_widget) {
	        		if (file_exists(DIR_APPLICATION . "controller/agoo/".$agoo_widget."/".$agoo_widget.".php")) {
		        		$this->control('agoo/'.$agoo_widget.'/'.$agoo_widget);
		        		$controller_agoo = 'controller_agoo_'.$agoo_widget.'_'.$agoo_widget;

		             	if (method_exists($this->registry->get($controller_agoo), 'loaddata'))
		             	$html = $this->$controller_agoo->loaddata($this->data);
	        	}
	        }
		}
		$this->response->setOutput($html);
	}


	private function validate() {
		if (!$this->user->hasPermission('modify', 'catalog/adapter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
	private function validateForm()	{
		if (!$this->user->hasPermission('modify', 'catalog/adapter')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		$this->request->get['adapter_theme'] = preg_replace("/[^a-zA-Z0-9_\s\-]/", "", $this->request->get['adapter_theme']);
		if ($this->request->get['adapter_theme'] == '') {
			$this->error['warning'] = $this->language->get('error_name');
		}
		if ($this->error && !isset($this->error['warning'])) {
			$this->error['warning'] = $this->language->get('error_warning');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}

	private function getThemes() {
		$this->data['themes'] = array();
		$directories          = glob(DIR_CATALOG . 'view/theme/*', GLOB_ONLYDIR);
		foreach ($directories as $directory) {
			$this->data['themes'][] = basename($directory);
		}
		return $this->data['themes'];
	}


	public function cont($cont) {
		$file  = DIR_CATALOG . 'controller/' . $cont . '.php';
		if (file_exists($file)) {
           $this->cont_loading($cont, $file);
		} else {
			$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
            if (file_exists($file)) {
             	$this->cont_loading($cont, $file);
            } else {
				trigger_error('Error: Could not load controller ' . $cont . '!');
				exit();
			}
		}
	}

	public function control($cont) {
		$file = DIR_APPLICATION . 'controller/' . $cont . '.php';
		$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
		if (file_exists($file)) {
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
		} else {
			trigger_error('Error: Could not load controller ' . $cont . '!');
			exit();
		}
	}

	private function cont_loading ($cont, $file) {
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
			include_once($file);
			$this->registry->set('controller_' . str_replace('/', '_', $cont), new $class($this->registry));
	}

 /*
 	private function formatHtml($html_new)
	{
		$healthy  = array(
			"<div",
			"/div>",
			"\t"
		);
		$yummy    = array(
			"\r\n<div",
			"/div>\r\n",
			""
		);
		$html_new = str_replace($healthy, $yummy, $html_new);
		$healthy  = array(
			"<div",
			"/div>",
			"\t"
		);
		$yummy    = array(
			"\r\n<div",
			"/div>\r\n",
			""
		);
		$html_new = str_replace($healthy, $yummy, $html_new);
		$html_new = preg_replace('/ {2,}/', ' ', $html_new);
		$html_new = preg_replace("/(\r\n){3,}/", "\r\n", $html_new);
		return $html_new;
	}

	public function backup($data)
	{
		$this->data = $data;
		foreach ($this->module_files as $module_file) {
			$module_file_content = DIR_CATALOG . 'view/theme/' . $this->data['theme'] . '/template/' . $module_file;
			if (file_exists($module_file_content)) {
				$path    = DIR_CACHE . 'view/theme/' . $this->data['theme'] . '/template/' . $module_file;
				$content = file_get_contents($module_file_content);
				$this->makefullpathfile($path);
				file_put_contents($path, $content);
			}
		}
	}

	public function makefullpathfile($path)
	{
		$path_file = dirname($path);
		$name_file = basename($path);
		$flag_save = false;
		$arr       = explode('/', $path_file);
		$curr      = array();
		foreach ($arr as $key => $val) {
			if (!empty($val)) {
				$curr[]  = $val;
				$mkdirka = implode('/', $curr) . "/";
				if (!file_exists($mkdirka)) {
					mkdir($mkdirka, 0777);
				}
			}
		}
		if (file_exists($path_file)) {
			file_put_contents($path, '');
			$flag_save = true;
		}
		return $flag_save;
	}
*/

}
require_once(DIR_SYSTEM . 'helper/seocmsprofunc.php');
