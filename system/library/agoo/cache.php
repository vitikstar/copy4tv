<?php
/* All rights reserved belong to the module, the module developers https://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
if (!class_exists('agooCache', false)) {
class agooCache extends Controller
{
	protected $Cache;
	private $dir_cache = DIR_CACHE;
	private $first = false;
	public $expire = 36000;
	public $max_files = 300;
	public $maxfile_length = 9437184;
	public $max_hache_folders_level = 1;
	private $gzip_level = 0;
	private $is_ssl = false;
	private $is_mobile = false;
	private $is_tablet = false;
	private $is_desktop = false;
	private $file_cache = false;
	private $customer_group_id;
	private $store_id;
	private $language_id;
	private $jetcache_settings = array();

    public function __construct($registry) {
		parent::__construct($registry);
		if (file_exists(DIR_APPLICATION . 'model/jetcache/jetcache.php')) {
			$this->load->model('jetcache/jetcache');
		}
	}
	public function agooconstruct($settings = array()) {

		if (!$this->config->get('cache_type') || $this->config->get('cache_type') == 'file') {
			$this->file_cache = true;
		} else {
			$this->file_cache = false;
		}

		$this->jetcache_settings = $this->config->get('asc_jetcache_settings');

        if (isset($this->jetcache_settings['seocms_jetcache_gzip_level']) && $this->jetcache_settings['seocms_jetcache_gzip_level'] > 0 && $this->jetcache_settings['seocms_jetcache_gzip_level'] < 10) {
        	if (extension_loaded('zlib')) {
             	$this->gzip_level = $this->jetcache_settings['seocms_jetcache_gzip_level'];
			}
        }

    	if (isset($this->jetcache_settings['cache_expire']) && $this->jetcache_settings['cache_expire'] != '') {
        	$this->expire = $this->jetcache_settings['cache_expire'];
    	}

    	if (isset($this->jetcache_settings['cache_max_files']) && $this->jetcache_settings['cache_max_files'] != '') {
        	$this->max_files = $this->jetcache_settings['cache_max_files'];
    	}

    	if (isset($this->jetcache_settings['cache_maxfile_length']) && $this->jetcache_settings['cache_maxfile_length'] != '') {
        	$this->maxfile_length = $this->jetcache_settings['cache_maxfile_length'];
    	}


    	if (isset($this->jetcache_settings['cache_max_hache_folders_level']) && $this->jetcache_settings['cache_max_hache_folders_level'] != '' && $this->jetcache_settings['cache_max_hache_folders_level'] > 0 && $this->jetcache_settings['cache_max_hache_folders_level'] < 10) {
        	$this->max_hache_folders_level = $this->jetcache_settings['cache_max_hache_folders_level'];
    	}

		if (((isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && (strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && strtolower($_SERVER['HTTP_X_FORWARDED_SSL']) == 'on')))) {
			$this->is_ssl = true;
		}

		$this->customer_group_id = (int)$this->config->get('config_customer_group_id');
        $this->store_id = (int)$this->config->get('config_store_id');
        $this->language_id = (int)$this->config->get('config_language_id');


    	if (isset($this->jetcache_settings['cache_mobile_detect']) && $this->jetcache_settings['cache_mobile_detect']) {

			if (!class_exists('jc_Mobile_Detect', false)) {
				loadlibrary('md/mobile_detect');
			}

	        $detect = new jc_Mobile_Detect;

	        if ($detect->isMobile()) {
	        	$this->is_mobile = true;
			}

			if($detect->isTablet()){
	        	$this->is_tablet = true;
			}

			if(!$this->is_tablet && !$this->is_mobile){
	        	$this->is_desktop = true;
			}
        }
	}

	public function construct_cache() {
		$asc_construct_cache = $this->registry->get('asc_construct_cache');
		if (!isset($asc_construct_cache[$this->dir_cache])) {
	        $exceptionizer = new PHP_Exceptionizer(E_ALL);
         	try {
				$files = glob($this->dir_cache . 'cache.*');
				if ($files) {
					clearstatcache();
					$count_files = count($files);
					foreach ($files as $file) {
						$time = substr(strrchr($file, '.'), 1);
						$file_size = @filesize($file);
						if (@file_exists($file) && @is_file($file)) {
							if ($time < time() || $count_files > $this->max_files || $file_size < 0 || $file_size > $this->maxfile_length) {
								@unlink($file);
							}
						}
					}
				}
				$asc_construct_cache[$this->dir_cache] = true;
				$this->registry->set('asc_construct_cache', $asc_construct_cache);
  			}  catch (E_WARNING $e) {
		       	return false;
			}
		}
	}

	public function __call($name, array $params) {
		$modules = false;

		if (isset($params[0]) && is_string($params[0])) {
			$pieces_array = explode('.', $params[0]);
		} else {
			$pieces_array = Array();
			$params[0]    = '';
		}

		if ($name == 'delete') {
			if (!empty($this->jetcache_settings)) {
				if (isset($this->jetcache_settings['ex_key']) && $this->jetcache_settings['ex_key'] != '') {
					$ex_key_array = explode(PHP_EOL, trim($this->jetcache_settings['ex_key']));
				    foreach($ex_key_array as $num => $ex_key) {
				    	$ex_key = trim($ex_key);
						if ($ex_key[0] != '#' && $ex_key != '' && $params[0] == $ex_key) {
							if (!$this->registry->get('jc_is_admin')) {
								agoo_cont('jetcache/jetcache', $this->registry);
								$this->registry->get('controller_jetcache_jetcache')->cacheremove('noaccess', false);
							} else {
								agoo_cont_admin('jetcache/jetcache', $this->registry);
								$this->registry->get('controller_jetcache_jetcache')->cacheremove(false);
							}
						}
				    }
				}
            }
		}

		if (!$this->config->get('blog_work') || (isset($pieces_array[0]) && $pieces_array[0] != 'blog') || !$this->file_cache) {
			$this_cache  = $this->registry->get('cache');
			$this->Cache = $this->registry->get('cache_old');
			$modules     = call_user_func_array(array(
				$this->Cache,
				$name
			), $params);
			$this->registry->set('cache', $this_cache);
			unset($this->Cache);
			unset($this_cache);
		} else {


            $pieces_devider = '/';
			if (isset($params[0])) {
				if (isset($pieces_array[1])) {
					$pieces = $pieces_array[1];
				} else {
					$pieces = '';
					$pieces_devider = '';
				}
			} else {
				$pieces = '';
				$pieces_devider = '';
			}

			if (isset($pieces_array[0]) && $pieces_array[0] == 'blog') {

				if ($pieces != 'db') {
					$file_cache = DIR_CACHE . 'seocms/index.html';
					if (!@file_exists($file_cache)) {
						$this->mkdirs($file_cache, true);
					}

					$hache = substr(strrchr($params[0], '.'), 1);

					$hache_array = explode('.', $params[0]);

                    $folder_hache_devider = '/';

					if (isset($hache_array[4])) {
						$hache = $hache_array[4];
					} else {
						if (isset($hache_array[3])) {
							$hache = $hache_array[3];
						} else {
							if (isset($hache_array[2])) {
								$hache = $hache_array[2];
							} else {
								$hache = $hache_array[2] = '';
								$folder_hache_devider = '';
							}
						}
					}

                    if ($hache != $pieces && isset($hache_array[2])) {
						$folder_hache_array = substr($hache, 0, $this->max_hache_folders_level);
                        $folder_hache = implode('/', str_split($folder_hache_array));
                    } else {
                    	$folder_hache = '';
                    }

					if ($folder_hache == '') {
						$folder_hache_cache_devider = '';
					} else {
						$folder_hache_cache_devider = '/';
					}

					$file_cache = DIR_CACHE . 'seocms'. str_replace('../', '', $pieces_devider . $pieces . $folder_hache_devider . $hache_array[2] . $folder_hache_cache_devider . $folder_hache . '/index.html');

					if (!@file_exists($file_cache) && $name == 'set') {
						$this->mkdirs($file_cache, false);
						@touch($file_cache);
						$accessWrite = @fopen($file_cache, "wb");
						@fwrite($accessWrite, 'Access denied');
						@fclose($accessWrite);
					}

					$this->dir_cache = DIR_CACHE . 'seocms/' . str_replace('../', '', $pieces . $pieces_devider . $hache_array[2] . $folder_hache_devider . $folder_hache . $folder_hache_cache_devider);

					if ($name == 'set') {

                       if (!isset($pieces_array[4])) {
					        /*
					        $files_to_del = $this->getDelFiles($this->dir_cache, '*', array('index.html', '.htaccess'));

							if ($files_to_del) {
								foreach ($files_to_del as $file) {
									if (file_exists($file)) {
									    try {
											@unlink($file);
											$status = true;
									    }  catch (E_WARNING $e) {
					                     	$status = false;
									    }
									}
								}
							}
							*/
                        } else {
					        $files_key = $this->dir_cache . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $params[0]).'.'.(int)$this->is_ssl.'_'.$this->store_id . '_' . $this->language_id . '_' . $this->customer_group_id . '_' .(int)$this->is_mobile.'_'.(int)$this->is_tablet.'_'.(int)$this->is_desktop;
				        	$this->delete_agoo($files_key);
				        }

						$modules = $this->set_agoo($params[0], $params[1]);
					}
					if ($name == 'get') {
						$modules = $this->get_agoo($params[0]);
					}
					if ($name == 'delete') {
                        // 5?
                        if (!isset($pieces_array[4])) {
					        $files_to_del = $this->getDelFiles($this->dir_cache, '*', array('index.html', '.htaccess'));

							if ($files_to_del) {
								foreach ($files_to_del as $file) {
									if (file_exists($file)) {
									    try {
											@unlink($file);
											$status = true;
									    }  catch (E_WARNING $e) {
					                     	$status = false;
									    }
									}
								}
							}
                        } else {
					        $files_key = $this->dir_cache . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $params[0]).'.'.(int)$this->is_ssl.'_'.$this->store_id . '_' . $this->language_id . '_' . $this->customer_group_id . '_' .(int)$this->is_mobile.'_'.(int)$this->is_tablet.'_'.(int)$this->is_desktop;
				        	$modules = $this->delete_agoo($files_key);
				        }

					}
				} else {

					$params[0] = $params[0].'.'.(int)$this->is_ssl.'_'.(int)$this->is_mobile.'_'.(int)$this->is_tablet.'_'.(int)$this->is_desktop.'.';

                    $table_suffix = '_0';
					$table = $pieces_array[2];
                    $hash = $pieces_array[3];
                    $hash_first = strtolower($hash[0]);

                    $table_0 = '01234567890';
                    if (strpos($table_0, $hash_first) !== false) {
                    	$table_suffix = '_0';
                    }
                    $table_1 = 'abcdefgh';
                    if (strpos($table_1, $hash_first) !== false) {
                    	$table_suffix = '_1';
                    }
                    $table_2 = 'ijklmn';
                    if (strpos($table_2, $hash_first) !== false) {
                    	$table_suffix = '_2';
                    }
                    $table_3 = 'opqrst';
                    if (strpos($table_3, $hash_first) !== false) {
                    	$table_suffix = '_3';
                    }
                    $table_4 = 'uvwxyz';
                    if (strpos($table_4, $hash_first) !== false) {
                    	$table_suffix = '_4';
                    }
                    $table = 'jetcache_'.$table.$table_suffix;

					if ($name == 'set') {
						$modules = $this->set_db_agoo($table, $params[0], $params[1]);
					}
					if ($name == 'get') {
						$modules = $this->get_db_agoo($table, $params[0]);
					}
					if ($name == 'delete') {
						$modules = $this->delete_db_agoo($table, $params[0]);
					}

				}

			} else {
				$this->dir_cache = DIR_CACHE;
			}
		}
		return $modules;
	}

    public function get_db_agoo($table, $key) {

       	$datas = $this->model_jetcache_jetcache->getSettings($table, $key);
        $exceptionizer = new PHP_Exceptionizer(E_ALL);
		if ($datas) {
			try {
				if ($this->gzip_level > 0) {
					$datas = gzdecode(base64_decode($datas));
				}
				$datas_array = json_decode($datas, true);
			} catch (E_WARNING $e) {
			}

			if (!is_array($datas_array)) {
				$datas_array = false;
			}
		} else {
			$datas_array = false;
		}
		unset($exceptionizer);
		return $datas_array;
    }

	public function set_db_agoo($table, $key, $value) {

        $this->delete_db_agoo($table, $key);
        $time_expire = time() + $this->expire;

        $exceptionizer = new PHP_Exceptionizer(E_ALL);
		if (is_array($value)) {
		    try {
		    	$value = json_encode($value);
		    	$this->json_error();
		    }  catch (E_WARNING $e) {
		    	return false;
		    }
		}

		if ($this->gzip_level > 0) {
			$value = base64_encode(gzencode($value, (int)$this->gzip_level));
		}

		$this->model_jetcache_jetcache->setSettings($table, $key, $value, $time_expire);
        unset($exceptionizer);
		return true;
	}

    public function delete_db_agoo($table, $key) {

        $exceptionizer = new PHP_Exceptionizer(E_ALL);
		try {
            $datas = $this->model_jetcache_jetcache->deleteSettings($table, $key);
		} catch (E_WARNING $e) {
		}
    	unset($exceptionizer);
    	return true;
    }

	public function set_agoo($key, $value) {

		$file = $this->dir_cache . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key).'.' . (int)$this->is_ssl . '_' . $this->store_id . '_' . $this->language_id . '_' . $this->customer_group_id . '_' .(int)$this->is_mobile.'_'.(int)$this->is_tablet.'_'.(int)$this->is_desktop.'.' . (time() + $this->expire);

		$handle = @fopen($file, 'w');
		@flock($handle, LOCK_EX);
		if (is_array($value)) {
			$exceptionizer = new PHP_Exceptionizer(E_ALL);
		    try {
		    	$value = json_encode($value);
                $this->json_error();
		    }  catch (E_WARNING $e) {
		    	return false;
		    }
		}

		if ($this->gzip_level > 0) {
			$value = gzencode($value, (int)$this->gzip_level);
		}

		@fwrite($handle, $value);
		@fflush($handle);
		@flock($handle, LOCK_UN);
		@fclose($handle);

		$this->registry->set('jetcache_cache_filename', $file);

		$this->construct_cache();
		return true;

	}

	public function get_agoo($key) {
		$files = glob($this->dir_cache . 'cache.' . preg_replace('/[^A-Z0-9\._-]/i', '', $key).'.'.(int)$this->is_ssl. '_' . $this->store_id . '_' . $this->language_id . '_' . $this->customer_group_id .'_'.(int)$this->is_mobile.'_'.(int)$this->is_tablet.'_'.(int)$this->is_desktop.'.*');

		if ($files) {
			$exceptionizer = new PHP_Exceptionizer(E_ALL);
         	try {
				clearstatcache();
				if (@file_exists($files[0])) {
					$handle = @fopen($files[0], 'r');
					@flock($handle, LOCK_SH);
					$file_size = @filesize($files[0]);

					$time = substr(strrchr($files[0], '.'), 1);

 					if ($time < time() || $file_size < 0 || $file_size > $this->maxfile_length) {
						@unlink($files[0]);
						$datas = '[]';
					} else {

						$datas = @fread($handle, $file_size);

						if ($this->gzip_level > 0) {
							$datas = gzdecode($datas);
						}

					}
					@flock($handle, LOCK_UN);
					@fclose($handle);

					$this->registry->set('jetcache_cache_filename', $files[0]);

					$datas_array = json_decode($datas, true);
				} else {
					$datas_array = $datas = array();
				}
			} catch (E_WARNING $e) {
			}

			if (isset($datas_array) && is_array($datas_array)) {
				$datas = $datas_array;
			} else {
				try {
					$datas_array = @unserialize($datas);
				}
				catch (E_WARNING $e) {
				}
			}
			unset($exceptionizer);
			if (is_array($datas_array)) {
				return $datas_array;
			} else {
				return $datas;
			}
		}
		return false;
	}

	public function delete_agoo($key) {

		$files = glob($key.'.*');

		if ($files) {
	        $exceptionizer = new PHP_Exceptionizer(E_ALL);
         	try {
				clearstatcache();
				foreach ($files as $file) {
					if (@file_exists($file)) {
						@unlink($file);
					}
				}
				return true;
			}  catch (E_WARNING $e) {
		    	return false;
			}
		}
		return false;
	}

	private function delTree($dir) {
		if (function_exists('modification')) {
			require_once(modification(DIR_SYSTEM . 'library/exceptionizer.php'));
		} else {
			require_once(DIR_SYSTEM . 'library/exceptionizer.php');
		}
	    $exceptionizer = new PHP_Exceptionizer(E_ALL);
	    try {
			$files = array_diff(scandir($dir), array('.','..'));
			foreach ($files as $file) {
			  (is_dir("$dir/$file")) ? $this->delTree("$dir/$file") : unlink("$dir/$file");
			}
			return rmdir($dir);
			$status = true;
	    }  catch (E_WARNING $e) {
	       	$status = false;
	    }
	}
	private function getDelFiles($dir, $ext = "*", $exp = array()) {
		$files = Array();
		if (function_exists('modification')) {
			require_once(modification(DIR_SYSTEM . 'library/exceptionizer.php'));
		} else {
			require_once(DIR_SYSTEM . 'library/exceptionizer.php');
		}
        $exceptionizer = new PHP_Exceptionizer(E_ALL);
		try {
		    $handle = opendir($dir);
		    $subfiles = Array();
		    while (false !== ($file = readdir($handle))) {
		      if ($file != '.' && $file != '..') {
		        if (is_dir($dir."/".$file)) {

		          $subfiles = $this->getDelFiles($dir."/".$file, $ext);
	              $this->delTree($dir."/".$file);
		          $files = array_merge($files,$subfiles);
		        } else {
			        $flie_name = $dir."/".$file;
			        $flie_name = str_replace("//", "/",$flie_name);
			        if ((substr($flie_name, strrpos($flie_name, '.')) == $ext) || ($ext == "*")) {
						if (!in_array($file, $exp)) {
							$files[] = $flie_name;
						}
					}
		        }
		      }
		    }
		    closedir($handle);
			$status = true;
		}  catch (E_WARNING $e) {
            $status = false;
		}
	    return $files;
	}


	private function DirFilesR($dir) {

		$handle   = opendir($dir);
		$files    = Array();
		$subfiles = Array();
		while (false !== ($file = readdir($handle))) {

			if ($file != "." && $file != "..") {
				$dir_file_name = str_replace("//", "/", $dir . "/" . $file);

				if (is_dir($dir_file_name)) {
					$subfiles = $this->DirFilesR($dir_file_name);
					$files = array_merge($files, $subfiles);

				} else {
					$files[] = $dir_file_name;
				}
			}
		}

		closedir($handle);
		return $files;
	}

  	private function json_error() {

		$error = json_last_error();

		if ($error != JSON_ERROR_NONE) {
			switch ($error) {
			        case JSON_ERROR_NONE:
			           $this->log->write('None error');
			        break;
			        case JSON_ERROR_DEPTH:
			            $this->log->write('Jet cache: Maximum stack depth reached');
			        break;
			        case JSON_ERROR_STATE_MISMATCH:
			        	 $this->log->write('Jet cache: Incorrect discharges or mode mismatch');
			        break;
			        case JSON_ERROR_CTRL_CHAR:
			        	 $this->log->write('Jet cache: Invalid control character');
			        break;
			        case JSON_ERROR_SYNTAX:
			        	 $this->log->write('Jet cache: Syntax error, incorrect JSON');
			        break;
			        case JSON_ERROR_UTF8:
			        	 $this->log->write('Jet cache: Incorrect UTF-8 characters, possibly incorrectly encoded');
			        break;
			        default:
			        	 $this->log->write('Jet cache: Unknow error');
			        break;
			}
		}

    }

	private function mkdirs($pathname, $index = FALSE, $mode = 0777) {
		$flag_save = false;
		$path_file = dirname($pathname);
		$name_file = basename($pathname);
		if (is_dir(dirname($path_file))) {
		} else {
			$this->mkdirs(dirname($pathname), $index, $mode);
		}
		if (is_dir($path_file)) {
			if (@file_exists($path_file)) {
				$flag_save = true;
			}
		} else {
			@umask(0);
			if (@!file_exists($path_file)) {
				@mkdir($path_file, $mode);
			}
			if (@file_exists($path_file)) {
				$flag_save = true;
			}
			if ($index) {
				$accessFile = $path_file . "/" . $name_file;

				@touch($accessFile);
				$accessWrite = @fopen($accessFile, "wb");
				@fwrite($accessWrite, 'Access denied');
				@fclose($accessWrite);
				if (@file_exists($accessFile)) {
					$flag_save = true;
				} else {
					$flag_save = false;
				}
			}
		}
		return $flag_save;
	}
}
}
