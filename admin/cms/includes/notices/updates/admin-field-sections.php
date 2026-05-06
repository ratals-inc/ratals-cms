<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD ADMIN_FIELD_SECTIONS
try
{
	$update_admin_field_sections = 'Yes';
	
	$admin_field_sections_packages = array();
	
	$admin_field_sections_packages[] = 'cms';
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/data/admin-field-sections.php'))
	{
		$admin_field_sections_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/data/admin-field-sections.php'))
	{
		$admin_field_sections_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/admin-field-sections.php'))
	{
		$admin_field_sections_packages[] = 'ai';
	}
	
	foreach($admin_field_sections_packages as $admin_field_sections_package)
	{
		include($temp_extract_dir.'/admin/'.$admin_field_sections_package.'/installer/data/admin-field-sections.php');
		
		$new_field_section_array_add = array();
	
		//Build lookup array keyed by system code for inserts
		foreach($parameters as $values)
		{
			//Store full row for inserts
			$new_field_section_array_add[$values['system_code']] = array_values($values);
		}
	
		//Get current sections from DB keyed by system_code
		$current_field_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '`system_code`', 'admin_fields_sections', '', [], 'system_code');
	
		$fields_to_add = array();
	
		foreach($new_field_section_array_add as $system_code => $row_values)
		{
			if(!isset($current_field_array[$system_code]))
			{
				$fields_to_add[] = $row_values;
			}
		}
	
		//Insert new sections
		if(!empty($fields_to_add))
		{
			writeToInstallLog($admin_field_sections_package.' module found '.count($fields_to_add).' new admin field sections to add.');
			try
			{
				$results->getInsertMultipleRecords(__LINE__, __FILE__, 'admin_fields_sections', $column_names, $placeholders, $fields_to_add);
				writeToInstallLog('Successfully added all '.count($fields_to_add).' '.$admin_field_sections_package.' new admin field sections.');
			}
			catch(\Throwable $e)
			{
				writeToInstallLog('Failed adding admin field sections: '.$e->getMessage());
			}
		}
		else
		{
			writeToInstallLog('No new admin field sections require adding.');
		}
	}

	writeToInstallLog('Completed admin field sections update check.');
}
catch(\Throwable $e)
{
	writeToInstallLog('Failed comparing admin field sections: '.$e->getMessage());
}