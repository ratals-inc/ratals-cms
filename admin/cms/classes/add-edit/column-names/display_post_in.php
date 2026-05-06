<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/display_post_in.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/display_post_in.php');
}
else
{
	if(!class_exists('display_post_in_aecn'))
	{
		class display_post_in_aecn
		{
			public function display_post_in_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					$post_values[$table_name][$admin_field["column_name"]] = ','.implode(',', $_POST[$table_name][$admin_field["column_name"]]).',';
				}
			}
		}
		
		$class_display_post_in_aecn = new display_post_in_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_display_post_in_aecn->display_post_in_aecn($table_name, $admin_field, $post_values, $errors);
	}
}