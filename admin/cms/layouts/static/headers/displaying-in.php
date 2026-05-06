<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/headers/displaying-in.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/headers/displaying-in.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'displaying_in')
	{
		$sql_record_data_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `urls_id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
		if(empty($sql_record_data_rows)) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
		
		$sql_record_data_rows['urls_record_data'] = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ?', [$sql_record_data_rows['urls_id']]);
		
		$assignments_rows = array();
		$sql_get_assignments_products = array();
		
		if($commerce_installed)
		{
			$sql_get_assignments_products = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'assignments_products', 'WHERE `child_id` = ? AND `child_id_table_name` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION['admin_table_name'], $_SESSION["site_set_for_editing"]]);
		}
		
		$assignments_products_rows = array();
		if(!empty($sql_get_assignments_products))
		{
			foreach($sql_get_assignments_products as $sql_get_assignments_products_rows)
			{
				$sql_get_assignments_products_rows['assignment_table_name'] = 'products';
				$assignments_products_rows[] = $sql_get_assignments_products_rows;
			}
		}
		
		$sql_get_assignments_sub_items = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'assignments_sub_items', 'WHERE `child_id` = ? AND `child_id_table_name` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION['admin_table_name'], $_SESSION["site_set_for_editing"]]);
		$assignments_sub_items_rows = array();
		if(!empty($sql_get_assignments_sub_items))
		{
			foreach($sql_get_assignments_sub_items as $sql_get_assignments_sub_items_rows)
			{
				$sql_get_assignments_sub_items_rows['assignment_table_name'] = 'sub_items';
				$assignments_sub_items_rows[] = $sql_get_assignments_sub_items_rows;
			}
		}
		
		//Chose to not add assignments_posts becuase you can see all places the post is displaying in on the admin post page.
		
		$assignments_rows = array_merge($assignments_products_rows, $assignments_sub_items_rows);
	}
}