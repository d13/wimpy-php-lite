<?php

class Request {
	private static $log;
	public static $post = array();
	public static $get = array();
	
	public static function loadData () {
		self::$log = Log::getInstance();
		//self::$log->write("Request > loadData : started");
		
		if (!empty($_POST)) {
			foreach ($_POST as $key => $value) {
				//self::$log->write("Request > loadData : $key - $value");
				self::$post[$key] = self::clean($value);
			}
			unset($_POST);
		} else {
			//self::$log->write("Request > loadData : no post data");
		}
		if (!empty($_GET)) {
			foreach ($_GET as $key => $value) {
				//self::$log->write("Request > loadData : $key - $value");
				if ($key == "page" || $key == "action" || $key == "param") {
					self::$get[$key] = strtolower(self::clean($value));
				}
			}
			unset($_GET);
		} else {
			//self::$log->write("Request > loadData : no get data");
		}
	}
	private static function clean ($str) {
		$strVal = $str;
		
		return $strVal;
	}
}