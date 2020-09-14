<?php
/* All rights reserved belong to the module, the module developers http://opencartadmin.com */
// https://opencartadmin.com © 2011-2019 All Rights Reserved
// Distribution, without the author's consent is prohibited
// Commercial license
class iBlog
{
	private static $mode = 'FULL';
	private static $short = true;
	private $adirs = array();

	public static function searchdir($path, $mode = 'self::$mode', $myself = false, $maxdepth = -1, $d = 0)
	{
		if (substr($path, strlen($path) - 1) != '/') {
			$path .= '/';
		}
		$dirlist = array();
		if ($mode != "FILES") {
			if ($d != 0 || $myself)
				$dirlist[] = $path;
		}
		if ($handle = opendir($path)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..') {
					$full_path_file = $path . $file;
					if (!self::$short) {
						$file = $full_path_file;
					} else {
						$file = $file;
					}
					if (!is_dir($full_path_file)) {
						if ($mode != "DIRS") {
							$dirlist[] = $file;
						}
					} elseif ($d >= 0 && ($d < $maxdepth || $maxdepth < 0)) {
						$dirlist[] = $file;
						$result    = self::searchdir($full_path_file . '/', $mode, $myself, $maxdepth, $d + 1);
					}
				}
			}
			closedir($handle);
		}
		if ($d == 0) {
			natcasesort($dirlist);
		}
		return ($dirlist);
	}
}
