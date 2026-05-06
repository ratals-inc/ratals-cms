<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/displayColorSwatch.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/displayColorSwatch.php');
}
else
{
	if(!class_exists('displayColorSwatchAeaf'))
	{
		class displayColorSwatchAeaf
		{
			public function displayColorSwatchAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
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
				
				if((isset($custom_field_type) && ($custom_field_type['display_as'] == 'boxes' || $custom_field_type['display_as'] == 'swatch')) || (isset($form_field_type) && $form_field_type['form_field_type'] == 'Swatch'))
				{
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
					<div class="edit-field">
					<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
					<option value=""></option>
					';
					
					$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$admin_field["admin_fields_lists_system_code"]]);
		
					if(!empty($admin_field_values))
					{
						foreach($admin_field_values as $admin_field_value)
						{
							$selected_item = '';
							
							$admin_field_id = $admin_field_value["value"];
							$admin_field_label = $admin_field_value["label"];
							
							if($field_value == $admin_field_id)
							{
								$selected_item = " selected";
							}
							
							echo '<option value="'.htmlspecialchars($admin_field_id ?? '').'"'.$selected_item.'>'.htmlspecialchars($admin_field_label ?? '').'</option>';
						}
						
						
					}
					echo '
					</select>
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
		
		$class_displayColorSwatchAeaf = new displayColorSwatchAeaf();
	}
	
	$class_displayColorSwatchAeaf->displayColorSwatchAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}