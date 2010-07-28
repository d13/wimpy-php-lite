<?php

//echo ($_SERVER['DOCUMENT_ROOT']);

// Open session & check for flag
session_start();
if (!isset($_SESSION['initiated'])) {
	session_regenerate_id();
	$_SESSION['initiated'] = TRUE;
}

// Include Library
array_walk(glob('./system/library/*.php'),create_function('$v,$i', ' return require_once($v);'));

// Load properties
Config::load();

// Start request
Dispatcher::initialize();