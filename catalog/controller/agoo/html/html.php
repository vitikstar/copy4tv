<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class ControllerAgooHtmlHtml extends Controller
{
	private $error = array();
	protected $data;
	protected $widget_type = 'html';

	public function index($data) {
		$this->data = $data;
		$this->language->load('agoo/html/html');
		$this->data['type'] = 'html';
		$html_flag_work = false;

		$this->data['template'] = 'html.tpl';

		$this->load->model('record/blog');

		$this->data['language'] = $this->language;
		$this->data['request']  = $this->request;
		$this->data['document'] = $this->document;
		$this->data['registry'] = $this->registry;
		$this->data['config'] = $this->config;
		$this->data['session'] = $this->session;


		if (isset($this->data['settingswidget']['search']) && !empty($this->data['settingswidget']['search'])) {
			if (isset($this->request->get['filter_name']) && $this->request->get['filter_name'] != '') {
				$this->data['text_for_search'] =  htmlspecialchars(strip_tags(html_entity_decode( $this->db->escape($this->request->get['filter_name']), ENT_QUOTES, 'UTF-8')), ENT_COMPAT, 'UTF-8');
			} else {
				$this->data['text_for_search'] = $this->language->get('text_for_search');
			}
        	$this->data['text_for_childcategory'] = $this->language->get('text_for_childcategory');
        	$this->data['text_for_desc'] = $this->language->get('text_for_desc');

			$this->data['blogies'] = array();
			$blogies_1 = $this->model_record_blog->getBlogies(0);
			foreach ($blogies_1 as $blog_1) {
				$level_2_data = array();
				$blogies_2    = $this->model_record_blog->getBlogies($blog_1['blog_id']);
				foreach ($blogies_2 as $blog_2) {
					$level_3_data = array();
					$blogies_3    = $this->model_record_blog->getBlogies($blog_2['blog_id']);
					foreach ($blogies_3 as $blog_3) {
						$level_3_data[] = array(
							'blog_id' => $blog_3['blog_id'],
							'name' => $blog_3['name']
						);
					}
					$level_2_data[] = array(
						'blog_id' => $blog_2['blog_id'],
						'name' => $blog_2['name'],
						'children' => $level_3_data
					);
				}
				$this->data['blogies'][] = array(
					'blog_id' => $blog_1['blog_id'],
					'name' => $blog_1['name'],
					'children' => $level_2_data
				);
			}
			$this->data['text_blog'] = $this->language->get('text_blog');
			if (isset($this->data['settings_general']['blog_search']) && $this->data['settings_general']['blog_search']) {
				$blog_info = $this->model_record_blog->getBlog($this->data['settings_general']['blog_search']);
				if ($blog_info) {
					$this->data['blog_search'] = array(
						'text' => $blog_info['name'],
						'href' => $this->url->link('record/blog', 'blog_id=' . $blog_info['blog_id'])
					);
				}
			}
		}
		$categories = array();
		$manufacturers = array();
		$blogs      = array();
		if (isset($this->data['settingswidget']['categories']) && !empty($this->data['settingswidget']['categories'])) {
			if ($this->data['route'] == 'product/category' && isset($this->request->get['path'])) {
				$categor = explode('_', (string) $this->request->get['path']);
				$categor_int = end($categor);
				$categor_int = (int)$categor_int;
				$categories[]['category_id'] = $categor_int;
			}
			if ($this->data['route'] == 'product/product' && isset($this->request->get['product_id'])) {
				$categories = $this->model_record_blog->getCategoriesByProduct((int)$this->request->get['product_id']);
			}
			foreach ($this->data['settingswidget']['categories'] as $num => $cat_id) {
				if (!empty($categories)) {
					foreach ($categories as $cat_num => $cat_cat_id) {
						if ($cat_id == $cat_cat_id['category_id']) {
							$html_flag_work = true;
						}
					}
				}
			}
		}

		if (isset($this->data['settingswidget']['manufacturers']) && !empty($this->data['settingswidget']['manufacturers'])) {
			if ($this->data['route'] == 'product/product' && isset($this->request->get['product_id'])) {
				$manufacturers = $this->model_record_blog->getManufacturersByProduct((int)$this->request->get['product_id']);
			}

			foreach ($this->data['settingswidget']['manufacturers'] as $man_num => $man_id) {
				if (!empty($manufacturers)) {
					foreach ($manufacturers as $manufacturer_num => $man_man_id) {
						if ($man_id == $man_man_id['manufacturer_id']) {
							$html_flag_work = true;
						}
					}
				}
			}
		}

		if (isset($this->data['settingswidget']['products']) && !empty($this->data['settingswidget']['products'])) {
			if ($this->data['route'] == 'product/product' && isset($this->request->get['product_id'])) {
				$product_id = (int)$this->request->get['product_id'];

				foreach ($this->data['settingswidget']['products'] as $prod_num => $prod_id) {
					if ($prod_id == $product_id) {
						$html_flag_work = true;
					}
				}
			}
		}

		if (isset($this->data['settingswidget']['blogs']) && !empty($this->data['settingswidget']['blogs'])) {
			if ($this->data['route'] == 'record/blog' && isset($this->request->get['blog_id'])) {
				$categor = explode('_', (string) $this->request->get['blog_id']);
				$categor_int = end($categor);
				$categor_int = (int)$categor_int;
				$blogs[]['blog_id'] = $categor_int;
			}
			if ($this->data['route'] == 'record/record' && isset($this->request->get['record_id'])) {
				$this->load->model('record/blog');
				$blogs = $this->model_record_blog->getBlogiesByRecord((int)$this->request->get['record_id']);
			}

			foreach ($this->data['settingswidget']['blogs'] as $num => $cat_id) {
				foreach ($blogs as $cat_num => $cat_cat_id) {
					if ($cat_id == $cat_cat_id['blog_id']) {

						$html_flag_work = true;
					}
				}
			}
		} else {
			if (!isset($this->data['settingswidget']['categories']) && empty($this->data['settingswidget']['categories']) &&
				!isset($this->data['settingswidget']['manufacturers']) && empty($this->data['settingswidget']['manufacturers']) &&
				!isset($this->data['settingswidget']['products']) && empty($this->data['settingswidget']['products'])
				) {
					$html_flag_work = true;
				}
		}

       /*
		if (isset($this->data['settingswidget']['reserved']) && $this->data['settingswidget']['reserved'] != '') {
        	$this->data['settingswidget']['reserved'] = html_entity_decode($this->data['settingswidget']['reserved']['reserved'], ENT_QUOTES, 'UTF-8');
		}
       */
		if ((!isset($this->data['settingswidget']['blogs']) || empty($this->data['settingswidget']['blogs'])) &&
			(!isset($this->data['settingswidget']['categories']) || empty($this->data['settingswidget']['categories'])) &&
			(!isset($this->data['settingswidget']['manufacturers']) || empty($this->data['settingswidget']['manufacturers'])) &&
			(!isset($this->data['settingswidget']['products']) || empty($this->data['settingswidget']['products']))
			) {
			$html_flag_work = true;
		}


        if (isset($this->data['settingswidget']['html_modal'][$this->config->get('config_language_id')]) && $this->data['settingswidget']['html_modal'][$this->config->get('config_language_id')]!='') {
			$this->data['html_modal'] = html_entity_decode($this->data['settingswidget']['html_modal'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		} else {
        	$this->data['html_modal'] = '';
		}


		if (isset($this->data['settingswidget']['search']) && !empty($this->data['settingswidget']['search'])) {

			if (SC_VERSION > 23) {
			    $template_engine = $this->config->get('template_engine');
				$this->config->set('template_engine', 'twig');
		    }

            $template_search = 'search.tpl';
            $template_info = pathinfo($template_search);
            $template_search = $template_info['filename'];
			$search_template = $this->seocmslib->template('agootemplates/module/' . $template_search);

            if ($search_template) {
				if (SC_VERSION < 20) {
					$this_template = $this->template;
					$this->template = $search_template;
					$this->data['module_search'] = $this->render();
					$this->template = $this_template;
				} else {
					$this->data['module_search'] = $this->load->view($search_template, $this->data);
					if (SC_VERSION > 23) {
						$this->config->set('template_engine', $template_engine);
				    }
				}
			} else {
				$this->data['module_search'] = '';
			}
		}

		if ($html_flag_work) {
			$class_widget                = $this->data['type'] . '_widget';
			$this->data                  = $this->$class_widget($this->data);
			$this->data['html_template'] = $this->data['template'];
		} else {
			$this->data['html_template'] = '';
		}

		return $this->data;
	}

	private function html_widget($data) {
		$this->data         = $data;
		$this->data['html'] = html_entity_decode($this->data['settingswidget']['html'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		$html_name          = "cache.html." . md5(serialize($this->data['settingswidget'])) . "." .(int)$this->config->get('config_language_id').".".(int)$this->config->get('config_store_id') . ".tpl";
		$file               = DIR_CACHE . $html_name;
		$this->deletecache('cache.html');

		if (!file_exists($file) || (isset($this->data['settings_general']['cache_widgets']) && !$this->data['settings_general']['cache_widgets'])) {
			$handle = fopen($file, 'w');
			fwrite($handle, $this->data['html']);
			fclose($handle);
		}
		if (file_exists($file)) {
			$this->data['mark'] = "Mark";
			extract($this->data);
			ob_start();
			require($file);
			$this->output = ob_get_contents();
			ob_end_clean();
		}
		$this->data['html'] = $this->output;

		if (isset($this->data['settingswidget']['title_list_latest'][$this->config->get('config_language_id')]))
			$this->data['heading_title'] = $this->data['settingswidget']['title_list_latest'][$this->config->get('config_language_id')];
		else
			$this->data['heading_title'] = '';

		if (isset($this->data['settingswidget']['template']) && $this->data['settingswidget']['template'] != '') {
			$this->data['template'] = 'agootemplates/widgets/html/' . $this->data['settingswidget']['template'];
		} else {
			$this->data['template'] = 'agootemplates/widgets/html/'.$this->data['template'];
		}
		return $this->data;
	}
	private function deletecache($key) {
		$files = glob(DIR_CACHE . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');
		if ($files) {
			foreach ($files as $file) {
				if (file_exists($file)) {
					$file_time    = filemtime($file);
					$date_current = date("d-m-Y H:i:s");
					$date_diff    = (strtotime($date_current) - ($file_time)) / 60;
					if ($date_diff > 1) {
						unlink($file);
					}
				}
			}
		}
	}

	public function css($data) {
		$this->data = $data;
        $widget_tpl_content = '';
        $widget_tpl_flag = false;
        $widget_css_content = '';
        $widget_css_flag = false;

        $template = 'agootemplates/stylesheet/'.$this->widget_type.'/'.$this->widget_type.'css';
        $this->template = $this->seocmslib->template($template);
        if ($this->template != '') {
			$widget_tpl_flag = true;
		} else {
			$widget_tpl_flag = false;
		}

		if ($widget_tpl_flag) {
			if (SC_VERSION < 20) {
				$widget_tpl_content = $this->render();
			} else {
				if (SC_VERSION > 23) {
				    $template_engine = $this->config->get('template_engine');
				    $this->config->set('template_engine', 'template');
				}
				$widget_tpl_content = $this->load->view($this->template, $this->data);
				if (SC_VERSION > 23) {
					$this->config->set('template_engine', $template_engine);
				}
			}
		}


		if (file_exists(DIR_TEMPLATE . $this->seocmslib->theme_folder . '/template/agootemplates/stylesheet/'.$this->widget_type.'/'.$this->widget_type.'.css')) {
			$widget_css_file = DIR_TEMPLATE . $this->seocmslib->theme_folder . '/template/agootemplates/stylesheet/'.$this->widget_type.'/'.$this->widget_type.'.css';
			$widget_css_flag = true;
		} else {
			if (file_exists(DIR_TEMPLATE .'default/template/agootemplates/stylesheet/'.$this->widget_type.'/'.$this->widget_type.'.css')) {
				$widget_css_file = DIR_TEMPLATE .'default/template/agootemplates/stylesheet/'.$this->widget_type.'/'.$this->widget_type.'.css';
				$widget_css_flag = true;
			}
		}
        if ($widget_css_flag) {
        	$widget_css_content = file_get_contents(str_replace('..', '', $widget_css_file));
        }

        $css_content = $widget_tpl_content . $widget_css_content;

		return $css_content;
	}



}
