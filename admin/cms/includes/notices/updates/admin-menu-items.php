<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD / UPDATE ADMIN_MENU_ITEMS
try
{
	$update_admin_menu_items = 'Yes';
	
	$admin_menu_packages = array();
	
	//Note - admin menu item update files have the entire menu in them because of menu sort ordering. Only run the menu update file for the highest package level of the account as it will update everything to that package.
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/admin-menus-items.php'))
	{
		$admin_menu_packages[] = 'ai';
	}
	elseif(file_exists($temp_extract_dir.'/admin/erp/installer/data/admin-menus-items.php'))
	{
		$admin_menu_packages[] = 'erp';
	}
	elseif(file_exists($temp_extract_dir.'/admin/commerce/installer/data/admin-menus-items.php'))
	{
		$admin_menu_packages[] = 'commerce';
	}
	else
	{
		$admin_menu_packages[] = 'cms';
	}
	
	foreach($admin_menu_packages as $admin_menu_package)
	{
		include($temp_extract_dir.'/admin/'.$admin_menu_package.'/installer/data/admin-menus-items.php');
		
		$new_menu_item_array = array();
		$new_menu_item_array_add = array();
		foreach($parameters as $column_name => $values)
		{
			$new_menu_item_data_set = array(
				'admin_menus_id' => $values['admin_menus_id'], 
				'admin_pages_id' => $values['admin_pages_id'], 
				'admin_page_system_code' => $values['admin_page_system_code'], 
				'admin_menu_items_parent_code' => $values['admin_menu_items_parent_code'], 
				'system_code' => $values['system_code'], 
				'link_parameters' => $values['link_parameters'], 
				'link_target' => $values['link_target']
			);
			
			//Normalize values for comparison by converting non-NULL values to strings.
			$new_menu_item_array[$column_name] = array_map(function($value)
			{
				return $value === NULL ? NULL : strval($value);
			}, $new_menu_item_data_set);
			
			//Store the full row for inserts
			$temp_values = array_values($values);
			$new_menu_item_array_add[$column_name] = $temp_values;
		}
		
		//Only get columns that need to be compared if they changed.
		$current_field_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_menu_items', '', [], 'system_code');
		
		$current_fields = array();
		if(!empty($current_field_array))
		{
			foreach($current_field_array as $column_name => $values)
			{
				$current_field_data_set = array(
					'admin_menus_id' => $values['admin_menus_id'], 
					'admin_pages_id' => $values['admin_pages_id'], 
					'admin_page_system_code' => $values['admin_page_system_code'], 
					'admin_menu_items_parent_code' => $values['admin_menu_items_parent_code'], 
					'system_code' => $values['system_code'], 
					'link_parameters' => $values['link_parameters'], 
					'link_target' => $values['link_target']
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
		if(!empty($new_menu_item_array))
		{
			foreach($new_menu_item_array as $column_name => $new_menu_item_data)
			{
				if(isset($current_fields[$column_name]))
				{
					$db_field_data = $current_fields[$column_name];
					
					//Create an array of columns needing to be updated.
					if($db_field_data !== $new_menu_item_data)
					{
						
						$new_menu_item_data_array = array(
							$new_menu_item_data['admin_menus_id'], 
							$new_menu_item_data['admin_pages_id'], 
							$new_menu_item_data['admin_page_system_code'], 
							$new_menu_item_data['admin_menu_items_parent_code'], 
							$new_menu_item_data['system_code'], 
							$new_menu_item_data['link_parameters'], 
							$new_menu_item_data['link_target'],
							$new_menu_item_data['system_code'] //WHERE claus
						);
	
						$fields_to_update[$column_name] = $new_menu_item_data_array;
					}
				}
				else
				{
					//Create an array of new columns needing to be added.
					$fields_to_add[] = $new_menu_item_array_add[$column_name];
				}
			}
		}
		
		if(!empty($fields_to_update))
		{
			writeToInstallLog($admin_menu_package.' module found '.count($fields_to_update).' admin_menu_items that need updating.');
			try
			{
				//Update columns that have changed.
				$update_columns = '`admin_menus_id` = ?, `admin_pages_id` = ?, `admin_page_system_code` = ?, `admin_menu_items_parent_code` = ?, `system_code` = ?, `link_parameters` = ?, `link_target` = ?';
				$update_where_clause = 'WHERE `system_code` = ?';
				
				$results->getUpdateMultipleRecords(__LINE__, __FILE__, 'admin_menu_items', $update_columns, $update_where_clause, $fields_to_update);
				writeToInstallLog('Successfully updated all '.count($fields_to_update).' '.$admin_menu_package.' admin_menu_items.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed updating admin_menu_items: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_menu_items require updating.');
		}
		
		if(!empty($fields_to_add))
		{
			writeToInstallLog($admin_menu_package.' module found '.count($fields_to_add).' admin_menu_items that need adding.');
			try
			{
				//Insert new columns
				//$column_names and $placeholders are set in /admin/'.$admin_menu_package.'/installer/data/admin-menus-items.php
				$results->getInsertMultipleRecords(__LINE__, __FILE__, 'admin_menu_items', $column_names, $placeholders, $fields_to_add);
				writeToInstallLog('Successfully added all '.count($fields_to_add).' ' .$admin_menu_package.' admin_menu_items.');
				
				//Re-get admin_menu_items after install new menu items to update parent_id
				$current_field_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_menu_items', '', [], 'system_code');
				
				//Loop through $fields_to_add and update with correct parent_id
				if(!empty($current_field_array))
				{
					writeToInstallLog('Updating parent_id values for newly added admin_menu_items.');
					
					try
					{
						//Build lookup of system_code => admin_menu_items row "id" from freshly fetched records
						$system_code_to_id = array();
						foreach($current_field_array as $system_code => $row_values)
						{
							//First value in row is row id
							$system_code_to_id[$system_code] = $row_values['id'];
						}
						
						//Loop through only the new items we just added
						foreach($fields_to_add as $new_menu_item_data)
						{
							$parent_code = $new_menu_item_data[7]; //parent_code
							$system_code = $new_menu_item_data[8]; //system_code
							
							//Skip if this is a top-level menu item (no parent_code) or if parent reference not found
							if(empty($parent_code) || !isset($system_code_to_id[$parent_code]) || !isset($system_code_to_id[$system_code]))
							{
								continue;
							}
							
							$new_parent_id = $system_code_to_id[$parent_code];
							$menu_item_row_id_to_update = $system_code_to_id[$system_code];
							
							//Update the parent_id for this new menu item
							$results->getUpdateRecord(__LINE__, __FILE__, 'admin_menu_items', '`parent_id` = ?', 'WHERE `id` = ?', [$new_parent_id, $menu_item_row_id_to_update]);
						}
						
						writeToInstallLog('Successfully updated parent_id values for new admin_menu_items.');
						
						//Update menu counts for parent items
						try
						{
							writeToInstallLog('Updating menu counts for parent admin_menu_items.');
						
							//Initialize counts for all parent system_codes
							$parent_counts = array();
							foreach($current_field_array as $system_code => $row_values)
							{
								$parent_counts[$system_code] = 0;
							}
						
							//Count how many menu items reference each parent_code
							foreach($current_field_array as $system_code => $row_values)
							{
								$parent_code = $row_values['admin_menu_items_parent_code']; 
								
								if(!empty($parent_code) && isset($parent_counts[$parent_code]))
								{
									$parent_counts[$parent_code]++;
								}
							}
						
							//Update each parent record with its total count
							foreach($parent_counts as $parent_system_code => $count)
							{
								if(isset($current_field_array[$parent_system_code]['sub_items']) && $current_field_array[$parent_system_code]['sub_items'] != $count)
								{
									$results->getUpdateRecord(__LINE__, __FILE__, 'admin_menu_items', '`sub_items` = ?', 'WHERE `system_code` = ?', [$count, $parent_system_code]);
								}
							}
						
							writeToInstallLog('Successfully updated menu_item_count for all parent admin_menu_items.');
						}
						catch(\Throwable $e)
						{
							writeToInstallLog('Failed updating menu_item_count for admin_menu_items: ' . $e->getMessage());
						}
					}
					catch(\Throwable $e)
					{
						writeToInstallLog('Failed updating parent_id values for new admin_menu_items: ' . $e->getMessage());
					}
				}
				
				writeToInstallLog('Completed admin_menu_items compare/update/install routine successfully.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed adding admin_menu_items: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_menu_items require adding.');
		}
		
		//Recalculate top-level menu counts
		writeToInstallLog('Recalculating top-level menu counts for admin_menus.');
		
		$admin_menus = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_menus', '', [], 'id');
		$admin_menu_items = $results->getSelectMultipleRecordsKeyNameArray(__LINE__, __FILE__, '*', 'admin_menu_items', '', [], 'admin_menus_id');
		
		foreach($admin_menus as $menu_id => $menu_data)
		{
			$admin_menu_items_count = 0;
			
			if(isset($admin_menu_items[$menu_id]))
			{
				$admin_menu_items_count = count($admin_menu_items[$menu_id]);
			}
			
			if($menu_data['sub_items'] != $admin_menu_items_count)
			{
				$results->getUpdateRecord(__LINE__, __FILE__, 'admin_menus', '`sub_items` = ?', 'WHERE `id` = ?', [$admin_menu_items_count, $menu_id]);
				writeToInstallLog('Updated sub_items for top-level menu ID '.$menu_id.' from '.$menu_data['sub_items'].' to '.$admin_menu_items_count.'.');
			}
		}
	}
	
	writeToInstallLog('Completed recalculation of top-level menu counts for admin_menus.');
}
catch (\Throwable $e)
{
	writeToInstallLog('Failed comparing admin_menu_items: ' . $e->getMessage());
}