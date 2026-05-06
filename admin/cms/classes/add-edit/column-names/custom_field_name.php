<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/custom_field_name.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/custom_field_name.php');
}
else
{
	if(!class_exists('custom_field_name_aecn'))
	{
		class custom_field_name_aecn
		{
			public function custom_field_name_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'custom_fields' && isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					foreach($_POST[$table_name]['custom_field_name'] as $option_languages => $custom_field_names)
					{
						$url_name = trim(strtolower($_POST[$table_name][$admin_field["column_name"]][$option_languages]['frontend_name']));
						$url_name = str_replace(array(' ', '_'), "-", $url_name);
						$url_name = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $url_name);
						
						if(empty($_POST[$table_name][$admin_field["column_name"]][$option_languages]['admin_name']))
						{
							$admin_name = str_replace("_", "-", $url_name);
							$admin_name = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $admin_name);
						}
						else
						{
							$admin_name = trim(strtolower($_POST[$table_name][$admin_field["column_name"]][$option_languages]['admin_name']));
							$admin_name = str_replace(array(' ', '_'), "-", $admin_name);
							$admin_name = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $admin_name);
						}
						
						//Make sure first language name is filed in.
						if(!isset($first_language_flag) && empty($_POST[$table_name][$admin_field["column_name"]][$option_languages]['frontend_name']))
						{
							$first_language_flag = '';
							$errors[$table_name][$admin_field["column_name"]][$option_languages]['frontend_name'] = 'Frontend Name cannot be empty.';
						}
						else
						{
							$first_language_flag = '';
						}
						
						$_POST[$table_name][$admin_field["column_name"]][$option_languages] = array('frontend_name' => $_POST[$table_name][$admin_field["column_name"]][$option_languages]['frontend_name'], 'admin_name' => $admin_name);
						$post_values[$table_name][$admin_field["column_name"]][$option_languages] = array('frontend_name' => $_POST[$table_name][$admin_field["column_name"]][$option_languages]['frontend_name'], 'admin_name' => $admin_name);
						
						if(!empty($_POST[$table_name]['column_name']))
						{
							$column_name = trim(strtolower($_POST[$table_name]['column_name']));
							$column_name = str_replace(array(' ', '-'), "_", $column_name);
							$column_name = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $column_name);
							$_POST[$table_name]['column_name'] = $column_name;
							$post_values[$table_name]['column_name'] = $column_name;
							
							$duplicate_name = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` != ? AND `column_name` = ?', [trim($_GET["rid"] ?? ''), $column_name]);
							
							if(!empty($duplicate_name) && !isset($errors[$table_name]['column_name']))
							{
								$errors[$table_name]['column_name'] = 'Column Name is already being used on another custom field.';
							}
	
						}
						//Use the first custom_field_name as the column_name if one was not entered.
						elseif(!isset($post_values[$table_name]['column_name']))
						{
							$column_name_fill = str_replace(array(' ', '-'), "_", $admin_name);
							$_POST[$table_name]['column_name'] = $column_name_fill;
							$post_values[$table_name]['column_name'] = $column_name_fill;
							
							$duplicate_name = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` != ? AND `column_name` = ?', [trim($_GET["rid"] ?? ''), $column_name_fill]);
							
							if(!empty($duplicate_name) && !isset($errors[$table_name]['column_name']))
							{
								$errors[$table_name]['column_name'] = 'Column Name is already being used on another custom field.';
							}
						}
						
						if(!empty($_POST[$table_name]['url_name']))
						{
							$url_name = trim(strtolower($_POST[$table_name]['url_name']));
							$url_name = str_replace(array(' ', '_'), "-", $url_name);
							$url_name = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $url_name);
							$_POST[$table_name]['url_name'] = $url_name;
							$post_values[$table_name]['url_name'] = $url_name;
							
							$duplicate_name = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` != ? AND `url_name` = ?', [trim($_GET["rid"] ?? ''), $url_name]);
							
							if(!empty($duplicate_name) && !isset($errors[$table_name]['url_name']))
							{
								$errors[$table_name]['url_name'] = 'URL Name is already being used on another custom field.';
							}
	
						}
						//Use the first custom_field_name as the url_name if one was not entered.
						elseif(!isset($post_values[$table_name]['url_name']))
						{
							$_POST[$table_name]['url_name'] = $url_name;
							$post_values[$table_name]['url_name'] = $url_name;
							
							$duplicate_name = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` != ? AND `url_name` = ?', [trim($_GET["rid"] ?? ''), $url_name]);
							
							if(!empty($duplicate_name) && !isset($errors[$table_name]['url_name']))
							{
								$errors[$table_name]['url_name'] = 'URL Name is already being used on another custom field.';
							}
						}
					}
					
					$post_values[$table_name][$admin_field["column_name"]] = JSON_ENCODE($post_values[$table_name][$admin_field["column_name"]]);
				}
			}
		}
		
		$class_custom_field_name_aecn = new custom_field_name_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_custom_field_name_aecn->custom_field_name_aecn($table_name, $admin_field, $post_values, $errors);
	}
}