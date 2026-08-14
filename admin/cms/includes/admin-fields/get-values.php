<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/get-values.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/get-values.php');
}
else
{
	//This gets the site data out of the table of `sites` so we can set a site id that is being edited.
	if($_SESSION['admin_one_record'] == 'Yes' && $_SESSION['admin_table_name'] == 'sites')
	{
		$current_values[$_SESSION['admin_table_name']] = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `id` = ?', [$_SESSION["site_set_for_editing"]]);
	}
	//admin_one_record tells us that this will use a one page form. admin_site_id_global tells us that there will be one record for all site so use site_id == 0. $_SESSION['admin_sub_page'] == 'No' tells us that this is not a record of some other parent page. 
	elseif($_SESSION['admin_one_record'] == 'Yes' && $_SESSION['admin_site_id_global'] == 'Yes' && $_SESSION['admin_sub_page'] == 'No')
	{
	$current_values[$_SESSION['admin_table_name']] = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `site_id` = ?', [0]);
	}
	//admin_one_record tells us that there is one row in the database for the whole site. $_SESSION['admin_sub_page'] == 'No' tells us that this is not a record of some other parent page. 
	elseif($_SESSION['admin_one_record'] == 'Yes' && $_SESSION['admin_sub_page'] == 'No')
	{
		$current_values[$_SESSION['admin_table_name']] = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `site_id` = ?', [$_SESSION["site_set_for_editing"]]);
	}
	//If table has urls_id get record row by urls_id.
	elseif($admin_fields_has_url == 'Yes')
	{
		//Get main table data by urls_id.
		$current_values[$_SESSION['admin_table_name']] = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `urls_id` = ?', [trim($_GET["rid"] ?? '')]);
		
		//Get urls data.
		$current_values['urls'] = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ?', [$current_values[$_SESSION['admin_table_name']]['urls_id']]);
	}
	//Get the id from the url for the row being requested in database.
	else
	{
		//Ship To, Bill To, Etc., on orders uses this.
		if($_SESSION['admin_sub_page'] == 'Yes' && !empty($_SESSION['admin_table_link_column']) && !empty($_SESSION['admin_parent_table_name']) && $_SESSION['admin_one_record'] == 'Yes')
		{
			$admin_sub_page = $_SESSION['admin_table_link_column'];
		}
		else
		{
			$admin_sub_page = 'id';
		}
		
		$current_values[$_SESSION['admin_table_name']] = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$admin_sub_page.'` = ?', [trim($_GET["rid"] ?? '')]);
		
		//Get custom_fields_options if main table being requested is custom_fields.
		if($_SESSION['admin_table_name'] == 'custom_fields')
		{
			$admin_field_values = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields_options', 'WHERE `custom_fields_id` = ?', [trim($_GET["rid"] ?? '')]);
			
			foreach($admin_field_values as $admin_field_value)
			{
				$option_data = JSON_DECODE($admin_field_value['option_data'] ?? '', true);
				
				$admin_field_value['label'] = $option_data[$_SESSION['admin_language']]['label'] ?? '';
				$admin_field_value['value'] = $option_data[$_SESSION['admin_language']]['value'] ?? '';
				
				$current_values['custom_fields_options'][] = $admin_field_value;
			}
		}
	}
	//echo '<pre>'; print_r($current_values); echo '</pre>';
}