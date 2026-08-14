<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/displaying-in.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/displaying-in.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'displaying_in')
	{
		$sql_record_data_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `urls_id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
		if(empty($sql_record_data_rows)) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
		
		$sql_record_data_rows['urls_record_data'] = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ?', [$sql_record_data_rows['urls_id']]);
		
		$displaying_in_record_id = $sql_record_data_rows['urls_record_data']['id'];
		$contextual_link_search = '%urlId('.$displaying_in_record_id.');%';
		$assignments_rows = array();
		$sql_get_assignments_products = array();
		
		if($commerce_installed)
		{
			$sql_get_assignments_products = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'assignments_products', 'WHERE `child_id` = ? AND `child_id_table_name` = ? AND `site_id` = ?', [$displaying_in_record_id, $_SESSION['admin_table_name'], $_SESSION["site_set_for_editing"]]);
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
		
		$sql_get_assignments_sub_items = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'assignments_sub_items', 'WHERE `child_id` = ? AND `child_id_table_name` = ? AND `site_id` = ?', [$displaying_in_record_id, $_SESSION['admin_table_name'], $_SESSION["site_set_for_editing"]]);
		$assignments_sub_items_rows = array();
		if(!empty($sql_get_assignments_sub_items))
		{
			foreach($sql_get_assignments_sub_items as $sql_get_assignments_sub_items_rows)
			{
				$sql_get_assignments_sub_items_rows['assignment_table_name'] = 'sub_items';
				$assignments_sub_items_rows[] = $sql_get_assignments_sub_items_rows;
			}
		}
		
		$sql_get_contextual_link_rows = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `url_status` = ? AND (`top_content` LIKE ? OR `bottom_content` LIKE ?)', [1, $contextual_link_search, $contextual_link_search]);
		$contextual_link_rows = array();
		if(!empty($sql_get_contextual_link_rows))
		{
			foreach($sql_get_contextual_link_rows as $sql_get_contextual_link_row)
			{
				$contextual_link_row['status'] = 'N/A';
				$contextual_link_row['parent_id'] = $sql_get_contextual_link_row['id'];
				$contextual_link_row['assignment_table_name'] = 'Contextual Link';
				$contextual_link_row['type'] = 'Contextual Link';
				
				$contextual_link_rows[] = $contextual_link_row;
			}
		}
		
		$assignments_rows = array_merge($contextual_link_rows, $assignments_products_rows, $assignments_sub_items_rows);
	}
}