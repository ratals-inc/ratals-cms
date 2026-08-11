<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Site Settings
$column_names = '`id`, `site_id`, `site_name`, `name_in_title_tag`, `title_separator`, `site_logo_media_id`, `favicon_16px_16px`, `favicon_32px_32px`, `favicon_180px_180px`, `blog_pagination`, `display_breadcrumbs`, `site_maintenance_mode`, `timezone`, `site_search_results_per_page`, `site_search_max_results`, `lazy_load_media_row`, `default_video_icon`, `default_file_icon`, `display_cookie_notice`, `cookie_notice_url`, `privacy_notice_url`, `load_pages_with_cached_results`, `seconds_between_cache_refreshing`, `pages_not_to_cache`, `collect_analytics_data`, `google_analytics_tag_id`, `microsoft_advertising_tag_id`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';
$placeholders = 'NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [$site_id, $site_name, 'Yes', '-', '', ($_SESSION['favicon-16x16.png'] ?? 31).'~||~', ($_SESSION['favicon-32x32.png'] ?? 34).'~||~', ($_SESSION['favicon-180x180.png'] ?? 37).'~||~', 10, 'Yes', 'No', $timezone, 30, 300, 5, ($_SESSION['video-icon.gif'] ?? 25).'~||~', ($_SESSION['file-icon.gif'] ?? 28).'~||~', 'Yes', $_SESSION['install_ids'][$site_id]['cookie_policy_page_url_id'] ?? NULL, $_SESSION['install_ids'][$site_id]['privacy_policy_page_url_id'] ?? NULL, $load_with_cache, 14400, 'account, addresses, account/addresses, add-address, account/addresses/add-address, edit-address, account/addresses/edit-address, affiliate, account/affiliate, cards-on-file, account/cards-on-file, add-card, account/cards-on-file/add-card, edit-card, account/cards-on-file/edit-card, invoice, account/invoice, license-keys, account/license-keys, orders, account/orders, order-details, account/orders/order-details, profile, account/profile, receipt, account/receipt, subscriptions, account/subscriptions, cancel-order, cart, checkout, order-confirmation, reset-password, robots', 'Yes', '', '', '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'site_settings', $column_names, $placeholders, $parameters);