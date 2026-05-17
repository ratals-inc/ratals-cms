<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/display-as/displayColorSwatch.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/display-as/displayColorSwatch.php');
}
else
{
	if(!class_exists('displayColorSwatchAeda'))
	{
		class displayColorSwatchAeda
		{
			public function displayColorSwatchAeda($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == "custom_fields_options")
				{
					if(!empty(trim($_GET['sub-page-rid'] ?? '')))
					{
						$custom_field_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ?', [trim($_GET['sub-page-rid'] ?? '')]);
						$custom_field_type['search_as'] = $custom_field_type['cf_search_as'];
						$custom_field_type['display_as'] = $custom_field_type['cf_display_as'];
					}
					elseif(!empty(trim($_GET["rid"] ?? '')))
					{
						$custom_field_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
						$custom_field_type['search_as'] = $custom_field_type['cf_search_as'];
						$custom_field_type['display_as'] = $custom_field_type['cf_display_as'];
					}
				}
				
				if($_SESSION['admin_table_name'] == "form_values")
				{
					if(!empty(trim($_GET['sub-page-rid'] ?? '')))
					{
						$form_field_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [trim($_GET['sub-page-rid'] ?? '')]);
					}
					elseif(!empty(trim($_GET["rid"] ?? '')))
					{
						$form_field_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
					}
				}
				
				//Only swatches validate for a color or media to be set in the dropdown.
				if((isset($custom_field_type) && $custom_field_type['display_as'] == 'swatch') || (isset($form_field_type) && $form_field_type['form_field_type'] == 'Swatch'))
				{
					if(empty($post_values[$table_name]['display']))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' cannot be empty.';
					}
				}
			}
		}
		
		$class_displayColorSwatchAeda = new displayColorSwatchAeda();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_displayColorSwatchAeda->displayColorSwatchAeda($table_name, $admin_field, $post_values, $errors);
	}
}