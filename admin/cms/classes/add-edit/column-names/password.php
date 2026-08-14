<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/password.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/password.php');
}
else
{
	if(!class_exists('password_aecn'))
	{
		class password_aecn
		{
			public function password_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				//Check to make sure password/confirm password matches and at least 10 characters long on edit. For admin users on EDIT.
				if($_SESSION['admin_type'] == 'edit' && ((isset($post_values['users']["password"]) && !empty($post_values['users']["password"])) || (isset($_POST['users']["confirm_password"]) && !empty($_POST['users']["confirm_password"]))))
				{
					$password_validation = passwordValidation($post_values['users']["password"]);
					
					if(strlen($post_values['users']["password"]) < 10)
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must be at least 10 characters in length.';
					}
					elseif($password_validation['sepcial_character_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 special character';
					}
					elseif($password_validation['letter_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 letter';
					}
					elseif($password_validation['number_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 number';
					}
					elseif($post_values['users']["password"] != $_POST['users']["confirm_password"])
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' and Confirm Password did not match.';
					}
					else
					{
						$post_values['users']["password"] = hash("sha512", $post_values['users']["password"]);
					}
				}
				//Check to make sure password/confirm password matches and at least 10 characters long on add. For admin users on ADD.
				elseif($_SESSION['admin_type'] == 'add' && isset($post_values['users']["password"]))
				{
					$password_validation = passwordValidation($post_values['users']["password"]);
					
					if(empty($post_values['users']["password"]))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must be at least 10 characters in length.';
					}
					elseif(strlen($post_values['users']["password"]) < 10)
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must be at least 10 characters in length.';
					}
					elseif($password_validation['sepcial_character_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 special character';
					}
					elseif($password_validation['letter_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 letter';
					}
					elseif($password_validation['number_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 number';
					}
					elseif($post_values['users']["password"] != $_POST['users']["confirm_password"])
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' and Confirm Password did not match.';
					}
					else
					{
						$post_values['users']["password"] = hash("sha512", $post_values['users']["password"]);
					}
				}
				//Check to make sure password/confirm password matches and at least 10 characters long on edit. For customers on EDIT.
				elseif($_SESSION['admin_type'] == 'edit' && ((isset($post_values['customer_accounts']["password"]) && !empty($post_values['customer_accounts']["password"])) || (isset($_POST['customer_accounts']["confirm_password"]) && !empty($_POST['customer_accounts']["confirm_password"]))))
				{
					$password_validation = passwordValidation($post_values['customer_accounts']["password"]);
					
					if(strlen($post_values['customer_accounts']["password"]) < 10)
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must be at least 10 characters in length.';
					}
					elseif($password_validation['sepcial_character_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 special character';
					}
					elseif($password_validation['letter_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 letter';
					}
					elseif($password_validation['number_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 number';
					}
					elseif($post_values['customer_accounts']["password"] != $_POST['customer_accounts']["confirm_password"])
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' and Confirm Password did not match.';
					}
					else
					{
						$post_values['customer_accounts']["password"] = hash("sha512", $post_values['customer_accounts']["password"]);
					}
				}
				//Check to make sure password/confirm password matches and at least 10 characters long on add. For customers on ADD.
				elseif($_SESSION['admin_type'] == 'add' && isset($post_values['customer_accounts']["password"]))
				{
					$password_validation = passwordValidation($post_values['customer_accounts']["password"]);
					
					if(empty($post_values['customer_accounts']["password"]))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must be at least 10 characters in length.';
					}
					elseif(strlen($post_values['customer_accounts']["password"]) < 10)
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must be at least 10 characters in length.';
					}
					elseif($password_validation['sepcial_character_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 special character';
					}
					elseif($password_validation['letter_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 letter';
					}
					elseif($password_validation['number_in_password'] == 'No')
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' must have at least 1 number';
					}
					elseif($post_values['customer_accounts']["password"] != $_POST['customer_accounts']["confirm_password"])
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' and Confirm Password did not match.';
					}
					else
					{
						$post_values['customer_accounts']["password"] = hash("sha512", $post_values['customer_accounts']["password"]);
					}
				}
			}
		}
		
		$class_password_aecn = new password_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_password_aecn->password_aecn($table_name, $admin_field, $post_values, $errors);
	}
}