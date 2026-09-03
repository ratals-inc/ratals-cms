<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/core/session-check-frontend.php'))
{
	include(INSTALLATION_ROOT.'/hooks/core/session-check-frontend.php');
}
else
{
	require_once(INSTALLATION_ROOT.'/admin/cms/functions/http-or-https.php');
	require_once(INSTALLATION_ROOT.'/admin/cms/frontend/requested-url.php');
	require_once('session-setting.php');
	
	if(session_status() === PHP_SESSION_NONE)
	{
		session_start();
	}
	
	if(!isset($_SESSION['last_regenerated']))
	{
		session_regenerate_id(true);
		$_SESSION['last_regenerated'] = time();
	}
	else
	{
		//$php_session_regeneration_time varaible is set in /core/session-setting.php
		if(time() - $_SESSION['last_regenerated'] >= $php_session_regeneration_time)
		{
			session_regenerate_id(true);
			$_SESSION['last_regenerated'] = time();
		}
	}
	
	if(!isset($site_id))
	{
		require_once('config.php');
	}
}