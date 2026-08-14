<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/customFieldType.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/customFieldType.php');
}
else
{
	if(!class_exists('customFieldTypeAeaf'))
	{
		class customFieldTypeAeaf
		{
			public function customFieldTypeAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $column_data_dynamic_lists, $label_names)
			{
				$display_hide_custom_fields_open = '';
				if($table_name == 'custom_fields')
				{
					//If custom_fields, add open div to hide/display all fields.
					$display_hide_custom_fields_open = ' <div id="display_custom_fields" class="display-as-none">';
				}
				
				if($_SESSION['admin_type'] == 'add')
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
					echo '</div>'.$display_hide_custom_fields_open; 
				}
				else
				{
					$display_field_value = '';
					if(!empty($admin_field['admin_fields_lists_system_code']) && isset($column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]) && isset($label_names[$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['table_name'].'_'.$admin_field['admin_fields_lists_system_code']][$field_value][$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['column_label']]) && !empty($label_names[$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['table_name'].'_'.$admin_field['admin_fields_lists_system_code']][$field_value][$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['column_label']]))
					{
						$display_field_value = $label_names[$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['table_name'].'_'.$admin_field['admin_fields_lists_system_code']][$field_value][$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['column_label']];
					}
					elseif(!empty($admin_field['admin_fields_lists_system_code']) && isset($label_names['admin_fields_values'][$field_value]['label']) && !empty($label_names['admin_fields_values'][$field_value]['label']))
					{
						$display_field_value = $label_names['admin_fields_values'][$field_value]['label'];
					}
					else
					{
						$display_field_value = $field_value;
					}
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
					<div class="edit-field text">
					'.htmlspecialchars($display_field_value ?? '').'
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>'.$display_hide_custom_fields_open;
				}
			}
		}
		
		$class_customFieldTypeAeaf = new customFieldTypeAeaf();
	}
	
	$class_customFieldTypeAeaf->customFieldTypeAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $column_data_dynamic_lists, $label_names);
}