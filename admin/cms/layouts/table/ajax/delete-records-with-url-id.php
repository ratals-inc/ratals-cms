<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-records-with-url-id.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-records-with-url-id.php');
}
else
{
	//Delete records that have a urls_id column. You must set the `js_name` column in `admin_pages` table to recordWithUrlId for these records to delete correctly.
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && isset($_POST['tableName']) && !empty($_POST['tableName']) && $_POST['type'] == 'delete-records-with-url-id')
	{
		foreach($_POST['deleteRow'] as $row_id)
		{
			if(!empty($row_id))
			{
				if($_POST['tableName'] == 'urls')
				{
					$sql_correct_table_name = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ?  AND `id` = ? LIMIT 1', [$_SESSION["site_set_for_editing"], $row_id]);
					
					if(isset($sql_correct_table_name['table_name']) && !empty($sql_correct_table_name['table_name']))
					{
						$correct_table_name = $sql_correct_table_name['table_name'];
					}
					else
					{
						//exit delete if table_name cannot be found when deleting a direct url.
						echo "1";
						exit;
					}
				}
				else
				{
					$correct_table_name = $_POST['tableName'];
				}
				
				$sql_parent_table_record_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $correct_table_name, 'WHERE `urls_id` = ? LIMIT 1', [$row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'urls', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, $correct_table_name, 'WHERE `site_id` = ? AND `urls_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_sub_items', 'WHERE `site_id` = ? AND `parent_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_sub_items', 'WHERE `site_id` = ? AND `child_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_posts', 'WHERE `site_id` = ? AND `parent_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_posts', 'WHERE `site_id` = ? AND `child_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'page_groups', 'WHERE `site_id` = ? AND `urls_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'menu_items', 'WHERE `site_id` = ? AND `links_to` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'slider_items', 'WHERE `site_id` = ? AND `links_to` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'comments', 'WHERE `site_id` = ? AND `post_url_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				if($commerce_installed)
				{
					$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_products', 'WHERE `site_id` = ? AND `parent_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
					
					$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_products', 'WHERE `site_id` = ? AND `child_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
					
					$results->getDeleteRecord(__LINE__, __FILE__, 'reviews', 'WHERE `site_id` = ? AND `product_url_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
					
					$results->getDeleteRecord(__LINE__, __FILE__, 'q_and_a', 'WHERE `site_id` = ? AND `product_url_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				}
				
				if(!empty($_SESSION['admin_child_table_name']))
				{
					//Fetch one row so we can see if $_SESSION['admin_table_name'].'_id'] isset on child table. If it is set, then remove child table records.
					$sql_child_table_results = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_child_table_name'], 'LIMIT 1', []);
					
					if(isset($sql_child_table_results[$_SESSION['admin_table_name'].'_id']))
					{
						$results->getDeleteRecord(__LINE__, __FILE__, $_SESSION['admin_child_table_name'], 'WHERE `'.$_SESSION['admin_table_name'].'_id` = ? AND (`site_id` = ? OR `site_id` = 0)', [$sql_parent_table_record_data['id'], $_SESSION["site_set_for_editing"]]);
					}
				}
				
				if($commerce_installed)
				{
					if($correct_table_name == 'products')
					{
						$sql_cart_product_ids = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'cart_items', 'WHERE `site_id` = ?  AND `product_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
						if(!empty($sql_cart_product_ids))
						{
							foreach($sql_cart_product_ids as $sql_cart_product_id)
							{
								$results->getDeleteRecord(__LINE__, __FILE__, 'cart_items', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $sql_cart_product_id['id']]);
								$results->getDeleteRecord(__LINE__, __FILE__, 'cart_items_custom_fields', 'WHERE `site_id` = ? AND `cart_items_id` = ?', [$_SESSION["site_set_for_editing"], $sql_cart_product_id['id']]);
							}
						}
					}
					
					//Update any sub_products that have this product attached to it.
					$sql_select_assignments = $results->getSelectUnionMultipleRecords(__LINE__, __FILE__, '`id`, `type`, `child_id`, `sub_products_ids`, `inventory_id`', 'assignments_products', 'assignments_sub_items', 'WHERE `type` = ? AND `sub_products_ids` LIKE ?', ['sub_products', '%|'.$row_id.':%']);
				
					if(!empty($sql_select_assignments))
					{
						foreach($sql_select_assignments as $sql_select_assignments_row)
						{
							$sub_items_data_array = array();
							$sub_items_data_array = getSubProductIds($sql_select_assignments_row['child_id']);
							
							$sub_products_ids = '';
							if(!empty($sub_items_data_array['sub_products_ids']))
							{
								$sub_products_ids = $sub_items_data_array['sub_products_ids'];
							}
							
							$sub_products_ids_products_table = '';
							if(!empty($sub_items_data_array['sub_products_ids_products_table']))
							{
								$sub_products_ids_products_table = $sub_items_data_array['sub_products_ids_products_table'];
							}
							
							$sub_products_price = 0;
							if(!empty($sub_items_data_array['product_price']))
							{
								$sub_products_price = $sub_items_data_array['product_price'];
							}
							
							$sub_products_sale_price = 0;
							if(!empty($sub_items_data_array['product_sale_price']))
							{
								$sub_products_sale_price = $sub_items_data_array['product_sale_price'];
							}
							
							$sub_products_sale_price_from = NULL;
							if($sub_items_data_array['product_sale_price_from'] != NULL)
							{
								$sub_products_sale_price_from = $sub_items_data_array['product_sale_price_from'];
							}
							
							$sub_products_sale_price_to = NULL;
							if($sub_items_data_array['product_sale_price_to'] != NULL)
							{
								$sub_products_sale_price_to = $sub_items_data_array['product_sale_price_to'];
							}
							
							$sql_select_pages_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'products', 'WHERE `urls_id` = ?', [$sql_select_assignments_row['child_id']]);
							
							if(!empty($sql_select_pages_row))
							{
								$first_active_media_id = NULL;
								if(strpos($sql_select_pages_row['media'], '*||*') !== false)
								{
									$first_media_ids = explode('*||*', $sql_select_pages_row['media']);
									$first_media_id = explode('~||~', $first_media_ids[0]);
									$first_active_media_id = $first_media_id[0];
								}
								elseif(!empty($sql_select_pages_row['media']))
								{
									$first_media_id = explode('~||~', $sql_select_pages_row['media']);
									$first_active_media_id = $first_media_id[0];
								}
								
								$review_score = NULL;
								if(!empty($sql_select_pages_row['review_score']))
								{
									$review_score = $sql_select_pages_row['review_score'];
								}
							}
							else
							{
								$first_active_media_id = NULL;
								$review_score = NULL;
								
							}
							
							$results->getUpdateRecord(__LINE__, __FILE__, 'products', '`products_assigned` = ?', 'WHERE `urls_id` = ?', [$sub_products_ids_products_table, $sql_select_assignments_row['child_id']]);
							
							$column_list = "`inventory_status` = NULL, `inventory_assigned_to_product_status` = NULL, `sub_products_ids` = ?, `product_price` = ?, `product_sale_price` = ?, `product_sale_price_from` = ?, `product_sale_price_to` = ?, `product_review_score` = ?, `inventory_id` = NULL, `inventory_price` = NULL, `inventory_sale_price` = NULL, `inventory_sale_price_from` = NULL, `inventory_sale_price_to` = NULL, `media_id` = ?, `inventory_track_quantity` = '', `inventory_quantity_available` = NULL, `inventory_allow_backorders` = '', `inventory_ships_within` = NULL, `updated_date` = NOW()";
							
							$where_clause = 'WHERE `child_id` = ? AND `type` = ?';
							
							$column_values = array($sub_products_ids, $sub_products_price, $sub_products_sale_price, $sub_products_sale_price_from, $sub_products_sale_price_to, $review_score, $first_active_media_id, $sql_select_assignments_row['child_id'], 'sub_products');
							
							$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_products', $column_list, $where_clause, $column_values);
							$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_sub_items', $column_list, $where_clause, $column_values);
						}
					}
				}
			}
		}
		
		echo "1";
		exit;
	}
}