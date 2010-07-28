<?php

class Model {
	// Local Vars
	private static $vars = array();
	public static function setLocalValue ($key,$value) {
		self::$vars[$key] = $value;
	}
	public static function getLocalValue ($key) {
		return(isset(self::$vars[$key])) ? self::$vars[$key] : "";
	}
	public static function deleteLocalValue ($key) {
		unset(self::$vars[$key]);
	}
	public static function getAllLocalValues () {
		return self::$vars;
	}
	public static function clearLocalValues () {
		self::$vars = NULL;
		self::$vars = array();
	}
	
	// Session Vars
	public static function setGlobalValue ($key,$value) {
		$_SESSION['global'][$key] = $value;
	}
	public static function getGlobalValue ($key) {
		return (isset($_SESSION['global'])) ? $_SESSION['global'][$key] : "";
	}
	public static function getAllGlobalValues () {
		return (isset($_SESSION['global'])) ? $_SESSION['global'] : NULL;
	}
	public static function deleteGlobalValue ($key) {
		if(isset($_SESSION['global']) && isset($_SESSION['global'][$key])) {
			unset($_SESSION['global'][$key]);
		}
	}
	public static function clearGlobalValues () {
		if(isset($_SESSION['global'])) {
			unset($_SESSION['global']);
		}
	}
	
	// All Vars
	public static function clearAllValues () {
		self::clearLocalValues();
		self::clearGlobalValues();
	}
	
	public static function getAllValues () { //TODO: What happens with duplicates?
		return array_merge(self::$vars,$_SESSION['global']);
	}
} 
