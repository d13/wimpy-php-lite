<?php

class SmartyHelper {
	public static function getFinalView ($tpls,$modelList) {
		$log = Log::getInstance();
		require(SMARTY_PATH.'/Smarty.class.php');
		$tplStr = $tpls;
		if (strpos($tplStr,"|") > -1) {
			$tplStr = "extends:$tplStr";
		}
		$log->write("SmartyHelper -> target view is: $tplStr");
		$smarty = new Smarty;
		$smarty->debugging = false;
		$smarty->caching = false;
		
		foreach($modelList as $key => $value) {
			$smarty->assign($key,$value);
		}
		
		$result =  $smarty->fetch($tplStr);
		
		return $result;
	}
}