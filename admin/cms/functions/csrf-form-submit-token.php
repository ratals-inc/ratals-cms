<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/csrf-form-submit-token.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/csrf-form-submit-token.php');
}
else
{
	if(!function_exists('csrfFormSubmitToken'))
	{
		function csrfFormSubmitToken()
		{
			if(!isset($_SESSION['csrf_token']))
			{
				try
				{
					//Preferred: php cryptographically secure.
					$_SESSION['csrf_token'] = bin2hex(random_bytes(16)); //32 chars
				}
				catch(Exception $e)
				{
					//Fallback if php cryptographically secure fails.
					$random_csrf = '0123456789abcdefghijklmnopqrstuvwxyz';
					$_SESSION['csrf_token'] = substr(str_shuffle($random_csrf), 0, 32);
				}
				
				return $_SESSION['csrf_token'];
			}
		}
	}
}