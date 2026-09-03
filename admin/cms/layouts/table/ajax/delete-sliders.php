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

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-sliders.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-sliders.php');
}
else
{
	//Delete Slider Rows and Slider Items
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-sliders')
	{
		$get_rid = $_POST['getRid'];
		
		foreach($_POST['deleteRow'] as $row_id)
		{
			if($_SESSION['admin_table_name'] == "sliders")
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'slider_items', 'WHERE `site_id` = ? AND `sliders_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'sliders', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
			}
			
			if($_SESSION['admin_table_name'] == "slider_items")
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'slider_items', 'WHERE `site_id` = ? AND `id` = ? AND `sliders_id` = ?', [$_SESSION["site_set_for_editing"], $row_id, $get_rid]);
			}	
		}
		
		if($_SESSION['admin_table_name'] == "slider_items")
		{
			//Get slider items count
			$sql_slider_items_count =$results->getSelectCountRecords(__LINE__, __FILE__, '*', 'slider_items', 'WHERE `sliders_id` = ? AND `site_id` = ?', [$get_rid, $_SESSION["site_set_for_editing"]]);
			
			//Update slider items count
			$results->getUpdateRecord(__LINE__, __FILE__, 'sliders', '`sub_items` = ?, `updated_date` = UTC_TIMESTAMP(),`updated_by` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$sql_slider_items_count, $_SESSION['user_username'], $get_rid, $_SESSION["site_set_for_editing"]]);
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