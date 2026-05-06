<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/days_of_the_week_open.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/days_of_the_week_open.php');
}
else
{
	if(!class_exists('days_of_the_week_open_aecn'))
	{
		class days_of_the_week_open_aecn
		{
			public function days_of_the_week_open_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				$post_days_of_the_week_open = '';
				
				for($counter = 0; $counter <= 7; $counter++)
				{
					if(!empty($_POST['site_contact_info']['open_time_1'][$counter]) || !empty($_POST['site_contact_info']['close_time_1'][$counter]) || !empty($_POST['site_contact_info']['open_time_2'][$counter]) || !empty($_POST['site_contact_info']['close_time_2'][$counter]))
					{
						$day = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
						
						$post_days_of_the_week_open .= $day[$counter].'|'.$_POST['site_contact_info']['open_time_1'][$counter].'|'.$_POST['site_contact_info']['close_time_1'][$counter].'|'.$_POST['site_contact_info']['open_time_2'][$counter].'|'.$_POST['site_contact_info']['close_time_2'][$counter].',';
					}
				}
				
				$post_values[$table_name][$admin_field["column_name"]] = trim($post_days_of_the_week_open, ',');
			}
		}
		
		$class_days_of_the_week_open_aecn = new days_of_the_week_open_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_days_of_the_week_open_aecn->days_of_the_week_open_aecn($table_name, $admin_field, $post_values, $errors);
	}
}