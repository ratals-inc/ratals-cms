<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/adminDirectoryUrlName.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/adminDirectoryUrlName.php');
}
else
{
	if(!class_exists('adminDirectoryUrlNameAeaf'))
	{
		class adminDirectoryUrlNameAeaf
		{
			public function adminDirectoryUrlNameAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">
				<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_adminDirectoryUrlNameAeaf = new adminDirectoryUrlNameAeaf();
	}
	
	$class_adminDirectoryUrlNameAeaf->adminDirectoryUrlNameAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}