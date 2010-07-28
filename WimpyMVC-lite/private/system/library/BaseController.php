<?php

abstract class BaseController {
	protected $log;
	protected $req_key;
	protected $template;
	protected $cachable = FALSE;
	
	abstract protected function generic ();
	
	final public function execute () {
		$this->generic();
	}
	final public function setCachable ($boolean) {
		$this->cachable = $boolean;
	}
	final public function isCachable () {
		return $this->cachable;
	}
	protected function loadView (){
		$view = new View($this->req_key,$this->template);
		$viewStr = $view->toString();
		$pagecontent = new ModelAndView($viewStr);
		Response::setBuffer($pagecontent->toString());
	}
}