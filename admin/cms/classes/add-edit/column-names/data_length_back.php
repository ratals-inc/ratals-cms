<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/data_length_back.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/data_length_back.php');
}
else
{
	if(!class_exists('data_length_back_aecn'))
	{
		class data_length_back_aecn
		{
			public function data_length_back_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
	
				if(($_SESSION['admin_table_name'] == 'admin_fields' || $_SESSION['admin_table_name'] == 'custom_fields') && isset($_POST[$table_name]['data_length_back']) && empty($_POST[$table_name]['data_length_back']))
				{
					$_POST[$table_name]['data_length_back'] = 0;
					$post_values[$table_name]['data_length_back'] = 0;
				}
			}
		}
		
		$class_data_length_back_aecn = new data_length_back_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_data_length_back_aecn->data_length_back_aecn($table_name, $admin_field, $post_values, $errors);
	}
}