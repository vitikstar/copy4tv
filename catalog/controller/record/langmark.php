<?php
class ControllerRecordLangmark extends Controller {

	protected $data;
	protected $settings;
	protected $protocol;
    private $jetcache_buildcache = false;

	public function __construct($registry) {
        //Старт
		parent::__construct($registry);

		if (!defined('SC_VERSION')) define('SC_VERSION', substr(str_replace('.','',VERSION), 0,2));

		if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
			$this->jetcache_buildcache = false;
			$jetcache_headers = getallheaders();
			if (isset($jetcache_headers['JETCACHE_BUILDCACHE'])) {
				$this->jetcache_buildcache = true;
			}
		}
		if ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) {
			$this->protocol = 'https';
		} else {
			$this->protocol = 'http';
		}

		if ($this->protocol == 'https') {
 			$config_url_0 = HTTPS_SERVER;
		} else {
 			$config_url_0 = HTTP_SERVER;
		}

		$this->load->model('setting/store');

		$this->data['stores'][0] = array(
			'store_id' => 0,
			'name'     => $this->config->get('config_name'),
			'url' => $config_url_0
		);

		$stores = $this->model_setting_store->getStores();

		foreach ($stores as $result) {
			if ($this->protocol == 'https' && isset($result['ssl']) && $result['ssl'] != '') {
				$store_url = $result['ssl'];
			} else {
				$store_url = $result['url'];
			}

			$this->data['stores'][$result['store_id']] = array(
				'store_id' => $result['store_id'],
				'name' => $result['name'],
				'url' => $store_url
			);
		}

	}

	public function index() {
			$this->config->set('blog_work', true);
            $this->load->model('localisation/language');

            $this->settings = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));

			$html = '';
			$language_code = $this->config->get('config_language');
			$language_id = $this->config->get('config_language_id');
			$store_id = $this->config->get('config_store_id');
            $this->data['config_store_id'] = $store_id;

            $this->data['languages'] = array();
            $array_hreflang = array();

			if (SC_VERSION < 20) {
				$this->load->language('module/language');
			} else {
				$this->load->language('common/language');
			}

			$this->data['text_language'] = $this->language->get('text_language');
			$this->data['action'] = '';
			$this->data['code'] = $this->data['language_code'] = $this->session->data['language'];

		    if (isset($this->request->get['route'])) {
			  	$route = $this->request->get['route'];
		    } else {
		        $route = 'common/home';
		    }

            $langmark_multi = $this->registry->get('langmark_multi');

            foreach ($this->data['stores'] as $store_num => $store) {

				$settings = $this->config->get('asc_langmark_' . $store['store_id']);


				if (isset($settings['use_link_status']) && !$settings['use_link_status']) {

					$url_current = $this->url->link($route, $this->getQueryString(array(
									'route',
									'_route_',
									'site_language',
									'tag'
									)));

					$prefix_current = $langmark_multi['prefix'];

	                $pos_current = stripos($url_current,  $prefix_current);
	                $pos_current_len = $pos_current + strlen($prefix_current);
	                $substr_pos_current_len = substr($url_current, $pos_current_len, 1);
	                $substr_prefix_current = substr($prefix_current, -1);

				}

				$languages = $this->model_localisation_language->getLanguages();

                foreach ($languages as $result) {
                	$languages_by_id[$result['language_id']] = $result;
                }

				if (!empty($settings['multi'])) {
					foreach ($settings['multi'] as $name => $settings_multi) {

	                	if (isset($languages_by_id[$settings_multi['language_id']]) && $languages_by_id[$settings_multi['language_id']]['status']) {

							if (isset($settings_multi['prefix_switcher_stores']) && $settings_multi['prefix_switcher_stores'] || $store['store_id'] == $store_id) {

								if ((isset($settings_multi['hreflang_switcher']) && $settings_multi['hreflang_switcher']) || (isset($settings_multi['prefix_switcher']) && $settings_multi['prefix_switcher'])) {

							    	if (!isset($settings['use_link_status']) || (isset($settings['use_link_status']) && $settings['use_link_status'])) {
	                                    if (isset($settings_multi['name'])) {
			                            	$settings_multi['store_id'] = $store['store_id'];
			                            	$this->registry->set('langmark_multi', $settings_multi);
			                            }
							        	$this->switchLanguage($settings, $settings_multi['language_id'], $languages_by_id[$settings_multi['language_id']]['code']);

										$url_lang = $this->url->link($route, $this->getQueryString(array(
										'route',
										'_route_',
										'site_language',
										'tag'
										)));

									} else {

										$prefix_replace = $settings_multi['prefix'];

				                        $substr_prefix_replace = substr($prefix_replace, -1);

										if ($substr_prefix_current != '/' && $substr_prefix_replace == '/' && $substr_pos_current_len == '/') {
											$prefix_replace = substr($prefix_replace, 0, -1);
										}

										if ($substr_prefix_current == '/' && $substr_prefix_replace != '/' && $substr_pos_current_len != '/' && $substr_pos_current_len) {
											$prefix_replace = $prefix_replace. '/';
										}

				                    	$url_lang = str_ireplace($prefix_current,  $prefix_replace, $url_current);
									}

								}

								//if (isset($settings_multi['hreflang_switcher']) && $settings_multi['hreflang_switcher'] && $store['store_id'] == $store_id) {
                                if (isset($settings_multi['hreflang_switcher']) && $settings_multi['hreflang_switcher']) {
							        if (isset($settings_multi['hreflang']) && $settings_multi['hreflang'] != '') {
							            $hreflang = $settings_multi['hreflang'];
							        } else {
							        	$hreflang = $languages_by_id[$settings_multi['language_id']]['code'];
							        }
									$array_hreflang[$languages_by_id[$settings_multi['language_id']]['code']] = Array('href' => $url_lang , 'hreflang' => $hreflang );
				                }

								if (isset($settings_multi['prefix_switcher']) && $settings_multi['prefix_switcher']) {


	                                 if ($settings_multi['prefix'] == $langmark_multi['prefix'] && $langmark_multi['store_id'] == $store_id) {
	                                 	$current = true;
	                                 } else {
	                                 	$current = false;
	                                 }

									 if (!isset($languages_by_id[$settings_multi['language_id']]['image'])) {
									 	$result['image'] = 'catalog/language/'. $languages_by_id[$settings_multi['language_id']]['code'] .'/'.$languages_by_id[$settings_multi['language_id']]['code'].'.png';
									 }

									 $this->data['languages'][$url_lang] = array(
										'url'  => $url_lang,
										'name'  => $settings_multi['name'],
										'code'  => $languages_by_id[$settings_multi['language_id']]['code'],
										'image' => $languages_by_id[$settings_multi['language_id']]['image'],
										'store_id' => $store['store_id'],
										'store_name' => $store['name'],
										'current' =>  $current
									 );
								}
							}
	                	}
					}
                }

	            $this->registry->set('langmark_multi', $langmark_multi);
				$this->switchLanguage($this->settings, $language_id, $language_code);

			    if (method_exists($this->document, 'setSCHreflang')  && isset($this->settings['hreflang_status']) && $this->settings['hreflang_status']) {
					if (!empty($array_hreflang)) {
						$this->document->setSCHreflang($array_hreflang);
					}
				}

            }

            $this->registry->set('langmark_multi', $langmark_multi);
			$template = 'langmark.tpl';
			$template_info  = pathinfo($template);
			$template = $template_info['filename'];
			$this_template = $this->seocmslib->template('agootemplates/record/' . $template);

			$this->data['language'] = $this->language;
			$this->data['theme'] = $this->seocmslib->theme_folder;

			$this->config->set('blog_work', false);

			$this->template = $this_template;

			if (SC_VERSION < 20) {
				$html = $this->render();
			} else {
				$html = $this->load->view($this->template, $this->data);
			}

            return $html;

	}

	private function getQueryString($exclude = array())	{
		if (!is_array($exclude)) {
			$exclude = array();
		}
		return urldecode(http_build_query(array_diff_key($this->request->get, array_flip($exclude))));
	}

	public function switchLanguage($settings, $language_id, $code) {
 			$ajax = false;

			if (isset($this->request->server['HTTP_ACCEPT'])) {

				if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('image')) !== false) {

	             	if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('html')) !== false) {
	                    $ajax = false;
					} else {
						$ajax = true;
					}
	            }

				if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('js')) !== false) {
	            	$ajax = true;
				}

				if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('json')) !== false) {
		            $ajax = true;
				}

				if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('ajax')) !== false) {
	    	        $ajax = true;
				}

				if (strpos(strtolower($this->request->server['HTTP_ACCEPT']),strtolower('javascript')) !== false) {
	        	    $ajax = true;
				}

			}

	        if (isset($settings['ex_multilang_route']) && $settings['ex_multilang_route']!='') {
		        $ex_multilang_route = $settings['ex_multilang_route'];
		        $ex_multilang_route_array = explode(PHP_EOL, $ex_multilang_route);
				if (isset($this->request->get['route'])) {
					foreach ($ex_multilang_route_array as $ex_route) {
						if (utf8_strpos(utf8_strtolower($this->request->get['route']),trim($ex_route)) !== false) {
		            		$ajax = true;
						}
					}
				}
	        }

	        if (isset($settings['ex_multilang_uri']) && $settings['ex_multilang_uri']!='') {
		        $ex_multilang_uri = $settings['ex_multilang_uri'];
		        $ex_multilang_uri_array = explode(PHP_EOL, $ex_multilang_uri);
				if (isset($this->request->server['REQUEST_URI'])) {
					foreach ($ex_multilang_uri_array as $ex_uri) {
						if (utf8_strpos(utf8_strtolower($this->request->server['REQUEST_URI']), trim($ex_uri)) !== false) {
			            	$ajax = true;
						}
					}
				}
			}

			if (isset($this->request->server['HTTP_X_REQUESTED_WITH']) && strtolower($this->request->server['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
				if (!$this->jetcache_buildcache) {
					$ajax = true;
				}
			}

			if ($code != '' && !$ajax) {

				$this->config->set('config_language_id', $language_id);
				$this->config->set('config_language', $code);
				$this->session->data['language'] = $code;

                if (isset($this->settings['jazz']) && $this->settings['jazz']) {
					setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $this->request->server['HTTP_HOST']);
				}

			}
	}
	public function set_agoo_hreflang($output) {
		if (isset($output) && !$this->registry->get('admin_work')) {
			if (is_string($output) && strpos($output, '</head>') !== false && (strpos($output, '<link rel="alternate"') === false || strpos($output, "<link rel='alternate'") === false) && is_callable(array($this->document, 'getSCHreflang'))) {
				$sc_hreflang = $this->document->getSCHreflang();

				if ($sc_hreflang && !empty($sc_hreflang)) {
					foreach($sc_hreflang as $sc_hreflang_code => $sc_hreflang_array) {

							$output = str_replace("</head>", '
<link rel="alternate" hreflang="' . $sc_hreflang_array['hreflang'] . '" href="' . $sc_hreflang_array['href'] . '" />
</head>', $output);
					}
                    $this->document->setSCHreflang(array());
				}
			}
		}
		return $output;
	}

	public function shortcodes($output) {
		$this->settings = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));
        if (!isset($this->settings['access']) || !$this->settings['access']) {
			return $output;
		}

        $langmark_multi = $this->registry->get('langmark_multi');

		if (isset($output) && is_string($output) && !$this->registry->get('admin_work')) {

			if (!empty($langmark_multi['shortcodes'])) {
				foreach($langmark_multi['shortcodes'] as $num => $shortcode) {
					if ($shortcode['out'] == ' ') $shortcode['out'] = '';
					$output = str_replace(html_entity_decode($shortcode['in'], ENT_QUOTES, 'UTF-8'), html_entity_decode($shortcode['out'], ENT_QUOTES, 'UTF-8'), $output);
				}
			}
		}
		return $output;
	}


	public function after($seo_url) {
    	if (!is_string($seo_url)) {
        	return $seo_url;
        }
		$this->settings = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));
        if (!isset($this->settings['access']) || !$this->settings['access']) {
			return $seo_url;
		}

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		} else {
			$route = 'common/home';
		}

        if (isset($this->settings['ex_url_route']) && $this->settings['ex_url_route']!='') {
	        $ex_url_route = $this->settings['ex_url_route'];
	        $ex_url_route_array = explode(PHP_EOL, $ex_url_route);

			if ($route != '') {
				foreach ($ex_url_route_array as $ex_route) {
                    if (trim($ex_route) != '') {
						if (utf8_strpos(utf8_strtolower($route),trim($ex_route)) !== false) {
		            		$ajax = true;
		            		return $seo_url;
						}
					}
				}
			}
		}

        if (isset($this->settings['ex_url_uri']) && $this->settings['ex_url_uri']!='') {
	        $ex_url_uri = $this->settings['ex_url_uri'];
	        $ex_url_uri_array = explode(PHP_EOL, $ex_url_uri);

			foreach ($ex_url_uri_array as $ex_uri) {
				if (trim($ex_uri) != '') {
					if (utf8_strpos(utf8_strtolower($seo_url), trim($ex_uri)) !== false) {
			        	$ajax = true;
			        	return $seo_url;
					}
				}
			}

        }

        $seo_url = $this->commonhome($seo_url);
        $seo_url = $this->seourl($seo_url);
        $seo_url = $this->pagination($seo_url);
        $seo_url = $this->twoslaches($seo_url);

        return $seo_url;
	}

	private function pagination($seo_url) {

        $langmark_multi = $this->registry->get('langmark_multi');
        $langmark_settings = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));
		if (isset($langmark_settings['pagination']) && $langmark_settings['pagination']) {

					if ($this->registry->get('langmark_page') != '') {

			   				$title = $this->document->getTitle();
			   				$description = $this->document->getDescription();
			                if (utf8_strpos($title, ' '. $this->registry->get('langmark_page')) === false) {
								$this->document->setTitle($title .  ' ' . $langmark_multi['pagination_title'] . ' ' . $this->registry->get('langmark_page'));
								$this->document->setDescription($description .  ' ' . $langmark_multi['pagination_title'] . ' ' . $this->registry->get('langmark_page'));
							}
					}
                    $modules_params_1 = $seo_url;
			        if (isset($modules_params_1) && is_string($modules_params_1) && strpos($modules_params_1, 'page=') !== false) {


					       	$component = parse_url(str_replace('&amp;', '&', $seo_url));

			                if (isset($component['path'])) {
			                	$component['path'] = str_replace('/index.php', '', $component['path']);
			                } else {
			                	$component['path'] = '';
			                }
							if (substr($component['path'], -1) == '/') {
								$slash_close = '/';
							} else {
								$slash_close = '';
							}
                            $this->registry->set('langmark_slash_close', $slash_close);
							$data_array = array();
							if (isset($component['query'])) {
							   parse_str($component['query'], $data_array);
							}

					        if (count($data_array)) {
								 /*** added code seo paging http://opencartadmin.com ***/
					  			$seo_url ='';
					  			$paging = '';
					            $devider = '/'; // :)
					            foreach ($data_array as $key => $value) {

											if ($key == $langmark_settings['pagination_prefix'] || $key == 'page') {
												$key = $langmark_settings['pagination_prefix'];
												if ($devider != '/') {
													$paging = '/' . $key . "-" . $value;
												} else  {
													$paging = $key . "-" . $value;
												}

												unset($data_array[$key]);
												if (isset($data_array['page'])) {
													unset($data_array['page']);
												}
											}
								}
                                // WTF?

                                if (isset($modules_params_1) && is_string($modules_params_1) && strpos($modules_params_1, 'page={page}') !== false) {
	                              //  $paging = '';
                                }

					            if (trim($paging, '/') == $langmark_settings['pagination_prefix'] . '-1') {
					            	$paging = '';
					            }

								if (count($data_array)) {
									$seo_url .= $paging . '?' . urldecode(http_build_query($data_array, '', '&amp;'));
								} else {
								  	$seo_url .= $paging;
								}

			                    if (trim($component['path']) == '') $mydel = ""; else $mydel = '/';
					             $seo_url = $component['scheme'].'://'.$component['host'].'/' .trim($component['path'], '/') . $mydel . $seo_url;

								if ($paging != '') {
					            	$seo_url = rtrim($seo_url,'/');
					            }

							}

			        }
			         /* for pagination */
        }
		return $seo_url;
	}

	private function seourl($seo_url) {

		if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
		} else {
			$route = 'common/home';
		}

       	$seo_url_parse = parse_url(str_replace('&amp;', '&', $seo_url));

        // From /system/library/agoo/multilang.php or from switch index()
        $langmark_multi = $this->registry->get('langmark_multi');
        if (!is_array($langmark_multi)) {
        	return $seo_url;
        }

		if ($seo_url_parse['scheme'] == 'https') {
			$conf_ssl = $this->config->get('config_ssl');
			if (!$conf_ssl) $conf_ssl = HTTPS_SERVER;
			$config_url = $conf_ssl;
		} else {
			$conf_url = $this->config->get('config_url');
			if (!$conf_url) $conf_url = HTTP_SERVER;
			$config_url = $conf_url;
		}

        $protocol = $seo_url_parse['scheme'] . '://';

		$seo_url_len = str_replace($config_url, '', $seo_url);

		if (isset($langmark_multi['prefix']) && strlen($seo_url_len) > 0 && substr($langmark_multi['prefix'], -1) != '/' ) {
			$divider = '/';
		} else {
			$divider = '';
		}

        // Error fo generate URL other stores
        if ($seo_url == $config_url && $langmark_multi['main_prefix_status']) {
        	if (isset($langmark_multi['store_id']) && $langmark_multi['store_id'] != $this->config->get('config_store_id') && isset($this->data['stores'][$langmark_multi['store_id']]['url'])) {
            	$seo_url = $this->data['stores'][$langmark_multi['store_id']]['url'];
        	} else {
        		$seo_url = $config_url;
        	}
        } else {
        	if (isset($langmark_multi['prefix'])) {
        		$seo_url = str_replace($config_url, $protocol . $langmark_multi['prefix'] . $divider, $seo_url);
        	} else {
        		$this->log->write($seo_url . ' -> ' . json_encode($langmark_multi));
        	}
        }

		return $seo_url;
	}

	private function twoslaches($seo_url) {
        if (isset($this->settings['two_status']) && $this->settings['two_status']) {
			$seo_url = preg_replace('/(?<!^[http:]|[https:])[\/]{2,}/', '/', trim($seo_url));
		}
		return $seo_url;
	}

	private function commonhome($seo_url) {

       	if (isset($this->settings['commonhome_status']) && $this->settings['commonhome_status']) {

			if (strpos(strtolower($seo_url), 'index.php?route=common/home') !== false) {
                /*
			    if(substr($seo_url, -1) == '/' || $seo_url == '') {
			    	$home_divider = '';
			    } else {
			    	$home_divider = '/';
			    }
                */
				if ((utf8_strpos(utf8_strtolower($seo_url), '&') !== false) || (utf8_strpos(utf8_strtolower($seo_url), '&amp;') !== false)) {
		           	$seo_url = str_replace('&amp;', '&', $seo_url);
		           	$seo_url = str_replace('&', '&amp;', $seo_url);
		           	$seo_url = str_replace('index.php?route=common/home&amp;', '?', $seo_url);
				 } else {
					$seo_url = str_replace('index.php?route=common/home', '', $seo_url);
				 }

			}
       	}
		return $seo_url;
	}

}
