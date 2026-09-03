<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/textareaWithEditor.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/textareaWithEditor.php');
}
else
{
	if(!class_exists('textareaWithEditorAeaf'))
	{
		class textareaWithEditorAeaf
		{
			public function textareaWithEditorAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				include_once INSTALLATION_ROOT.'/admin/cms/includes/admin-fields/modify-js/editor.php';
				
				if(isset($admin_field['custom_field_name']))
				{
					$custom_field_name = JSON_DECODE($admin_field['custom_field_name'] ?? '', true);
					
					$admin_field['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
				}
				
				$admin_user_email_signature = '';
				if(empty($field_value) && $admin_field["column_name"] == 'sales_message' && isset($_SESSION['user_email_signature']) && !empty($_SESSION['user_email_signature']))
				{
					$field_value = $_SESSION['user_email_signature'];
				}
				
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? '')).$field_required.'</div>
				<div class="edit-field html-text-editor">';
				$editor_name = htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '');
				$editor_content = $field_value;
				include INSTALLATION_ROOT.'/admin/cms/includes/editor/editor.php';
				echo '</div>
				<div class="small-text">'.$admin_field["notes"].'</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_textareaWithEditorAeaf = new textareaWithEditorAeaf();
	}
	
	$class_textareaWithEditorAeaf->textareaWithEditorAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}