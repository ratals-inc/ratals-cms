<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/data_length.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/data_length.php');
}
else
{
	if(!class_exists('data_length_aecn'))
	{
		class data_length_aecn
		{
			public function data_length_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if(($_SESSION['admin_table_name'] == 'admin_fields' || $_SESSION['admin_table_name'] == 'custom_fields') && isset($_POST[$table_name]['data_length']) && empty($_POST[$table_name]['data_length']))
				{
					$_POST[$table_name]['data_length'] = 0;
					$post_values[$table_name]['data_length'] = 0;
				}
			}
		}
		
		$class_data_length_aecn = new data_length_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_data_length_aecn->data_length_aecn($table_name, $admin_field, $post_values, $errors);
	}
}