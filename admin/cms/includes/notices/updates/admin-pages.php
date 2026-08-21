<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD / UPDATE ADMIN_PAGES
try
{
	$update_admin_pages = 'Yes';
	
	$admin_pages_packages = array();
	
	$admin_pages_packages[] = 'cms';
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/data/admin-pages.php'))
	{
		$admin_pages_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/data/admin-pages.php'))
	{
		$admin_pages_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/admin-pages.php'))
	{
		$admin_pages_packages[] = 'ai';
	}
	
	foreach($admin_pages_packages as $admin_pages_package)
	{
		include($temp_extract_dir.'/admin/'.$admin_pages_package.'/installer/data/admin-pages.php');
		
		$new_page_array = array();
		$new_page_array_add = array();
		foreach($parameters as $values)
		{
			$new_page_data_set = array(
				'admin_pages_name' => $values['admin_pages_name'], 
				'url' => $values['url'], 
				'add_url' => $values['add_url'], 
				'edit_url' => $values['edit_url'], 
				'sub_items_url' => $values['sub_items_url'], 
				'sub_items_add_url' => $values['sub_items_add_url'], 
				'sub_items_edit_url' => $values['sub_items_edit_url'], 
				'save_url' => $values['save_url'], 
				'no_record_url' => $values['no_record_url'], 
				'help_video_url' => $values['help_video_url'], 
				'type' => $values['type'], 
				'table_name' => $values['table_name'], 
				'table_link_column' => $values['table_link_column'], 
				'parent_table_name' => $values['parent_table_name'], 
				'parent_table_link_column' => $values['parent_table_link_column'], 
				'child_table_name' => $values['child_table_name'], 
				'child_table_link_column' => $values['child_table_link_column'], 
				'admin_pages_parent_code' => $values['admin_pages_parent_code'], 
				'system_code' => $values['system_code'], 
				'admin_page_level' => $values['admin_page_level'], 
				'sub_page' => $values['sub_page'], 
				'sort_or_dragdrop' => $values['sort_or_dragdrop'], 
				'global' => $values['global'], 
				'one_record' => $values['one_record'], 
				'parent_indicator' => $values['parent_indicator'], 
				'admin_pages_assigned_type' => $values['admin_pages_assigned_type'], 
				'js_name' => $values['js_name'], 
				'class' => $values['class'], 
				'submit_button_label' => $values['submit_button_label'], 
				'submit_button_type' => $values['submit_button_type']
			);
			
			//Normalize values for comparison by converting non-NULL values to strings.
			$new_page_array[$values['url']] = array_map(function($value)
			{
				return $value === NULL ? NULL : strval($value);
			}, $new_page_data_set);
			
			//Store the full row for inserts
			$temp_values = array_values($values);
			$new_page_array_add[$values['url']] = $temp_values;
		}
		
		//Only get columns that need to be compared if they changed.
		$current_field_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_pages', '', [], 'url');
		
		$current_fields = array();
		if(!empty($current_field_array))
		{
			foreach($current_field_array as $column_name => $values)
			{
				$current_page_data_set = array(
					'admin_pages_name' => $values['admin_pages_name'], 
					'url' => $values['url'], 
					'add_url' => $values['add_url'], 
					'edit_url' => $values['edit_url'], 
					'sub_items_url' => $values['sub_items_url'], 
					'sub_items_add_url' => $values['sub_items_add_url'], 
					'sub_items_edit_url' => $values['sub_items_edit_url'], 
					'save_url' => $values['save_url'], 
					'no_record_url' => $values['no_record_url'], 
					'help_video_url' => $values['help_video_url'], 
					'type' => $values['type'], 
					'table_name' => $values['table_name'], 
					'table_link_column' => $values['table_link_column'], 
					'parent_table_name' => $values['parent_table_name'], 
					'parent_table_link_column' => $values['parent_table_link_column'], 
					'child_table_name' => $values['child_table_name'], 
					'child_table_link_column' => $values['child_table_link_column'], 
					'admin_pages_parent_code' => $values['admin_pages_parent_code'], 
					'system_code' => $values['system_code'], 
					'admin_page_level' => $values['admin_page_level'], 
					'sub_page' => $values['sub_page'], 
					'sort_or_dragdrop' => $values['sort_or_dragdrop'], 
					'global' => $values['global'], 
					'one_record' => $values['one_record'], 
					'parent_indicator' => $values['parent_indicator'], 
					'admin_pages_assigned_type' => $values['admin_pages_assigned_type'], 
					'js_name' => $values['js_name'], 
					'class' => $values['class'], 
					'submit_button_label' => $values['submit_button_label'], 
					'submit_button_type' => $values['submit_button_type']
				);
				
				//Normalize values for comparison by converting non-NULL values to strings.
				$current_fields[$column_name] = array_map(function($value)
				{
					return $value === NULL ? NULL : strval($value);
				}, $current_page_data_set);
			}
		}
		
		$fields_to_update = array();
		$fields_to_add = array();
		if(!empty($new_page_array))
		{
			foreach($new_page_array as $column_name => $new_page_data)
			{
				if(isset($current_fields[$column_name]))
				{
					$db_field_data = $current_fields[$column_name];
					
					//Create an array of columns needing to be updated.
					if($db_field_data !== $new_page_data)
					{
						$new_page_data_array = array(
							$new_page_data['admin_pages_name'], 
							$new_page_data['url'], 
							$new_page_data['add_url'], 
							$new_page_data['edit_url'], 
							$new_page_data['sub_items_url'], 
							$new_page_data['sub_items_add_url'], 
							$new_page_data['sub_items_edit_url'], 
							$new_page_data['save_url'], 
							$new_page_data['no_record_url'], 
							$new_page_data['help_video_url'], 
							$new_page_data['type'], 
							$new_page_data['table_name'], 
							$new_page_data['table_link_column'], 
							$new_page_data['parent_table_name'], 
							$new_page_data['parent_table_link_column'], 
							$new_page_data['child_table_name'], 
							$new_page_data['child_table_link_column'], 
							$new_page_data['admin_pages_parent_code'], 
							$new_page_data['system_code'], 
							$new_page_data['admin_page_level'], 
							$new_page_data['sub_page'], 
							$new_page_data['sort_or_dragdrop'], 
							$new_page_data['global'], 
							$new_page_data['one_record'], 
							$new_page_data['parent_indicator'], 
							$new_page_data['admin_pages_assigned_type'], 
							$new_page_data['js_name'], 
							$new_page_data['class'], 
							$new_page_data['submit_button_label'], 
							$new_page_data['submit_button_type'],
							$new_page_data['url'] //WHERE clause
						);
	
						$fields_to_update[$column_name] = $new_page_data_array;
					}
				}
				else
				{
					//Create an array of new columns needing to be added.
					$fields_to_add[] = $new_page_array_add[$column_name];
				}
			}
		}
		
		//Update existing admin_pages
		if(!empty($fields_to_update))
		{
			writeToInstallLog($admin_pages_package.' module found ' . count($fields_to_update) . ' admin_pages that need updating.');
			try
			{
				$update_columns = '`admin_pages_name` = ?, `url` = ?, `add_url` = ?, `edit_url` = ?, `sub_items_url` = ?, `sub_items_add_url` = ?, `sub_items_edit_url` = ?, `save_url` = ?, `no_record_url` = ?, `help_video_url` = ?, `type` = ?, `table_name` = ?, `table_link_column` = ?, `parent_table_name` = ?, `parent_table_link_column` = ?, `child_table_name` = ?, `child_table_link_column` = ?, `admin_pages_parent_code` = ?, `system_code` = ?, `admin_page_level` = ?, `sub_page` = ?, `sort_or_dragdrop` = ?, `global` = ?, `one_record` = ?, `parent_indicator` = ?, `admin_pages_assigned_type` = ?, `js_name` = ?, `class` = ?, `submit_button_label` = ?, `submit_button_type` = ?';
				$update_where_clause = 'WHERE `url` = ?';
	
				$results->getUpdateMultipleRecords(__LINE__, __FILE__, 'admin_pages', $update_columns, $update_where_clause, $fields_to_update);
	
				writeToInstallLog('Successfully updated all '.count($fields_to_update).' '.$admin_pages_package.' admin_pages.');
			}
			catch (\Throwable $e)
			{
				writeToInstallLog('Failed updating admin_pages: ' . $e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_pages require updating.');
		}
	
		//Insert new admin_pages
		if(!empty($fields_to_add))
		{
			writeToInstallLog($admin_pages_package.' module found ' . count($fields_to_add) . ' admin_pages that need adding.');
			try
			{
				//$column_names and $placeholders are already defined in /admin/'.$admin_pages_package.'/installer/data/admin-pages.php
				$results->getInsertMultipleRecords(__LINE__, __FILE__, 'admin_pages', $column_names, $placeholders, $fields_to_add);
	
				writeToInstallLog('Successfully added all '.count($fields_to_add).' '.$admin_pages_package.' admin_pages.');
			}
			catch (\Throwable $e)
			{
				writeToInstallLog('Failed adding admin_pages: ' . $e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No admin_pages require adding.');
		}
	}
}
catch (\Throwable $e)
{
	writeToInstallLog('Failed comparing admin_pages: ' . $e->getMessage());
}