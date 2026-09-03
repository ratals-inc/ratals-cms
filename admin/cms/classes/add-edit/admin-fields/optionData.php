<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/optionData.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/optionData.php');
}
else
{
	if(!class_exists('optionDataAeaf'))
	{
		class optionDataAeaf
		{
			public function optionDataAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				
				
				$admin_field_values = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$admin_field["admin_fields_lists_system_code"]], 'value');
				
				$site_settings_languages = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'sites', 'ORDER BY `site_language` ASC', []);
				
				$field_value_array = array();
				$site_languages_unique_list  = array();
				
				if(!empty($field_value))
				{
					$field_value_array = JSON_DECODE($field_value ?? '', true);
				}
				
				if(!empty($site_settings_languages))
				{
					foreach($site_settings_languages as $site_settings_language)
					{
						if(!in_array($site_settings_language['site_language'], $site_languages_unique_list))
						{
							$site_languages_unique_list[] = $site_settings_language['site_language'];
							
							$admin_field_data_label = '';
							if(isset($field_value_array[$site_settings_language['site_language']]['label']) && !empty($field_value_array[$site_settings_language['site_language']]['label']))
							{
								$admin_field_data_label = $field_value_array[$site_settings_language['site_language']]['label'];
							}
							
							$admin_field_data_value = '';
							if(isset($field_value_array[$site_settings_language['site_language']]['value']) && !empty($field_value_array[$site_settings_language['site_language']]['value']))
							{
								$admin_field_data_value = $field_value_array[$site_settings_language['site_language']]['value'];
							}
							
							echo '<div class="options-data"><div class="title">'.$admin_field_values[$site_settings_language['site_language']]['label'].'</div>';
							
							echo '
							<div class="edit options-data-bottom '.htmlspecialchars($admin_field["url_name"] ?? '').'">
							<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
							<div class="edit-field">
							<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']['.$site_settings_language['site_language'].'][label]' ?? '').'" value="'.htmlspecialchars($admin_field_data_label ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
							<div class="small-text">'.$admin_field["notes"].'</div>
							</div>';
							if(isset($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['label'])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['label'] ?? '').'</div>'; }
							echo '</div>';
							
							if($_SESSION['admin_type'] == 'edit' && !empty($admin_field_data_value))
							{
								echo '
								<div class="edit options-data-bottom '.htmlspecialchars($admin_field["url_name"] ?? '').'">
								<div class="edit-label">URL Value</div>
								<div class="edit-field text">
								'.htmlspecialchars($admin_field_data_value ?? '').'
								<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']['.$site_settings_language['site_language'].'][value]' ?? '').'" type="hidden" value="'.htmlspecialchars($admin_field_data_value ?? '').'">
								<div class="small-text">The URL value used to identify this option in URLs. This cannot be changed after the option is created.</div>
								</div>';
								if(isset($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['value'])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['value'] ?? '').'</div>'; }
								echo '</div>';
							}
							else
							{
								echo '
								<div class="edit options-data-bottom '.htmlspecialchars($admin_field["url_name"] ?? '').'">
								<div class="edit-label">URL Value</div>
								<div class="edit-field">
								<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']['.$site_settings_language['site_language'].'][value]' ?? '').'" value="'.htmlspecialchars($admin_field_data_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
								<div class="small-text">Enter the URL value used to identify this option in URLs. Use only lowercase letters, numbers, and dashes. Example: option-value</div>
								</div>';
								if(isset($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['value'])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]][$site_settings_language['site_language']]['value'] ?? '').'</div>'; }
								echo '</div>';
							}
							
							echo '</div>';
						}
					}
				}
			}
		}
		
		$class_optionDataAeaf = new optionDataAeaf();
	}
	
	$class_optionDataAeaf->optionDataAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}