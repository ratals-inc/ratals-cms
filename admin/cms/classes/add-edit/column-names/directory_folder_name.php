<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/directory_folder_name.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/directory_folder_name.php');
}
else
{
	if(!class_exists('directory_folder_name_aecn'))
	{
		class directory_folder_name_aecn
		{
			public function directory_folder_name_aecn($table_name, $admin_field, &$post_values, &$errors, $current_values)
			{
				//If template directory name is changed or added, make sure the new template directory name doesn't already exist.
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					$template_file_type = array();
					if($_SESSION['admin_type'] == 'add')
					{
						$active_template = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `directory_folder_name` = ?', [$_SESSION["site_set_for_editing"], $_POST[$table_name][$admin_field["column_name"]]]);
					}
					elseif($_SESSION['admin_type'] == 'edit')
					{
						$active_template = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `id` != ? AND `site_id` = ? AND `directory_folder_name` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"], $_POST[$table_name][$admin_field["column_name"]]]);
					}
					
					if(!empty($active_template))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is being used on another template.';
					}
					elseif($_SESSION['admin_type'] == 'add' && file_exists($_SERVER['DOCUMENT_ROOT']."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$_POST[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is being used on another template here: '.$_SERVER['DOCUMENT_ROOT']."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$_POST[$table_name][$admin_field["column_name"]];
					}
					elseif($_SESSION['admin_type'] == 'edit' && $_POST[$table_name][$admin_field["column_name"]] != $current_values[$table_name][$admin_field["column_name"]] && file_exists($_SERVER['DOCUMENT_ROOT']."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$_POST[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is being used on another template here: '.$_SERVER['DOCUMENT_ROOT']."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$_POST[$table_name][$admin_field["column_name"]];
					}
					elseif($_SESSION['admin_type'] == 'edit' && !file_exists($_SERVER['DOCUMENT_ROOT']."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$current_values[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Original '.$admin_field["name"].' cannot be found on the server to change it here: '.$_SERVER['DOCUMENT_ROOT']."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$current_values[$table_name][$admin_field["column_name"]];
					}
				}
			}
		}
		
		$class_directory_folder_name_aecn = new directory_folder_name_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_directory_folder_name_aecn->directory_folder_name_aecn($table_name, $admin_field, $post_values, $errors, $current_values);
	}
}