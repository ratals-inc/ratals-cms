<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/passwordAndConfirmPassword.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/passwordAndConfirmPassword.php');
}
else
{
	if(!class_exists('passwordAndConfirmPasswordAeaf'))
	{
		class passwordAndConfirmPasswordAeaf
		{
			public function passwordAndConfirmPasswordAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">
				<input type="password" autocomplete="new-password" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<div class="small-text">'.$admin_field["notes"].'
				<div class="password-requirements-wrap">
				<span>Password Requirements</span>
				<ul class="password-requirements">
				<li>At least 10 characters long</li>
				<li>At least one special character (e.g., `~!@#$%^&amp;*()-_+=[{]}\|;:\'",.?/)</li>
				<li>At least one letter (anywhere from A to Z)</li>
				<li>At least one number (from 0 to 9)</li>
				</ul>
				</div>
				</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'; 
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">Confirm Password</div>
				<div class="edit-field">
				<input type="password" autocomplete="new-password" name="'.htmlspecialchars($table_name.'[confirm_password]' ?? '').'" value="" id="'.htmlspecialchars($table_name ?? '').'_confirm_password">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_passwordAndConfirmPasswordAeaf = new passwordAndConfirmPasswordAeaf();
	}
	
	$class_passwordAndConfirmPasswordAeaf->passwordAndConfirmPasswordAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}