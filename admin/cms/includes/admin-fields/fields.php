<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/fields.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/fields.php');
}
else
{
	$_SESSION['multiple_media_counter'] = 1;
	$_SESSION['date_counter'] = 0;
	
	if(!empty($admin_fields))
	{
		foreach($admin_fields as $table_name => $table_column)
		{
			foreach($table_column as $key => $admin_field) 
			{
				include(INSTALLATION_ROOT.'/admin/cms/includes/admin-fields/field-sections.php');
				
				//This section gets the db/current and post values to load admin_fields db table.
				if($admin_field['display_in_admin'] == 'Yes')
				{
					//if add or edit is posted in, display form with posted values.
					if(!empty($post_values))
					{
						foreach($post_values as $key_1 => $value_1)
						{
							foreach($value_1 as $key_2 => $value_2)
							{
								if($key_1.$key_2 == $table_name.$key)
								{
									$field_value = $value_2;
									break;
								}
							}
						}
					}
					//if edit page, get db/current values from admin_fields db table.
					elseif(isset($current_values[$table_name][$admin_field['column_name']]))
					{
						$field_value = $current_values[$table_name][$admin_field['column_name']]; 
					}
					//if db/current values and posted values are empty, display admin_fields as empty.
					else
					{
						$field_value = ''; 
					}
					
					//Get the field_display_as class so it can load in the admin page.
					//If you want to customize an existing /classes/add-edit/admin-fields/ file, copy the file to the folder of /hooks/classes/add-edit/admin-fields/. This allows you to edit existing files that software updates will not override.
					$field_display_as = $admin_field['display_as'];
					if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php');
					}
				}
				
				//This section gets the db/current and post values to load custom_fields db table.
				if($admin_field["column_name"] == 'custom_fields')
				{
					include_once INSTALLATION_ROOT.'/admin/cms/includes/custom-fields/fields.php';
				}
			}
		}
	}
}