<?php

class Dispatcher {
	private static $log;
	public static function initialize (){
		if (empty(self::$log)) {
			self::$log = Log::getInstance();
		}
		self::$log->write("REQUEST FROM URL ****************************************** ",1);
		
		// Capture request data
		Request::loadData(); // TODO: SANITIZE STRINGS
		
		// TODO: ReqFilter - Create a request filter to maps keys to specific actions
		//			TODO: ReqFilter - add filter flag in wimpy config
		//			TODO: ReqFilter - add filter mappings to app config
		
		// SET REQUEST KEY
		if(!empty(Request::$get["page"])) {
			$req_key = Request::$get["page"];
			self::$log->write("Dispatcher > initialize :: map: $req_key");
		} else {
			$req_key = DEFAULT_KEY; // Open domain
			self::$log->write("Dispatcher > initialize :: default map: $req_key");
		}
		// SET ACTION NAME
		if(!empty(Request::$get["action"])) {
			$req_action = Request::$get["action"];
			self::$log->write("Dispatcher > initialize :: action: $req_action");
		} else {
			$req_action = DEFAULT_ACTION;
			self::$log->write("Dispatcher > initialize :: default action: $req_action");
		}
		// SET PARAM
		if(!empty(Request::$get["param"])) {
			$req_param = Request::$get["param"];
			self::$log->write("Dispatcher > initialize :: param: $req_param");
		} else {
			$req_param = NULL;
			self::$log->write("Dispatcher > initialize :: param: NONE");
		}
		self::process($req_key,$req_action,$req_param);
		self::$log->write("ENDING REQUEST ****************************************** ");
		exit();
	}
	public static function process($req_key,$req_action=NULL,$req_param=NULL) {
		$result = self::load($req_key,$req_action,$req_param);
		if (!empty($result) && strlen($result) > 0) {
			echo $result;
		}
		Model::clearLocalValues();
	}
	public static function load($req_key,$req_action=DEFAULT_ACTION,$req_param=NULL) {
		self::$log->write("Dispatcher > load :: args: $req_key - $req_action - $req_param",1);
		
		self::$log->write("Dispatcher > load :: request method is ".$_SERVER['REQUEST_METHOD'],1);

		if(CACHE_ENABLED && ($_SERVER['REQUEST_METHOD'] == "GET")) {
			self::$log->write("Dispatcher > load :: caching is on",1);
			$cachedView = self::loadFromCache($req_key,$req_action,$req_param);
			$result = $cachedView[0];
			$file_type = $cachedView[1];
		} else {
			self::$log->write("Dispatcher > load :: caching is not available",1);
		}
		if (empty($result) ||  strlen($result) < 1) { 
			self::$log->write("Dispatcher > load :: loading from controller",1);
			// viewInfo will be .tpl to consume, file type
			$viewInfo = self::loadFromController($req_key,$req_action,$req_param);
			
			// view and model to send to ViewBuilder
			$tpls = $viewInfo[0];
			$model_list = Model::getAllValues();
			
			// result will be a string from ViewBuilder->getFinalView
			$viewBuilder = ViewBuilderFactory::getViewBuilder();
			$result = $viewBuilder->getFinalView($tpls,$model_list);
			
			// saves result view into a file using a url.cache.type naming convention
			if (CACHE_ENABLED && (!empty($result) ||  strlen($result) > 0)) {
				$file_type = $viewInfo[1];
				CacheHelper::saveView($result,$file_type);
			}
		}
		
		// Set content type based on file type from viewInfo (ie. css is text/css) 
		if ($file_type == "css") {
			header("Content-type: text/css");
			self::$log->write("CACHE FILE IS CSS",2);
		} else if ($file_type == "js") {
			header("Content-type: text/javascript");
			self::$log->write("CACHE FILE IS JS",2);
		}
		
		return $result; // returns a string from smarty
	}
	private static function loadFromCache ($req_key,$req_action,$req_param) {
		// TODO: perform lookup for file with name matching everything minus the extension
		$cache_file = CacheHelper::findFileFromUrl();
		$cache_file_path = CACHE_PATH.'/'.$cache_file_path;
		self::$log->write("Dispatcher > loadFromCache :: filename: $cache_file");
		self::$log->write("Dispatcher > loadFromCache :: filename w path: $cache_file_path",1);
		$time = time();
		$file_exists = (empty($cache_file_path) ? FALSE : file_exists($cache_file_path));
		if ($file_exists) {
			$time_diff = $time - filemtime($cache_file_path);
			$time_diff_ok = ($time_diff < CACHE_LIMIT) ? TRUE : FALSE;
			self::$log->write("Time diff of $cache_file is: $time_diff",2);
		} else {
			$time_diff_ok = FALSE;
		}
		if ($time_diff_ok) {
			self::$log->write("Dispatcher > loadFromCache :: Retrieving $cache_file",1);
			$content = @file_get_contents($cache_file_path);
			$endsWith = substr($cache_file, strpos($name,CACHE_EXT));
			return array($content,$endsWith);
		} else {
			if ($file_exists) {
				self::$log->write("Dispatcher > loadFromCache :: Cache is too old",1);
			} else {
				self::$log->write("Dispatcher > loadFromCache :: Cache file not found",1);
			}
			return array(NULL,'html');
		}
	}
	private static function loadFromController ($req_key,$req_action,$req_param) {
		$buffer = "";
		
		// GET CONTROLLER
		$objArr = Config::getController($req_key);
		$filename = CONTROLLER_PATH.'/'.$objArr[0].'.php';
		if (file_exists($filename)) {
			self::$log->write("Dispatcher > loadController > class: $objArr[0]",1);
			require_once($filename);
			$obj = new $objArr[0];
		} else if ($req_key != "error") { // No controller found
			self::$log->write("Dispatcher > loadController > key: $req_key has no controller",1);
			$buffer = self::load("error");
		} else { 
			self::$log->write("Dispatcher > loadController > cannot load: $req_key");
			self::$log->write("FAILING REQUEST ****************************************** ");
			die("Request cannot be processed"); 
		}
		
		if (!empty($obj)) {
			$isCachable = $objArr[1];
			if(strpos($req_action,'_') != FALSE){
				$action = Inflector::toCamelCase($req_action,'_');
			} else { 
				$action = $req_action; 
			}
			if (method_exists($obj,$action)) { 
				if(!empty($req_param)) { // If params are passed
					self::$log->write("Dispatcher > loadFromController -- param string: $req_param");
					
					$req_param_list = array();
					$req_param_list = explode("/",$req_param);
					for($i=0; $i < sizeof($req_param_list); ++$i) {
						self::$log->write("Dispatcher > loadFromController -- param array > value: ".$req_param_list[$i]);
					}

					call_user_func_array(array($obj, $action),$req_param_list);
					self::$log->write("Dispatcher > loadController :: called $req_key > $action({$req_param})");
				} else { // Call function with no params
					self::$log->write("Dispatcher > loadController :: calling {$req_key}->{$action}()");
					$obj->$action();
				}
			} else { // If method does not exist 
				self::$log->write("Dispatcher > loadController :: {$req_key}->{$action}() does not exist");
				//$buffer = self::load("error");
			}
			
			// get view info
			$buffer = $obj->getViewInfo();
			self::$log->write("view is ".$buffer[0]." ======================================");
			$obj = null;
		}
		// Return view info for smarty to consume
		return $buffer;
	}
}
