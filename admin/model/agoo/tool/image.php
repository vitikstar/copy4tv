<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// http://opencartadmin.com © 2011-2017 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class agooModelToolImage extends Controller
{
protected  $Layout_;
protected  $data;

   public function __call($name, array $params)
   {
       	$object = 'ModelToolImage';
       	$data = false;

	    if (!class_exists('ModelToolImage')) {
			$file  = DIR_APPLICATION . 'model/tool/image.php';
			if (function_exists('modification')) {
        		$file = modification($file);
        	}
			if (file_exists($file)) {
				include_once($file);
			}
	    }

		$this->Layout_ =  new $object($this->registry);

		if ($this->config->get('ascp_settings') != '') {
			$this->data['settings_general'] = $this->config->get('ascp_settings');
		} else {
			$this->data['settings_general'] = Array();
		}

       	if (isset($this->data['settings_general']['blog_resize']) && $this->data['settings_general']['blog_resize'] && $name == 'resize' && $this->config->get("blog_work") && !$this->registry->get('admin_work')) {

        	if (isset($params[3])) {
        		$data = $this->resizeme($params[0], $params[1], $params[2], $params[3]);
        	} else {
        	 	$data = $this->resizeme($params[0], $params[1], $params[2]);
        	}

        } else {
        	$data = call_user_func_array(array($this->Layout_ , $name), $params);
        }
        return $data;
   }


	public function resizeme($filename, $width, $height, $type = true, $copy = true)
	{
 		if (!class_exists('PhpThumbFactory')) {
			require_once(DIR_SYSTEM . 'library/image/ThumbLib.php');
		}
        $http_image = getHttpImage($this);
		if ($type) {
			$asaptive_path = "adaptive/";
		} else {
			$asaptive_path = "";
		}


        $dir_image = DIR_IMAGE;

        $ok = false;
		if (!file_exists($dir_image . $filename) || !is_file($dir_image . $filename)) {
			return $ok;
		}

		$info      = pathinfo($filename);
		$extension = $info['extension'];
		$old_image = $filename;
		$new_image = 'cache/'.$asaptive_path . utf8_substr($filename, 0, utf8_strrpos($filename, '.')) . '-' . $width . 'x' . $height . '.' . $extension;
		if (!file_exists($dir_image . $new_image) || (filemtime($dir_image . $old_image) > filemtime($dir_image . $new_image))) {
			$path        = '';
			$directories = explode('/', dirname(str_replace('../', '', $new_image)));
			foreach ($directories as $directory) {
				$path = $path . '/' . $directory;
				if (!file_exists($dir_image . $path)) {
					@mkdir($dir_image . $path, 0777);
				}
			}
				list($width_orig, $height_orig) = getimagesize($dir_image . $old_image);
				if (($width_orig != $width || $height_orig != $height) || !$copy) {
		  			//********* code *************
		  			$thumb = PhpThumbFactory::create($dir_image . $old_image, Array('resizeUp'=> true));
					if ($type) {
						$ok = $thumb->adaptiveResize($width, $height)->save($dir_image . $new_image);
					} else {
						//$ok = $thumb->resize($width, $height)->save($dir_image . $new_image);
                        // opencart standart
						$image = new Image($dir_image . $old_image);
						$image->resize($width, $height);
						$image->save($dir_image . $new_image);
						$ok = true;
					}
		            //********* code *************
				} else {
					$ok =  copy($dir_image . $old_image, $dir_image . $new_image);
				}

			 if ($ok) {

			 return $http_image.$new_image;
			 }
			 else
			 return '';
		} else {
			 return $http_image.$new_image;
		}

    }

	public function resizeavatar($filename, $filename_original, $width, $height, $type = true, $copy = true)
	{
 		if (!class_exists('PhpThumbFactory')) {
			require_once(DIR_SYSTEM . 'library/image/ThumbLib.php');
		}
        $http_image = getHttpImage($this);
        $ok = false;
		if (!file_exists(DIR_IMAGE . $filename) || !is_file(DIR_IMAGE . $filename)) {
			return $ok;
		}

		$info      = pathinfo($filename_original);
		$extension = $info['extension'];
		$old_image = $filename;
        $new_image = $filename_original;

			if (!file_exists(DIR_IMAGE . $new_image) || (filemtime(DIR_IMAGE . $old_image) > filemtime(DIR_IMAGE . $new_image))) {
				$path        = '';
				$directories = explode('/', dirname(str_replace('../', '', $new_image)));
				foreach ($directories as $directory) {
					$path = $path . '/' . $directory;
					if (!file_exists(DIR_IMAGE . $path)) {
						@mkdir(DIR_IMAGE . $path, 0777);
					}
				}

				list($width_orig, $height_orig) = getimagesize(DIR_IMAGE . $old_image);
				if (($width_orig != $width || $height_orig != $height) || !$copy) {
		  			//********* code *************
		  			$thumb = PhpThumbFactory::create(DIR_IMAGE . $old_image, Array('resizeUp'=> true));
					if ($type)
						$ok = $thumb->adaptiveResize($width, $height)->save(DIR_IMAGE . $new_image);
					else
						$ok = $thumb->resize($width, $height)->save(DIR_IMAGE . $new_image);
		            //********* code *************
				} else {
					copy(DIR_IMAGE . $old_image, DIR_IMAGE . $new_image);
					$ok = true;
				}

			 if ($ok)
			 return $http_image.$new_image;
			 else
			 return '';
		}

    }

}
