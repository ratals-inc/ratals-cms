<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/sub-items.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/sub-items.php');
}
else
{
	if(!function_exists('getSubItems'))
	{
		function getSubItems($pages_data, $site_id, $row_id, $domain, $home_page, $url_structure, $sites_end_urls_with, $url)
		{
			//Start getting sub items assigned.
			$page_groups = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'page_groups', 'WHERE `site_id` = ? AND `urls_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$site_id, $row_id, 1]);
			
			$groups = array();
			
			if(!empty($page_groups))
			{
				foreach($page_groups as $page_group)
				{
					//Run code through fuction for urls, media, and nonce.
					if(!empty($page_group['sub_items_code']))
					{
						$sub_items_code_updater = getNonce($page_group['sub_items_code'] ?? '');
						$sub_items_code_updater = urlId($sub_items_code_updater);
						$page_group['sub_items_code'] = mediaId($sub_items_code_updater, '', '', '');
					}
					
					//Run content through fuction for urls, media, and nonce.
					if(!empty($page_group['content']))
					{
						$content_updater = getNonce($page_group['content'] ?? '');
						$content_updater = urlId($content_updater);
						$page_group['content'] = mediaId($content_updater, '', '', '');
					}
					
					$page_group_array = $page_group;
					
					$assigned_sub_items_array = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, 
					"*", 
					'assignments_sub_items', 
					"WHERE 
					`site_id` = ? 
					AND `pages_groups_id` = ? 
					AND `parent_id` = ? 
					AND `status` = ? 
					AND (`item_status` = '1' OR `item_status` IS NULL) 
					AND (`inventory_status` = '1' OR `inventory_status` IS NULL) 
					AND (`inventory_assigned_to_product_status` = '1' OR `inventory_assigned_to_product_status` IS NULL) 
					AND (
						 (`type` != 'inventory')
					  OR (`type` = 'inventory' AND `inventory_track_quantity` = 'Yes' AND `inventory_quantity_available` > 0)
					  OR (`type` = 'inventory' AND `inventory_track_quantity` = 'Yes' AND `inventory_allow_backorders` = 'Yes')
					  OR (`type` = 'inventory' AND `inventory_track_quantity` = 'No')
					  OR (`type` = 'inventory' AND `inventory_track_quantity` = '')
					)", 
					[$site_id, $page_group["id"], $pages_data['id'], 1]);
					
					$assigned_sub_items = array();
					
					//Increase/decrease prices based on currency exchange rate difference.
					if(!empty($assigned_sub_items_array))
					{
						foreach($assigned_sub_items_array as $assigned_sub_item)
						{
							if($assigned_sub_item['product_price'] > 0)
							{
								$assigned_sub_item['product_price'] = ($assigned_sub_item['product_price'] * $_SESSION['exchange_rate_difference']);
							}
							
							if($assigned_sub_item['product_sale_price'] > 0)
							{
								$assigned_sub_item['product_sale_price'] = ($assigned_sub_item['product_sale_price'] * $_SESSION['exchange_rate_difference']);
							}
							
							if($assigned_sub_item['inventory_price'] > 0)
							{
								$assigned_sub_item['inventory_price'] = ($assigned_sub_item['inventory_price'] * $_SESSION['exchange_rate_difference']);
							}
							
							if($assigned_sub_item['inventory_sale_price'] > 0)
							{
								$assigned_sub_item['inventory_sale_price'] = ($assigned_sub_item['inventory_sale_price'] * $_SESSION['exchange_rate_difference']);
							}
							
							$assigned_sub_items[] = $assigned_sub_item;
						}
					}
					
					$page_data_array = array();
					
					if(!empty($assigned_sub_items)) 
					{
						$page_data_array = getItemsData($site_id, $domain, $home_page, $url_structure, $sites_end_urls_with, $url, $assigned_sub_items);
					}
					
					$page_data_arrays = array('group_rows' => $page_data_array);
					$groups[] = array_merge($page_group_array, $page_data_arrays);
				}
			}
			return $groups;
		}
	}
}