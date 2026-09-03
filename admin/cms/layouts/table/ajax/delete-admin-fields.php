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

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-admin-fields.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-admin-fields.php');
}
else
{
	//Delete admin fields records. This will also drop the column on the database table.
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-admin-fields')
	{
		foreach($_POST['deleteRow'] as $row_id)
		{
			//Get the database column name / admin fields record.
			$all_datbase_column_name = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` = ?', [$row_id]);
			
			if(!empty($all_datbase_column_name['admin_fields_lists_system_code']))
			{
				$sql_admin_field_list_connected = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ? AND `dynamic` = ?', [$all_datbase_column_name['admin_fields_lists_system_code'], 'Yes']);
				
				if(!empty($sql_admin_field_list_connected))
				{
					echo 'A dynamic "Admin Fields List" is connected to this admin field, so it cannot be deleted. Please delete the "Admin Fields List" with ID: '.$sql_admin_field_list_connected['id'].', and then you will be able to delete this admin field.';
					exit;
				}
			}
			
			//Get all database tables using this field / column name.
			$all_datbase_tables_using_column_name = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `admin_fields_ids` LIKE ?', ['%,'.$row_id.',%']);
			
			if(!empty($all_datbase_column_name) && !empty($all_datbase_tables_using_column_name))
			{
				foreach($all_datbase_tables_using_column_name as $all_datbase_tables_using_column)
				{
					$existing_column_ids = '';
					if(substr_count($all_datbase_tables_using_column['admin_fields_ids'], ',') > 2)
					{
						$existing_column_ids = str_replace($row_id.',', '',$all_datbase_tables_using_column['admin_fields_ids']);
					}
					
					$results->getUpdateRecord(__LINE__, __FILE__, 'database_tables', '`admin_fields_ids` = ?, `updated_date` = UTC_TIMESTAMP(), `updated_by` = ?', 'WHERE `id` = ?', [$existing_column_ids, $_SESSION['user_username'], $all_datbase_tables_using_column['id']]);
					
					$results->getAlterDatabaseTable(__LINE__, __FILE__, $all_datbase_tables_using_column['database_table_name'], 'DROP '.$all_datbase_column_name['column_name']);
				}
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'admin_fields', 'WHERE `id` = ?', [$row_id]);
			}
		}
		
		echo "1";
		exit;
	}
}