<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__));
}

if(file_exists(INSTALLATION_ROOT.'/hooks/core/session-check-admin.php'))
{
	include(INSTALLATION_ROOT.'/hooks/core/session-check-admin.php');
}
else
{
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
		//$php_session_regeneration_time varaible is set in /load-sites/session-setting.php
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
	
	//Get the path part and collapse multiple slashes to one slash.
	$admin_cleaned_path = preg_replace('#/+#', '/', $_SERVER['REQUEST_URI']);
	
	//Get Requested admin URL.
	$admin_url_check = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$admin_cleaned_path;
	
	//Block direct access to the admin directory if they try going to /admin or /admin/*.
	if($domain.'/'.$path_url === $domain.'/admin' || strpos($admin_url_check, $domain.'/admin/') !== false)
	{
		http_response_code(403);
		die('Forbidden');
	}
	
	if(!isset($_SESSION['admin_directory']))
	{
		header('Location: '.$domain.'/');
		exit();
	}
}