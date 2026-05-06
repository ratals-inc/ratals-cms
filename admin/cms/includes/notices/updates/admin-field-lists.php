<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD / UPDATE ADMIN_FIELD_LISTS
try
{
	$update_admin_fields_lists = 'Yes';
	
	$admin_field_list_packages = array();
	
	$admin_field_list_packages[] = 'cms';
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/data/admin-fields-lists.php'))
	{
		$admin_field_list_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/data/admin-fields-lists.php'))
	{
		$admin_field_list_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/admin-fields-lists.php'))
	{
		$admin_field_list_packages[] = 'ai';
	}
	
	foreach($admin_field_list_packages as $admin_field_list_package)
	{
		include($temp_extract_dir.'/admin/'.$admin_field_list_package.'/installer/data/admin-fields-lists.php');
		
		$new_field_list_array = array();
		$new_field_list_array_add = array();
		foreach($parameters as $values)
		{
			$new_field_list_data_set = array(
				'name' => $values['name'], 
				'sub_items' => $values['sub_items'], 
				'dynamic' => $values['dynamic'], 
				'dynamic_table_name' => $values['dynamic_table_name'], 
				'dynamic_column_id' => $values['dynamic_column_id'], 
				'dynamic_column_label' => $values['dynamic_column_label'], 
				'system_code' => $values['system_code']
			);
			
			//Convert values to string and store in main compare array
			$new_field_list_array[$values['system_code']] = array_map('strval', $new_field_list_data_set);
			
			//Store the full row for inserts
			$temp_values = array_values($values);
			$new_field_list_array_add[$values['system_code']] = $temp_values;
		}
		
		//Only get columns that need to be compared if they changed.
		$current_field_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_lists', '', [], 'system_code');
		
		$current_fields = array();
		if(!empty($current_field_array))
		{
			foreach($current_field_array as $column_name => $values)
			{
				$current_field_data_set = array(
					'name' => $values['name'], 
					'sub_items' => $values['sub_items'], 
					'dynamic' => $values['dynamic'], 
					'dynamic_table_name' => $values['dynamic_table_name'], 
					'dynamic_column_id' => $values['dynamic_column_id'], 
					'dynamic_column_label' => $values['dynamic_column_label'], 
					'system_code' => $values['system_code']
				);
				
				//Make sure array is all string so comparision works.
				$current_fields[$column_name] = array_map('strval', $current_field_data_set);
			}
		}
		
		$fields_to_update = array();
		$fields_to_add = array();
	
		if(!empty($new_field_list_array))
		{
			foreach($new_field_list_array as $column_name => $new_field_list_data)
			{
				if(isset($current_fields[$column_name]))
				{
					$db_field_data = $current_fields[$column_name];
	
					//Check if any column differs
					if($db_field_data !== $new_field_list_data)
					{
						$new_field_list_data_array = array(
							$new_field_list_data['name'], 
							$new_field_list_data['sub_items'], 
							$new_field_list_data['dynamic'], 
							$new_field_list_data['dynamic_table_name'], 
							$new_field_list_data['dynamic_column_id'], 
							$new_field_list_data['dynamic_column_label'], 
							$new_field_list_data['system_code'], 
							$new_field_list_data['system_code'] //WHERE clause
						);
	
						$fields_to_update[$column_name] = $new_field_list_data_array;
					}
				}
				else
				{
					//Admin Field list not found - needs to be inserted
					$fields_to_add[] = $new_field_list_array_add[$column_name];
				}
			}
		}
	
		//Update existing menus that changed
		if(!empty($fields_to_update))
		{
			writeToInstallLog($admin_field_list_package.' module found '.count($fields_to_update).' admin_fields_lists that need updating.');
			try
			{
				$update_columns = '`name` = ?, `sub_items` = ?, `dynamic` = ?, `dynamic_table_name` = ?, `dynamic_column_id` = ?, `dynamic_column_label` = ?, `system_code` = ?';
				$update_where_clause = 'WHERE `system_code` = ?';
	
				$results->getUpdateMultipleRecords(__LINE__, __FILE__, 'admin_fields_lists', $update_columns, $update_where_clause, $fields_to_update);
				writeToInstallLog('Successfully updated all '.count($fields_to_update).' '.$admin_field_list_package.' admin_fields_lists.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed updating admin_fields_lists: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_fields_lists require updating.');
		}
	
		//Insert new menus
		if(!empty($fields_to_add))
		{
			writeToInstallLog($admin_field_list_package.' module found '.count($fields_to_add).' admin_fields_lists that need adding.');
			try
			{
				//$column_names and $placeholders are already defined in /admin/'.$admin_field_list_package.'/installer/data/admin-fields-lists.php
				$results->getInsertMultipleRecords(__LINE__, __FILE__, 'admin_fields_lists', $column_names, $placeholders, $fields_to_add);
				writeToInstallLog('Successfully added all '.count($fields_to_add).' '.$admin_field_list_package.' admin_fields_lists.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed adding admin_fields_lists: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_fields_lists require adding.');
		}
	}

	writeToInstallLog('Completed admin_fields_lists update check.');
}
catch (\Throwable $e)
{
	writeToInstallLog('Failed comparing admin_fields_lists: ' . $e->getMessage());
}