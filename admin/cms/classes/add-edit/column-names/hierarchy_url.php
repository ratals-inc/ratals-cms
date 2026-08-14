<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/hierarchy_url.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/hierarchy_url.php');
}
else
{
	if(!class_exists('hierarchy_url_aecn'))
	{
		class hierarchy_url_aecn
		{
			public function hierarchy_url_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				//Build Hierarchy URL structure.
				if(isset($_POST[$table_name][$admin_field["column_name"]]))
				{
					$post_values[$table_name][$admin_field["column_name"]] = '';
					
					if(isset($post_values['urls']['path_level']) && !empty($post_values['urls']['path_level']))
					{
						foreach($_POST['urls']['path_level'] as $hierarchy_url_builder)
						{
							if($hierarchy_url_builder != '')
							{
								$hierarchy_url_builder = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ?', [$hierarchy_url_builder]);
								
								if(strpos($hierarchy_url_builder['hierarchy_url'], '/') !== false)
								{
									$hierarchy_url_builder_array = explode('/', $hierarchy_url_builder['hierarchy_url']);
									
									$post_values[$table_name][$admin_field["column_name"]] .= end($hierarchy_url_builder_array).'/';
								}
								else
								{
									$post_values[$table_name][$admin_field["column_name"]] .= $hierarchy_url_builder['hierarchy_url'].'/';
								}
							}
						}
					}
					
					$post_values[$table_name][$admin_field["column_name"]] .= $_POST[$table_name][$admin_field["column_name"]];
				}
				
				//Check if Hierarchy URL empty.
				if(!isset($_POST[$table_name][$admin_field["column_name"]]) || empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' cannot be empty.';
				}
				//Check if Hierarchy URL is already being used.
				elseif(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					$hierarchy_url_exist = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `hierarchy_url` = ? AND `id` != ?', [$_SESSION["site_set_for_editing"], $post_values[$table_name][$admin_field["column_name"]], trim($_GET["rid"] ?? '')]);
					
					if(!empty($hierarchy_url_exist))
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Selected URL "'.$post_values[$table_name][$admin_field["column_name"]].'" is already being used on another document.';
					}
				}
			}
		}
		
		$class_hierarchy_url_aecn = new hierarchy_url_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_hierarchy_url_aecn->hierarchy_url_aecn($table_name, $admin_field, $post_values, $errors);
	}
}