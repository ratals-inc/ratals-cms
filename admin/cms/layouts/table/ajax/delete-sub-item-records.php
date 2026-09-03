<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-sub-item-records.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-sub-item-records.php');
}
else
{
	//Delete sub item records.
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && isset($_POST['tableName']) && !empty($_POST['tableName']) && $_POST['type'] == 'delete-sub-item-records')
	{
		$get_rid = $_POST['getRid'];
		
		if($_POST['tableName'] == $_SESSION['admin_table_name'])
		{
			foreach($_POST['deleteRow'] as $row_id)
			{
				$results->getDeleteRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], 'WHERE `id` = ? AND (`site_id` = ? OR `site_id` = 0)', [$row_id, $_SESSION["site_set_for_editing"]]);
				
				//Get count of sub items left.
				$sql_sub_item_count = $results->getSelectCountRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ? AND (`site_id` = ? OR `site_id` = 0)', [$get_rid, $_SESSION["site_set_for_editing"]]);
				
				//Update parent table with sub items left.
				$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_parent_table_name'], '`sub_items` = ?, `updated_date` = UTC_TIMESTAMP(), `updated_by` = ?', 'WHERE `id` = ?', [$sql_sub_item_count, $_SESSION['user_username'], $get_rid]);
			}
		}
		
		//Clear cache on save.
		if($_SESSION['admin_site_id_global'] == 'No')
		{
			clearSiteCache($_SESSION['site_set_for_editing']);
		}
		else
		{
			clearAllSiteCache();
		}
		
		echo "1";
		exit;
	}
}