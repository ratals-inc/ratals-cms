<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

//Mark As Read
if(isset($_POST['id']) && !empty($_POST['id']) && $_POST['type'] == 'markAdRead')
{
	$message_row_id = $_POST['id'];
	
	$results->getUpdateRecord(__LINE__, __FILE__, 'notices', '`status` = ?', 'WHERE `id` = ?', [2, $message_row_id]);
	
	echo "1";
	exit;
}