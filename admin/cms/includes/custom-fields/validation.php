<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/custom-fields/validation.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/custom-fields/validation.php');
}
else
{
	$post_custom_field_values = array();
	//Query db for all custom_fields associated with the record id set in url.
	if($_SESSION['admin_table_name'] == "inventory")
	{
		//Get the inventory attribute ids that are assigned on the inventory so it only validated what is assigned.
		$attribute_ids_data = explode(',', trim($current_values['inventory']['attribute_ids_set'] ?? '', ','));
		$attribute_ids_placeholders = implode(',', array_fill(0, count($attribute_ids_data), '?'));
		$parameters = array_merge(array($_SESSION["site_set_for_editing"], 0, $_SESSION['admin_table_name'], 1, 'Inventory Attribute'), $attribute_ids_data);
		
		$validate_custom_fields_inventory_arrtibutes = array();
		$validate_custom_fields_inventory_arrtibutes = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE (`site_id` = ? OR `site_id` = ?) AND `assigned_to` = ? AND `status` = ? AND `field_type` = ? AND `id` IN ('.$attribute_ids_placeholders.')', $parameters);
		
		$validate_custom_fields_content_fields = array();
		$validate_custom_fields_content_fields = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE (`site_id` = ? OR `site_id` = ?) AND `assigned_to` = ? AND `status` = ? AND `field_type` = ?', [$_SESSION["site_set_for_editing"], 0, $_SESSION['admin_table_name'], 1, 'Content Field']);
		
		//Merge custom fields of assigned inventory attributes with content fields for inventory admin page.
		$validate_custom_fields = array_merge($validate_custom_fields_inventory_arrtibutes, $validate_custom_fields_content_fields);
	}
	else
	{
		$validate_custom_fields = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE (`site_id` = ? OR `site_id` = ?) AND `assigned_to` = ? AND `status` = ?', [$_SESSION["site_set_for_editing"], 0, $_SESSION['admin_table_name'], 1]);
	}
	
	//echo '<pre>'; print_r($_POST); echo '</pre>';
	
	if(!empty($validate_custom_fields) && !empty($_POST) && !isset($_POST['change_site']))
	{
		foreach($validate_custom_fields as $custom_field)
		{
			$custom_field_name = JSON_DECODE($custom_field['custom_field_name'], true);
			$custom_field['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
			$custom_field['admin_name'] = $custom_field_name[$_SESSION['admin_language']]['admin_name'] ?? '';
			
			//echo '<pre>'; print_r($_POST[$custom_field['table_name']][$custom_field["column_name"]]); echo '</pre>';
			//Builld array to INSERT values in database.
			if(isset($_POST[$custom_field['assigned_to']][$custom_field["column_name"]]) && !empty($_POST[$custom_field['assigned_to']][$custom_field["column_name"]]) && $custom_field["cf_display_as"] == "singleMedia")
			{
				$media_string = '';
				foreach($_POST[$custom_field['assigned_to']][$custom_field["column_name"]] as $media_array)
				{
					$media_string .= $media_array[0].'~||~'.$media_array[1].'*||*';
				}
				$post_custom_field_values[$custom_field["column_name"]] = trim($media_string, '*||*');
			}
			elseif(isset($_POST[$custom_field['assigned_to']][$custom_field["column_name"]]) && !empty($_POST[$custom_field['assigned_to']][$custom_field["column_name"]]))
			{
				$post_custom_field_values[$custom_field["column_name"]] = $_POST[$custom_field['assigned_to']][$custom_field["column_name"]];
			}
			elseif(isset($_POST[$custom_field['assigned_to']][$custom_field["column_name"]]) && is_numeric($_POST[$custom_field['assigned_to']][$custom_field["column_name"]]))
			{
				$post_custom_field_values[$custom_field["column_name"]] = $_POST[$custom_field['assigned_to']][$custom_field["column_name"]];
			}
			else
			{
				$post_custom_field_values[$custom_field["column_name"]] = '';
			}
			
			//Validate form submit
			//Never validate product options as you do not set them on add or edit admin pages in admin. They are set within a static page in admin.
			if($custom_field['field_type'] != 'Product Option')
			{
				//Check to see if field was submitted empty and required.
				if(empty($post_custom_field_values[$custom_field["column_name"]]) && $custom_field["required"] == "Yes")
				{
					$errors[$custom_field['assigned_to']][$custom_field["column_name"]] = $custom_field["frontend_name"].' cannot be empty.';
				}
			}
		}
		
		if(!empty($post_custom_field_values))
		{
			$post_values[$custom_field['assigned_to']]['custom_fields'] = JSON_ENCODE($post_custom_field_values);
		}
	}
}