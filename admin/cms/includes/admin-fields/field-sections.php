<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/field-sections.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/field-sections.php');
}
else
{
	//This section inserts headers into the admin area between admin_fields.
	//Heading labels
	if(!isset($admin_fields_sections))
	{
		$admin_fields_sections = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_sections', '', [], 'before_admin_field_id');
	}
	
	if(array_key_exists($admin_field['id'], $admin_fields_sections))
	{
		$admin_fields_sections_notes = '';
		if(!empty($admin_fields_sections[$admin_field['id']]['notes']))
		{
			$admin_fields_sections_notes = '<div class="section-notes">'.$admin_fields_sections[$admin_field['id']]['notes'].'</div>';
		}
		
		echo '
		<div class="header-text margin-bottom-13 '.$admin_fields_sections[$admin_field['id']]['system_code'].'">
		  <div class="text float-none">'.htmlspecialchars($admin_fields_sections[$admin_field['id']]['field_section_name']).$admin_fields_sections_notes.'</div>
		</div>';
	}
}