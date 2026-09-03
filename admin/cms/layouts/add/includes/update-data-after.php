<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/add/includes/update-data-after.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/add/includes/update-data-after.php');
}
else
{
	if(!empty($_POST) && empty($errors) && !isset($_POST['change_site']) && isset($post_values))
	{
		//If admin_page has a child_table set and a column for sub_items, count the number of the child rows for the sub_items to save the count on parent and main record.
		if(isset($post_values[$_SESSION['admin_table_name']]['sub_items']))
		{
			if(!empty($_SESSION['admin_table_link_column']) && !empty($_SESSION['admin_parent_table_name']) && !empty(trim($_GET["rid"] ?? '')) && is_numeric(trim($_GET["rid"] ?? '')))
			{
				//Update main table sub_items total
				$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ?', [trim($_GET["rid"] ?? '')]);
				$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_parent_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), trim($_GET["rid"] ?? '')]);
				
				if(isset($_GET["sub-rid"]) && !empty(trim($_GET["sub-rid"] ?? '')) && is_numeric(trim($_GET["sub-rid"] ?? '')))
				{
					//Update item table sub_items total
					$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `parent_id` = ?', [trim($_GET["sub-rid"] ?? '')]);
					$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), trim($_GET["sub-rid"] ?? '')]);
				}
			}
		}
		elseif(!empty($_SESSION['admin_table_link_column']) && $_SESSION['admin_sub_page'] == 'Yes' && $_SESSION['admin_table_name'] != 'customer_addresses')
		{
			//Update main table sub_items total
			$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ?', [trim($_GET["rid"] ?? '')]);
			$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_parent_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), trim($_GET["rid"] ?? '')]);
		}
	}
	
	//Get the last sort number for this menu and parent level.
	if($_SESSION['admin_table_name'] == 'menu_items' && !empty(trim($_GET["rid"] ?? '')) && is_numeric(trim($_GET["rid"] ?? '')))
	{
		$menus_id = trim($_GET["rid"]);
		$parent_id = 0;
		
		if(!empty(trim($_GET["sub-rid"] ?? '')) && is_numeric(trim($_GET["sub-rid"] ?? '')))
		{
			$parent_id = trim($_GET["sub-rid"]);
		}
		
		//Get the last sort number for this menu and parent level.
		$menu_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `menus_id` = ? AND `parent_id` = ? AND `sort` > ? ORDER BY `sort` DESC LIMIT 1', [$menus_id, $parent_id, 0]);
		
		$next_sort_count = 1;
		
		if(isset($menu_data['sort']) && is_numeric($menu_data['sort']))
		{
			$next_sort_count = $menu_data['sort'] + 1;
		}
		
		//Set the new menu item to the end of this menu level.
		$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`sort` = ?', 'WHERE `id` = ?', [$next_sort_count, $created_row_id]);
	}
	
	//Get the last sort number for this admin menu and parent level.
	if($_SESSION['admin_table_name'] == 'admin_menu_items' && !empty(trim($_GET["rid"] ?? '')) && is_numeric(trim($_GET["rid"] ?? '')))
	{
		$admin_menus_id = trim($_GET["rid"]);
		$parent_id = 0;
		
		if(!empty(trim($_GET["sub-rid"] ?? '')) && is_numeric(trim($_GET["sub-rid"] ?? '')))
		{
			$parent_id = trim($_GET["sub-rid"]);
		}
		
		//Get the last sort number for this admin menu and parent level.
		$menu_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `admin_menus_id` = ? AND `parent_id` = ? AND `sort` > ? ORDER BY `sort` DESC LIMIT 1', [$admin_menus_id, $parent_id, 0]);
		
		$next_sort_count = 1;
		
		if(isset($menu_data['sort']) && is_numeric($menu_data['sort']))
		{
			$next_sort_count = $menu_data['sort'] + 1;
		}
		
		//Set the new admin menu item to the end of this menu level.
		$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`sort` = ?', 'WHERE `id` = ?', [$next_sort_count, $created_row_id]);
	}
}