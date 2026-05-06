<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/url_name.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/url_name.php');
}
else
{
	if(!class_exists('url_name_aecn'))
	{
		class url_name_aecn
		{
			public function url_name_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'admin_fields' && isset($_POST[$table_name]['url_name']))
				{
					if(!empty($_POST[$table_name]['url_name']))
					{
						$value = $_POST[$table_name]['url_name'];
					}
					else
					{
						$value = $_POST[$table_name]['name'];
					}
					
					$value = str_replace(array(' ', '_'), "-", trim(strtolower($value)));
					$value = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $value);
					$_POST[$table_name]['url_name'] = $value;
					$post_values[$table_name]['url_name'] = $value;
					
					if($_SESSION['admin_type'] == 'edit')
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` != ? AND  `url_name` = ?', [trim($_GET["rid"] ?? ''), $value]);
					}
					else
					{
						$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `url_name` = ?', [$value]);
					}
					
					if(!empty($duplicate_value) && !isset($errors[$table_name]['url_name']))
					{
						$errors[$table_name]['url_name'] = $admin_field["name"].' is already being used on another admin field.';
					}
				}
			}
		}
		
		$class_url_name_aecn = new url_name_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_url_name_aecn->url_name_aecn($table_name, $admin_field, $post_values, $errors);
	}
}