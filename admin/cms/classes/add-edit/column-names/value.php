<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/value.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/value.php');
}
else
{
	if(!class_exists('value_aecn'))
	{
		class value_aecn
		{
			public function value_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'form_values' && isset($_POST[$table_name]['value']) && !empty($_POST[$table_name]['value']))
				{
					$value = str_replace(array(' ', '-'), "_", trim(strtolower($_POST[$table_name]['value'])));
					$value = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $value);
					$_POST[$table_name]['value'] = $value;
					$post_values[$table_name]['value'] = $value;
					
					if($_SESSION['admin_type'] == 'edit')
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_values', 'WHERE `id` != ? AND  `form_fields_id` = ? AND `value` = ?', [trim($_GET["rid"] ?? ''), trim($_GET['sub-page-rid'] ?? ''), $value]);
					}
					else
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `value` = ?', [trim($_GET["rid"] ?? ''), $value]);
					}
					
					if(!empty($duplicate_value) && !isset($errors[$table_name]['value']))
					{
						$errors[$table_name]['value'] = $admin_field["name"].' is already being used on another form field.';
					}
				}
			}
		}
		
		$class_value_aecn = new value_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_value_aecn->value_aecn($table_name, $admin_field, $post_values, $errors);
	}
}