<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/customFieldName.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/customFieldName.php');
}
else
{
	if(!class_exists('customFieldNameAeaf'))
	{
		class customFieldNameAeaf
		{
			public function customFieldNameAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $sql_sites_in_account)
			{
				$admin_field_values = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$admin_field["admin_fields_lists_system_code"]], 'value');
				
				$site_settings_languages = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'sites', 'ORDER BY `site_language` ASC', []);
				
				$field_value_array = array();
				$site_languages_unique_list  = array();
				
				if(!empty($field_value))
				{
					$field_value_array = JSON_DECODE($field_value ?? '', true);
				}
				
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				if(!empty($site_settings_languages))
				{
					foreach($site_settings_languages as $site_settings_language)
					{
						if(!in_array($site_settings_language['site_language'], $site_languages_unique_list))
						{
							$site_languages_unique_list[] = $site_settings_language['site_language'];
							
							$admin_field_data_label = '';
							if(isset($field_value_array[$site_settings_language['site_language']]['frontend_name']) && !empty($field_value_array[$site_settings_language['site_language']]['frontend_name']))
							{
								$admin_field_data_label = $field_value_array[$site_settings_language['site_language']]['frontend_name'];
							}
							
							$admin_field_data_value = '';
							if(isset($field_value_array[$site_settings_language['site_language']]['admin_name']) && !empty($field_value_array[$site_settings_language['site_language']]['admin_name']))
							{
								$admin_field_data_value = $field_value_array[$site_settings_language['site_language']]['admin_name'];
							}
							
							echo '<div class="options-data"><div class="title">'.$admin_field_values[$site_settings_language['site_language']]['label'].'</div>';
							
							echo '
							<div class="edit options-data-bottom '.htmlspecialchars($admin_field["url_name"] ?? '').'">
							<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
							<div class="edit-field">
							<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']['.$site_settings_language['site_language'].'][frontend_name]' ?? '').'" value="'.htmlspecialchars($admin_field_data_label ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
							<div class="small-text">'.$admin_field["notes"].'</div>
							</div>';
							if(isset($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['frontend_name'])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['frontend_name'] ?? '').'</div>'; }
							echo '</div>';
							
							if($_SESSION['admin_type'] == 'edit' && !empty($admin_field_data_value))
							{
								echo '
								<div class="edit options-data-bottom '.htmlspecialchars($admin_field["url_name"] ?? '').'">
								<div class="edit-label">URL Name</div>
								<div class="edit-field text">
								'.htmlspecialchars($admin_field_data_value ?? '').'
								<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']['.$site_settings_language['site_language'].'][admin_name]' ?? '').'" type="hidden" value="'.htmlspecialchars($admin_field_data_value ?? '').'">
								<div class="small-text">The URL key used to identify this field in URLs. This cannot be changed after the custom field is created.</div>
								</div>';
								if(isset($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['admin_name'])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['admin_name'] ?? '').'</div>'; }
								echo '</div>';
							}
							else
							{
								echo '
								<div class="edit options-data-bottom '.htmlspecialchars($admin_field["url_name"] ?? '').'">
								<div class="edit-label">URL Name</div>
								<div class="edit-field">
								<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']['.$site_settings_language['site_language'].'][admin_name]' ?? '').'" value="'.htmlspecialchars($admin_field_data_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
								<div class="small-text">Used to identify this field in URLs. This field is required, but you can leave it blank to automatically create it from the Name entered above. If entering it manually, use only lowercase letters, numbers, and dashes. Example: new-frontend-name</div>
								</div>';
								if(isset($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['admin_name'])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['admin_name'] ?? '').'</div>'; }
								echo '</div>';
							}
							
							echo '</div>';
						}
					}
				}
			}
		}
		
		$class_customFieldNameAeaf = new customFieldNameAeaf();
	}
	
	$class_customFieldNameAeaf->customFieldNameAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $sql_sites_in_account);
}