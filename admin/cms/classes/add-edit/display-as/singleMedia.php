<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/display-as/singleMedia.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/display-as/singleMedia.php');
}
else
{
	if(!class_exists('singleMediaAeda'))
	{
		class singleMediaAeda
		{
			public function singleMediaAeda($table_name, $admin_field, &$post_values, &$errors)
			{
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					$media_string = '';
					foreach($_POST[$table_name][$admin_field["column_name"]] as $media_array)
					{
						$media_string .= $media_array[0].'~||~'.$media_array[1].'*||*';
					}
					$post_values[$table_name][$admin_field["column_name"]] = trim($media_string, '*||*');
				}
			}
		}
		
		$class_singleMediaAeda = new singleMediaAeda();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_singleMediaAeda->singleMediaAeda($table_name, $admin_field, $post_values, $errors);
	}
}