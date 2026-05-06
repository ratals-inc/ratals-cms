<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/custom-fields/fields.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/custom-fields/fields.php');
}
else
{
	//Get all custom columns that are an inventory attribute / Display inventory attributes on the inventory admin pages.
	if($_SESSION['admin_table_name'] == "inventory")
	{
		//Get the inventory attribute ids that are assigned on the inventory so it only display what is assigned.
		$attribute_ids_data = explode(',', trim($current_values['inventory']['attribute_ids_set'] ?? '', ','));
		$attribute_ids_placeholders = implode(',', array_fill(0, count($attribute_ids_data), '?'));
		$parameters = array_merge(array($_SESSION["site_set_for_editing"], 0, $_SESSION['admin_table_name'], 1, 'Inventory Attribute'), $attribute_ids_data, $attribute_ids_data);
		
		//Query db for all custom_fields associated with the record id set in url.
		$attribute_custom_fields = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE (`site_id` = ? OR `site_id` = ?) AND `assigned_to` = ? AND `status` = ? AND `field_type` = ? AND `id` IN ('.$attribute_ids_placeholders.') ORDER BY FIELD(`id`, '.$attribute_ids_placeholders.')', $parameters);
		
		if(!empty($attribute_custom_fields))
		{
			echo '<!-- Start Header -->
			<div class="header-text margin-bottom-13">
			<div class="text">Inventory Attributes</div>
			<div class="toggle-results">Results</div>
			</div>
			<!-- End Header -->';
			
			//This section gets the db/current and post values to load attribute custom_fields db table.
			foreach($attribute_custom_fields as $attribute_custom_field)
			{
				//Set cf_search_as to search_as and cf_display_as to display_as so it is set for admin/includes/admin-fields/field_classes.php / $adminFields->class_names
				$attribute_custom_field['search_as'] = $attribute_custom_field['cf_search_as'];
				$attribute_custom_field['display_as'] = $attribute_custom_field['cf_display_as'];
				
				//Get current value from the custom_fields column to set selected option.
				$saved_option_values = JSON_DECODE($current_values[$attribute_custom_field["assigned_to"]]['custom_fields'] ?? '', true);
				
				//if add or edit is posted in, display custom_fields with posted values.
				if(!empty($post_custom_field_values))
				{
					foreach($post_custom_field_values as $key => $value)
					{
						if($key == $attribute_custom_field["column_name"])
						{
							$attribute_custom_field_value = $value;
							break;
						}
					}
				}
				//if edit page, get db/current values from attribute custom_fields db table.
				elseif(isset($saved_option_values[$attribute_custom_field['column_name']]))
				{
					$attribute_custom_field_value = $saved_option_values[$attribute_custom_field['column_name']];
				}
				//if db/current values and posted values are empty, display attribute custom_fields as empty.
				else 
				{
					$attribute_custom_field_value = '';
				}
				
				//Save parent / original table name that this is looping in.
				$original_table_name = $table_name;
				
				$table_name = $attribute_custom_field["assigned_to"];
				$admin_field = $attribute_custom_field;
				$field_value = $attribute_custom_field_value;
				
				//Get dropdown $adminFields class methods to load add and edit attribute custom fields. All attributes load as a dropdown on inventory so we only need to call the dropdown method.
				//Get the field class so it can load in the admin page.
				$field_display_as = $admin_field['display_as'];
				if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
				{
					include($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php');
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
				{
					include($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php');
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
				{
					include($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php');
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
				{
					include($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php');
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
				{
					include($_SERVER['DOCUMENT_ROOT'].'/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php');
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
				{
					include($_SERVER['DOCUMENT_ROOT'].'/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php');
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
				{
					include($_SERVER['DOCUMENT_ROOT'].'/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php');
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
				{
					include($_SERVER['DOCUMENT_ROOT'].'/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php');
				}
				
				//Set $table_name back to original parnet loop name that this is running here: admin/includes/custom-fields/fields.php. Setting it back in case there are more fields after custom_fields.
				$table_name = $original_table_name;
			}
		}
	}
	
	//Get all custom columns that are a custom content field
	$content_custom_fields = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE (`site_id` = ? OR `site_id` = ?) AND `assigned_to` = ? AND `status` = ? AND `field_type` = ? ORDER BY `column_name` ASC', [$_SESSION["site_set_for_editing"], 0, $_SESSION['admin_table_name'], 1, 'Content Field']);
	
	//Display Custom Fields
	//Dont display custom fields on add media admin page.
	if(!empty($content_custom_fields) && $_SESSION['admin_table_name'] != 'media' && $_SESSION['admin_type'] != 'add')
	{
		$multiple_media_counter = 0;
		
		if($_SESSION['admin_class'] != 'global-custom-fields')
		{
			echo '<!-- Start Header -->
			<div class="header-text margin-bottom-13">
			<div class="text">Content Custom Fields</div>
			<div class="toggle-results">Results</div>
			</div>
			<!-- End Header -->';
		}
		
		//This section gets the db/current and post values to load content custom_fields db table.
		foreach($content_custom_fields as $content_custom_field)
		{
			//Set cf_search_as to search_as and cf_display_as to display_as so it is set for admin/includes/admin-fields/field_classes.php / $adminFields->class_names
			$content_custom_field['search_as'] = $content_custom_field['cf_search_as'];
			$content_custom_field['display_as'] = $content_custom_field['cf_display_as'];
				
			//Get current value from the custom_fields column to set selected option.
			$saved_option_values = JSON_DECODE($current_values[$content_custom_field["assigned_to"]]['custom_fields'] ?? '', true);
			
			if(!empty($post_custom_field_values))
			{
				foreach($post_custom_field_values as $key => $value)
				{
					if($key == $content_custom_field["column_name"])
					{
						$content_custom_field_value = $value;
						break;
					}
				}
			}
			//if edit page, get db/current values from content custom_fields db table.
			elseif(isset($saved_option_values[$content_custom_field['column_name']]))
			{
				$content_custom_field_value = $saved_option_values[$content_custom_field['column_name']]; 
			}
			//if db/current values and posted values are empty, display content custom_fields as empty.
			else 
			{
				$content_custom_field_value = '';
			}
			
			//Save parent / original table name that this is looping in.
			$original_table_name = $table_name;
			
			$table_name = $content_custom_field["assigned_to"];
			$admin_field = $content_custom_field;
			$field_value = $content_custom_field_value;
			
			//Get $adminFields class methods to load add and edit content custom fields. All content fields load as these fields so we only need to call the methods.
			//Get the field class so it can load in the admin page.
			$field_display_as = $admin_field['display_as'];
			if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
			{
				include($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php');
			}
			elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
			{
				include($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php');
			}
			elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
			{
				include($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php');
			}
			elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
			{
				include($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php');
			}
			elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
			{
				include($_SERVER['DOCUMENT_ROOT'].'/admin/cms/classes/add-edit/admin-fields/'.$field_display_as.'.php');
			}
			elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
			{
				include($_SERVER['DOCUMENT_ROOT'].'/admin/commerce/classes/add-edit/admin-fields/'.$field_display_as.'.php');
			}
			elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
			{
				include($_SERVER['DOCUMENT_ROOT'].'/admin/erp/classes/add-edit/admin-fields/'.$field_display_as.'.php');
			}
			elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php'))
			{
				include($_SERVER['DOCUMENT_ROOT'].'/admin/ai/classes/add-edit/admin-fields/'.$field_display_as.'.php');
			}
			
			//Set $table_name back to original parnet loop name that this is running here: admin/includes/custom-fields/fields.php. Setting it back in case there are more fields after custom_fields.
			$table_name = $original_table_name;
		}
	}
}