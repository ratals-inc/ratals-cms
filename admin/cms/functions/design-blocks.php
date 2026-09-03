<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/design-blocks.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/design-blocks.php');
}
else
{
	if(!function_exists('getSubItems'))
	{
		function getSubItems($pages_data, $site_id, $row_id, $domain, $home_page, $url_structure, $sites_end_urls_with, $url)
		{
			//Start getting sub items assigned.
			$design_blocks = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'design_blocks', 'WHERE `site_id` = ? AND `urls_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$site_id, $row_id, 1]);
			
			$groups = array();
			
			if(!empty($design_blocks))
			{
				foreach($design_blocks as $design_block)
				{
					//Run code through fuction for urls, media, and nonce.
					if(!empty($design_block['design_blocks_code']))
					{
						$design_blocks_code_updater = getNonce($design_block['design_blocks_code'] ?? '');
						$design_blocks_code_updater = urlId($design_blocks_code_updater);
						$design_block['design_blocks_code'] = mediaId($design_blocks_code_updater, '', '', '', '');
					}
					
					//Run content through fuction for urls, media, and nonce.
					if(!empty($design_block['content']))
					{
						$content_updater = getNonce($design_block['content'] ?? '');
						$content_updater = urlId($content_updater);
						$design_block['content'] = mediaId($content_updater, '', '', '', '');
					}
					
					$design_block_array = $design_block;
					
					$assigned_sub_items_array = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, 
					"*", 
					'assignments_design_blocks', 
					"WHERE 
					`site_id` = ? 
					AND `design_blocks_id` = ? 
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
					[$site_id, $design_block["id"], $pages_data['id'], 1]);
					
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
						$page_data_array = getItemsData($site_id, $domain, $home_page, $url_structure, $sites_end_urls_with, $url, $design_block, $assigned_sub_items);
					}
					
					$page_data_arrays = array('group_rows' => $page_data_array);
					$groups[] = array_merge($design_block_array, $page_data_arrays);
				}
			}
			return $groups;
		}
	}
}