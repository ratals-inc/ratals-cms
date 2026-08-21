<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD / UPDATE ADMIN_FIELD_VALUES
try
{
	$update_admin_fields_values = 'Yes';
	
	$admin_field_values_packages = array();
	
	$admin_field_values_packages[] = 'cms';
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/data/admin-fields-values.php'))
	{
		$admin_field_values_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/data/admin-fields-values.php'))
	{
		$admin_field_values_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/admin-fields-values.php'))
	{
		$admin_field_values_packages[] = 'ai';
	}
	
	foreach($admin_field_values_packages as $admin_field_values_package)
	{
		include($temp_extract_dir.'/admin/'.$admin_field_values_package.'/installer/data/admin-fields-values.php');
		
		$new_field_value_array = array();
		$new_field_value_array_add = array();
		foreach($parameters as $values)
		{
			
			$new_field_value_data_set = array(
				'label' => $values['label'], 
				'value' => $values['value'], 
				'admin_fields_lists_parent_code' => $values['admin_fields_lists_parent_code'], 
				'system_code' => $values['system_code']
			);
			
			//Normalize values for comparison by converting non-NULL values to strings.
			$new_field_value_array[$values['system_code']] = array_map(function($value)
			{
				return $value === NULL ? NULL : strval($value);
			}, $new_field_value_data_set);
			
			//Store the full row for inserts
			$temp_values = array_values($values);
			$new_field_value_array_add[$values['system_code']] = $temp_values;
		}
		
		//Only get columns that need to be compared if they changed.
		$current_field_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_values', '', [], 'system_code');
		
		$current_fields = array();
		if(!empty($current_field_array))
		{
			foreach($current_field_array as $column_name => $values)
			{
				$current_field_data_set = array(
					'label' => $values['label'], 
					'value' => $values['value'], 
					'admin_fields_lists_parent_code' => $values['admin_fields_lists_parent_code'], 
					'system_code' => $values['system_code']
				);
				
				//Normalize values for comparison by converting non-NULL values to strings.
				$current_fields[$column_name] = array_map(function($value)
				{
					return $value === NULL ? NULL : strval($value);
				}, $current_field_data_set);
			}
		}
		
		$fields_to_update = array();
		$fields_to_add = array();
	
		if(!empty($new_field_value_array))
		{
			foreach($new_field_value_array as $column_name => $new_field_value_data)
			{
				if(isset($current_fields[$column_name]))
				{
					$db_field_data = $current_fields[$column_name];
	
					//Check if any column differs
					if($db_field_data !== $new_field_value_data)
					{
						$new_field_value_data_array = array(
							$new_field_value_data['label'], 
							$new_field_value_data['value'], 
							$new_field_value_data['admin_fields_lists_parent_code'], 
							$new_field_value_data['system_code'], 
							$new_field_value_data['system_code']
						);
	
						$fields_to_update[$column_name] = $new_field_value_data_array;
					}
				}
				else
				{
					//Admin Fields List not found - needs to be inserted
					$fields_to_add[] = $new_field_value_array_add[$column_name];
				}
			}
		}
		
		//Update existing admin_fields_values that changed
		if(!empty($fields_to_update))
		{
			writeToInstallLog($admin_field_values_package.' module found '.count($fields_to_update).' admin_fields_values that need updating.');
			try
			{
				$update_columns = '`label` = ?, `value` = ?, `admin_fields_lists_parent_code` = ?, `system_code` = ?';
				$update_where_clause = 'WHERE `system_code` = ?';
	
				$results->getUpdateMultipleRecords(__LINE__, __FILE__, 'admin_fields_values', $update_columns, $update_where_clause, $fields_to_update);
				writeToInstallLog('Successfully updated all '.count($fields_to_update).' '.$admin_field_values_package.' admin_fields_values.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed updating admin_fields_values: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_fields_values require updating.');
		}
	
		//Insert new admin_fields_values
		if(!empty($fields_to_add))
		{
			writeToInstallLog($admin_field_values_package.' module found '.count($fields_to_add).' admin_fields_values that need adding.');
			try
			{
				//$column_names and $placeholders are already defined in /admin/'.$admin_field_values_package.'/installer/data/admin-fields-values.php
				$results->getInsertMultipleRecords(__LINE__, __FILE__, 'admin_fields_values', $column_names, $placeholders, $fields_to_add);
				writeToInstallLog('Successfully added all '.count($fields_to_add).' '.$admin_field_values_package.' admin_fields_values.');
				
				//Re-fetch admin_fields_lists to map parent codes to correct IDs
				$current_admin_fields_lists = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_lists', '', [], 'system_code');
				
				if(!empty($current_admin_fields_lists) && !empty($fields_to_add))
				{
					writeToInstallLog('Mapping newly added admin_fields_values to correct admin_fields_lists_id.');
				
					try
					{
						//Build lookup: system_code => id
						$system_code_to_id = array();
						foreach($current_admin_fields_lists as $system_code => $row_values)
						{
							$system_code_to_id[$system_code] = $row_values['id'];
						}
				
						//Loop through only the new values we just added
						foreach($fields_to_add as $new_field_value_data)
						{
							$parent_code = $new_field_value_data[4]; //admin_fields_lists_parent_code
							$system_code = $new_field_value_data[5]; //system_code
				
							//Skip if no parent admin_fields_lists
							if(!empty($parent_code) && isset($system_code_to_id[$parent_code]) && isset($system_code_to_id[$system_code]))
							{
								$new_parent_id = $system_code_to_id[$parent_code];
					
								//Update the admin_fields_lists_id for this new value
								$results->getUpdateRecord(__LINE__, __FILE__, 'admin_fields_values', '`admin_fields_lists_id` = ?', 'WHERE `system_code` = ?', [$new_parent_id, $system_code]);
							}
						}
				
						writeToInstallLog('Successfully updated admin_fields_lists_parent_code for new admin_fields_values.');
				
					} catch (\Throwable $e) {
						writeToInstallLog('Failed updating admin_fields_lists_parent_code for new admin_fields_values: ' . $e->getMessage());
					}
				}
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed adding admin_fields_values: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_fields_values require adding.');
		}
	}
	
	//Recalculate sub_items counts for admin_fields_lists
	writeToInstallLog('Recalculating sub_items counts for admin_fields_lists.');
	
	//Get all admin_fields_lists keyed by ID
	$admin_fields_lists = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_lists', '', [], 'id');
	
	//Get all admin_fields_values grouped by admin_fields_lists_id
	$admin_fields_values = $results->getSelectMultipleRecordsKeyNameArray(__LINE__, __FILE__, '*', 'admin_fields_values', '', [], 'admin_fields_lists_id');
	
	foreach($admin_fields_lists as $list_id => $list_data)
	{
		$values_count = 0;
	
		if(isset($admin_fields_values[$list_id]))
		{
			$values_count = count($admin_fields_values[$list_id]);
		}
	
		if($list_data['sub_items'] != $values_count)
		{
			$results->getUpdateRecord(__LINE__, __FILE__, 'admin_fields_lists', '`sub_items` = ?', 'WHERE `id` = ?', [$values_count, $list_id]);
			writeToInstallLog('Updated sub_items for admin_fields_lists ID '.$list_id.' from '.$list_data['sub_items'].' to '.$values_count.'.');
		}
	}
	
	writeToInstallLog('Completed recalculation of sub_items counts for admin_fields_lists.');
}
catch (\Throwable $e)
{
	writeToInstallLog('Failed comparing admin_fields_values: ' . $e->getMessage());
}