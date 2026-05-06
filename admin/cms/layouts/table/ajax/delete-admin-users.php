<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-admin-users.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-admin-users.php');
}
else
{
	//Delete records
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-admin-users')
	{
		foreach($_POST['deleteRow'] as $row_id)
		{
			$results->getDeleteRecord(__LINE__, __FILE__, 'users', 'WHERE `id` = ? AND (`site_id` = ? OR `site_id` = 0)', [$row_id, $_SESSION["site_set_for_editing"]]);
			
			$results->getDeleteRecord(__LINE__, __FILE__, 'assigned_fields', 'WHERE `user_id` = ? AND (`site_id` = ? OR `site_id` = 0)', [$row_id, $_SESSION["site_set_for_editing"]]);
		}
		
		echo "1";
		exit;
	}
}