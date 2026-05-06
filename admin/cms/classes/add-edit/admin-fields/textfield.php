<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/textfield.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/textfield.php');
}
else
{
	if(!class_exists('textfieldAeaf'))
	{
		class textfieldAeaf
		{
			public function textfieldAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$last_class_for_urls_table_fields = '';
				
				if($table_name == 'urls' && $admin_field["column_name"] == 'hreflang_url_id')
				{
					//If URLs and hreflang_url_id, add class to close out gray padding.
					$last_class_for_urls_table_fields .= ' url-last';
				}
				
				$url_name_class = '';
				if($admin_field["url_name"] != 'url')
				{
					$url_name_class = ' '.htmlspecialchars($admin_field["url_name"] ?? '');
				}
				
				if(isset($admin_field['custom_field_name']))
				{
					$custom_field_name = JSON_DECODE($admin_field['custom_field_name'] ?? '', true);
					
					$admin_field['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
				}
				
				echo '
				<div class="edit'.$last_class_for_urls_table_fields.$url_name_class.'">
				<div class="edit-label">'.((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? '')).'</div>
				<div class="edit-field">
				<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'" placeholder="'.htmlspecialchars($admin_field["placeholder"] ?? '').'">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
				
				if($table_name == 'urls' && $admin_field["column_name"] == 'hreflang_url_id')
				{
					//Close URLs gray backgound area.
					echo '</div>';
				}
			}
		}
		
		$class_textfieldAeaf = new textfieldAeaf();
	}
	
	$class_textfieldAeaf->textfieldAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}