<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/clickPath.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/clickPath.php');
}
else
{
	if(!class_exists('clickPathAeaf'))
	{
		class clickPathAeaf
		{
			public function clickPathAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$field_value_array = explode(',', $field_value);
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field text">';
				echo '<ol>';
				foreach($field_value_array as $field_value_data)
				{
					echo '<li>'.$field_value_data.'</li>';
				}
				echo '</ol>';
				echo '<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_clickPathAeaf = new clickPathAeaf();
	}
	
	$class_clickPathAeaf->clickPathAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}