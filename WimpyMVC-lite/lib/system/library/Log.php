<?php

class Log {
	private static $instance;
	private $logFilePrefix = 'logfile';
	private $fp = null; 
	
	public static function getInstance () {
		if (!self::$instance) {
			self::$instance = new Log();
		}
		return self::$instance;
	}
	
	private function getLogPath () {
		return LOGS_PATH.'/'.$this->logFilePrefix;
	}
	private function open () {
		// define log file path and name  
	    $lfile = $this->getLogPath();
	    // define the current date (it will be appended to the log file name)  
	    $today = date('Y-m-d');  
	    // open log file for writing only; place the file pointer at the end of the file  
	    // if the file does not exist, attempt to create it  
	    $this->fp = fopen($lfile . '_' . $today . '.txt', 'a') or exit("Can't open $lfile!");
	}
	public function write ($message,$level=3) { // 3 is 'lowest' priority log msg
		if (LOGS_ENABLED && (LOGS_LEVEL == 0 || $level <= LOGS_LEVEL)) {
			// if file pointer doesn't exist, then open log file 
			if (!$this->fp) $this->open();
			// get the time to concat to the log msg
			$time = date('H:i:s'); 
			fwrite($this->fp, "$time # $message\r\n"); 
		}
	}
}
