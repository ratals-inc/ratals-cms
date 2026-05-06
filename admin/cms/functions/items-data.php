<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/items-data.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/items-data.php');
}
else
{
	//This gets the data for sub_items and products to load for things like prices, image, review score, URL, etc.
	if(!function_exists('getItemsData'))
	{
		function getItemsData($site_id, $domain, $home_page, $url_structure, $sites_end_urls_with, $url, $assigned_products_sub_items)
		{
			global $currency_zeros_after_separator, $currency_fractional_separator;
			
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
					
					if(isset($sql_sub_item_page_data_row['media']) && !empty($sql_sub_item_page_data_row['media']))
					{
						$get_media_array = array();											
						$get_media_array = mediaIdArray($sql_sub_item_page_data_row['media']);
						$sub_item_data['media_url'] = $get_media_array[0]['media_url'];
						$sub_item_data['media_tag'] = $get_media_array[0]['media_tag'];
						$sub_item_data['media_type'] = $get_media_array[0]['media_type'];
						$sub_item_data['media_width'] = $get_media_array[0]['width'];
						$sub_item_data['media_height'] = $get_media_array[0]['height'];
						$sub_item_data['media_video_poster'] = $get_media_array[0]['video_poster'];
					}
					
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
					}
					//Get price for type == 'inventory'
					elseif($sql_assigned_item_rows['type'] == 'inventory')
					{
						if(!empty($sql_assigned_item_rows['inventory_id']))
						{
							$sql_sub_item_inventory_data_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `id` = ? AND `status` = ?', [$sql_assigned_item_rows['inventory_id'], '1']);
						}
						
						if(!empty($sql_sub_item_inventory_data_row))
						{
							$sub_item_data['meta_title'] = $sql_sub_item_inventory_data_row['name'];
							$sub_item_data['meta_description'] = $sql_sub_item_inventory_data_row['description'];
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
								
								if(isset($sql_assigned_item_rows['media_id']) && !empty($sql_assigned_item_rows['media_id']))
								{
									$get_media_array = array();											
									$get_media_array = mediaIdArray($sql_assigned_item_rows['media_id'].'~||~'.$sql_assigned_item_rows['media_tag']);
									$sub_item_data['media_url'] = $get_media_array[0]['media_url'];
									$sub_item_data['media_tag'] = $get_media_array[0]['media_tag'];
									$sub_item_data['media_type'] = $get_media_array[0]['media_type'];
									$sub_item_data['media_width'] = $get_media_array[0]['width'];
									$sub_item_data['media_height'] = $get_media_array[0]['height'];
									$sub_item_data['media_video_poster'] = $get_media_array[0]['video_poster'];
								}
							}
							else
							{
								$sub_item_data['sale_price_active'] = 'No';
								$sub_item_data['price'] = $sql_assigned_item_rows['inventory_price'];
								$sub_item_data['sale_price'] = 0.00;
								$sub_item_data['save_amount'] = 0.00;
								
								if(isset($sql_assigned_item_rows['media_id']) && !empty($sql_assigned_item_rows['media_id']))
								{
									$get_media_array = array();											
									$get_media_array = mediaIdArray($sql_assigned_item_rows['media_id'].'~||~'.$sql_assigned_item_rows['media_tag']);
									$sub_item_data['media_url'] = $get_media_array[0]['media_url'];
									$sub_item_data['media_tag'] = $get_media_array[0]['media_tag'];
									$sub_item_data['media_type'] = $get_media_array[0]['media_type'];
									$sub_item_data['media_width'] = $get_media_array[0]['width'];
									$sub_item_data['media_height'] = $get_media_array[0]['height'];
									$sub_item_data['media_video_poster'] = $get_media_array[0]['video_poster'];
								}
							}
						}
						else
						{
							$sub_item_data['sale_price_active'] = 'No';
							$sub_item_data['price'] = $sql_assigned_item_rows['inventory_price'];
							$sub_item_data['sale_price'] = $sql_assigned_item_rows['inventory_sale_price'];
							$sub_item_data['save_amount'] = 0.00;
							
							if(isset($sql_assigned_item_rows['media_id']) && !empty($sql_assigned_item_rows['media_id']))
							{
								$get_media_array = array();											
								$get_media_array = mediaIdArray($sql_assigned_item_rows['media_id'].'~||~'.$sql_assigned_item_rows['media_tag']);
								$sub_item_data['media_url'] = $get_media_array[0]['media_url'];
								$sub_item_data['media_tag'] = $get_media_array[0]['media_tag'];
								$sub_item_data['media_type'] = $get_media_array[0]['media_type'];
								$sub_item_data['media_width'] = $get_media_array[0]['width'];
								$sub_item_data['media_height'] = $get_media_array[0]['height'];
								$sub_item_data['media_video_poster'] = $get_media_array[0]['video_poster'];
							}
						}
					}
				}
				$page_data_array[] = $sql_assigned_item_rows + $sub_item_data + array('pages_data' => $sql_sub_item_page_data_row);
			}
			return $page_data_array;
		}
	}
}