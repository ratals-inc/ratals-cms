<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/change-active.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/change-active.php');
}
else
{
	//Enable/Disable the majority of items.
	if(!function_exists('changeActive'))
	{
		function changeActive($row_id, $current_status, $database_table_name)
		{
			if($_SESSION['record_has_url'] == 'Yes' || $database_table_name == 'urls')
			{
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, 'urls', '`url_status` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$current_status, $row_id, $_SESSION["site_set_for_editing"]]);
				
				//Set the correct database table name when $database_table_name == 'urls'
				$database_url_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ?', [$row_id]);
				if(isset($database_url_row['table_name']) && !empty($database_url_row['table_name']))
				{
					$database_table_name = $database_url_row['table_name'];
				}
			}
			else
			{
				$global_record_or_not = '';
				if($_SESSION['admin_site_id_global'] == 'No' || $database_table_name == 'custom_fields' || $database_table_name == 'assignments_sub_items') 
				{
					$global_record_or_not = " AND (`site_id` = '".$_SESSION["site_set_for_editing"]."' || `site_id` = '0')";
				}
				
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, $database_table_name, '`status` = ?', 'WHERE `id` = ?'.$global_record_or_not, [$current_status, $row_id]);
			}
			
			if($database_table_name == "templates")
			{
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, 'templates', '`status` = ?', 'WHERE `site_id` = ? AND `id` != ?', ['2', $_SESSION["site_set_for_editing"], $row_id]);
			}
			
			if($database_table_name == 'products')
			{
				enableDisableProductOrInventory($row_id, $current_status, 'product');
			}
			
			if($database_table_name == 'inventory' || $database_table_name == 'assigned-inventory')
			{
				enableDisableProductOrInventory($row_id, $current_status, 'inventory');
			}
			
			if($database_table_name == 'comments')
			{
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, $database_table_name, '`approved_date` = UTC_TIMESTAMP(), `approved_by` = ?', 'WHERE `id` = ?', [$_SESSION['user_first_last_name'], $row_id]);
			}
			
			if($database_table_name == 'q_and_a')
			{
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, $database_table_name, '`answered_by` = ?, `answered_date` = UTC_TIMESTAMP()', 'WHERE `id` = ?', [$_SESSION['user_first_last_name'], $row_id]);
			}
			
			//When an admin user updates a review, calculate the total review score and save on product.
			if($database_table_name == 'reviews')
			{
				$sql_get_review_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'reviews', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$sql_get_pending_reviews_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, 'avg(score) as review_score', 'reviews', 'WHERE `site_id` = ? AND `product_url_id` = ? AND `status` = ?', [$_SESSION["site_set_for_editing"], $sql_get_review_row['product_url_id'], '1']);
				
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, 'products', '`review_score` = ?, `updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', 'WHERE `urls_id` = ? AND `site_id` = ?', [$sql_get_pending_reviews_rows['review_score'], $_SESSION['user_first_last_name'], $sql_get_review_row['product_url_id'], $_SESSION["site_set_for_editing"]]);
				
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, 'assignments_products', '`product_review_score` = ?', 'WHERE `child_id` = ? AND `site_id` = ?', [$sql_get_pending_reviews_rows['review_score'], $sql_get_review_row['product_url_id'], $_SESSION["site_set_for_editing"]]);
				
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, 'assignments_sub_items', '`product_review_score` = ?', 'WHERE `child_id` = ? AND `site_id` = ?', [$sql_get_pending_reviews_rows['review_score'], $sql_get_review_row['product_url_id'], $_SESSION["site_set_for_editing"]]);
			}
			
			echo "1";
		}
	}
}