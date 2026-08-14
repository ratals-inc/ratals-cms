<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/admin_directory.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/admin_directory.php');
}
else
{
	if(!class_exists('admin_directory_aecn'))
	{
		class admin_directory_aecn
		{
			public function admin_directory_aecn($table_name, $admin_field, &$post_values, &$errors, $current_values)
			{
				//If Admin Directory URL Name is changed make sure the new directory name doesn't already exist.
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					if(empty(trim($_POST[$table_name][$admin_field["column_name"]] ?? '', '-')))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' cannot be empty or only contain dashes. Enter a valid directory name.';
					}
					elseif(preg_match('/[^a-z\-0-9]/i', $_POST[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Enter a vaild directory name that contains only a-z, 0-9, or dashes';
					}
					elseif($_SESSION['admin_type'] == 'edit' && $_POST[$table_name][$admin_field["column_name"]] != $current_values[$table_name][$admin_field["column_name"]] && file_exists(INSTALLATION_ROOT."/".$_POST[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is being used on another directory here: '.INSTALLATION_ROOT."/".$_POST[$table_name][$admin_field["column_name"]];
					}
					elseif($_SESSION['admin_type'] == 'edit' && (strtolower($_POST[$table_name][$admin_field["column_name"]]) == 'admin' || strtolower($_POST[$table_name][$admin_field["column_name"]]) == 'administrator' || strtolower($_POST[$table_name][$admin_field["column_name"]]) == 'root' || strtolower($_POST[$table_name][$admin_field["column_name"]]) == 'login' || strtolower($_POST[$table_name][$admin_field["column_name"]]) == 'backend'))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' cannot be "admin," "administrator," "root," "login," or "backend." Select a unique and creative name to keep your backend login URL secure and harder for hackers to guess.';
					}
				}
			}
		}
		
		$class_admin_directory_aecn = new admin_directory_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_admin_directory_aecn->admin_directory_aecn($table_name, $admin_field, $post_values, $errors, $current_values);
	}
}