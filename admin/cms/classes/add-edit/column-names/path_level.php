<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/path_level.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/path_level.php');
}
else
{
	if(!class_exists('path_level_aecn'))
	{
		class path_level_aecn
		{
			public function path_level_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if(isset($_POST[$table_name][$admin_field["column_name"]]))
				{
					if(!empty($_POST[$table_name][$admin_field["column_name"]]))
					{
						$post_values[$table_name][$admin_field["column_name"]] = '';
						
						foreach($_POST[$table_name][$admin_field["column_name"]] as $path_level_builder)
						{
							if($path_level_builder != '')
							{
								$post_values[$table_name][$admin_field["column_name"]] .= $path_level_builder."/";
							}
							elseif($_POST[$table_name][$admin_field["column_name"]][0] == '')
							{
								$post_values[$table_name][$admin_field["column_name"]] = 0;
							}
						}
					}
					else
					{
						$post_values[$table_name][$admin_field["column_name"]] = 0;
					}
				}
			}
		}
		
		$class_path_level_aecn = new path_level_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_path_level_aecn->path_level_aecn($table_name, $admin_field, $post_values, $errors);
	}
}