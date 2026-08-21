<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Admin Pages
$column_names = '`id`, `site_id`, `admin_pages_name`, `url`, `add_url`, `edit_url`, `sub_items_url`, `sub_items_add_url`, `sub_items_edit_url`, `save_url`, `no_record_url`, `help_video_url`, `type`, `table_name`, `table_link_column`, `parent_table_name`, `parent_table_link_column`, `child_table_name`, `child_table_link_column`, `admin_pages_parent_code`, `system_code`, `admin_page_level`, `sub_page`, `sort_or_dragdrop`, `global`, `one_record`, `parent_indicator`, `admin_pages_assigned_type`, `js_name`, `class`, `submit_button_label`, `submit_button_type`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = 'NULL,0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();

$parameters[] = ['Add a Site', 'add-a-site', '', '', '', '', '', '', '', '', 'static', 'sites', '', '', '', '', '', '', 'add-a-site', '1', 'No', 'sort', 'No', 'No', 'No', 'add_a_site', '', 'add-a-site', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Database Tables<span class="display-block-font-size"><span class="color-e50606">WARNING:</span> You should never delete a Database Table. Deleting Database Tables will destroy the entire site. Be extremely cautious.</span>', 'admin/database-tables', '', 'admin/database-tables/edit', '', '', '', '', '', '', 'table', 'database_tables', '', '', '', '', '', '', 'admin/database-tables', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-database-tables', 'database-tables', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Database Table', 'admin/database-tables/add', '', '', '', '', '', 'admin/database-tables', '', '', 'add', 'database_tables', '', '', '', '', '', '', 'admin/database-tables/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-database-tables', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Database Table', 'admin/database-tables/edit', '', '', '', '', '', 'admin/database-tables', 'admin/database-tables', '', 'edit', 'database_tables', '', '', '', '', '', 'admin/database-tables', 'admin/database-tables/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-database-tables', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Export Data', 'admin/export-data', '', '', '', '', '', 'admin/export-data', '', '', 'edit', 'export_data', '', '', '', '', '', '', 'admin/export-data', '1', 'No', 'sort', 'Yes', 'Yes', 'No', '', '', 'export-data', 'Export Data', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Admin Field List Options', 'admin/field-list-options', '', 'edit', '', 'admin/field-list-options/add', 'admin/field-list-options/edit', '', 'admin/field-lists', '', 'table', 'admin_fields_values', 'admin_fields_lists_id', 'admin_fields_lists', 'id', '', '', 'admin/field-lists', 'admin/field-list-options', '1', 'Yes', 'dragdrop', 'Yes', 'No', 'No', '', 'delete-records', 'list-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Admin Field List Option', 'admin/field-list-options/add', '', '', '', '', '', 'admin/field-list-options', 'admin/field-lists', '', 'add', 'admin_fields_values', 'admin_fields_lists_id', 'admin_fields_lists', 'id', '', '', 'admin/field-lists', 'admin/field-list-options/add', '1', 'Yes', 'sort', 'Yes', 'No', 'No', '', '', 'add-list-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Admin Field List Option', 'admin/field-list-options/edit', '', '', '', '', '', 'admin/field-list-options', 'admin/field-lists', '', 'edit', 'admin_fields_values', 'admin_fields_lists_id', 'admin_fields_lists', 'id', '', '', 'admin/field-lists', 'admin/field-list-options/edit', '1', 'Yes', 'sort', 'Yes', 'No', 'No', '', '', 'edit-list-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Admin Field Lists', 'admin/field-lists', '', 'admin/field-lists/edit', 'admin/field-list-options', '', '', '', '', '', 'table', 'admin_fields_lists', '', '', '', 'admin_fields_values', 'admin_fields_lists_id', '0', 'admin/field-lists', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-records', 'lists', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Admin Field List', 'admin/field-lists/add', '', '', '', '', '', 'admin/field-lists', '', '', 'add', 'admin_fields_lists', '', '', '', 'admin_fields_values', 'admin_fields_lists_id', '0', 'admin/field-lists/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-list', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Admin Field List', 'admin/field-lists/edit', '', '', '', '', 'admin/field-list-options', 'admin/field-lists', 'admin/field-lists', '', 'edit', 'admin_fields_lists', '', '', '', 'admin_fields_values', 'admin_fields_lists_id', 'admin/field-lists', 'admin/field-lists/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-lists', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Field Sections', 'admin/field-sections', '', 'admin/field-sections/edit', '', '', '', '', '', '', 'table', 'admin_fields_sections', '', '', '', '', '', '', 'admin/field-sections', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-records', 'field-sections', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Field Section', 'admin/field-sections/add', '', '', '', '', '', 'admin/field-sections', '', '', 'add', 'admin_fields_sections', '', '', '', '', '', '', 'admin/field-sections/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-field-sections', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Field Section', 'admin/field-sections/edit', '', '', '', '', '', 'admin/field-sections', 'admin/field-sections', '', 'edit', 'admin_fields_sections', '', '', '', '', '', 'admin/field-sections', 'admin/field-sections/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-field-sections', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Admin Fields<span class="display-block-font-size"><span class="color-e50606">WARNING:</span> You should never delete Admin Fields. Deleting any Admin Fields can destroy the entire site. This will alter the database and drop columns.</span>', 'admin/fields', '', 'admin/fields/edit', '', '', '', '', '', '', 'table', 'admin_fields', '', '', '', '', '', '', 'admin/fields', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-admin-fields', 'admin-fields', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Admin Field', 'admin/fields/add', '', '', '', '', '', 'admin/fields', '', '', 'add', 'admin_fields', '', '', '', '', '', '', 'admin/fields/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'admin-add-field', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Admin Field', 'admin/fields/edit', '', '', '', '', '', '', 'admin/fields', '', 'edit', 'admin_fields', '', '', '', '', '', 'admin/fields', 'admin/fields/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'admin-edit-field', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Import Data', 'admin/import-data', '', '', '', '', '', 'admin/import-data', '', '', 'edit', 'import_data', '', '', '', '', '', '', 'admin/import-data', '1', 'No', 'sort', 'Yes', 'Yes', 'No', '', '', 'import-data', 'Import Data', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Admin Menu Items', 'admin/menu-items', '', '', 'admin/menu-items', 'admin/menu-items/add', 'admin/menu-items/edit', '', 'admin/menus', '', 'table', 'admin_menu_items', 'admin_menus_id', 'admin_menus', 'id', '', '', 'admin/menus', 'admin/menu-items', '1', 'Yes', 'dragdrop', 'Yes', 'No', 'Yes', '', 'delete-admin-menus', 'admin-menu-items', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Admin Menu Item', 'admin/menu-items/add', '', '', '', '', '', 'admin/menu-items', 'admin/menus', '', 'add', 'admin_menu_items', 'admin_menus_id', 'admin_menus', 'id', '', '', 'admin/menus', 'admin/menu-items/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'admin-add-menu-item', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Admin Menu Item', 'admin/menu-items/edit', '', '', '', '', 'admin/menu-items', 'admin/menu-items', 'admin/menus', '', 'edit', 'admin_menu_items', 'admin_menus_id', 'admin_menus', 'id', '', '', 'admin/menus', 'admin/menu-items/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'admin-edit-menu-item', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Admin Menus', 'admin/menus', '', 'admin/menus/edit', 'admin/menu-items', '', '', '', '', '', 'table', 'admin_menus', '', '', '', 'admin_menu_items', 'admin_menus_id', '0', 'admin/menus', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-admin-menus', 'admin-menus', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Admin Menu', 'admin/menus/add', '', '', '', '', '', 'admin/menus', '', '', 'add', 'admin_menus', '', '', '', 'admin_menu_items', 'admin_menus_id', '0', 'admin/menus/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'admin-add-menu', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Admin Menu', 'admin/menus/edit', '', '', '', '', 'admin/menu-items', 'admin/menus', 'admin/menus', '', 'edit', 'admin_menus', '', '', '', 'admin_menu_items', 'admin_menus_id', 'admin/menus', 'admin/menus/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'admin-edit-menu', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['All Notices', 'admin/notices', '', 'admin/notices/edit', '', '', '', '', '', '', 'table', 'notices', '', '', '', '', '', '', 'admin/notices', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'hide-delete-option', 'notices', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Notice', 'admin/notices/edit', '', '', '', '', '', '', 'admin/notices', '', 'edit', 'notices', '', '', '', '', '', 'admin/notices', 'admin/notices/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-notices', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Admin Pages', 'admin/pages', '', 'admin/pages/edit', '', '', '', '', '', '', 'table', 'admin_pages', '', '', '', '', '', '', 'admin/pages', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-records', 'admin-pages', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Admin Page', 'admin/pages/add', '', '', '', '', '', 'admin/pages', '', '', 'add', 'admin_pages', '', '', '', '', '', '', 'admin/pages/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'admin-add-page', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Admin Page', 'admin/pages/edit', '', '', '', '', '', '', 'admin/pages', '', 'edit', 'admin_pages', '', '', '', '', '', 'admin/pages', 'admin/pages/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'admin-edit-page', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['All Permissions', 'admin/user-permissions', '', 'admin/user-permissions/edit', '', '', '', '', '', '', 'table', 'permissions', '', '', '', '', '', '', 'admin/user-permissions', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-records', 'permissions', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Permissions', 'admin/user-permissions/add', '', '', '', '', '', 'admin/user-permissions', '', '', 'add', 'permissions', '', '', '', '', '', '', 'admin/user-permissions/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-permissions', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Permissions', 'admin/user-permissions/edit', '', '', '', '', '', '', 'admin/user-permissions', '', 'edit', 'permissions', '', '', '', '', '', 'admin/user-permissions', 'admin/user-permissions/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-permissions', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['All Users', 'admin/users', '', 'admin/users/edit', '', '', '', '', '', '', 'table', 'users', '', '', '', '', '', '', 'admin/users', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-admin-users', 'users', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add User', 'admin/users/add', '', '', '', '', '', 'admin/users', '', '', 'add', 'users', '', '', '', '', '', '', 'admin/users/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-user', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit User', 'admin/users/edit', '', '', '', '', '', '', 'admin/users', '', 'edit', 'users', '', '', '', '', '', 'admin/users', 'admin/users/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-user', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Pageviews', 'analytics/pageviews', '', '', '', '', '', '', '', '', 'static', 'analytics', '', '', '', '', '', 'dashboard', 'analytics/pageviews', '1', 'No', 'sort', 'No', 'No', 'No', 'analytics_pageviews', '', '', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Unique Visitors', 'analytics/unique-visitors', '', '', '', '', '', '', '', '', 'static', 'analytics', '', '', '', '', '', 'dashboard', 'analytics/unique-visitors', '1', 'No', 'sort', 'No', 'No', 'No', 'analytics_unique_visitors', '', '', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Custom Field Options', 'custom-field-options', '', '', '', 'custom-field-options/add', 'custom-field-options/edit', '', 'custom-fields', '', 'table', 'custom_fields_options', 'custom_fields_id', 'custom_fields', 'id', '', '', 'custom-fields', 'custom-field-options', '1', 'Yes', 'dragdrop', 'Yes', 'No', 'No', 'custom_field_options', 'delete-sub-item-records', 'custom-field-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Custom Field Option', 'custom-field-options/add', '', '', '', '', '', 'custom-field-options', 'custom-fields', '', 'add', 'custom_fields_options', 'custom_fields_id', 'custom_fields', 'id', '', '', 'custom-fields', 'custom-field-options/add', '1', 'Yes', 'sort', 'Yes', 'No', 'No', '', '', 'add-custom-field-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Custom Field Option', 'custom-field-options/edit', '', '', '', '', '', 'custom-field-options', 'custom-fields', '', 'edit', 'custom_fields_options', 'custom_fields_id', 'custom_fields', 'id', '', '', 'custom-fields', 'custom-field-options/edit', '1', 'Yes', 'sort', 'Yes', 'No', 'No', '', '', 'edit-custom-field-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Custom Fields<span class="display-block-font-size">Create content custom field shortcodes to embed in templates and set a unique value for each URL. For a field that uses the same value across all URLs, assign it to global_custom_fields and embed its shortcode in any templates where you want it displayed. Its value can then be managed from Global Custom Fields in the admin. If you are using Commerce, you can also create custom fields for Inventory Attributes and Product Options.</span>', 'custom-fields', '', 'custom-fields/edit', 'custom-field-options', '', '', '', '', '', 'table', 'custom_fields', '', '', '', 'custom_fields_options', 'custom_fields_id', '0', 'custom-fields', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-custom-fields', 'custom-fields', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Custom Field', 'custom-fields/add', '', '', '', '', '', 'custom-fields', '', '', 'add', 'custom_fields', '', '', '', 'custom_fields_options', 'custom_fields_id', '0', 'custom-fields/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-custom-field', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Custom Field', 'custom-fields/edit', '', '', '', '', 'custom-field-options', 'custom-fields', 'custom-fields', '', 'edit', 'custom_fields', '', '', '', 'custom_fields_options', 'custom_fields_id', 'custom-fields', 'custom-fields/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-custom-field', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Global Custom Fields<span class="display-block-font-size">If you created a custom field assigned to global_custom_fields, this is where you set its value. That value will display across all URLs using a template where the custom field shortcode is embedded. Global custom fields are often useful for shared content in areas such as sidebars, footers, and other template includes.</span>', 'custom-fields/global-custom-fields', '', '', '', '', '', 'custom-fields/global-custom-fields', '', '', 'edit', 'custom_fields_global', '', '', '', '', '', '', 'custom-fields/global-custom-fields', '1', 'No', 'sort', 'No', 'Yes', 'No', '', '', 'global-custom-fields', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Junk Leads', 'customers/junk-leads', '', 'customers/junk-leads/edit', '', '', '', '', 'customers/leads', '', 'table', 'leads', '', '', '', '', '', 'customers/leads', 'customers/junk-leads', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-leads', 'junk-leads', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Junk Lead', 'customers/junk-leads/edit', '', '', '', '', '', '', 'customers/leads', '', 'edit', 'leads', '', '', '', '', '', 'customers/leads', 'customers/junk-leads/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-junk-lead', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Good Leads', 'customers/leads', '', 'customers/leads/edit', '', '', '', '', '', '', 'table', 'leads', '', '', '', '', '', '', 'customers/leads', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-leads', 'good-leads', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Lead', 'customers/leads/edit', '', '', '', '', '', '', 'customers/leads', '', 'edit', 'leads', '', '', '', '', '', 'customers/leads', 'customers/leads/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-good-lead', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Dashboard', 'dashboard', '', '', '', '', '', '', '', 'https://www.ratals.com/tutorials/dashboard/', 'static', 'analytics', '', '', '', '', '', '', 'dashboard', '1', 'No', 'sort', 'No', 'No', 'No', 'dashboard', '', 'dashboard', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['License', 'license', '', '', '', '', '', 'license', '', '', 'edit', 'license', '', '', '', '', '', '', 'license', '1', 'No', 'sort', 'Yes', 'Yes', 'No', '', '', 'license', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Logout', 'logout.php', '', '', '', '', '', '', '', '', 'static', '', '', '', '', '', '', '', 'logout.php', '1', 'No', '', '', 'No', '', '', '', 'logout', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Media<span class="display-block-font-size">Original Media: Yes means this is the original uploaded file. No means this is an automatically generated size or format linked to the original through Original Media ID.</span>', 'media', '', 'media/edit', '', '', '', '', '', '', 'table', 'media', '', '', '', '', '', '', 'media', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-media', 'media', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Media', 'media/add', '', '', '', '', '', 'media', '', '', 'add', 'media', '', '', '', '', '', '', 'media/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-media', 'Upload', 'button', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Video Embed', 'media/add-video-embed', '', '', '', '', '', 'media', '', '', 'add', 'media', '', '', '', '', '', '', 'media/add-video-embed', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-video-embed', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Media', 'media/edit', '', '', '', '', '', '', 'media', '', 'edit', 'media', '', '', '', '', '', 'media', 'media/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-media', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Blocking Spam', 'security/blocking-spam', '', '', '', '', '', 'security/blocking-spam', '', '', 'edit', 'blocking_spam', '', '', '', '', '', '', 'security/blocking-spam', '1', 'No', 'sort', 'No', 'Yes', 'No', '', '', 'blocking-spam', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Failed Logins', 'security/failed-logins', '', 'security/failed-logins/edit', '', '', '', '', '', '', 'table', 'failed_logins', '', '', '', '', '', '', 'security/failed-logins', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-records', 'failed-logins', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Failed Login', 'security/failed-logins/edit', '', '', '', '', '', '', 'security/failed-logins', '', 'edit', 'failed_logins', '', '', '', '', '', 'security/failed-logins', 'security/failed-logins/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-failed-logins', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Site Security', 'security/site-security', '', '', '', '', '', 'security/site-security', '', '', 'edit', 'site_security', '', '', '', '', '', '', 'security/site-security', '1', 'No', 'sort', 'No', 'Yes', 'No', '', '', 'site-security', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['All 404 Errors', 'website/404-urls', '', 'website/404-urls/edit', '', '', '', '', '', '', 'table', 'errors_404', '', '', '', '', '', '', 'website/404-urls', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-404-errors', '404-urls', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit 404 Error', 'website/404-urls/edit', '', '', '', '', '', '', 'website/404-urls', '', 'edit', 'errors_404', '', '', '', '', '', 'website/404-urls', 'website/404-urls/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-404-urls', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['All Author Bios', 'website/authors', '', 'website/authors/edit', 'website/urls', '', '', '', '', '', 'table', 'authors', '', '', '', '', '', '', 'website/authors', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-records', 'authors', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Author Bio', 'website/authors/add', '', '', '', '', '', 'website/authors', '', '', 'add', 'authors', '', '', '', '', '', '', 'website/authors/add', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'add-author', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Author Bio', 'website/authors/edit', '', '', '', '', '', '', 'website/authors', '', 'edit', 'authors', '', '', '', '', '', 'website/authors', 'website/authors/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-author', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Categories', 'website/categories', '', 'website/categories/edit', 'website/urls', '', '', '', '', '', 'table', 'categories', '', '', '', '', '', '', 'website/categories', '1', 'No', 'sort', 'No', 'No', 'No', 'categories', 'delete-records-with-url-id', 'categories', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Category', 'website/categories/add', '', '', '', '', '', 'website/categories', '', '', 'add', 'categories', '', '', '', '', '', '', 'website/categories/add', '1', 'No', 'sort', 'No', 'No', 'No', 'categories', '', 'add-category', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Displaying In', 'website/categories/displaying-in', '', '', '', '', '', '', 'website/categories', '', 'static', 'categories', '', '', '', '', '', 'website/categories', 'website/categories/displaying-in', '1', 'No', 'sort', 'No', 'No', 'No', 'displaying_in', '', 'category-displaying-in', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Category', 'website/categories/edit', '', '', '', '', '', '', 'website/categories', '', 'edit', 'categories', '', '', '', '', '', 'website/categories', 'website/categories/edit', '1', 'No', 'sort', 'No', 'No', 'No', 'categories', '', 'edit-category', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Redirects', 'website/categories/redirects', '', '', '', '', '', 'website/categories/edit', 'website/categories', '', 'static', 'categories', '', '', '', '', '', '', 'website/categories/redirects', '1', 'No', 'sort', 'No', 'No', 'No', 'redirects', '', 'category-redirects', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Sub Items', 'website/categories/sub-items', '', '', '', '', '', 'website/categories/sub-items', 'website/categories', '', 'static', 'categories', '', '', '', '', '', 'website/categories', 'website/categories/sub-items', '1', 'No', 'sort', 'No', 'No', 'No', 'sub_items', '', 'category-sub-items', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Unassigned Categories', 'website/categories/unassigned', '', 'website/categories/edit', '', '', '', '', '', '', 'static', 'categories', '', '', '', '', '', '', 'website/categories/unassigned', '1', 'No', 'sort', 'No', 'No', 'No', 'unassigned_categories', '', 'unassigned-categories', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Form Field Options', 'website/form-field-options', '', '', '', 'website/form-field-options/add', 'website/form-field-options/edit', '', 'website/form-fields', '', 'table', 'form_values', 'form_fields_id', 'form_fields', 'id', '', '', 'website/form-fields', 'website/form-field-options', '1', 'Yes', 'dragdrop', 'Yes', 'No', 'No', 'form_field_options', 'delete-form-field-options', 'form-field-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Form Field Option', 'website/form-field-options/add', '', '', '', '', '', 'website/form-field-options', 'website/form-fields', '', 'add', 'form_values', 'form_fields_id', 'form_fields', 'id', '', '', 'website/form-fields', 'website/form-field-options/add', '1', 'Yes', 'sort', 'Yes', 'No', 'No', '', '', 'add-form-field-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Form Field Option', 'website/form-field-options/edit', '', '', '', '', '', 'website/form-field-options', 'website/form-fields', '', 'edit', 'form_values', 'form_fields_id', 'form_fields', 'id', '', '', 'website/form-fields', 'website/form-field-options/edit', '1', 'Yes', 'sort', 'Yes', 'No', 'No', '', '', 'edit-form-field-options', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Form Fields<span class="display-block-font-size"><strong>Note:</strong> This section contains the fields available for use within your forms. To manage an existing form field, click Edit on the corresponding row below.</span>', 'website/form-fields', '', 'website/form-fields/edit', 'website/form-field-options', '', '', '', '', '', 'table', 'form_fields', '', '', '', 'form_values', 'form_fields_id', '0', 'website/form-fields', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-form-fields', 'form-fields', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Form Field<span class="display-block-font-size"><strong>Note:</strong> This section allows you to create form fields that can be assigned to a form. For fields such as dropdowns or swatches, use Sub Items after creating the field to add the available options, such as dropdown values or colors.</span>', 'website/form-fields/add', '', '', '', '', '', 'website/form-fields', '', '', 'add', 'form_fields', '', '', '', 'form_values', 'form_fields_id', '0', 'website/form-fields/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-form-field', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Form Field', 'website/form-fields/edit', '', '', '', '', 'website/form-field-options', '', 'website/form-fields', '', 'edit', 'form_fields', '', '', '', 'form_values', 'form_fields_id', 'website/form-fields', 'website/form-fields/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-form-field', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Forms', 'website/forms', '', 'website/forms/edit', '', '', '', '', '', '', 'table', 'forms', '', '', '', '', '', '', 'website/forms', '1', 'No', 'sort', 'Yes', 'No', 'No', '', 'delete-forms-and-swatches', 'forms', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Form<span class="display-block-font-size"><strong>Note:</strong> If you are unsure how each setting is used, review the two forms included with your installation for examples. Any CSS class names entered below can be defined in your website template stylesheet to customize the form\'s appearance.</span>', 'website/forms/add', '', '', '', '', '', 'website/forms', '', '', 'add', 'forms', '', '', '', '', '', '', 'website/forms/add', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'add-form', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Form', 'website/forms/edit', '', '', '', '', '', '', 'website/forms', '', 'edit', 'forms', '', '', '', '', '', 'website/forms', 'website/forms/edit', '1', 'No', 'sort', 'Yes', 'No', 'No', '', '', 'edit-form', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Form Fields<span class="display-block-font-size"><strong>Note:</strong> Click the Manage Form Fields button to add or reorder fields assigned to this form. To create a new field, go to <a href="/[ADMIN_DIRECTORY]/website/form-fields/add/">Add Form Field</a>, then return here to assign it. To edit an existing field, go to <a href="/[ADMIN_DIRECTORY]/website/form-fields/">Form Fields</a>. Form fields are created separately so they can be reused across multiple forms.</span>', 'website/forms/form-fields', '', '', '', '', '', 'website/forms/form-fields', 'website/forms', '', 'static', 'forms', '', '', '', '', '', 'website/forms', 'website/forms/form-fields', '1', 'No', 'sort', 'Yes', 'No', 'No', 'form_fields_assigned', '', 'form-fields-assigned', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Form Media Swatches<span class="display-block-font-size"><strong>Note:</strong> To add images for a swatch field, first assign a form field configured as a swatch to this form. The swatch options will then appear below, allowing you to assign media to each option for display within the form.</span>', 'website/forms/media-swatches', '', '', '', '', '', 'website/forms/media-swatches', 'website/forms', '', 'static', 'form_media', 'id', 'forms', 'id', '', '', 'website/forms', 'website/forms/media-swatches', '1', 'No', 'sort', 'Yes', 'No', 'No', 'form_media_swatches', '', 'form-media-swatches', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['All Posts Comments', 'website/interactions/posts-comments', '', 'website/interactions/posts-comments/edit', '', '', '', '', '', '', 'table', 'comments', '', '', '', '', '', '', 'website/interactions/posts-comments', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-records', 'posts-comments', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Post Comment', 'website/interactions/posts-comments/edit', '', '', '', '', '', '', 'website/interactions/posts-comments', '', 'edit', 'comments', '', '', '', '', '', 'website/interactions/posts-comments', 'website/interactions/posts-comments/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-posts-comments', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['All Site Searches', 'website/interactions/site-searches', '', 'website/interactions/site-searches/edit', '', '', '', '', '', '', 'table', 'site_search', '', '', '', '', '', '', 'website/interactions/site-searches', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-site-searches', 'site-searches', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Site Searches', 'website/interactions/site-searches/edit', '', '', '', '', '', '', 'website/interactions/site-searches', '', 'edit', 'site_search', '', '', '', '', '', 'website/interactions/site-searches', 'website/interactions/site-searches/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-site-searches', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Menu Items', 'website/menu-items', '', '', 'website/menu-items', 'website/menu-items/add', 'website/menu-items/edit', '', 'website/menus', '', 'table', 'menu_items', 'menus_id', 'menus', 'id', '', '', 'website/menus', 'website/menu-items', '1', 'Yes', 'dragdrop', 'No', 'No', 'Yes', '', 'delete-menus', 'menu-items', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Menu Item', 'website/menu-items/add', '', '', '', '', '', 'website/menu-items', 'website/menus', '', 'add', 'menu_items', 'menus_id', 'menus', 'id', '', '', 'website/menus', 'website/menu-items/add', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'add-menu-item', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Menu Item', 'website/menu-items/edit', '', '', '', '', 'website/menu-items', 'website/menu-items', 'website/menus', '', 'edit', 'menu_items', 'menus_id', 'menus', 'id', '', '', 'website/menus', 'website/menu-items/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-menu-item', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Menus', 'website/menus', '', 'website/menus/edit', 'website/menu-items', '', '', '', '', '', 'table', 'menus', '', '', '', 'menu_items', 'menus_id', '0', 'website/menus', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-menus', 'menus', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Menu', 'website/menus/add', '', '', '', '', '', 'website/menus', '', '', 'add', 'menus', '', '', '', 'menu_items', 'menus_id', '0', 'website/menus/add', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'add-menu', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Menu<span class="display-block-font-size"><strong>Note:</strong> Menu items are managed through the Sub Items link below. Click Sub Items to add, edit, or organize the items displayed within this menu.</span>', 'website/menus/edit', '', '', '', '', 'website/menu-items', 'website/menus', 'website/menus', '', 'edit', 'menus', '', '', '', 'menu_items', 'menus_id', 'website/menus', 'website/menus/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-menu', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Pages', 'website/pages', '', 'website/pages/edit', 'website/urls', '', '', '', '', '', 'table', 'pages', '', '', '', '', '', '', 'website/pages', '1', 'No', 'sort', 'No', 'No', 'No', 'pages', 'delete-records-with-url-id', 'pages', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Page', 'website/pages/add', '', '', '', '', '', 'website/pages', '', '', 'add', 'pages', '', '', '', '', '', '', 'website/pages/add', '1', 'No', 'sort', 'No', 'No', 'No', 'pages', '', 'add-page', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Displaying In', 'website/pages/displaying-in', '', '', '', '', '', '', 'website/pages', '', 'static', 'pages', '', '', '', '', '', 'website/pages', 'website/pages/displaying-in', '1', 'No', 'sort', 'No', 'No', 'No', 'displaying_in', '', 'pages-displaying-in', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Page', 'website/pages/edit', '', '', '', '', '', '', 'website/pages', '', 'edit', 'pages', '', '', '', '', '', 'website/pages', 'website/pages/edit', '1', 'No', 'sort', 'No', 'No', 'No', 'pages', '', 'edit-page', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Redirects', 'website/pages/redirects', '', '', '', '', '', 'website/pages/edit', 'website/pages', '', 'static', 'pages', '', '', '', '', '', '', 'website/pages/redirects', '1', 'No', 'sort', 'No', 'No', 'No', 'redirects', '', 'pages-redirects', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Sub Items', 'website/pages/sub-items', '', '', '', '', '', 'website/pages/sub-items', 'website/pages', '', 'static', 'pages', '', '', '', '', '', 'website/pages', 'website/pages/sub-items', '1', 'No', 'sort', 'No', 'No', 'No', 'sub_items', '', 'pages-sub-items', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Unassigned Pages', 'website/pages/unassigned', '', 'website/pages/edit', '', '', '', '', '', '', 'static', 'pages', '', '', '', '', '', '', 'website/pages/unassigned', '1', 'No', 'sort', 'No', 'No', 'No', 'unassigned_pages', '', 'unassigned-pages', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Posts', 'website/posts', '', 'website/posts/edit', 'website/urls', '', '', '', '', '', 'table', 'posts', '', '', '', '', '', '', 'website/posts', '1', 'No', 'sort', 'No', 'No', 'No', 'posts', 'delete-records-with-url-id', 'posts', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Post', 'website/posts/add', '', '', '', '', '', 'website/posts', '', '', 'add', 'posts', '', '', '', '', '', '', 'website/posts/add', '1', 'No', 'sort', 'No', 'No', 'No', 'posts', '', 'add-post', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Comments', 'website/posts/comments', '', '', '', '', '', '', 'website/posts', '', 'static', 'posts', '', '', '', '', '', 'website/posts', 'website/posts/comments', '1', 'No', 'sort', 'No', 'No', 'No', 'posts_comments', '', 'posts-comments', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Displaying In', 'website/posts/displaying-in', '', '', '', '', '', '', 'website/posts', '', 'static', 'posts', '', '', '', '', '', 'website/posts', 'website/posts/displaying-in', '1', 'No', 'sort', 'No', 'No', 'No', 'displaying_in', '', 'posts-displaying-in', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Post', 'website/posts/edit', '', '', '', '', '', '', 'website/posts', '', 'edit', 'posts', '', '', '', '', '', 'website/posts', 'website/posts/edit', '1', 'No', 'sort', 'No', 'No', 'No', 'posts', '', 'edit-post', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Redirects', 'website/posts/redirects', '', '', '', '', '', 'website/posts/edit', 'website/posts', '', 'static', 'posts', '', '', '', '', '', '', 'website/posts/redirects', '1', 'No', 'sort', 'No', 'No', 'No', 'redirects', '', 'posts-redirects', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Sub Items', 'website/posts/sub-items', '', '', '', '', '', 'website/posts/sub-items', 'website/posts', '', 'static', 'posts', '', '', '', '', '', 'website/posts', 'website/posts/sub-items', '1', 'No', 'sort', 'No', 'No', 'No', 'sub_items', '', 'posts-sub-items', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Unassigned Posts', 'website/posts/unassigned', '', 'website/posts/edit', '', '', '', '', '', '', 'static', 'posts', '', '', '', '', '', '', 'website/posts/unassigned', '1', 'No', 'sort', 'No', 'No', 'No', 'unassigned_posts', '', 'unassigned-posts', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Redirects', 'website/redirects', '', 'website/redirects/edit', '', '', '', '', '', '', 'table', 'redirects', '', '', '', '', '', '', 'website/redirects', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-records', 'redirects', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Redirect', 'website/redirects/add', '', '', '', '', '', 'website/redirects', '', '', 'add', 'redirects', '', '', '', '', '', '', 'website/redirects/add', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'add-redirect', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Redirect', 'website/redirects/edit', '', '', '', '', '', 'website/redirects', 'website/redirects', '', 'edit', 'redirects', '', '', '', '', '', 'website/redirects', 'website/redirects/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-redirect', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Contact Information', 'website/site-settings/contact-information', '', '', '', '', '', 'website/site-settings/contact-information', '', '', 'edit', 'site_contact_info', '', '', '', '', '', '', 'website/site-settings/contact-information', '1', 'No', 'sort', 'No', 'Yes', 'No', '', '', 'contact-information', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['General Settings', 'website/site-settings/general-settings', '', '', '', '', '', 'website/site-settings/general-settings', '', '', 'edit', 'site_settings', '', '', '', '', '', '', 'website/site-settings/general-settings', '1', 'No', 'sort', 'No', 'Yes', 'No', '', '', 'general-settings', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Search Engines', 'website/site-settings/search-engines', '', '', '', '', '', 'website/site-settings/search-engines', '', '', 'edit', 'search_engines', '', '', '', '', '', '', 'website/site-settings/search-engines', '1', 'No', 'sort', 'No', 'Yes', 'No', '', '', 'search-engines', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['URL Settings', 'website/site-settings/url-settings', '', '', '', '', '', 'website/site-settings/url-settings', '', '', 'edit', 'sites', '', '', '', '', '', '', 'website/site-settings/url-settings', '1', 'No', 'sort', 'No', 'Yes', 'No', '', '', 'url-settings', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Slider Items', 'website/slider-items', '', '', '', 'website/slider-items/add', 'website/slider-items/edit', '', 'website/sliders', '', 'table', 'slider_items', 'sliders_id', 'sliders', 'id', '', '', 'website/sliders', 'website/slider-items', '1', 'Yes', 'dragdrop', 'No', 'No', 'No', '', 'delete-sliders', 'slider-items', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Slider Item', 'website/slider-items/add', '', '', '', '', '', 'website/slider-items', 'website/sliders', '', 'add', 'slider_items', 'sliders_id', 'sliders', 'id', '', '', 'website/sliders', 'website/slider-items/add', '1', 'Yes', 'sort', 'No', 'No', 'No', '', '', 'add-slider-item', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Slider Item', 'website/slider-items/edit', '', '', '', '', '', 'website/slider-items', 'website/sliders', '', 'edit', 'slider_items', 'sliders_id', 'sliders', 'id', '', '', 'website/sliders', 'website/slider-items/edit', '1', 'Yes', 'sort', 'No', 'No', 'No', '', '', 'edit-slider-item', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Sliders', 'website/sliders', '', 'website/sliders/edit', 'website/slider-items', '', '', '', '', '', 'table', 'sliders', '', '', '', 'slider_items', 'sliders_id', '0', 'website/sliders', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-sliders', 'sliders', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Slider', 'website/sliders/add', '', '', '', '', '', 'website/sliders', '', '', 'add', 'sliders', '', '', '', 'slider_items', 'sliders_id', '0', 'website/sliders/add', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'add-slider', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Slider', 'website/sliders/edit', '', '', '', '', 'website/slider-items', 'website/sliders', 'website/sliders', '', 'edit', 'sliders', '', '', '', 'slider_items', 'sliders_id', 'website/sliders', 'website/sliders/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-slider', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Template Files', 'website/template-files', '', '', '', 'website/template-files/add', 'website/template-files/edit', '', 'website/templates', '', 'table', 'template_files', 'templates_id', 'templates', 'id', '', '', 'website/templates', 'website/template-files', '1', 'Yes', 'sort', 'No', 'No', 'No', '', 'delete-templates', 'template-files', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Template File', 'website/template-files/add', '', '', '', '', '', 'website/template-files', 'website/templates', '', 'add', 'template_files', 'templates_id', 'templates', 'id', '', '', 'website/templates', 'website/template-files/add', '1', 'Yes', 'sort', 'No', 'No', 'No', '', '', 'add-template-file', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Template File', 'website/template-files/edit', '', '', '', '', '', 'website/template-files/edit', 'website/templates', '', 'edit', 'template_files', 'templates_id', 'templates', 'id', '', '', 'website/templates', 'website/template-files/edit', '1', 'Yes', 'sort', 'No', 'No', 'No', '', '', 'edit-template-file', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Templates', 'website/templates', '', 'website/templates/edit', 'website/template-files', '', '', '', '', '', 'table', 'templates', '', '', '', 'template_files', 'templates_id', '0', 'website/templates', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-templates', 'templates', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Add Template', 'website/templates/add', '', '', '', '', '', 'website/templates', '', '', 'add', 'templates', '', '', '', 'template_files', 'templates_id', '0', 'website/templates/add', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'add-template', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['Edit Template', 'website/templates/edit', '', '', '', '', 'website/template-files', 'website/templates', 'website/templates', '', 'edit', 'templates', '', '', '', 'template_files', 'templates_id', 'website/templates', 'website/templates/edit', '1', 'No', 'sort', 'No', 'No', 'No', '', '', 'edit-template', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

$parameters[] = ['All URLs', 'website/urls', '', '', 'website/urls', '', '', '', '', '', 'table', 'urls', '', '', '', '', '', '', 'website/urls', '1', 'No', 'sort', 'No', 'No', 'No', '', 'delete-records-with-url-id', 'urls', 'Save', 'submit', '{}', $first_last_name, $first_last_name];

if(!isset($update_admin_pages))
{
	$results->getinsertMultipleRecords(__LINE__, __FILE__, 'admin_pages', $column_names, $placeholders, $parameters);
}
else
{
	$update_parameters = $parameters;
	$parameters = array();
	
	foreach($update_parameters as $param)
	{
		$parameters[] = ['admin_pages_name' => $param[0], 
						 'url' => $param[1], 
						 'add_url' => $param[2], 
						 'edit_url' => $param[3], 
						 'sub_items_url' => $param[4], 
						 'sub_items_add_url' => $param[5], 
						 'sub_items_edit_url' => $param[6], 
						 'save_url' => $param[7], 
						 'no_record_url' => $param[8], 
						 'help_video_url' => $param[9], 
						 'type' => $param[10],
						 'table_name' => $param[11],
						 'table_link_column' => $param[12],
						 'parent_table_name' => $param[13],
						 'parent_table_link_column' => $param[14],
						 'child_table_name' => $param[15],
						 'child_table_link_column' => $param[16],
						 'admin_pages_parent_code' => $param[17],
						 'system_code' => $param[18],
						 'admin_page_level' => $param[19], 
						 'sub_page' => $param[20],
						 'sort_or_dragdrop' => $param[21],
						 'global' => $param[22],
						 'one_record' => $param[23],
						 'parent_indicator' => $param[24],
						 'admin_pages_assigned_type' => $param[25],
						 'js_name' => $param[26],
						 'class' => $param[27],
						 'submit_button_label' => $param[28],
						 'submit_button_type' => $param[29],
						 'custom_fields' => $param[30],
						 'updated_by' => $first_last_name, 
						 'created_by' => $first_last_name];
	}
}