<?php

class View {
	private $log;
	private $templateName = DEFAULT_TEMPLATE;
	private $viewPath;
	private $templateStr;
	
	public function __construct ($req_key,$template = NULL) {
		$this->log = Log::getInstance();
		
		$this->viewPath=Config::getViewPath($req_key);
		
		if (!empty($template)){
			$this->templateName = $template;
		}
	}
	
	public function toString () {
		$this->log->write('View > toString(): '.$this->templateName);
		
		//prepare the template
		$templateStr = @file_get_contents(Config::getViewPath($this->templateName));
		if (strpos($templateStr,'${template.header}')) {
			$content = @file_get_contents(Config::getViewPath('template.header'));
			$templateStr = str_replace('${template.header}', $content, $templateStr);
		}
		if (strpos($templateStr,'${template.navigation}')) {
			$content = @file_get_contents(Config::getViewPath('template.nav'));
			$templateStr = str_replace('${template.navigation}', $content, $templateStr);
		}
		if (strpos($templateStr,'${template.footer}')) {
			$content = @file_get_contents(Config::getViewPath('template.footer'));
			$templateStr = str_replace('${template.footer}', $content, $templateStr);
		}
		
		//add the raw content
		if (strpos($templateStr,'${template.content}')) {
			$content = @file_get_contents($this->viewPath);
			$templateStr = str_replace('${template.content}', $content, $templateStr);
		}
		
		return $templateStr;
	}
	public function render () {
		echo $this->toString();
	}
}