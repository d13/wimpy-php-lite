<?php

class CacheHelper {
	public static function makeFileName ($req_key,$req_action,$req_param_list) {
		$log = Log::getInstance();
		$cache_file = $req_key;
		if (!empty($req_action)) {
			$cache_file .= "-".$req_action;
		}
		if (!empty($req_param_list)) {
			$req_param = implode("-", $req_param_list);
			$req_param = substr($req_param, 0,-1); // Get rid of extra "-"
			$log->write("CacheHelper > makeFileName - imploded params: ".$req_param);
			$cache_file .= "-".$req_param;
		}
		$cache_file .= CACHE_EXT;
		if (strpos($cache_file,"get_styles") > -1) {
			$cache_file .= "css";
		} else if (strpos($cache_file,"get_scripts") > -1) {
			$cache_file .= "js";
		} else {
			$cache_file .= "txt";
		}
		
		return $cache_file;
	}
	public static function makeFileNameFromUrl ($req_key,$req_action=NULL,$req_param=NULL) {
		if (empty($req_param)) {
			$req_param_list = NULL;
		} else {
			$req_param_list = array();
			$req_param_list = explode("/",$req_param);
		}
		return self::makeFileName($req_key, $req_action, $req_param_list);
	}
	public static function saveView ($buffer,$key,$action=NULL,$params=NULL) {
		$log = Log::getInstance();
		if (!empty($key) && !empty($key)) {
			$filename = self::makeFileName($key, $action, $params);
			$log->write("CacheHelper > cacheView - filename: ".$filename);
			self::write($filename,$buffer);
		} else {
			$log->write("CacheHelper > cacheView - filename: NO KEY PRESENT");
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