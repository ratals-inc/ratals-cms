<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/textfield.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/textfield.php');
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
				
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				if($_SESSION['admin_url'] == INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/menus/add' || $_SESSION['admin_url'] == INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/menus/edit')
				{
					if($admin_field["column_name"] == 'name')
					{
						$field_label = 'Admin Name';
						$field_notes = 'The name used to identify this menu within the admin. This name is not displayed on the frontend.';
					}
					elseif($admin_field["column_name"] == 'frontend_name')
					{
						$field_label = 'Frontend Name';
						$field_notes = 'The name that can be used to display this menu on the frontend, such as a heading above the menu.';
					}
					else
					{
						$field_label = ((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? ''));
						$field_notes = $admin_field["notes"];
					}
				}
				else
				{
					$field_label = ((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? ''));
					$field_notes = $admin_field["notes"];
				}
				
				echo '
				<div class="edit'.$last_class_for_urls_table_fields.$url_name_class.'">
				<div class="edit-label">'.$field_label.$field_required.'</div>
				<div class="edit-field">
				<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'" placeholder="'.htmlspecialchars($admin_field["placeholder"] ?? '').'">
				<div class="small-text">'.$field_notes.'</div>
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