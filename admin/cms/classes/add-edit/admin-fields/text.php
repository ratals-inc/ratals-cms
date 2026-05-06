<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/text.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/text.php');
}
else
{
	if(!class_exists('textAeaf'))
	{
		class textAeaf
		{
			public function textAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $column_data_dynamic_lists, $label_names)
			{
				$display_field_value = '';
				if(!empty($admin_field['admin_fields_lists_system_code']) && isset($column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]) && isset($label_names[$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['dynamic_table_name'].'_'.$admin_field['admin_fields_lists_system_code']][$field_value][$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['dynamic_column_label']]) && !empty($label_names[$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['dynamic_table_name'].'_'.$admin_field['admin_fields_lists_system_code']][$field_value][$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['dynamic_column_label']]))
				{
					$display_field_value = $label_names[$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['dynamic_table_name'].'_'.$admin_field['admin_fields_lists_system_code']][$field_value][$column_data_dynamic_lists[$admin_field['admin_fields_lists_system_code']]['dynamic_column_label']];
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
				<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_textAeaf = new textAeaf();
	}
	
	$class_textAeaf->textAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $column_data_dynamic_lists, $label_names);
}