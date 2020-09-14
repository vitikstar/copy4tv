<?php
class ControllerAgooLangmarkLangmark extends Controller
{
	private $error = array();
	protected  $data;
	protected $settings;
	protected $langmark_settings;

 	public function __construct($registry) 	{
		//Старт
		parent::__construct($registry);
		$this->langmark_settings  = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));
	}

	public function index($data) {
		$this->data = $data;

        $this->langmark_settings  = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));
		if (!isset($this->langmark_settings['access']) || !$this->langmark_settings['access']) {
			return;
		}

        $this->data['langmark'] = '';
		if ((!isset($this->data['settings_general']) || !empty($this->data['settings_general'])) && $this->config->get('ascp_settings') != '') {
			$this->data['settings_general'] = $this->config->get('ascp_settings');
		}

        if (!isset($this->data['settingswidget'])) {
        	$this->data['settingswidget'] = array();
        }

        if (isset($this->data['settings_general']['langmark_widget_status']) && $this->data['settings_general']['langmark_widget_status']) {

		 	$this->data['langmark_template'] = 'agoo/langmark/langmark.tpl';
		    $this->language->load('agoo/langmark/langmark');
		    $this->data['type'] = 'langmark';

			$this->data['languages'] = $this->registry->get('langmarkdata');

			if (isset($this->data['languages']) && is_array($this->data['languages'])) {
				usort($this->data['languages'], 'commd');
			}

			$this->language->load('module/language');

			$this->data['text_language'] = $this->language->get('text_language');
	        $this->data['redirect'] = '';
			$this->data['action'] = '';
			$this->data['language_code'] = $this->session->data['language'];
	        $this->data['language_prefix'] = $this->registry->get('langmark_prefix');

		    $class_widget = $this->data['type'].'_widget';
		    $this->data = $this->$class_widget($this->data);
		    $this->data['langmark_template'] = $this->data['template'];

			if (isset($this->langmark_settings['pagination_prefix'])) {
				$pagination_prefix = $this->langmark_settings['pagination_prefix'];
			} else {
				$pagination_prefix = 'page';
			}
		 	$file = DIR_APPLICATION.'controller/record/pagination.php';
			require_once($file);

			if (SC_VERSION > 15) {
				if (isset($this->data['settings_general']['langmark_widget_status']) && $this->data['settings_general']['langmark_widget_status']
				 && isset($this->langmark_settings['description_status']) && $this->langmark_settings['description_status'] ) {
		         	if (isset($this->request->get[$pagination_prefix]) && $this->request->get[$pagination_prefix] > 1) {
						if (isset($this->langmark_settings['desc_type']) && !empty($this->langmark_settings['desc_type'])) {
	                        foreach ($this->langmark_settings['desc_type'] as $desc_type) {
								$ex_vars_array = explode(PHP_EOL, trim($desc_type['vars']));
							    foreach($ex_vars_array as $num => $ex_var) {
									$array_replace = array();
							    	$ex_var = trim($ex_var);
									if ($ex_var[0] != '#' && $ex_var != '') {
                                    	$array_replace[$ex_var] = '';
					         			$this->load->setreplacedata(array($desc_type['title'] => $array_replace));
					         		}
         		                }
                            }
                        }
		         	}
	            }

	            $pagination_object = (object) array('ControllerRecordPagination' => 'setPagination');
	            $this->load->setreplacedata(array('' => array('pagination'=> $pagination_object)));

                if (isset($this->data['settings_general']['langmark_widget_status']) && $this->data['settings_general']['langmark_widget_status']) {
	            	$this->load->setreplacecontroller(array('common/language' => 'record/langmark'));
	            }


            } else {
 				$langmark = new ControllerRecordPagination($this->registry);

    	        $output = $this->response->seocms_getOutput();

    	        $output = $langmark->setPagination($output);
            }

			$this->setMain();

           	if (SC_VERSION < 20) {
		 		$file = DIR_APPLICATION.'controller/record/langmark.php';
			    require_once($file);
				$langmark = new ControllerRecordLangmark($this->registry);
				$this->data['langmark'] = $langmark->index();
			}
        }
	    return $this->data;
	}

	public function setMain() {

		$this->langmark_settings  = $this->config->get('asc_langmark_' . $this->config->get('config_store_id'));

		if (!isset($this->langmark_settings['access']) || !$this->langmark_settings['access']) {
			return;
		}

	    if (isset($this->request->get['route'])) {
			$route = $this->request->get['route'];
	    } else {
	    	$route = 'common/home';
	    }

        // From /system/library/agoo/multilang.php
        $langmark_multi = $this->registry->get('langmark_multi');

		if (isset($langmark_multi['main_title']) && $langmark_multi['main_title'] != '' && $route == 'common/home') {
	        $this->document->setTitle($langmark_multi['main_title']);
		}

		if (isset($langmark_multi['main_description']) && $langmark_multi['main_description'] != '' && $route == 'common/home') {
	        $this->document->setDescription($langmark_multi['main_description']);
		}

		if (isset($langmark_multi['main_keywords']) && $langmark_multi['main_keywords'] != '' && $route == 'common/home') {
	        $this->document->setKeywords($langmark_multi['main_keywords']);
		}

	}

	private function langmark_widget($data)	{
		$this->data = $data;
		$this->data['html'] = '';
		if (isset($this->data['settingswidget']['html'][$this->config->get('config_language_id')])) {
			$this->data['html'] = html_entity_decode($this->data['settingswidget']['html'][$this->config->get('config_language_id')], ENT_QUOTES, 'UTF-8');
		}

		if ($this->data['html'] != '') {

			$html_name = "cache.langmarkwidget." . md5(serialize($this->data['settingswidget'])) . "." . (int)$this->config->get('config_language_id').".".(int) $this->config->get('config_store_id') . ".tpl";
			$file = DIR_CACHE . $html_name;

		    $this->deletecache('langmarkwidget');
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
        }

		if (isset($this->data['settingswidget']['title_list_latest'][$this->config->get('config_language_id')]))
			$this->data['heading_title'] = $this->data['settingswidget']['title_list_latest'][$this->config->get('config_language_id')];
		else
			$this->data['heading_title'] = '';

        $this->data['template'] = 'langmark';
        if (SC_VERSION > 23) {
        	$this->data['template'] .= '.tpl';
        }

		if (isset($this->data['settingswidget']['template']) && $this->data['settingswidget']['template'] != '') {
			$this->data['template'] = 'agootemplates/widgets/langmark/' . $this->data['settingswidget']['template'];
		} else {
			$this->data['template'] = 'agootemplates/widgets/langmark/'.$this->data['template'];
		}
         /*
		$template_info  = pathinfo($this->data['template']);
        $template = $template_info['filename'];

        $this->data['template'] = 'agootemplates/widgets/langmark/' . $template;
         */
	     return $this->data;
	}

	private function deletecache($key) {
		$files = glob(DIR_CACHE . preg_replace('/[^A-Z0-9\._-]/i', '', $key) . '.*');

		if ($files) {
	    	foreach ($files as $file) {
	    		if (file_exists($file)) {
					$file_time = filemtime ($file);
					$date_current = date("d-m-Y H:i:s");
					$date_diff = (strtotime($date_current) - ($file_time))/60;
					if ($date_diff > 5) {
					 unlink($file);
					}
				}
	    	}
		}
	}

}