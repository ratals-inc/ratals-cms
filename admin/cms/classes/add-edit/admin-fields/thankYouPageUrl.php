<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/thankYouPageUrl.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/thankYouPageUrl.php');
}
else
{
	if(!class_exists('thankYouPageUrlAeaf'))
	{
		class thankYouPageUrlAeaf
		{
			public function thankYouPageUrlAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
				<div class="edit-field">
				<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<option value="">In Form Thank You Message - No redirect to a thank you page URL</option>
				';
				
				//Get admin_fields_lists table to check if list is dynamic or not.
				$admin_field_list = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', [$admin_field["admin_fields_lists_system_code"]]);
				
				$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', $admin_field_list['dynamic_table_name'], ' ORDER BY `'.$admin_field_list['dynamic_column_label'].'` ASC', []);
		
				if(!empty($admin_field_values))
				{
					foreach($admin_field_values as $admin_field_value)
					{
						$selected_item = '';
						
						if(isset($admin_field_value[$admin_field_list['dynamic_column_label']]))
						{
							$admin_field_id = $admin_field_value['id'];
							$admin_field_label = $admin_field_value[$admin_field_list['dynamic_column_label']];
							
							if(!empty($admin_field_value['id']) && $field_value == $admin_field_value['id'])
							{
								$selected_item = " selected";
							}
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
		}
		
		$class_thankYouPageUrlAeaf = new thankYouPageUrlAeaf();
	}
	
	$class_thankYouPageUrlAeaf->thankYouPageUrlAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}