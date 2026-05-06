<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/add/includes/update-data-after.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/add/includes/update-data-after.php');
}
else
{
	if(!empty($_POST) && empty($errors) && !isset($_POST['change_site']) && isset($post_values))
	{
		//If admin_page has a child_table set and a column for sub_items, count the number of the child rows for the sub_items to save the count on parent and main record.
		if(isset($post_values[$_SESSION['admin_table_name']]['sub_items']))
		{
			if(!empty($_SESSION['admin_table_link_column']) && !empty($_SESSION['admin_parent_table_name']) && !empty(trim($_GET["rid"] ?? '')) && is_numeric(trim($_GET["rid"] ?? '')))
			{
				//Update main table sub_items total
				$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ?', [trim($_GET["rid"] ?? '')]);
				$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_parent_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), trim($_GET["rid"] ?? '')]);
				
				if(isset($_GET["sub-rid"]) && !empty(trim($_GET["sub-rid"] ?? '')) && is_numeric(trim($_GET["sub-rid"] ?? '')))
				{
					//Update item table sub_items total
					$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `parent_id` = ?', [trim($_GET["sub-rid"] ?? '')]);
					$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), trim($_GET["sub-rid"] ?? '')]);
				}
			}
		}
		elseif(!empty($_SESSION['admin_table_link_column']) && $_SESSION['admin_sub_page'] == 'Yes' && $_SESSION['admin_table_name'] != 'customer_addresses')
		{
			//Update main table sub_items total
			$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ?', [trim($_GET["rid"] ?? '')]);
			$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_parent_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), trim($_GET["rid"] ?? '')]);
		}
	}
}