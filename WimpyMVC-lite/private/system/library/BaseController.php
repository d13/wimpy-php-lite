<?php

abstract class BaseController {
	protected $log;
	protected $req_key;
	protected $template;
	protected $pagecontent;
	protected $cachable = FALSE;

	public function __construct () {
		$this->log = Log::getInstance();
	}
	
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
	// send views to use file type to use 
	public function getViewInfo (){
		$viewObj = Config::getView($this->req_key);
		$viewData = array($viewObj["extends"],$viewObj["view"]);
		$viewStr= implode("|",$viewData);
		$viewInfo = array($viewStr,$viewObj["type"]);
		$this->log->write("VIEW IS: ".$viewObj["view"]." +++++++++++++++++++++++++++++++++++++++++++++");
		return $viewInfo;
	}
}