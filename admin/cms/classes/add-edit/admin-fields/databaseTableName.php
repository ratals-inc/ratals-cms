<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/databaseTableName.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/databaseTableName.php');
}
else
{
	if(!class_exists('databaseTableNameAeaf'))
	{
		class databaseTableNameAeaf
		{
			public function databaseTableNameAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				if($_SESSION['admin_type'] == 'add')
				{
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="edit-field">
					<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>';
				}
				else
				{
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="edit-field text">
					'.htmlspecialchars($field_value ?? '').'
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>';
				}
			}
		}
		
		$class_databaseTableNameAeaf = new databaseTableNameAeaf();
	}
	
	$class_databaseTableNameAeaf->databaseTableNameAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}