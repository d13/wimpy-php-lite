<?php

class ModelAndView {
	private $log;
	private $strView;
	public function __construct ($view) {
		$this->log = Log::getInstance();
		$this->strView = $view;
		// Match Variable Patterns
		preg_match_all('/\$\{([^}]+)\}/', $this->strView,$list); // $list is the result
		
		// Get variable value and replace
		for($i = 0; $i < sizeof($list[0]); ++$i) {
			$var = $list[0][$i];
			$key = $list[1][$i];
			$val = $this->getValue($key);
			$this->strView = str_replace($var, $val, $this->strView);
		}
	}
	private function getValue($var) {
		$context = substr($var,0,strpos($var,"."));
		$result = "";
		if (strpos($var,":LOOP") > -1) {
			$isLoop = TRUE;
			$key = substr($var,(strpos($var,".")+1),(strpos($var,":")-(strpos($var,".")+1)));
		} else {
			$key = substr($var,(strpos($var,".")+1));
		}
		
		//$this->log->write("ModelAndView > getValue(): context is $context");
		if ($context == "local") {
			$result = Model::getLocalValue($key);
		} else if ($context == "global") {
			$result = Model::getGlobalValue($key);
		} else {
			$result = "";
		}
		if (!empty($isLoop) && $isLoop) {
			$snippet = substr($var,(strpos($var,"(")+1),(strripos($var,")")-(strpos($var,"(")+1)));
			$finalResult = "";
			foreach ($result as $item) {
				$finalSnippet = $snippet;
				foreach ($item as $key => $value) {
					$snipKey = '$'.strtoupper($key);
					$finalSnippet = str_replace($snipKey, $value, $finalSnippet);
				}
				$finalResult .= $finalSnippet;
			}
			$result = $finalResult;
		}
		return $result;
	}
	public function render () {
		echo $this->strView;
	}
	public function toString () {
		return $this->strView;
	}
}