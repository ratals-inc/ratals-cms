<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/ajax/unassigned-urls-status.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/ajax/unassigned-urls-status.php');
}
else
{
	//Enable/Disable pages that are not assigned to anything - unassigned.php
	if($_POST['type'] == 'unassignedStatus')
	{
		$record_id = htmlspecialchars($_POST['recordId'] ?? '');
		$url_id = htmlspecialchars($_POST['urlId'] ?? '');
		$value = htmlspecialchars($_POST['value'] ?? '');
		
		$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', 'WHERE `id` = ? AND `site_id` = ?', [$_SESSION['user_first_last_name'], $_POST['recordId'], $_SESSION["site_set_for_editing"]]);
		
		$results->getUpdateRecord(__LINE__, __FILE__, 'urls', '`url_status` = ?', 'WHERE `id` = ? AND `site_id` = ? AND `table_name` = ? ', [$_POST['value'], $_POST['urlId'], $_SESSION["site_set_for_editing"], $_SESSION['admin_table_name']]);
		
		if($value == 1)
		{
			echo '<span class="unassignedStatus" data-click="'.$record_id.','.$url_id.','.$value.'">Enabled</span>';
		}
		elseif($value == 2)
		{
			echo '<span class="unassignedStatus" data-click="'.$record_id.','.$url_id.','.$value.'">Disabled</span>';
		}
		exit;
	}
}