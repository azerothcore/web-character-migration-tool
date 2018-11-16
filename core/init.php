<?php
/*
 Copyright (C) 2017+     AzerothCore <www.azerothcore.org>, released under GNU GPL v2 license: http://github.com/azerothcore/azerothcore-wotlk/LICENSE-GPL2
 * Copyright (C) 2008-2016 TrinityCore <http://www.trinitycore.org/>
 * Copyright (C) 2005-2009 MaNGOS <http://getmangos.com/>
*/

session_start();

$GLOBALS['config'] = array(
	'mysql'    => array(
		'host' 	   => '127.0.0.1',
		'port' 	   => '3306',
		'username' => 'root',
		'password' => 'root',
		'db' 	   => 'auth'
	),

	'remember' => array(
		'cookie_name'   => 'hash',
		'cookie_expiry' => 604800
	),

	'session' => array(
		'session_name' => 'user',
		'token_name'   => 'token'
	),

	# NEED TO CONVERT FROM T_CONFIG.PHP
	# USAGE CONFIG::GET('OLD/GAMEBUILD') WILL OUTPUT 12340
	
	'old' => array(
		'gamebuild' => 12340
	),
);

spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';