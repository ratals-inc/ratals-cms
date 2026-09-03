<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/search-fields.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/search-fields.php');
}
else
{
	//Default column search fields
	$search_type = '';
	$search_counter ++;
	$search_query_dropdown_name = '';
	$search_query_textfield_name_start_range = '';
	$search_query_textfield_name_end_range = '';
	
	//Checking to see if its a dropdown
	if(isset($_GET['dropdown-'.$sql_account_columns_search_fileds["url_name"]]) && (!empty($_GET['dropdown-'.$sql_account_columns_search_fileds["url_name"]]) || is_numeric($_GET['dropdown-'.$sql_account_columns_search_fileds["url_name"]])))
	{
		$search_query_dropdown_name = $_GET['dropdown-'.$sql_account_columns_search_fileds["url_name"]];
	}
	
	//Checking to see if its a textfield
	$search_query_textfield_name = '';
	if(isset($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"]]) && !empty($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"]]))
	{
		$search_query_textfield_name = trim($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"]] ?? '');
	}
	
	//Checking to see if its a textfield as a price
	$search_query_price_field_name = '';
	if(isset($_GET['price-'.$sql_account_columns_search_fileds["url_name"]]) && !empty($_GET['price-'.$sql_account_columns_search_fileds["url_name"]]))
	{
		$search_query_price_field_name = trim($_GET['price-'.$sql_account_columns_search_fileds["url_name"]] ?? '');
	}
	
	//Checking to see if its a textfield with a start date range
	if(isset($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"].'-start-range']) && !empty($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"].'-start-range']))
	{
		$search_query_textfield_name_start_range = trim($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"].'-start-range'] ?? '');
	}
	
	//Checking to see if its a textfield with a end date range
	if(isset($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"].'-end-range']) && !empty($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"].'-end-range']))
	{
		$search_query_textfield_name_end_range = trim($_GET['textfield-'.$sql_account_columns_search_fileds["url_name"].'-end-range'] ?? '');
	}
	
	if($sql_account_columns_search_fileds["default_or_custom"] == "default")
	{
		if($sql_account_columns_search_fileds["search_as"] == "template") 
		{
			$search_type = '<select name="dropdown-'.$sql_account_columns_search_fileds["url_name"].'">';
			$search_type .= '<option value=""></option>';
			
			$sql = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'template_files', 'ORDER BY `name` ASC', []);
			
			if(!empty($sql)) 
			{
				foreach($sql as $options_list)
				{
					$search_type .= '<option value="'.htmlspecialchars($options_list['id'] ?? '').'"'.(($search_query_dropdown_name == $options_list['id']) ? ' selected' : '').'>'.htmlspecialchars($options_list['filename'] ?? '').'</option>';
				}
			}
			$search_type .= '</select>';
		}
		elseif($sql_account_columns_search_fileds['search_as'] == "dropdownId" || $sql_account_columns_search_fileds['search_as'] == "dropdownValue" ) 
		{
			if($_SESSION['admin_table_name'] == "admin_fields" && $sql_account_columns_search_fileds['column_name'] == 'display_as')
			{
				$list = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', ['admin_fields_display_as']);
				$sql_account_columns_search_fileds["admin_fields_lists_system_code"] = 'admin_fields_display_as';
			}
			elseif($_SESSION['admin_table_name'] == "admin_fields" && $sql_account_columns_search_fileds['column_name'] == 'search_as')
			{
				$list = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', ['admin_fields_search_as']);
				$sql_account_columns_search_fileds["admin_fields_lists_system_code"] = 'admin_fields_search_as';
			}
			elseif($_SESSION['admin_table_name'] == "admin_fields" && $sql_account_columns_search_fileds['column_name'] == 'data_type')
			{
				$list = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', ['admin_fields_data_type']);
				$sql_account_columns_search_fileds["admin_fields_lists_system_code"] = 'admin_fields_data_type';
			}
			else
			{
				$list = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', [$sql_account_columns_search_fileds["admin_fields_lists_system_code"]]);
			}
			
			if(!empty($list) && $list['dynamic'] == 'No')
			{
				if(!empty($sql_account_columns_search_fileds["admin_fields_lists_system_code"])) 
				{			
					$search_type = '<select name="dropdown-'.$sql_account_columns_search_fileds["url_name"].'">';
					$search_type .= '<option value=""></option>';
					
					$sql = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$sql_account_columns_search_fileds["admin_fields_lists_system_code"]]);
					
					if(!empty($sql)) 
					{
						foreach($sql as $options_list)
						{
							if(!empty($options_list["value"]) && $sql_account_columns_search_fileds["search_as"] == "dropdownValue")
							{
								$value_or_id = $options_list["value"];
							}
							else
							{
								$value_or_id = $options_list["id"];
							}
							
							
							$search_type .= '<option value="'.htmlspecialchars($value_or_id ?? '').'"'.(($search_query_dropdown_name == $value_or_id) ? ' selected' : '').'>'.htmlspecialchars($options_list["label"] ?? '').'</option>';
						}
					}
					$search_type .= '</select>';
				}
			}
			elseif(!empty($list) && $list['dynamic'] == 'Yes')
			{
				$search_type = '<select name="dropdown-'.$sql_account_columns_search_fileds["url_name"].'">';
				$search_type .= '<option value=""></option>';
				
				$sql = $results->getSelectMultipleRecords(__LINE__, __FILE__, 'DISTINCT `'.$list['dynamic_column_id'].'`,`'.$list['dynamic_column_label'].'`', $list['dynamic_table_name'], 'ORDER BY `'.$list['dynamic_column_label'].'` ASC', []);
				
				if(!empty($sql)) 
				{
					foreach($sql as $options_list)
					{
						$search_type .= '<option value="'.htmlspecialchars($options_list[$list['dynamic_column_id']] ?? '').'"'.(($search_query_dropdown_name == $options_list[$list['dynamic_column_id']]) ? ' selected' : '').'>'.htmlspecialchars($options_list[$list['dynamic_column_label']] ?? '').'</option>';
					}
				}
				$search_type .= '</select>';
			}
		}
		elseif($sql_account_columns_search_fileds["search_as"] == "tableName")
		{
			$search_type = '<select name="dropdown-'.$sql_account_columns_search_fileds["url_name"].'">';
			$search_type .= '<option value=""></option>';
			
			//Get all the table names of the site.
			$all_table_names = $_SESSION['results_schema']->getSchemaSelectMultipleRecords(__LINE__, __FILE__, '*', 'tables', 'WHERE `table_schema` = ? ORDER BY `table_name` ASC', [$_SESSION['site_db_name']]);
			
			if(!empty($all_table_names)) 
			{
				foreach($all_table_names as $all_table_name)
				{
					$search_type .= '<option value="'.htmlspecialchars($all_table_name['table_name'] ?? '').'"'.(($search_query_dropdown_name == $all_table_name['table_name']) ? ' selected' : '').'>'.htmlspecialchars($all_table_name['table_name'] ?? '').'</option>';
				}
			}
			$search_type .= '</select>';
		}
		elseif($sql_account_columns_search_fileds["search_as"] == "linksTo") 
		{
			$list = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', [$sql_account_columns_search_fileds["admin_fields_lists_system_code"]]);
			
			if(!empty($list) && $list['dynamic'] == 'Yes')
			{
				$search_type = '<select name="dropdown-'.$sql_account_columns_search_fileds["url_name"].'">';
				$search_type .= '<option value=""></option>';
				
				$select_homeage = '';
				if(is_numeric($search_query_dropdown_name) && $search_query_dropdown_name == '0') { $select_homeage = ' selected'; }
				$search_type .= '<option value="0"'.$select_homeage.'>Always Link To Homepage</option>';
				
				$sql = $results->getSelectMultipleRecords(__LINE__, __FILE__, 'DISTINCT `'.$list['dynamic_column_id'].'`,`'.$list['dynamic_column_label'].'`', $list['dynamic_table_name'], 'ORDER BY `'.$list['dynamic_column_label'].'` ASC', []);
				
				if(!empty($sql)) 
				{
					foreach($sql as $options_list)
					{
						$search_type .= '<option value="'.htmlspecialchars($options_list[$list['dynamic_column_id']] ?? '').'"'.(($search_query_dropdown_name == $options_list[$list['dynamic_column_id']]) ? ' selected' : '').'>'.htmlspecialchars($options_list[$list['dynamic_column_label']] ?? '').'</option>';
					}
				}
				$search_type .= '</select>';
			}
		}
		elseif($sql_account_columns_search_fileds['search_as'] == "dateRange")
		{
			$search_type = '
			<script nonce="'.NONCE.'">
			//Start show datepicker
			$(function() 
			{
				$( "#datepicker_'.$search_counter.'_from" ).datepicker({dateFormat: "yy-mm-dd"});
				$( "#datepicker_'.$search_counter.'_to" ).datepicker({dateFormat: "yy-mm-dd"});
			});
			//End show datepicker
			</script>
			<input type="text" name="textfield-'.$sql_account_columns_search_fileds["url_name"].'-start-range" id="datepicker_'.$search_counter.'_from" value="'.$search_query_textfield_name_start_range.'" placeholder="From" autocomplete="off">
			<input type="text" name="textfield-'.$sql_account_columns_search_fileds["url_name"].'-end-range" id="datepicker_'.$search_counter.'_to" value="'.$search_query_textfield_name_end_range.'"  placeholder="To" autocomplete="off">
			';
		}
		elseif($sql_account_columns_search_fileds['search_as'] == "price")
		{ 
			$search_type = '
			<input type="text" name="price-'.$sql_account_columns_search_fileds["url_name"].'" value="'.$search_query_price_field_name.'" placeholder="'.$sql_account_columns_search_fileds["placeholder"].'">
		'; 
		}
		elseif($sql_account_columns_search_fileds['search_as'] == "textfield")
		{ 
			$search_type = '
			<input type="text" name="textfield-'.$sql_account_columns_search_fileds["url_name"].'" value="'.$search_query_textfield_name.'" placeholder="'.$sql_account_columns_search_fileds["placeholder"].'">
		'; 
		}
	}
	
	//Custom column search fields
	elseif($sql_account_columns_search_fileds["default_or_custom"] == "custom")
	{
		if($sql_account_columns_search_fileds['cf_search_as'] == "dropdownId") 
		{ 
			$custom_fields_options = '';
			
			$sql_swatch_values = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields_options', 'WHERE `custom_fields_id` = ? ORDER BY `id` ASC', [$sql_account_columns_search_fileds["id"]]);
			
			if(!empty($sql_swatch_values)) 
			{
				foreach($sql_swatch_values as $sql_swatch_values_row)
				{
					//Decode attribute frontend and admin names
					$option_data = array();
					if(isset($sql_swatch_values_row['option_data']) && !empty($sql_swatch_values_row['option_data']))
					{
						$option_data = JSON_DECODE($sql_swatch_values_row['option_data'] ?? '', true);
					}
					
					$sql_swatch_values_row['label'] = 'Not Set for ('.$_SESSION['admin_language'].')';
					if(isset($option_data[$_SESSION['admin_language']]['label']) && !empty($option_data[$_SESSION['admin_language']]['label']))
					{
						$sql_swatch_values_row['label'] = $option_data[$_SESSION['admin_language']]['label'];
					}
					
					$custom_fields_options .= '<option value="'.htmlspecialchars($sql_swatch_values_row["id"] ?? '').'"'.(($search_query_dropdown_name == $sql_swatch_values_row["id"]) ? ' selected' : '').'>'.htmlspecialchars($sql_swatch_values_row["label"] ?? '').'</option>';
				}
			}
			
			$search_type = '
			<select name="dropdown-'.$sql_account_columns_search_fileds["url_name"].'">
			<option value=""></option>
			'.$custom_fields_options.'
			</select>'; 
		}
		elseif($sql_account_columns_search_fileds['cf_search_as'] == "dateRange")
		{
			$search_type = '
			<script nonce="'.NONCE.'">
			//Start show datepicker
			$(function() 
			{
				$( "#datepicker_'.$search_counter.'_from" ).datepicker({dateFormat: "yy-mm-dd"});
				$( "#datepicker_'.$search_counter.'_to" ).datepicker({dateFormat: "yy-mm-dd"});
			});
			//End show datepicker
			</script>
			<input type="text" name="textfield-'.$sql_account_columns_search_fileds["url_name"].'-start-range" id="datepicker_'.$search_counter.'_from" value="'.$search_query_textfield_name_start_range.'"  placeholder="From" autocomplete="off">
			<input type="text" name="textfield-'.$sql_account_columns_search_fileds["url_name"].'-end-range" id="datepicker_'.$search_counter.'_to" value="'.$search_query_textfield_name_end_range.'"  placeholder="To" autocomplete="off">
			';
		}
		elseif($sql_account_columns_search_fileds['cf_search_as'] == "price")
		{ 
			$search_type = '
			<input type="text" name="price-'.$sql_account_columns_search_fileds["url_name"].'" value="'.$search_query_price_field_name.'" placeholder="'.$sql_account_columns_search_fileds["placeholder"].'">
		'; 
		}
		elseif($sql_account_columns_search_fileds['cf_search_as'] == "textfield") 
		{ 
			$search_type = '
			<input type="text" name="textfield-'.$sql_account_columns_search_fileds["url_name"].'" value="'.$search_query_textfield_name.'" placeholder="'.$sql_account_columns_search_fileds["placeholder"].'">
			'; 
		}
	}
}