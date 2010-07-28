<?php

class Inflector {
	public static function capitalize ($string) {
		return ucfirst($string);
	}
	public static function capitalizeAll ($string) {
		return ucwords($string);
	}
	public static function toCamelCase ($string,$separator="_") {
		$log = Log::getInstance();
		$log->write("toCamelCase called");
		$final_string = "";
		
		$word_list = array();
		$word_list = explode($separator,$string);
		
		for($i=0; $i < sizeof($word_list); ++$i) {
			if ($i == 0) {
				$final_string = $word_list[$i];
			} else {
				$final_string .= self::capitalize($word_list[$i]);
			}
		}
		$log->write("toCamelCase completed: $final_string");
		return $final_string;
	}
}