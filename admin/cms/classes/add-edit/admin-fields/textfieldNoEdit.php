<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/textfieldNoEdit.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/textfieldNoEdit.php');
}
else
{
	if(!class_exists('textfieldNoEditAeaf'))
	{
		class textfieldNoEditAeaf
		{
			public function textfieldNoEditAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				if($_SESSION['admin_type'] == 'edit')
				{
					if(isset($admin_field['custom_field_name']))
					{
						$custom_field_name = JSON_DECODE($admin_field['custom_field_name'] ?? '', true);
						
						$admin_field['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
					}
					
					$field_required = '';
					if($admin_field["required"] == 'Yes')
					{
						$field_required = ' <span class="required-asterisk">*</span>';
					}
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? '')).$field_required.'</div>
					<div class="edit-field text">
					'.htmlspecialchars($field_value ?? '').'
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>'; 
				}
				else
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
					
					$field_required = '';
					if($admin_field["required"] == 'Yes')
					{
						$field_required = ' <span class="required-asterisk">*</span>';
					}
					
					echo '
					<div class="edit'.$last_class_for_urls_table_fields.$url_name_class.'">
					<div class="edit-label">'.((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? '')).$field_required.'</div>
					<div class="edit-field">
					<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
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
		}
		
		$class_textfieldNoEditAeaf = new textfieldNoEditAeaf();
	}
	
	$class_textfieldNoEditAeaf->textfieldNoEditAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}