<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/core/installation-paths.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/core/installation-paths.php');
}
else
{
	if(!defined('INSTALLATION_URL_PATH'))
	{
		$document_root = realpath($_SERVER['DOCUMENT_ROOT']);
		$installation_root = realpath(INSTALLATION_ROOT);
	
		if($document_root !== false && $installation_root !== false)
		{
			//Normalize Windows directory separators.
			$document_root = str_replace('\\', '/', $document_root);
			$installation_root = str_replace('\\', '/', $installation_root);
	
			//Determine the installation path relative to the web document root.
			if(strpos($installation_root, $document_root) === 0)
			{
				$installation_url_path = substr($installation_root, strlen($document_root));
			}
			else
			{
				$installation_url_path = '';
			}
		}
		else
		{
			$installation_url_path = '';
		}
	
		//Remove trailing slash so URLs can consistently begin with a forward slash.
		$installation_url_path = rtrim($installation_url_path, '/');
	
		define('INSTALLATION_URL_PATH', $installation_url_path);
	}
}