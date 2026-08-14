<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/scheduled_date.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/scheduled_date.php');
}
else
{
	if(!class_exists('scheduled_date_aecn'))
	{
		class scheduled_date_aecn
		{
			public function scheduled_date_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				//If Status = Scheduled make sure Scheduled Date is not empty.
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && empty($_POST[$table_name][$admin_field["column_name"]])&& isset($_POST['urls']['status']) && $_POST['urls']['status'] == '4')
				{
					$errors[$table_name][$admin_field["column_name"]] = 'Please select a '.$admin_field["name"].' as Status is set to Scheduled.';
				}
			}
		}
		
		$class_scheduled_date_aecn = new scheduled_date_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_scheduled_date_aecn->scheduled_date_aecn($table_name, $admin_field, $post_values, $errors);
	}
}