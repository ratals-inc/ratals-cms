<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/option_data.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/option_data.php');
}
else
{
	if(!class_exists('option_data_aecn'))
	{
		class option_data_aecn
		{
			public function option_data_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'custom_fields_options' && isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					//loop through labels/values for each language.
					foreach($_POST[$table_name]['option_data'] as $option_languages => $option_value)
					{
						if(!isset($_POST[$table_name]['option_data'][$option_languages]['value']) || empty($_POST[$table_name]['option_data'][$option_languages]['value']))
						{
							$option_value = str_replace(array(' ', '_'), "-", trim(strtolower($_POST[$table_name]['option_data'][$option_languages]['label'])));
							$option_value = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $option_value);
							$post_values[$table_name]['option_data'][$option_languages]['label'] = $_POST[$table_name]['option_data'][$option_languages]['label'];
							
							$_POST[$table_name]['option_data'][$option_languages]['value'] = $option_value;
							$post_values[$table_name]['option_data'][$option_languages]['value'] = $option_value;
						}
						else
						{
							$option_value = str_replace(array(' ', '_'), "-", trim(strtolower($_POST[$table_name]['option_data'][$option_languages]['value'])));
							$option_value = str_replace(array("%", "+", "`", "~", "@", "#", "$", "^", "&", "(", ")", "=", "[", "]", "{", "}", ";", ":", '"', "'", ",", "?", "/", "|", "\\", "<", ">"), "", $option_value);
							$post_values[$table_name]['option_data'][$option_languages]['label'] = $_POST[$table_name]['option_data'][$option_languages]['value'];
							
							$_POST[$table_name]['option_data'][$option_languages]['value'] = $option_value;
							$post_values[$table_name]['option_data'][$option_languages]['value'] = $option_value;
						}
						
						if($_SESSION['admin_type'] == 'edit')
						{
							$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, 'JSON_UNQUOTE(JSON_EXTRACT(`option_data`, "$.'.$option_languages.'.value")) AS option_data', 'custom_fields_options', 'WHERE `id` != ? AND `custom_fields_id` = ? AND JSON_UNQUOTE(JSON_EXTRACT(`option_data`, "$.'.$option_languages.'.value")) = ?', [trim($_GET["rid"] ?? ''), trim($_GET['sub-page-rid'] ?? ''), $option_value]);
						}
						else
						{
							$duplicate_value = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, 'JSON_UNQUOTE(JSON_EXTRACT(`option_data`, "$.'.$option_languages.'.value")) AS option_data', 'custom_fields_options', 'WHERE `custom_fields_id` = ? AND JSON_UNQUOTE(JSON_EXTRACT(`option_data`, "$.'.$option_languages.'.value")) = ?', [trim($_GET["rid"] ?? ''), $option_value]);
						}
						
						if(!empty($duplicate_value) && !isset($errors[$table_name]['option_data'][$option_languages]['value']))
						{
							$errors[$table_name]['option_data'][$option_languages]['value'] = 'Value is already being used on another item.';
						}
						elseif(empty($_POST[$table_name]['option_data'][$option_languages]['label']))
						{
							$errors[$table_name]['option_data'][$option_languages]['label'] = 'Label cannot be empty.';
						}
						
						$post_values[$table_name][$admin_field["column_name"]][$option_languages] = $_POST[$table_name][$admin_field["column_name"]][$option_languages];
					}
					
					$post_values[$table_name][$admin_field["column_name"]] = JSON_ENCODE($post_values[$table_name][$admin_field["column_name"]]);
				}
			}
		}
		
		$class_option_data_aecn = new option_data_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_option_data_aecn->option_data_aecn($table_name, $admin_field, $post_values, $errors);
	}
}