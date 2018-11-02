<?php
if (!isset($_FLY)) {
	include 'Fly.Core.php';
}
FlyIncludeRegister('FLY.FUNCTIONS');

date_default_timezone_set("America/Chicago");
if (!function_exists('str_lreplace')) {
	function str_lreplace($search, $replace, $subject) {
		$pos = strrpos($subject, $search);
		if($pos !== false) {
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
	}
}

if (!function_exists('unlink_dir')) {
	function unlink_dir($dir) { 
		if (is_dir($dir)) { 
			$objects = scandir($dir); 
			foreach ($objects as $object) { 
				if ($object != "." && $object != "..") { 
					if (filetype($dir."/".$object) == "dir") {
						unlink_dir($dir."/".$object);
					} else {
						unlink($dir."/".$object);
					} 
				} 
			} 
			reset($objects); 
			rmdir($dir); 
		} 
	}
}

if(!function_exists('fnmatch')) {
	function fnmatch($pattern, $string) {
		return preg_match("#^".strtr(preg_quote($pattern, '#'), array('\*' => '.*', '\?' => '.'))."$#i", $string);
	}
}

?>