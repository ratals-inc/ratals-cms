<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//If admin_page_system_code is empty set admin_pages_id with 0. If admin_page_system_code is not empty, set admin_pages_id with admin_pages > admin_page_system_code row id.
//If parent_name is empty set parent_id with 0. If parent_name is not empty, set parent_id with admin_menus_items id.

//Admin Menu Items
$column_names = '`id`, `site_id`, `status`, `name`, `sub_items`, `admin_menus_id`, `admin_pages_id`, `admin_page_system_code`, `parent_id`, `admin_menu_items_parent_code`, `system_code`, `link_parameters`, `link_target`, `sort`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = 'NULL,0,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';
$parameter = array();

//START MAIN MENU

//Dashboard
$parameter['dashboard'] = ['1', 'Dashboard', '0', 'admin_main_menu', '0', 'dashboard', '0', '', 'dashboard', '', '', '1', '{}', $first_last_name, $first_last_name];

//Customers
$parameter['customers'] = ['1', 'Customers', '1', 'admin_main_menu', '0', '', '0', '', 'customers', '', '', '3', '{}', $first_last_name, $first_last_name];

$parameter['customers_all_form_leads'] = ['1', 'Form Leads', '0', 'admin_main_menu', '0', 'customers/leads', '0', 'customers', 'customers_all_form_leads', '', '', '6', '{}', $first_last_name, $first_last_name];

//Website
$parameter['website'] = ['1', 'Website', '55', 'admin_main_menu', '0', '', '0', '', 'website', '', '', '4', '{}', $first_last_name, $first_last_name];

//Website > Categories
$parameter['website_categories'] = ['1', 'Categories', '3', 'admin_main_menu', '0', '', '0', 'website', 'website_categories', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_all_categories'] = ['1', 'Categories', '0', 'admin_main_menu', '0', 'website/categories', '0', 'website_categories', 'website_all_categories', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_category'] = ['1', 'Add Category', '0', 'admin_main_menu', '0', 'website/categories/add', '0', 'website_categories', 'website_add_category', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['website_unassigned_categories'] = ['1', 'Unassigned Categories', '0', 'admin_main_menu', '0', 'website/categories/unassigned', '0', 'website_categories', 'website_unassigned_categories', '', '', '3', '{}', $first_last_name, $first_last_name];

//Website > Pages
$parameter['website_pages'] = ['1', 'Pages', '3', 'admin_main_menu', '0', '', '0', 'website', 'website_pages', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['website_all_pages'] = ['1', 'Pages', '0', 'admin_main_menu', '0', 'website/pages', '0', 'website_pages', 'website_all_pages', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_page'] = ['1', 'Add Page', '0', 'admin_main_menu', '0', 'website/pages/add', '0', 'website_pages', 'website_add_page', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['website_unassigned_pages'] = ['1', 'Unassigned Pages', '0', 'admin_main_menu', '0', 'website/pages/unassigned', '0', 'website_pages', 'website_unassigned_pages', '', '', '3', '{}', $first_last_name, $first_last_name];

//Website > Posts
$parameter['website_posts'] = ['1', 'Posts', '3', 'admin_main_menu', '0', '', '0', 'website', 'website_posts', '', '', '3', '{}', $first_last_name, $first_last_name];

$parameter['website_all_posts'] = ['1', 'Posts', '0', 'admin_main_menu', '0', 'website/posts', '0', 'website_posts', 'website_all_posts', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_post'] = ['1', 'Add Post', '0', 'admin_main_menu', '0', 'website/posts/add', '0', 'website_posts', 'website_add_post', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['website_unassigned_posts'] = ['1', 'Unassigned Posts', '0', 'admin_main_menu', '0', 'website/posts/unassigned', '0', 'website_posts', 'website_unassigned_posts', '', '', '3', '{}', $first_last_name, $first_last_name];

//Website > Author Bios
$parameter['website_author_bios'] = ['1', 'Author Bios', '2', 'admin_main_menu', '0', '', '0', 'website', 'website_author_bios', '', '', '5', '{}', $first_last_name, $first_last_name];

$parameter['website_all_author_bios'] = ['1', 'Author Bios', '0', 'admin_main_menu', '0', 'website/authors', '0', 'website_author_bios', 'website_all_author_bios', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_author_bio'] = ['1', 'Add Author Bio', '0', 'admin_main_menu', '0', 'website/authors/add', '0', 'website_author_bios', 'website_add_author_bio', '', '', '2', '{}', $first_last_name, $first_last_name];

//Website > URLs
$parameter['website_urls'] = ['1', 'URLs', '1', 'admin_main_menu', '0', '', '0', 'website', 'website_urls', '', '', '6', '{}', $first_last_name, $first_last_name];

$parameter['website_all_urls'] = ['1', 'URLs', '0', 'admin_main_menu', '0', 'website/urls', '0', 'website_urls', 'website_all_urls', '', '', '1', '{}', $first_last_name, $first_last_name];

//Website > 404 URL Errors
$parameter['website_404_url_errors'] = ['1', '404 URL Errors', '1', 'admin_main_menu', '0', '', '0', 'website', 'website_404_url_errors', '', '', '7', '{}', $first_last_name, $first_last_name];

$parameter['website_all_404_url_errors'] = ['1', '404 URL Errors', '0', 'admin_main_menu', '0', 'website/404-urls', '0', 'website_404_url_errors', 'website_all_404_url_errors', '', '', '1', '{}', $first_last_name, $first_last_name];

//Website > Forms
$parameter['website_forms'] = ['1', 'Forms', '4', 'admin_main_menu', '0', '', '0', 'website', 'website_forms', '', '', '8', '{}', $first_last_name, $first_last_name];

$parameter['website_all_forms'] = ['1', 'Forms', '0', 'admin_main_menu', '0', 'website/forms', '0', 'website_forms', 'website_all_forms', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_form'] = ['1', 'Add Form', '0', 'admin_main_menu', '0', 'website/forms/add', '0', 'website_forms', 'website_add_form', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['website_all_form_fields'] = ['1', 'Form Fields', '0', 'admin_main_menu', '0', 'website/form-fields', '0', 'website_forms', 'website_all_form_fields', '', '', '3', '{}', $first_last_name, $first_last_name];

$parameter['website_add_form_field'] = ['1', 'Add Form Field', '0', 'admin_main_menu', '0', 'website/form-fields/add', '0', 'website_forms', 'website_add_form_field', '', '', '4', '{}', $first_last_name, $first_last_name];

//Website > Menus
$parameter['website_menus'] = ['1', 'Menus', '2', 'admin_main_menu', '0', '', '0', 'website', 'website_menus', '', '', '9', '{}', $first_last_name, $first_last_name];

$parameter['website_all_menus'] = ['1', 'Menus', '0', 'admin_main_menu', '0', 'website/menus', '0', 'website_menus', 'website_all_menus', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_menu'] = ['1', 'Add Menu', '0', 'admin_main_menu', '0', 'website/menus/add', '0', 'website_menus', 'website_add_menu', '', '', '2', '{}', $first_last_name, $first_last_name];

//Website > Sliders
$parameter['website_sliders'] = ['1', 'Sliders', '2', 'admin_main_menu', '0', '', '0', 'website', 'website_sliders', '', '', '10', '{}', $first_last_name, $first_last_name];

$parameter['website_all_silders'] = ['1', 'Silders', '0', 'admin_main_menu', '0', 'website/sliders', '0', 'website_sliders', 'website_all_silders', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_slider'] = ['1', 'Add Slider', '0', 'admin_main_menu', '0', 'website/sliders/add', '0', 'website_sliders', 'website_add_slider', '', '', '2', '{}', $first_last_name, $first_last_name];

//Website > Templates
$parameter['website_templates'] = ['1', 'Templates', '2', 'admin_main_menu', '0', '', '0', 'website', 'website_templates', '', '', '11', '{}', $first_last_name, $first_last_name];

$parameter['website_all_templates'] = ['1', 'Templates', '0', 'admin_main_menu', '0', 'website/templates', '0', 'website_templates', 'website_all_templates', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_template'] = ['1', 'Add Template', '0', 'admin_main_menu', '0', 'website/templates/add', '0', 'website_templates', 'website_add_template', '', '', '2', '{}', $first_last_name, $first_last_name];

//Website > Interactions
$parameter['website_interactions'] = ['1', 'Interactions', '4', 'admin_main_menu', '0', '', '0', 'website', 'website_interactions', '', '', '12', '{}', $first_last_name, $first_last_name];

$parameter['website_all_posts_comments'] = ['1', 'Posts Comments', '0', 'admin_main_menu', '0', 'website/interactions/posts-comments', '0', 'website_interactions', 'website_all_posts_comments', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_all_site_search_terms'] = ['1', 'Site Search Terms', '0', 'admin_main_menu', '0', 'website/interactions/site-searches', '0', 'website_interactions', 'website_all_site_search_terms', '', '', '2', '{}', $first_last_name, $first_last_name];

//Website > URL Redirects
$parameter['website_url_redirects'] = ['1', 'URL Redirects', '2', 'admin_main_menu', '0', '', '0', 'website', 'website_url_redirects', '', '', '15', '{}', $first_last_name, $first_last_name];

$parameter['website_all_url_redirects'] = ['1', 'URL Redirects', '0', 'admin_main_menu', '0', 'website/redirects', '0', 'website_url_redirects', 'website_all_url_redirects', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_add_url_redirect'] = ['1', 'Add URL Redirect', '0', 'admin_main_menu', '0', 'website/redirects/add', '0', 'website_url_redirects', 'website_add_url_redirect', '', '', '2', '{}', $first_last_name, $first_last_name];

//Website > Site Settings
$parameter['website_site_settings'] = ['1', 'Site Settings', '4', 'admin_main_menu', '0', '', '0', 'website', 'website_site_settings', '', '', '16', '{}', $first_last_name, $first_last_name];

$parameter['website_contact_information'] = ['1', 'Contact Information', '0', 'admin_main_menu', '0', 'website/site-settings/contact-information', '0', 'website_site_settings', 'website_contact_information', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['website_general_settings'] = ['1', 'General Settings', '0', 'admin_main_menu', '0', 'website/site-settings/general-settings', '0', 'website_site_settings', 'website_general_settings', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['website_search_engines'] = ['1', 'Search Engines', '0', 'admin_main_menu', '0', 'website/site-settings/search-engines', '0', 'website_site_settings', 'website_search_engines', '', '', '3', '{}', $first_last_name, $first_last_name];

$parameter['website_url_settings'] = ['1', 'URL Settings', '0', 'admin_main_menu', '0', 'website/site-settings/url-settings', '0', 'website_site_settings', 'website_url_settings', '', '', '4', '{}', $first_last_name, $first_last_name];

//Media Library
$parameter['media_library'] = ['1', 'Media Library', '3', 'admin_main_menu', '0', '', '0', '', 'media_library', '', '', '7', '{}', $first_last_name, $first_last_name];

$parameter['media_library_all_media'] = ['1', 'Media', '0', 'admin_main_menu', '0', 'media', '0', 'media_library', 'media_library_all_media', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['media_library_add_media'] = ['1', 'Add Media', '0', 'admin_main_menu', '0', 'media/add', '0', 'media_library', 'media_library_add_media', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['media_library_add_video_embed'] = ['1', 'Add Video Embed', '0', 'admin_main_menu', '0', 'media/add-video-embed', '0', 'media_library', 'media_library_add_video_embed', '', '', '3', '{}', $first_last_name, $first_last_name];

//Custom Fields
$parameter['custom_fields'] = ['1', 'Custom Fields', '3', 'admin_main_menu', '0', '', '0', '', 'custom_fields', '', '', '8', '{}', $first_last_name, $first_last_name];

$parameter['custom_fields_all_custom_fields'] = ['1', 'Custom Fields', '0', 'admin_main_menu', '0', 'custom-fields', '0', 'custom_fields', 'custom_fields_all_custom_fields', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['custom_fields_add_custom_field'] = ['1', 'Add Custom Field', '0', 'admin_main_menu', '0', 'custom-fields/add', '0', 'custom_fields', 'custom_fields_add_custom_field', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['custom_fields_global_custom_fields'] = ['1', 'Global Custom Fields', '0', 'admin_main_menu', '0', 'custom-fields/global-custom-fields', '0', 'custom_fields', 'custom_fields_global_custom_fields', '', '', '3', '{}', $first_last_name, $first_last_name];

//Security
$parameter['security'] = ['1', 'Security', '4', 'admin_main_menu', '0', '', '0', '', 'security', '', '', '9', '{}', $first_last_name, $first_last_name];

$parameter['security_site_security'] = ['1', 'Site Security', '0', 'admin_main_menu', '0', 'security/site-security', '0', 'security', 'security_site_security', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['security_blocking_spam'] = ['1', 'Blocking Spam', '0', 'admin_main_menu', '0', 'security/blocking-spam', '0', 'security', 'security_blocking_spam', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['security_failed_logins'] = ['1', 'Failed Logins', '0', 'admin_main_menu', '0', 'security/failed-logins', '0', 'security', 'security_failed_logins', '', '', '4', '{}', $first_last_name, $first_last_name];

//Admin
$parameter['admin'] = ['1', 'Admin', '29', 'admin_main_menu', '0', '', '0', '', 'admin', '', '', '10', '{}', $first_last_name, $first_last_name];

//Admin > Admin Field Lists
$parameter['admin_admin_field_lists'] = ['1', 'Admin Field Lists', '2', 'admin_main_menu', '0', '', '', 'admin', 'admin_admin_field_lists', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_admin_field_lists'] = ['1', 'Admin Field Lists', '0', 'admin_main_menu', '0', 'admin/field-lists', '0', 'admin_admin_field_lists', 'admin_all_admin_field_lists', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_add_admin_field_list'] = ['1', 'Add Admin Field List', '0', 'admin_main_menu', '0', 'admin/field-lists/add', '0', 'admin_admin_field_lists', 'admin_add_admin_field_list', '', '', '2', '{}', $first_last_name, $first_last_name];

//Admin > Admin Field Sections
$parameter['admin_admin_field_sections'] = ['1', 'Admin Field Sections', '2', 'admin_main_menu', '0', '', '', 'admin', 'admin_admin_field_sections', '', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_admin_field_sections'] = ['1', 'Admin Field Sections', '0', 'admin_main_menu', '0', 'admin/field-sections', '0', 'admin_admin_field_sections', 'admin_all_admin_field_sections', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_add_admin_field_section'] = ['1', 'Add Admin Field Section', '0', 'admin_main_menu', '0', 'admin/field-sections/add', '0', 'admin_admin_field_sections', 'admin_add_admin_field_section', '', '', '2', '{}', $first_last_name, $first_last_name];

//Admin > Admin Fields
$parameter['admin_admin_fields'] = ['1', 'Admin Fields', '2', 'admin_main_menu', '0', '', '', 'admin', 'admin_admin_fields', '', '', '3', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_admin_fields'] = ['1', 'Admin Fields', '0', 'admin_main_menu', '0', 'admin/fields', '0', 'admin_admin_fields', 'admin_all_admin_fields', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_add_admin_field'] = ['1', 'Add Admin Field', '0', 'admin_main_menu', '0', 'admin/fields/add', '0', 'admin_admin_fields', 'admin_add_admin_field', '', '', '2', '{}', $first_last_name, $first_last_name];

//Admin > Admin Pages
$parameter['admin_admin_pages'] = ['1', 'Admin Pages', '2', 'admin_main_menu', '0', '', '', 'admin', 'admin_admin_pages', '', '', '4', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_admin_pages'] = ['1', 'Admin Pages', '0', 'admin_main_menu', '0', 'admin/pages', '0', 'admin_admin_pages', 'admin_all_admin_pages', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_add_admin_page'] = ['1', 'Add Admin Page', '0', 'admin_main_menu', '0', 'admin/pages/add', '0', 'admin_admin_pages', 'admin_add_admin_page', '', '', '2', '{}', $first_last_name, $first_last_name];

//Admin > Admin Menus
$parameter['admin_admin_menus'] = ['1', 'Admin Menus', '2', 'admin_main_menu', '0', '', '', 'admin', 'admin_admin_menus', '', '', '5', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_admin_menus'] = ['1', 'Admin Menus', '0', 'admin_main_menu', '0', 'admin/menus', '0', 'admin_admin_menus', 'admin_all_admin_menus', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_add_admin_menu'] = ['1', 'Add Admin Menu', '0', 'admin_main_menu', '0', 'admin/menus/add', '0', 'admin_admin_menus', 'admin_add_admin_menu', '', '', '2', '{}', $first_last_name, $first_last_name];

//Admin > Database Tables
$parameter['admin_database_tables'] = ['1', 'Database Tables', '2', 'admin_main_menu', '0', '', '0', 'admin', 'admin_database_tables', '', '', '7', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_database_tables'] = ['1', 'Database Tables', '0', 'admin_main_menu', '0', 'admin/database-tables', '0', 'admin_database_tables', 'admin_all_database_tables', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_add_database_table'] = ['1', 'Add Database Table', '0', 'admin_main_menu', '0', 'admin/database-tables/add', '0', 'admin_database_tables', 'admin_add_database_table', '', '', '2', '{}', $first_last_name, $first_last_name];

//Admin > Import/Export Data
$parameter['admin_import_export_data'] = ['1', 'Import/Export Data', '2', 'admin_main_menu', '0', '', '0', 'admin', 'admin_import_export_data', '', '', '8', '{}', $first_last_name, $first_last_name];

$parameter['admin_import_data'] = ['1', 'Import Data', '0', 'admin_main_menu', '0', 'admin/import-data', '0', 'admin_import_export_data', 'admin_import_data', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_export_data'] = ['1', 'Export Data', '0', 'admin_main_menu', '0', 'admin/export-data', '0', 'admin_import_export_data', 'admin_export_data', '', '', '2', '{}', $first_last_name, $first_last_name];

//Admin > Notices
$parameter['admin_notices'] = ['1', 'Notices', '1', 'admin_main_menu', '0', '', '0', 'admin', 'admin_notices', '', '', '9', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_notices'] = ['1', 'Notices', '0', 'admin_main_menu', '0', 'admin/notices', '0', 'admin_notices', 'admin_all_notices', 'No', '', '1', '{}', $first_last_name, $first_last_name];

//Admin > User Permissions
$parameter['admin_user_permissions'] = ['1', 'User Permissions', '2', 'admin_main_menu', '0', '', '0', 'admin', 'admin_user_permissions', '', '', '10', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_user_permissions'] = ['1', 'User Permissions', '0', 'admin_main_menu', '0', 'admin/user-permissions', '0', 'admin_user_permissions', 'admin_all_user_permissions', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_add_user_permission'] = ['1', 'Add User Permission', '0', 'admin_main_menu', '0', 'admin/user-permissions/add', '0', 'admin_user_permissions', 'admin_add_user_permission', '', '', '2', '{}', $first_last_name, $first_last_name];

//Admin > Admin Users
$parameter['admin_admin_users'] = ['1', 'Admin Users', '2', 'admin_main_menu', '0', '', '0', 'admin', 'admin_admin_users', '', '', '11', '{}', $first_last_name, $first_last_name];

$parameter['admin_all_admin_users'] = ['1', 'Admin Users', '0', 'admin_main_menu', '0', 'admin/users', '0', 'admin_admin_users', 'admin_all_admin_users', '', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['admin_add_admin_user'] = ['1', 'Add Admin User', '0', 'admin_main_menu', '0', 'admin/users/add', '0', 'admin_admin_users', 'admin_add_admin_user', '', '', '2', '{}', $first_last_name, $first_last_name];

//Add Site
$parameter['add_site'] = ['1', 'Add Site', '0', 'admin_main_menu', '0', 'add-a-site', '0', '', 'add_site', '', '', '11', '{}', $first_last_name, $first_last_name];

//License
$parameter['license'] = ['1', 'License', '0', 'admin_main_menu', '0', 'license', '0', '', 'license', '', '', '12', '{}', $first_last_name, $first_last_name];

//Logout
$parameter['logout'] = ['1', 'Logout', '0', 'admin_main_menu', '0', 'logout.php', '0', '', 'logout', '', '', '13', '{}', $first_last_name, $first_last_name];

//START SUB MENUS

//Categories - Sub Menu
$parameter['sub_categories_edit_category'] = ['1', 'Edit Category', '0', 'categories_sub_menu', '0', 'website/categories/edit', '0', '', 'sub_categories_edit_category', 'Yes', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['sub_categories_sub_items'] = ['1', 'Sub Items', '0', 'categories_sub_menu', '0', 'website/categories/sub-items', '0', '', 'sub_categories_sub_items', 'Yes', '', '5', '{}', $first_last_name, $first_last_name];

$parameter['sub_categories_displaying_in'] = ['1', 'Displaying In', '0', 'categories_sub_menu', '0', 'website/categories/displaying-in', '0', '', 'sub_categories_displaying_in', 'Yes', '', '6', '{}', $first_last_name, $first_last_name];

//Pages - Sub Menu
$parameter['sub_pages_edit_page'] = ['1', 'Edit Page', '0', 'pages_sub_menu', '0', 'website/pages/edit', '0', '', 'sub_pages_edit_page', 'Yes', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['sub_pages_sub_items'] = ['1', 'Sub Items', '0', 'pages_sub_menu', '0', 'website/pages/sub-items', '0', '', 'sub_pages_sub_items', 'Yes', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['sub_pages_displaying_in'] = ['1', 'Displaying In', '0', 'pages_sub_menu', '0', 'website/pages/displaying-in', '0', '', 'sub_pages_displaying_in', 'Yes', '', '3', '{}', $first_last_name, $first_last_name];

//Posts - Sub Menu
$parameter['sub_posts_edit_post'] = ['1', 'Edit Post', '0', 'posts_sub_menu', '0', 'website/posts/edit', '0', '', 'sub_posts_edit_post', 'Yes', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['sub_posts_sub_items'] = ['1', 'Sub Items', '0', 'posts_sub_menu', '0', 'website/posts/sub-items', '0', '', 'sub_posts_sub_items', 'Yes', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['sub_posts_comments'] = ['1', 'Comments', '0', 'posts_sub_menu', '0', 'website/posts/comments', '0', '', 'sub_posts_comments', 'Yes', '', '3', '{}', $first_last_name, $first_last_name];

$parameter['sub_posts_displaying_in'] = ['1', 'Displaying In', '0', 'posts_sub_menu', '0', 'website/posts/displaying-in', '0', '', 'sub_posts_displaying_in', 'Yes', '', '4', '{}', $first_last_name, $first_last_name];

//Forms - Sub Menu
$parameter['sub_forms_edit_form'] = ['1', 'Edit Form', '0', 'forms_sub_menu', '0', 'website/forms/edit', '0', '', 'sub_forms_edit_form', 'Yes', '', '1', '{}', $first_last_name, $first_last_name];

$parameter['sub_forms_form_fields'] = ['1', 'Form Fields', '0', 'forms_sub_menu', '0', 'website/forms/form-fields', '0', '', 'sub_forms_form_fields', 'Yes', '', '2', '{}', $first_last_name, $first_last_name];

$parameter['sub_forms_media_swatches'] = ['1', 'Media Swatches', '0', 'forms_sub_menu', '0', 'website/forms/media-swatches', '0', '', 'sub_forms_media_swatches', 'Yes', '', '3', '{}', $first_last_name, $first_last_name];

//Get admin_menus to map admin_menu_items to admin_menu ids.
$current_admin_menus = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_menus', '', [], 'system_code', 'id');

//Get admin_pages to map admin_menu_items to admin_pages ids.
$current_admin_pages = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_pages', '', [], 'url');

//Set admin_pages_ids
$parameters_with_admin_page_ids = array();
$parent_key = array();
$database_row_id_counter = 1;
if(!empty($parameter))
{
	foreach($parameter as $key => $values)
	{
		//$values[3] is admin_menus_id.
		if(!empty($values[3]) && isset($current_admin_menus[$values[3]]['id']))
		{
			//Set the correct top-level admin menu id on the admin menu item.
			$admin_menus_id = $current_admin_menus[$values[3]]['id'];
			$values[3] = $admin_menus_id;
		}
		//If no admin_menus_id, just set the array back in.
		else
		{
			//$values[3] not found in $current_admin_menus - 0 and it wont be linked to any menu.
			$values[3] = 0;
		}
		
		//$values[5] is admin page 'url'.
		if(!empty($values[5]) && isset($current_admin_pages[$values[5]]['id']))
		{
			//Set the correct admin page id on the menu item.
			$admin_page_id = $current_admin_pages[$values[5]]['id'];
			//$values[4] is admin_pages_id.
			$values[4] = $admin_page_id;
		}
		//If no admin_page, just set the array back in.
		else
		{
			//$values[4] is admin_pages_id - 0 for no link to an admin page
			$values[4] = 0;
		}
		
		//Store the updated row
		$parameters_with_admin_page_ids[$key] = $values;
		
		//Build lookup: system_code => simulated ID
		//$values[8] = system_code
		$system_code = $values[8];
		if(!empty($system_code))
		{
			$parent_key[$system_code] = $database_row_id_counter;
		}

		$database_row_id_counter++;
	}
}

//Set parent menu item id
$parameters = array();
if(!empty($parameters_with_admin_page_ids))
{
	foreach($parameters_with_admin_page_ids as $key => $values)
	{
		//$values[7] = parent_code
		$parent_code = $values[7];

		if(!empty($parent_code) && isset($parent_key[$parent_code]))
		{
			//Look up parent ID from the parent_key map
			//$values[6] = parent_id
			$values[6] = $parent_key[$parent_code];
		}
		else
		{
			//No parent -> top-level menu
			//$values[6] = parent_id - 0 for no parent menu item
			$values[6] = 0;
		}

		//Store the updated row
		$parameters[$key] = $values;
	}
}

if(!isset($update_admin_menu_items))
{
	$results->getinsertMultipleRecords(__LINE__, __FILE__, 'admin_menu_items', $column_names, $placeholders, $parameters);
}
else
{
	$update_parameters = $parameters;
	$parameters = array();
	
	foreach($update_parameters as $param)
	{
		$parameters[$param[8]] = ['status' => $param[0], 
						 'name' => $param[1], 
						 'sub_items' => $param[2], 
						 'admin_menus_id' => $param[3], 
						 'admin_pages_id' => $param[4], 
						 'admin_page_system_code' => $param[5], 
						 'parent_id' => $param[6], 
						 'admin_menu_items_parent_code' => $param[7], 
						 'system_code' => $param[8],
						 'link_parameters' => $param[9],
						 'link_target' => $param[10],
						 'sort' => $param[11],
						 'custom_fields' => $param[12],
						 'updated_by' => $first_last_name, 
						 'created_by' => $first_last_name];
	}
}