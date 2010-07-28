<?php

class ServiceManager {
	public static function getService ($srvRef) {
		$log = Log::getInstance();
		$class = ucfirst($srvRef).'Service';
		$file_path = SERVICE_PATH."/$class.php";
		if(file_exists($file_path)) {
			$log->write("ServiceManager Loading Class: $class");
			require_once($file_path);
			return new $class;
		}
		return NULL;
	}
}