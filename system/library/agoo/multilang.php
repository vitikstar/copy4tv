<?php
class agooMultilang extends Controller
{
	private $langcode_all;
	private $languages_all;
	private $domen = '';
	private $jetcache_buildcache = false;
    private $langmark_settings;
    private $langmark_store_id_settings;
    private $set_multi = false;

	public function __construct($registry) {
        //Старт
		parent::__construct($registry);

		if (!defined('VERSION')) return; if (!defined('SC_VERSION')) define('SC_VERSION', substr(str_replace('.', '', VERSION), 0, 2));

		$this->langmark_settings = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));

		if (!isset($this->langmark_settings['access']) || !$this->langmark_settings['access']) {
			return;
		}

        $flag_pagination = false;
		$ajax = false;

		$languages = array();
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language WHERE status = '1'");

		foreach ($query->rows as $result) {
			$languages[$result['code']] = $result;
			$this->langcode_all[$result['code']]         = $result;
			$this->languages_all[$result['language_id']] = $result;
		}



		if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
			$conf_ssl = $this->config->get('config_ssl');
			if (!$conf_ssl) $conf_ssl = HTTPS_SERVER;
			$config_url = $conf_ssl;
		} else {
			$conf_url = $this->config->get('config_url');
			if (!$conf_url) $conf_url = HTTP_SERVER;
			$config_url = $conf_url;
		}

        $this->domen = $config_url;

        $uri = $this->request->server['REQUEST_URI'];

        $full_url = rtrim($this->domen, '/') . $uri;
        $full_url_data = parse_url(str_replace('&amp;', '&', $full_url));

		if (isset($this->request->get['_route_'])) {
			$route = urldecode($this->request->get['_route_']);
		} else {
			$route = '';
		}

        $__route__ = $route;

        $full_url_route = rtrim($this->domen, '/') . '/' . $route;
        $url_data = parse_url(str_replace('&amp;', '&', $uri));

        if (isset($url_data['path'])) {
        	$url_data['path'] = trim($url_data['path'], '/');
        } else {
        	$url_data['path'] = '';
        }

        $path_info = pathinfo($url_data['path']);

        if (isset($path_info['extension'])) {
        	$url_data['ext'] = $path_info['extension'];
        } else {
        	$url_data['ext'] = '';
        }

		if ($url_data['ext'] == 'js' || $url_data['ext'] == 'css' || $url_data['ext'] == 'jpg' || $url_data['ext'] == 'jpeg' || $url_data['ext'] == 'png' || $url_data['ext'] == 'webp' || $url_data['ext'] == 'ico') {
           	$ajax = true;
		}

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->jetcache_buildcache = false;
			$jetcache_headers = getallheaders();
			if (isset($jetcache_headers['JETCACHE_BUILDCACHE'])) {
				$this->jetcache_buildcache = true;
			}
		}

		if (isset($this->request->server['HTTP_ACCEPT'])) {

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']), strtolower('image')) !== false) {
             	if (strpos(strtolower($this->request->server['HTTP_ACCEPT']), strtolower('html')) !== false) {
                    $ajax = false;
				} else {
					$ajax = true;
				}
            }

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']), strtolower('js')) !== false) {
            	$ajax = true;
			}

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']), strtolower('json')) !== false) {
	            $ajax = true;
			}

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']), strtolower('ajax')) !== false) {
    	        $ajax = true;
			}

			if (strpos(strtolower($this->request->server['HTTP_ACCEPT']), strtolower('javascript')) !== false) {
        	    $ajax = true;
			}

		}


		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			if (!$this->jetcache_buildcache) {
				$ajax = true;
			}
		}

        $parts = explode('/', trim(utf8_strtolower($route), '/'));
		$parts_first = $parts[0];

		$parts_first_array = explode('?', $parts_first);
		$parts_first =  $parts_first_array[0];

        $full_domen_data = parse_url(str_replace('&amp;', '&', $this->domen));
        $domen_prefix = $full_domen_data['scheme'].'://'. $full_domen_data['host'] . '/';

        $switch_ex = $this->ex($this->langmark_settings);

        //if (!$ajax) {
        	$max_len = 0;
        	if (!$switch_ex && is_array($this->langmark_settings['multi']) && !empty($this->langmark_settings['multi'])) {
        		foreach ($this->langmark_settings['multi'] as $multi_name => $multi) {
        			if (isset($this->languages_all[$multi['language_id']]['code'])) {
	        			$lang_code = $this->languages_all[$multi['language_id']]['code'];

	                    $prefix_url = $multi['prefix'];

						if (isset($multi['main_prefix_status']) && $multi['main_prefix_status'] && (trim($route) == '' || utf8_strpos(utf8_strtolower($route),'index.php?route=common/home') !== false)) {
							$prefix_url= $domen_prefix;
						} else {
							$prefix_url = $full_url_data['scheme'].'://'.$multi['prefix'];
						}

	                	$prefix_len_source = substr($full_url_route, 0, strlen($prefix_url));

	                    $this_prefix = trim(str_ireplace($this->domen, '', $prefix_url), '/');
	                    $route_prefix_array = explode('/', $route);

	                    $route_prefix = trim($route_prefix_array[0], '/');

	                    if (($this_prefix == $route_prefix) || ($this_prefix == '' && $route_prefix != '')) {
							if ($prefix_len_source == $prefix_url && strlen($prefix_url) > $max_len) {
								$max_len = strlen($prefix_url);

								$__route__ = str_ireplace($prefix_url, '', $full_url_route);

								if (!$ajax) {
									$switch_flag = true;
									$this->config->set('config_store_id', $multi['store_id']);
									$this->langmark_store_id_settings = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));

									$multi = $this->langmark_store_id_settings['multi'][$multi_name];
                                    $this->set_multi = true;
									$this->registry->set('langmark_multi', $multi);
									$this->session->data['langmark_multi'] = array();
									$this->session->data['langmark_multi']['name'] = $multi['name'];
	                                $this->session->data['langmark_multi']['store_id'] = $multi['store_id'];

								} else {
									if (isset($this->session->data['langmark_multi']['name']) && $this->session->data['langmark_multi']['name'] != '') {
	                                	$this->langmark_store_id_settings = $this->config->get('asc_langmark_' . $this->session->data['langmark_multi']['store_id'] );
										if ($this->langmark_store_id_settings) {
											$this->set_multi = true;
											$this->registry->set('langmark_multi', $this->langmark_store_id_settings['multi'][$this->session->data['langmark_multi']['name']]);
										}
									} else {
										$this->config->set('config_store_id', $multi['store_id']);
										$this->langmark_store_id_settings = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));
										$multi = $this->langmark_store_id_settings['multi'][$multi_name];
                                        $this->set_multi = true;
										$this->registry->set('langmark_multi', $multi);
										$this->session->data['langmark_multi'] = array();
										$this->session->data['langmark_multi']['name'] = $multi['name'];
	                                	$this->session->data['langmark_multi']['store_id'] = $multi['store_id'];
									}
								}
							}
						}
					}
				}
        	}

            if (!$this->set_multi) {
				foreach ($this->langmark_settings['multi'] as $multi_name => $multi) {
					if (isset($multi['main_prefix_status']) && $multi['main_prefix_status'] && (trim($route) != '' || utf8_strpos(utf8_strtolower($route),'index.php?route=common/home') === false)) {
						$this->set_multi = true;
						$this->registry->set('langmark_multi', $multi);
					}
				}
            }

	        if (isset($__route__ ) && $__route__ != '') {
	        	if (isset($this->request->get['route'])) {
	        		//unset($this->request->get['_route_']);
	        		//unset($_GET['_route_']);
	        	} else {
	        		$this->request->get['_route_'] = $_GET['_route_'] = $__route__;
	        	}
	        } else {
	        	unset($this->request->get['_route_']);
	        	unset($_GET['_route_']);
	        }

            if ($this->registry->get('langmark_multi')) {
	        	$multi_switch = $this->registry->get('langmark_multi');
	        	$multi_switch_settings = $this->config->get('asc_langmark_' . $multi_switch['store_id']);
	        } else {
	        	$multi_switch_settings = $this->langmark_settings;
	        }

	        if (!$ajax) {

                if (!$switch_ex) {
			        if (isset($switch_flag) && $switch_flag) {
						if (isset($this->languages_all[$multi_switch['language_id']]['language_id'])) {
							$this->switchLanguage($this->languages_all[$multi_switch['language_id']]['language_id'], $this->languages_all[$multi_switch['language_id']]['code']);
						}
			        }

		            //unset($this->session->data['currency_old']);
		            if (!isset($this->session->data['currency_old'])) {
		            	$this->session->data['currency_old'] = Array();
		            }

		            if (isset($this->session->data['currency']) && isset($this->session->data['language']) && isset($this->session->data['currency_old'][$this->session->data['language']]['currency']) && $this->session->data['currency'] !== $this->session->data['currency_old'][$this->session->data['language']]['currency']) {

		            } else {
						if (isset($multi_switch['currency']) && $multi_switch['currency'] != '') {

							$this->session->data['currency'] = $multi_switch['currency'];

							if (SC_VERSION > 21) {
								unset($this->session->data['shipping_method']);
								unset($this->session->data['shipping_methods']);
							} else {
								$this->currency->set($multi_switch['currency']);
							}

			            	if (isset($this->langmark_settings['currency_switch']) && $this->langmark_settings['currency_switch']) {
			            		unset($this->session->data['currency_old']);
			            	}

							$this->session->data['currency_old'][$this->session->data['language']]['switch'] = true;
							$this->session->data['currency_old'][$this->session->data['language']]['currency'] = $this->session->data['currency'];
							$this->session->data['currency_old'][$this->session->data['language']]['language'] = $this->session->data['language'];

				 		}
		            }
                }
	        }

        	$parts_route = explode('/', trim($__route__, '/'));

		if (isset($multi_switch_settings['pagination']) && $multi_switch_settings['pagination']) {
		        /* for seo pagination */

				$parts_end = end($parts_route);

				if ($multi_switch_settings['pagination_prefix'] != '' && (strpos($parts_end, $multi_switch_settings['pagination_prefix'].'-') !== false || strpos($parts_end, 'page-') !== false)) {
						list($key, $value) = explode('-', $parts_end);
                        $value = (int)$value;
						if ($value > 1) {
							$this->request->get['page'] = $value;
						}

			   			$title = $this->document->getTitle();
			   			$description = $this->document->getDescription();

                        $this->registry->set('langmark_page', $value);

                        if (isset($multi_switch['pagination_title'])) {
							$this->document->setTitle($title .  ' '. $multi_switch['pagination_title'] . ' ' . $this->registry->get('langmark_page'));
							$this->document->setDescription($description . ' ' . $multi_switch['pagination_title'] . ' ' . $this->registry->get('langmark_page'));
						}

						unset($parts_route[count($parts_route) - 1]);

                        $flag_pagination = true;

		        		reset($parts_route);

		       	}
		        /* for seo pagination */
        }

		if (isset($this->request->get['_route_'])) {
	        if (!$flag_pagination) {
	        	$this->request->get['_route_'] = $_GET['_route_'] = $__route__;
	        } else {
	        	$this->request->get['_route_'] = $_GET['_route_'] = implode('/', $parts_route);
	        }
		}

		if (isset($this->request->get['route']) || empty($parts_route)) {
			if (!isset($multi_switch_settings['jazz']) || !$multi_switch_settings['jazz'] || $flag_pagination) {
				unset($this->request->get['_route_']);
			}
		}

        if (isset($this->request->get['_route_'])) {
        	$this->request->get['_route_'] = ltrim($this->request->get['_route_'], '/');
        }

        return;
		/************************************************************************************************/
	}

    public function index() {
    	return true;
    }

    public function ex($langmark_settings) {
        $ajax = false;

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		} else {
			$route = 'common/home';
		}
        // Only for without seo url
        if (isset($this->langmark_settings['ex_multilang_route']) && $this->langmark_settings['ex_multilang_route']!='') {
	        $ex_multilang_route = $this->langmark_settings['ex_multilang_route'];
	        $ex_multilang_route_array = explode(PHP_EOL, $ex_multilang_route);
			if (isset($this->request->get['route'])) {
				foreach ($ex_multilang_route_array as $ex_route) {
					if (trim($ex_route) != '') {
						if (utf8_strpos(utf8_strtolower($this->request->get['route']),trim($ex_route)) !== false) {
							if (isset($this->session->data['langmark_multi']['name']) && $this->session->data['langmark_multi']['name'] != '') {
                            	$this->langmark_store_id_settings = $this->config->get('asc_langmark_' . $this->session->data['langmark_multi']['store_id'] );
								if ($this->langmark_store_id_settings) {
									$this->set_multi = true;
									$this->registry->set('langmark_multi', $this->langmark_store_id_settings['multi'][$this->session->data['langmark_multi']['name']]);
								}
                            }
		            		$ajax = true;
						}
					}
				}
			}
        }

        if (isset($this->langmark_settings['ex_multilang_uri']) && $this->langmark_settings['ex_multilang_uri']!='') {
	        $ex_multilang_uri = $this->langmark_settings['ex_multilang_uri'];
	        $ex_multilang_uri_array = explode(PHP_EOL, $ex_multilang_uri);
			if (isset($this->request->server['REQUEST_URI'])) {
				foreach ($ex_multilang_uri_array as $ex_uri) {
					if (trim($ex_uri) != '') {
						if (utf8_strpos(utf8_strtolower($this->request->server['REQUEST_URI']), trim($ex_uri)) !== false) {
							if (isset($this->session->data['langmark_multi']['name']) && $this->session->data['langmark_multi']['name'] != '') {
                            	$this->langmark_store_id_settings = $this->config->get('asc_langmark_' . $this->session->data['langmark_multi']['store_id'] );
                            	if ($this->langmark_store_id_settings) {
									$this->set_multi = true;
									$this->registry->set('langmark_multi', $this->langmark_store_id_settings['multi'][$this->session->data['langmark_multi']['name']]);
								}
                            }
			            	$ajax = true;
						}
					}
				}
			}
		}
		return $ajax;

    }

	private function switchLanguage($language_id, $language_code)	{
		if ($language_code != '') {

				$language_code_old = $this->session->data['language'];
				$this->session->data['language'] = $language_code;
				setcookie('language', $language_code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
				$this->config->set('config_language_id', $language_id);
				$this->config->set('config_language', $language_code);

				if (SC_VERSION > 21) {
                    $language_construct = $language_code;
				} else {
					$language_construct = $this->langcode_all[$language_code]['directory'];
				}
				$language = new Language($language_construct);

				if (SC_VERSION > 15) {
					if (SC_VERSION > 21) {
						$language->load($language_code);
					} else {
						$language->load('default');
						$language->load($language_construct);
					}

				} else {
					$language->load($this->langcode_all[$language_code]['filename']);
				}
				$this->registry->set('language', $language);

				$langdata = $this->config->get('config_langdata');
				if (isset($langdata[$language_id])) {
				  foreach ($langdata[$language_id] as $key => $value) {
				    $this->config->set('config_' . $key, $value);
				  }
				}


		}
	}

	private function session_clear() {
		$data = $_SESSION;
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				if ($key != 'user_id' || $key != 'token') {
					unset($_SESSION[$key]);
				}
			}
		}
	}

}
