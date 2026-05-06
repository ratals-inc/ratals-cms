<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/admin_form_name.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/admin_form_name.php');
}
else
{
	if(!class_exists('admin_form_name_aecn'))
	{
		class admin_form_name_aecn
		{
			public function admin_form_name_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'forms' && isset($_POST[$table_name]['admin_form_name']) && !empty($_POST[$table_name]['admin_form_name']))
				{
					$admin_form_name = str_replace(array(' ', '-'), "_", trim(strtolower($_POST[$table_name]['admin_form_name'])));
					$admin_form_name = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $admin_form_name);
					$_POST[$table_name]['admin_form_name'] = $admin_form_name;
					$post_values[$table_name]['admin_form_name'] = $admin_form_name;
					
					if($_SESSION['admin_type'] == 'edit')
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'forms', 'WHERE `id` != ? AND `admin_form_name` = ?', [trim($_GET["rid"] ?? ''), $admin_form_name]);
					}
					else
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'forms', 'WHERE `admin_form_name` = ?', [$admin_form_name]);
					}
					
					if(!empty($duplicate_value) && !isset($errors[$table_name]['admin_form_name']))
					{
						$errors[$table_name]['admin_form_name'] = $admin_field["name"].' is already being used on another form.';
					}
				}
			}
		}
		
		$class_admin_form_name_aecn = new admin_form_name_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_admin_form_name_aecn->admin_form_name_aecn($table_name, $admin_field, $post_values, $errors);
	}
}