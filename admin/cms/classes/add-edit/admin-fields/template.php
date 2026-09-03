<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/template.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/template.php');
}
else
{
	if(!class_exists('templateAeaf'))
	{
		class templateAeaf
		{
			public function templateAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
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
				<option value="">Select Template</option>
				';
				
				$template_data = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `status` = ?', [$_SESSION["site_set_for_editing"], 1]);
				
				$admin_field_list = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', [$admin_field["admin_fields_lists_system_code"]]);
				
				$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', $admin_field_list['dynamic_table_name'], 'WHERE `templates_id` = ? AND `status` = ? AND (`template_type` = ? OR `template_type` = ?) ORDER BY `name` ASC', [$template_data['id'], 1, 'all', $_SESSION['admin_table_name']]);
				
				if(!empty($admin_field_values))
				{
					foreach($admin_field_values as $admin_field_value)
					{
						$selected_item = '';
						
						$admin_field_id = $admin_field_value[$admin_field_list['dynamic_column_id']];
						$admin_field_label = $admin_field_value[$admin_field_list['dynamic_column_label']];
						
						if(!empty($admin_field_value[$admin_field_list['dynamic_column_id']]) && $field_value == $admin_field_value[$admin_field_list['dynamic_column_id']])
						{
							$selected_item = " selected";
						}
						
						$default_file = '';
						if($admin_field_value['default_file'] == 'Yes')
						{
							$default_file = ' - Default';
						}
						
						echo '<option value="'.htmlspecialchars($admin_field_id ?? '').'"'.$selected_item.'>'.htmlspecialchars($admin_field_label ?? '').$default_file.'</option>';
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
		
		$class_templateAeaf = new templateAeaf();
	}
	
	$class_templateAeaf->templateAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}