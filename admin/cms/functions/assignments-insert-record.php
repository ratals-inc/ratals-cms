<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/assignments-insert-record.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/assignments-insert-record.php');
}
else
{
	//When you add or edit sub items this is used. It creates the data to INSERT it into the assignments_sub_items tables. This is used on sub-items url like this: website/categories/sub-items
	if(!function_exists('assignmentsInsertRecord'))
	{
		function assignmentsInsertRecord($table_name, $post_group_id, $parent_id, $insert_items)
		{
			//Get next sort number.
			$sql_get_page_groups_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', $table_name, 'WHERE `pages_groups_id` = ? AND `parent_id` = ? ORDER BY `sort` DESC LIMIT 1', [$post_group_id, $parent_id]);
			if(!empty($sql_get_page_groups_row['sort']))	
			{
				$order_counter = $sql_get_page_groups_row['sort'] + 1;
			}
			else
			{
				$order_counter = 1;
			}
			
			//Get all custom fields.
			$sql_custom_field_rows = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'custom_fields', '', [], 'id');
			
			//Get all shipping centers.
			$shipping_center_array = array();
			if($_SESSION['commerce_installed'])
			{
				$shipping_center_array = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'shipping_centers', 'WHERE `status` = ?', [1]);
			}
			
			foreach($insert_items as $insert_item)
			{
				if(isset($insert_item['item_type']) && isset($insert_item['item_id']) && isset($insert_item['table_name']))
				{
					//Get url and record data
					$record_data = $_SESSION['results']->getSelectLeftJoinSingleRecord(__LINE__, __FILE__, '*', 'urls', '`'.$insert_item['table_name'].'` ON `'.$insert_item['table_name'].'`.`urls_id` = `urls`.`id`', 'WHERE `urls_id` = ? LIMIT 1', [$insert_item["item_id"]]);
					
					$first_media_id = NULL;
					$first_media_tag = '';
					if(strpos($record_data['media'], '*||*') !== false)
					{
						$media_data_array = explode('*||*', $record_data['media']);
						$first_media_data = explode('~||~', $media_data_array[0]);
						$first_media_id = $first_media_data[0];
						$first_media_tag = $first_media_data[1];
					}
					elseif(!empty($record_data['media']))
					{
						$first_media_data = explode('~||~', $record_data['media']);
						$first_media_id = $first_media_data[0];
						$first_media_tag = $first_media_data[1];
					}
					
					//If media tag/alt is empty, get media tag/alt from media.
					if(!empty($first_media_id) && empty($first_media_tag))
					{
						$sql_get_media_tag = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$first_media_id]);
						
						if(!empty($sql_get_media_tag))
						{
							$first_media_tag = $sql_get_media_tag['media_tag'];
						}
					}
					
					if(isset($insert_item['item_status']))
					{
						//This is used when saving sub items as item_status submits with update.
						$status = $insert_item['item_status'];
					}
					else
					{
						//Thisis used when adding new sub items to a group
						$status = 1;
					}
					$inventory_status = NULL;
					$inventory_assigned_to_product_status = 1;
					$type = $insert_item['item_type'];
					$pages_groups_id = $post_group_id;
					$inventory_attribute_value_ids = '';
					$sub_products_ids = '';
					$product_price = NULL;
					$product_sale_price = NULL;
					$product_sale_price_from = NULL;
					$product_sale_price_to = NULL;
					$product_review_score = NULL;
					$inventory_id = NULL;
					$inventory_price = NULL;
					$inventory_sale_price = NULL;
					$inventory_sale_price_from = NULL;
					$inventory_sale_price_to = NULL;
					$inventory_url = '';
					$inventory_track_quantity = '';
					$inventory_quantity_available = NULL;
					$inventory_allow_backorders = '';
					$inventory_ships_within = NULL;
					$updated_date = NULL;
					
					$assigned_inventory_ids_unique = array();
					unset($first_inventory_flag);
					
					//If inventory_id isset, that means an inventory is being assigned.
					if(isset($insert_item['inventory_id']) && !empty($insert_item['inventory_id']))
					{
						$type = 'inventory';
						
						$assigned_inventory_ids_unique[] = $insert_item['inventory_id'];
					}
					//If a product is being added and the product_type = Inventory Items
					elseif(isset($record_data['product_type']) && $record_data['product_type'] == 'Inventory Items')
					{
						$type = 'products';
						
						//Get inventory ids assigned to the product.
						if(!empty(trim($record_data["inventory_assigned"], ',')))
						{
							$first_inventory_id_assigned = '';
							
							if(strpos(trim($record_data["inventory_assigned"], ','), ',') !== false)
							{
								$assigned_inventory_ids_arrays = explode(',', trim($record_data["inventory_assigned"], ','));
								
								foreach($assigned_inventory_ids_arrays as $assigned_inventory_ids_arrayss)
								{
									$assigned_inventory_id = explode('|', $assigned_inventory_ids_arrayss);
									
									if(!in_array($assigned_inventory_id[1], $assigned_inventory_ids_unique))
									{
										$assigned_inventory_ids_unique[] = $assigned_inventory_id[1];
									}
								}
							}
							else
							{
								$assigned_inventory_id = explode('|', trim($record_data["inventory_assigned"], ','));
								
								if(!in_array($assigned_inventory_id[1], $assigned_inventory_ids_unique))
								{
									$assigned_inventory_ids_unique[] = $assigned_inventory_id[1];
								}
							}
						}
					}
					//If a product is being added and the product_type = Sub Products
					elseif(isset($record_data['product_type']) && $record_data['product_type'] == 'Sub Products')
					{
						$type = 'sub_products';
						
						//This method will get the correct pricing for a product with sub products. 
						$sub_products_data = getSubProductIds($record_data['urls_id']);
						
						if(!empty($sub_products_data['sub_products_ids']))
						{
							$sub_products_ids = $sub_products_data['sub_products_ids'];
						}
						
						if(!empty($sub_products_data['product_price']) && $sub_products_data['product_price'] > 0)
						{
							$product_price = $sub_products_data['product_price'];
						}
						
						if(!empty($sub_products_data['product_sale_price']) && $sub_products_data['product_sale_price'] > 0)
						{
							$product_sale_price = $sub_products_data['product_sale_price'];
						}
						
						if(!empty($sub_products_data['product_sale_price_from']))
						{
							$product_sale_price_from = $sub_products_data['product_sale_price_from'];
						}
						
						if(!empty($sub_products_data['product_sale_price_to']))
						{
							$product_sale_price_to = $sub_products_data['product_sale_price_to'];
						}
					}
					
					//If the item being assigned is assocaited with an inventory, get the data needed for assignment table.
					if(!empty($assigned_inventory_ids_unique))
					{
						$select_inventory_placeholders = '';
						$select_inventory_ids = array();
						foreach($assigned_inventory_ids_unique as $assigned_inventory_ids)
						{
							$select_inventory_placeholders .= '?,';
							$select_inventory_ids[] = $assigned_inventory_ids;
						}
						
						$select_inventory_placeholders = trim($select_inventory_placeholders ?? '', ',');
						
						$sql_get_inventory_data_rows = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `id` IN ('.$select_inventory_placeholders.')', $select_inventory_ids, 'id');
						
						foreach($assigned_inventory_ids_unique as $assigned_inventory_ids)
						{
							//Get inventory assigned to this product.
							$sql_get_inventory_data_row = $sql_get_inventory_data_rows[$assigned_inventory_ids];
							
							
							if(!empty($sql_get_inventory_data_row))
							{
								//Set this data for first inventory item.
								if(!isset($first_inventory_flag))
								{
									$first_inventory_flag = 'Yes';
									
									$inventory_id = $sql_get_inventory_data_row['id'];
									
									$inventory_status = $sql_get_inventory_data_row['status'];
									
									$product_review_score = $record_data['review_score'];
									
									//Inventory media set
									$inventory_media_set = 'No';
									
									//If inventory and has media, set that media id.
									if($type == 'inventory')
									{
										if(strpos($sql_get_inventory_data_row['media'], '*||*') !== false)
										{
											$media_data_array = explode('*||*', $sql_get_inventory_data_row['media']);
											$first_media_data = explode('~||~', $media_data_array[0]);
											$first_media_id = $first_media_data[0];
											$first_media_tag = $first_media_data[1];
											$inventory_media_set = 'Yes';
										}
										elseif(!empty($sql_get_inventory_data_row['media']))
										{
											$first_media_data = explode('~||~', $sql_get_inventory_data_row['media']);
											$first_media_id = $first_media_data[0];
											$first_media_tag = $first_media_data[1];
											$inventory_media_set = 'Yes';
										}
									}
									
									//If media tag/alt is empty, get media tag/alt from media.
									if($inventory_media_set == 'Yes' && !empty($first_media_id) && empty($first_media_tag))
									{
										$sql_get_media_tag = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$first_media_id]);
										
										if(!empty($sql_get_media_tag))
										{
											$first_media_tag = $sql_get_media_tag['media_tag'];
										}
									}
									
									if(!empty($sql_get_inventory_data_row['price']) && $sql_get_inventory_data_row['price'] > 0)
									{
										$product_price = $sql_get_inventory_data_row['price'];
										$inventory_price = $sql_get_inventory_data_row['price'];
									}
									
									if(!empty($sql_get_inventory_data_row['sale_price']) && $sql_get_inventory_data_row['sale_price'] > 0)
									{
										$product_sale_price = $sql_get_inventory_data_row['sale_price'];
										$inventory_sale_price = $sql_get_inventory_data_row['sale_price'];
									}
									
									if(!empty($sql_get_inventory_data_row['sale_price_from']))
									{
										$product_sale_price_from = $sql_get_inventory_data_row['sale_price_from'];
										$inventory_sale_price_from = $sql_get_inventory_data_row['sale_price_from'];
									}
									
									if(!empty($sql_get_inventory_data_row['sale_price_to']))
									{
										$product_sale_price_to = $sql_get_inventory_data_row['sale_price_to'];
										$inventory_sale_price_to = $sql_get_inventory_data_row['sale_price_to'];
									}
									
									if(!empty($sql_get_inventory_data_row['ships_within']))
									{
										$inventory_ships_within = $sql_get_inventory_data_row['ships_within'];
									}
									
									if(!empty($sql_get_inventory_data_row['allow_backorders']))
									{
										$inventory_allow_backorders = $sql_get_inventory_data_row['allow_backorders'];
									}
									
									//Putting shipping centers assigned to this inventory in an array.
									$shipping_centers_array = array();
									if(!empty($sql_get_inventory_data_row['shipping_centers']) && strpos($sql_get_inventory_data_row['shipping_centers'],',') !== false)
									{
										$shipping_centers_array = explode(',',$sql_get_inventory_data_row['shipping_centers']);
									}
									elseif(!empty($sql_get_inventory_data_row['shipping_centers']))
									{
										$shipping_centers_array[] = $sql_get_inventory_data_row['shipping_centers'];
									}
									
									//Looking for a shipping center thats not tracking qty/dropship on this inventory item.
									$inventory_track_quantity = 'Yes';
									foreach($shipping_center_array as $shipping_center_array_check)
									{
										if(in_array($shipping_center_array_check['id'],$shipping_centers_array))
										{
											if($shipping_center_array_check['track_inventory'] == 'No')
											{
												$inventory_track_quantity = 'No';
												break;
											}
										}
									}
									
									//Start QTY available for each center
									$shipping_centers_available = array();
									if(!empty($sql_get_inventory_data_row['shipping_centers_available']) && strpos($sql_get_inventory_data_row['shipping_centers_available'],',') !== false)
									{
										$shipping_centers_available_array = explode(',',$sql_get_inventory_data_row['shipping_centers_available']);
										
										foreach($shipping_centers_available_array as $shipping_centers_available_data)
										{
											$shipping_centers_available_data_exploded = explode('=',$shipping_centers_available_data);
											$shipping_centers_available[$shipping_centers_available_data_exploded[0]] = $shipping_centers_available_data_exploded[1];
										}
										$inventory_quantity_available = array_sum($shipping_centers_available);
									}
									elseif(!empty($sql_get_inventory_data_row['shipping_centers_available']) && strpos($sql_get_inventory_data_row['shipping_centers_available'],'=') !== false)
									{
										$shipping_centers_available_data_exploded = explode('=',$sql_get_inventory_data_row['shipping_centers_available']);
										$shipping_centers_available[$shipping_centers_available_data_exploded[0]] = $shipping_centers_available_data_exploded[1];
										$inventory_quantity_available = array_sum($shipping_centers_available);
									}
									//Start QTY available for each center
									
									//Start get all assgined attribute column names
									$assigned_column_names = array();
									$assigned_column_type = array();
									if(!empty(trim($record_data['inventory_attribute_ids'], ',')))
									{
										$inventory_attribute_ids_array = array();
										$inventory_attribute_ids_array = explode(',', trim($record_data['inventory_attribute_ids'], ','));
										foreach($inventory_attribute_ids_array as $inventory_attribute_id)
										{
											$sql_get_custom_field_column_name_row[] = $sql_custom_field_rows[$inventory_attribute_id];
											
											if(!empty($sql_custom_field_rows))
											{
												foreach($sql_custom_field_rows as $sql_custom_fields_data_row)
												{
													if($sql_custom_fields_data_row['field_type'] == 'Inventory Attribute')
													{
														$assigned_column_names[$sql_custom_fields_data_row['id']] = $sql_custom_fields_data_row['column_name'];
														
														$assigned_column_type[$sql_custom_fields_data_row['id']] = $sql_custom_fields_data_row['cf_display_as'];
														
													}
												}
											}
										}
									}
									//End get all assgined attribute column names
									
									//Start get custom field values/names
									$assigned_column_values = array();
									if(!empty($assigned_column_names))
									{
										foreach($assigned_column_names as $key => $value)
										{
											$sql_get_swatch_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields_options', 'WHERE `custom_fields_id` = ?', [$key]);
											
											if(!empty($sql_get_swatch_values))
											{
												foreach($sql_get_swatch_values as $sql_get_swatch_values_row)
												{
													$option_data = JSON_DECODE($sql_get_swatch_values_row['option_data'] ?? '', true);
													
													$sql_get_swatch_values_row['value'] = $option_data[$_SESSION['admin_language']]['value'] ?? '';
													
													$assigned_column_values[$value][$sql_get_swatch_values_row['id']] = $sql_get_swatch_values_row['value'];
												}
											}
										}
									}
									//End get custom field values/names
								}
								
								if(strpos(trim($record_data['inventory_attribute_ids'], ','), ',') !== false)
								{
									$inventory_attribute_ids = explode(',', trim($record_data['inventory_attribute_ids'] ?? '', ','));
									
									foreach($inventory_attribute_ids as $inventory_attribute_id)
									{
										$sql_get_custom_field_column_name_row = $sql_custom_field_rows[$inventory_attribute_id];
										
										//Get value from option_data JSON
										//Get the custom_field admin_name
										$custom_field_name = JSON_DECODE($sql_get_custom_field_column_name_row['custom_field_name'] ?? '', true);
										$custom_field_admin_name = $custom_field_name[$_SESSION['admin_language']]['admin_name'] ?? '';
										
										//Get the custom field option value set in the custom_field column.
										$option_datas = JSON_DECODE($sql_get_inventory_data_row['custom_fields'] ?? '', true);
										
										//Get the value set on the custom_field_option.
										$custom_fields_optins_value = $assigned_column_values[$sql_get_custom_field_column_name_row['column_name']][$option_datas[$sql_get_custom_field_column_name_row['column_name']]] ?? '';
										
										if(!empty($custom_field_admin_name) && !empty($custom_fields_optins_value))
										{
											if($type == 'inventory')
											{
												$inventory_url .= $custom_field_admin_name.'='.$custom_fields_optins_value.'&';
											}
										}
										if(isset($inventory_attribute_id) && !empty($inventory_attribute_id) && isset($sql_get_custom_field_column_name_row['column_name']) && !empty($sql_get_custom_field_column_name_row['column_name']) && isset($option_datas[$sql_get_custom_field_column_name_row['column_name']]) && !empty($option_datas[$sql_get_custom_field_column_name_row['column_name']]))
										{
											$inventory_attribute_value_ids .= $inventory_attribute_id.':'.$option_datas[$sql_get_custom_field_column_name_row['column_name']].',';
										}
									}
								}
								elseif(!empty(trim($record_data['inventory_attribute_ids'] ?? '', ',')))
								{
									$record_data['inventory_attribute_ids'] = trim($record_data['inventory_attribute_ids'] ?? '', ',');
									
									$sql_get_custom_field_column_name_row = $sql_custom_field_rows[$record_data['inventory_attribute_ids']];
									
									//Get value from option_data JSON
									//Get the custom_field admin_name
									$custom_field_name = JSON_DECODE($sql_get_custom_field_column_name_row['custom_field_name'] ?? '', true);
									$custom_field_admin_name = $custom_field_name[$_SESSION['admin_language']]['admin_name'] ?? '';
									
									//Get the custom field option value set in the custom_field column.
									$option_datas = JSON_DECODE($sql_get_inventory_data_row['custom_fields'] ?? '', true);
									
									//Get the value set on the custom_field_option.
									$custom_fields_optins_value = $assigned_column_values[$sql_get_custom_field_column_name_row['column_name']][$option_datas[$sql_get_custom_field_column_name_row['column_name']]] ?? '';
									
									if(!empty($custom_field_admin_name) && !empty($custom_fields_optins_value))
									{
										if($type == 'inventory')
										{
											$inventory_url .= $custom_field_admin_name.'='.$custom_fields_optins_value.'&';
										}
									}
									
									if(isset($record_data['inventory_attribute_ids']) && !empty($record_data['inventory_attribute_ids']) && isset($sql_get_custom_field_column_name_row['column_name']) && !empty($sql_get_custom_field_column_name_row['column_name']) && isset($option_datas[$sql_get_custom_field_column_name_row['column_name']]) && !empty($option_datas[$sql_get_custom_field_column_name_row['column_name']]))
									{
										$inventory_attribute_value_ids .= trim($record_data['inventory_attribute_ids'], ',').':'.$option_datas[$sql_get_custom_field_column_name_row['column_name']].',';
									}
								}
								
								if(!empty($inventory_attribute_value_ids)) { $inventory_attribute_value_ids = ','.trim($inventory_attribute_value_ids, ',').','; }
								if(!empty($inventory_url)) { $inventory_url = '?'.trim($inventory_url, '&'); }
							}
						}
					}
					
					$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, $table_name, '`site_id`, `status`, `item_status`, `inventory_status`, `inventory_assigned_to_product_status`, `parent_id_table_name`, `child_id_table_name`, `type`, `pages_groups_id`, `parent_id`, `child_id`, `inventory_attribute_value_ids`, `sub_products_ids`, `product_price`, `product_sale_price`, `product_sale_price_from`, `product_sale_price_to`, `product_review_score`, `inventory_id`, `inventory_price`, `inventory_sale_price`, `inventory_sale_price_from`, `inventory_sale_price_to`, `inventory_url`, `media_id`, `media_tag`, `inventory_track_quantity`, `inventory_quantity_available`, `inventory_allow_backorders`, `inventory_ships_within`, `sort`, `updated_date`', "?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?", [$_SESSION["site_set_for_editing"], $status, $record_data['url_status'], $inventory_status, $inventory_assigned_to_product_status, $_SESSION['admin_table_name'], $insert_item['table_name'], $type, $pages_groups_id, $parent_id, $insert_item["item_id"], $inventory_attribute_value_ids, $sub_products_ids, $product_price, $product_sale_price, $product_sale_price_from, $product_sale_price_to, $product_review_score, $inventory_id, $inventory_price, $inventory_sale_price, $inventory_sale_price_from, $inventory_sale_price_to, $inventory_url, $first_media_id, $first_media_tag, $inventory_track_quantity, $inventory_quantity_available, $inventory_allow_backorders, $inventory_ships_within, $order_counter, $updated_date]);
					
					$order_counter = $order_counter + 1;
				}
			}
		}
	}
}