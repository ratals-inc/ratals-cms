<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/admin_name.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/admin_name.php');
}
else
{
	if(!class_exists('admin_name_aecn'))
	{
		class admin_name_aecn
		{
			public function admin_name_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'form_fields' && isset($_POST[$table_name]['admin_name']) && !empty($_POST[$table_name]['admin_name']))
				{
					$admin_name = str_replace(array(' ', '-'), "_", trim(strtolower($_POST[$table_name]['admin_name'])));
					$admin_name = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $admin_name);
					$_POST[$table_name]['admin_name'] = $admin_name;
					$post_values[$table_name]['admin_name'] = $admin_name;
					
					if($_SESSION['admin_type'] == 'edit')
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` != ? AND `admin_name` = ?', [trim($_GET["rid"] ?? ''), $admin_name]);
					}
					else
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `admin_name` = ?', [$admin_name]);
					}
					
					if(!empty($duplicate_value) && !isset($errors[$table_name]['admin_name']))
					{
						$errors[$table_name]['admin_name'] = $admin_field["name"].' is already being used on another form field.';
					}
				}
			}
		}
		
		$class_admin_name_aecn = new admin_name_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_admin_name_aecn->admin_name_aecn($table_name, $admin_field, $post_values, $errors);
	}
}