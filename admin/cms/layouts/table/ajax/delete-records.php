<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-records'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-records');
}
else
{

	
	//Delete records
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && isset($_POST['tableName']) && !empty($_POST['tableName']) && $_POST['type'] == 'delete-records')
	{
		if($_POST['tableName'] == $_SESSION['admin_table_name'])
		{
			foreach($_POST['deleteRow'] as $row_id)
			{
				$results->getDeleteRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], 'WHERE `id` = ? AND (`site_id` = ? OR `site_id` = 0)', [$row_id, $_SESSION["site_set_for_editing"]]);
				
				if(!empty($_SESSION['admin_child_table_name']))
				{
					$results->getDeleteRecord(__LINE__, __FILE__, $_SESSION['admin_child_table_name'], 'WHERE `'.$_SESSION['admin_table_name'].'_id` = ? AND (`site_id` = ? OR `site_id` = 0)', [$row_id, $_SESSION["site_set_for_editing"]]);
				}
			}
		}
		
		echo "1";
		exit;
	}
	
	
}