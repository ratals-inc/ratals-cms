<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/core/session-setting.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/core/session-setting.php');
}
else
{
	ini_set('session.use_only_cookies', '1');
	ini_set('session.use_strict_mode', '1');
	
	//Setting $php_session_regeneration_time to 600 seconds (10 minutes) will cause PHP to generate a new session ID (PHPSESSID) every 10 minutes. This helps mitigate session fixation attacks by ensuring the session ID is periodically regenerated.
	$php_session_regeneration_time = 600;
	
	//Setting $cookie_session_max to 0 means the session cookie will expire when the browser is closed.
	$cookie_session_max = 0;
	
	$is_https = (
		(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
		(!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ||
		(!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') ||
		(!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443)
	);
	
	session_set_cookie_params([
		'lifetime' => $cookie_session_max,
		'domain' => $homepage_url,
		'path' => '/',
		'secure' => $is_https,
		'httponly' => true,
		'samesite' => 'Lax'
	]);
}