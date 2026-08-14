<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/items-data.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/items-data.php');
}
else
{
	$_SESSION['sub_products_done'] = 0;
	$_SESSION['lazy_load_item_counter'] = 0;
	$_SESSION['fetch_priority_high_counter'] = 0;
	
	if(!function_exists('getItemsDataMedia'))
	{
		function getItemsDataMedia($media_data, $page_group)
		{
			global $pages_data, $lazy_load_media_row;
			
			$media_array = array();
			
			if(strpos($media_data, '*||*') !== false)
			{
				$media_items = explode('*||*', $media_data);
				$media_array = explode('~||~', $media_items[0]);
			}
			elseif(strpos($media_data, '~||~') !== false)
			{
				$media_array = explode('~||~', $media_data);
			}
			elseif(is_numeric($media_data))
			{
				$media_array = array($media_data, '');
			}
			
			$media_array[2] = 'lazyLoadNo';
			$media_array[3] = 'fetchPriorityAuto';
			
			//Used for sub-items
			if(!empty($page_group))
			{
				if(isset($page_group['lazy_load_media']) && $page_group['lazy_load_media'] == 'Yes')
				{
					$media_array[2] = 'lazyLoadYes';
				}
				
				if(isset($page_group['fetch_priority']) && $page_group['fetch_priority'] == 'Yes')
				{
					$media_array[3] = 'fetchPriorityHigh';
				}
			}
			//Used for product categories
			//$lazy_load_media_row variable is set in /admin/cms/fronted/site-settings.php
			else
			{
				$_SESSION['sub_products_done']++;
				
				if($lazy_load_media_row == 1)
				{
					$_SESSION['lazy_load_item_counter'] = $lazy_load_media_row;
				}
				elseif($lazy_load_media_row > 1)
				{
					$_SESSION['lazy_load_item_counter'] = ($pages_data['grid_columns'] * ($lazy_load_media_row - 1)) + 1;
				}
				
				if($_SESSION['lazy_load_item_counter'] > 0)
				{
					if($_SESSION['sub_products_done'] >= $_SESSION['lazy_load_item_counter'])
					{
						$media_array[2] = 'lazyLoadYes';
					}
				}
				
				if(empty($lazy_load_media_row) || $lazy_load_media_row == 0 || $lazy_load_media_row == 1)
				{
					$_SESSION['fetch_priority_high_counter'] = ($pages_data['grid_columns'] * 1);
				}
				elseif($lazy_load_media_row == 2)
				{
					$_SESSION['fetch_priority_high_counter'] = ($pages_data['grid_columns'] * 2);
				}
				elseif($lazy_load_media_row > 2)
				{
					$_SESSION['fetch_priority_high_counter'] = ($pages_data['grid_columns'] * 3);
				}
				
				if($_SESSION['fetch_priority_high_counter'] > 0)
				{
					if($_SESSION['sub_products_done'] <= $_SESSION['fetch_priority_high_counter'])
					{
						$media_array[3] = 'fetchPriorityHigh';
					}
				}
			}
			
			return $media_array;
		}
	}
	
	//This gets the data for sub_items and products to load for things like prices, image, review score, URL, etc.
	if(!function_exists('getItemsData'))
	{
		function getItemsData($site_id, $domain, $home_page, $url_structure, $sites_end_urls_with, $url, $page_group, $assigned_products_sub_items)
		{
			global $currency_zeros_after_separator, $currency_fractional_separator;
			
			//Get all inventory in one query for speed.
			$all_inventory_ids = array();
			$all_inventory_placeholders = array();
			foreach($assigned_products_sub_items as $inventory_array_item)
			{
				if(!empty($inventory_array_item['inventory_id']) && !in_array($inventory_array_item['inventory_id'], $all_inventory_ids))
				{
					$all_inventory_ids[] = $inventory_array_item['inventory_id'];
					$all_inventory_placeholders[] = '?';
				}
			}
			$all_inventory_results = array();
			if(!empty($all_inventory_ids) && !empty($all_inventory_placeholders))
			{
				$all_inventory_ids[] = 1; //Add inventory status 1 as active.
				$all_inventory_placeholders = implode(',', $all_inventory_placeholders);
				
				$all_inventory_results = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `id` IN ('.$all_inventory_placeholders.') AND `status` = ?', $all_inventory_ids, 'id');
			}
			
			foreach($assigned_products_sub_items as $sql_assigned_item_rows)
			{
				$sub_item_data = array();
				
				$sql_sub_item_page_data_row = $_SESSION['results']->getSelectLeftJoinSingleRecord(__LINE__, __FILE__, '*', $sql_assigned_item_rows['child_id_table_name'], '`urls` ON `'.$sql_assigned_item_rows['child_id_table_name'].'`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`id` = ? AND `urls`.`url_status` = ?', [$site_id, $sql_assigned_item_rows['child_id'], '1']);
				
				if(!empty($sql_sub_item_page_data_row))
				{
					$sub_item_data['meta_title'] = $sql_sub_item_page_data_row['meta_title'];
					$sub_item_data['meta_description'] = $sql_sub_item_page_data_row['meta_description'];
					$sub_item_data['flat_url'] = $sql_sub_item_page_data_row['flat_url'];
					$sub_item_data['hierarchy_url'] = $sql_sub_item_page_data_row['hierarchy_url'];
					$sub_item_data['url_extension'] = $sql_sub_item_page_data_row['url_extension'];
					$sub_item_data['custom_link'] = $sql_sub_item_page_data_row['custom_link'];
					$sub_item_data['link_type'] = $sql_sub_item_page_data_row['link_type'];
					
					$sub_item_data['review_score'] = '';
					if(isset($sql_sub_item_page_data_row['review_score']))
					{
						$sub_item_data['review_score'] = $sql_sub_item_page_data_row['review_score'];
					}
					
					$sub_item_data['url'] = getUrl($sql_sub_item_page_data_row['custom_link'], $sql_sub_item_page_data_row['url_extension'], $sites_end_urls_with, $url_structure, $domain, $sql_sub_item_page_data_row['hierarchy_url'], $sql_sub_item_page_data_row['flat_url'], $sql_assigned_item_rows['inventory_url'], $sql_sub_item_page_data_row['urls_id'], $home_page);
					
					//Get price for type == 'products'
					if($sql_assigned_item_rows['type'] == 'products')
					{
						//Current DateTime - This is for sale price dates to start/end based on a main timezone in site settings.										
						$current_timestamp = date('Y-m-d');
						
						$calc_save_amount = 0;
						if($sql_assigned_item_rows['product_sale_price'] > 0)
						{
							if((empty($sql_assigned_item_rows['product_sale_price_from']) && empty($sql_assigned_item_rows['product_sale_price_to'])) || ($current_timestamp >= $sql_assigned_item_rows['product_sale_price_from'] && $current_timestamp <= $sql_assigned_item_rows['product_sale_price_to']) || ($current_timestamp >= $sql_assigned_item_rows['product_sale_price_from'] && empty($sql_assigned_item_rows['product_sale_price_to'])) || (empty($sql_assigned_item_rows['product_sale_price_from']) && $current_timestamp <= $sql_assigned_item_rows['product_sale_price_to']))
							{
								$sub_item_data['sale_price_active'] = 'Yes';
								$sub_item_data['price'] = $sql_assigned_item_rows['product_price'];
								$sub_item_data['sale_price'] = $sql_assigned_item_rows['product_sale_price'];
								$calc_save_amount = ($sql_assigned_item_rows['product_price'] - $sql_assigned_item_rows['product_sale_price']);
								$sub_item_data['save_amount'] = number_format($calc_save_amount ?? '0', $_SESSION['currency_zeros_after_separator'], '.', '');
							}
							else
							{
								$sub_item_data['sale_price_active'] = 'No';
								$sub_item_data['price'] = $sql_assigned_item_rows['product_price'];
								$sub_item_data['sale_price'] = 0.00;
								$sub_item_data['save_amount'] = 0.00;
							}
						}
						else
						{
							$sub_item_data['sale_price_active'] = 'No';
							$sub_item_data['price'] = $sql_assigned_item_rows['product_price'];
							$sub_item_data['sale_price'] = $sql_assigned_item_rows['product_sale_price'];
							$sub_item_data['save_amount'] = 0.00;
						}
						
						//If product page, set media from product page.
						$sub_item_data['media_data'] = '';
						if(isset($sql_sub_item_page_data_row['media']) && !empty($sql_sub_item_page_data_row['media']))
						{
							$media_item_data = getItemsDataMedia($sql_sub_item_page_data_row['media'], $page_group);
							$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
						}
					}
					//Get price for type == 'sub_products'
					elseif($sql_assigned_item_rows['type'] == 'sub_products')
					{
						//Current DateTime - This is for sale price dates to start/end based on a main timezone in site settings.
						$current_timestamp = date('Y-m-d');
						
						$calc_save_amount = 0;
						if($sql_assigned_item_rows['product_sale_price'] > 0)
						{
							if(!empty($sql_assigned_item_rows['product_sale_price']) && $sql_assigned_item_rows['product_sale_price'] < $sql_assigned_item_rows['product_price'])
							{
								$sub_item_data['sale_price_active'] = 'Yes';
								$sub_item_data['price'] = $sql_assigned_item_rows['product_price'];
								$sub_item_data['sale_price'] = $sql_assigned_item_rows['product_sale_price'];
								$calc_save_amount = ($sql_assigned_item_rows['product_price'] - $sql_assigned_item_rows['product_sale_price']);
								$sub_item_data['save_amount'] = number_format($calc_save_amount ?? '0', $_SESSION['currency_zeros_after_separator'], '.', '');
							}
							else
							{
								$sub_item_data['sale_price_active'] = 'No';
								$sub_item_data['price'] = $sql_assigned_item_rows['product_price'];
								$sub_item_data['sale_price'] = 0.00;
								$sub_item_data['save_amount'] = 0.00;
							}
						}
						else
						{
							$sub_item_data['sale_price_active'] = 'No';
							$sub_item_data['price'] = $sql_assigned_item_rows['product_price'];
							$sub_item_data['sale_price'] = $sql_assigned_item_rows['product_sale_price'];
							$sub_item_data['save_amount'] = 0.00;
						}
						
						//If product page, set media from product page.
						if(isset($sql_sub_item_page_data_row['media']) && !empty($sql_sub_item_page_data_row['media']))
						{
							$media_item_data = getItemsDataMedia($sql_sub_item_page_data_row['media'], $page_group);
							$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
						}
					}
					//Get price for type == 'inventory'
					elseif($sql_assigned_item_rows['type'] == 'inventory')
					{
						$inventory_item_data = $all_inventory_results[$sql_assigned_item_rows['inventory_id']] ?? '';
						
						if(!empty($inventory_item_data))
						{
							$sub_item_data['meta_title'] = $inventory_item_data['name'];
							$sub_item_data['meta_description'] = $inventory_item_data['description'];
						}
						
						//Current DateTime - This is for sale price dates to start/end based on a main timezone in site settings.
						$current_timestamp = date('Y-m-d');
						
						$calc_save_amount = 0;
						if($sql_assigned_item_rows['inventory_sale_price'] > 0)
						{
							if((empty($sql_assigned_item_rows['inventory_sale_price_from']) && empty($sql_assigned_item_rows['inventory_sale_price_to'])) || ($current_timestamp >= $sql_assigned_item_rows['inventory_sale_price_from'] && $current_timestamp <= $sql_assigned_item_rows['inventory_sale_price_to']) || ($current_timestamp >= $sql_assigned_item_rows['inventory_sale_price_from'] && empty($sql_assigned_item_rows['inventory_sale_price_to'])) || (empty($sql_assigned_item_rows['inventory_sale_price_from']) && $current_timestamp <= $sql_assigned_item_rows['inventory_sale_price_to']))
							{
								$sub_item_data['sale_price_active'] = 'Yes';
								$sub_item_data['price'] = $sql_assigned_item_rows['inventory_price'];
								$sub_item_data['sale_price'] = $sql_assigned_item_rows['inventory_sale_price'];
								$calc_save_amount = ($sql_assigned_item_rows['inventory_price'] - $sql_assigned_item_rows['inventory_sale_price']);
								$sub_item_data['save_amount'] = number_format($calc_save_amount ?? '0', $_SESSION['currency_zeros_after_separator'], '.', '');
								
								if(isset($inventory_item_data['media']) && !empty($inventory_item_data['media']))
								{
									$media_item_data = getItemsDataMedia($inventory_item_data['media'], $page_group);
									$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
								}
								//If inventory does not have media, get media set on the product page
								elseif(isset($sql_sub_item_page_data_row['media']) && !empty($sql_sub_item_page_data_row['media']))
								{
									$media_item_data = getItemsDataMedia($sql_sub_item_page_data_row['media'], $page_group);
									$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
								}
							}
							else
							{
								$sub_item_data['sale_price_active'] = 'No';
								$sub_item_data['price'] = $sql_assigned_item_rows['inventory_price'];
								$sub_item_data['sale_price'] = 0.00;
								$sub_item_data['save_amount'] = 0.00;
								
								if(isset($inventory_item_data['media']) && !empty($inventory_item_data['media']))
								{
									$media_item_data = getItemsDataMedia($inventory_item_data['media'], $page_group);
									$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
								}
								//If inventory does not have media, get media set on the product page
								elseif(isset($sql_sub_item_page_data_row['media']) && !empty($sql_sub_item_page_data_row['media']))
								{
									$media_item_data = getItemsDataMedia($sql_sub_item_page_data_row['media'], $page_group);
									$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
								}
							}
						}
						else
						{
							$sub_item_data['sale_price_active'] = 'No';
							$sub_item_data['price'] = $sql_assigned_item_rows['inventory_price'];
							$sub_item_data['sale_price'] = $sql_assigned_item_rows['inventory_sale_price'];
							$sub_item_data['save_amount'] = 0.00;
							
							if(isset($inventory_item_data['media']) && !empty($inventory_item_data['media']))
							{
								$media_item_data = getItemsDataMedia($inventory_item_data['media'], $page_group);
								$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
							}
							//If inventory does not have media, get media set on the product page
							elseif(isset($sql_sub_item_page_data_row['media']) && !empty($sql_sub_item_page_data_row['media']))
							{
								$media_item_data = getItemsDataMedia($sql_sub_item_page_data_row['media'], $page_group);
								$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
							}
						}
					}
					//If page is page, post, category, etc., set media from the page type.
					elseif(isset($sql_sub_item_page_data_row['media']) && !empty($sql_sub_item_page_data_row['media']))
					{
						$media_item_data = getItemsDataMedia($sql_sub_item_page_data_row['media'], $page_group);
						$sub_item_data['media_html_code'] = mediaId($media_item_data[0], $media_item_data[2], $media_item_data[3], $media_item_data[1]);
					}
				}
				
				$page_data_array[] = array_merge($sql_assigned_item_rows, $sub_item_data, array('pages_data' => $sql_sub_item_page_data_row));
			}
			
			return $page_data_array;
		}
	}
}