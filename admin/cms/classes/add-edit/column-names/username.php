<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/username.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/username.php');
}
else
{
	if(!class_exists('username_aecn'))
	{
		class username_aecn
		{
			public function username_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				//Check to make sure the username is not being used by another user. For admin users.
				if(isset($post_values['users']['username']) && !empty($post_values['users']['username']))
				{
					$username_exist = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'users', 'WHERE `username` = ? AND `id` != ?', [$post_values[$table_name][$admin_field["column_name"]], trim($_GET["rid"] ?? '')]);
					
					if(!empty($username_exist))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' "'.$post_values[$table_name][$admin_field["column_name"]].'" is already being used.';
					}
				}
			}
		}
		
		$class_username_aecn = new username_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		if(!empty($_POST[$_SESSION['admin_table_name']]) && $admin_field['update_field_on_save'] == 'Yes')
		{
			$class_username_aecn->username_aecn($table_name, $admin_field, $post_values, $errors);
		}
	}
}