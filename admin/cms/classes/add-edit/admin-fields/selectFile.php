<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/selectFile.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/selectFile.php');
}
else
{
	if(!class_exists('selectFileAeaf'))
	{
		class selectFileAeaf
		{
			public function selectFileAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
				<div class="edit-field text">
				<input type="file" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'" class="padding-border-none">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_selectFileAeaf = new selectFileAeaf();
	}
	
	$class_selectFileAeaf->selectFileAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}