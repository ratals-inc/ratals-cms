<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/lead.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/lead.php');
}
else
{
	if(!class_exists('leadAeaf'))
	{
		class leadAeaf
		{
			public function leadAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$lead_content = htmlspecialchars($field_value ?? '');
				$lead_content = str_replace('&lt;br&gt;', '<br>', $lead_content);
				$lead_content = str_replace('&lt;strong&gt;', '<strong>', $lead_content);
				$lead_content = str_replace('&lt;/strong&gt;', '</strong>', $lead_content);
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field text">
				'.$lead_content.'
				<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_leadAeaf = new leadAeaf();
	}
	
	$class_leadAeaf->leadAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}