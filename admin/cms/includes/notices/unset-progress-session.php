<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 4));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

unset($_SESSION['current_update_log']);
echo json_encode(['status' => 'ok']);