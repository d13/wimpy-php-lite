<?php

class ViewBuilderFactory {
	public static function getViewBuilder () {
		$log = Log::getInstance();
		$vb_class = VIEW_BUILDER_CLASS;
		$vb_class_path = PLUGINS_PATH."/$vb_class.php";
		if(file_exists($vb_class_path)) {
			$log->write("ViewBuilderFactory Loading ViewBuilder: $vb_class");
			require_once($vb_class_path);
			return new $vb_class;
		} else {
			return NULL;
		}
	}
}