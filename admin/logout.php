<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/logout.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/logout.php');
}
else
{
	$admin_directory = $_SESSION['admin_directory'];
	session_unset();
	session_destroy();
	header("Location: /".$admin_directory."/?login=logout");
	exit;
}