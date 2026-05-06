<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//create page groups that load on clients and protfolio pages.
$column_names = '`id`, `site_id`, `table_name`, `urls_id`, `status`, `name`, `sub_items_type`, `sub_items_code`, `sub_items_load_template_include_file`, `title`, `content`, `columns`, `display_text_from_sub_items`, `gap_between_items`, `outter_css_box_styles`, `inner_css_box_styles`, `lazy_load_media`, `fetch_priority`, `display_as_slider`, `slides_in_view`, `slide_all_at_once`, `slide_minimum_width`, `auto_slide_media`, `pause_time`, `slide_speed`, `slide_margin`, `display_pagination`, `pagination_alignment`, `pagination_over_image`, `display_thumbnails`, `pagination_thumbnail_width`, `pagination_margin`, `sort`';
$placeholders = 'NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?';

$parameters = array();
$parameters[] = [$site_id, 'pages', $_SESSION['install_ids'][$site_id]['clients_page_url_id'] ?? 0, 1, 'Clients 1', 'Sub Items', '', '', '', '', 5, 'Yes', 3, '', '', 'No', 'Yes', 'No', 5, 'No', 200, 'No', 8000, 500, 1, 'Yes', 'center', 'No', 'No', 30, 5, 1];
$parameters[] = [$site_id, 'pages', $_SESSION['install_ids'][$site_id]['clients_page_url_id'] ?? 0, 1, 'Clients 2', 'Sub Items', '', '', 'Clients Two', 'Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two Clients Two.', 5, 'Yes', 3, 'margin-top: 10px;', '', 'Yes', 'No', 'No', 5, 'No', 200, 'No', 8000, 500, 1, 'Yes', 'center', 'No', 'Yes', 30, 5, 2];
$parameters[] = [$site_id, 'pages', $_SESSION['install_ids'][$site_id]['portfolio_page_url_id'] ?? 0, 1, 'Portfolio Items 1', 'Sub Items', '', '', '', '', 5, 'Yes', 3, '', '', 'No', 'Yes','No', 5, 'No', 200, 'No', 8000, 500, 1, 'Yes', 'center', 'No', 'No', 30, 5, 1];
$parameters[] = [$site_id, 'pages', $_SESSION['install_ids'][$site_id]['portfolio_page_url_id'] ?? 0, 1, 'Portfolio Items 2', 'Sub Items', '', '', 'Portfolio Two', 'Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two Portfolio Two.', 5, 'Yes', 3, 'margin-top: 10px;', '', 'Yes', 'No', 'No', 5, 'No', 200, 'No', 8000, 500, 1, 'Yes', 'center', 'No', 'Yes', 30, 5, 2];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'page_groups', $column_names, $placeholders, $parameters);