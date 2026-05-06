<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/display-as/checkboxId.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/display-as/checkboxId.php');
}
else
{
	if(!class_exists('checkboxIdAeda'))
	{
		class checkboxIdAeda
		{
			public function checkboxIdAeda($table_name, $admin_field, &$post_values, &$errors)
			{
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]) && is_array($_POST[$table_name][$admin_field["column_name"]]))
				{
					$post_values[$table_name][$admin_field["column_name"]] = '';
					
					if(!empty($_POST[$table_name][$admin_field["column_name"]]))
					{
						foreach($_POST[$table_name][$admin_field["column_name"]] as $check_box_builder)
						{
							$post_values[$table_name][$admin_field["column_name"]] .= $check_box_builder.",";
						}
						
						$post_values[$table_name][$admin_field["column_name"]] = ','.$post_values[$table_name][$admin_field["column_name"]];
					}
				}
			}
		}
		
		$class_checkboxIdAeda = new checkboxIdAeda();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_checkboxIdAeda->checkboxIdAeda($table_name, $admin_field, $post_values, $errors);
	}
}