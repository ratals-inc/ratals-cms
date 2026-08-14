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

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-menus.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-menus.php');
}
else
{
	//Delete Website Frontend Menu Rows and Menu Items.
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-menus')
	{
		$get_rid = $_POST['getRid'];
		$get_sub_rid = $_POST['getSubRid'];
		
		foreach($_POST['deleteRow'] as $row_id)
		{
			//Delete a Full Menu and Sub Items
			if($_SESSION['admin_table_name'] == "menus")
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'menu_items', 'WHERE `site_id` = ? AND `menus_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'menus', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
			}
			//Delete Menu Sub Items
			elseif($_SESSION['admin_table_name'] == "menu_items")
			{
				//Get all subs of a menu items id
				$all_menu_items = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'menu_items', 'WHERE `site_id` = ? AND `menus_id` = ? AND `id` >= ? ORDER BY `id` ASC', [$_SESSION["site_set_for_editing"], $get_rid, $row_id]);
				
				$sub_subs_to_delete_array = array();
				
				if(!empty($all_menu_items))
				{
					$start_of_menu[] = $all_menu_items[0]['id'];
					$all_menu_items_array[] = $all_menu_items[0]; 
					
					foreach($all_menu_items as $all_menu_item)
					{
						foreach($all_menu_items as $all_menu_item_2)
						{
							if(in_array($all_menu_item_2['parent_id'], $start_of_menu))
							{
								$start_of_menu[] = $all_menu_item_2['id'];
								$all_menu_items_array[] = $all_menu_item_2;
							}
						}	
					}
					$sub_subs_to_delete_array = array_unique($start_of_menu);
				}
				
				if(!empty($sub_subs_to_delete_array) && !empty($get_rid))
				{
					foreach($sub_subs_to_delete_array as $sub_subs_to_delete)
					{
						$results->getDeleteRecord(__LINE__, __FILE__, 'menu_items', 'WHERE `site_id` = ? AND `id` = ? AND `menus_id` = ?', [$_SESSION["site_set_for_editing"], $sub_subs_to_delete, $get_rid]);
					}
				}
				
				//Get menu items count
				$sql_menu_items_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'menu_items', 'WHERE `menus_id` = ? AND `site_id` = ?', [$get_rid, $_SESSION["site_set_for_editing"]]);
				
				//Update number of menu items left under menu
				$results->getUpdateRecord(__LINE__, __FILE__, 'menus', '`sub_items` = ?, `updated_date` = UTC_TIMESTAMP(), `updated_by` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$sql_menu_items_check, $_SESSION['user_first_last_name'], $get_rid, $_SESSION["site_set_for_editing"]]);
			
				if(!empty(trim($get_sub_rid ?? '')))
				{
					//Get menu items count for sub sub items
					$sql_menu_items_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'menu_items', 'WHERE `menus_id` = ? AND `site_id` = ? AND `parent_id` = ?', [$get_rid, $_SESSION["site_set_for_editing"], trim($get_sub_rid ?? '')]);
					
					//Update number of menu items left under a sub sub menu item
					$results->getUpdateRecord(__LINE__, __FILE__, 'menu_items', '`sub_items` = ?, `updated_date` = UTC_TIMESTAMP(), `updated_by` = ?', 'WHERE `menus_id` = ? AND `site_id` = ? AND `id` = ?', [$sql_menu_items_check, $_SESSION['user_first_last_name'], $get_rid, $_SESSION["site_set_for_editing"], trim($get_sub_rid ?? '')]);
				}
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