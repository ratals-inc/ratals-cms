<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Admin Menus
$column_names = '`id`, `site_id`, `status`, `name`, `sub_items`, `menu_type`, `menu_locations`, `system_code`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = 'NULL,0,1,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();

$parameters[] = ['Admin - Main Menu', 95, 'Main Menu', [], 'admin_main_menu', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Categories - Sub Menu', 3, 'Sub Menu', ['website/categories/edit', 'website/categories/products-and-inventory-assigned', 'website/categories/assign-products-to-category', 'website/categories/assign-products-to-category/inventory', 'website/categories/search-filters', 'website/categories/sub-items', 'website/categories/displaying-in'], 'categories_sub_menu', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Pages - Sub Menu', 3, 'Sub Menu', ['website/pages/edit', 'website/pages/sub-items', 'website/pages/displaying-in'], 'pages_sub_menu', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Posts - Sub Menu', 4, 'Sub Menu', ['website/posts/edit', 'website/posts/sub-items', 'website/posts/comments', 'website/posts/displaying-in'], 'posts_sub_menu', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Forms - Sub Menu', 3, 'Sub Menu', ['website/forms/edit', 'website/forms/form-fields', 'website/forms/media-swatches'], 'forms_sub_menu', '{}', $first_last_name, $first_last_name];

$current_admin_pages = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_pages', '', [], 'system_code');

foreach($parameters as $key => $values)
{
	//$values[3] is menu_locations
	$menu_locations = $values[3];

	if(!empty($menu_locations) && is_array($menu_locations))
	{
		$admin_page_ids = [];

		foreach($menu_locations as $admin_page_url)
		{
			if(isset($current_admin_pages[$admin_page_url]['id']))
			{
				$admin_page_ids[] = $current_admin_pages[$admin_page_url]['id'];
			}
		}
		
		if(!empty($admin_page_ids))
		{
			$parameters[$key][3] = ','.implode(',', $admin_page_ids).',';
		}
		else
		{
			$parameters[$key][3] = '';
		}
	}
	else
	{
		$parameters[$key][3] = '';
	}
}

if(!isset($update_admin_menus))
{
	$results->getinsertMultipleRecords(__LINE__, __FILE__, 'admin_menus', $column_names, $placeholders, $parameters);
}
else
{
	$update_parameters = $parameters;
	$parameters = array();
	
	foreach($update_parameters as $param)
	{
		$parameters[] = ['name' => $param[0], 
						 'sub_items' => $param[1], 
						 'menu_type' => $param[2], 
						 'menu_locations' => $param[3], 
						 'system_code' => $param[4], 
						 'custom_fields' => $param[5], 
						 'updated_by' => $first_last_name, 
						 'created_by' => $first_last_name];
	}
}