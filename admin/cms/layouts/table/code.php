<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/code.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/code.php');
}
else
{
	//Query table to see if it has urls
	$table_uses_urls = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'LIMIT 1', []);
	$table_urls = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'LIMIT 1', []);
	
	//Query DB with correct site_id
	$site_id_no_joined_query = '';
	$site_id_joined_query = '';
	$site_id_value = array();
	
	if($_SESSION['admin_table_name'] == "custom_fields" || $_SESSION['admin_table_name'] == "custom_fields_options")
	{
		$site_id_no_joined_query = ' AND (`site_id` = ? OR `site_id` = "0") ';
		$site_id_joined_query = ' AND (`site_id` = ? OR `site_id` = "0") '; //Requires db column name on join query so its no ambiguous.
		$site_id_value = array($_SESSION["site_set_for_editing"]);
	}
	elseif($_SESSION['admin_site_id_global'] == 'No')
	{
		$site_id_no_joined_query = ' AND `site_id` = ? ';
		$site_id_joined_query = ' AND `'.$_SESSION['admin_table_name'].'`.`site_id` = ? '; //Requires db column name on join query so its no ambiguous.
		$site_id_value = array($_SESSION["site_set_for_editing"]);
	}
	
	//SQL conditions for sub items
	$sub_item_id = '';
	$sub_item_id_value = array();
	$sub_item_order_by = '';
	$sub_products_assigned = '';
	if(!empty(trim($_GET["rid"] ?? '')) && $_SESSION['admin_sub_page'] == 'Yes' && !empty($_SESSION['admin_table_link_column']) && !empty($_SESSION['admin_parent_table_name'])) 
	{
		//If id is or sub_id is set in url.
		$sub_sub_ids = '';
		$sub_sub_ids_value = array();
		
		if($_SESSION['record_has_url'] == 'Yes' && !empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET["sub-rid"] ?? '')))
		{
			$sub_sub_ids = " `urls_id` = ? AND `".$_SESSION['admin_table_link_column']."` = ? ";
			$sub_sub_ids_value = array(trim($_GET["sub-rid"] ?? ''), trim($_GET["rid"] ?? ''));
			$sub_table_for_results = $_SESSION['admin_table_name'];
		}
		elseif(!empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET["sub-rid"] ?? '')))
		{
			$sub_sub_ids = " `id` = ? AND `".$_SESSION['admin_table_link_column']."` = ? ";
			$sub_sub_ids_value = array(trim($_GET["sub-rid"] ?? ''), trim($_GET["rid"] ?? ''));
			$sub_table_for_results = $_SESSION['admin_table_name'];
		}
		elseif($_SESSION['record_has_url'] == 'Yes' && !empty(trim($_GET["rid"] ?? '')))
		{
			$sub_sub_ids = " `urls_id` = ? ";
			$sub_sub_ids_value = array(trim($_GET["rid"] ?? ''));
			$sub_table_for_results = $_SESSION['admin_parent_table_name'];
		}
		elseif(!empty(trim($_GET["rid"] ?? '')))
		{
			$sub_sub_ids = " `id` = ? ";
			$sub_sub_ids_value = array(trim($_GET["rid"] ?? ''));
			$sub_table_for_results = $_SESSION['admin_parent_table_name'];
		}
		
		//Query if there are results for the parent item of this sub item.
		$item_exist_parameters = array_merge($sub_sub_ids_value, $site_id_value);
		$item_exist = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $sub_table_for_results, 'WHERE '.$sub_sub_ids.$site_id_no_joined_query, $item_exist_parameters);
		if(empty($item_exist)) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
		
		//Column name for sub_id's in database
		$sub_item_id = " AND `".$_SESSION['admin_table_link_column']."` = ? "; 
		$sub_item_id_value[] = trim($_GET["rid"] ?? '');
		
		//If parent table has a indicator for selected rows first. Menus use this so you can get the first menu items, then get the childern items.
		if($_SESSION['admin_parent_indicator'] == 'Yes' && empty(trim($_GET["sub-rid"] ?? '')))
		{
			$sub_item_id .= " AND `parent_id` = ?"; 
			$sub_item_id_value[] = '0';
		}
		elseif($_SESSION['admin_parent_indicator'] == 'Yes' && !empty(trim($_GET["sub-rid"] ?? '')))
		{
			$sub_item_id .= " AND `parent_id` = ?";
			$sub_item_id_value[] = trim($_GET["sub-rid"] ?? '');
		}
		
		//If drag and drop sort list order so admin shows results in current order.
		if($_SESSION['admin_sort_or_dragdrop'] == 'dragdrop')
		{
			$sub_item_order_by = " ORDER BY `sort` ASC ";
		}
	}
	elseif($_SESSION['admin_assigned_type'] == 'sub_products_assigned' && !empty(trim($_GET["rid"] ?? '')))
	{
		$sub_products_assigned = " AND `product_type` = 'Inventory Items' ";
	}
	elseif(empty(trim($_GET["rid"] ?? '')) && empty(trim($_GET["sub-rid"] ?? '')) && $_SESSION['admin_sub_page'] == 'Yes')
	{
		header("Location: ".$_SESSION['admin_url_no_records']);
		exit();
	}
	
	$head_title_name = '';
	if(!empty(trim($_GET["rid"] ?? '')) && $_SESSION['admin_sub_page'] == 'Yes' && !empty($_SESSION['admin_parent_table_name'])) 
	{
		$head_title_name_parameters = array_merge(array(trim($_GET["rid"] ?? '')), $site_id_value);
		$sql_head_title_name = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_parent_table_name'], 'WHERE `id` = ? '.$site_id_no_joined_query, $head_title_name_parameters);
		
		$head_title_name = '';
		if(isset($sql_head_title_name['name']))
		{
			$head_title_name = $sql_head_title_name['name'].' - ';
		}
		if(isset($sql_head_title_name['frontend_name']))
		{
			$head_title_name = $sql_head_title_name['frontend_name'].' - ';
		}
		elseif(isset($sql_head_title_name['admin_name']))
		{
			$head_title_name = $sql_head_title_name['admin_name'].' - ';
		}
	}
	
	//If table == 'leads' add to were clause.
	$good_lead_filter = '';
	if($_SESSION['admin_table_name'] == "leads")
	{
		if($_SESSION['admin_class'] == 'good-leads')
		{
			$good_lead_filter = " AND `lead_status` != 'Junk' ";
		}
		elseif($_SESSION['admin_class'] == 'junk-leads')
		{
			$good_lead_filter = " AND `lead_status` = 'Junk' ";
		}
	}
	
	//This will display all sub categories of a category or all categories with the path_level of 0.
	$get_hierarchy_categories_id = '';
	$get_hierarchy_categories_id_values = array();
	
	$get_hierarchy_categories_id = '';
	if(isset($_GET['layout']) && $_GET['layout'] == 'hierarchy' && isset($_GET["path-ids"]) && (is_numeric($_GET["path-ids"]) || !empty($_GET["path-ids"])))
	{
		$get_hierarchy_categories_id = " AND `path_level` = ? ";
		$get_hierarchy_categories_id_values = array($_GET["path-ids"]);
	}
	
	//Set assigned columns and order when changed.
	if(isset($_POST["save"])) 
	{
		$delete_columns_parameters = array($_SESSION['user_id'], $_SESSION['admin_table_name']);
		
		$results->getDeleteRecord(__LINE__, __FILE__, 'assigned_fields', 'WHERE `user_id` = ? AND `table_name` = ?', $delete_columns_parameters);
		
		$columns_counter = 1;
		if(!empty($_POST["column"])) { $selected_columns = $_POST["column"]; } else { $selected_columns = array(); }
		if(!empty($selected_columns))
		{
			foreach($selected_columns as $selected_column)
			{
				$selected_column_exploded = explode("-", $selected_column);
				
				$results->getInsertRecord(__LINE__, __FILE__, 'assigned_fields', '`site_id`, `user_id`, `field_id`, `table_name`, `default_or_custom`, `sort`', '?,?,?,?,?,?', [0, $_SESSION['user_id'], $selected_column_exploded[0], $_SESSION['admin_table_name'], $selected_column_exploded[1], $columns_counter ++]);
			}
		}
	}
	
	//Query db for all columns assinged to this admin page.
	$assigned_fields_parameter = array($_SESSION['user_id'], $_SESSION['admin_table_name']);
	$assigned_fields_result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'assigned_fields', 'WHERE `user_id` = ? AND `table_name` = ? ORDER BY `sort` ASC', $assigned_fields_parameter);
	
	$sql_account_columns_default_assigned_id = array();
	$sql_account_columns_custom_assigned_id = array();
	if(!empty($assigned_fields_result))
	{
		foreach($assigned_fields_result as $sql_account_columns_assigned_rows) 
		{
			if($sql_account_columns_assigned_rows["default_or_custom"] == "default")
			{
				$sql_account_columns_assigned_array[] = $sql_account_columns_assigned_rows;
				$sql_account_columns_default_assigned_id[] = $sql_account_columns_assigned_rows["field_id"];
				
				$sql_account_columns_default = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` = ?', [$sql_account_columns_assigned_rows["field_id"]]);
				
				if(!empty($sql_account_columns_default))
				{
					
					$all_languages_admin_field_values = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ?', ['languages'], 'value');
					$site_languages_unique_list = array();
					
					foreach($sql_account_columns_default as $sql_account_columns_default_rows) 
					{
						//Get option_data and custom_field_name for custom fields options / Languages
						if($sql_account_columns_default_rows['column_name'] == 'custom_field_name' || $sql_account_columns_default_rows['column_name'] == 'option_data')
						{
							$site_settings_languages = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'sites', 'ORDER BY `site_language` ASC', []);
							
							if(!empty($site_settings_languages))
							{
								foreach($site_settings_languages as $site_settings_language)
								{
									if(isset($all_languages_admin_field_values[$site_settings_language['site_language']]['label']))
									{
										//If multiple sites use the same language, only add the language column once.
										if(!in_array($site_settings_language['site_language'], $site_languages_unique_list))
										{
											$site_languages_unique_list[] = $site_settings_language['site_language'];
											
											$sql_account_columns_array[] = array('name' => $all_languages_admin_field_values[$site_settings_language['site_language']]['label'], 'language' => $site_settings_language['site_language'], "default_or_custom" => "default") + $sql_account_columns_default_rows ;
										}
									}
								}
							}
						}
						else
						{
							$sql_account_columns_array[] = $sql_account_columns_default_rows + array("default_or_custom" => "default");
						}
					}
				}
			}
			
			if($sql_account_columns_assigned_rows["default_or_custom"] == "custom")
			{
				$sql_account_columns_assigned_array[] = $sql_account_columns_assigned_rows;
				$sql_account_columns_custom_assigned_id[] = $sql_account_columns_assigned_rows["field_id"];
				
				$account_columns_default_parameter = array_merge(array($sql_account_columns_assigned_rows["field_id"]), $site_id_value);
				
				$sql_account_columns_default = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ? '.$site_id_no_joined_query , $account_columns_default_parameter);
				
				if(!empty($sql_account_columns_default))
				{
					foreach($sql_account_columns_default as $sql_account_columns_default_rows) 
					{
						$custom_field_name = JSON_DECODE($sql_account_columns_default_rows['custom_field_name'] ?? '', true);
						
						$sql_account_columns_default_rows['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
						$sql_account_columns_default_rows['admin_name'] = $custom_field_name[$_SESSION['admin_language']]['admin_name'] ?? '';
						
						$sql_account_columns_array[] = $sql_account_columns_default_rows + array("default_or_custom" => "custom");
					}
				}
			}
		}
	}
	
	//Get query string from URL. Anything after the questionmark in URL on Search.
	$url_sorting_query = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	$url_sorting_query = parse_url($url_sorting_query, PHP_URL_QUERY);
	
	$no_duplicate_fields = array();
	$url_search_fileds_array = array();
	$url_search_fileds_string = '';
	$sort_column = '';
	$sql_sorting_type = 'DESC';
	$next_sorting_type = "ascend";
	$redirect_search_url = '';
	$url_sorting = '';
	$url_sorting_name = '';
	$url_sorting_value = '';
	
	if(!empty($url_sorting_query))
	{
		$url_sorting_query = urldecode($url_sorting_query);
		
		$url_sorting_query_exploded = explode('&', $url_sorting_query);
		
		if(!empty($url_sorting_query_exploded))
		{
			//Reversing the order will make it get the last searched field value first to put back in the array.
			$url_sorting_query_exploded = array_reverse($url_sorting_query_exploded);
			
			foreach($url_sorting_query_exploded as $url_sorting_query_data)
			{
				$check_value_data = explode('=', $url_sorting_query_data);
				
				if(!empty($check_value_data[1]) || is_numeric($check_value_data[1]))
				{
					if(!in_array($url_sorting_query_data, $no_duplicate_fields))
					{
						//get clean url to redirect to.
						if($check_value_data[1] != 'ascend' && $check_value_data[1] != 'descend' && $check_value_data[0] != 'results-per-page' && $check_value_data[0] != 'page-number')
						{
							$no_duplicate_fields[] = $url_sorting_query_data;
							$url_search_fileds_array[] = $url_sorting_query_data;
							$url_search_fileds_string .= "&".$url_sorting_query_data;
						}
						//get sorting data for URL.
						elseif($check_value_data[1] == 'ascend' || $check_value_data[1] == 'descend')
						{						
							if($check_value_data[1] == 'ascend')
							{
								$no_duplicate_fields[] = $url_sorting_query_data;
								$url_search_fileds_array[] = $url_sorting_query_data;
								
								$sql_sorting_type = 'ASC'; 
								$next_sorting_type = "descend"; 
								$url_sorting = '&'.$url_sorting_query_data;
								$url_sorting_name = $check_value_data[0];
								$url_sorting_value = $check_value_data[1];
							}
							elseif($check_value_data[1] == 'descend')
							{
								$no_duplicate_fields[] = $url_sorting_query_data;
								$url_search_fileds_array[] = $url_sorting_query_data;
								
								$sql_sorting_type = 'DESC';
								$next_sorting_type = "ascend";
								$url_sorting = '&'.$url_sorting_query_data;
								$url_sorting_name = $check_value_data[0];
								$url_sorting_value = $check_value_data[1];
							}
						}
					}
				}
				else
				{
					$redirect_search_url = 'Yes';
				}
			}
			
			if($redirect_search_url == 'Yes')
			{
				if(!empty($url_sorting) || !empty($url_search_fileds_string))
				{ 
					header("Location: ".$_SESSION['admin_url']."/?".trim($url_sorting.$url_search_fileds_string, '&'));
					exit;
				}
				else
				{
					header("Location: ".$_SESSION['admin_url']."/");
					exit;
				}
			}
		}
	}
	
	//Build SQL search query to search db.
	$sql_query_for_search = '';
	$sql_query_for_search_values = array();
	
	if(!empty($url_search_fileds_array)) 
	{
		foreach($url_search_fileds_array as $build_mysql_query_string)
		{
			$build_mysql_query_data = explode("=", $build_mysql_query_string);
			
			if(!empty($sql_account_columns_array))
			{
				foreach($sql_account_columns_array as $sql_account_columns_active) 
				{
					//echo '<pre>'; print_r($sql_account_columns_active); echo '</pre>';
					if($build_mysql_query_data[1] != 'ascend' && $build_mysql_query_data[1] != 'descend')
					{
						//start-date-range query
						if($build_mysql_query_data[0] == "textfield-".$sql_account_columns_active["url_name"]."-start-range") 
						{
							$admin_field_column = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'Where `column_name` = ? LIMIT 1', [$sql_account_columns_active["column_name"]]);
							
							if(isset($admin_field_column['data_type']) && ($admin_field_column['data_type'] == 'datetime' || $admin_field_column['data_type'] == 'timestamp'))
							{
								//If date is included in the time, adjust date based on timezone.
								$from_time = dateToUtc($build_mysql_query_data[1], '00:00:00', 'Y-m-d H:i:s');
							}
							else
							{
								//If submitted date has no time, just look up the date submitted by user.
								$from_time = $build_mysql_query_data[1];
							}
							
							$sql_query_for_search .= " AND `".$sql_account_columns_active["column_name"]."` >= ?";
							$sql_query_for_search_values[] = $from_time;
						}
						
						//end-date-range query
						elseif($build_mysql_query_data[0] == "textfield-".$sql_account_columns_active["url_name"]."-end-range") 
						{
							$admin_field_column = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'Where `column_name` = ? LIMIT 1', [$sql_account_columns_active["column_name"]]);
							
							if(isset($admin_field_column['data_type']) && ($admin_field_column['data_type'] == 'datetime' || $admin_field_column['data_type'] == 'timestamp'))
							{
								//If date is included in the time, adjust date based on timezone.
								$to_time = dateToUtc($build_mysql_query_data[1], '23:59:59', 'Y-m-d H:i:s');
							}
							else
							{
								//If submitted date has no time, just look up the date submitted by user.
								$to_time = $build_mysql_query_data[1];
							}
							
							$sql_query_for_search .= " AND `".$sql_account_columns_active["column_name"]."` <= ?";
							$sql_query_for_search_values[] = $to_time;
						}
						
						//make sure prices use decimal for currency search
						elseif($build_mysql_query_data[0] == "textfield-".$sql_account_columns_active["url_name"] 																  
								&& (
								$sql_account_columns_active["column_name"] == "amount" 
								|| $sql_account_columns_active["column_name"] == "price" 
								|| $sql_account_columns_active["column_name"] == "sale_price" 
								|| $sql_account_columns_active["column_name"] == "standard_cost" 
								|| $sql_account_columns_active["column_name"] == "landed_cost")
								) 
						{
							$sql_query_for_search .= " AND `".$sql_account_columns_active["column_name"]."` LIKE ?";
							$sql_query_for_search_values[] = '%'.str_replace($_SESSION['currency_fractional_separator'], '.', $build_mysql_query_data[1] ?? '').'%';
						}
						
						//price field query
						elseif($build_mysql_query_data[0] == "price-".$sql_account_columns_active["url_name"]) 
						{
							$sql_query_for_search .= " AND `".$_SESSION['admin_table_name']."`.`".$sql_account_columns_active["column_name"]."` LIKE ?";
							$sql_query_for_search_values[] = '%'.str_replace(',', '.', $build_mysql_query_data[1] ?? '').'%';
	
						}
						
						//If table has urls_id and the id is searched, look for the id in the urls table.
						elseif($build_mysql_query_data[0] == "textfield-id" && array_key_exists('urls_id', $table_uses_urls)) 
						{
							$sql_query_for_search .= " AND `urls`.`id` LIKE ?";
							$sql_query_for_search_values[] = ''.$build_mysql_query_data[1].'';
							break;
						}
						
						//textfield query
						elseif($build_mysql_query_data[0] == "textfield-".$sql_account_columns_active["url_name"]) 
						{
							//When table is orders, join orders_ship_to so admin users can search orders by the customers info.
							if($_SESSION['admin_table_name'] == 'orders' && ($sql_account_columns_active["column_name"] == 'orders_id' || $sql_account_columns_active["column_name"] == 'first_name' || $sql_account_columns_active["column_name"] == 'last_name' || $sql_account_columns_active["column_name"] == 'company_name' || $sql_account_columns_active["column_name"] == 'street_address_1' || $sql_account_columns_active["column_name"] == 'street_address_2' || $sql_account_columns_active["column_name"] == 'city' || $sql_account_columns_active["column_name"] == 'country' || $sql_account_columns_active["column_name"] == 'state' || $sql_account_columns_active["column_name"] == 'postal_code' || $sql_account_columns_active["column_name"] == 'address_type' || $sql_account_columns_active["column_name"] == 'loading_dock' || $sql_account_columns_active["column_name"] == 'tax_exempt'))
							{
								$sql_query_for_search .= " AND `orders_ship_to`.`".$sql_account_columns_active["column_name"]."` LIKE ?";
								$sql_query_for_search_values[] = '%'.$build_mysql_query_data[1].'%';
							}
							//If column is ID or Record ID and exits search extact match / =.
							elseif(array_key_exists($sql_account_columns_active["column_name"], $table_uses_urls) && $build_mysql_query_data[0] == 'textfield-id')
							{
								$sql_query_for_search .= " AND `".$_SESSION['admin_table_name']."`.`".$sql_account_columns_active["column_name"]."` = ?";
								$sql_query_for_search_values[] = $build_mysql_query_data[1];
							}
							//If column exits on main table search main table.
							elseif(array_key_exists($sql_account_columns_active["column_name"], $table_uses_urls))
							{
								$sql_query_for_search .= " AND `".$_SESSION['admin_table_name']."`.`".$sql_account_columns_active["column_name"]."` LIKE ?";
								$sql_query_for_search_values[] = '%'.$build_mysql_query_data[1].'%';
							}
							//If column exits on urls table search urls table.
							elseif(array_key_exists($sql_account_columns_active["column_name"], $table_urls) && array_key_exists('urls_id', $table_uses_urls))
							{
								$sql_query_for_search .= " AND `urls`.`".$sql_account_columns_active["column_name"]."` LIKE ?";
								$sql_query_for_search_values[] = '%'.$build_mysql_query_data[1].'%';
							}
							//If custom_fields column exits on main table search custom_fields column.
							elseif(array_key_exists('custom_fields', $table_uses_urls))
							{
								$sql_query_for_search .= " AND JSON_UNQUOTE(JSON_EXTRACT(custom_fields, '$.".$sql_account_columns_active["column_name"]."')) LIKE ?";
								$sql_query_for_search_values[] = '%'.$build_mysql_query_data[1].'%';
							}
						}
						
						//dropdown query
						elseif($build_mysql_query_data[0] == "dropdown-".$sql_account_columns_active["url_name"]) 
						{
							//When table is orders, join orders_ship_to so admin users can search orders by the customers info.
							if($_SESSION['admin_table_name'] == 'orders' && ($sql_account_columns_active["column_name"] == 'orders_id' || $sql_account_columns_active["column_name"] == 'first_name' || $sql_account_columns_active["column_name"] == 'last_name' || $sql_account_columns_active["column_name"] == 'company_name' || $sql_account_columns_active["column_name"] == 'street_address_1' || $sql_account_columns_active["column_name"] == 'street_address_2' || $sql_account_columns_active["column_name"] == 'city' || $sql_account_columns_active["column_name"] == 'country' || $sql_account_columns_active["column_name"] == 'state' || $sql_account_columns_active["column_name"] == 'postal_code' || $sql_account_columns_active["column_name"] == 'address_type' || $sql_account_columns_active["column_name"] == 'loading_dock' || $sql_account_columns_active["column_name"] == 'tax_exempt'))
							{
								$sql_query_for_search .= " AND `orders_ship_to`.`".$sql_account_columns_active["column_name"]."` LIKE ?";
								$sql_query_for_search_values[] = '%'.$build_mysql_query_data[1].'%';
							}
							//If column exits on main table search main table.
							elseif(array_key_exists($sql_account_columns_active["column_name"], $table_uses_urls))
							{
								$sql_query_for_search .= " AND `".$_SESSION['admin_table_name']."`.`".$sql_account_columns_active["column_name"]."` LIKE ?";
								$sql_query_for_search_values[] = '%'.$build_mysql_query_data[1].'%';
							}	
							//If column exits on urls table search urls table.
							elseif(array_key_exists($sql_account_columns_active["column_name"], $table_urls) && array_key_exists('urls_id', $table_uses_urls))
							{
								$sql_query_for_search .= " AND `urls`.`".$sql_account_columns_active["column_name"]."` LIKE ?";
								$sql_query_for_search_values[] = '%'.$build_mysql_query_data[1].'%';
							}
							//If custom_fields column exits on main table search custom_fields column.
							elseif(array_key_exists('custom_fields', $table_uses_urls))
							{
								$sql_query_for_search .= " AND JSON_UNQUOTE(JSON_EXTRACT(custom_fields, '$.".$sql_account_columns_active["column_name"]."')) LIKE ?";
								$sql_query_for_search_values[] = ''.$build_mysql_query_data[1].'';
							}
						}
					}
					
					//sort db column
					elseif($build_mysql_query_data[1] == 'ascend' || $build_mysql_query_data[1] == 'descend')
					{
						if($build_mysql_query_data[0] == $sql_account_columns_active["url_name"])
						{
							//When table is orders, join orders_ship_to so admin users can search orders by the customers info.
							if($_SESSION['admin_table_name'] == 'orders' && ($sql_account_columns_active["column_name"] == 'orders_id' || $sql_account_columns_active["column_name"] == 'first_name' || $sql_account_columns_active["column_name"] == 'last_name' || $sql_account_columns_active["column_name"] == 'company_name' || $sql_account_columns_active["column_name"] == 'street_address_1' || $sql_account_columns_active["column_name"] == 'street_address_2' || $sql_account_columns_active["column_name"] == 'city' || $sql_account_columns_active["column_name"] == 'country' || $sql_account_columns_active["column_name"] == 'state' || $sql_account_columns_active["column_name"] == 'postal_code' || $sql_account_columns_active["column_name"] == 'address_type' || $sql_account_columns_active["column_name"] == 'loading_dock' || $sql_account_columns_active["column_name"] == 'tax_exempt'))
							{
								$sort_column = " ORDER BY `orders_ship_to`.`".$sql_account_columns_active["column_name"]."` ".$sql_sorting_type;
							}
							//If sorting column name is total_searches, don't use the .$_SESSION['admin_table_name'] with query as this is an alias column. This is used on site search terms to count total searches made for a search term.
							elseif($sql_account_columns_active["column_name"] == 'total_searches')
							{
								$sort_column = " ORDER BY `".$sql_account_columns_active["column_name"]."` ".$sql_sorting_type;
							}
							//If sorting column name is total_404s, don't use the .$_SESSION['admin_table_name'] with query as this is an alias column. This is used on 404 errors URLs to count total 404 made for a URL.
							elseif($sql_account_columns_active["column_name"] == 'total_404s')
							{
								$sort_column = " ORDER BY `".$sql_account_columns_active["column_name"]."` ".$sql_sorting_type;
							}
							//If column exits on main table use main table name for sorting.
							elseif(array_key_exists($sql_account_columns_active["column_name"], $table_uses_urls))
							{
								$sort_column = " ORDER BY `".$_SESSION['admin_table_name']."`.`".$sql_account_columns_active["column_name"]."` ".$sql_sorting_type;
							}
							//If column exits on urls table use urls table name for sorting.
							elseif(array_key_exists($sql_account_columns_active["column_name"], $table_urls) && array_key_exists('urls_id', $table_uses_urls))
							{
								$sort_column = " ORDER BY `urls`.`".$sql_account_columns_active["column_name"]."` ".$sql_sorting_type;
							}
							//If custom_fields column exits on main table use custom_fields column name for sorting.
							elseif(array_key_exists('custom_fields', $table_uses_urls))
							{
								$sort_column = " ORDER BY JSON_UNQUOTE(JSON_EXTRACT(custom_fields, '$.".$sql_account_columns_active["column_name"]."')) ".$sql_sorting_type;
							}
						}
					}
				}
			}
		}
	}
	
	if(empty($sort_column))
	{
		if($_SESSION['admin_table_name'] == 'site_search')
		{
			$sort_column = " ORDER BY `".$_SESSION['admin_table_name']."`.`keyword` DESC";
		}
		elseif($_SESSION['admin_table_name'] == 'errors_404')
		{
			$sort_column = " ORDER BY `".$_SESSION['admin_table_name']."`.`url_404` DESC";
		}
		else
		{
			$sort_column = " ORDER BY `".$_SESSION['admin_table_name']."`.`id` DESC";
		}
	}
	
	//Set default values or get values from URL for results-per-page.
	if(isset($_GET['results-per-page']) && is_numeric($_GET['results-per-page']) && $_GET['results-per-page'] > 0 && $_GET['results-per-page'] <= 1000)
	{
		$results_per_page = trim($_GET['results-per-page'] ?? ''); $results_per_page_set = "&results-per-page=".trim($_GET['results-per-page'] ?? '');
	} 
	elseif(isset($_GET['results-per-page']) && is_numeric($_GET['results-per-page']) && $_GET['results-per-page'] > 0 && $_GET['results-per-page'] > 1000)
	{
		$results_per_page = 1000; $results_per_page_set = "&results-per-page=1000";
	}
	else
	{
		$results_per_page = "10"; $results_per_page_set = '';
	}
	
	if(isset($_GET['page-number']) && is_numeric($_GET['page-number']) && $_GET['page-number'] > 0)
	{
		$limit_offset = ($results_per_page * ($_GET['page-number'] - 1));
	}
	else
	{
		$limit_offset = 0;
	}
	
	//If assigning inventory to category, create IN () clase so it will only get inventory assigned to the product.
	$inventory_ids_assgined_to_product = '';
	if($_SESSION['admin_assigned_type'] == "assign_inventory_to_category")
	{
		$sql_products_inventory_assigned_to_category = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'products', 'WHERE `urls_id` = ? AND `site_id` = ?', [trim($_GET["sub-rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
		
		$inventory_ids_assgined_to_product = '';
		$all_inventory_ids_assigned_to_product_array = array();
		
		$all_inventory_ids_assigned_to_product = trim($sql_products_inventory_assigned_to_category['inventory_assigned'] ?? '', ',');
		
		if(!empty($all_inventory_ids_assigned_to_product))
		{
			if(strpos($all_inventory_ids_assigned_to_product, ',') !== false)
			{
				$all_inventory_ids_assigned_to_product_array = explode(',', $all_inventory_ids_assigned_to_product);
			}
			else
			{
				$all_inventory_ids_assigned_to_product_array[] = $all_inventory_ids_assigned_to_product;
			}
			
			foreach($all_inventory_ids_assigned_to_product_array as $all_inventory_id_assigned_product)
			{
				$inventory_ids_assgined_to_product_array = explode('|', $all_inventory_id_assigned_product);
				$inventory_ids_assgined_to_product .= $inventory_ids_assgined_to_product_array[1].',';
			}
			
			$inventory_ids_assgined_to_product = trim($inventory_ids_assgined_to_product ?? '', ',');
			$inventory_ids_assgined_to_product = ' AND `id` IN ('.$inventory_ids_assgined_to_product.')';
		}
		else
		{
			$inventory_ids_assgined_to_product = ' AND `id` IN (0)';
		}
	}
	
	//If table is setup as drag and drop, and not sort, clear any sorting.
	if($_SESSION['admin_sort_or_dragdrop'] == "dragdrop") { $sort_column = ''; }
	
	//If admin user is on URL 404 Errors this will create the alias in the count the total number of 404s for each URL.
	//It also adds the group by so to the query so 404 URLs are grouped togther for counting them.
	$count_404_erros_totals = '';
	$group_by_404_errros = '';
	if($_SESSION['admin_table_name'] == 'errors_404')
	{
		$count_404_erros_totals = 'MIN(id) AS id, MIN(site_id) AS site_id,  COUNT(*) AS total_404s, url_404, MIN(created_date) AS created_date';
		$group_by_404_errros = ' GROUP BY `url_404`';
	}
	
	//If admin user is on site search terms this will create the alias in the count the total number of searches for each term searched.
	//It also adds the group by so to the query so search terms are grouped togther for counting them.
	$count_search_term_totals = '';
	$group_by_keywords = '';
	if($_SESSION['admin_table_name'] == 'site_search')
	{
		$count_search_term_totals = 'MIN(id) AS id, MIN(site_id) AS site_id, COUNT(*) AS total_searches, keyword, MIN(created_date) AS created_date';
		$group_by_keywords = ' GROUP BY `keyword`';
	}
	
	//WHERE clause for core query db that is used to display results with NO site_id joined query.
	$where_query = $site_id_no_joined_query.$sub_item_id.$sub_products_assigned.$good_lead_filter.$get_hierarchy_categories_id.$sql_query_for_search.$group_by_keywords.$group_by_404_errros.$inventory_ids_assgined_to_product;
	if(!empty($where_query))
	{
		$where_query = ' WHERE '.trim($where_query, ' AND');
	}
	
	//WHERE clause for core query db that is used to display results WITH site_id joined query.
	$where_query_joined = $site_id_joined_query.$sub_item_id.$sub_products_assigned.$good_lead_filter.$get_hierarchy_categories_id.$sql_query_for_search.$group_by_keywords.$group_by_404_errros.$inventory_ids_assgined_to_product;
	if(!empty($where_query_joined))
	{
		$where_query_joined = ' WHERE '.trim($where_query_joined, ' AND');
	}
	
	//Parameters for core query db that is used to display results.
	$where_query_parameters = array_merge($site_id_value, $sub_item_id_value, $get_hierarchy_categories_id_values, $sql_query_for_search_values);
	
	if($_SESSION['admin_table_name'] == 'orders')
	{
		//When table is orders, join orders_ship_to so admin users can search orders by the customers info.
		//Total number of results
		$sql_custom_fields_count = $results->getSelectLeftJoinCountRecords(__LINE__, __FILE__, '`'.$_SESSION['admin_table_name'].'`.`id`, `orders_ship_to`.`orders_id`', $_SESSION['admin_table_name'], '`orders_ship_to` ON `'.$_SESSION['admin_table_name'].'`.`id` = `orders_ship_to`.`orders_id`', $where_query_joined, $where_query_parameters);
		
		//Core Query / Results being searched / filtered 
		$sql_custom_fields_sorted = $results->getSelectLeftJoinMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], '`orders_ship_to` ON `'.$_SESSION['admin_table_name'].'`.`id` = `orders_ship_to`.`orders_id`', $where_query_joined.$sort_column.$sub_item_order_by.' LIMIT '.$limit_offset.', '.$results_per_page, $where_query_parameters);
		$sql_custom_fields_sorted_count = count($sql_custom_fields_sorted);
	}
	elseif($_SESSION['admin_table_name'] == 'errors_404')
	{
		//Only get results becuase no urls associated with table.
		//Total number of results
		$sql_custom_fields_count = $results->getSelectCountRecords(__LINE__, __FILE__, $count_404_erros_totals, $_SESSION['admin_table_name'], $where_query, $where_query_parameters);
		
		//Core Query / Results being searched / filtered 
		$sql_custom_fields_sorted = $results->getSelectMultipleRecords(__LINE__, __FILE__, $count_404_erros_totals, $_SESSION['admin_table_name'], $where_query.$sort_column.$sub_item_order_by.' LIMIT '.$limit_offset.', '.$results_per_page, $where_query_parameters);
		$sql_custom_fields_sorted_count = count($sql_custom_fields_sorted);
	}
	elseif($_SESSION['admin_table_name'] == 'site_search')
	{
		//Only get results becuase no urls associated with table.
		//Total number of results
		$sql_custom_fields_count = $results->getSelectCountRecords(__LINE__, __FILE__, $count_search_term_totals, $_SESSION['admin_table_name'], $where_query, $where_query_parameters);
		
		//Core Query / Results being searched / filtered 
		$sql_custom_fields_sorted = $results->getSelectMultipleRecords(__LINE__, __FILE__, $count_search_term_totals, $_SESSION['admin_table_name'], $where_query.$sort_column.$sub_item_order_by.' LIMIT '.$limit_offset.', '.$results_per_page, $where_query_parameters);
		$sql_custom_fields_sorted_count = count($sql_custom_fields_sorted);
	}
	elseif(!isset($table_uses_urls['urls_id']))
	{
		//Only get results becuase no urls associated with table.
		//Total number of results
		$sql_custom_fields_count = $results->getSelectCountRecords(__LINE__, __FILE__, 'id', $_SESSION['admin_table_name'], $where_query, $where_query_parameters);
		
		//Core Query / Results being searched / filtered 
		$sql_custom_fields_sorted = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], $where_query.$sort_column.$sub_item_order_by.' LIMIT '.$limit_offset.', '.$results_per_page, $where_query_parameters);
		$sql_custom_fields_sorted_count = count($sql_custom_fields_sorted);
	}
	else
	{
		//Join urls with results becuase urls are associated with table.
		//Total number of results
		$sql_custom_fields_count = $results->getSelectLeftJoinCountRecords(__LINE__, __FILE__, '`'.$_SESSION['admin_table_name'].'`.`urls_id`, `urls`.`id`', $_SESSION['admin_table_name'], '`urls` ON `'.$_SESSION['admin_table_name'].'`.`urls_id` = `urls`.`id`', $where_query_joined, $where_query_parameters);
		
		//Core Query / Results being searched / filtered 
		$sql_custom_fields_sorted = $results->getSelectLeftJoinMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], '`urls` ON `'.$_SESSION['admin_table_name'].'`.`urls_id` = `urls`.`id`', $where_query_joined.$sort_column.$sub_item_order_by.' LIMIT '.$limit_offset.', '.$results_per_page, $where_query_parameters);
		$sql_custom_fields_sorted_count = count($sql_custom_fields_sorted);
	}
	
	//Calculate the number of pages needed to show all results.
	$number_of_result_pages = ceil($sql_custom_fields_count / $results_per_page);
	
	//Set default values or get values from URL for page-number.
	if(isset($_GET['page-number']) && is_numeric($_GET['page-number']) && $_GET['page-number'] > 0 && $_GET['page-number'] <= $number_of_result_pages)
	{
		$curent_page_number = trim($_GET['page-number'] ?? '');
		$curent_page_number_set = "&page-number=".trim($_GET['page-number'] ?? '');
	} 
	elseif(isset($_GET['page-number']) && is_numeric($_GET['page-number']) && $_GET['page-number'] > 0 && $_GET['page-number'] > $number_of_result_pages)
	{
		$curent_page_number = $number_of_result_pages;
		$curent_page_number_set = "&page-number=".$number_of_result_pages;
	}
	elseif(isset($_GET['page-number']) && !is_numeric($_GET['page-number']))
	{
		$curent_page_number = "1";
		$curent_page_number_set = "&page-number=1";
	}
	else
	{
		$curent_page_number = "1";
	}
	$prev_page_number = $curent_page_number - 1;
	$next_page_number = $curent_page_number + 1;
	
	//Off set for SQL Limit on last page
	$limit_offset_next_page = ($results_per_page * ($curent_page_number));
	
	//This set the correct id format to see if a product or inventory is already assigned to a category.
	if($_SESSION['admin_assigned_type'] == "assign_products_to_category" || $_SESSION['admin_assigned_type'] == "assign_inventory_to_category")
	{
		$sql_products_inventory_assigned_to_category = array();
		if($commerce_installed)
		{
			$sql_products_inventory_assigned_to_category = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'assignments_products', 'WHERE `parent_id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
		}
		
		$products_ids_assigned = array();
		if(!empty($sql_products_inventory_assigned_to_category))
		{
			foreach($sql_products_inventory_assigned_to_category as $sql_products_inventory_assigned)
			{
				$inventory_id_sets = 0;
				if(!empty($sql_products_inventory_assigned['inventory_id']))
				{
					$inventory_id_sets = $sql_products_inventory_assigned['inventory_id'];
				}
				
				if($sql_products_inventory_assigned['type'] == 'products')
				{
					$type_sets = 1;
				}
				elseif($sql_products_inventory_assigned['type'] == 'inventory')
				{
					$type_sets = 2;
				}
				elseif($sql_products_inventory_assigned['type'] == 'sub_products')
				{
					$type_sets = 3;
				}
				elseif($sql_products_inventory_assigned['type'] == 'lead_form')
				{
					$type_sets = 4;
				}
				
				$products_ids_assigned[] = $sql_products_inventory_assigned['child_id'].'|'.$inventory_id_sets.'|'.$type_sets;
			}
		}
	}
	
	if(!empty($sql_custom_fields_sorted)) 
	{
		$sql_custom_fields = array();
		$sql_get_product_inventoryy = array();
		
		foreach($sql_custom_fields_sorted as $sql_custom_fields_sorted_rows) 
		{
			//echo '<pre>'; print_r($sql_custom_fields_sorted_rows); echo '</pre>';
			
			//Get the URL for a "Links_To" column.
			$end_url_with = '';
			$links_to_item_url = array();
			if(isset($sql_custom_fields_sorted_rows['links_to']))
			{
				if($sql_custom_fields_sorted_rows['links_to'] == 0)
				{				
					$sql_custom_fields_sorted_rows['links_to'] = $sql_sites_in_account[$_SESSION["site_set_for_editing"]]['homepage'];
				}
				
				//Get item links_to URL
				$sql_url_data_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ? AND `site_id` = ?', [$sql_custom_fields_sorted_rows['links_to'], $_SESSION["site_set_for_editing"]]);
				
				$links_to_item_url['meta_title'] = '';
				$links_to_item_url['links_to_url'] = '';
				
				if(!empty($sql_url_data_row))
				{
					$links_to_item_url['meta_title'] = $sql_url_data_row['meta_title'];
					
					if(!empty($sql_url_data_row['url_extension']))
					{
						$end_url_with = $sql_url_data_row['url_extension'];
					}
					elseif(!empty($sites['global_url_extension']))
					{
						$end_url_with = $sites['global_url_extension'];
					}
						
					if($sql_sites_in_account[$_SESSION["site_set_for_editing"]]['homepage'] == $sql_custom_fields_sorted_rows['links_to'])
					{
						$links_to_item_url['links_to_url'] = $view_frontend_of_site.INSTALLATION_URL_PATH.'/';
					}
					elseif($sites['url_structure'] == 'Hierarchy')
					{
						$links_to_item_url['links_to_url'] = $view_frontend_of_site.INSTALLATION_URL_PATH.'/'.$sql_url_data_row['hierarchy_url'].$end_url_with;
					}
					elseif($sites['url_structure'] == 'Flat')
					{
						$links_to_item_url['links_to_url'] = $view_frontend_of_site.INSTALLATION_URL_PATH.'/'.$sql_url_data_row['flat_url'].$end_url_with;
					}
				}
			}
			
			if(empty($add_custom_values_back)) { $add_custom_values_back = array(); }
			unset($sql_custom_fields_sorted_rows[""]);
			
			$sql_custom_fields[] = $links_to_item_url + $sql_custom_fields_sorted_rows + $add_custom_values_back;
			
			unset($add_custom_values_back);
		}
	}
	
	if($_SESSION['admin_table_name'] == 'orders')
	{
		//Join orders and orders_ship_to so admin users can search orders with customers info.
		//Query db to see how many results are on the next paginated page.
		$sql_custom_fields_next_page_count = $results->getSelectLeftJoinCountRecords(__LINE__, __FILE__, '`'.$_SESSION['admin_table_name'].'`.`id`, `orders_ship_to`.`orders_id`', $_SESSION['admin_table_name'], '`orders_ship_to` ON `'.$_SESSION['admin_table_name'].'`.`id` = `orders_ship_to`.`orders_id`', $where_query_joined.' LIMIT '.$limit_offset_next_page.', '.$results_per_page, $where_query_parameters);
	}
	elseif($_SESSION['admin_table_name'] == 'errors_404')
	{
		//Only get results becuase no urls associated with table.
		//Query db to see how many results are on the next paginated page.
		$sql_custom_fields_next_page_count = $results->getSelectCountRecords(__LINE__, __FILE__, $count_404_erros_totals, $_SESSION['admin_table_name'], $where_query.' LIMIT '.$limit_offset_next_page.', '.$results_per_page, $where_query_parameters);
	}
	elseif($_SESSION['admin_table_name'] == 'site_search')
	{
		//Only get results becuase no urls associated with table.
		//Query db to see how many results are on the next paginated page.
		$sql_custom_fields_next_page_count = $results->getSelectCountRecords(__LINE__, __FILE__, $count_search_term_totals, $_SESSION['admin_table_name'], $where_query.' LIMIT '.$limit_offset_next_page.', '.$results_per_page, $where_query_parameters);
	}
	elseif(!isset($table_uses_urls['urls_id']))
	{
		//Only get results becuase no urls associated with table.
		//Query db to see how many results are on the next paginated page.
		$sql_custom_fields_next_page_count = $results->getSelectCountRecords(__LINE__, __FILE__, 'id', $_SESSION['admin_table_name'], $where_query.' LIMIT '.$limit_offset_next_page.', '.$results_per_page, $where_query_parameters);
	}
	else
	{
		//Join urls with results becuase urls are associated with table.
		$sql_custom_fields_next_page_count = $results->getSelectLeftJoinCountRecords(__LINE__, __FILE__, '`'.$_SESSION['admin_table_name'].'`.`urls_id`, `urls`.`id`', $_SESSION['admin_table_name'], '`urls` ON `'.$_SESSION['admin_table_name'].'`.`urls_id` = `urls`.`id`', $where_query_joined.' LIMIT '.$limit_offset_next_page.', '.$results_per_page, $where_query_parameters);
	}
}