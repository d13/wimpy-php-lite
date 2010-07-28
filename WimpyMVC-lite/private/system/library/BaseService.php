<?php

class BaseService {
	protected $log;
	public function __construct () {
		$this->log = Log::getInstance();
	} 
}