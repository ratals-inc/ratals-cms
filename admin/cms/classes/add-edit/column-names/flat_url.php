<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/flat_url.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/flat_url.php');
}
else
{
	if(!class_exists('flat_url_aecn'))
	{
		class flat_url_aecn
		{
			public function flat_url_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				//Check if Flat URL is already being used.
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					$flat_url_exist = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `flat_url` = ? AND `id` != ?', [$_SESSION["site_set_for_editing"], $post_values[$table_name][$admin_field["column_name"]], trim($_GET["rid"] ?? '')]);
					
					if(!empty($flat_url_exist))
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Selected URL "'.$post_values[$table_name][$admin_field["column_name"]].'" is already being used on another document.';
					}
				}
			}
		}
		
		$class_flat_url_aecn = new flat_url_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_flat_url_aecn->flat_url_aecn($table_name, $admin_field, $post_values, $errors);
	}
}