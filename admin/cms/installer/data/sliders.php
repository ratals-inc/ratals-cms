<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$column_names = '`id`, `site_id`, `status`, `name`, `sub_items`, `slides_in_view`, `slide_all_at_once`, `slide_minimum_width`, `auto_slide_media`, `pause_time`, `slide_speed`, `slide_margin`, `display_pagination`, `pagination_alignment`, `pagination_over_image`, `display_thumbnails`, `pagination_thumbnail_width`, `pagination_margin`, `lazy_load_media`, `fetch_priority`, `embed_slider`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();
$parameters[] = [$_SESSION['install_ids'][$site_id]['slider_id'] ?? NULL, $site_id, 1, 'Homepage Slider', 2, '1', 'No', '200', 'Yes', '8000', '500', '0', 'Yes', 'center', 'Yes', 'No', '60', '5', 'No', 'Yes', '', '{}', $install_update_username, $install_update_username];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'sliders', $column_names, $placeholders, $parameters);