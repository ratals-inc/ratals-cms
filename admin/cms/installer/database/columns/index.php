<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Admin Fields
$column_names = '`id`, `site_id`, `name`, `column_name`, `url_name`, `search_as`, `display_as`, `display_in_admin`, `update_field_on_save`, `placeholder`, `admin_fields_lists_system_code`, `data_type`, `character_set_and_collate`, `is_nullable`, `is_primary_key`, `is_auto_increment`, `data_length`, `data_length_back`, `financial_field`, `required`, `notes`, `css_class`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = 'NULL,0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();

$parameters['accounting_enabled'] = ['Accounting Enabled', 'accounting_enabled', 'accounting-enabled', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '<span class="color-f00"><strong>Important: </strong></span> The accounting module is still under development. Once it\'s fully completed, we\'ll send a notification in the admin panel to let you know it\'s ready to be enabled. For now, please leave this setting on No, as several features within the module are not yet finished. When development is complete, you\'ll be able to switch this to Yes and the full accounting functionality will become available in your installation.', '', '{}', $first_last_name, $first_last_name];

$parameters['add_url'] = ['Add URL', 'add_url', 'add-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'The Add URL is used to create new records. If you enter a value in this field, you must also create a corresponding admin page for this URL. Do not include the admin directory name, do not use leading or trailing slashes, and use lowercase letters only. Use dashes instead of spaces and slashes to separate directories. This URL must always end with "/add", as the system relies on this format. Example: content/menus/add', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_directory'] = ['Admin Directory URL Name', 'admin_directory', 'admin-directory', 'textfield', 'adminDirectoryUrlName', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '<span class="color-f00"><strong>Important:</strong></span> If you are using <strong>Apache</strong> or <strong>LiteSpeed</strong>, the system may take up to 15 seconds to update the admin URL after clicking "Save". Updating the admin directory URL will also apply to all sites within your account or database. If you are using <strong>Nginx</strong>, you must update the URL here and also manually update it in your server configuration (e.g., via SSH by editing /etc/nginx/sites-available/your-sites) for the change to take effect, as outlined in the <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-on-nginx/" target="_blank">Nginx setup documentation</a>.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_fields_ids'] = ['Admin Fields / Table Columns', 'admin_fields_ids', 'admin-fields-ids', 'textfield', 'adminFieldsIds', 'Yes', 'Yes', '', '0', 'varchar(512)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '512', '0', 'No', 'Yes', '<span class="color-f00"><strong>Important:</strong></span> Make sure your database tables always starts with an "id" and "site_id" as your first two columns of the database table. They should be your first and second row in this view. The only table that should not have a "site_id" column on it, is the "sites" table. The "id" of the "sites" table is the "site_id" that all other tables use. Also, if this is going to be a child table to a parent table, make sure to make your third column name as "parent_table_name_id". Replace "parent_table_name" with your parent table name. The software will look for this column for the relationship between the parent and child table. If your parent table name is "my_locations" then add "my_locations_id" as the third column on the child table.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_fields_lists_id'] = ['Admin Field List', 'admin_fields_lists_id', 'admin-fields-lists-id', 'textfield', 'parentTableId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'No', 'If the field you\'re creating will display a list of options, select the "Admin Field List" you would like to use as the options.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_fields_lists_parent_code'] = ['Admin Field Lists Parent Code', 'admin_fields_lists_parent_code', 'admin-fields-lists-parent-code', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_fields_lists', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Select the "Admin Field List" this displays under.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_fields_lists_system_code'] = ['Admin Field Lists', 'admin_fields_lists_system_code', 'admin-fields-lists-system-code', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_fields_lists', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_form_name'] = ['Admin Form Name', 'admin_form_name', 'admin-form-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_language'] = ['Admin Language', 'admin_language', 'admin-language', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'languages', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'This will only change the language for custom field\'s data as you can translate it to other languages for multilingual sites.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_menu_items_parent_code'] = ['Admin Menu List "System Code"', 'admin_menu_items_parent_code', 'admin-menu-items-parent-code', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'menu_items_parent_system_codes', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If this is a top-level menu item, leave this field empty. Otherwise, select the parent "Admin Menu List" that this menu will appear under. Admin Menu List codes are usually derived from URLs, with slashes ( / ) replaced by underscores ( _ ).', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_menus_id'] = ['Admin Menus ID', 'admin_menus_id', 'admin-menus-id', 'textfield', 'parentTableId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_name'] = ['Admin Name', 'admin_name', 'admin-name', 'textfield', 'textfieldNoEdit', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_page_level'] = ['Admin Page Level', 'admin_page_level', 'admin-page-level', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_page_level', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_page_system_code'] = ['Admin Page System Code', 'admin_page_system_code', 'admin-page-system-code', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'admin_pages_system_codes', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Select the "Admin Page System Code" that this menu item will link to. This is typically the same as the admin page\'s URL.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_pages_assigned_type'] = ['Admin Pages Assigned Type', 'admin_pages_assigned_type', 'admin-pages-assigned-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_pages_assigned_type', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', 'If you have created custom code for an assigned type for this admin page, select the appropriate type here to ensure the page loads correctly.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_pages_id'] = ['Link Menu Item To', 'admin_pages_id', 'admin-pages-id', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_pages_urls_id_as_value', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_pages_ids'] = ['Admin Pages', 'admin_pages_ids', 'admin-pages-ids', 'textfield', 'permissions', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', 'Select the Admin Page URLs that you would like this permission to be able to access.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_pages_name'] = ['Name', 'admin_pages_name', 'admin-pages-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(512)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '512', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_pages_parent_code'] = ['Admin Page Parent URL', 'admin_pages_parent_code', 'navigation-parent-code', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'admin_pages_urls_url_as_value', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Select the parent URL for this admin page. If you are creating a top-level parent page, leave this field empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['admin_permissions_id'] = ['Admin Permissions', 'admin_permissions_id', 'admin-permissions-id', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_users_permissions', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'If no Admin Permissions are set, the user will have access to all admin pages as super admin.', '', '{}', $first_last_name, $first_last_name];

$parameters['affiliate_account_id'] = ['Affiliate Account ID', 'affiliate_account_id', 'affiliate-account-id', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'If an affiliate ID is set, it means an affiliate referred this customer or traffic to you.', '', '{}', $first_last_name, $first_last_name];

$parameters['affiliate_lead_commission_amount'] = ['Affiliate Lead Commission Amount', 'affiliate_lead_commission_amount', 'affiliate-lead-commission-amount', 'textfield', 'text', 'Yes', 'No', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'If an affiliate sent the traffic that generated this lead, this is the amount they will be paid if you mark the lead as valid.', '', '{}', $first_last_name, $first_last_name];

$parameters['allow_comments'] = ['Allow Comments', 'allow_comments', 'allow-comments', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'If you set this to Yes, visitors will see the comment form to be able to leave comments. If you select as No, visitors will not be able to leave comments on this post. The comment form will be hidden.', '', '{}', $first_last_name, $first_last_name];

$parameters['allow_software_update_messages'] = ['Allow This User to See Software Update Messages', 'allow_software_update_messages', 'allow-software-update-messages', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '<span class="color-f00"><strong>Important:</strong></span> Granting this permission allows the user to view software update notifications. Users with this permission will also see an "Update Software" button and can perform updates. This should generally only be given to users who understand how to back up the current files and database before applying updates.', '', '{}', $first_last_name, $first_last_name];

$parameters['analytics_cookie_id'] = ['Analytics Cookie ID', 'analytics_cookie_id', 'analytics-cookie-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'char(32)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '32', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['analytics_unique_id'] = ['Analytics Unique ID', 'analytics_unique_id', 'analytics-unique-id', 'textfield', 'text', 'Yes', 'No', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['approved_by'] = ['Approved By', 'approved_by', 'approved-by', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['approved_date'] = ['Approved Date', 'approved_date', 'approved-date', 'dateRange', 'hidden', 'Yes', 'Yes', '&#xf073;', '0', 'datetime', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['area_served'] = ['Area Served', 'area_served', 'area-served', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Insert area served information with state or country codes like so: US, or US,CA', '', '{}', $first_last_name, $first_last_name];

$parameters['assigned_to'] = ['Assigned To', 'assigned_to', 'assigned-to', 'dropdownId', 'assignedTo', 'Yes', 'Yes', '', 'database_table_names', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['assigned_type'] = ['Assigned Type', 'assigned_type', 'assigned-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'template_files_assigned_type', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', 'Assigned Type is a flag used to identify when custom code should run with a template file. If you plan to add custom logic to this template file, you can use this field to define a variable name that your code will reference. To learn how to make a custom template file load and execute that code, see this tutorial: <a href="https://www.ratals.com/tutorials/customization/setting-up-custom-template-file-code/" target="_blank">here</a>.', '', '{}', $first_last_name, $first_last_name];

$parameters['author_bio_id'] = ['Author Bio', 'author_bio_id', 'author-bio-id', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'authors_bios', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['author_description'] = ['Author Description', 'author_description', 'author-description', 'textfield', 'textareaWithEditor', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '<span class="color-f00"><strong>Note:</strong></span> We are currently working on a WYSIWYG editor so you won\'t need to manually write HTML for your content. For now, all posts, product descriptions, and other content must be formatted using HTML. We\'re working to have the editor ready soon, allowing you to simply type your content while the formatting is handled automatically in the background. Write a short summary (about 25 to 50 words) that will appear at the bottom of content pages written by this author. The author\'s name will automatically link to their profile page, so no need to add a link manually.', '', '{}', $first_last_name, $first_last_name];

$parameters['author_job_title'] = ['Author Job Title', 'author_job_title', 'author-job-title', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['author_name'] = ['Author Name', 'author_name', 'author-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['author_personal_website_url'] = ['Author Canonical URL @ID (Source of Truth URL)', 'author_personal_website_url', 'author-personal-website-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Use a single, consistent URL that represents the author\'s primary identity across the web. This should be the author\'s main bio or profile page (their "source of truth"), and it must be used exactly the same everywhere this author appears in schema. Do not change or vary this URL - consistency ensures search engines recognize this as the same person across all sites.', '', '{}', $first_last_name, $first_last_name];

$parameters['author_photo'] = ['Author Photo', 'author_photo', 'author-photo', 'textfield', 'singleMedia', 'Yes', 'Yes', 'Media ID', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', 'Upload or select the author\'s photo. This image will display on the author\'s profile page and alongside their content.', 'media-center', '{}', $first_last_name, $first_last_name];

$parameters['author_same_as_urls'] = ['Author SameAs URLs', 'author_same_as_urls', 'author-same-as-urls', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', 'Enter the author\'s personal or professional profile URLs, such as their LinkedIn page, personal website, or another trusted source that identifies them. Separate multiple URLs with a comma. These links help search engines connect this person with their existing online presence and recognize them as an established authority in the topics related to their profiles.', '', '{}', $first_last_name, $first_last_name];

$parameters['auto_blocked_email_address'] = ['Email Address for Notices', 'auto_blocked_email_address', 'auto-blocked-email-address', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Note: It will only send you one email when the "Max Pageviews" are met within the "Time Period".', '', '{}', $first_last_name, $first_last_name];

$parameters['auto_blocked_ip_email_me'] = ['When "Max Pageviews" are met within the "Time Period" Set Above, What Should The Software Do', 'auto_blocked_ip_email_me', 'auto-blocked-ip-email-me', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'site_security_email_block_ip', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['auto_complete'] = ['Auto Complete', 'auto_complete', 'auto-complete', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'forms_auto_complete_type', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['auto_generate_canonical_url'] = ['Auto Generate Canonical URL', 'auto_generate_canonical_url', 'auto-generate-canonical-url', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['auto_slide_media'] = ['Auto Slide Media', 'auto_slide_media', 'auto-slide-media', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'Select "Yes" if you want the media to auto-slide, or "No" if you do not.', '', '{}', $first_last_name, $first_last_name];

$parameters['available_language'] = ['Available Language', 'available_language', 'available-language', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'View all language code options here: https://developers.google.com/admin-sdk/directory/v1/languages', '', '{}', $first_last_name, $first_last_name];

$parameters['back_symbol'] = ['Back Symbol', 'back_symbol', 'back-symbol', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['before_admin_field_id'] = ['Display Before The Admin Field Of', 'before_admin_field_id', 'befor-admin-field-id', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_fields_all', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['block_ip_failed_login'] = ['Block IP Addresses for 1 Hour with too Many Failed Customer Login Attempts', 'block_ip_failed_login', 'block-ip-failed-login', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['blog_pagination'] = ['Default Blog Pagination', 'blog_pagination', 'blog-pagination', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'Number of results you would like to display on each blog page category. If you set a number directly in the blog page category, this number will be over written for that blog page category.', '', '{}', $first_last_name, $first_last_name];

$parameters['blog_sidebar_link_display'] = ['Display Category Link in Blog Sidebar', 'blog_sidebar_link_display', 'blog-sidebar-link-display', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['blog_sidebar_link_order'] = ['Blog Sidebar Link Order', 'blog_sidebar_link_order', 'blog-sidebar-link-order', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'Lower numbers appear first.', '', '{}', $first_last_name, $first_last_name];

$parameters['bottom_content'] = ['Bottom Content', 'bottom_content', 'bottom-content', 'textfield', 'textareaWithEditor', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '<span class="color-f00"><strong>Note:</strong></span> We are currently working on a WYSIWYG editor so you won\'t need to manually write HTML for your content. For now, all posts, product descriptions, and other content must be formatted using HTML. We\'re working to have the editor ready soon, allowing you to simply type your content while the formatting is handled automatically in the background.', '', '{}', $first_last_name, $first_last_name];

$parameters['breadcrumbs_label'] = ['Breadcrumbs Label', 'breadcrumbs_label', 'breadcrumbs-label', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Meta Title will be used if left empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['call_phone_number'] = ['Call Phone Number', 'call_phone_number', 'call-phone-number', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(20)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '20', '0', 'No', 'No', 'Example: 1-888-888-8888', '', '{}', $first_last_name, $first_last_name];

$parameters['call_phone_number_class'] = ['Call Phone Number Class', 'call_phone_number_class', 'call-phone-number-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['call_phone_number_text'] = ['Call Phone Number Text', 'call_phone_number_text', 'call-phone-number-text', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: Call us for a quote:', '', '{}', $first_last_name, $first_last_name];

$parameters['call_phone_number_text_class'] = ['Call Phone Number Text Class', 'call_phone_number_text_class', 'call-phone-number-text-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: call-text-for-quote', '', '{}', $first_last_name, $first_last_name];

$parameters['canonical_url'] = ['Canonical URL', 'canonical_url', 'canonical-url', 'textfield', 'canonicalUrl', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['cart_id'] = ['Cart ID', 'cart_id', 'cart-id', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'If the customer has a Cart ID, it will display here. When they have a Cart ID and they login, they will be able to see what they last had in their cart.', '', '{}', $first_last_name, $first_last_name];

$parameters['cf_display_as'] = ['Display As', 'cf_display_as', 'cf-display-as', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'custom_fields_display_as', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['cf_search_as'] = ['Search As', 'cf_search_as', 'cf-search-as', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'custom_fields_search_as', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['character_set_and_collate'] = ['Character Set & Collate', 'character_set_and_collate', 'character-set-and-collate', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'database_character_set_and_collate', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Select a "Character Set & Collate" if you\'re creating a field that will hold basic text as a string of numbers and charicters. If this field will hold integers, decimal, dates, etc., leave this field empty. This field is used to know how to create a database table column when a new database is created.', '', '{}', $first_last_name, $first_last_name];

$parameters['child_id'] = ['Child ID', 'child_id', 'child-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['child_id_table_name'] = ['Child ID Table Name', 'child_id_table_name', 'child-id-table-name', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['child_table_link_column'] = ['Child Table Link Column', 'child_table_link_column', 'child-table-link-column', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', 'Enter the column in the child table that links back to the parent table. In most cases, this column follows the format "parent_table_name_id", for example, "admin_menus_id.', '', '{}', $first_last_name, $first_last_name];

$parameters['child_table_name'] = ['Child Table Name', 'child_table_name', 'child-table-name', 'tableName', 'childTableName', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If this admin page will have Sub Items, select the child table where the Sub Item data will be stored. If the table does not exist, create it first under "Admin > Database Tables". Make sure the table includes a column named "site_id", as this column is required to build an admin page.', '', '{}', $first_last_name, $first_last_name];

$parameters['city'] = ['City', 'city', 'city', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['class'] = ['Class', 'class', 'class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Class field is used to identify which custom code should run when this admin page is requested. If you plan to create custom logic for this admin page, you can use this value to match against a variable in your code and determine when that logic should be executed.', '', '{}', $first_last_name, $first_last_name];

$parameters['click_path'] = ['Click Path', 'click_path', 'click-path', 'textfield', 'clickPath', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['collect_analytics_data'] = ['Collect Analytics Data', 'collect_analytics_data', 'collect-analytics-data', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'This will collect analytics data within this site\'s database if set to "Yes".', '', '{}', $first_last_name, $first_last_name];

$parameters['color_number'] = ['Color Number', 'color_number', 'color-number', 'textfield', 'colorNumber', 'Yes', 'Yes', '', '0', 'varchar(25)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '25', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['column_name'] = ['Column Name', 'column_name', 'column-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'You can leave this field empty. We will use the "Name" you enter above to create the database "Column Name". If you want to enter the "Column Name" the rules are: use all lowercase letters; use underscores for spaces. Example: new_column_name', '', '{}', $first_last_name, $first_last_name];

$parameters['columns'] = ['Columns', 'columns', 'columns', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['comment'] = ['Comment', 'comment', 'comment', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['comment_parent_id'] = ['Comment Parent ID', 'comment_parent_id', 'comment-parent-id', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['comments_block_links'] = ['Automatically Delete Comments with Links', 'comments_block_links', 'comments-block-links', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['comments_blocked_keywords'] = ['Automatically Delete Comments with These Keywords', 'comments_blocked_keywords', 'comments-blocked-keywords', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', 'Separate keywords with commas like so: keyword 1, keyword 2, keyword 3, etc.', '', '{}', $first_last_name, $first_last_name];

$parameters['content'] = ['Content', 'content', 'content', 'textfield', 'textareaWithEditor', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '<span class="color-f00"><strong>Note:</strong></span> We are currently working on a WYSIWYG editor so you won\'t need to manually write HTML for your content. For now, all posts, product descriptions, and other content must be formatted using HTML. We\'re working to have the editor ready soon, allowing you to simply type your content while the formatting is handled automatically in the background.', '', '{}', $first_last_name, $first_last_name];

$parameters['content_title'] = ['Content Title /  H1', 'content_title', 'content-title', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['cookie_ip_hash'] = ['Cookie IP Hash', 'cookie_ip_hash', 'cookie-ip-hash', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'char(32)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '32', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['cookie_notice_url'] = ['Cookie Policy Notice URL', 'cookie_notice_url', 'cookie-notice-url', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'display_urls_as_urls', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['country'] = ['Country', 'country', 'country', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'countries', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['created_by'] = ['Created By', 'created_by', 'created-by', 'textfield', 'createdBy', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['created_date'] = ['Created Date', 'created_date', 'created-date', 'dateRange', 'createdDate', 'Yes', 'Yes', '&#xf073;', '0', 'datetime', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['css_class'] = ['CSS Class', 'css_class', 'css-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If you created a CSS Class for this field, you can add that class here to improve the look and feel of the admin area. Example: if you selected "Date Range" for "Search As" above, you can put date-range in this field and it will make the "from" and "to" dates fields sit next to each in the table view header. If you selected "Media" or "Single Media" enter in media-center.', '', '{}', $first_last_name, $first_last_name];

$parameters['css_class_name'] = ['CSS Class Name', 'css_class_name', 'css-class-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['currency_type'] = ['Currency Type', 'currency_type', 'currency-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'currency_types', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'Example: USA dollars uses "USD"', '', '{}', $first_last_name, $first_last_name];

$parameters['custom_field_name'] = ['Custom Field Name', 'custom_field_name', 'custom-field-name', 'textfield', 'customFieldName', 'Yes', 'Yes', '', 'languages', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['custom_fields'] = ['Custom Fields', 'custom_fields', 'custom-fields', 'textfield', 'textfield', 'No', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['custom_fields_id'] = ['Custom Fields ID', 'custom_fields_id', 'custom-fields-id', 'textfield', 'parentTableId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['custom_link'] = ['Custom Link', 'custom_link', 'custom-link', 'textfield', 'customLink', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['data_length'] = ['Data Length Front', 'data_length', 'data-length', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'No', 'Data Length Front is used to indicate how many characters this field can handle. If you selected varchar(25), you would enter 25 here. If you select decimal(16,6), you should enter 10. For decimals, you subtract the second number from the first number leaving it with 10. If you selected text, you should enter 65535. If you selected longtext, you should enter 2147483647. If you selected date, datetime, or int, you should enter 0.', '', '{}', $first_last_name, $first_last_name];

$parameters['data_length_back'] = ['Data Length Back', 'data_length_back', 'data-length-back', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'No', 'Data Length Back is only used if you select "decimal" for "Data Type" above. If you selected "decimal(16,6)" this means you would enter 6 here. Enter the last number of the decimal value if decimal was selected. If you did not select decimal, enter 0.', '', '{}', $first_last_name, $first_last_name];

$parameters['data_type'] = ['Data Type', 'data_type', 'data-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_fields_data_type', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['database_table_name'] = ['Database Table Name', 'database_table_name', 'database-table-name', 'dropdownValue', 'databaseTableName', 'Yes', 'Yes', '', 'database_table_names', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['days_of_the_week_open'] = ['Days Of The Week Open', 'days_of_the_week_open', 'days-of-the-week-open', 'textfield', 'daysOfTheWeekOpen', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'These are the hours that search engines will display for when you\'re open or closed. This is for phone calls plus visitors to a store address location.', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_blocked_ips'] = ['Log of IP Addresses Blocked for Hitting Max Pagesviews in Time Period', 'ddos_blocked_ips', 'ddos-blocked-ips', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '<span class="color-f00"><strong>Important: </strong></span>The IP Addresses in this list are not used to block visitors from visiting the site. This is just a log so you can see what was automatically added to "Blocked IP Addresses" at the top of this page. Once you have confirmed that these IP Addresses are okay to be blocked, you can empty this log. If you determine that an IP Address should not be blocked, remove it from "Blocked IP Addresses" at the top of this page.', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_email_bcc'] = ['BCC Email Addresses', 'ddos_email_bcc', 'ddos-email-bcc', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Separate email addresses with ";". <span class="color-f00"><strong>Important:</strong></span> Some hosting companies will send BCC emails to imunify/quarantine and they will not be delivered. If you notice BCC emails not being delivered and your domain / hosting IP Address does not have a bad reputation for email spam, contact your hosting company to see if BCC email are being quarantined and not sending from your server.', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_email_cc'] = ['CC Email Addresses', 'ddos_email_cc', 'ddos-email-cc', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Separate email addresses with ";".', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_email_from'] = ['From Email Address', 'ddos_email_from', 'ddos-email-from', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_email_from_name'] = ['From Name', 'ddos_email_from_name', 'ddos-email-from-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Name of person or team the email is from.', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_email_password'] = ['Email Login Password', 'ddos_email_password', 'ddos-email-password', 'textfield', 'password', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '<span class="color-f00"><strong>Important:</strong></span> Leave blank to keep existing value. Also, if you are running email through a connector or relay and have whitelisted this sites IP, leave the username/password empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_email_server_port'] = ['Email Server Port Number', 'ddos_email_server_port', 'ddos-email-server-port', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '10', '0', 'No', 'No', 'This is typically "587" which is secure or port "25" that is not secure.', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_email_server_url'] = ['Email Server URL', 'ddos_email_server_url', 'ddos-email-server-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'This is typically something like mail.your_domain_name.com', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_email_username'] = ['Email Login Username', 'ddos_email_username', 'ddos-email-username', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'This is typically the email address you\'re sending from. Note: If you are running email through a connector or relay and have whitelisted this sites IP, leave the username/password empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['ddos_to_name'] = ['To Name', 'ddos_to_name', 'ddos-to-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', 'Name of person or team the email will be sending to.', '', '{}', $first_last_name, $first_last_name];

$parameters['default_admin_page'] = ['Default Admin Page', 'default_admin_page', 'default-admin-page', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'admin_pages_urls_id_as_value', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', 'The Default Admin Page is where the user will be redirected after logging in. Be sure to set permissions for the Default Admin Page URL below or the admin page will not load when the admin user logs in.', '', '{}', $first_last_name, $first_last_name];

$parameters['default_file'] = ['Default File', 'default_file', 'default-file', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['default_file_icon'] = ['Default File Icon', 'default_file_icon', 'default-file-icon', 'textfield', 'singleMedia', 'Yes', 'Yes', 'Media ID', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', 'When you add a file to a slider, product slider, etc., this is the icon that will display for the file.', '', '{}', $first_last_name, $first_last_name];

$parameters['default_or_custom'] = ['Default Or Custom', 'default_or_custom', 'default-or-custom', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['default_video_icon'] = ['Default Video Icon', 'default_video_icon', 'default-video-icon', 'textfield', 'singleMedia', 'Yes', 'Yes', 'Media ID', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', 'When you add a video to a slider, product slider, etc., this is the icon that will display for the video.', '', '{}', $first_last_name, $first_last_name];

$parameters['desktop_media'] = ['Desktop Media', 'desktop_media', 'desktop-media', 'textfield', 'singleMedia', 'Yes', 'Yes', 'Media ID', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', 'media-center', '{}', $first_last_name, $first_last_name];

$parameters['directory_folder_name'] = ['Directory Folder Name', 'directory_folder_name', 'directory-folder-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display'] = ['Display', 'display', 'display', 'dropdownId', 'displayColorSwatch', 'Yes', 'Yes', '', 'custom_field_options_display_color_swatch', 'varchar(20)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '20', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_as'] = ['Display As', 'display_as', 'display-as', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_fields_display_as', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_as_slider'] = ['Display as Slider', 'display_as_slider', 'display-as-slider', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_breadcrumbs'] = ['Display Breadcrumbs', 'display_breadcrumbs', 'display-breadcrumbs', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'No', 'Breadcrumbs are the links at the top of most pages that show the path to the page you\'re on.', '', '{}', $first_last_name, $first_last_name];

$parameters['display_call_phone_number'] = ['Display Call Phone Number', 'display_call_phone_number', 'display-call-phone-number', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(20)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '20', '0', 'No', 'No', 'Example: (888) 888-8888', '', '{}', $first_last_name, $first_last_name];

$parameters['display_contact_info_on_site'] = ['Display Contact Information on Site', 'display_contact_info_on_site', 'display-contact-info-on-site', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_cookie_notice'] = ['Display Cookie Notice Banner', 'display_cookie_notice', 'display-cookie-notice', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'A banner will display at the bottom of every page letting the visitor know of your cookie policy. After they close the banner, the banner will no longer display.', '', '{}', $first_last_name, $first_last_name];

$parameters['display_in_admin'] = ['Display in Admin', 'display_in_admin', 'display-in-admin', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_last_updated'] = ['Display Last Updated Date', 'display_last_updated', 'display-last-updated', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_pagination'] = ['Display Pagination', 'display_pagination', 'display-pagination', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'Choose whether to display pagination (bullets or thumbnails). Select "Yes" to show it or "No" to hide it.', '', '{}', $first_last_name, $first_last_name];

$parameters['display_post_in'] = ['Display Post In', 'display_post_in', 'display-post-in', 'textfield', 'displayPostIn', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_posted_on'] = ['Display Posted On Date', 'display_posted_on', 'display-posted-on', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_posts'] = ['Display Blog Posts', 'display_posts', 'display-posts', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'No', 'If you\'re using a blog category template, you can choose whether to display the blog roll (posts assigned to this category) when the page loads on the frontend. Select "No" to hide the blog posts and create a fully custom top-level blog category page.', '', '{}', $first_last_name, $first_last_name];

$parameters['display_sms_phone_number'] = ['Display SMS Phone Number', 'display_sms_phone_number', 'display-sms-phone-number', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(20)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '20', '0', 'No', 'No', 'Example: (888) 888-8888', '', '{}', $first_last_name, $first_last_name];

$parameters['display_text_from_sub_items'] = ['Display Text From Sub Items', 'display_text_from_sub_items', 'display-text-from-sub-items', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['display_thumbnails'] = ['Display Thumbnails', 'display_thumbnails', 'display-thumbnails', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'If using pagination, select "Yes" to show image thumbnails, or "No" for bullets.', '', '{}', $first_last_name, $first_last_name];

$parameters['domain'] = ['Domain - TLD', 'domain', 'domain', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['dynamic'] = ['Dynamic', 'dynamic', 'dynamic', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['dynamic_column_id'] = ['Dynamic Column ID', 'dynamic_column_id', 'dynamic-column-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If you selected "Yes" to the field of "Dynamic" above, select the table column name that you would like to use as the ID for the dropdown option.', '', '{}', $first_last_name, $first_last_name];

$parameters['dynamic_column_label'] = ['Dynamic Column Label', 'dynamic_column_label', 'dynamic-column-label', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If you selected "Yes" to the field of "Dynamic" above, select the table column name that you would like to use as the Label for the dropdown option.', '', '{}', $first_last_name, $first_last_name];

$parameters['dynamic_table_name'] = ['Dynamic Table Name', 'dynamic_table_name', 'dynamic-table-name', 'tableName', 'tableName', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If you selected "Yes" to the field of "Dynamic" above, select the table this Dynamic field will connect to.', '', '{}', $first_last_name, $first_last_name];

$parameters['edit_url'] = ['Edit URL', 'edit_url', 'edit-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'The Edit URL is used to edit existing records. If you enter a value in this field, you must also create a corresponding admin page for this URL. Do not include the admin directory name, do not use leading or trailing slashes, and use lowercase letters only. Use dashes instead of spaces and slashes to separate directories. This URL must always end with "/edit", as the system relies on this format. Example: content/menus/edit', '', '{}', $first_last_name, $first_last_name];

$parameters['email'] = ['Email', 'email', 'email', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['email_bcc'] = ['BCC Email Addresses', 'email_bcc', 'email-bcc', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Separate email addresses with ";". <span class="color-f00"><strong>Important:</strong></span> Some hosting companies will send BCC emails to imunify/quarantine and they will not be delivered. If you notice BCC emails not being delivered and your domain / hosting IP Address does not have a bad reputation for email spam, contact your hosting company to see if BCC email are being quarantined and not sending from your server.', '', '{}', $first_last_name, $first_last_name];

$parameters['email_cc'] = ['CC Email Addresses', 'email_cc', 'email-cc', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Separate email addresses with ";".', '', '{}', $first_last_name, $first_last_name];

$parameters['email_from'] = ['From Email Address', 'email_from', 'email-from', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['email_from_name'] = ['From Name', 'email_from_name', 'email-from-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Name of person or team the email is from.', '', '{}', $first_last_name, $first_last_name];

$parameters['email_password'] = ['Email Login Password', 'email_password', 'email-password', 'textfield', 'password', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '<span class="color-f00"><strong>Important:</strong></span> Leave blank to keep existing value. Also, if you are running email through a connector or relay and have whitelisted this sites IP, leave the username/password empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['email_server_port'] = ['Email Server Port Number', 'email_server_port', 'email-server-port', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '10', '0', 'No', 'No', 'This is typically "587" which is secure or port "25" that is not secure.', '', '{}', $first_last_name, $first_last_name];

$parameters['email_server_url'] = ['Email Server URL', 'email_server_url', 'email-server-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'This is typically something like mail.your_domain_name.com', '', '{}', $first_last_name, $first_last_name];

$parameters['email_signiture'] = ['Email Signiture', 'email_signiture', 'email-signiture', 'textfield', 'textareaWithEditor', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', '<span class="color-f00"><strong>Note:</strong></span> We are currently working on a WYSIWYG editor so you won\'t need to manually write HTML for your content. For now, all posts, product descriptions, and other content must be formatted using HTML. We\'re working to have the editor ready soon, allowing you to simply type your content while the formatting is handled automatically in the background. This email signature will be added to emails sent for product questions you answer within the admin area.', '', '{}', $first_last_name, $first_last_name];

$parameters['email_username'] = ['Email Login Username', 'email_username', 'email-username', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'This is typically the email address you\'re sending from. Note: If you are running email through a connector or relay and have whitelisted this sites IP, leave the username/password empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['emailed'] = ['Emailed', 'emailed', 'emailed', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['embed_custom_field'] = ['Embed Custom Field', 'embed_custom_field', 'embed-custom-field', 'textfield', 'embedCustomField', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['embed_form'] = ['Embed Form', 'embed_form', 'embed-form', 'textfield', 'embedForm', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['embed_media'] = ['Embed Media', 'embed_media', 'embed-media', 'textfield', 'embedMedia', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['embed_menu'] = ['Embed Menu', 'embed_menu', 'embed-menu', 'textfield', 'embedMenu', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['embed_slider'] = ['Embed Slider in Template Code', 'embed_slider', 'embed-slider', 'textfield', 'embedSlider', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['end_row_id'] = ['End Row ID', 'end_row_id', 'end-row-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'Some tables can become large and you may have to break up the rows to export. Enter an ending row ID that you would like to stop exporting on. Leave empty to export all rows in database table.', '', '{}', $first_last_name, $first_last_name];

$parameters['event_name'] = ['Event Name', 'event_name', 'event-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['event_value'] = ['Event Value', 'event_value', 'event-value', 'price', 'price', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '4', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['exchange_rate_difference'] = ['Exchange Rate Difference', 'exchange_rate_difference', 'exchange-rate-difference', 'price', 'price', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'Yes', 'For inventory to sell at the price on an inventory item, enter 1.00. If the price on your inventory was 100.00 USD and you need to increase it for another currency by 20%, you would enter 1.20. If you need to decease by 20% you would enter 0.80.', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_address'] = ['Email Address to Send Notice of Failed Login Blocks', 'failed_login_attempts_email_address', 'failed-login-attempts-email-address', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', 'Separate email addresses with ";".', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_bcc'] = ['BCC Email Addresses', 'failed_login_attempts_email_bcc', 'failed-login-attempts-email-bcc', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Separate email addresses with ";". <span class="color-f00"><strong>Important:</strong></span> Some hosting companies will send BCC emails to imunify/quarantine and they will not be delivered. If you notice BCC emails not being delivered and your domain / hosting IP Address does not have a bad reputation for email spam, contact your hosting company to see if BCC email are being quarantined and not sending from your server.', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_cc'] = ['CC Email Addresses', 'failed_login_attempts_email_cc', 'failed-login-attempts-email-cc', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Separate email addresses with ";".', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_from'] = ['From Email Address', 'failed_login_attempts_email_from', 'failed-login-attempts-email-from', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_from_name'] = ['From Name', 'failed_login_attempts_email_from_name', 'failed-login-attempts-email-from-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Name of person or team the email is from.', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_me'] = ['Send Email When IP Address Gets Blocked for Max Customer Login Attempts', 'failed_login_attempts_email_me', 'failed-login-attempts-email-me', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_password'] = ['Email Login Password', 'failed_login_attempts_email_password', 'failed-login-attempts-email-password', 'textfield', 'password', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '<span class="color-f00"><strong>Important:</strong></span> Leave blank to keep existing value. Also, if you are running email through a connector or relay and have whitelisted this sites IP, leave the username/password empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_server_port'] = ['Email Server Port Number', 'failed_login_attempts_email_server_port', 'failed-login-attempts-email-server-port', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '10', '0', 'No', 'No', 'This is typically "587" which is secure or port "25" that is not secure.', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_server_url'] = ['Email Server URL', 'failed_login_attempts_email_server_url', 'failed-login-attempts-email-server-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'This is typically something like mail.your_domain_name.com', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_email_username'] = ['Email Login Username', 'failed_login_attempts_email_username', 'failed-login-attempts-email-username', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'This is typically the email address you\'re sending from. Note: If you are running email through a connector or relay and have whitelisted this sites IP, leave the username/password empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_attempts_to_name'] = ['To Name', 'failed_login_attempts_to_name', 'failed-login-attempts-to-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', 'Name of person or team the email will be sending to.', '', '{}', $first_last_name, $first_last_name];

$parameters['failed_login_blocked_ips'] = ['Log of IP Addresses Blocked from Failed login Attempts', 'failed_login_blocked_ips', 'failed-login-blocked-ips', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', 'This is only a log of what has been blocked in the past. You should empty this log every once and a while to prevent it from becoming too large.', '', '{}', $first_last_name, $first_last_name];

$parameters['favicon_16px_16px'] = ['Site Favicon (Use .png image 16px x 16px)', 'favicon_16px_16px', 'favicon-16px-16px', 'textfield', 'singleMedia', 'Yes', 'Yes', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['favicon_32px_32px'] = ['Site Favicon (Use .png image 32px x 32px)', 'favicon_32px_32px', 'favicon-32px-32px', 'textfield', 'singleMedia', 'Yes', 'Yes', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['favicon_180px_180px'] = ['Site Favicon (Use .png image 180px x 180px)', 'favicon_180px_180px', 'favicon-180px-180px', 'textfield', 'singleMedia', 'Yes', 'Yes', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['fetch_priority'] = ['Add High Fetch Priority to Image', 'fetch_priority', 'fetch-priority', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'If this is a large media file that appears near the top of the page, select Yes. If visitors must scroll to see it, select No. Selecting Yes applies fetchpriority="high", ensuring the media is fetched early during the page\'s initial load.', '', '{}', $first_last_name, $first_last_name];

$parameters['field_id'] = ['Field ID', 'field_id', 'field-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['field_section_name'] = ['Field Section Name', 'field_section_name', 'field-section-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['field_type'] = ['Custom Field Type', 'field_type', 'field-type', 'dropdownId', 'customFieldType', 'Yes', 'Yes', '', 'custom_fields_field_type', 'varchar(20)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '20', '0', 'No', 'Yes', 'Inventory Attributes and Product Options are global across the account, making them visible and usable on all sites within the account. In contrast, Content Fields are specific to each site and will only appear on the site where they were created.', '', '{}', $first_last_name, $first_last_name];

$parameters['file_code'] = ['File Code', 'file_code', 'file-code', 'textfield', 'fileCode', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['filename'] = ['Filename', 'filename', 'filename', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Be sure to include your file extension like .php, .html, .htm, .txt, .css, .js. Example: my-file.php', '', '{}', $first_last_name, $first_last_name];

$parameters['filter_attribute_ids'] = ['Filter Attribute IDs', 'filter_attribute_ids', 'filter-attribute-ids', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['financial_field'] = ['Financial Field', 'financial_field', 'financial-field', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'When configuring your currency, you specified how many digits appear after the fractional separator. For financial accuracy, fields used in financial reports should always use the full 6 digits (e.g., 0.000000) stored in the database to avoid rounding discrepancies. If this field will be used in financial records, select "Yes" to ensure all 6 digits are displayed and used in calculations for maximum precision.', '', '{}', $first_last_name, $first_last_name];

$parameters['first_name'] = ['First Name', 'first_name', 'first-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['flat_url'] = ['Flat URL', 'flat_url', 'flat-url', 'textfield', 'flatUrl', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['follow_up_date'] = ['Follow Up Date', 'follow_up_date', 'follow-up-date', 'dateRange', 'dateField', 'Yes', 'Yes', '&#xf073;', '0', 'date', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['form_auto_complete'] = ['Form Auto Complete', 'form_auto_complete', 'form-auto-complete', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'forms_auto_complete', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['form_conversion_value'] = ['Form Conversion Value', 'form_conversion_value', 'form-conversion-value', 'price', 'price', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '4', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['form_field_type'] = ['Form Field Type', 'form_field_type', 'form-field-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'forms_field_type', 'varchar(20)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '20', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['form_fields_id'] = ['Form Fields ID', 'form_fields_id', 'form-fields-id', 'textfield', 'parentTableId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['form_fields_ids'] = ['Form Fields IDs', 'form_fields_ids', 'form-fields-ids', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['form_id'] = ['Form ID', 'form_id', 'form-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['form_name_class'] = ['Form Name Class', 'form_name_class', 'form-name-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: form-1', '', '{}', $first_last_name, $first_last_name];

$parameters['forms_block_links'] = ['Automatically Mark Form Submissions with Links as Junk', 'forms_block_links', 'forms-block-links', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['forms_blocked_keywords'] = ['Automatically Mark Form Submissions with These Keywords as Junk', 'forms_blocked_keywords', 'forms-blocked-keywords', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', 'Separate keywords with commas like so: keyword 1, keyword 2, keyword 3, etc.', '', '{}', $first_last_name, $first_last_name];

$parameters['forms_frontend_name'] = ['Form Name', 'forms_frontend_name', 'forms-frontend-name', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Form name used to submit this lead.', '', '{}', $first_last_name, $first_last_name];

$parameters['forms_id'] = ['Forms ID', 'forms_id', 'forms-id', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['forms_pageviews'] = ['Form Pageviews', 'forms_pageviews', 'forms-pageviews', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', 'Enter the number of pages a visitor should visit for a form submission to not go into junk.', '', '{}', $first_last_name, $first_last_name];

$parameters['forms_time_on_site'] = ['Form Timer', 'forms_time_on_site', 'forms-time-on-site', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', 'Enter the number of seconds a visitor should be on the site for a form submission to not go into junk.', '', '{}', $first_last_name, $first_last_name];

$parameters['fractional_separator'] = ['Fractional Separator', 'fractional_separator', 'fractional-separator', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'currency_fractional_separator', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', 'Example: USA dollars uses a "." to separate dollars and cents', '', '{}', $first_last_name, $first_last_name];

$parameters['front_symbol'] = ['Front Symbol', 'front_symbol', 'front-symbol', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', 'Example: USA dollars uses a "$" in front of numbers', '', '{}', $first_last_name, $first_last_name];

$parameters['frontend_name'] = ['Frontend Name', 'frontend_name', 'frontend-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['frontend_name_class'] = ['Frontend Name Class', 'frontend_name_class', 'frontend-name-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: title', '', '{}', $first_last_name, $first_last_name];

$parameters['gap_between_items'] = ['Gap Between Items', 'gap_between_items', 'gap-between-items', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['global'] = ['Global', 'global', 'global', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', 'Select "Yes" if this admin page will have a single record that applies to all domains in the account. Select "No" if the page will have a separate record for each domain.', '', '{}', $first_last_name, $first_last_name];

$parameters['global_url_extension'] = ['Global URL Extension', 'global_url_extension', 'global-url-extension', 'textfield', 'globalUrlExtension', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['google_analytics_tag_id'] = ['Google Analytics G-Number / Tag ID', 'google_analytics_tag_id', 'google-analytics-tag-id', 'textfield', 'textfield', 'Yes', 'Yes', 'i.e. G-123456789', '0', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'No', 'When you enter a Google Analytics account number here, the site will start running Google Analytics code for tracking purposes. Additionally, it will push purchase values and lead form values to Google Analytics for revenue tracking.', '', '{}', $first_last_name, $first_last_name];

$parameters['grid_columns'] = ['Grid Columns', 'grid_columns', 'grid-columns', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'css_grid_columns', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['has_cart_contact_info'] = ['Has Cart Contact Info', 'has_cart_contact_info', 'has-cart-contact-info', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'tinyint(1)', '', 'No', 'No', 'No', '1', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['has_form_conversion'] = ['Has Form Conversion', 'has_form_conversion', 'has-form-conversion', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'tinyint(1)', '', 'No', 'No', 'No', '1', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['has_order'] = ['Has Order', 'has_order', 'has-order', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'tinyint(1)', '', 'No', 'No', 'No', '1', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['height'] = ['Height', 'height', 'height', 'textfield', 'height', 'Yes', 'Yes', '', '0', 'varchar(25)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '25', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['help_video_url'] = ['Help Video URL', 'help_video_url', 'help-video-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(512)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '512', '0', 'No', 'No', 'Help Video URL is used to link a help or tutorial video for this admin section. If you create a help video for this page, enter the full URL here so admin users can easily access it when needed.', '', '{}', $first_last_name, $first_last_name];

$parameters['hierarchy_url'] = ['Hierarchy URL', 'hierarchy_url', 'hierarchy-url', 'textfield', 'hierarchyUrl', 'Yes', 'Yes', '', '0', 'varchar(512)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '512', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['homepage'] = ['Load Homepage URL With', 'homepage', 'homepage', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'display_urls_as_meta_titles', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['hours'] = ['Hours', 'hours', 'hours', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['hreflang_url_id'] = ['hreflang - Core URL ID', 'hreflang_url_id', 'hreflang-url-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', 'If your site will be in a single language, leave this field blank. If you plan to have a multilingual, enter the URL ID for the main URL that will connect the pages across all languages. For example, if the URL ID for your English version of the page is 108, enter 108 in this field. If you create a Spanish version of the page, you would still enter 108 in this field for the Spanish page. The English URL ID (or your core language\'s URL ID) will connect all corresponding pages across different languages. This helps establish the hreflang tags in your page\'s code, allowing search engines to know which pages to serve based on language or country. If you navigate to the core language page in the admin, you can find the URL ID in the URL. It will appear like this: ?rid=108.', '', '{}', $first_last_name, $first_last_name];

$parameters['https_in_url'] = ['https in URL', 'https_in_url', 'https-in-url', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'If you change this setting from https to http some browsers will force the redirect to https still. Sometimes clearing your browser cache will allow the http version of the site to load but not always. In some browsers, once it knows the domain has an SSL Certificate the browser will keep forcing the redirect to https when you want http. This will cause an infinite loop error within the browser as you want http but the browser keeps sending it back to https. Once on https you should leave it on https so the browser doesn\'t have these redirect looping issues.', '', '{}', $first_last_name, $first_last_name];

$parameters['id'] = ['ID', 'id', 'id', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'Yes', 'Yes', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['import_into_table'] = ['Table to Import Data Into', 'import_into_table', 'import-into-table', 'tableName', 'tableName', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '<span class="color-f00"><strong>Important:</strong></span> Watch the help library video for a better understanding, if you have not done so already, for importing help. Importing data can destroy an entire table in the database if done incorrectly. You should back up your database/table before you ever important data in case something goes wrong. Make sure to select the correct table you want to import into. If you select the wrong table and have id and name in your csv file, and the wrong table also includes id and name, it will update the wrong table.

<strong>Rules for Inserting New Rows in the Database</strong><ol><li>All columns within the database table must be in your csv file.</li><li>All columns in your csv file must be in the same order as your database table.</li><li>The id column row must be left empty in your csv file. This tells the code to insert a new record and not update an existing row in the database.</li></ol><strong>Rules for Updating Existing Rows in the Database</strong><ol><li>The id column must exist and have the id of the database row you want to edit. When the id column row has an id number in it, it tells the importer to update that id row.</li><li>No need to include all columns when updating only. Only include the id column and columns you want to update.</li></ol>', '', '{}', $first_last_name, $first_last_name];

$parameters['in_form_thank_you'] = ['In Form Thank You', 'in_form_thank_you', 'in-form-thank-you', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Example:  Submitted successfully. Thank you!', '', '{}', $first_last_name, $first_last_name];

$parameters['in_form_thank_you_class'] = ['In Form Thank You Class', 'in_form_thank_you_class', 'in-form-thank-you-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: in-form-thank-you', '', '{}', $first_last_name, $first_last_name];

$parameters['include_in_html_sitemap'] = ['Include In HTML Sitemap', 'include_in_html_sitemap', 'include-in-html-sitemap', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['include_in_site_search'] = ['Display in Site Search', 'include_in_site_search', 'include-in-site-search', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['include_in_xml_sitemap'] = ['Include In XML Sitemap', 'include_in_xml_sitemap', 'include-in-xml-sitemap', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inner_css_box_styles'] = ['Inner CSS Box Styles', 'inner_css_box_styles', 'inner-css-box-styles', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['install_id'] = ['Install ID', 'install_id', 'install-id', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'char(32)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '32', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_allow_backorders'] = ['Inventory Allow Backorders', 'inventory_allow_backorders', 'inventory-allow-backorders', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_assigned'] = ['Inventory Assigned', 'inventory_assigned', 'inventory-assigned', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_assigned_to_product_status'] = ['Inventory Assigned To Product Status', 'inventory_assigned_to_product_status', 'inventory-assigned-to-product-status', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_attribute_value_ids'] = ['Inventory Attribute Value IDs', 'inventory_attribute_value_ids', 'inventory-attribute-value-ids', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_id'] = ['Inventory ID', 'inventory_id', 'inventory-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_price'] = ['Inventory Price', 'inventory_price', 'inventory-price', 'price', 'price', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_quantity_available'] = ['Inventory Quantity Available', 'inventory_quantity_available', 'inventory-quantity-available', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_sale_price'] = ['Inventory Sale Price', 'inventory_sale_price', 'inventory-sale-price', 'price', 'price', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_sale_price_from'] = ['Inventory Sale Price From', 'inventory_sale_price_from', 'inventory-sale-price-from', 'dateRange', 'dateFrom', 'Yes', 'Yes', '&#xf073;', '0', 'date', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_sale_price_to'] = ['Inventory Sale Price To', 'inventory_sale_price_to', 'inventory-sale-price-to', 'dateRange', 'dateTo', 'Yes', 'Yes', '&#xf073;', '0', 'date', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_ships_within'] = ['Inventory Ships Within', 'inventory_ships_within', 'inventory-ships-within', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_status'] = ['Inventory Status', 'inventory_status', 'inventory-status', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_track_quantity'] = ['Inventory Track Quantity', 'inventory_track_quantity', 'inventory-track-quantity', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['inventory_url'] = ['Inventory URL', 'inventory_url', 'inventory-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['ip_address'] = ['IP Address', 'ip_address', 'ip-address', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['is_auto_increment'] = ['Is Auto Increment', 'is_auto_increment', 'is-auto-increment', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['is_nullable'] = ['Is Nullable', 'is_nullable', 'is-nullable', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['is_primary_key'] = ['Is Primary Key', 'is_primary_key', 'is-primary-key', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['item_number'] = ['Item Number', 'item_number', 'item-number', 'textfield', 'itemNumber', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['item_status'] = ['Item Status', 'item_status', 'item-status', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['js_name'] = ['JavaScript Name', 'js_name', 'js-name', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'forms_javascript_names', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'JavaScript Name connects this admin page to functions or code that run via AJAX.', '', '{}', $first_last_name, $first_last_name];

$parameters['keyword'] = ['Keyword', 'keyword', 'keyword', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['label'] = ['Label', 'label', 'label', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['last_logged_in'] = ['Last Logged In', 'last_logged_in', 'last-logged-in', 'dateRange', 'updatedDate', 'Yes', 'Yes', '&#xf073;', '0', 'datetime', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['last_name'] = ['Last Name', 'last_name', 'last-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['last_seen'] = ['Last Seen', 'last_seen', 'last-seen', 'dateRange', 'updatedDate', 'Yes', 'No', '&#xf073;', '0', 'datetime', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['lazy_load_media'] = ['Lazy Load Media', 'lazy_load_media', 'lazy-load-media', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'If this media will load high in the page, select No, If the visitor will have to scroll to see this media, select Yes. This will make it so the media will not load on the initial page load. It will load as the visitor scrolls and gets close to the media. This will help your pages load faster for visitors as the browser will not have to load the media right away.', '', '{}', $first_last_name, $first_last_name];

$parameters['lazy_load_media_row'] = ['Row Number To Start Media Lazy Loading', 'lazy_load_media_row', 'lazy-load-media-row', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', 'If you do not want any media to lazy load in paginated results, enter 0. If you want media to start lazy loading on the 5th row of results, enter 5. The row number you enter should require the visitor to scroll a little for the lazy loading media to be visible in the browser.', '', '{}', $first_last_name, $first_last_name];

$parameters['lead'] = ['Lead', 'lead', 'lead', 'textfield', 'lead', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['lead_status'] = ['Lead Status', 'lead_status', 'lead-status', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'customer_leads_lead_status', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['leads_id'] = ['Leads ID', 'leads_id', 'leads-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['lead_value_amount'] = ['Lead Value Amount', 'lead_value_amount', 'lead-value-amount', 'price', 'price', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'No', 'If this lead is a quote request, enter its estimated lead value amount. This will help you sort and prioritize leads by their potential value later.', '', '{}', $first_last_name, $first_last_name];

$parameters['license_billing_line_items'] = ['Billing Line Items', 'license_billing_line_items', 'license-billing-line-items', 'textfield', 'licenseBillingLineItems', 'Yes', 'No', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['license_key'] = ['License Key', 'license_key', 'license-key', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(128)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '128', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['license_last_billing_date'] = ['Last Billing Date', 'license_last_billing_date', 'license-last-billing-date', 'dateRange', 'updatedDate', 'Yes', 'No', '&#xf073;', '0', 'datetime', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['license_next_billing_amount'] = ['Estimated Next Billing Amount', 'license_next_billing_amount', 'license-next-charge-amount', 'textfield', 'text', 'Yes', 'No', '', '0', 'decimal(16,6)', '', 'No', 'No', 'No', '10', '6', 'Yes', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['license_next_billing_date'] = ['Next Billing Date', 'license_next_billing_date', 'license-next-billing-date', 'dateRange', 'updatedDate', 'Yes', 'No', '&#xf073;', '0', 'datetime', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['license_status'] = ['Status', 'license_status', 'license-status', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['license_type'] = ['License Type', 'license_type', 'license-type', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['link_parameters'] = ['Add Link Parameters', 'link_parameters', 'link-parameters', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['link_target'] = ['Link Target', 'link_target', 'link-target', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'links_target_types', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['link_type'] = ['Link Type', 'link_type', 'link-type', 'dropdownId', 'linkType', 'Yes', 'Yes', '', 'urls_link_type', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['links_to'] = ['Links To', 'links_to', 'links-to', 'linksTo', 'linksTo', 'Yes', 'Yes', '', 'display_urls_as_urls', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['load_on'] = ['Load Site On', 'load_on', 'load-on', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'site_settings_load_site_on', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['load_pages_with_cached_results'] = ['Load Pages With Cached Results', 'load_pages_with_cached_results', 'load-pages-with-cached-results', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'Enabling this feature will generate static files for your webpages on the server. It will not cache files when forms are submitted, nor will it cache files when a URL contains a "?" or "#", as these indicate variations of the main webpage. All static file caches will be stored in a directory named "cache" on the server. To delete all cached files, simply remove this cache folder; it will be recreated automatically when the next cache is generated from the webpage loading in a browser. When you\'re logged into the admin area, pages will load directly from the database instead of using the cache. This allows you to see the most up-to-date version of the page as it will appear once the cache is refreshed.', '', '{}', $first_last_name, $first_last_name];

$parameters['manufacturer'] = ['Manufacturer', 'manufacturer', 'manufacturer', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['max_pageviews_block'] = ['Max Pageviews for an IP Address', 'max_pageviews_block', 'max-pageviews-block', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', 'Enter the number of pageviews a visitor can visit before blocking them/send yourself an email/both. A good number is around 500.', '', '{}', $first_last_name, $first_last_name];

$parameters['masked_ip_hash'] = ['Masked IP Address', 'masked_ip_hash', 'masked-ip-address', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'varchar(64)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '64', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['media'] = ['Media', 'media', 'media', 'textfield', 'multipleMedia', 'Yes', 'Yes', 'Media ID', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', '', 'media-center', '{}', $first_last_name, $first_last_name];

$parameters['media_id'] = ['Media ID', 'media_id', 'media-id', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['media_size'] = ['Media Size', 'media_size', 'media-size', 'textfield', 'hiddenOnAddTextfieldOnEdit', 'Yes', 'Yes', '', '0', 'varchar(20)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '20', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['media_tag'] = ['Media Tag', 'media_tag', 'media-tag', 'textfield', 'mediaTag', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['media_type'] = ['Media Type', 'media_type', 'media-type', 'dropdownValue', 'mediaType', 'Yes', 'Yes', '', 'media_types', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['media_url'] = ['Media URL', 'media_url', 'media-url', 'textfield', 'mediaUrl', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', 'media-library', '{}', $first_last_name, $first_last_name];

$parameters['menu_locations'] = ['Menu Locations', 'menu_locations', 'menu-locations', 'dropdownId', 'checkboxId', 'Yes', 'Yes', '', 'admin_pages_urls_id_as_value', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Select the admin pages you want this menu to display on.', '', '{}', $first_last_name, $first_last_name];

$parameters['menu_type'] = ['Menu Type', 'menu_type', 'menu-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_menu_type', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['menus_id'] = ['Menus ID', 'menus_id', 'menus-id', 'textfield', 'parentTableId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['meta_description'] = ['Meta Description', 'meta_description', 'meta-description', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['meta_keywords'] = ['Meta Keywords', 'meta_keywords', 'meta-keywords', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['meta_robots'] = ['Meta Robots', 'meta_robots', 'meta-robots', 'dropdownId', 'metaRobots', 'Yes', 'Yes', '', 'meta_robots', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Be very careful. The Meta Robots tag tells search engines if you want them to crawl your site or not so you can rank for search engine traffic.', '', '{}', $first_last_name, $first_last_name];

$parameters['meta_title'] = ['Meta Title', 'meta_title', 'meta-title', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['microsoft_advertising_tag_id'] = ['Microsoft Advertising Tag ID', 'microsoft_advertising_tag_id', 'microsoft-advertising-tag-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'No', 'When you enter a Microsoft Advertising account number here, the site will start pushing purchase values to Microsoft Ads.', '', '{}', $first_last_name, $first_last_name];

$parameters['mobile_media'] = ['Mobile Media', 'mobile_media', 'mobile-media', 'textfield', 'singleMedia', 'Yes', 'Yes', 'Media ID', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', '', 'media-center', '{}', $first_last_name, $first_last_name];

$parameters['name'] = ['Name', 'name', 'name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['name_class'] = ['Frontend Name Class', 'name_class', 'name-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: text', '', '{}', $first_last_name, $first_last_name];

$parameters['name_in_title_tag'] = ['Add Site Name In Title Tag', 'name_in_title_tag', 'name-in-title-tag', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['new_url'] = ['New URL', 'new_url', 'new-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Do not add a slash or extension (i.e. html) to the front or back of the URL path. Example: category-name/new-product', '', '{}', $first_last_name, $first_last_name];

$parameters['no_record_url'] = ['No Record URL', 'no_record_url', 'no-record-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'No Record URL is used to redirect an admin user back to a main page when a requested record does not exist. For example, if an admin user visits "website/menus/edit?rid=1000" and record ID "1000" is not found in the database, the user will be redirected to this URL. Do not include the admin directory name, do not use leading or trailing slashes, and use lowercase letters only. Use dashes instead of spaces and slashes to separate directories. Example: content/menus', '', '{}', $first_last_name, $first_last_name];

$parameters['notes'] = ['Notes', 'notes', 'notes', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['notice_message'] = ['Notice Message', 'notice_message', 'notice-message', 'textfield', 'textareaWithEditor', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'Yes', '<span class="color-f00"><strong>Note:</strong></span> We are currently working on a WYSIWYG editor so you won\'t need to manually write HTML for your content. For now, all posts, product descriptions, and other content must be formatted using HTML. We\'re working to have the editor ready soon, allowing you to simply type your content while the formatting is handled automatically in the background.', '', '{}', $first_last_name, $first_last_name];

$parameters['notice_software_version'] = ['Notice Software Version', 'notice_software_version', 'notice-software-version', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['notice_subject'] = ['Notice Subject', 'notice_subject', 'notice-subject', 'textfield', 'textfield', 'Yes', 'No', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['notice_update_software'] = ['Notice Update Software', 'notice_update_software', 'notice-update-software', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['notice_upgrade_from'] = ['Notice Upgrade From', 'notice_upgrade_from', 'notice-upgrade-from', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['notice_upgrade_to'] = ['Notice Upgrade To', 'notice_upgrade_to', 'notice-upgrade-to', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['notice_url'] = ['Notice URL', 'notice_url', 'notice-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(512)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '512', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['number_of_failed_login_attempts'] = ['Number of Failed Customer Login Attempts That Can Be Made Within 1 Hour Period', 'number_of_failed_login_attempts', 'number-of-failed-login-attempts', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['number_of_posts'] = ['Number of Posts', 'number_of_posts', 'number-of-posts', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'If the template for this record displays Posts, this controls how many to display per page.', '', '{}', $first_last_name, $first_last_name];

$parameters['number_of_sitemap_results'] = ['Number of Sitemap Results', 'number_of_sitemap_results', 'number-of-sitemap-results', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'If the template for this record is a HTML Sitemap, this controls how many results to display per page.', '', '{}', $first_last_name, $first_last_name];

$parameters['old_url'] = ['Old URL', 'old_url', 'old-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Do not add a slash or extension (i.e. html) to the front or back of the URL path. Example: category-name/old-product', '', '{}', $first_last_name, $first_last_name];

$parameters['one_record'] = ['Single Page Form', 'one_record', 'one-record', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'Select "Yes" if this admin page will be a single-page form. Select "No" if the page will contain multiple rows of records.', '', '{}', $first_last_name, $first_last_name];

$parameters['option_cost'] = ['Option Cost', 'option_cost', 'option-cost', 'price', 'customFieldsOptionCost', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['option_data'] = ['Option Data', 'option_data', 'option-data', 'textfield', 'optionData', 'Yes', 'Yes', '', 'languages', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['option_price'] = ['Option Price', 'option_price', 'option-price', 'price', 'customFieldsOptionPrice', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['orders_id'] = ['Orders ID', 'orders_id', 'orders-id', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['original_media'] = ['Original Media', 'original_media', 'original-media', 'dropdownValue', 'originalMedia', 'Yes', 'No', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['original_media_id'] = ['Original Media ID', 'original_media_id', 'original-media-id', 'textfield', 'originalMediaId', 'Yes', 'No', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['other_business_urls'] = ['Other Business URLs', 'other_business_urls', 'other-business-urls', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', 'Add other business URLs with this structure: "facebook_url","twitter_url","youtube_url" - Use double quotes around each URL and separate them with commas.', '', '{}', $first_last_name, $first_last_name];

$parameters['outter_css_box_styles'] = ['Outter CSS Box Styles', 'outter_css_box_styles', 'outter-css-box-style', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['pages_groups_id'] = ['Pages Groups ID', 'pages_groups_id', 'pages-groups-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['pages_not_to_cache'] = ['Pages Not To Cache', 'pages_not_to_cache', 'pages-not-to-cache', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', 'Separate URLs not to cache with commas like so: account, addresses, account/addresses, add-address, account/addresses/add-address, edit-address, account/addresses/edit-address, affiliate, account/affiliate, cards-on-file, account/cards-on-file, add-card, account/cards-on-file/add-card, edit-card, account/cards-on-file/edit-card, invoice, account/invoice, license-keys, account/license-keys, orders, account/orders, order-details, account/orders/order-details, profile, account/profile, receipt, account/receipt, subscriptions, account/subscriptions, cancel-order, cart, checkout, order-confirmation, reset-password, robots<br><span class="color-f00"><strong>Important: </strong></span> When caching is enabled, the URLs listed above will never be cached as they need to display unique data for each user. For example, caching the cart page would result in displaying the last cached cart items, which would belong to another user. This would prevent visitors from seeing their own cart items. Ensure these example URLs remain in this textarea if you turn caching on as these example URLs should never be cached. Also, if you ever change any of these default URLs, you must update this list to the new URL you changed it to.', '', '{}', $first_last_name, $first_last_name];

$parameters['pageview_hash'] = ['Pageview Hash', 'pageview_hash', 'pageview-hash', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'char(32)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '32', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['pageview_url'] = ['Pageview URL', 'pageview_url', 'pageview-url', 'textfield', 'text', 'Yes', 'No', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['pageviews'] = ['Pageviews', 'pageviews', 'pageviews', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', 'The number of pages this user visited before submitting this lead.', '', '{}', $first_last_name, $first_last_name];

$parameters['pagination_alignment'] = ['Pagination Alignment', 'pagination_alignment', 'pagination-alignment', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'sliders_pager_alignments', 'varchar(25)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '25', '0', 'No', 'Yes', 'If displaying pagination, select its alignment: Left, Center, or Right.', '', '{}', $first_last_name, $first_last_name];

$parameters['pagination_margin'] = ['Pagination Margin', 'pagination_margin', 'pagination-margin', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'If displaying pagination, set the margin between each bullet or thumbnail in pixels (e.g., "5" for 5px).', '', '{}', $first_last_name, $first_last_name];

$parameters['pagination_over_image'] = ['Pagination Over Image', 'pagination_over_image', 'pagination-over-image', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'Select "Yes" to overlay pagination on top of the images, or "No" to place it below the images.', '', '{}', $first_last_name, $first_last_name];

$parameters['pagination_thumbnail_width'] = ['Pagination Thumbnail Width', 'pagination_thumbnail_width', 'pagination-thumbnail-width', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'Set the width for thumbnails in pagination (e.g., enter "60" for 60px wide thumbnails).', '', '{}', $first_last_name, $first_last_name];

$parameters['parent_id'] = ['Parent ID', 'parent_id', 'parent-id', 'textfield', 'parentId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['parent_id_table_name'] = ['Parent ID Table Name', 'parent_id_table_name', 'parent-id-table-name', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['parent_indicator'] = ['Parent Indicator', 'parent_indicator', 'parent-indicator', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'The Parent Indicator flag tells the system to first look for parent items and then for child items under them. For example, menu items first fetch all items with a parent ID of 0, then fetch any items under those parent IDs. In most cases, select "No" unless you are writing custom code that uses this behavior.', '', '{}', $first_last_name, $first_last_name];

$parameters['parent_table_link_column'] = ['Parent Table Link Column', 'parent_table_link_column', 'parent-table-link-column', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', 'Enter the column in the parent table that links to related rows in child or associated tables. In most cases, this column is "id". If the parent table is associated with URLs, the column is usually "urls_id".', '', '{}', $first_last_name, $first_last_name];

$parameters['parent_table_name'] = ['Parent Table Name', 'parent_table_name', 'parent-table-name', 'tableName', 'parentTableName', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If this admin page is a Sub Items page, select the parent table in this field. If the parent table does not exist, create it first under "Admin > Database Tables". Make sure the table includes a column named "site_id", as this column is required to build an admin page.', '', '{}', $first_last_name, $first_last_name];

$parameters['password'] = ['Password', 'password', 'password', 'textfield', 'passwordAndConfirmPassword', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '<span class="color-f00"><strong>Important:</strong></span> Leave blank to keep existing value.', '', '{}', $first_last_name, $first_last_name];

$parameters['path_level'] = ['URL Sub Items', 'path_level', 'path-level', 'textfield', 'hidden', 'No', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['pause_time'] = ['Pause Time', 'pause_time', 'pause-time', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', 'Set the delay between auto-slide transitions in milliseconds. For example, enter "8000" for 8 seconds or "12000" for 12 seconds.', '', '{}', $first_last_name, $first_last_name];

$parameters['phone_number'] = ['Phone Number', 'phone_number', 'phone-number', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'Yes', 'This phone number will display on the frontend of the site plus display on search engines for the number to contact you.', '', '{}', $first_last_name, $first_last_name];

$parameters['phone_number_ext'] = ['Phone Number Ext', 'phone_number_ext', 'phone-number-ext', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['php_array'] = ['PHP Array For Template', 'php_array', 'php-array', 'textfield', 'phpArrayForTemplate', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Copy this template code into your file code below to see all available data to build the page.', '', '{}', $first_last_name, $first_last_name];

$parameters['placeholder'] = ['Placeholder', 'placeholder', 'placeholder', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['post_url_id'] = ['Post URL', 'post_url_id', 'post-url-id', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['postal_code'] = ['Postal Code', 'postal_code', 'postal-code', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['privacy_notice_url'] = ['Privacy Policy Notice URL', 'privacy_notice_url', 'privacy-notice-url', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'display_urls_as_urls', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['product_price'] = ['Product Price', 'product_price', 'product-price', 'price', 'price', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['product_review_score'] = ['Product Review Score', 'product_review_score', 'product-review-score', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'decimal(2,1)', '', 'Yes', 'No', 'No', '2', '1', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['product_sale_price'] = ['Product Sale Price', 'product_sale_price', 'product-sale-price', 'price', 'price', 'Yes', 'Yes', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['product_sale_price_from'] = ['Product Sale Price From', 'product_sale_price_from', 'product-sale-price-from', 'dateRange', 'dateFrom', 'Yes', 'Yes', '&#xf073;', '0', 'date', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['product_sale_price_to'] = ['Product Sale Price To', 'product_sale_price_to', 'product-sale-price-to', 'dateRange', 'dateTo', 'Yes', 'Yes', '&#xf073;', '0', 'date', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['record_id'] = ['Record ID', 'record_id', 'record-id', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['redirect_to_opposite_url'] = ['Redirect To Opposite URL', 'redirect_to_opposite_url', 'redirect-to-opposite-url', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['redirect_type'] = ['Redirect Type', 'redirect_type', 'redirect-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'urls_redirect_types', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['referer_source'] = ['Referrer Source', 'referer_source', 'referer-source', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['referer_url'] = ['Referrer URL', 'referer_url', 'referer-url', 'textfield', 'text', 'Yes', 'No', '', '0', 'varchar(512)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '512', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['required'] = ['Required', 'required', 'required', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['required_mysql_version'] = ['Required MySQL Version', 'required_mysql_version', 'required-mysql-version', 'textfield', 'textfield', 'Yes', 'No', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['required_php_version'] = ['Required PHP Version', 'required_php_version', 'required-php-version', 'textfield', 'textfield', 'Yes', 'No', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['reset_expires'] = ['Reset Expires', 'reset_expires', 'reset-expires', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['reset_selector'] = ['Reset Selector', 'reset_selector', 'reset-selector', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['reset_token'] = ['Reset Token', 'reset_token', 'reset-token', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['save_url'] = ['Save URL', 'save_url', 'save-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Save URL is used to determine where an admin user is redirected after adding a new record or saving changes on an edit page. Do not include the admin directory name, do not use leading or trailing slashes, and use lowercase letters only. Use dashes instead of spaces and slashes to separate directories. Example to stay on the edit page: "content/menus/edit". Example to stay on the add page: "content/menus/add". Example to return to the main admin page: content/menus', '', '{}', $first_last_name, $first_last_name];

$parameters['scheduled_date'] = ['Scheduled Date', 'scheduled_date', 'scheduled-date', 'dateRange', 'scheduledDate', 'Yes', 'Yes', '&#xf073;', '0', 'date', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['search_as'] = ['Search As', 'search_as', 'search-as', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_fields_search_as', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['seconds_between_cache_refreshing'] = ['Seconds Between Cache Refreshing', 'seconds_between_cache_refreshing', 'seconds-between-cache-refreshing', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', 'Enter the number of seconds after which a page is deemed outdated and needs to be re-cached. For instance, 60 seconds * 60 minutes * 4 hours equals 14400 seconds. If you input 14400, your page\'s cache will refresh every 4 hours when it is requested in a browser.', '', '{}', $first_last_name, $first_last_name];

$parameters['select_file_to_import'] = ['CSV File with Delimiters as Commas to Import', 'select_file_to_import', 'select-file-to-import', 'textfield', 'selectFile', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Make sure your file is a .csv file with delimiters as commas. Watch the help library video for a better understanding, if you have not done so already, for importing help.', '', '{}', $first_last_name, $first_last_name];

$parameters['seo_keyword'] = ['SEO Keyword', 'seo_keyword', 'seo-keyword', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['seo_score'] = ['SEO Score', 'seo_score', 'seo-score', 'textfield', 'seoScore', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['site_allowed_ips'] = ['Allowed IP Addresses - Site Will Always Load For Entered IP Addresses', 'site_allowed_ips', 'site-allowed-ips', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', 'To allow a specific IP address, enter all four octets like "1.1.1.1, 2.2.2.2, 3.3.3.3, etc." seperating each IP with a comma.', '', '{}', $first_last_name, $first_last_name];

$parameters['site_blocked_ips'] = ['Blocked IP Addresses - Site Will Not Load for Entered IP Addresses', 'site_blocked_ips', 'site-blocked-ips', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', 'To block a specific IP address, enter all four octets like "1.1.1.1, 2.2.2.2, 3.3.3.3, etc." seperating each IP with a comma. To block an entire C-block (all IPs sharing the first three octets), enter it as "1.1.1." with a trailing dot. Make sure to include the dot at the end - otherwise you may unintentionally block a much broader range of IPs that start with the same numbers.<br><span class="color-f00"><strong>Important:</strong></span> Before blocking an IP address, make sure it does not belong to a legitimate search engine or AI crawler that you want to allow for search traffic. You can review official IP ranges for common services here: <a href="https://developers.google.com/static/crawling/ipranges/common-crawlers.json" target="_blank">Googlebot IPs</a>, <a href="https://developers.google.com/static/crawling/ipranges/special-crawlers.json" target="_blank">Google AdsBot IPs</a>, and <a href="https://www.bing.com/toolbox/bingbot.json" target="_blank">Bing IPs</a>. You can also use IP lookup tools to identify the owner of an IP address before deciding to block it.', '', '{}', $first_last_name, $first_last_name];

$parameters['site_email'] = ['Site Email Address', 'site_email', 'site-email', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Be sure to enter an email address for this domain name that you\'re editing. Not doing so will make any emails you send out, from the admin area, go to the recipient\'s junk inbox.', '', '{}', $first_last_name, $first_last_name];

$parameters['site_id'] = ['Site ID', 'site_id', 'site-id', 'textfield', 'siteId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['site_id_to_export'] = ['Site To Export', 'site_id_to_export', 'site-id-to-export', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'all_sites_in_account', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'To export data for all sites, don\'t select a Site.', '', '{}', $first_last_name, $first_last_name];

$parameters['site_language'] = ['Site Language', 'site_language', 'site-language', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'languages', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'This attribute tells screen readers, search engines, and other tools what language the website is in. For example, if the site is in English, it will appear in the code as &lt;html lang="en"&gt;. For a multilingual site, this will also be used for the hreflang language-country code. If your main site is in English for the United States but you also want to target English speakers in Great Britain, you should create the default "en" site for the general English version. Then, create a separate site for the "en-GB" version for Great Britain. Search engines will serve the "en" version to all countries that speak English, except for Great Britain, where the "en-GB" version will be shown. You can see all langue codes here: https://developers.google.com/admin-sdk/directory/v1/languages', '', '{}', $first_last_name, $first_last_name];

$parameters['site_logo_media_id'] = ['Site Logo Media ID', 'site_logo_media_id', 'site-logo-media-id', 'textfield', 'singleMedia', 'Yes', 'Yes', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['site_maintenance_mode'] = ['Site Maintenance Mode', 'site_maintenance_mode', 'site-maintenance-mode', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'To put the site into maintenance mode, select "Yes". All pages within the site will return a single message with the HTTP code of 503.', '', '{}', $first_last_name, $first_last_name];

$parameters['site_name'] = ['Site Name', 'site_name', 'site-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Site Name will be used in the header of the site if no Logo Media is set below. Site name is also used in other places within the site so it should not be empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['site_permissions_id'] = ['Site Permissions', 'site_permissions_id', 'site-permissions-id', 'dropdownId', 'checkboxId', 'Yes', 'Yes', '', 'all_sites_in_account', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', 'If no Site Permissions are set, the user will have access to all sites as super admin.', '', '{}', $first_last_name, $first_last_name];

$parameters['site_search_max_results'] = ['Number of Max Results to Return', 'site_search_max_results', 'site-search-max-results', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', 'Default is 300 max results.', '', '{}', $first_last_name, $first_last_name];

$parameters['site_search_results_per_page'] = ['Results Per Page', 'site_search_results_per_page', 'site-search-results-per-page', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', 'Default is 30 results per page.', '', '{}', $first_last_name, $first_last_name];

$parameters['slide_all_at_once'] = ['Slide All at Once', 'slide_all_at_once', 'slide-all-at-once', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'Choose "Yes" to have all visible slides move together when navigating the slider. Choose "No" to advance one slide at a time, even if multiple slides are visible.', '', '{}', $first_last_name, $first_last_name];

$parameters['slide_margin'] = ['Slide Margin', 'slide_margin', 'slide-margin', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'Define the margin around each slide in pixels. For one slide in view, use "0"; for multiple slides, a value like "5" is common.', '', '{}', $first_last_name, $first_last_name];

$parameters['slide_minimum_width'] = ['Slide Minimum Width', 'slide_minimum_width', 'slide-minimum-width', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', 'Enter a value (e.g., 200) to make the media responsive. When the media width reaches this value, fewer slides will be shown if "Slides in View" is greater than 1.', '', '{}', $first_last_name, $first_last_name];

$parameters['slide_speed'] = ['Slide Speed', 'slide_speed', 'slide-speed', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', 'Adjust the transition speed between slides in milliseconds. Enter "2000" for a slow 2-second transition, or "500" for a faster half-second transition.', '', '{}', $first_last_name, $first_last_name];

$parameters['sliders_id'] = ['Sliders ID', 'sliders_id', 'sliders-id', 'textfield', 'parentTableId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['slides_in_view'] = ['Slides in View', 'slides_in_view', 'slides-in-view', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', 'Specify the number of media slides to display at a time. Enter "1" for one slide, "2" for two, and so on.', '', '{}', $first_last_name, $first_last_name];

$parameters['sms_phone_number'] = ['SMS Phone Number', 'sms_phone_number', 'sms-phone-number', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'No', 'Example: 1-888-888-8888', '', '{}', $first_last_name, $first_last_name];

$parameters['sms_phone_number_class'] = ['SMS Phone Number Class', 'sms_phone_number_class', 'sms-phone-number-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sms_phone_number_text'] = ['SMS Phone Number Text', 'sms_phone_number_text', 'sms-phone-number-text', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example:  | Text us for a quote:', '', '{}', $first_last_name, $first_last_name];

$parameters['sms_phone_number_text_class'] = ['SMS Phone Number Text Class', 'sms_phone_number_text_class', 'sms-phone-number-text-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: call-text-for-quote', '', '{}', $first_last_name, $first_last_name];

$parameters['sort'] = ['Sort', 'sort', 'sort', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sort_or_dragdrop'] = ['Sort or Drag & Drop', 'sort_or_dragdrop', 'sort-or-dragdrop', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'sort_or_drag_and_drop', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', 'If the database table includes a sort column, choose whether the record order should be managed using Sorting or Drag & Drop.', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_address'] = ['Email Address for SQL Injection Notices', 'sql_injection_email_address', 'sql-injection-email-address', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_bcc'] = ['BCC Email Addresses', 'sql_injection_email_bcc', 'sql-injection-email-bcc', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Separate email addresses with ";". <span class="color-f00"><strong>Important:</strong></span> Some hosting companies will send BCC emails to imunify/quarantine and they will not be delivered. If you notice BCC emails not being delivered and your domain / hosting IP Address does not have a bad reputation for email spam, contact your hosting company to see if BCC email are being quarantined and not sending from your server.', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_cc'] = ['CC Email Addresses', 'sql_injection_email_cc', 'sql-injection-email-cc', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Separate email addresses with ";".', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_from'] = ['From Email Address', 'sql_injection_email_from', 'sql-injection-email-from', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_from_name'] = ['From Name', 'sql_injection_email_from_name', 'sql-injection-email-from-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Name of person or team the email is from.', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_me'] = ['Email Me When It Seems Like Someone Might Be Trying to do SQL Injection', 'sql_injection_email_me', 'sql-injection-email-me', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', 'If we notice any POST attempts with SQL words like UNION, DELETE, UPDATE, ALTER, etc., we will email you the details of what they are trying to do.', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_password'] = ['Email Login Password', 'sql_injection_email_password', 'sql-injection-email-password', 'textfield', 'password', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '<span class="color-f00"><strong>Important:</strong></span> Leave blank to keep existing value. Also, if you are running email through a connector or relay and have whitelisted this sites IP, leave the username/password empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_server_port'] = ['Email Server Port Number', 'sql_injection_email_server_port', 'sql-injection-email-server-port', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '10', '0', 'No', 'No', 'This is typically "587" which is secure or port "25" that is not secure.', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_server_url'] = ['Email Server URL', 'sql_injection_email_server_url', 'sql-injection-email-server-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'This is typically something like mail.your_domain_name.com', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_email_username'] = ['Email Login Username', 'sql_injection_email_username', 'sql-injection-email-username', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'This is typically the email address you\'re sending from. Note: If you are running email through a connector or relay and have whitelisted this sites IP, leave the username/password empty.', '', '{}', $first_last_name, $first_last_name];

$parameters['sql_injection_to_name'] = ['To Name', 'sql_injection_to_name', 'sql-injection-to-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(50)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '50', '0', 'No', 'No', 'Name of person or team the email will be sending to.', '', '{}', $first_last_name, $first_last_name];

$parameters['start_row_id'] = ['Start Row ID', 'start_row_id', 'start-row-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', 'Some tables can become large and you may have to break up the rows to export. Enter a starting row ID that you would like to export on. Leave empty to export all rows in database table.', '', '{}', $first_last_name, $first_last_name];

$parameters['state'] = ['State / Province / Region', 'state', 'state', 'textfield', 'textfield', 'Yes', 'Yes', '', '', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['status'] = ['Status', 'status', 'status', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'status_main', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['store_latitude'] = ['Store Location Latitude', 'store_latitude', 'store-latitude', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'No', 'Search engines use your "latitude coordinates" to be able to tell your customers where you physical store is at.', '', '{}', $first_last_name, $first_last_name];

$parameters['store_longitude'] = ['Store Location Longitude', 'store_longitude', 'store-longitude', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(30)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '30', '0', 'No', 'No', 'Search engines use your "longitude coordinates" to be able to tell your customers where you physical store is at.', '', '{}', $first_last_name, $first_last_name];

$parameters['street_address'] = ['Street Address', 'street_address', 'street-address', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['street_address_1'] = ['Street Address 1', 'street_address_1', 'street-address-1', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['street_address_2'] = ['Street Address 2', 'street_address_2', 'street-address-2', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_items'] = ['Sub Items', 'sub_items', 'sub-items', 'textfield', 'subItems', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_items_add_url'] = ['Sub Items Add URL', 'sub_items_add_url', 'sub-items-add-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'The Sub Items Add URL is used to create new Sub Item records. If you enter a value in this field, you must also create a corresponding admin page for this URL. Do not include the admin directory name, do not use leading or trailing slashes, and use lowercase letters only. Use dashes instead of spaces and slashes to separate directories. This URL must always end with "/add", as the system relies on this format. Example: website/menus/menu-items/add', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_items_code'] = ['Sub Items Code', 'sub_items_code', 'sub-items-code', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_items_edit_url'] = ['Sub Items Edit URL', 'sub_items_edit_url', 'sub-items-edit-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'The Sub Items Edit URL is used to edit existing Sub Item records. If you enter a value in this field, you must also create a corresponding admin page for this URL. Do not include the admin directory name, do not use leading or trailing slashes, and use lowercase letters only. Use dashes instead of spaces and slashes to separate directories. This URL must always end with "/edit", as the system relies on this format. Example: website/menus/menu-items/edit', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_items_load_template_include_file'] = ['Sub Items Load Template Include File', 'sub_items_load_template_include_file', 'sub-items-load-template-include-file', 'template', 'template', 'Yes', 'Yes', '', 'templates_files_list_of_all_files', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_items_type'] = ['Sub Items Type', 'sub_items_type', 'sub-items-type', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(25)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '25', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_items_url'] = ['Sub Items URL', 'sub_items_url', 'sub-items-url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'The Sub Items URL is used to view and delete Sub Item records. If you enter a value in this field, you must also create a corresponding admin page for this URL. Do not include the admin directory name, do not use leading or trailing slashes, and use lowercase letters only. Use dashes instead of spaces and slashes to separate directories. Example: website/menus/menu-items, where "menu-items" is the Sub Items admin page that exists under "menus".', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_page'] = ['Sub Page', 'sub_page', 'sub-page', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'No', 'Will this admin page have Sub Pages under it? Select "Yes" if you are creating a URL such as "content/menus/menu_items", content/menus/menu_items/add, or content/menus/menu_items/edit', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_products_ids'] = ['Sub Products IDs', 'sub_products_ids', 'sub-products-ids', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_text'] = ['Sub Text', 'sub_text', 'sub-text', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: We hate spam just as much as you do. We will not sell your information to any third parties. We will ONLY use the information you provide to give you your free quote.', '', '{}', $first_last_name, $first_last_name];

$parameters['sub_text_class'] = ['Sub Text Class', 'sub_text_class', 'sub-text-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: note', '', '{}', $first_last_name, $first_last_name];

$parameters['subdomain'] = ['Subdomain', 'subdomain', 'subdomain', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['submit_button_label'] = ['Submit Button Label', 'submit_button_label', 'submit-button-label', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'This field sets the label for the submit button on the admin form. Examples include: "Save", "Submit", or "Continue".', '', '{}', $first_last_name, $first_last_name];

$parameters['submit_button_text'] = ['Submit Button Text', 'submit_button_text', 'submit-button-text', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Example: REQUEST QUOTE', '', '{}', $first_last_name, $first_last_name];

$parameters['submit_button_text_class'] = ['Submit Button Text Class', 'submit_button_text_class', 'submit-button-text-class', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Example: button', '', '{}', $first_last_name, $first_last_name];

$parameters['submit_button_type'] = ['Form Submit Button Type', 'submit_button_type', 'submit-button-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'forms_submit_button_type', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'Select "Submit" to save the admin form to the database. Select "Button" if you are creating a custom form that requires a button for JavaScript or other custom actions.', '', '{}', $first_last_name, $first_last_name];

$parameters['submitted_from_url'] = ['Submitted From URL', 'submitted_from_url', 'submitted-from-url', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['svg_path'] = ['SVG Icon Path', 'svg_path', 'svg-path', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['swap_admin_field'] = ['Swap Admin Field', 'swap_admin_field', 'swap-admin-field', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'admin_fields_all', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If this field doesn\'t need to trigger a swap, leave it empty. Otherwise, select which Admin Field should be swapped or updated when this field\'s value changes. For example, if this field represents a country, you may want the State field list to update automatically based on the selected country.', '', '{}', $first_last_name, $first_last_name];

$parameters['swap_admin_field_to'] = ['Swap Admin Field To', 'swap_admin_field_to', 'swap-admin-field-to', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'forms_swap_fields', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If this Admin Field is set to swap in the main list setup, select the Admin Field List that should be used when this value is selected. If the target list doesn\'t exist yet, create it first before selecting it here. For example, if you\'re adding a new country and want its list of states, provinces, or regions to appear, select the corresponding list for that country.', '', '{}', $first_last_name, $first_last_name];

$parameters['swap_form_field'] = ['Swap Form Field', 'swap_form_field', 'swap-form-field', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'forms_form_fields', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Leave this field empty if no swap or update is required. If a swap is needed, select the Form Field that should be updated when this field\'s value changes. For example, if this field represents a Country, you may want the State dropdown to automatically update based on the selected country. <span class="color-f00"><strong>Important:</strong></span> The field you select to be swapped or updated must also be included in the form. If the target field is not present in the form, the swap cannot occur.', '', '{}', $first_last_name, $first_last_name];

$parameters['swap_form_field_to'] = ['Swap Form Field To', 'swap_form_field_to', 'swap-form-field-to', 'dropdownId', 'dropdownId', 'Yes', 'Yes', '', 'forms_form_fields', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'If this Form Field is set to swap in the main form setup, select the Form Field List that should be used when this value is selected. If the target list doesn\'t exist yet, create it first before selecting it here. For example, if you\'re adding a new country and want its list of states, provinces, or regions to appear, select the corresponding list for that country.', '', '{}', $first_last_name, $first_last_name];

$parameters['system_code'] = ['System Code', 'system_code', 'system-code', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '<span class="color-f00"><strong>Important:</strong></span> Do not change existing system codes. Create a unique code when adding a new record.', '', '{}', $first_last_name, $first_last_name];

$parameters['table_name'] = ['Table Name', 'table_name', 'table-name', 'tableName', 'tableName', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', 'Table Name field specifies which database table this admin URL will operate on. If the table does not yet exist, you must create it first under "Admin > Database Tables". Make sure the table includes a column named site_id, as this column is required to build the admin page.', '', '{}', $first_last_name, $first_last_name];

$parameters['table_link_column'] = ['Table Link Column', 'table_link_column', 'table-link-column', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'No', 'Fill out this field only when the "Table Name" is a child table for the admin page. Enter the column in this table that links back to the parent table. Typically, this column is named using the format parent_table_name_id, for example, admin_menus_id.', '', '{}', $first_last_name, $first_last_name];

$parameters['table_of_contents'] = ['Table of Contents', 'table_of_contents', 'table-of-contents', 'textfield', 'textarea', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', 'Table of Contents setup code: &lt;div class=&quot;table-of-contents&quot;&gt;&lt;div class=&quot;title&quot;&gt;Table of Contents&lt;/div&gt;&lt;ul&gt;&lt;li&gt;<embed>&</embed>#x276F;<embed>&</embed>nbsp; &lt;a href="#name-1"&gt;Name 1&lt;/a&gt;&lt;/li&gt;&lt;/ul&gt;&lt;/div&gt;', '', '{}', $first_last_name, $first_last_name];

$parameters['table_to_export'] = ['Table To Export', 'table_to_export', 'table-to-export', 'tableName', 'tableName', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['tablet_media'] = ['Tablet Media', 'tablet_media', 'tablet-media', 'textfield', 'singleMedia', 'Yes', 'Yes', 'Media ID', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', '', 'media-center', '{}', $first_last_name, $first_last_name];

$parameters['template'] = ['Template', 'template', 'template', 'template', 'template', 'Yes', 'Yes', '', 'templates_files_list_of_all_files', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['template_type'] = ['Template Type', 'template_type', 'template-type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'templates_types', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['templates_id'] = ['Templates ID', 'templates_id', 'templates-id', 'textfield', 'parentTableId', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['thank_you_url'] = ['Thank You Page URL', 'thank_you_url', 'thank-you-url', 'dropdownId', 'thankYouPageUrl', 'Yes', 'Yes', '', 'display_urls_as_urls', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['thousand_separator'] = ['Thousand Separator', 'thousand_separator', 'thousand-separator', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'currency_thousand_separator', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', 'Example: USA dollars uses a "," to separator thousands like 1,000.00', '', '{}', $first_last_name, $first_last_name];

$parameters['time_period_block'] = ['Time Period (In Minutes) for Max Pageviews', 'time_period_block', 'time-period-block', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', 'If you entered "500" for "Max Pageviews" above, and "60" minutes for "Time Period", the software will automatically block/send you an email/both for any IP that hits these two numbers.', '', '{}', $first_last_name, $first_last_name];

$parameters['timer'] = ['Timer', 'timer', 'timer', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', 'The number of seconds this user was on the site before submitting this leads.', '', '{}', $first_last_name, $first_last_name];

$parameters['timezone'] = ['Time Zone', 'timezone', 'timezone', 'textfield', 'timezone', 'Yes', 'Yes', '', '0', 'varchar(100)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '100', '0', 'No', 'Yes', 'All timestamps in the database are saved as UTC time. Setting your time zone will offset UTC times in the database so you can see when items have been saved as your local time.', '', '{}', $first_last_name, $first_last_name];

$parameters['title'] = ['Title', 'title', 'title', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['title_separator'] = ['Title Separator', 'title_separator', 'title-separator', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', 'This is used to separate your page title from your site name. If you enter a "-" your title tags would look like this: My Page Title - Site Name.', '', '{}', $first_last_name, $first_last_name];

$parameters['top_content'] = ['Main Content', 'top_content', 'top-content', 'textfield', 'textareaWithEditor', 'Yes', 'Yes', '', '0', 'longtext', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '2147483647', '0', 'No', 'No', '<span class="color-f00"><strong>Note:</strong></span> We are currently working on a WYSIWYG editor so you won\'t need to manually write HTML for your content. For now, all posts, product descriptions, and other content must be formatted using HTML. We\'re working to have the editor ready soon, allowing you to simply type your content while the formatting is handled automatically in the background. If you\'re using a Table of Contents, wrap content sections with this: &lt;span id="name-1" class="contents-section"&gt;Your Content Here&lt;/span&gt;', '', '{}', $first_last_name, $first_last_name];

$parameters['top_ip_address'] = ['Top IP Addresses', 'top_ip_address', 'top-ip-address', 'textfield', 'topIpAddresses', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'Yes', 'If any of these IP Addresses are being too aggressive, and you have confirmed they should not be hitting the site, you can block them by adding there IP Address in the field below of "Blocked IP Addresses".', '', '{}', $first_last_name, $first_last_name];

$parameters['total_404s'] = ['Total 404s', 'total_404s', 'total-404s', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['total_coupon_discount_amount'] = ['Total Coupon Discount Amount', 'total_coupon_discount_amount', 'total-coupon-discount-amount', 'price', 'priceAsText', 'No', 'No', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['total_order_amount'] = ['Total Order Amount', 'total_order_amount', 'total-order-amount', 'price', 'priceAsText', 'Yes', 'No', '', '0', 'decimal(16,6)', '', 'No', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['total_order_landed_cost_amount'] = ['Total Order Landed Cost Amount', 'total_order_landed_cost_amount', 'total-order-landed-cost-amount', 'price', 'priceAsText', 'No', 'No', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['total_order_standard_cost_amount'] = ['Total Order Standard Cost Amount', 'total_order_standard_cost_amount', 'total-order-standard-cost-amount', 'price', 'priceAsText', 'No', 'No', '', '0', 'decimal(16,6)', '', 'Yes', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['total_product_amount'] = ['Total Product Amount', 'total_product_amount', 'total-product-amount', 'price', 'priceAsText', 'No', 'No', '', '0', 'decimal(16,6)', '', 'No', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['total_searches'] = ['Total Searches', 'total_searches', 'total-searches', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'int', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['total_shipping_amount'] = ['Total Shipping Amount', 'total_shipping_amount', 'total-shipping-amount', 'price', 'priceAsText', 'No', 'No', '', '0', 'decimal(16,6)', '', 'No', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['total_tax_amount'] = ['Total Tax Amount', 'total_tax_amount', 'total-tax-amount', 'price', 'priceAsText', 'No', 'No', '', '0', 'decimal(16,6)', '', 'No', 'No', 'No', '10', '6', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['type'] = ['Admin Page Type', 'type', 'type', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'admin_pages_type', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'The Admin Page Type determines how this admin page is displayed. "Table" displays a spreadsheet-style view showing all records and allows users to edit or delete them. "Add" should be selected when creating an Add URL, and "Edit" should be selected when creating an Edit URL. "Static" is used for fully custom admin pages and requires you to build all aspects of the layout and functionality manually.', '', '{}', $first_last_name, $first_last_name];

$parameters['update_field_on_save'] = ['Update Field on Save', 'update_field_on_save', 'update-field-on-save', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', 'To have this field update when saving an admin edit page, select Yes. If you prefer not to update this field via admin edit pages, select No.', '', '{}', $first_last_name, $first_last_name];

$parameters['updated_by'] = ['Updated By', 'updated_by', 'updated-by', 'textfield', 'updatedBy', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['updated_date'] = ['Updated Date', 'updated_date', 'updated-date', 'dateRange', 'updatedDate', 'Yes', 'Yes', '&#xf073;', '0', 'datetime', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', 'date-range', '{}', $first_last_name, $first_last_name];

$parameters['url'] = ['URL', 'url', 'url', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'URL is used to view and delete records. If you enter a value in this field, you must also create a corresponding admin page for this URL. Do not include the admin directory name, do not use leading or trailing slashes, and use lowercase letters only. Use dashes instead of spaces and slashes to separate directories. Example: website/menus', '', '{}', $first_last_name, $first_last_name];

$parameters['url_404'] = ['404 URL', 'url_404', 'url-404', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(512)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '512', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['url_extension'] = ['URL Extension', 'url_extension', 'url-extension', 'textfield', 'urlExtension', 'Yes', 'Yes', '', '0', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['url_name'] = ['URL Name', 'url_name', 'url-name', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', 'You can leave this field empty. We will use the "Name" you enter above to create the database "URL Name". If you want to enter the "URL Name" the rules are: use all lowercase letters; use dashes for spaces. Example: new-url-name', '', '{}', $first_last_name, $first_last_name];

$parameters['url_status'] = ['URL Status', 'url_status', 'url-status', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'urls_status', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['url_structure'] = ['URL Structure', 'url_structure', 'url-structure', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'site_settings_url_structure', 'varchar(10)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '10', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['urls_id'] = ['URLs ID', 'urls_id', 'urls-id', 'textfield', 'hidden', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['user_email'] = ['User Email', 'user_email', 'user-email', 'textfield', 'text', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['user_id'] = ['User ID', 'user_id', 'user-id', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['username'] = ['Username', 'username', 'username', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['valid_lead'] = ['Is This a Valid Lead', 'valid_lead', 'valid-lead', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'No', 'If you select "Yes" and an affiliate referred this lead, it will appear in their account, indicating that they will be paid for the lead.', '', '{}', $first_last_name, $first_last_name];

$parameters['value'] = ['Value', 'value', 'value', 'textfield', 'textfieldNoEdit', 'Yes', 'Yes', '', '0', 'varchar(255)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '255', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['value_ids'] = ['Value IDs', 'value_ids', 'value-ids', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['values'] = ['Values', 'values', 'values', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['video_poster'] = ['Video Poster', 'video_poster', 'video-poster', 'textfield', 'singleMedia', 'Yes', 'Yes', 'Media ID', '0', 'text', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '65535', '0', 'No', 'No', 'If this is a Video, you can select a poster image that will display when the video first loads. This poster image should help website visitors get an idea of what the video is about.', 'media-center', '{}', $first_last_name, $first_last_name];

$parameters['visit_date'] = ['Visit Date', 'visit_date', 'visit-date', 'dateRange', 'dateFrom', 'Yes', 'Yes', '&#xf073;', '0', 'date', '', 'Yes', 'No', 'No', '0', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['wait'] = ['Wait', 'wait', 'wait', 'textfield', 'textfield', 'Yes', 'Yes', '', '0', 'varchar(5)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '5', '0', 'No', 'No', '', '', '{}', $first_last_name, $first_last_name];

$parameters['width'] = ['Width', 'width', 'width', 'textfield', 'width', 'Yes', 'Yes', '', '0', 'varchar(25)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '25', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['www_in_url'] = ['www in URL', 'www_in_url', 'www-in-url', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'yes_no', 'varchar(3)', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'No', 'No', 'No', '3', '0', 'No', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$parameters['zeros_after_separator'] = ['Zeros After Fractional Separator', 'zeros_after_separator', 'zeros-after-separator', 'dropdownValue', 'dropdownValue', 'Yes', 'Yes', '', 'currency_zeros_after_fractional_separator', 'int', '', 'No', 'No', 'No', '0', '0', 'No', 'Yes', 'Example: USA dollars uses "2" ending zeros like 0.00', '', '{}', $first_last_name, $first_last_name];

if(!isset($update_admin_fields))
{
	$results->getinsertMultipleRecords(__LINE__, __FILE__, 'admin_fields', $column_names, $placeholders, $parameters);
}
else
{
	$update_parameters = $parameters;
	$parameters = array();
	
	foreach($update_parameters as $param)
	{
		$parameters[$param[1]] = ['name' => $param[0], 
						 'column_name' => $param[1], 
						 'url_name' => $param[2], 
						 'search_as' => $param[3], 
						 'display_as' => $param[4], 
						 'display_in_admin' => $param[5], 
						 'update_field_on_save' => $param[6], 
						 'placeholder' => $param[7], 
						 'admin_fields_lists_system_code' => $param[8], 
						 'data_type' => $param[9], 
						 'character_set_and_collate' => $param[10], 
						 'is_nullable' => $param[11], 
						 'is_primary_key' => $param[12], 
						 'is_auto_increment' => $param[13], 
						 'data_length' => $param[14], 
						 'data_length_back' => $param[15], 
						 'financial_field' => $param[16], 
						 'required' => $param[17], 
						 'notes' => $param[18], 
						 'css_class' => $param[19], 
						 'custom_fields' => $param[20], 
						 'updated_by' => $first_last_name, 
						 'created_by' => $first_last_name];
	}
}