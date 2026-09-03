<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/http-or-https.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/http-or-https.php');
}
else
{
	function getRequestScheme()
	{
		if(!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
		{
			return 'https';
		}
	
		if(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']))
		{
			$forwarded_protocols = explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO']);
			$forwarded_protocol = strtolower(trim($forwarded_protocols[0]));
	
			if($forwarded_protocol === 'http' || $forwarded_protocol === 'https')
			{
				return $forwarded_protocol;
			}
		}
	
		if(!empty($_SERVER['REQUEST_SCHEME']))
		{
			$request_scheme = strtolower($_SERVER['REQUEST_SCHEME']);
	
			if($request_scheme === 'http' || $request_scheme === 'https')
			{
				return $request_scheme;
			}
		}
	
		if(isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
		{
			return 'https';
		}
	
		return 'http';
	}
}