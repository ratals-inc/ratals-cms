<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Custom Fields
$column_names = '`id`, `site_id`, `field_type`, `status`, `custom_field_name`, `sub_items`, `assigned_to`, `column_name`, `url_name`, `cf_search_as`, `cf_display_as`, `required`, `placeholder`, `embed_custom_field`, `notes`, `updated_by`, `updated_date`, `created_by`, `created_date`';
$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [$_SESSION['install_ids'][$site_id]['company_image'] ?? NULL, $site_id, 'Content Field', 1, '{"'.$site_language.'":{"frontend_name":"Side Bar Company Image","admin_name":"side-bar-company-image"}}', 0, 'custom_fields_global', 'side_bar_company_image', 'side-bar-company-image', 'textfield', 'singleMedia', 'No', '', '', 'This is the image in the side bar of some template files for about us.', $first_last_name, $first_last_name];
$parameters[] = [$_SESSION['install_ids'][$site_id]['company_title'] ?? NULL, $site_id, 'Content Field', 1, '{"'.$site_language.'":{"frontend_name":"Side Bar Company Title","admin_name":"side-bar-company-title"}}', 0, 'custom_fields_global', 'side_bar_company_title', 'side-bar-company-title', 'textfield', 'textfield', 'No', '', '', 'This is the title in the side bar of some template files for about us.', $first_last_name, $first_last_name];
$parameters[] = [$_SESSION['install_ids'][$site_id]['company_text'] ?? NULL, $site_id, 'Content Field', 1, '{"'.$site_language.'":{"frontend_name":"Side Bar Company Text","admin_name":"side-bar-company-text"}}', 0, 'custom_fields_global', 'side_bar_company_text', 'side-bar-company-text', 'textfield', 'textareaWithEditor', 'No', '', '', 'This is the text in the side bar of some template files for about us.', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'custom_fields', $column_names, $placeholders, $parameters);