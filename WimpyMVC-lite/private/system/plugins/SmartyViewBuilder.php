<?php

class SmartyViewBuilder implements ViewBuilder {
	public function getFinalView ($tplName,$modelMap) {
		$log = Log::getInstance();
		require(SMARTY_PATH.'/Smarty.class.php');
		$tplStr = $tplName;
		if (strpos($tplStr,"|") > -1) {
			$tplStr = "extends:$tplStr";
		}
		$log->write("SmartyHelper -> target view is: $tplStr");
		$smarty = new Smarty;
		$smarty->debugging = false;
		$smarty->caching = false;
		
		foreach($modelMap as $key => $value) {
			$smarty->assign($key,$value);
		}
		
		$result =  $smarty->fetch($tplStr);
		
		return $result;
	}
}