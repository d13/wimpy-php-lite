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
		
		$this->template_dir = array(VIEW_PATH);
        $this->compile_dir = SMARTY_COMPILE_PATH;
        $this->plugins_dir = array(SMARTY_PLUGINS_DIR);
        $this->cache_dir = SMARTY_CACHE_PATH;
        $this->config_dir = SMARTY_VIEW_CFG_PATH;
		
		foreach($modelMap as $key => $value) {
			$smarty->assign($key,$value);
		}
		
		$result =  $smarty->fetch($tplStr);
		
		return $result;
	}
}