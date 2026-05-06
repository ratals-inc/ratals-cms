<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/column_name.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/column_name.php');
}
else
{
	if(!class_exists('column_name_aecn'))
	{
		class column_name_aecn
		{
			public function column_name_aecn($table_name, $admin_field, &$post_values, &$errors, &$database_old_column_name, &$database_new_column_name, &$database_tables_with_old_column_name)
			{
				if($_SESSION['admin_table_name'] == 'admin_fields' && isset($_POST[$table_name]['column_name']))
				{
					if(!empty($_POST[$table_name]['column_name']))
					{
						$value = $_POST[$table_name]['column_name'];
					}
					else
					{
						$value = $_POST[$table_name]['name'];
					}
					
					$value = str_replace(array(' ', '-'), "_", trim(strtolower($value)));
					$value = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $value);
					$_POST[$table_name]['column_name'] = $value;
					$post_values[$table_name]['column_name'] = $value;
					
					if($_SESSION['admin_type'] == 'edit')
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` != ? AND  `column_name` = ?', [trim($_GET["rid"] ?? ''), $value]);
					}
					else
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `column_name` = ?', [$value]);
					}
					
					if(!empty($duplicate_value) && !isset($errors[$table_name]['column_name']))
					{
						$errors[$table_name]['column_name'] = $admin_field["name"].' is already being used on another admin field.';
					}
					
					$database_tables_with_old_column_name = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `admin_fields_ids` LIKE ?', ['%,'.$_POST[$table_name]['id'].',%']);
					
					$database_old_column_name = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` = ?', [$_POST[$table_name]['id']]);
					$database_new_column_name = $value;
				}
			}
		}
		
		$class_column_name_aecn = new column_name_aecn();
	}
	
	$database_old_column_name = '';
	$database_new_column_name = '';
	$database_tables_with_old_column_name = '';
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_column_name_aecn->column_name_aecn($table_name, $admin_field, $post_values, $errors, $database_old_column_name, $database_new_column_name, $database_tables_with_old_column_name);
	}
}