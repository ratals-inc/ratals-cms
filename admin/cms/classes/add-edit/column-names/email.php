<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/email.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/email.php');
}
else
{
	if(!class_exists('email_aecn'))
	{
		class email_aecn
		{
			public function email_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				//Check to make sure the email is not being used by another user. For admin users.
				if(isset($post_values['users']['user_email_address']) && !empty($post_values['users']['user_email_address']))
				{
					$username_exist = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'users', 'WHERE `email` = ? AND `id` != ?', [$post_values[$table_name][$admin_field["column_name"]], trim($_GET["rid"] ?? '')]);
					
					if(!empty($username_exist))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' "'.$post_values[$table_name][$admin_field["column_name"]].'" is already being used.';
					}
				}
				//Check to make sure the username/email is not being used by another user. For customer user.
				elseif(isset($post_values['customer_accounts']['email']) && !empty($post_values['customer_accounts']['email']))
				{
					$username_exist = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'customer_accounts', 'WHERE `site_id` = ? AND `email` = ? AND `id` != ?', [$_SESSION["site_set_for_editing"], $post_values[$table_name][$admin_field["column_name"]], trim($_GET["rid"] ?? '')]);
					
					if(!empty($username_exist))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' "'.$post_values[$table_name][$admin_field["column_name"]].'" is already being used.';
					}
				}
			}
		}
		
		$class_email_aecn = new email_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_email_aecn->email_aecn($table_name, $admin_field, $post_values, $errors);
	}
}