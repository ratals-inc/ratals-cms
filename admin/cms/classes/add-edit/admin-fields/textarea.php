<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/textarea.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/textarea.php');
}
else
{
	if(!class_exists('textareaAeaf'))
	{
		class textareaAeaf
		{
			public function textareaAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$display_hide_custom_fields_close = '';
				if($table_name == 'custom_fields')
				{
					//If custom_fields, add close div to hide/display all fields.
					$display_hide_custom_fields_close = ' </div>';
				}
				
				if(isset($admin_field['custom_field_name']))
				{
					$custom_field_name = JSON_DECODE($admin_field['custom_field_name'] ?? '', true);
					
					$admin_field['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
				}
					
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? '')).'</div>
				<div class="edit-field">
				<textarea name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'" cols="" rows="8">'.htmlspecialchars($field_value ?? '').'</textarea>
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'.$display_hide_custom_fields_close; 
			}
		}
		
		$class_textareaAeaf = new textareaAeaf();
	}
	
	$class_textareaAeaf->textareaAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}