<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-database-tables.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-database-tables.php');
}
else
{
	//Delete database tables.
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-database-tables')
	{
		foreach($_POST['deleteRow'] as $row_id)
		{
			//Get the database column name / admin fields record.
			$sql_datbase_table_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `id` = ?', [$row_id]);
			
			$sql_admin_field_list_connected = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `dynamic_table_name` = ?', [$sql_datbase_table_data['database_table_name']]);
			
			if(!empty($sql_admin_field_list_connected))
			{
				echo 'An "Admin Fields List" is connected to this table, so it cannot be deleted. Please delete the "Admin Fields List" with ID: '.$sql_admin_field_list_connected['id'].', and then you will be able to delete this table.';
				exit;
			}
			
			$results->getDropDatabaseTable(__LINE__, __FILE__, $sql_datbase_table_data['database_table_name']);
			
			$results->getDeleteRecord(__LINE__, __FILE__, 'database_tables', 'WHERE `id` = ?', [$row_id]);
		}
		
		echo "1";
		exit;
	}
}