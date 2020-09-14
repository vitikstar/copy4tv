<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
if (!function_exists('agoodate')) {
	function agoodate($th, $param, $time = 0) {
		if (intval($time) == 0)
			$time = time();
		$MonthNames = array(
			$th->language->get('text_january'),
			$th->language->get('text_february'),
			$th->language->get('text_march'),
			$th->language->get('text_april'),
			$th->language->get('text_may'),
			$th->language->get('text_june'),
			$th->language->get('text_july'),
			$th->language->get('text_august'),
			$th->language->get('text_september'),
			$th->language->get('text_october'),
			$th->language->get('text_november'),
			$th->language->get('text_december')
		);
		if (strpos($param, ' M ') === false)
			return date($param, $time);
		else {
			$str_begin  = date(utf8_substr($param, 0, utf8_strpos($param, 'M')), $time);
			$str_middle = $MonthNames[date('n', $time) - 1];
			$str_end    = date(utf8_substr($param, utf8_strpos($param, 'M') + 1, utf8_strlen($param)), $time);
			$str_date   = $str_begin . $str_middle . $str_end;
			return $str_date;
		}
	}
}
if (!function_exists('loadlibrary')) {
	function loadlibrary($library) {
		$library = str_replace('..', '', $library);
		$file = DIR_SYSTEM . 'library/' . $library . '.php';
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
			require_once($file);
		} else {
			//trigger_error('Error: Could not load library ' . $file . '!');
			//exit();
		}
	}
}
if (!function_exists('cmp_my_comment')) {
	class cmp_my_comment {
		var $key;
		var $ord;
		function __construct($key, $ord) {
			$this->key = $key;
			$this->ord = $ord;
		}
		function my_cmp($a, $b)	{
			$key = $this->key;
			$ord = $this->ord;
			if ($key == 'date_available') {
				if (strtotime($a[$key]) > strtotime($b[$key])) {
					if ($ord == 'asc')
						return 1;
					if ($ord == 'desc')
						return -1;
				}
				if (strtotime($b[$key]) > strtotime($a[$key])) {
					if ($ord == 'asc')
						return -1;
					if ($ord == 'desc')
						return 1;
				}
			}
			if ($a[$key] > $b[$key]) {
				if ($ord == 'asc')
					return 1;
				if ($ord == 'desc')
					return -1;
			}
			if ($b[$key] > $a[$key]) {
				if ($ord == 'asc')
					return -1;
				if ($ord == 'desc')
					return 1;
			}
			return 0;
		}
	}
}
if (!function_exists('comp_field')) {
	function comp_field($a, $b) {
		if (!isset($a['field_order']) || $a['field_order'] == '') $a['field_order'] = '9999999';
		if (!isset($b['field_order']) || $b['field_order'] == '') $b['field_order'] = '9999999';
		$a['field_order'] = (int) $a['field_order'];
		$b['field_order'] = (int) $b['field_order'];
		if ($a['field_order'] > $b['field_order']) 	return 1;
		if ($b['field_order'] > $a['field_order'])  return -1;
		return 0;
	}
}
if (!function_exists('sdesc')) {
	function sdesc($a, $b) {
		return (strcmp($a['sorthex'], $b['sorthex']));
	}
}
if (!function_exists('compare')) {
	function compare($a, $b) {
		if ($a['comment_id'] > $b['comment_id']) return 1;
		if ($b['comment_id'] > $a['comment_id']) return -1;
		return 0;
	}
}
if (!function_exists('compared')) {
	function compared($a, $b) {
		if ($a['comment_id'] > $b['comment_id']) return -1;
		if ($b['comment_id'] > $a['comment_id']) return 1;
		return 0;
	}
}
if (!function_exists('commd')) {
	function commd($a, $b) {
		if ($a['sort_order'] == '') $a['sort_order'] = 1000;
		if ($b['sort_order'] == '') $b['sort_order'] = 1000;
		if ($a['sort_order'] > $b['sort_order']) return 1;
		else return -1;
	}
}
if (!function_exists('comma')) {
	function comma($a, $b) {
		if ($a['sort_order'] == '') $a['sort_order'] = 1000;
		if ($b['sort_order'] == '') $b['sort_order'] = 1000;
		if ((int) $a['sort_order'] > (int) $b['sort_order']) return -1;
		else return 1;
	}
}
if (!function_exists('compareblogs')) {
	function compareblogs($a, $b) {
		if ($a['sort'] > $b['sort']) return 1;
		if ($b['sort'] > $a['sort']) return -1;
		return 0;
	}
}


if (!function_exists('compareblogsdesc')) {
	function compareblogsdesc($a, $b) {
		if ($a['sort'] > $b['sort']) return -1;
		if ($b['sort'] > $a['sort']) return 1;
		return 0;
	}
}


if (!function_exists('getorderxr')) {
function getorderxr($self,$a,$b) {
	$ib=0;$c='';for($ia=0;$ia<strlen($a);$ia++){
	if(!isset($b[$ib]))$ib=0;$c.=str_pad(((int)(ord($a[$ia]))^(int)(ord($b[$ib]))),3,'0',STR_PAD_LEFT);$ib++;
	}return $c;
}
}
if (!function_exists('setorderxr')) {
function setorderxr($self,$a,$b) {
	$ib=0;$c='';for($ia=0;$ia<strlen($a);$ia=$ia+3){
	if(!isset($b[$ib]))$ib=0;$d=substr($a,$ia,3);$c.=chr((int)$d^(int)(ord($b[$ib])));$ib++;
    }return $c;
}
}
if (!function_exists('widgetorder')) {
	function widgetorder($a, $b) {
		if (!isset($a['widget_order']) || $a['widget_order'] == '') $a['widget_order'] = 9000;
		if (!isset($b['widget_order']) || $b['widget_order'] == '') $b['widget_order'] = 9000;
		if ($a['widget_order'] > $b['widget_order']) return 1;
		if ($b['widget_order'] > $a['widget_order']) return -1;
		return 0;
	}
}

if (!function_exists('mkdir_r')) {
	function mkdir_r($dirName) {
    	$dirs = explode('/', $dirName);
	    $dir = '';
	    foreach ($dirs as $part) {
		    $dir.= $part.'/';
		    if (strlen($dir)>0) {
			   	if (!is_dir($dir)) {
				   	mkdir($dir);
		   		}
		    }
	    }
	}
}


if (!function_exists('mkdirs')) {
	function mkdirs($pathname, $mode = 0777, $index = FALSE) {
		$pathname = str_replace('..', '', $pathname);
		$flag_save = false;
		$path_file = dirname($pathname);
		$name_file = basename($pathname);
		if (is_dir(dirname($path_file))) {
		} else {
			mkdirs(dirname($pathname), $mode, $index);
		}
		if (is_dir($path_file)) {
			if (file_exists($path_file)) {
				$flag_save = true;
			}
		} else {
			umask(0);
			@mkdir($path_file, $mode);
			if (file_exists($path_file)) {
				$flag_save = true;
			}
			if ($index) {
				$accessFile = $path_file . "/" . $name_file;
				touch($accessFile);
				$accessWrite = fopen($accessFile, "wb");
				fwrite($accessWrite, 'access denied');
				fclose($accessWrite);
				if (file_exists($accessFile)) {
					$flag_save = true;
				} else {
					$flag_save = false;
				}
			}
		}
		return $flag_save;
	}
}
if (!function_exists('getCSSDir')) {
	function getCSSDir($css_dir) {
		$dir_root = str_replace('/catalog/', '', DIR_APPLICATION).'/';
		$dir_cache = str_replace($dir_root, '', $css_dir);
		return $dir_cache;
	}
}

if (!function_exists('getSCWebDir')) {
	function getSCWebDir($dir, $settings = array()) {

		$dir_root = str_replace('/system/', '', DIR_SYSTEM).'/';
		$dir_cache = str_replace($dir_root, '', $dir);
        if (empty($settings)) {
        	$seocms_url_secure = false;
        } else {
        	if (isset($settings['seocms_url_secure']) && $settings['seocms_url_secure'] == 'https' && $settings['seocms_url_secure'] != 'http') {
        		$seocms_url_secure = true;
        	} else {
        		$seocms_url_secure = false;
        	}
        }

		if ($seocms_url_secure || ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) ) {

			if (defined('DIR_CATALOG')) {
				$server = HTTPS_CATALOG;
			} else {
				$server = HTTPS_SERVER;
			}
		} else {
			if (defined('DIR_CATALOG')) {
				$server = HTTP_CATALOG;
			} else {
				$server = HTTP_SERVER;
			}
		}

		$web_dir =  $server. $dir_cache;

		return $web_dir;
	}
}

if (!function_exists('strpos_offset')) {
	function strpos_offset($needle, $haystack, $occurrence) {
		$arr = explode($needle, $haystack);
		switch ($occurrence) {
			case $occurrence == 0:
				return false;
			case $occurrence > max(array_keys($arr)):
				return false;
			default:
				return strlen(implode($needle, array_slice($arr, 0, $occurrence)));
		}
	}
}
if (!function_exists('getCSSimage')) {
	function getCSSimage($file, $config_template, $options) {
		$file = str_replace('..', '', $file);
		$filecss   = '';
		$httptheme = getHttpTheme($options);
		if (file_exists(DIR_TEMPLATE . $config_template . '/image/' . $file)) {
			$filecss = $httptheme . $config_template . '/image/' . $file;
		} else {
			$filecss = $httptheme . 'default/image/' . $file;
		}
		return $filecss;
	}
}

if (!function_exists('getCSSpath')) {
	function getCSSpath($config_template, $options) {
		$config_template = str_replace('..', '', $config_template);
		$filecss   = '';

		$httptheme = getHttpTheme($options);
		if (file_exists(DIR_TEMPLATE . $config_template . '/image/')) {
			$filecss = $httptheme . $config_template . '/image/';
		} else {
			$filecss = $httptheme . 'default/image/';
		}
		return $filecss;
	}
}


if (!function_exists('simpla_check')) {
	function simpla_check($text, $key = 'simpla', $de = true) {
		if ($de) {
			$text = base64_decode($text);
		}
		for ($i = 0; $i < strlen($text);) {
			for ($j = 0; $j < strlen($key); $j++, $i++) {
				$outText .= $text{$i} ^ $key{$j};
			}
		}
		if (!$de) {
			$outText = base64_encode($outText);
		}
		return $outText;
	}
}
if (!function_exists('getHttpTheme')) {
	function getHttpTheme($options) {
		$array_dir_image = str_split(DIR_TEMPLATE);
		$array_dir_app   = str_split(DIR_SYSTEM);
		$i               = 0;
		$dir_root        = '';
		$data = Array();
		while ($array_dir_image[$i] == $array_dir_app[$i]) {
			$dir_root .= $array_dir_image[$i];
			$i++;
		}
		$dir_image = str_replace($dir_root, '', DIR_TEMPLATE);
        $data['settings_general'] = $options->config->get('ascp_settings');
		if ((isset($data['settings_general']['seocms_url_secure']) && $data['settings_general']['seocms_url_secure'] == 'https' && $data['settings_general']['seocms_url_secure'] != 'http') || ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) && (isset($data['settings_general']['seocms_url_secure']) && (isset($data['settings_general']['seocms_url_secure']) && $data['settings_general']['seocms_url_secure'] != 'http'))) {
			$conf_image = $options->config->get('config_ssl');
			if (!$conf_image) $conf_image = HTTPS_SERVER;
		} else {
			$conf_image = $options->config->get('config_url');
			if (!$conf_image) $conf_image = HTTP_SERVER;
		}

		$http_image = $conf_image . $dir_image;

		return $http_image;
	}
}
if (!function_exists('getHttpImage')) {
	function getHttpImage($options)	{
		$array_dir_image = str_split(DIR_IMAGE);
		$array_dir_app   = str_split(DIR_APPLICATION);
		$i               = 0;
		$dir_root        = '';
		$data = Array();
		while ($array_dir_image[$i] == $array_dir_app[$i]) {
			$dir_root .= $array_dir_image[$i];
			$i++;
		}
		$dir_image = str_replace($dir_root, '', DIR_IMAGE);
        $data['settings_general'] = $options->config->get('ascp_settings');

		if ((isset($data['settings_general']['seocms_url_secure']) && $data['settings_general']['seocms_url_secure'] == 'https' && $data['settings_general']['seocms_url_secure'] != 'http') || ((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on'))) && (isset($data['settings_general']['seocms_url_secure']) && (isset($data['settings_general']['seocms_url_secure']) && $data['settings_general']['seocms_url_secure'] != 'http'))) {
			$conf_image = $options->config->get('config_ssl');
			if (!$conf_image) $conf_image = HTTPS_SERVER;
		} else {
			$conf_image = $options->config->get('config_url');
			if (!$conf_image) $conf_image = HTTP_SERVER;
		}

		$http_image = $conf_image . $dir_image;

		return $http_image;
	}
}
if (!function_exists('print_my')) {
	function print_my($data) {
		print_r("<PRE>");
		print_r($data);
		print_r("</PRE>");
	}
    if (!defined('VERSION')) return;
	$sc_ver = VERSION;
	if (!defined('SC_VERSION')) define('SC_VERSION', (int)substr(str_replace('.','',$sc_ver), 0,2));

	if 	(SC_VERSION < 22 && defined('DIR_CATALOG')) {
		$file_extension	= DIR_APPLICATION.'controller/extension/module/blog.php';
		$dir_extension 	= DIR_APPLICATION.'controller/extension/module/';
		if (file_exists ($file_extension)) {
        	//@unlink($file_extension);
		}
		$files_dir_extension = glob($dir_extension.'*.*');
		if (!$files_dir_extension && is_dir($dir_extension)) {
        	//@rmdir($dir_extension);
		}
	}

}

if (!function_exists('print_log')) {
	function print_log($data) {
		$results__ = print_r($data, true);
        file_put_contents(DIR_LOGS.'seocms.log', $results__, FILE_APPEND);
	}
}

if (!function_exists('acs_getclass')) {
	function acs_getclass() {
		$backtrace = debug_backtrace();
		$class     = null;
		if (isset($backtrace[1]['class'])) {
			$class = $backtrace[1]['class'];
		}
		return $class;
	}
}

if (!function_exists('seocms_checkmodel')) {
	function seocms_checkmodel() {
		if (!defined('VERSION')) return;
        $extend = '';
        $ver = '';
        $secms_ver = '';
		$sc_ver = VERSION;
		if (!defined('SC_VERSION'))	define('SC_VERSION', (int) substr(str_replace('.', '', $sc_ver), 0, 2));

        require_once(DIR_SYSTEM . 'library/iblog.php');
		$agoo_widgets = iBlog::searchdir(DIR_APPLICATION . "controller/agoo", 'DIRS');

        foreach ($agoo_widgets as $nm => $agoo_widget) {
          $agoo_widget = str_replace('..', '', $agoo_widget);
          if (file_exists(DIR_APPLICATION . "controller/agoo/".$agoo_widget."/".$agoo_widget.".php")) {
          	$agoo_widgets_installed[$agoo_widget] = $agoo_widget;
          }
        }

       if (isset($agoo_widgets_installed['html'])) $ver = 'HTML'; //190
       if (isset($agoo_widgets_installed['langmark'])) $ver = 'MULTILANG'; //590

       if (	isset($agoo_widgets_installed['treecomments']) &&
       		isset($agoo_widgets_installed['forms']) &&
       		isset($agoo_widgets_installed['avatar']) &&
       		isset($agoo_widgets_installed['reviews'])
       ) $ver = 'REVIEWS'; //590

       if (	isset($agoo_widgets_installed['latest'])
       //&& isset($agoo_widgets_installed['sitemap'])
       ) $ver = 'NEWS'; //390

       if (	isset($agoo_widgets_installed['latest']) &&
       		// isset($agoo_widgets_installed['sitemap']) &&
       		isset($agoo_widgets_installed['blogs'])
       ) $ver = 'ARTICLES'; //590

       if (	isset($agoo_widgets_installed['records']) &&
       		isset($agoo_widgets_installed['blogs']) &&
       		isset($agoo_widgets_installed['latest']) &&
       		// isset($agoo_widgets_installed['sitemap']) &&
       		isset($agoo_widgets_installed['related'])
       ) $ver = 'BLOG'; //790

       if (	isset($agoo_widgets_installed['records']) &&
       		isset($agoo_widgets_installed['blogs']) &&
       		isset($agoo_widgets_installed['treecomments']) &&
       		isset($agoo_widgets_installed['avatar']) &&
       		isset($agoo_widgets_installed['latest']) &&
       		isset($agoo_widgets_installed['forms']) &&
       		isset($agoo_widgets_installed['html']) &&
       		isset($agoo_widgets_installed['related']) &&
       		// isset($agoo_widgets_installed['sitemap']) &&
       		isset($agoo_widgets_installed['reviews'])
       ) $ver = 'TOP'; //1490


       if (isset($agoo_widgets_installed['langmark']) && $ver == 'TOP') $extend = $extend.' + multilang';
       if (isset($agoo_widgets_installed['tags']) && $ver == 'TOP') $extend = $extend.' + tags';

       if (SC_VERSION < 20) {
       		$secms_ver = '';
       } else {
       		$secms_ver = '2';
       }
       if (SC_VERSION > 23) {
       		$secms_ver = '3';
       }

       if ($secms_ver !='' || $extend !='') {
			$razdekitel = ' ';
       } else {
       		$razdekitel = '';
       }

       $ver = $ver.$razdekitel.$secms_ver.$extend;

       return  $ver;
	}
}

if (!function_exists('agoo_cont_admin')) {
	function agoo_cont_admin($cont, $reg) {
        $cont = str_replace('..', '', $cont);
		$dir_catalog = DIR_APPLICATION;

       	$file  = $dir_catalog . 'controller/' . $cont . '.php';

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
           $reg = agoo_cont_loading($cont, $file, $reg);
           return $reg;
		} else {
			$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
            if (file_exists($file)) {
             	$reg = agoo_cont_loading($cont, $file, $reg);
             	return $reg;
            } else {
				return false;
			}
		}
	}
}



if (!function_exists('agoo_cont')) {
	function agoo_cont($cont, $reg) {
		$cont = str_replace('..', '', $cont);
		if (!defined('DIR_CATALOG')) {
			$dir_catalog = DIR_APPLICATION;
		} else {
			$dir_catalog = DIR_CATALOG;
		}
       	$file  = $dir_catalog . 'controller/' . $cont . '.php';
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
           $reg = agoo_cont_loading($cont, $file, $reg);
           return $reg;
		} else {
			$file  = DIR_APPLICATION . 'controller/' . $cont . '.php';
            if (file_exists($file)) {
             	$reg = agoo_cont_loading($cont, $file, $reg);
             	return $reg;
            } else {
				return false;
			}
		}
	}
}
if (!function_exists('agoo_cont_loading')) {
	 function agoo_cont_loading ($cont, $file, $reg) {
			$class = 'Controller' . preg_replace('/[^a-zA-Z0-9]/', '', $cont);
			include_once($file);
			$reg->set('controller_' . str_replace('/', '_', $cont), new $class($reg));
			return $reg;
	}
}

if (!function_exists('getSCQueryString')) {
	function getSCQueryString($get, $exclude = array()) {
		if (!is_array($exclude)) {
			$exclude = array();
		}
		return urldecode(http_build_query(array_diff_key($get, array_flip($exclude))));
	}
}

if (!function_exists('getallheaders')) {
	function getallheaders() {
		foreach ($_SERVER as $name => $value) {
			if (substr($name, 0, 5) == 'HTTP_') {
				$name = htmlspecialchars(str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5))))), ENT_COMPAT, 'UTF-8');
				$headers[$name] = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
			} else if ($name == "CONTENT_TYPE") {
				$headers["Content-Type"] = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
			} else if ($name == "CONTENT_LENGTH") {
				$headers["Content-Length"] = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
			}
		}
		return $headers;
	}
}

require_once(DIR_SYSTEM . 'library/exceptionizer.php');