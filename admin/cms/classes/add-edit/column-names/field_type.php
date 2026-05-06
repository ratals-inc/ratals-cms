<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/field_type.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/field_type.php');
}
else
{
	if(!class_exists('field_type_aecn'))
	{
		class field_type_aecn
		{
			public function field_type_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					if($_POST[$table_name][$admin_field["column_name"]] == 'Inventory Attribute')
					{
						$_POST[$table_name]['assigned_to'] = 'inventory';
						$post_values[$table_name]['assigned_to'] = 'inventory';
						
						$_POST[$table_name]['site_id'] = 0;
						$post_values[$table_name]['site_id'] = 0;
						
						$post_values[$table_name][$admin_field["column_name"]] = $_POST[$table_name][$admin_field["column_name"]];
					}
					elseif($_POST[$table_name][$admin_field["column_name"]] == 'Product Option')
					{
						$_POST[$table_name]['assigned_to'] = 'products';
						$post_values[$table_name]['assigned_to'] = 'products';
						
						$post_values[$table_name][$admin_field["column_name"]] = $_POST[$table_name][$admin_field["column_name"]];
					}
					else
					{
						$post_values[$table_name][$admin_field["column_name"]] = $_POST[$table_name][$admin_field["column_name"]];
					}
				}
			}
		}
		
		$class_field_type_aecn = new field_type_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_field_type_aecn->field_type_aecn($table_name, $admin_field, $post_values, $errors);
	}
}