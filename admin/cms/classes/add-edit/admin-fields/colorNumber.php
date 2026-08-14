<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/colorNumber.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/colorNumber.php');
}
else
{
	if(!class_exists('colorNumberAeaf'))
	{
		class colorNumberAeaf
		{
			public function colorNumberAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
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
				
				//Only swatches and boxes display select a color for a swatch display.
				if((isset($custom_field_type) && $custom_field_type['display_as'] == 'swatch') || (isset($form_field_type) && $form_field_type['form_field_type'] == 'Swatch'))
				{
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
					<div class="edit-field">
					<input type="color" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'" class="height-padding-cursor">
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>'; 
				}
				else
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
			}
		}
		
		$class_colorNumberAeaf = new colorNumberAeaf();
	}
	
	$class_colorNumberAeaf->colorNumberAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}