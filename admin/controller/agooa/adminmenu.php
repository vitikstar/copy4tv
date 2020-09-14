<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class ControllerAgooaAdminmenu extends Controller
{
	private $error = array();
	protected $data;

	public function index() {
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

		if (SC_VERSION < 20) {
			//$this->document->addScript('view/javascript/seocms/bootstrap/js/bootstrap.js');
			//$this->document->addStyle('view/javascript/seocms/bootstrap/css/bootstrap.css');
			$this->document->addStyle('view/javascript/seocms/font-awesome/css/font-awesome.css');
		}


        $this->data['token'] = $this->session->data[$this->data['token_name']];
	 	$this->config->set("blog_work", true);
        $this->data['ascp_settings'] = $this->config->get('ascp_settings');

        $html = '';

		$this->load->language('seocms/agooa/adminmenu');


  		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		}

       $this->data['agoo_menu_block']   = 'agoo_first_menu_block';
       $this->data['agoo_menu_options']       = $this->language->get('agoo_menu_options');

       $this->data['agoo_menu_url_options']   = $this->url->link('module/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if ($route == 'module/blog') {
       		$this->data['agoo_menu_active_options']   = ' markactive';
       		$this->data['agoo_menu_block']   = 'agoo_first_menu_block';
       } else {
       		$this->data['agoo_menu_active_options']   = '';
       }

       $this->data['agoo_menu_layouts']       = $this->language->get('agoo_menu_layouts');
       $this->data['agoo_menu_url_layouts']   = $this->url->link('module/blog/schemes', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if ($route == 'module/blog/schemes') {
       		$this->data['agoo_menu_active_layouts']   = ' markactive';
       		$this->data['agoo_menu_block']   = 'agoo_first_menu_block';
       } else {
       		$this->data['agoo_menu_active_layouts']   = '';
       }

       $this->data['agoo_menu_widgets']       = $this->language->get('agoo_menu_widgets');
       $this->data['agoo_menu_url_widgets']   = $this->url->link('module/blog/widgets', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if ($route == 'module/blog/widgets') {
	       	$this->data['agoo_menu_active_widgets']   = ' markactive';
       		$this->data['agoo_menu_block']   = 'agoo_first_menu_block';
       } else {
       		$this->data['agoo_menu_active_widgets']   = '';
       }

       $this->data['agoo_menu_modules'] = $this->language->get('agoo_menu_modules');

       if (SC_VERSION < 23) {
			$this->data['agoo_menu_url_modules'] = $this->url->link('extension/module', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       } else {
			$this->data['agoo_menu_url_modules'] = $this->url->link('extension/extension', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);

       }

       $this->data['agoo_menu_categories']    = $this->language->get('agoo_menu_categories');
       $this->data['agoo_menu_url_categories']= $this->url->link('catalog/blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if (strpos($route, 'catalog/blog') === false) {
       		$this->data['agoo_menu_active_categories']   = '';
       } else {
       		$this->data['agoo_menu_active_categories']   = ' markactive';
       		$this->data['agoo_menu_block']   = 'agoo_second_menu_block';
       }

       $this->data['agoo_menu_records']       = $this->language->get('agoo_menu_records');
       $this->data['agoo_menu_url_records']   = $this->url->link('catalog/record', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if (strpos($route, 'catalog/record') === false) {
       		$this->data['agoo_menu_active_records']   = '';
       } else {
       		$this->data['agoo_menu_active_records']   = ' markactive';
       		$this->data['agoo_menu_block']   = 'agoo_second_menu_block';
       }

       $this->data['agoo_menu_comments']      = $this->language->get('agoo_menu_comments');
       $this->data['agoo_menu_url_comments']  = $this->url->link('catalog/comment', 'action=comment&'.$this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if (strpos($route, 'catalog/comment') === false) {
       		$this->data['agoo_menu_active_comments']   = '';
       } else {
			if (isset($this->request->get['action']) && $this->request->get['action']=='comment') {
	       		$this->data['agoo_menu_active_comments']   = ' markactive';
	       		$this->data['agoo_menu_block']   = 'agoo_second_menu_block';
	       	} else {
	       	 	$this->data['agoo_menu_active_comments']   = '';
	       	}
       }

       $this->data['agoo_menu_reviews']       = $this->language->get('agoo_menu_reviews');
       $this->data['agoo_menu_url_reviews']   = $this->url->link('catalog/comment', 'action=review&'.$this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if (strpos($route, 'catalog/comment') === false) {
			$this->data['agoo_menu_active_reviews']   = '';
       } else {
			if (isset($this->request->get['action']) && $this->request->get['action']=='review') {
	       		$this->data['agoo_menu_active_reviews']   = ' markactive';
	       		$this->data['agoo_menu_block']   = 'agoo_second_menu_block';
	       	} else {
	       	 	$this->data['agoo_menu_active_reviews']   = '';
	       	}
       }

       $this->data['agoo_menu_adapter']       = $this->language->get('agoo_menu_adapter');
       $this->data['agoo_menu_url_adapter']   = $this->url->link('catalog/adapter', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if (strpos($route, 'catalog/adapter') === false) {
       		$this->data['agoo_menu_active_adapter']   = '';
       } else {
	       	$this->data['agoo_menu_active_adapter']   = ' markactive';
       		$this->data['agoo_menu_block']   = 'agoo_third_menu_block';
       }

       $this->data['agoo_menu_sitemap']       = $this->language->get('agoo_menu_sitemap');
       $this->data['agoo_menu_url_sitemap']   = $this->url->link('feed/google_sitemap_blog', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if (strpos($route, 'feed/google_sitemap_blog') === false) {
       		$this->data['agoo_menu_active_sitemap']   = '';
       } else {
       		$this->data['agoo_menu_active_sitemap']   = ' markactive';
       		$this->data['agoo_menu_block']   = 'agoo_third_menu_block';
       }



       $this->data['agoo_menu_fields']       = $this->language->get('agoo_menu_fields');
       $this->data['agoo_menu_url_fields']   = $this->url->link('catalog/fields', $this->data['token_name'].'=' . $this->session->data[$this->data['token_name']], $url_link_ssl);
       if (strpos($route, 'catalog/fields') === false) {
       		$this->data['agoo_menu_active_fields']   = '';
       } else {
       		$this->data['agoo_menu_active_fields'] = ' markactive';
       		$this->data['agoo_menu_block'] = 'agoo_third_menu_block';
       }

		$this->children          = array();

		$this->data['language']  = $this->language;

		if (SC_VERSION > 23) {
			$this->template          = 'agooa/adminmenu';
		} else {
			$this->template          = 'agooa/adminmenu.tpl';
		}

		if (SC_VERSION < 20) {
			$html = $this->render();
		} else {
			$this->data['header']      = '';
			$this->data['menu']        = '';
			$this->data['footer']      = '';
			$this->data['column_left'] = '';

			if (SC_VERSION > 23) {
				$template_engine = $this->config->get('template_engine', 'template');
				$this->config->set('template_engine', 'template');
	        }

			$html = $this->load->view($this->template, $this->data);

			if (SC_VERSION > 23) {
				$this->config->set('template_engine', $template_engine);
	        }

		}
		return $html;
	}

	public function admin_header($data) {
        $html = '';
		if (SC_VERSION > 23) {
			$this_template          = 'agooa/adminheader';
		} else {
			$this_template          = 'agooa/adminheader.tpl';
		}

		$this->load->language('module/blog');
		$data['heading_dev'] = $this->language->get('heading_dev');

		if (SC_VERSION < 20) {

			$this->data = $data;
			$this->template = $this_template;
			$this->children = array();
			$html = $this->render();
		} else {
			$data['header']      = '';
			$data['menu']        = '';
			$data['footer']      = '';
			$data['column_left'] = '';

			if (SC_VERSION > 23) {
				$template_engine = $this->config->get('template_engine', 'template');
				$this->config->set('template_engine', 'template');
	        }

			$html = $this->load->view($this_template, $data);


			if (SC_VERSION > 23) {
				$this->config->set('template_engine', $template_engine);
	        }

		}
		return $html;
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'module/blog')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
