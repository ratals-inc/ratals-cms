<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Get the admin_fields state row id so it can be set on the dynamic counties list below.
$admin_field_state_id = '';
$admin_fields_state_id = $results->getSelectSingleRecord(__LINE__, __FILE__, '`id`', 'admin_fields', 'WHERE `column_name` = ? LIMIT 1', ['state']);
if(isset($admin_fields_state_id['id']) && !empty($admin_fields_state_id['id']))
{
	$admin_field_state_id = $admin_fields_state_id['id'];
}

$column_names = '`id`, `site_id`, `name`, `sub_items`, `dynamic`, `dynamic_table_name`, `dynamic_column_id`, `dynamic_column_label`, `swap_admin_field`, `system_code`, `custom_fields`, `created_date`, `created_by`, `updated_date`, `updated_by`';
$placeholders = 'NULL,0,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();

$parameters[] = ['Accepted File Extension Types', 8, 'No', '', '', '', '', 'accepted_file_extension_types', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Accepted Image Extension Types', 10, 'No', '', '', '', '', 'accepted_image_extension_types', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Accepted Video Extension Types', 2, 'No', '', '', '', '', 'accepted_video_extension_types', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Field Lists', NULL, 'Yes', 'admin_fields_lists', 'system_code', 'name', '', 'admin_fields_lists', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Fields - All', NULL, 'Yes', 'admin_fields', 'id', 'column_name', '', 'admin_fields_all', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Fields - Data Type', 24, 'No', '', '', '', '', 'admin_fields_data_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Fields - Display As', 83, 'No', '', '', '', '', 'admin_fields_display_as', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Fields - Search As', 8, 'No', '', '', '', '', 'admin_fields_search_as', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Menu - Type', 2, 'No', '', '', '', '', 'admin_menu_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Page Level', 4, 'No', '', '', '', '', 'admin_page_level', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Pages - Assigned Type', 18, 'No', '', '', '', '', 'admin_pages_assigned_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Pages - System Codes', NULL, 'Yes', 'admin_pages', 'system_code', 'system_code', '', 'admin_pages_system_codes', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Pages - Type', 5, 'No', '', '', '', '', 'admin_pages_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Pages - URLs - ID as Value', NULL, 'Yes', 'admin_pages', 'id', 'url', '', 'admin_pages_urls_id_as_value', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Pages - URLs - URL as Value', NULL, 'Yes', 'admin_pages', 'url', 'url', '', 'admin_pages_urls_url_as_value', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Admin Users - Permissions', NULL, 'Yes', 'permissions', 'id', 'name', '', 'admin_users_permissions', '{}', $install_update_username, $install_update_username];

$parameters[] = ['All Sites in Account', NULL, 'Yes', 'sites', 'id', 'domain', '', 'all_sites_in_account', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Analytics - Traffic Sources', 3, 'No', '', '', '', '', 'analytics_traffic_sources', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Authors - Bios', NULL, 'Yes', 'authors', 'urls_id', 'author_name', '', 'authors_bios', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Display URLs as Meta Titles', NULL, 'Yes', 'urls', 'id', 'meta_title', '', 'display_urls_as_meta_titles', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Countries', 199, 'No', '', '', '', $admin_field_state_id, 'countries', '{}', $install_update_username, $install_update_username];

$parameters[] = ['CSS Grid Columns', 10, 'No', '', '', '', '', 'css_grid_columns', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Currency - Fractional Separator', 2, 'No', '', '', '', '', 'currency_fractional_separator', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Currency - Thousand Separator', 4, 'No', '', '', '', '', 'currency_thousand_separator', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Currency - Types', 26, 'No', '', '', '', '', 'currency_types', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Currency - Zeros After Fractional Separator', 6, 'No', '', '', '', '', 'currency_zeros_after_fractional_separator', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Custom Field Options - Display Color Swatch', 2, 'No', '', '', '', '', 'custom_field_options_display_color_swatch', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Custom Fields - Data Type', 8, 'No', '', '', '', '', 'custom_fields_data_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Custom Fields - Display As', 7, 'No', '', '', '', '', 'custom_fields_display_as', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Custom Fields - Field Type', 1, 'No', '', '', '', '', 'custom_fields_field_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Custom Fields - Search As', 4, 'No', '', '', '', '', 'custom_fields_search_as', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Customer Leads - Lead Status', 2, 'No', '', '', '', '', 'customer_leads_lead_status', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Database - Character Set & Collate', 1, 'No', '', '', '', '', 'database_character_set_and_collate', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Database - Table Names', NULL, 'Yes', 'database_tables', 'database_table_name', 'database_table_name', '', 'database_table_names', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Display URLs as URLs', NULL, 'Yes', 'urls', 'id', 'hierarchy_url', '', 'display_urls_as_urls', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Forms - Auto Complete', 2, 'No', '', '', '', '', 'forms_auto_complete', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Forms - Auto Complete Type', 24, 'No', '', '', '', '', 'forms_auto_complete_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Forms - Field Type', 4, 'No', '', '', '', '', 'forms_field_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Forms - Form Fields', NULL, 'Yes', 'form_fields', 'id', 'frontend_name', '', 'forms_form_fields', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Forms - JavaScript Names', 19, 'No', '', '', '', '', 'forms_javascript_names', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Forms - List of All Forms', NULL, 'Yes', 'forms', 'id', 'frontend_name', '', 'forms_list_of_all_forms', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Forms - Submit Button Type', 2, 'No', '', '', '', '', 'forms_submit_button_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Forms - Swap Fields', NULL, 'Yes', 'admin_fields_lists', 'system_code', 'name', '', 'forms_swap_fields', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Languages', 55, 'No', '', '', '', '', 'languages', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Links - Target Types', 4, 'No', '', '', '', '', 'links_target_types', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Media - Types', 4, 'No', '', '', '', '', 'media_types', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Menu Items - Parent System Codes', NULL, 'Yes', 'admin_menu_items', 'system_code', 'system_code', '', 'menu_items_parent_system_codes', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Meta Robots', 4, 'No', '', '', '', '', 'meta_robots', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Province', 14, 'No', '', '', '', '', 'ca_province', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Site Security - Email / Block IP', 2, 'No', '', '', '', '', 'site_security_email_block_ip', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Site Settings - URL Structure', 2, 'No', '', '', '', '', 'site_settings_url_structure', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Sliders - Pager Alignments', 3, 'No', '', '', '', '', 'sliders_pager_alignments', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Sort or Drag and Drop', 2, 'No', '', '', '', '', 'sort_or_drag_and_drop', '{}', $install_update_username, $install_update_username];

$parameters[] = ['State', 51, 'No', '', '', '', '', 'us_states', '{}', $install_update_username, $install_update_username];

$parameters[] = ['State / Province / Region', 0, 'No', '', '', '', '', 'state_province_region', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Status - Main', 2, 'No', '', '', '', '', 'status_main', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Sub Items Labels', 10, 'No', '', '', '', '', 'sub_items_labels', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Template Files - Assigned Type', 19, 'No', '', '', '', '', 'template_files_assigned_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Template Files - List of All Files', NULL, 'Yes', 'template_files', 'id', 'name', '', 'templates_files_list_of_all_files', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Templates - Types', 7, 'No', '', '', '', '', 'templates_types', '{}', $install_update_username, $install_update_username];

$parameters[] = ['URLs - Link Type', 2, 'No', '', '', '', '', 'urls_link_type', '{}', $install_update_username, $install_update_username];

$parameters[] = ['URLs - Redirect Tpyes', 2, 'No', '', '', '', '', 'urls_redirect_types', '{}', $install_update_username, $install_update_username];

$parameters[] = ['URLs - Status', 4, 'No', '', '', '', '', 'urls_status', '{}', $install_update_username, $install_update_username];

$parameters[] = ['Yes/No', 2, 'No', '', '', '', '', 'yes_no', '{}', $install_update_username, $install_update_username];

if(!isset($update_admin_fields_lists))
{
	$results->getinsertMultipleRecords(__LINE__, __FILE__, 'admin_fields_lists', $column_names, $placeholders, $parameters);
}
else
{
	$update_parameters = $parameters;
	$parameters = array();
	
	foreach($update_parameters as $param)
	{
		$parameters[] = ['name' => $param[0], 
						 'sub_items' => $param[1], 
						 'dynamic' => $param[2], 
						 'dynamic_table_name' => $param[3], 
						 'dynamic_column_id' => $param[4], 
						 'dynamic_column_label' => $param[5], 
						 'swap_admin_field' => $param[6], 
						 'system_code' => $param[7], 
						 'custom_fields' => $param[8], 
						 'created_by' => $install_update_username, 
						 'updated_by' => $install_update_username];
	}
}