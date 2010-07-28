<?php

class BaseEntity {
	protected $log;
	private $id = 0;
	
	public function __construct() {
		$this->log = Log::getInstance();
	}
}
