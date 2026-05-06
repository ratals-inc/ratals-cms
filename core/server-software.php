<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/server-software.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/core/server-software.php');
}
else
{
	$server_software = strtolower($_SERVER['SERVER_SOFTWARE']);
	$nginx_warning = '';
	
	//Check if server is running Apache or LiteSpeed
	if(stripos($server_software, 'apache') !== false || stripos($server_software, 'litespeed') !== false)
	{
		//Check if .htaccess override is working (only relevant for Apache or LiteSpeed)
		if(empty($_SERVER['RATALSEERP_ALLOWOVERRIDE_TEST']))
		{
			echo '<p style="text-align: center;">Error: "AllowOverride All" is not enabled. Ratals requires .htaccess overrides to be allowed.</p>';
			exit();
		}
		
		//If running Apache, ensure version is 2.4 or greater
		if(stripos($server_software, 'apache') !== false && function_exists('apache_get_version'))
		{
			$version = apache_get_version();
			if(preg_match('/\d+\.\d+/', $version, $matches))
			{
				if(version_compare($matches[0], '2.4', '<'))
				{
					echo '<p style="text-align: center;">Ratals requires Apache 2.4 or greater. Detected version: '.$matches[0].'</p>';
					exit();
				}
			}
			else
			{
				//Fallback if version cannot be determined
				echo '<p style="text-align: center;">Unable to determine the Apache version. Ratals requires Apache 2.4 or higher, or LiteSpeed.</p>';
				exit();
			}
		}
	}
	//Check if server is running Nginx
	elseif(stripos($server_software, 'nginx') !== false)
	{
		$nginx_warning = '<div style="color: red; text-align: center; padding-bottom: 12px"><strong>Important - Nginx Web Server Detected</strong><br>Before entering your information and installing the software below, you must configure your Nginx rewrite rules on your server. See the installation documentation <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-on-nginx/" target="_blank">here</a>. Once the rules are configured, you can ignore this message and proceed with the installation.</div>';
	}
	//Other servers
	else
	{
		echo '<p style="text-align: center;">Ratals requires a supported web server: Apache, LiteSpeed, or Nginx. Detected: '.$_SERVER['SERVER_SOFTWARE'].'</p>';
		exit();
	}
}