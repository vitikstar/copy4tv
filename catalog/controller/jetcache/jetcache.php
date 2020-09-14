<?php
if (!class_exists('ControllerJetcacheJetcache')) {
class ControllerJetcacheJetcache extends Controller {
	protected $data;
	protected $template;
	protected $jetcache_settings;
	protected $seocms_settings;
	protected $sc_cache_name;

	public function jetcache_construct() {

		$sc_ver = VERSION; if (!defined('SC_VERSION')) define('SC_VERSION', (int) substr(str_replace('.', '', $sc_ver), 0, 2));

		if ($this->config->get('ascp_settings') != '') {
			$this->seocms_settings = $this->config->get('ascp_settings');
		} else {
			$this->seocms_settings = Array();
		}
		$this->jetcache_settings = $this->registry->get('config')->get('asc_jetcache_settings');
        $this->setOutputRegistry($this->registry);

        return true;
	}


	public function index() {
		return true;
	}

	public function setOutputRegistry($registry) {
		if (is_callable(array('Response', 'seocms_setRegistry'))) {
			$this->response->seocms_setRegistry($registry);
		} else {
        	$this->registry->set('seocms_jetcache_alter', false);
		}
	}


	public function visual($arg) {

        $this->language->load('jetcache/jetcache');

        if (SC_VERSION > 21) {
        	$link_protocol = true;
        } else {
        	$link_protocol = 'SSL';
        }
        $this->data['jetcache_url_cache_remove'] = $this->url->link('module/blog/cacheremove', '', $link_protocol);
        $html = '';
        $this->data['load'] = $arg['load'];
        $this->data['start'] = $arg['start'];
        $this->data['end'] = $arg['end'];
        $this->data['queries'] = $arg['queries'];
        $this->data['cache'] = round($arg['end'] - $arg['start'], 3);
        $this->data['rate'] = round($this->data['load'] / $this->data['cache'], 0);
        $this->data['icon'] = getSCWebDir(DIR_IMAGE , $this->seocms_settings).'jetcache/jetcache-icon.png';

        if (is_callable(array('DB', 'get_sc_jetcache_query_count'))) {
        	$this->data['queries_cache'] = $this->db->get_sc_jetcache_query_count();
        } else {
        	$this->data['queries_cache'] = '';
        }

		if (SC_VERSION > 21 && !$this->config->get('config_template')) {
			$this->config->set('config_template', $this->config->get($this->config->get('config_theme').'_directory'));
		}

        $template = '/template/agootemplates/jetcache/visual.tpl';

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . $template) && is_file(DIR_TEMPLATE . $this->config->get('config_template') . $template)) {
			$this->template = $this->config->get('config_template') . $template;
		} else {
			if (file_exists(DIR_TEMPLATE . 'default' . $template) && is_file(DIR_TEMPLATE . 'default' . $template)) {
				$this->template = 'default' . $template;
			} else {
				$this->template = '';
			}
		}
        $this->data['language'] = $this->language;
		if ($this->template != '') {
			if (SC_VERSION < 20) {
				$html = $this->render();
			} else {
				if (!is_array($this->data))	$this->data = array();

				$html = $this->load->view($this->template, $this->data);
			}
		}

		return $html;
	}

	public function info($params, $name) {

        if ($this->registry->get('seocms_cache_status')) {

	       	if (($this->registry->get('sc_isLogged') && isset($this->jetcache_settings['jetcache_info_status']) && $this->jetcache_settings['jetcache_info_status']) || (isset($this->jetcache_settings['jetcache_info_demo_status']) && $this->jetcache_settings['jetcache_info_demo_status']) ) {

				if (is_array($this->registry->get('jetcache_output_visual'))) {

			        	$time_visual = $this->registry->get('jetcache_output_visual');

						$visual_html = $this->visual($time_visual);
						$visual_find = array('</body>');
						$visual_replace = array($visual_html. '</body>');

						if (strtolower($name) == 'setoutput') {
							$params = str_replace($visual_find, $visual_replace, $params);
						}
				} else {
		                $time_visual['start'] = $this->registry->get('sc_time_start');
		                $time_visual['end'] = microtime(true);
		                $time_visual['load'] = round($time_visual['end'] - $time_visual['start'], 3);

		                $time_visual['queries'] = $this->db->get_sc_jetcache_query_count();

						$visual_html = $this->visual($time_visual);
						$visual_find = array('</body>');
						$visual_replace = array($visual_html. '</body>');

						if (strtolower($name) == 'setoutput') {
							$params = str_replace($visual_find, $visual_replace, $params);
						}
				}
			}
        }
        return $params;

	}

    public function access_exeptions() {

			if (isset($this->jetcache_settings['ex_route']) && !empty($this->jetcache_settings['ex_route'])) {
				$routes = explode('/', $this->request->get['route']);
                $routes_count = count($routes);

			    foreach($this->jetcache_settings['ex_route'] as $ex_route) {
		    		if ($ex_route['status']) {
			    		$ex_routes = explode('/', $ex_route['route']);
                        $ex_routes_count = count($ex_routes);
						if ($ex_routes_count <= $routes_count) {

                            $new_array = array();
                            $prom_array = array();
						    $key_search = array_search('%', $ex_routes);
						    if ($routes_count - $ex_routes_count > 0) {
                            	$prom_array = array_fill($key_search, $routes_count - $ex_routes_count , '%');
                            }

                            array_splice($ex_routes, $key_search, 0, $prom_array);

	                        $key = 0;
							foreach ($routes as $routes_val) {
                            	if ($ex_routes[$key] == '%') {
                            		$ex_routes[$key] = $routes_val;
                            	}
								$key++;
			    			}

                            if ($routes == $ex_routes)  {
                            	 return $access_status = false;
                            }
						}
		    		}
			    }
			}
            $request_uri_trim = ltrim($this->request->server['REQUEST_URI'], '/');

			if (isset($this->jetcache_settings['ex_page']) && !empty($this->jetcache_settings['ex_page'])) {
			    foreach($this->jetcache_settings['ex_page'] as $ex_page) {
		    		if ($ex_page['status'] && ($request_uri_trim == $ex_page['url'] || (!$ex_page['accord'] && strpos($request_uri_trim, $ex_page['url']) !== false ))) {
		    			return $access_status = false;
		    		}
			    }
			}

        return true;

    }

	public function sc_force_cache_access_output() {

        $access_status = false;

        // Если loader первее загрузился чем seo_url роута может еще не быть (даже к примеру на категории товаров)  и тогда не найдет по имени файла кеша
		if (!isset($this->request->get['route'])) {
			if (!isset($this->request->get['_route_'])) {
            	$this->request->get['route'] = 'common/home';
			}
		}
		if (isset($this->request->get['record_id']) && isset($this->request->get['blog_id'])) {
			unset($this->request->get['blog_id']);
		}

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			return $access_status = false;
		}

        if ((isset($this->seocms_settings['cache_pages']) && $this->seocms_settings['cache_pages'] && !$this->registry->get('admin_work')) && (isset($this->request->get['record_id']) || isset($this->request->get['blog_id']))) {
        	return $access_status = true;
        }


		if (isset($this->request->get['route']) && $this->request->get['route'] != 'error/not_found') {

	      	if (isset($this->jetcache_settings['store']) && in_array($this->config->get('config_store_id'), $this->jetcache_settings['store'])) {
	       		$access_status = true;
	      	} else {
				return $access_status = false;
			}

			if ((isset($this->seocms_settings['jetcache_widget_status']) && $this->seocms_settings['jetcache_widget_status'] && isset($this->jetcache_settings['pages_status']) && $this->jetcache_settings['pages_status']) &&
				!$this->registry->get('admin_work')) {
				$access_status = true;
			} else {
				return $access_status = false;
			}

			$access_status = $this->access_exeptions();
        }
		return $access_status;
	}


	public function to_cache_output($name, $header_flag_json) {

		if ($this->sc_force_cache_access_output()) {

			if ($this->registry->get('seocms_cache_status') || $this->registry->get('blog_output')) {

	  			if (!$header_flag_json) {
		  			if (strtolower($name) == 'setoutput' || strtolower($name) == 'output') {

						if ($this->registry->get('url_old')) {
							$url = $this->registry->get('response_old');
							$class = get_class($url);
						} else {
							$url = $this->url;
							$class = get_class($url);
						}

						$reflection = new ReflectionClass($class);
						$priv_attr  = $reflection->getProperties(ReflectionProperty::IS_PRIVATE);

                        $property = 'output';
		                if ($reflection->hasProperty($property)) {
							$reflectionProperty = $reflection->getProperty($property);
							$reflectionProperty->setAccessible(true);
							$data_private = $reflectionProperty->getValue($url);
	                        $cache_output = $data_private;
		                    unset($data_private);
		                    unset($reflectionProperty);
						}

                        /*
                        $cache_output = $this->registry->get('jetcache_response_output');
                        $this->registry->set('jetcache_response_output', false);
                        */

						$property = 'headers';
		                if ($reflection->hasProperty($property)) {
							$reflectionProperty = $reflection->getProperty($property);
							$reflectionProperty->setAccessible(true);
							$data_private = $reflectionProperty->getValue($url);
	                        $cache_headers = $data_private;
		                    unset($data_private);
		                    unset($reflectionProperty);
						}

	                    unset($url);
	                    unset($reflection);
	                    unset($priv_attr);
	                    unset($class);

						//for cache
						if (!$this->config->get('blog_work')) {
							$this->config->set('blog_work', true);
							$off_blog_work = true;
						} else {
							$off_blog_work = false;
						}

	                    if (is_string($cache_output) && $cache_output != '') {
							$this->sc_set_cache_name();

	                        $sc_time_end = microtime(true);
							$cache['time'] = $sc_time_end - $this->registry->get('sc_time_start');

					        if (is_callable(array('DB', 'get_sc_jetcache_query_count'))) {
					        	$cache['queries'] = $this->db->get_sc_jetcache_query_count();
					        } else {
					        	$cache['queries'] = '';
					        }
	                        $cache['headers'] = $cache_headers;
							// $cache['output'] = base64_encode($cache_output);
                            $cache['output'] = $cache_output;

	                        $this->cache->set($this->sc_cache_name, $cache);
						}

						if ($off_blog_work) {
							$this->config->set('blog_work', false);
						}
						$this->registry->set('jetcache_response_set_cache', true);

					}
              	}
			}
    	}
	}


	public function from_cache_output() {

			$this->sc_set_cache_name();

			$this->registry->set('jetcache_output_name', $this->sc_cache_name);

			if (!$this->config->get("blog_work")) {
				$this->config->set("blog_work", true);
				$off_blog_work = true;
			} else {
				$off_blog_work = false;
			}

			$cache_content = $this->cache->get($this->sc_cache_name);

			if ($off_blog_work) {
				$this->config->set("blog_work", false);
			}

			if (isset($cache_content['output']) && $cache_content['output'] != '' && !$this->registry->get('jetcache_response_set_cache')) {

				//$jetcache_content = base64_decode($cache_content['output']);
				$jetcache_content = $cache_content['output'];
			    $jetcache_headers = $cache_content['headers'];
			    $jetcache_time = $cache_content['time'];
			    $jetcache_queries = $cache_content['queries'];

				$this->config->set('ascp_comp_url', true);

				if (SC_VERSION > 15) {
					//$this->load->controller('common/seoblog');
				} else {
					//$this->getChild('common/seoblog');
				}

				if (!empty($jetcache_headers)) {
					foreach ($jetcache_headers as $jc_header) {
			    		$this->response->addHeader($jc_header);
					}
			    }

			 	if (isset($this->request->get['record_id']) || isset($this->request->get['blog_id'])) {
					if (isset($this->request->get['record_id'])) {
						$this->countRecordUpdate();
					}
					if ($this->checkAccess()) {
						$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 200 OK');
					} else {
						$this->response->addHeader($this->request->server['SERVER_PROTOCOL'] . '/1.1 404 Not Found');
					}
				}

			    if (($this->registry->get('sc_isLogged') && isset($this->jetcache_settings['jetcache_info_status']) && $this->jetcache_settings['jetcache_info_status']) || (isset($this->jetcache_settings['jetcache_info_demo_status']) && $this->jetcache_settings['jetcache_info_demo_status']) ) {
				    $time_visual['start'] = $this->registry->get('sc_time_start');
				    $time_visual['end'] = microtime(true);
				    $time_visual['load'] = $jetcache_time;
				    $time_visual['queries'] = $jetcache_queries;

					$this->registry->set('jetcache_output_visual', $time_visual);
				}

			    $this->registry->set('jetcache_output', true);

				$this->response->setOutput($jetcache_content);

				if (SC_VERSION > 21) {
					$this->response->setCompression($this->config->get('config_compression'));
				}

				$this->response->output();

				exit();
			}

	}


	private function countRecordUpdate() {
		$msql = "UPDATE `" . DB_PREFIX . "record` SET `viewed`=`viewed` + 1 WHERE `record_id`='" . (int) ($this->db->escape($this->request->get['record_id'])) . "'";
		$this->db->query($msql);
	}


	private function checkAccess() {
		$check = false;
		if (!$this->config->get('ascp_customer_groups')) {
			agoo_cont('record/customer', $this->registry);
			$data = $this->controller_record_customer->customer_groups($this->seocms_settings);
			$this->config->set('ascp_customer_groups', $data['customer_groups']);
		} else {
			$data['customer_groups'] = $this->config->get('ascp_customer_groups');
		}
		if (isset($this->request->get['record_id']) && $this->request->get['route'] == 'record/record') {

			$this->load->model('record/record');
			$record_info = $this->model_record_record->getRecord($this->request->get['record_id']);
			if ($record_info) {
				$check = true;
			} else {
				$check = false;
			}
		}
		if (isset($this->request->get['blog_id']) && $this->request->get['route'] == 'record/blog') {
			$this->load->model('record/blog');
			$blog_info = $this->model_record_blog->getBlog($this->request->get['blog_id']);
			if ($blog_info) {
				$check = true;
			} else {
				$check = false;
			}
		}
		return $check;
	}

    public function jetcache_cont_access($params) {
		if (isset($this->seocms_settings['jetcache_widget_status']) && $this->seocms_settings['jetcache_widget_status']) {
			if (isset($this->jetcache_settings['store']) && in_array($this->config->get('config_store_id'), $this->jetcache_settings['store'])) {
		       	if (isset($this->jetcache_settings['cont_status']) && $this->jetcache_settings['cont_status']) {
			       if (isset($this->jetcache_settings['add_cont']) && !empty($this->jetcache_settings['add_cont'])) {
				       foreach($this->jetcache_settings['add_cont'] as $add_cont) {
	         				if ($params == $add_cont['cont'] && $add_cont['status']) {
	         					$access_status = true;
	         					$access_status = $this->access_exeptions();
	         					return $access_status;
	         				}
				       }
			       }
				}
			}
		}
		return false;
    }


    public function jetcache_cont_from_cache($cont_route) {

        if (is_string($cont_route)) {

        	$this->sc_set_cache_name('cont', str_replace('/', '_', $cont_route));

            if (!$this->config->get('blog_work')) {
				$this->config->set('blog_work', true);
				$off_blog_work = true;
			} else {
				$off_blog_work = false;
			}

	        $cache_content = $this->cache->get($this->sc_cache_name);

			if ($off_blog_work) {
				$this->config->set('blog_work', false);
			}

			if (isset($cache_content['output']) && $cache_content['output'] != '') {

				return $cache_content['output'];
			}

			return false;
        }
    }

    public function jetcache_cont_to_cache($cache_output, $cont_route ) {

    	if (is_string($cache_output) && is_string($cont_route)) {

	    	$this->sc_set_cache_name('cont', str_replace('/', '_', $cont_route));

			if (!$this->config->get('blog_work')) {
				$this->config->set('blog_work', true);
				$off_blog_work = true;
			} else {
				$off_blog_work = false;
			}
			$cache['output'] = $cache_output;

	        $this->cache->set($this->sc_cache_name, $cache);

			if ($off_blog_work) {
				$this->config->set('blog_work', false);
			}
		}
    }


	private function sc_set_cache_name($type = 'pages', $cont_route = '') {

        if (!$this->registry->get('jetcache_output_name') || $type != 'pages') {

			if (isset($this->session->data)) {
				$session = $this->session->data;
			} else {
				$session = array();
			}

			if (isset($session['token'])) {
				unset($session['token']);
			}
			if (isset($session['captcha'])) {
				unset($session['captcha']);
			}

	        $data_cache['cart'] = $this->cart->getProducts();
	       //$data_cache['cart'] = $this->cart->hasProducts();

			if (isset($this->jetcache_settings['session_log']) && $this->jetcache_settings['session_log']) {
            	$this->log->write('SESSION: ' . json_encode($session).PHP_EOL);
			}

			$data_cache['session'] = $session;
			$data_cache['url'] = $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];
			$data_cache['post'] = $this->request->post;
			$data_cache['get'] = $this->request->get;
	        unset($data_cache['get']['_route_']);

			if (isset($this->jetcache_settings['ex_session']) && $this->jetcache_settings['ex_session'] != '') {

				$ex_session_array = explode (PHP_EOL, trim($this->jetcache_settings['ex_session'], PHP_EOL));

				foreach($data_cache['session'] as $data_session_param => $data_session) {
					$data_session_param = trim($data_session_param);
					foreach($ex_session_array as $ex_session) {
		                $ex_session = trim($ex_session);
		                if ($data_session_param == $ex_session) {
		                	unset($data_cache['session'][$ex_session]);
		                }
					}
	       		}
           	}

			$hash = md5(json_encode($data_cache));

			$route_name = $this->config->get('config_language_id').'_'.$this->config->get('config_store_id');
			if (isset($this->request->get['route'])) {
				$route_name .= '_'.str_replace('/', '_', $this->request->get['route']);
			}

			unset($data_cache);

			if (isset($this->jetcache_settings[$type.'_db_status']) && $this->jetcache_settings[$type.'_db_status']) {
	        	$this->sc_cache_name  = 'blog.db.'.$type.'.'.$hash.'.'. $cont_route. $route_name;
			} else {
	        	$this->sc_cache_name = 'blog.jetcache_'.$type.'_'.$route_name.'.'.$cont_route.'.' . $hash;
			}

		} else {
			$this->sc_cache_name = $this->registry->get('jetcache_output_name');
		}

   		return $this->sc_cache_name;
    }

}
}
