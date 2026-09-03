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

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/sort-table-rows.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/sort-table-rows.php');
}
else
{
	//Sort Rows for Assign Inventory to Products
	if(isset($_POST['sortrows_update']) && !empty($_POST['sortrows_update']) && $_POST['sortrows_update'] == 'assigned_inventory')
	{
		//include_once('function-update-assignment-tables.php');
		
		$_SESSION['admin_assigned_type'] = 'assigned_inventory';
		$product_id = $_POST['sub_rid'];
		$inventory_assigned_string = '';
		$inventory_assigned_array = array();
		$inventory_assigned = array();
		$inventory_assigned2 = array();
		$first_key_position = 10000000000;
		
		$inventory_assigned_order_string = '';
		$array_submitted_values	= $_POST['sortorder'];
		
		$select_pages_data_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'products', 'WHERE `urls_id` = ? AND `site_id` = ?', [$product_id, $_SESSION["site_set_for_editing"]]);
		
		if(!empty($select_pages_data_row))
		{
			$inventory_assigned_string = trim($select_pages_data_row['inventory_assigned'], ',');
			$inventory_assigned_array = explode(',', $inventory_assigned_string);
			
			//Create array of all inventory items currently assigned to product. Also find the first key for the set of items being sorted. So, if user was on pagination page 3 with 10 results a page that would be key 19 when stating from 0. This set the point of where to start updating for drag and drop.
			foreach($inventory_assigned_array as $key => $inventory_assigned_data)
			{
				$inventory_assigned_data_array = explode('|', $inventory_assigned_data);
				$inventory_assigned[] = $inventory_assigned_data;
				$inventory_assigned2[$inventory_assigned_data_array[1]] = $inventory_assigned_data;
				
				if(in_array($inventory_assigned_data_array[1], $array_submitted_values) && $key < $first_key_position)
				{
					$first_key_position = $key;
				}
			}
		}
		
		//Update what was submitted based on the first key postion of where the sort order was changed.
		foreach($array_submitted_values as $id_value) 
		{
			$inventory_assigned[$first_key_position] = $inventory_assigned2[$id_value];
			
			$first_key_position ++;
		}
		
		//Create sting of all inventory items assigned in there new order to update product in function of updateAssignmentTables();
		foreach($inventory_assigned as $inventory_assigned_item)
		{
			$inventory_assigned_order_string .= $inventory_assigned_item.',';
		}
		
		$inventory_assigned_order_string = trim($inventory_assigned_order_string, ',');
		
		updateAssignmentTables($inventory_assigned_order_string, $product_id);
		
		//Clear cache on save.
		if($_SESSION['admin_site_id_global'] == 'No')
		{
			clearSiteCache($_SESSION['site_set_for_editing']);
		}
		else
		{
			clearAllSiteCache();
		}
		
		exit;
	}
	
	//Sort Rows for Assign Sub Products to Products
	if(isset($_POST['sortrows_update']) && !empty($_POST['sortrows_update']) && $_POST['sortrows_update'] == 'sub_products_assigned')
	{	
		$select_pages_data_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'products', 'WHERE `urls_id` = ? AND `site_id` = ?', [$_POST['sub_rid'], $_SESSION["site_set_for_editing"]]);
		
		if(!empty($select_pages_data_row))
		{
			$current_order = explode(',',  trim($select_pages_data_row['products_assigned'], ',')  );
			
			foreach($current_order as $current_order_item)
			{
				$current_order_exploded = explode('|', $current_order_item);
				$current_order_array[$current_order_exploded[1]] = $current_order_exploded[0];
			}
		}
		
		$new_order = '';
		$array	= $_POST['sortorder'];
		
		foreach ($array as $id_value) 
		{
			$new_order .= $current_order_array[$id_value].'|'.$id_value.',';
		}
		
		$results->getUpdateRecord(__LINE__, __FILE__, 'products', '`products_assigned` = ?', 'WHERE `urls_id` = ? AND `site_id` = ?', [$new_order, $_POST['sub_rid'], $_SESSION["site_set_for_editing"]]);
		
		//Clear cache on save.
		if($_SESSION['admin_site_id_global'] == 'No')
		{
			clearSiteCache($_SESSION['site_set_for_editing']);
		}
		else
		{
			clearAllSiteCache();
		}
		
		exit;
	}
	
	//Sort Rows for tables with parent ids
	if(isset($_POST['sortrows_update']) && !empty($_POST['sortrows_update']) && $_POST['sortrows_update'] == 'dragdrop' && is_numeric($_POST['sort_counter']))
	{
		$sort_counter = $_POST['sort_counter'];
		
		if(!empty($_POST['sortorder']))
		{
			foreach($_POST['sortorder'] as $set_row_id)
			{			
				$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`sort` = ?', 'WHERE `id` = ? AND `'.$_SESSION['admin_table_link_column'].'` = ?', [$sort_counter, $set_row_id, $_POST['sub_rid']]);
				$sort_counter ++;
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
		
		exit;
	}
}