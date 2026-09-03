<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$column_names = '`id`, `site_id`, `status`, `name`, `frontend_name`, `sub_items`, `embed_menu`, `system_code`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = '?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();

$parameters[] = [$_SESSION['install_ids'][$site_id]['header_menu_id'] ?? NULL, $site_id, 1, 'Header', 'Header', 4, '', 'header', '{}', $install_update_username, $install_update_username];
$parameters[] = [$_SESSION['install_ids'][$site_id]['footer_catetories_menu_id'] ?? NULL, $site_id, 1, 'Footer Company', 'COMPANY', 6, '', 'footer_company', '{}', $install_update_username, $install_update_username];
$parameters[] = [$_SESSION['install_ids'][$site_id]['connect_on_social_menu_id'] ?? NULL, $site_id, 1, 'Connect on Social', 'CONTACT US', 8, '', 'connect_on_social', '{}', $install_update_username, $install_update_username];
$parameters[] = [$_SESSION['install_ids'][$site_id]['footer_bottom_menu_id'] ?? NULL, $site_id, 1, 'Footer Links', 'LINKS', 3, '', 'footer_links', '{}', $install_update_username, $install_update_username];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'menus', $column_names, $placeholders, $parameters);