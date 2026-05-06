<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/site-search.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/site-search.php');
}
else
{
	$search_results_number_of_paginated_pages = 0;
	$search_results_start_count = 0;
	$search_results_end_count = 0;
	
	$search_results = array();
	$search_term = '';
	
	if(isset($_GET['search']) && !empty($_GET['search']))
	{
		$search_term = trim($_GET['search'] ?? '', ' ');
		
		//Current DateTime - This is for sale price dates to start/end based on a main timezone in site settings.
		$current_time = strtotime(date('Y-m-d'));
		
		$tables_to_search = array();
		
		if(!empty($site_search_max_results) && is_numeric($site_search_max_results))
		{
			$max_search_results = $site_search_max_results;
		}
		else
		{
			$max_search_results = 300;
		}
		
		
		if(!empty($search_term)) 
		{
			//INSERT Site Search Keyowrds into DB
			$results->getInsertRecord(__LINE__, __FILE__, 'site_search', '`site_id`, `keyword`, `created_date`', '?,?,UTC_TIMESTAMP()', [$site_id, $search_term]);
			
			//Get admin field id for custom_fields to know if its on the table for searching.
			$admin_fields_custom_fields_id_array = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `column_name` = ?', ['custom_fields']);
			$admin_fields_custom_fields_id = $admin_fields_custom_fields_id_array['id'];
			
			//Get all database tables that have urls attached to them.
			$tables_with_urls_id = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `admin_fields_ids` LIKE ? ORDER BY `database_table_name` ASC', ['%,'.$admin_fields_urls_id.',%']);
			
			$tables_to_search_first = array('products', 'categories', 'posts', 'pages');
			//Put tables being search in order from array $tables_to_search_first, then search tables that have been added because of a custom admin page added in admin.
			if($tables_with_urls_id)
			{
				//Put core tables to search in array first.
				foreach($tables_to_search_first as $table_to_search_first)
				{
					foreach($tables_with_urls_id as $tables_with_urls)
					{
						if($tables_with_urls['database_table_name'] == $table_to_search_first && !in_array($table_to_search_first, $tables_to_search))
						{
							$tables_to_search[$tables_with_urls['database_table_name']] = $tables_with_urls;
							
							//Set if the table has a custom_fields column on it or not to search.
							if(!empty($admin_fields_custom_fields_id) && strpos($tables_with_urls['admin_fields_ids'], $admin_fields_custom_fields_id) !== false)
							{
								$tables_to_search[$tables_with_urls['database_table_name']]['table_has_custom_fields'] = 'Yes';
							}
							else
							{
								$tables_to_search[$tables_with_urls['database_table_name']]['table_has_custom_fields'] = 'No';
							}
						}
					}
				}
				
				//Put secondary tables to search in array last.
				foreach($tables_with_urls_id as $tables_with_urls)
				{
					if(!in_array($tables_with_urls['database_table_name'], $tables_to_search))
					{
						$tables_to_search[$tables_with_urls['database_table_name']] = $tables_with_urls;
						
						//Set if the table has a custom_fields column on it or not to search.
						if(!empty($admin_fields_custom_fields_id) && strpos($tables_with_urls['admin_fields_ids'], $admin_fields_custom_fields_id) !== false)
						{
							$tables_to_search[$tables_with_urls['database_table_name']]['table_has_custom_fields'] = 'Yes';
						}
						else
						{
							$tables_to_search[$tables_with_urls['database_table_name']]['table_has_custom_fields'] = 'No';
						}
					}
				}
			}
			
			if($commerce_installed)
			{
				//////////////////SEARCH ITEM NUMBERS - EXACT MATCH//////////////////
				if(count($search_results) < $max_search_results)
				{
					//Search product item numbers for exact search term 
					$product_item_number_exact_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND `products`.`item_number` = ? LIMIT 1', [$site_id, '1', 'Yes', $search_term], 'id');
					if(!empty($product_item_number_exact_match)) 
					{
						foreach($product_item_number_exact_match as $key => $value)
						{
							if(!array_key_exists($key, $search_results))
							{
								if(count($search_results) >= $max_search_results) { break; }
								
								$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
								
								if(isset($value['media']))
								{
									$value['media_data'] = mediaIdArray($value['media']);
								}
								
								$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
								$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
								
								if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
								{
									$value['price'] = $value['products_sale_price'];
									$value['save'] = ($value['products_price'] - $value['products_sale_price']);
								}
								elseif($value['products_price'] > 0)
								{
									$value['price'] = $value['products_price'];
									$value['save'] = '';
								}
								else
								{
									$value['price'] = '';
									$value['save'] = '';
								}
								
								//Get first active inventory assigned to product.
								$inventory_values = array();
								if(!empty($value['inventory_assigned']))
								{
									$inventory_assigned = explode(',', trim($value['inventory_assigned'] ?? '', ','));
									
									if(!empty($inventory_assigned))
									{
										foreach($inventory_assigned as $inventory_assigned_item)
										{
											$inventory_assigned_data = explode('|', $inventory_assigned_item ?? '');
											
											if($inventory_assigned_data[0] == 1)
											{
												$inventory_values = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `id` = ? LIMIT 1', ['1', $inventory_assigned_data[1]]);
												
												if(!empty($inventory_values))
												{
													break;
												}
											}
										}
									}
								}
								
								if(!empty($inventory_values))
								{
									$search_results[$key] = $value + array('inventory' => $inventory_values);
								}
								else
								{
									$search_results[$key] = $value + array('inventory' => '');
								}
								
							}
						}
					}
				}
				if(count($search_results) < $max_search_results)
				{
					//Search inventory item numbers for exact search term
					$inventory_item_number_exact_match = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `item_number` = ? LIMIT 1', ['1', $search_term], 'id');
					if(!empty($inventory_item_number_exact_match)) 
					{
						foreach($inventory_item_number_exact_match as $key => $inventory_values)
						{
							$inventory_product_item_number_exact_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND `products`.`inventory_assigned` LIKE ? LIMIT 1', [$site_id, '1', 'Yes', '%,1|'.$key.',%'], 'id');
							
							if(!empty($inventory_product_item_number_exact_match))
							{
								foreach($inventory_product_item_number_exact_match as $key => $value)
								{
									if(!array_key_exists($key, $search_results))
									{
										if(count($search_results) >= $max_search_results) { break; }
										
										$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
										
										if(isset($value['media']))
										{
											$media_data = mediaIdArray($value['media']);
											$value['media_data'] = $media_data;
										}
										
										$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
										$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
								
										if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
										{
											$value['price'] = $value['products_sale_price'];
											$value['save'] = ($value['products_price'] - $value['products_sale_price']);
										}
										elseif($value['products_price'] > 0)
										{
											$value['price'] = $value['products_price'];
											$value['save'] = '';
										}
										else
										{
											$value['price'] = '';
											$value['save'] = '';
										}
										
										$search_results[$key] = $value + array('inventory' => $inventory_values);
									}
								}
							}
						}
					}
				}
				
				//////////////////SEARCH ITEM NUMBERS - PHRASE MATCH//////////////////
				if(count($search_results) < $max_search_results)
				{
					//Search product item numbers for phrase search term
					$product_item_number_phrase_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND `products`.`item_number` LIKE ? LIMIT '.$max_search_results, [$site_id, '1', 'Yes', '%'.$search_term.'%'], 'id');
					if(!empty($product_item_number_phrase_match)) 
					{
						foreach($product_item_number_phrase_match as $key => $value)
						{
							if(!array_key_exists($key, $search_results))
							{
								if(count($search_results) >= $max_search_results) { break; }
								
								$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
								
								if(isset($value['media']))
								{
									$media_data = mediaIdArray($value['media']);
									$value['media_data'] = $media_data;
								}
								
								$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
								$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
								
								if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
								{
									$value['price'] = $value['products_sale_price'];
									$value['save'] = ($value['products_price'] - $value['products_sale_price']);
								}
								elseif($value['products_price'] > 0)
								{
									$value['price'] = $value['products_price'];
									$value['save'] = '';
								}
								else
								{
									$value['price'] = '';
									$value['save'] = '';
								}
								
								//Get first active inventory assigned to product.
								$inventory_values = '';
								if(!empty($value['inventory_assigned']))
								{
									$inventory_assigned = explode(',', trim($value['inventory_assigned'] ?? '', ','));
									
									if(!empty($inventory_assigned))
									{
										foreach($inventory_assigned as $inventory_assigned_item)
										{
											$inventory_assigned_data = explode('|', $inventory_assigned_item ?? '');
											
											if($inventory_assigned_data[0] == 1)
											{
												$inventory_values = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `id` = ? LIMIT 1', ['1', $inventory_assigned_data[1]]);
												
												if(!empty($inventory_values))
												{
													break;
												}
											}
										}
									}
								}
								
								if(!empty($inventory_values))
								{
									$search_results[$key] = $value + array('inventory' => $inventory_values);
								}
								else
								{
									$search_results[$key] = $value + array('inventory' => '');
								}
							}
						}
					}
				}
				
				if(count($search_results) < $max_search_results)
				{
					//Search inventory item numbers for phrase search term
					$inventory_item_number_phrase_match = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `item_number` LIKE ? LIMIT '.$max_search_results, ['1', '%'.$search_term.'%'], 'id');
					if(!empty($inventory_item_number_phrase_match)) 
					{
						foreach($inventory_item_number_phrase_match as $key => $inventory_values)
						{
							$inventory_product_item_number_exact_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND `products`.`inventory_assigned` LIKE ? LIMIT 1', [$site_id, '1', 'Yes', '%,1|'.$key.',%'], 'id');
							
							if(!empty($inventory_product_item_number_exact_match))
							{
								foreach($inventory_product_item_number_exact_match as $key => $value)
								{
									if(!array_key_exists($key, $search_results))
									{
										if(count($search_results) >= $max_search_results) { break; }
										
										$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
										
										if(isset($value['media']))
										{
											$media_data = mediaIdArray($value['media']);
											$value['media_data'] = $media_data;
										}
										
										$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
										$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
										
										if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
										{
											$value['price'] = $value['products_sale_price'];
											$value['save'] = ($value['products_price'] - $value['products_sale_price']);
										}
										elseif($value['products_price'] > 0)
										{
											$value['price'] = $value['products_price'];
											$value['save'] = '';
										}
										else
										{
											$value['price'] = '';
											$value['save'] = '';
										}
										
										$search_results[$key] = $value + array('inventory' => $inventory_values);
									}
								}
							}
						}
					}
				}
				
				//////////////////SEARCH ITEM NUMBERS - BROAD MATCH//////////////////
				if(count($search_results) < $max_search_results && strpos($search_term, ' ') !== false)
				{
					$broad_search_array = explode(' ', $search_term);
					
					$columns_to_search_products = '';
					$parameters_to_search_products = array($site_id, '1', 'Yes');
					
					$columns_to_search_inventory = '';
					$parameters_to_search_inventory = array('1');
					
					foreach($broad_search_array as $broad_search)
					{
						$columns_to_search_products .= ' AND `products`.`item_number` LIKE ?';
						$parameters_to_search_products[] = '%'.$broad_search.'%';
						
						$columns_to_search_inventory .= ' AND `item_number` LIKE ?';
						$parameters_to_search_inventory[] = '%'.$broad_search.'%';
					}
					
					//Search product item numbers for broad search term
					if(count($search_results) < $max_search_results)
					{
						$product_item_number_broad_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? '.$columns_to_search_products.' LIMIT '.$max_search_results, $parameters_to_search_products, 'id');
						if(!empty($product_item_number_broad_match)) 
						{
							foreach($product_item_number_broad_match as $key => $value)
							{
								if(!array_key_exists($key, $search_results))
								{
									if(count($search_results) >= $max_search_results) { break; }
									
									$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
									
									if(isset($value['media']))
									{
										$media_data = mediaIdArray($value['media']);
										$value['media_data'] = $media_data;
									}
									
									$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
									$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
									
									if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
									{
										$value['price'] = $value['products_sale_price'];
										$value['save'] = ($value['products_price'] - $value['products_sale_price']);
									}
									elseif($value['products_price'] > 0)
									{
										$value['price'] = $value['products_price'];
										$value['save'] = '';
									}
									else
									{
										$value['price'] = '';
										$value['save'] = '';
									}
									
									//Get first active inventory assigned to product.
									$inventory_values = '';
									if(!empty($value['inventory_assigned']))
									{
										$inventory_assigned = explode(',', trim($value['inventory_assigned'] ?? '', ','));
										
										if(!empty($inventory_assigned))
										{
											foreach($inventory_assigned as $inventory_assigned_item)
											{
												$inventory_assigned_data = explode('|', $inventory_assigned_item ?? '');
												
												if($inventory_assigned_data[0] == 1)
												{
													$inventory_values = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `id` = ? LIMIT 1', ['1', $inventory_assigned_data[1]]);
													
													if(!empty($inventory_values))
													{
														break;
													}
												}
											}
										}
									}
									
									if(!empty($inventory_values))
									{
										$search_results[$key] = $value + array('inventory' => $inventory_values);
									}
									else
									{
										$search_results[$key] = $value + array('inventory' => '');
									}
								}
							}
						}
					}
					//Search inventory item numbers for broad search term
					if(count($search_results) < $max_search_results)
					{
						$inventory_item_number_broad_match = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? '.$columns_to_search_inventory.' LIMIT '.$max_search_results, $parameters_to_search_inventory, 'id');
						if(!empty($inventory_item_number_broad_match)) 
						{
							foreach($inventory_item_number_broad_match as $key => $inventory_values)
							{
								$inventory_product_item_number_exact_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND `products`.`inventory_assigned` LIKE ? LIMIT 1', [$site_id, '1', 'Yes', '%,1|'.$key.',%'], 'id');
								
								if(!empty($inventory_product_item_number_exact_match))
								{
									foreach($inventory_product_item_number_exact_match as $key => $value)
									{
										if(!array_key_exists($key, $search_results))
										{
											if(count($search_results) >= $max_search_results) { break; }
											
											$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
											
											if(isset($value['media']))
											{
												$media_data = mediaIdArray($value['media']);
												$value['media_data'] = $media_data;
											}
											
											$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
											$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
											
											if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
											{
												$value['price'] = $value['products_sale_price'];
												$value['save'] = ($value['products_price'] - $value['products_sale_price']);
											}
											elseif($value['products_price'] > 0)
											{
												$value['price'] = $value['products_price'];
												$value['save'] = '';
											}
											else
											{
												$value['price'] = '';
												$value['save'] = '';
											}
											
											$search_results[$key] = $value + array('inventory' => $inventory_values);
										}
									}
								}
							}
						}
					}
				}
				
				//////////////////SEARCH INVENTORY - EXACT MATCH//////////////////
				if(count($search_results) < $max_search_results)
				{
					//Get lists from custom_fields_options in db with key as ID for inventory attributes.
					$search_custom_field_option_rows = $results->getSelectLeftJoinMultipleRecordsKeyNameTwo(__LINE__, __FILE__, '`custom_fields_options`.`id`, `custom_fields_options`.`option_data`->>"$.'.$_SESSION['site_language'].'.value" AS option_data, `custom_fields`.`column_name`', 'custom_fields_options', 'custom_fields ON `custom_fields_options`.`custom_fields_id` = `custom_fields`.`id`', 'WHERE `field_type` = ? AND `status` = ? AND `option_data`->>"$.'.$_SESSION['site_language'].'.value" = ?', ['Inventory Attribute', 1, strtolower($search_term)], 'id', 'column_name');
					
					$custom_field_column = '';
					$custom_field_values = array('1', $search_term, $search_term, $search_term, $search_term);
					
					if(isset($search_custom_field_option_rows) && !empty($search_custom_field_option_rows))
					{
						foreach($search_custom_field_option_rows as $key => $value)
						{
							$custom_field_column .= ' OR `custom_fields` = ?';
							$custom_field_values[] = '{"'.$value.'":"'.$key.'"}';
						}
					}
					
					//Search inventory data for exact search term
					$inventory_search_exact_match = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND (`name` = ? OR `description` = ? OR `price` = ? OR `sale_price` = ?'.$custom_field_column.') LIMIT '.$max_search_results, $custom_field_values, 'id');
					
					if(!empty($inventory_search_exact_match)) 
					{
						foreach($inventory_search_exact_match as $key => $inventory_values)
						{
							$inventory_product_item_number_exact_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND `products`.`inventory_assigned` LIKE ? LIMIT 1', [$site_id, '1', 'Yes', '%,1|'.$key.',%'], 'id');
							
							if(!empty($inventory_product_item_number_exact_match))
							{
								foreach($inventory_product_item_number_exact_match as $key => $value)
								{
									if(!array_key_exists($key, $search_results))
									{
										if(count($search_results) >= $max_search_results) { break; }
										
										$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
										
										if(isset($value['media']))
										{
											$media_data = mediaIdArray($value['media']);
											$value['media_data'] = $media_data;
										}
										
										$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
										$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
								
										if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
										{
											$value['price'] = $value['products_sale_price'];
											$value['save'] = ($value['products_price'] - $value['products_sale_price']);
										}
										elseif($value['products_price'] > 0)
										{
											$value['price'] = $value['products_price'];
											$value['save'] = '';
										}
										else
										{
											$value['price'] = '';
											$value['save'] = '';
										}
										
										$search_results[$key] = $value + array('inventory' => $inventory_values);
									}
								}
							}
						}
					}
				}
				
				//////////////////SEARCH INVENTORY - PHRASE MATCH//////////////////
				if(count($search_results) < $max_search_results)
				{
					//Get lists from custom_fields_options in db with key as ID for inventory attributes.
					$search_custom_field_option_rows = $results->getSelectLeftJoinMultipleRecordsKeyNameTwo(__LINE__, __FILE__, '`custom_fields_options`.`id`, `custom_fields_options`.`option_data`->>"$.'.$_SESSION['site_language'].'.value" AS option_data, `custom_fields`.`column_name`', 'custom_fields_options', 'custom_fields ON `custom_fields_options`.`custom_fields_id` = `custom_fields`.`id`', 'WHERE `field_type` = ? AND `status` = ? AND `option_data`->>"$.'.$_SESSION['site_language'].'.value" = ?', ['Inventory Attribute', 1, strtolower($search_term)], 'id', 'column_name');
					
					$custom_field_column = '';
					$custom_field_values = array('1', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%');
					
					if(isset($search_custom_field_option_rows) && !empty($search_custom_field_option_rows))
					{
						foreach($search_custom_field_option_rows as $key => $value)
						{
							$custom_field_column .= ' OR `custom_fields` LIKE ?';
							$custom_field_values[] = '%"'.$value.'":"'.$key.'"%';
						}
					}
					
					//Search inventory data for phrase search term
					$inventory_search_phrase_match = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND (`name` LIKE ? OR `description` LIKE ? OR `price` LIKE ? OR `sale_price` LIKE ?'.$custom_field_column.') LIMIT '.$max_search_results, $custom_field_values, 'id');
					
					if(!empty($inventory_search_phrase_match)) 
					{
						foreach($inventory_search_phrase_match as $key => $inventory_values)
						{
							$inventory_product_item_number_exact_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND `products`.`inventory_assigned` LIKE ? LIMIT 1', [$site_id, '1', 'Yes', '%,1|'.$key.',%'], 'id');
							
							if(!empty($inventory_product_item_number_exact_match))
							{
								foreach($inventory_product_item_number_exact_match as $key => $value)
								{
									if(!array_key_exists($key, $search_results))
									{
										if(count($search_results) >= $max_search_results) { break; }
										
										$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
										
										if(isset($value['media']))
										{
											$media_data = mediaIdArray($value['media']);
											$value['media_data'] = $media_data;
										}
										
										$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
										$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
										
										if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
										{
											$value['price'] = $value['products_sale_price'];
											$value['save'] = ($value['products_price'] - $value['products_sale_price']);
										}
										elseif($value['products_price'] > 0)
										{
											$value['price'] = $value['products_price'];
											$value['save'] = '';
										}
										else
										{
											$value['price'] = '';
											$value['save'] = '';
										}
										
										$search_results[$key] = $value + array('inventory' => $inventory_values);
									}
								}
							}
						}
					}
				}
				
				//////////////////SEARCH INVENTORY - BROAD MATCH//////////////////
				if(count($search_results) < $max_search_results)
				{
					$broad_search_array = explode(' ', $search_term);
					
					$custom_field_column = '';
					$custom_field_values = array();
					$custom_field_values[] = 'Inventory Attribute';
					$custom_field_values[] = '1';
					
					$columns_to_search_inventory = '';
					$parameters_to_search_inventory = array();
					$parameters_to_search_inventory[] = '1';
					
					foreach($broad_search_array as $broad_search)
					{
						$custom_field_column .= ' OR `option_data` LIKE ? OR `column_name` LIKE ?';
						$custom_field_values[] = '%'.$broad_search.'%';
						$custom_field_values[] = '%'.$broad_search.'%';
						
						$columns_to_search_inventory .= ' OR `name` LIKE ? OR `description` LIKE ? OR `price` LIKE ? OR `sale_price` LIKE ?';
						$parameters_to_search_inventory[] = '%'.$broad_search.'%';
						$parameters_to_search_inventory[] = '%'.$broad_search.'%';
						$parameters_to_search_inventory[] = '%'.$broad_search.'%';
						$parameters_to_search_inventory[] = '%'.$broad_search.'%';
					}
					
					$columns_to_search_inventory = trim($columns_to_search_inventory, ' OR ');
					$custom_field_column = trim($custom_field_column, ' OR ');
					
					//Get lists from custom_fields_options in db for inventory attributes. IDs are set on inventory for inventory attributes so we have to get the ids to look for colors, sizes, etc on inventory items.
					$search_custom_field_option_rows = $results->getSelectLeftJoinMultipleRecordsKeyNameTwo(__LINE__, __FILE__, '`custom_fields_options`.`id`, `custom_fields_options`.`option_data`->>"$.'.$_SESSION['site_language'].'.value" AS option_data, `custom_fields`.`column_name`', 'custom_fields_options', 'custom_fields ON `custom_fields_options`.`custom_fields_id` = `custom_fields`.`id`', 'WHERE `field_type` = ? AND `status` = ?  AND ('.$custom_field_column.')', $custom_field_values, 'id', 'column_name');
					
					if(isset($search_custom_field_option_rows) && !empty($search_custom_field_option_rows))
					{
						foreach($search_custom_field_option_rows as $key => $value)
						{
							$columns_to_search_inventory .= ' OR `custom_fields` LIKE ? OR `custom_fields` LIKE ?';
							$parameters_to_search_inventory[] = '%'.$key.'%';
							$parameters_to_search_inventory[] = '%'.$value.'%';
						}
					}
					
					$columns_to_search_inventory = trim($columns_to_search_inventory, ' OR ');
					
					//Search inventory data for broad search term
					$inventory_search_broad_match = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND ('.$columns_to_search_inventory.') LIMIT '.$max_search_results, $parameters_to_search_inventory, 'id');
					
					if(!empty($inventory_search_broad_match)) 
					{
						foreach($inventory_search_broad_match as $key => $inventory_values)
						{
							$inventory_product_item_number_exact_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'products', '`urls` ON `products`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND `products`.`inventory_assigned` LIKE ? LIMIT 1', [$site_id, '1', 'Yes', '%,1|'.$key.',%'], 'id');
							
							if(!empty($inventory_product_item_number_exact_match))
							{
								foreach($inventory_product_item_number_exact_match as $key => $value)
								{
									if(!array_key_exists($key, $search_results))
									{
										if(count($search_results) >= $max_search_results) { break; }
										
										$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
										
										if(isset($value['media']))
										{
											$media_data = mediaIdArray($value['media']);
											$value['media_data'] = $media_data;
										}
										
										$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
										$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
										
										if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
										{
											$value['price'] = $value['products_sale_price'];
											$value['save'] = ($value['products_price'] - $value['products_sale_price']);
										}
										elseif($value['products_price'] > 0)
										{
											$value['price'] = $value['products_price'];
											$value['save'] = '';
										}
										else
										{
											$value['price'] = '';
											$value['save'] = '';
										}
										
										$search_results[$key] = $value + array('inventory' => $inventory_values);
									}
								}
							}
						}
					}
				}
			}
			
			//////////////////SEARCH TABLES WITH URLS_ID//////////////////
			if(!empty($tables_to_search) && count($search_results) < $max_search_results)
			{
				foreach($tables_to_search as $table_to_search)
				{
					//Search table for exact search term
					if(count($search_results) < $max_search_results)
					{
						//Search column of custom_fields if it exist on the table.
						$columns_to_search_table = '';
						$parameters_to_search_table = array($site_id, '1', 'Yes', $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);
						if($table_to_search['table_has_custom_fields'] == 'Yes')
						{
							$columns_to_search_table = ' OR `'.$table_to_search['database_table_name'].'`.`custom_fields` = ?';
							$parameters_to_search_table = array($site_id, '1', 'Yes', $search_term, $search_term, $search_term, $search_term, $search_term, $search_term, $search_term);
						}
						
						$table_exact_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', $table_to_search['database_table_name'], '`urls` ON `'.$table_to_search['database_table_name'].'`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND (`urls`.`meta_title` = ? OR `urls`.`meta_description` = ? OR `urls`.`content_title` = ? OR `urls`.`table_of_contents` = ? OR `urls`.`top_content` = ? OR `urls`.`bottom_content` = ?'.$columns_to_search_table.') LIMIT 1', $parameters_to_search_table, 'id');
						if(!empty($table_exact_match)) 
						{
							foreach($table_exact_match as $key => $value)
							{
								if(!array_key_exists($key, $search_results))
								{
									if(count($search_results) >= $max_search_results) { break; }
									
									$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
									
									if(isset($value['media']))
									{
										$media_data = mediaIdArray($value['media']);
										$value['media_data'] = $media_data;
									}
									
									if($table_to_search['database_table_name'] == 'products')
									{
										$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
										$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
										
										if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
										{
											$value['price'] = $value['products_sale_price'];
											$value['save'] = ($value['products_price'] - $value['products_sale_price']);
										}
										elseif($value['products_price'] > 0)
										{
											$value['price'] = $value['products_price'];
											$value['save'] = '';
										}
										else
										{
											$value['price'] = '';
											$value['save'] = '';
										}
										
										//Get first active inventory assigned to product.
										$inventory_values = array();
										if(!empty($value['inventory_assigned']))
										{
											$inventory_assigned = explode(',', trim($value['inventory_assigned'] ?? '', ','));
											
											if(!empty($inventory_assigned))
											{
												foreach($inventory_assigned as $inventory_assigned_item)
												{
													$inventory_assigned_data = explode('|', $inventory_assigned_item ?? '');
													
													if($inventory_assigned_data[0] == 1)
													{
														$inventory_values = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `id` = ? LIMIT 1', ['1', $inventory_assigned_data[1]]);
												
														if(!empty($inventory_values))
														{
															break;
														}
													}
												}
											}
										}
										
										if(!empty($inventory_values))
										{
											$search_results[$key] = $value + array('inventory' => $inventory_values);
										}
										else
										{
											$search_results[$key] = $value + array('inventory' => '');
										}
									}
									else
									{
										//If not a product, set the values for whatever it is.
										$search_results[$key] = $value;
									}
								}
							}
						}
					}
					//Search table for phrase search term
					if(count($search_results) < $max_search_results)
					{
						//Search column of custom_fields if it exist on the table.
						$columns_to_search_table = '';
						$parameters_to_search_table = array($site_id, '1', 'Yes', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%');
						if($table_to_search['table_has_custom_fields'] == 'Yes')
						{
							$columns_to_search_table = ' OR `'.$table_to_search['database_table_name'].'`.`custom_fields` LIKE ?';
							$parameters_to_search_table = array($site_id, '1', 'Yes', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%', '%'.$search_term.'%');
						}
						
						$table_phrase_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', $table_to_search['database_table_name'], '`urls` ON `'.$table_to_search['database_table_name'].'`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? AND (`urls`.`meta_title` LIKE ? OR `urls`.`meta_description` LIKE ? OR `urls`.`content_title` LIKE ? OR `urls`.`table_of_contents` LIKE ? OR `urls`.`top_content` LIKE ? OR `urls`.`bottom_content` LIKE ?'.$columns_to_search_table.') LIMIT '.$max_search_results, $parameters_to_search_table, 'id');
						if(!empty($table_phrase_match)) 
						{
							foreach($table_phrase_match as $key => $value)
							{
								if(!array_key_exists($key, $search_results))
								{
									if(count($search_results) >= $max_search_results) { break; }
									
									$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
									
									if(isset($value['media']))
									{
										$media_data = mediaIdArray($value['media']);
										$value['media_data'] = $media_data;
									}
									
									if($table_to_search['database_table_name'] == 'products')
									{
										$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
										$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
										
										if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
										{
											$value['price'] = $value['products_sale_price'];
											$value['save'] = ($value['products_price'] - $value['products_sale_price']);
										}
										elseif($value['products_price'] > 0)
										{
											$value['price'] = $value['products_price'];
											$value['save'] = '';
										}
										else
										{
											$value['price'] = '';
											$value['save'] = '';
										}
										
										//Get first active inventory assigned to product.
										$inventory_values = array();
										if(!empty($value['inventory_assigned']))
										{
											$inventory_assigned = explode(',', trim($value['inventory_assigned'] ?? '', ','));
											
											if(!empty($inventory_assigned))
											{
												foreach($inventory_assigned as $inventory_assigned_item)
												{
													$inventory_assigned_data = explode('|', $inventory_assigned_item ?? '');
													
													if($inventory_assigned_data[0] == 1)
													{
														$inventory_values = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `id` = ? LIMIT 1', ['1', $inventory_assigned_data[1]]);
												
														if(!empty($inventory_values))
														{
															break;
														}
													}
												}
											}
										}
										
										if(!empty($inventory_values))
										{
											$search_results[$key] = $value + array('inventory' => $inventory_values);
										}
										else
										{
											$search_results[$key] = $value + array('inventory' => '');
										}
									}
									else
									{
										//If not a product, set the values for whatever it is.
										$search_results[$key] = $value;
									}
								}
							}
						}
					}
					//Search table for broad search term
					if(count($search_results) < $max_search_results && strpos($search_term, ' ') !== false)
					{
						$broad_search_array = explode(' ', $search_term);
						
						$columns_to_search_table = '';
						$parameters_to_search_table = array($site_id, '1', 'Yes');
						
						foreach($broad_search_array as $broad_search)
						{
							$columns_to_search_table .= ' AND (`urls`.`meta_title` LIKE ? OR `urls`.`meta_description` LIKE ? OR `urls`.`content_title` LIKE ? OR `urls`.`table_of_contents` LIKE ? OR `urls`.`top_content` LIKE ? OR `urls`.`bottom_content` LIKE ?)';
							$parameters_to_search_table[] = '%'.$broad_search.'%';
							$parameters_to_search_table[] = '%'.$broad_search.'%';
							$parameters_to_search_table[] = '%'.$broad_search.'%';
							$parameters_to_search_table[] = '%'.$broad_search.'%';
							$parameters_to_search_table[] = '%'.$broad_search.'%';
							$parameters_to_search_table[] = '%'.$broad_search.'%';
							
							//Search column of custom_fields if it exist on the table.
							if($table_to_search['table_has_custom_fields'] == 'Yes')
							{
								$columns_to_search_table .= ' AND (`urls`.`meta_title` LIKE ? OR `urls`.`meta_description` LIKE ? OR `urls`.`content_title` LIKE ? OR `urls`.`table_of_contents` LIKE ? OR `urls`.`top_content` LIKE ? OR `urls`.`bottom_content` LIKE ? OR `'.$table_to_search['database_table_name'].'`.`custom_fields` LIKE ?)';
								
								$parameters_to_search_table[] = '%'.$broad_search.'%';
								$parameters_to_search_table[] = '%'.$broad_search.'%';
								$parameters_to_search_table[] = '%'.$broad_search.'%';
								$parameters_to_search_table[] = '%'.$broad_search.'%';
								$parameters_to_search_table[] = '%'.$broad_search.'%';
								$parameters_to_search_table[] = '%'.$broad_search.'%';
								$parameters_to_search_table[] = '%'.$broad_search.'%';
							}
						}
						
						$table_broad_match = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, '*', $table_to_search['database_table_name'], '`urls` ON `'.$table_to_search['database_table_name'].'`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `urls`.`include_in_site_search` = ? '.$columns_to_search_table.' LIMIT '.$max_search_results, $parameters_to_search_table, 'id');
						if(!empty($table_broad_match)) 
						{
							foreach($table_broad_match as $key => $value)
							{
								if(!array_key_exists($key, $search_results))
								{
									if(count($search_results) >= $max_search_results) { break; }
									
									$value['url_data'] = getUrl($value['custom_link'], $value['url_extension'], $sites_end_urls_with, $url_structure, $domain, $value['hierarchy_url'], $value['flat_url'], '', $value['urls_id'], $home_page);
									
									if(isset($value['media']))
									{
										$media_data = mediaIdArray($value['media']);
										$value['media_data'] = $media_data;
									}
									
									
									if($table_to_search['database_table_name'] == 'products')
									{
										$value['products_price'] = ($value['products_price'] * $_SESSION['exchange_rate_difference']);
										$value['products_sale_price'] = ($value['products_sale_price'] * $_SESSION['exchange_rate_difference']);
										
										if(!empty($value['products_sale_price']) && $value['products_sale_price'] > 0 && ((empty($value['products_sale_price_from']) && empty($value['products_sale_price_to'])) || (!empty($value['products_sale_price_from']) && !empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time && strtotime($value['products_sale_price_to']) >= $current_time) || (!empty($value['products_sale_price_from']) && empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_from']) <= $current_time) || (empty($value['products_sale_price_from']) &&!empty($value['products_sale_price_to']) && strtotime($value['products_sale_price_to']) >= $current_time)))
										{
											$value['price'] = $value['products_sale_price'];
											$value['save'] = ($value['products_price'] - $value['products_sale_price']);
										}
										elseif($value['products_price'] > 0)
										{
											$value['price'] = $value['products_price'];
											$value['save'] = '';
										}
										else
										{
											$value['price'] = '';
											$value['save'] = '';
										}
										
										//Get first active inventory assigned to product.
										$inventory_values = array();
										if(!empty($value['inventory_assigned']))
										{
											$inventory_assigned = explode(',', trim($value['inventory_assigned'] ?? '', ','));
											
											if(!empty($inventory_assigned))
											{
												foreach($inventory_assigned as $inventory_assigned_item)
												{
													$inventory_assigned_data = explode('|', $inventory_assigned_item ?? '');
													
													if($inventory_assigned_data[0] == 1)
													{
														$inventory_values = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `id` = ? LIMIT 1', ['1', $inventory_assigned_data[1]]);
												
														if(!empty($inventory_values))
														{
															break;
														}
													}
												}
											}
										}
										
										if(!empty($inventory_values))
										{
											$search_results[$key] = $value + array('inventory' => $inventory_values);
										}
										else
										{
											$search_results[$key] = $value + array('inventory' => '');
										}
									}
									else
									{
										//If not a product, set the values for whatever it is.
										$search_results[$key] = $value;
									}
								}
							}
						}
					}
				}
			}
		}
		
		//Start setting variables for pagination.
		$pages_data['total_number_of_results'] = count($search_results);
		
		if(!empty($site_search_results_per_page) && is_numeric($site_search_results_per_page))
		{
			$search_results_results_per_page = $site_search_results_per_page;
		}
		else
		{
			$search_results_results_per_page = 30;
		}
		
		$page_number = 1;
		if(!empty($_GET["page"]) && is_numeric($_GET["page"]))
		{
			$page_number = $_GET["page"];
		}
		
		$search_results_number_of_paginated_pages = '0';
		if(count($search_results) > 0)
		{
			$search_results_number_of_paginated_pages = ceil(count($search_results) / $search_results_results_per_page);
		}
		
		//If user enters a larger page number in the URL than what is available, send user back to page 1.
		if($page_number > 1 && $page_number > $search_results_number_of_paginated_pages)
		{
			header("Location: ".$pages_data["pages_url"]."?search=".$search_term); exit();
		}
		
		//Offset results based on page number
		$search_results_offset = ($page_number * $search_results_results_per_page) - $search_results_results_per_page;
		$search_results_with_offset = $search_results;
		$search_results_with_offset = array_splice($search_results_with_offset, $search_results_offset, $search_results_results_per_page);
		
		//Set number results count show like: 1 - 30.
		if(!empty($search_results))
		{
			$search_results_start_count = $search_results_offset + 1;
			$search_results_end_count = $search_results_start_count + (count($search_results_with_offset) - 1);
		}
		else
		{
			$search_results_start_count = 0;
			$search_results_end_count = 0;
		}
	}
}