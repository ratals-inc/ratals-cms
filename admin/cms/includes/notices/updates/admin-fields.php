<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD / UPDATE ADMIN_FIELDS - ADMIN FIELDS CONTROL HOW RULES WILL BE APPLIED TO DATABASE COLUMNS WHEN INTERACTING WITH THEM IN THE ADMIN AREA.
try
{
	$update_admin_fields = 'Yes';
	
	$admin_fields_packages = array();
	
	$admin_fields_packages[] = 'cms';
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/database/columns/index.php'))
	{
		$admin_fields_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/database/columns/index.php'))
	{
		$admin_fields_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/database/columns/index.php'))
	{
		$admin_fields_packages[] = 'ai';
	}
	
	foreach($admin_fields_packages as $admin_fields_package)
	{
		include($temp_extract_dir.'/admin/'.$admin_fields_package.'/installer/database/columns/index.php');
		
		$new_field_array = array();
		$new_field_array_add = array();
		foreach($parameters as $column_name => $values)
		{
			$new_field_data_set = array(
				'name' => $values['name'], 
				'column_name' => $values['column_name'], 
				'url_name' => $values['url_name'], 
				'search_as' => $values['search_as'], 
				'display_as' => $values['display_as'], 
				'display_in_admin' => $values['display_in_admin'], 
				'update_field_on_save' => $values['update_field_on_save'], 
				'placeholder' => $values['placeholder'], 
				'admin_fields_lists_system_code' => $values['admin_fields_lists_system_code'], 
				'data_type' => $values['data_type'], 
				'character_set_and_collate' => $values['character_set_and_collate'], 
				'is_nullable' => $values['is_nullable'], 
				'is_primary_key' => $values['is_primary_key'], 
				'is_auto_increment' => $values['is_auto_increment'], 
				'data_length' => $values['data_length'], 
				'data_length_back' => $values['data_length_back'], 
				'financial_field' => $values['financial_field'], 
				'required' => $values['required'], 
				'notes' => $values['notes'], 
				'css_class' => $values['css_class']
			);
			
			//Normalize values for comparison by converting non-NULL values to strings.
			$new_field_array[$column_name] = array_map(function($value)
			{
				return $value === NULL ? NULL : strval($value);
			}, $new_field_data_set);
			
			//Store the full row for inserts
			$temp_values = array_values($values);
			$new_field_array_add[$column_name] = $temp_values;
		}
		
		//Get admin_fields/db columns to compare if they have changed or they are new and need to be added.
		$current_field_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', '', [], 'column_name');
		
		$current_fields = array();
		if(!empty($current_field_array))
		{
			foreach($current_field_array as $column_name => $values)
			{
				$current_field_data_set = array(
					'name' => $values['name'], 
					'column_name' => $values['column_name'], 
					'url_name' => $values['url_name'], 
					'search_as' => $values['search_as'], 
					'display_as' => $values['display_as'], 
					'display_in_admin' => $values['display_in_admin'], 
					'update_field_on_save' => $values['update_field_on_save'], 
					'placeholder' => $values['placeholder'], 
					'admin_fields_lists_system_code' => $values['admin_fields_lists_system_code'], 
					'data_type' => $values['data_type'], 
					'character_set_and_collate' => $values['character_set_and_collate'], 
					'is_nullable' => $values['is_nullable'], 
					'is_primary_key' => $values['is_primary_key'], 
					'is_auto_increment' => $values['is_auto_increment'], 
					'data_length' => $values['data_length'], 
					'data_length_back' => $values['data_length_back'], 
					'financial_field' => $values['financial_field'], 
					'required' => $values['required'], 
					'notes' => $values['notes'], 
					'css_class' => $values['css_class']
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
		if(!empty($new_field_array))
		{
			foreach($new_field_array as $column_name => $new_field_data)
			{
				if(isset($current_fields[$column_name]))
				{
					$db_field_data = $current_fields[$column_name];
					
					//Create an array of columns needing to be updated.
					if($db_field_data !== $new_field_data)
					{
						$new_field_data_array = 
						array(
							$new_field_data['name'], 
							$new_field_data['column_name'], 
							$new_field_data['url_name'], 
							$new_field_data['search_as'], 
							$new_field_data['display_as'], 
							$new_field_data['display_in_admin'], 
							$new_field_data['update_field_on_save'], 
							$new_field_data['placeholder'], 
							$new_field_data['admin_fields_lists_system_code'], 
							$new_field_data['data_type'], 
							$new_field_data['character_set_and_collate'], 
							$new_field_data['is_nullable'], 
							$new_field_data['is_primary_key'], 
							$new_field_data['is_auto_increment'], 
							$new_field_data['data_length'], 
							$new_field_data['data_length_back'], 
							$new_field_data['financial_field'], 
							$new_field_data['required'], 
							$new_field_data['notes'], 
							$new_field_data['css_class'],
							$new_field_data['column_name'] //where clause
						);
						
						$fields_to_update[$column_name] = $new_field_data_array;
					}
				}
				else
				{
					//Create an array of new columns needing to be added.
					$fields_to_add[] = $new_field_array_add[$column_name];
				}
			}
		}
		
		if(!empty($fields_to_update))
		{
			writeToInstallLog($admin_fields_package.' module found '.count($fields_to_update).' admin_fields that need updating.');
			try
			{
				//Update columns that have changed.
				$update_columns = '`name` = ?, `column_name` = ?, `url_name` = ?, `search_as` = ?, `display_as` = ?, `display_in_admin` = ?, `update_field_on_save` = ?, `placeholder` = ?, `admin_fields_lists_system_code` = ?, `data_type` = ?, `character_set_and_collate` = ?, `is_nullable` = ?, `is_primary_key` = ?, `is_auto_increment` = ?, `data_length` = ?, `data_length_back` = ?, `financial_field` = ?, `required` = ?, `notes` = ?, `css_class` = ?';
				$update_where_clause = 'WHERE `column_name` = ?';
				
				$results->getUpdateMultipleRecords(__LINE__, __FILE__, 'admin_fields', $update_columns, $update_where_clause, $fields_to_update);
				
				foreach($fields_to_update as $field_to_update)
				{
					writeToInstallLog('Successfully updated admin field: '.$field_to_update[1]);
				}
				
				writeToInstallLog('Successfully updated all '.count($fields_to_update).' '.$admin_fields_package.' admin_fields.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed updating admin_fields: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_fields require updating.');
		}
		
		if(!empty($fields_to_add))
		{
			writeToInstallLog($admin_fields_package.' module found '.count($fields_to_add).' admin_fields that need adding.');
			try
			{
				//Insert new columns
				//$column_names and $placeholders are set in /admin/'.$admin_fields_package.'/installer/database/columns/index.php
				$results->getInsertMultipleRecords(__LINE__, __FILE__, 'admin_fields', $column_names, $placeholders, $fields_to_add);
				
				foreach($fields_to_add as $field_to_add)
				{
					writeToInstallLog('Successfully added admin field: '.$field_to_add[1]);
				}
				
				writeToInstallLog('Successfully added all '.count($fields_to_add).' '.$admin_fields_package.' admin_fields.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed adding admin_fields: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_fields require adding.');
		}
	}
}
catch (\Throwable $e)
{
	writeToInstallLog('Failed comparing admin_fields: ' . $e->getMessage());
}