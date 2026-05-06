<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/hiddenOnAddTextfieldOnEdit.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/hiddenOnAddTextfieldOnEdit.php');
}
else
{
	if(!class_exists('hiddenOnAddTextfieldOnEditAeaf'))
	{
		class hiddenOnAddTextfieldOnEditAeaf
		{
			public function hiddenOnAddTextfieldOnEditAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				if($_SESSION['admin_type'] == 'add')
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
				else
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
		}
		
		$class_hiddenOnAddTextfieldOnEditAeaf = new hiddenOnAddTextfieldOnEditAeaf();
	}
	
	$class_hiddenOnAddTextfieldOnEditAeaf->hiddenOnAddTextfieldOnEditAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}