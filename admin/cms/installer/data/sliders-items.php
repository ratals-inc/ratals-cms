<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$column_names = '`id`, `site_id`, `sliders_id`, `status`, `desktop_media`, `tablet_media`, `mobile_media`, `links_to`, `custom_link`, `link_type`, `sort`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = 'NULL,'.$site_id.',?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();
$parameters[] = [$last_slider_id + 1, 1, ($_SESSION['image-coming-soon-1500-300.gif'] ?? 16).'~||~', ($_SESSION['image-coming-soon-1025-300.gif'] ?? 22).'~||~', ($_SESSION['image-coming-soon-600-300.gif'] ?? 10).'~||~', $_SESSION['install_ids'][$site_id]['blog_page_url_id'] ?? NULL, '', '', 1, '{}', $install_update_username, $install_update_username];
$parameters[] = [$last_slider_id + 1, 1, ($_SESSION['image-coming-soon-1500-300.gif'] ?? 16).'~||~', ($_SESSION['image-coming-soon-1025-300.gif'] ?? 22).'~||~', ($_SESSION['image-coming-soon-600-300.gif'] ?? 10).'~||~', $_SESSION['install_ids'][$site_id]['about_us_page_url_id'] ?? NULL, '', '', 2, '{}', $install_update_username, $install_update_username];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'slider_items', $column_names, $placeholders, $parameters);