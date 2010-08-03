<?php

class Config {
	private static $config;
	public static function load(){
		define("BASE_DIR",$_SERVER['DOCUMENT_ROOT']);
		$config_list = array('/wimpy.config.json','/application.config.json');
		foreach ($config_list as $config_file) {
			$config_fp = BASE_DIR.'/private'.$config_file;
			if (file_exists($config_fp)) {
				$items = array();
				self::$config = json_decode(file_get_contents($config_fp), TRUE);
				foreach (self::$config as $key => $value) {
					if (strpos($key, "_path") > -1) {
						$def_value = BASE_DIR.self::$config[$key];
					} else {
						$def_value = self::$config[$key];
					}
					if (strpos($key, "system.") > -1) {
						$def_name = strtoupper(substr($key,7));
						define($def_name,$def_value);
					}
				}
			}
			else { die('Configuration missing'); }
		}
	}
	public static function get($key) {
		if(empty(self::$config)) {
			self::load();
		}
		$def_value = self::$config[$key];
		if ((strpos($key,'_path') > -1)&&(strpos($key, BASE_DIR) === FALSE)) {
			$def_value = BASE_DIR.$def_value;
		}
		return $def_value;
	}
	public static function getSysProp ($key) {
		return self::get("system.$key");
	}
	public static function getDataProp ($key) {
		return self::get("datastore.$key");
	}
	public static function getController ($key) {
		return self::get("controllers.$key");
	}
	public static function getView ($key) {
		return self::get("views.$key");
	}
	public static function getDao ($key) {
		return self::get("dao.$key");
	}
}