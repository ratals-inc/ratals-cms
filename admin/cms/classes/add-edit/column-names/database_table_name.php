<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/database_table_name.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/database_table_name.php');
}
else
{
	if(!class_exists('database_table_name_aecn'))
	{
		class database_table_name_aecn
		{
			public function database_table_name_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				//Checking to make sure database table name does not already exist.
				if($_SESSION['admin_table_name'] == 'database_tables' && isset($_POST[$table_name]['database_table_name']) && !empty($_POST[$table_name]['database_table_name']))
				{
					$value = str_replace(array(' ', '-'), "_", trim(strtolower($_POST[$table_name]['database_table_name'])));
					$value = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $value);
					$_POST[$table_name]['database_table_name'] = $value;
					$post_values[$table_name]['database_table_name'] = $value;
					
					if($_SESSION['admin_type'] == 'edit')
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `id` != ? AND `database_table_name` = ?', [trim($_GET["rid"] ?? ''), $value]);
					}
					else
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `database_table_name` = ?', [$value]);
						
						if(empty($duplicate_value))
						{
							$duplicate_value = $_SESSION['results_schema']->getSchemaSelectMultipleRecords(__LINE__, __FILE__, '*', 'tables', 'WHERE `table_schema` = "'.$_SESSION['site_db_name'].'" AND `table_name` = ?', [$value]);
						}
					}
					
					if(!empty($duplicate_value) && !isset($errors[$table_name]['database_table_name']))
					{
						$errors[$table_name]['database_table_name'] = $admin_field["name"].' is already being used on another table.';
					}
				}
			}
		}
		
		$class_database_table_name_aecn = new database_table_name_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_database_table_name_aecn->database_table_name_aecn($table_name, $admin_field, $post_values, $errors);
	}
}