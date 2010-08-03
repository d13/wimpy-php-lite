<?php

class DAOFactory {
	private static $instance;
	private static $log;
	
	public static function getInstance () {
		if (!self::$instance) {
			self::$instance = new DAOFactory();
			self::$log = Log::getInstance();
		}
		return self::$instance;
	}
	public static function getDAO ($domain) {
		$dao_ref = Config::getDao($domain);
		$dao_int_path = DAO_PATH."/$dao_ref[0].php";
		$dao_path = DAO_PATH."/$dao_ref[1].php";
		if(file_exists($dao_int_path) && file_exists($dao_path)) {
			self::$log->write("DAOFactory Loading DAO: $dao_ref[0]");
			require_once($dao_int_path);
			require_once($dao_path);
			return new $class;
		}
		return NULL;
	}
}