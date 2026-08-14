<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/filename.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/filename.php');
}
else
{
	if(!class_exists('filename_aecn'))
	{
		class filename_aecn
		{
			public function filename_aecn($table_name, $admin_field, &$post_values, &$errors, $current_values)
			{
				//If template filename is changed or added, make sure the new template filename doesn't already exist.
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					$template_file_type = array();
					if($_SESSION['admin_type'] == 'add')
					{
						$active_template = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `id` = ? AND site_id = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
						
						$template_file_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'template_files', 'WHERE `templates_id` = ? AND `site_id` = ? AND `filename` = ?', [$active_template['id'], $_SESSION["site_set_for_editing"], $_POST[$table_name][$admin_field["column_name"]]]);
					}
					elseif($_SESSION['admin_type'] == 'edit')
					{
						$active_template = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `id` = ? AND site_id = ?', [trim($_GET['sub-page-rid'] ?? ''), $_SESSION["site_set_for_editing"]]);
						
						$template_file_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'template_files', 'WHERE `id` != ? AND `templates_id` = ? AND `site_id` = ? AND `filename` = ?', [trim($_GET["rid"] ?? ''), $active_template['id'], $_SESSION["site_set_for_editing"], $_POST[$table_name][$admin_field["column_name"]]]);
					}
					
					if(!empty($template_file_type))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is being used on another template file.';
					}
					elseif($_SESSION['admin_type'] == 'add' && isset($active_template['directory_folder_name']) && !empty($active_template['directory_folder_name']) && file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$_POST[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is being used on another template file here: '.INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$_POST[$table_name][$admin_field["column_name"]];
					}
					elseif($_SESSION['admin_type'] == 'edit' && isset($active_template['directory_folder_name']) && !empty($active_template['directory_folder_name']) && $_POST[$table_name][$admin_field["column_name"]] != $current_values[$table_name][$admin_field["column_name"]] && file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$_POST[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is being used on another template file here: '.INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$_POST[$table_name][$admin_field["column_name"]];
					}
					elseif($_SESSION['admin_type'] == 'edit' && isset($active_template['directory_folder_name']) && !empty($active_template['directory_folder_name']) && !file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$current_values[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Original '.$admin_field["name"].' cannot be found on the server to change it here: '.INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$current_values[$table_name][$admin_field["column_name"]];
					}
				}
			}
		}
		
		$class_filename_aecn = new filename_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_filename_aecn->filename_aecn($table_name, $admin_field, $post_values, $errors, $current_values);
	}
}