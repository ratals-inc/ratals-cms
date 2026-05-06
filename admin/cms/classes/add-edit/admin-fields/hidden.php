<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/hidden.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/hidden.php');
}
else
{
	if(!class_exists('hiddenAeaf'))
	{
		class hiddenAeaf
		{
			public function hiddenAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				echo '
				<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
			}
		}
		
		$class_hiddenAeaf = new hiddenAeaf();
	}
	
	$class_hiddenAeaf->hiddenAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}