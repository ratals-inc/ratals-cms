<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD / UPDATE ADMIN_MENUS
try
{
	$update_admin_menus = 'Yes';
	
	$admin_menu_packages = array();
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/admin-menus.php'))
	{
		$admin_menu_packages[] = 'ai';
	}
	elseif(file_exists($temp_extract_dir.'/admin/erp/installer/data/admin-menus.php'))
	{
		$admin_menu_packages[] = 'erp';
	}
	elseif(file_exists($temp_extract_dir.'/admin/commerce/installer/data/admin-menus.php'))
	{
		$admin_menu_packages[] = 'commerce';
	}
	else
	{
		$admin_menu_packages[] = 'cms';
	}
	
	foreach($admin_menu_packages as $admin_menu_package)
	{
		include($temp_extract_dir.'/admin/'.$admin_menu_package.'/installer/data/admin-menus.php');
	
		$new_menu_array = array();
		$new_menu_array_add = array();
	
		foreach($parameters as $values)
		{
			$new_menu_data_set = array(
				'name' => $values['name'], 
				'sub_items' => $values['sub_items'], 
				'menu_type' => $values['menu_type'], 
				'menu_locations' => $values['menu_locations'], 
				'system_code' => $values['system_code']
			);
			
			//Convert values to string and store in main compare array
			$new_menu_array[$values['system_code']] = array_map('strval', $new_menu_data_set);
			
			//Store the full row for inserts
			$temp_values = array_values($values);
			$new_menu_array_add[$values['system_code']] = $temp_values;
		}
	
		//Only get columns needed for comparison
		$current_field_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_menus', '', [], 'system_code');
	
		$current_fields = array();
		if(!empty($current_field_array))
		{
			foreach($current_field_array as $column_name => $values)
			{
				$current_field_data_set = array(
					'name' => $values['name'], 
					'sub_items' => $values['sub_items'], 
					'menu_type' => $values['menu_type'], 
					'menu_locations' => $values['menu_locations'], 
					'system_code' => $values['system_code']
				);
				
				//Make sure array is all string so comparision works.
				$current_fields[$column_name] = array_map('strval', $current_field_data_set);
			}
		}
	
		$fields_to_update = array();
		$fields_to_add = array();
	
		if(!empty($new_menu_array))
		{
			foreach($new_menu_array as $column_name => $new_menu_data)
			{
				if(isset($current_fields[$column_name]))
				{
					$db_field_data = $current_fields[$column_name];
	
					//Check if any column differs
					if($db_field_data !== $new_menu_data)
					{
						$new_menu_data_array = array(
							$new_menu_data['name'],
							$new_menu_data['sub_items'],
							$new_menu_data['menu_type'],
							$new_menu_data['menu_locations'],
							$new_menu_data['system_code'],
							$new_menu_data['system_code'] //WHERE clause
						);
	
						$fields_to_update[$column_name] = $new_menu_data_array;
					}
				}
				else
				{
					//Menu not found - needs to be inserted
					$fields_to_add[] = $new_menu_array_add[$column_name];
				}
			}
		}
	
		//Update existing menus that changed
		if(!empty($fields_to_update))
		{
			writeToInstallLog($admin_menu_package.' module found '.count($fields_to_update).' admin_menus that need updating.');
			try
			{
				$update_columns = '`name` = ?, `sub_items` = ?, `menu_type` = ?, `menu_locations` = ?, `system_code` = ?';
				$update_where_clause = 'WHERE `system_code` = ?';
	
				$results->getUpdateMultipleRecords(__LINE__, __FILE__, 'admin_menus', $update_columns, $update_where_clause, $fields_to_update);
				writeToInstallLog('Successfully updated all '.count($fields_to_update).' '. $admin_menu_package.' admin_menus.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed updating admin_menus: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_menus require updating.');
		}
	
		//Insert new menus
		if(!empty($fields_to_add))
		{
			writeToInstallLog($admin_menu_package .' module found '.count($fields_to_add).' admin_menus that need adding.');
			try
			{
				//$column_names and $placeholders are already defined in /admin/'.$admin_menu_package.'/installer/data/admin-menus.php
				$results->getInsertMultipleRecords(__LINE__, __FILE__, 'admin_menus', $column_names, $placeholders, $fields_to_add);
				writeToInstallLog('Successfully added all '.count($fields_to_add).' '.$admin_menu_package.' admin_menus.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed adding admin_menus: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_menus require adding.');
		}
	}
	
	writeToInstallLog('Completed admin_menus update check.');
}
catch (\Throwable $e)
{
	writeToInstallLog('Failed comparing admin_menus: ' . $e->getMessage());
}