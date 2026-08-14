<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/textAsPercentage.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/textAsPercentage.php');
}
else
{
	if(!class_exists('textAsPercentageAeaf'))
	{
		class textAsPercentageAeaf
		{
			public function textAsPercentageAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field text">
				'.number_format(htmlspecialchars((float)$field_value ?? 0.00), '2').'%
				<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_textAsPercentageAeaf = new textAsPercentageAeaf();
	}
	
	$class_textAsPercentageAeaf->textAsPercentageAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}