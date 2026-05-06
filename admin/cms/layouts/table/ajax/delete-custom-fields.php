<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-custom-fields.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-custom-fields.php');
}
else
{
	//Delete Custom Column Row and Records
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-custom-fields')
	{
		foreach($_POST['deleteRow'] as $row_id)
		{
			$select_custom_field_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ? AND (`site_id` = ? OR `site_id` = ?)', [$row_id, $_SESSION["site_set_for_editing"], '0']);	
			
			if(!empty($select_custom_field_row))
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'custom_fields_options', 'WHERE (`site_id` = ? || `site_id` = ?) AND `custom_fields_id` = ?', [$_SESSION["site_set_for_editing"], '0', $select_custom_field_row["id"]]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'assigned_fields', 'WHERE `field_id` = ? AND `table_name` = ? AND `default_or_custom` = ?', [$select_custom_field_row["id"], $select_custom_field_row["assigned_to"], 'custom']);
				
				if($select_custom_field_row['field_type'] == 'Inventory Attribute')
				{
					$select_custom_field_ids = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'products', 'WHERE `inventory_attribute_ids` LIKE ? AND `product_type` = ?', ['%,'.$row_id.',%', 'Inventory Items']);
					
					if(!empty($select_custom_field_ids))
					{
						foreach($select_custom_field_ids as $select_custom_field_ids_row)
						{
							$attributes_left = '';
							
							$select_custom_field_ids_data = trim($select_custom_field_ids_row['inventory_attribute_ids'], ',');
							$select_custom_field_id_array = explode(',', $select_custom_field_ids_data);
							
							foreach($select_custom_field_id_array as $select_custom_field_id)
							{
								if($select_custom_field_id != $row_id)
								{
									$attributes_left .= $select_custom_field_id.',';
								}
							}
							
							if(!empty($attributes_left))
							{
								$attributes_left = ','.$attributes_left;
							}
							
							$results->getUpdateRecord(__LINE__, __FILE__, 'products', '`inventory_attribute_ids` = ? ', 'WHERE `id` = ?', [$attributes_left, $select_custom_field_ids_row['id']]);
						}
					}
				}
				elseif($select_custom_field_row['field_type'] == 'Product Option')
				{
					$select_custom_field_ids = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'products', 'WHERE `product_option_ids` LIKE ? AND `product_type` = ?', ['%,'.$row_id.',%', 'Inventory Items']);
					
					if(!empty($select_custom_field_ids))
					{
						foreach($select_custom_field_ids as $select_custom_field_ids_row)
						{
							$options_left = '';
							
							$select_custom_field_ids_data = trim($select_custom_field_ids_row['product_option_ids'], ',');
							$select_custom_field_id_array = explode(',', $select_custom_field_ids_data);
							
							foreach($select_custom_field_id_array as $select_custom_field_id)
							{
								if($select_custom_field_id != $row_id)
								{
									$options_left .= $select_custom_field_id.',';
								}
							}
							
							if(!empty($options_left))
							{
								$options_left = ','.$options_left;
							}
							
							$results->getUpdateRecord(__LINE__, __FILE__, 'products', '`product_option_ids` = ? ', 'WHERE `id` = ?', [$options_left, $select_custom_field_ids_row['id']]);
						}
					}
				}
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'custom_fields', 'WHERE `id` = ? AND `assigned_to` = ?', [$select_custom_field_row["id"], $select_custom_field_row["assigned_to"]]);
			}
		}
		
		echo "1";
		exit;
	}
}