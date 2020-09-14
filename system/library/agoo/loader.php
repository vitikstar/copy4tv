<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
if (!class_exists('agooLoader', false)) {
class agooLoader extends Controller
{
	protected $Loader;
	protected $seocms_settings;

	public function __call($name, array $params) {
		$this->registry->set('loader_work', true);

        $this->seocms_settings = $this->registry->get('config')->get('ascp_settings');

		if (
			isset($this->seocms_settings['latest_widget_status']) && $this->seocms_settings['latest_widget_status'] &&
			$this->registry->get('seocms_url_alter') &&
			!class_exists('ControllerCommonSeoBlog', false) &&
			(class_exists('ControllerCommonSeoUrl', false) ||
			 class_exists('ControllerCommonSeoPro', false) ||
			 class_exists('ControllerStartupSeoUrl', false) ||
			 class_exists('ControllerStartupMultimerchSeoUrl', false) ||
			 class_exists('ControllerStartupSeoPro', false))
			 && !$this->registry->get('admin_work')
             && !$this->config->get('sc_ar_'.strtolower('ControllerCommonSeoBlog'))
			 ) {
			agoo_cont('record/addrewrite', $this->registry);
			$this->controller_record_addrewrite->add_construct($this->registry);
		}

        unset($this->seocms_settings);

		$flag    = false;
		$modules = NULL;

		if ($name == 'library') {

  			$name_agoo = str_replace('agoo/', '', $params[0]);

            $file = DIR_SYSTEM . str_replace('../', '', 'library/agoo/' . $name_agoo . '.php');

            $original_file = $file;

            if (function_exists('modification')) {
        		$file = modification($file);
        	}
			if (class_exists('VQMod',false)) {
				if (VQMod::$directorySeparator) {
					if (strpos($file,'vq2-')!==FALSE) {
					} else {
						if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
							//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
							//exit;
						}
						if ($original_file != $file) {
							$file = VQMod::modCheck($file, $original_file);
						} else {
							$file = VQMod::modCheck($original_file);
						}
					}
				}
			}

			if (file_exists($file)) {

				if (SC_VERSION < 20) {
                	include_once($file);
				} else {
					require_once($file);
				}

				if (SC_VERSION > 21) {
					$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$name_agoo);
					$class = str_replace('/', '', $params[0]);
					$this->registry->set(basename($route), new $class($this->registry));
				}

				$flag = true;

			} else {

				if (!is_callable(array('Loader', 'library'))) {
					$file = DIR_SYSTEM . str_replace('../', '', 'library/' . $params[0] . '.php');
		            $original_file = $file;
		            if (function_exists('modification')) {
		        		$file = modification($file);
		        	}
					if (class_exists('VQMod',false)) {
						if (VQMod::$directorySeparator) {
							if (strpos($file,'vq2-')!==FALSE) {
							} else {
								if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
									//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
									//exit;
								}
								if ($original_file != $file) {
									$file = VQMod::modCheck($file, $original_file);
								} else {
									$file = VQMod::modCheck($original_file);
								}
							}
						}
					}

					if (file_exists($file)) {

						if (SC_VERSION < 20) {
		                	include_once($file);
						} else {
							require_once($file);
						}

						if (SC_VERSION > 21) {
							$route = preg_replace('/[^a-zA-Z0-9_\/]/', '', (string)$params[0]);
							$class = str_replace('/', '\\', $route);
							$this->registry->set(basename($route), new $class($this->registry));
						}

  						$flag = true;
					}
				}
			}
		}
		if ($name == 'helper') {
			$file = DIR_SYSTEM . str_replace('../', '', 'helper/agoo/' . $params[0] . '.php');
			$original_file = $file;
            if (function_exists('modification')) {
        		$file = modification($file);
        	}
			if (class_exists('VQMod',false)) {
				if (VQMod::$directorySeparator) {
					if (strpos($file,'vq2-')!==FALSE) {
					} else {
						if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
							//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
							//exit;
						}
						if ($original_file != $file) {
							$file = VQMod::modCheck($file, $original_file);
						} else {
							$file = VQMod::modCheck($original_file);
						}
					}
				}
			}
			if (file_exists($file)) {
				$params[0] = 'agoo/' . $params[0];
				$flag      = true;
			}
		}
		if ($name == 'model') {
			$file = DIR_APPLICATION . str_replace('../', '', 'model/agoo/' . $params[0] . '.php');
			$original_file = $file;

            if (function_exists('modification')) {
        		$file = modification($file);
        	}

			if (class_exists('VQMod',false)) {
				if (VQMod::$directorySeparator) {
					if (strpos($file,'vq2-')!==FALSE) {
					} else {
						if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
							//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
							//exit;
						}
						if ($original_file != $file) {
							$file = VQMod::modCheck($file, $original_file);
						} else {
							$file = VQMod::modCheck($original_file);
						}
					}
				}
			}

			if (file_exists($file) || isset($params[1])) {
				$flag = true;
				if (isset($params[1])) {
					if (isset($params[2])) {
						$this->agoomodel($params[0], $params[1], $params[2]);
					} else {
						$this->agoomodel($params[0], $params[1]);
					}
				} else {
					$this->agoomodel($params[0]);
				}
			}
		}
		if (SC_VERSION > 15) {
			if ($name == 'controller' && !$this->registry->get('admin_work')) {

				$asc_replacecontroller = $this->registry->get('asc_replacecontroller');
				if (!empty($asc_replacecontroller)) {
					foreach ($asc_replacecontroller as $replace_controller_key => $replace_controller) {

						// 7.2 each deprecated
						//list($key_replace_controller, $value_replace_controller) = each($replace_controller);
                        foreach ($replace_controller as $key_data => $value_data) {
                        	$key_replace_controller = $key_data;
                        	$value_replace_controller = $value_data;
                        }

						if ($params[0] == $key_replace_controller) {
							if (!isset($params[1])) {
								$modules = $this->load->controller($value_replace_controller);
							} else {
								$modules = $this->load->controller($value_replace_controller, $params[1]);
							}
							$this->registry->set('loader_work', false);
							return $modules;
						}
					}
				}

				if ($this->registry->get('returnResponseSetOutput')) {
					if ($params[0] == 'common/footer' || $params[0] == 'common/header') {
						return '';
					}
				}
			}
			if ($name == 'view' && !$this->registry->get('admin_work')) {

				$asc_replacedata = $this->registry->get('asc_replacedata');

				if (!empty($asc_replacedata)) {
					foreach ($asc_replacedata as $replace_data_key => $replace_data) {
						// 7.2 each deprecated
						//list($key_replace_data, $value_replace_data) = each($replace_data);
                        foreach ($replace_data as $key_data => $value_data) {
                        	$key_replace_data = $key_data;
                        	$value_replace_data = $value_data;
                        }

						if ($key_replace_data != '') {
							if (SC_VERSION > 21) {
								$pos = stripos($key_replace_data, $params[0]);
							} else {
	                          	$pos = stripos($params[0], $key_replace_data);
							}
						} else {
							$pos = true;
						}
						if (isset($params[1]) && ($key_replace_data == '' || $pos !== false)) {
							$params[1] = $this->replacedatamethod($params[1], $key_replace_data, $value_replace_data);
							if ($key_replace_data != '') {
								unset($asc_replacedata[$replace_data_key]);
								$this->registry->set('asc_replacedata', $asc_replacedata);
							}
						}
					}
				}
			}
		}
		if (!$flag) {

			$this_loader = $this->registry->get('load');
			if (!$this->registry->get('loader_work')) {
				$this->Loader = $this->registry->get('load_old');
			} else {
				$this->Loader = new Loader($this->registry);
			}
			if ($name == 'library' && !is_callable(array('Loader', 'library'))) {
				$flag = true;
			}

			if (!$flag) {

				$modules = call_user_func_array(array(
					$this->Loader,
					$name
				), $params);

				if (SC_VERSION > 15) {
					$modules = $this->set_agoo_headers($modules);
                }

			}

			$this->registry->set('load', $this_loader);
			unset($this->Loader);

		}
		$this->registry->set('loader_work', false);
		return $modules;
	}

	public function setreplacecontroller($data) {
		$asc_replacecontroller   = $this->registry->get('asc_replacecontroller');
		$asc_replacecontroller[] = $data;
		$this->registry->set('asc_replacecontroller', $asc_replacecontroller);
	}

	public function getreplacecontroller() {
		return $this->registry->get('asc_replacecontroller');
	}

	public function setreplacedata($data) {
		$asc_replacedata   = $this->registry->get('asc_replacedata');
		$asc_replacedata[] = $data;
		$this->registry->set('asc_replacedata', $asc_replacedata);
	}

	public function getreplacedata() {
		return $this->registry->get('asc_replacedata');
	}

	public function replacedatamethod($data, $value, $newvalue)	{
		// 7.2 each deprecated
		//list($key_replace_data, $value_replace_data) = each($newvalue);
	    foreach ($newvalue as $key_data => $value_data) {
	    	$key_replace_data = $key_data;
	    	$value_replace_data = $value_data;
	    }

		if (isset($data[$key_replace_data]) || $key_replace_data == '') {
			if (is_object($value_replace_data)) {
				// reset($value_replace_data);
				$value_replace_data = (array) $value_replace_data;

				// 7.2 each deprecated
				//list($object_str, $method_str) = each($value_replace_data);
			    foreach ($value_replace_data as $key_data => $value_data) {
			    	$object_str = $key_data;
			    	$method_str = $value_data;
			    }

				$this_obgect = new $object_str($this->registry);
				if ($key_replace_data == '') {
					$this_obgect->$method_str();
				} else {
					$data[$key_replace_data] = $this_obgect->$method_str($data[$key_replace_data]);
				}
			} else {
				$data[$key_replace_data] = $value_replace_data;
			}
		}
		return $data;
	}

	public function agoomodel($model, $data = array(), $dir_application = DIR_APPLICATION) {
		$model = str_replace('../', '', (string) $model);
		$file  = $dir_application . 'model/agoo/' . $model . '.php';
        $original_file = $file;
  		if (function_exists('modification')) {
        	$file = modification($file);
        }
		if (class_exists('VQMod',false)) {
			if (VQMod::$directorySeparator) {
				if (strpos($file,'vq2-')!==FALSE) {
				} else {
					if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
						//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
						//exit;
					}
					if ($original_file != $file) {
						$file = VQMod::modCheck($file, $original_file);
					} else {
						$file = VQMod::modCheck($original_file);
					}
				}
			}
		}
		$class = 'agooModel' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		if (!file_exists($file)) {
			$file  = $dir_application . 'model/' . $model . '.php';
			if (function_exists('modification')) {
        		$file = modification($file);
        	}
			$class = 'Model' . preg_replace('/[^a-zA-Z0-9]/', '', $model);
		}
		if (file_exists($file)) {
			include_once($file);
			$this->registry->set('model_' . str_replace('/', '_', $model), new $class($this->registry));
		} else {

		}
	}


	public function agoo_mod_vq($file) {
		    $original_file = $file;
		    if (function_exists('modification')) {
		 		$file = modification($file);
		 	}

			if (class_exists('VQMod',false)) {
				if (VQMod::$directorySeparator) {
					if (strpos($file,'vq2-')!==FALSE) {
					} else {
						if (version_compare(VQMod::$_vqversion,'2.5.0','<')) {
							//trigger_error("You are using an old VQMod version '".VQMod::$_vqversion."', please upgrade your VQMod!");
							//exit;
						}
						if ($original_file != $file) {
							$file = VQMod::modCheck($file,$original_file);
						} else {
							$file = VQMod::modCheck($original_file);
						}
					}
				}
			}
			return $file;
	}
	public function set_agoo_headers($params) {

		$params = $this->set_agoo_og_page($params);
		//$params = $this->set_agoo_hreflang($params);
		return $params;
	}

	private function set_agoo_hreflang($params) {
		if (isset($params) && !$this->registry->get('admin_work')) {
			if (is_string($params) && strpos($params, '</head>') !==false && strpos($params, '<link rel="alternate"') === false && is_callable(array($this->document, 'getSCHreflang'))) {
				$sc_hreflang = $this->document->getSCHreflang();

				if ($sc_hreflang && !empty($sc_hreflang)) {
					foreach ($sc_hreflang as $sc_hreflang_code => $sc_hreflang_array) {

							$params = str_replace("</head>", '
<link rel="alternate" hreflang="' . $sc_hreflang_array['hreflang'] . '" href="' . $sc_hreflang_array['href'] . '" />
</head>', $params);
					}
                    $this->document->setSCHreflang(array());
				}
			}
		}
		return $params;
	}

	private function set_agoo_og_page($params) {
		if (isset($params) && !$this->registry->get('admin_work')) {
			if (isset($this->request->get['route']) && ($this->request->get['route'] == 'record/record' || $this->request->get['route'] == 'record/blog')) {

				if (is_string($params) && strpos($params, '</head>') !==false && strpos($params, '<meta name="robots"') === false && is_callable(array($this->document, 'getSCRobots'))) {
					$sc_robots = $this->document->getSCRobots();
					if ($sc_robots && $sc_robots != '')
						$params = str_replace("</head>", '
<meta name="robots" content="' . $sc_robots . '" />
</head>', $params);

				$this->document->setSCRobots('');
				}

				if (isset($this->seocms_settings['og']) && $this->seocms_settings['og']) {
					if (is_string($params) && strpos($params, '</head>') !==false && strpos($params, "og:image") === false && is_callable(array($this->document, 'getSCOgImage'))) {
						$og_image = $this->document->getSCOgImage();

						if ($og_image && $og_image != '')
							$params = str_replace("</head>", '
<meta property="og:image" content="' . $og_image . '" />
</head>', $params);
					$this->document->setSCOgImage('');
					}
					if (is_string($params) && strpos($params, '</head>') !==false  && strpos($params, "og:title") === false && is_callable(array($this->document, 'getSCOgTitle'))) {
						$og_title = $this->document->getSCOgTitle();
						if ($og_title && $og_title != '')
							$params = str_replace("</head>", '
<meta property="og:title" content="' . $og_title . '" />
</head>', $params);

					$this->document->setSCOgTitle('');
					}
					if (is_string($params) && strpos($params, '</head>') !==false  && strpos($params, "og:description") === false && is_callable(array($this->document, 'getSCOgDescription'))) {
						$og_description = $this->document->getSCOgDescription();
						if ($og_description && $og_description != '')
							$params = str_replace("</head>", '
<meta property="og:description" content="' . $og_description . '" />
</head>', $params);
					$this->document->setSCOgDescription('');
					}
					if (is_string($params) && strpos($params, '</head>') !==false  && strpos($params, "og:url") === false && is_callable(array($this->document, 'getSCOgUrl'))) {
						$og_url = $this->document->getSCOgUrl();
						if ($og_url && $og_url != '')
							$params = str_replace("</head>", '
<meta property="og:url" content="' . $og_url . '" />
</head>', $params);
					$this->document->setSCOgUrl('');
					}
					if (is_string($params) && strpos($params, '</head>') !==false  && strpos($params, "og:type") === false && is_callable(array($this->document, 'getSCOgType'))) {
						$og_type = $this->document->getSCOgType();
						if ($og_type && $og_type != '')
							$params = str_replace("</head>", '
<meta property="og:type" content="' . $og_type . '" />
</head>', $params);
					$this->document->setSCOgType('');
					}
				}
			}
		}
		return $params;
	}

}
}
