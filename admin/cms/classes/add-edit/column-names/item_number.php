<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/item_number.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/item_number.php');
}
else
{
	if(!class_exists('item_number_aecn'))
	{
		class item_number_aecn
		{
			public function item_number_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				//Check if Product / Inventory Item Number is already being used.
				if($_SESSION['commerce_installed'] && isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					if($table_name == 'products')
					{
						$item_number_exist_products = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'products', 'WHERE `item_number` = ? AND `urls_id` != ?', [$post_values[$table_name][$admin_field["column_name"]], trim($_GET["rid"] ?? '')]);
						$item_number_exist_inventory = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `item_number` = ?', [$post_values[$table_name][$admin_field["column_name"]]]);
					}
					else
					{
						$item_number_exist_products = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'products', 'WHERE `item_number` = ?', [$post_values[$table_name][$admin_field["column_name"]]]);
						$item_number_exist_inventory = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `item_number` = ? AND `id` != ?', [$post_values[$table_name][$admin_field["column_name"]], trim($_GET["rid"] ?? '')]);
					}
					
					if(!empty($item_number_exist_products) || !empty($item_number_exist_inventory))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is already being used on another item.';
					}
				}
			}
		}
		
		$class_item_number_aecn = new item_number_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_item_number_aecn->item_number_aecn($table_name, $admin_field, $post_values, $errors);
	}
}