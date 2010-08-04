<?php

class CacheHelper {
	public static function makeFileName ($file_type) {
		$log = Log::getInstance();
		$cache_file = self::makeFileNameFromUrl($url);
		$cache_file .= CACHE_EXT;
		if ($file_type == "css") {
			$cache_file .= "css";
		} else if ($file_type == "js") {
			$cache_file .= "js";
		} else {
			$cache_file .= "html";
		}
		
		return $cache_file;
	}
	public static function makeFileNameFromUrl () {
		$file_name = $_SERVER["SERVER_NAME"];
		if ($_SERVER["REQUEST_URI"] != "/") {
			$file_name.=$_SERVER["REQUEST_URI"];
		}
		$file_name = str_replace("/","-",$file_name);
		return $file_name;
	}
	public static function findFileFromUrl () {
		$log = Log::getInstance();
		$file_name = self::makeFileNameFromUrl();
		$log->write("CacheHelper > findFileFromUrl - target starts with: ".$file_name);
		// Look through directory for file
		if($directory = opendir(CACHE_PATH)){
			while(FALSE !== ($name = readdir($directory))) {
				if($name != '.' && $name != '..') {
					$startsWith = substr($name,0, strpos($name,CACHE_EXT));
					$log->write("CacheHelper > findFileFromUrl - file starts with: ".$startsWith);
					if($file_name == $startsWith) {
						$full_file_path = $name;
						break;
					}
				}
			}
			closedir($directory);
		}
		$log->write("CacheHelper > findFileFromUrl - filename: ".$full_file_path);
		//$full_file_path = $file_name;
		return $full_file_path;
	}
	public static function saveView ($result,$file_type) {
		$log = Log::getInstance();
		if (!empty($result) && !empty($result)) {
			$filename = self::makeFileName($file_type);
			$log->write("CacheHelper > cacheView - filename: ".$filename);
			self::write($filename,$result);
		} else {
			$log->write("CacheHelper > cacheView - filename: NO CONTENT PRESENT");
		}
	}
	private static function write ($filename,$buffer) {
		$log = Log::getInstance();
	    $fileTitle = CACHE_PATH.'/'.$filename;
		$log->write("CacheHelper > cacheView - filepath: ".$fileTitle);
		//$log->write("CacheHelper > cacheView - text to cache: \n".self::$buffer);
		file_put_contents($fileTitle,$buffer);
	}
}