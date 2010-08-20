<?php

class DataFactory {
	// TODO: Add Data Connection functionality
	public static function getDataSource ($key) {
		$log = Log::getInstance();
		$dao_ref = Config::getDataProp($key);
		$dao_int_path = DAO_PATH."/$dao_ref[0].php";
		$dao_path = DAO_PATH."/$dao_ref[1].php";
		if(file_exists($dao_int_path) && file_exists($dao_path)) {
			$log->write("DAOFactory Loading DAO: $dao_ref[0]");
			require_once($dao_int_path);
			require_once($dao_path);
			return new $dao_ref[1];
		}
		return NULL;
	}
	private static function getMysqli () {}
	//private static function getMysqli () {}
	//private static function getMysqli () {}
	//private static function getMysqli () {}
	//private static function getMysqli () {}
}