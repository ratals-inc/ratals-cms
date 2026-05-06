<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/data_type.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/data_type.php');
}
else
{
	if(!class_exists('data_type_aecn'))
	{
		class data_type_aecn
		{
			public function data_type_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'admin_fields' && isset($_POST[$table_name]['data_type']))
				{
					if(!empty($_POST[$table_name]['character_set_and_collate']) && (strpos($_POST[$table_name]['data_type'], 'date') !== false
						|| strpos($_POST[$table_name]['data_type'], 'decimal') !== false
						|| strpos($_POST[$table_name]['data_type'], 'int') !== false
						|| strpos($_POST[$table_name]['data_type'], 'time') !== false))
					{
						$errors[$table_name]['character_set_and_collate'] = '"Character Set & Collate" must be empty when you select a "Data Type" of date, decimal, or int.';
					}
					
					if(empty($_POST[$table_name]['character_set_and_collate']) 
						&& (strpos($_POST[$table_name]['data_type'], 'longtext') !== false
						|| strpos($_POST[$table_name]['data_type'], 'text') !== false
						|| strpos($_POST[$table_name]['data_type'], 'char') !== false))
					{
						$errors[$table_name]['character_set_and_collate'] = '"Character Set & Collate" cannot be empty when you select a "Data Type" of text or char.';
					}
					
					if($_POST[$table_name]['is_nullable'] == 'Yes' 
						&& (strpos($_POST[$table_name]['data_type'], 'longtext') !== false
						|| strpos($_POST[$table_name]['data_type'], 'text') !== false
						|| strpos($_POST[$table_name]['data_type'], 'char') !== false))
					{
						$errors[$table_name]['is_nullable'] = '"Is Nullable" should be set to "No" when selecting a "Data Type" of text or char.';
					}
					
					if($_POST[$table_name]['is_primary_key'] == 'Yes' 
						&& (strpos($_POST[$table_name]['data_type'], 'date') !== false
						|| strpos($_POST[$table_name]['data_type'], 'decimal') !== false
						|| strpos($_POST[$table_name]['data_type'], 'time') !== false 
						|| strpos($_POST[$table_name]['data_type'], 'longtext') !== false
						|| strpos($_POST[$table_name]['data_type'], 'text') !== false
						|| strpos($_POST[$table_name]['data_type'], 'char') !== false))
					{
						$errors[$table_name]['is_primary_key'] = '"Is Primary Key" should only be set to "Yes" when selecting a "Data Type" of int.';
					}
					
					if($_POST[$table_name]['is_auto_increment'] == 'Yes' 
						&& (strpos($_POST[$table_name]['data_type'], 'date') !== false
						|| strpos($_POST[$table_name]['data_type'], 'decimal') !== false
						|| strpos($_POST[$table_name]['data_type'], 'time') !== false 
						|| strpos($_POST[$table_name]['data_type'], 'longtext') !== false
						|| strpos($_POST[$table_name]['data_type'], 'text') !== false
						|| strpos($_POST[$table_name]['data_type'], 'char') !== false))
					{
						$errors[$table_name]['is_auto_increment'] = '"Is Auto Increment" should only be set to "Yes" when selecting a "Data Type" of int.';
					}
					
					$post_values[$table_name]['data_type'] = $_POST[$table_name]['data_type'];
				}
			}
		}
		
		$class_data_type_aecn = new data_type_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_data_type_aecn->data_type_aecn($table_name, $admin_field, $post_values, $errors);
	}
}