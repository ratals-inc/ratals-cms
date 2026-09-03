<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$post_custom_fields = JSON_ENCODE(array('side_bar_company_image' => ($_SESSION['image-coming-soon-650-650.gif'] ?? 7).'~||~Company Photo', 'side_bar_company_title' => 'About Me or My Company', 'side_bar_company_text' => '<p>Use this area to introduce yourself or your company, explain what you do, and tell visitors why they should <a href="urlId('.($_SESSION['install_ids'][$site_id]['contact_us_page_url_id'] ?? 0).');">contact you</a>. You can also highlight what makes you or your business unique.</p><p>Add more details here about yourself, your company, services, experience, or anything else you want visitors to know.</p>'));

$column_names = '`id`, `site_id`, `custom_fields`, `created_date`, `created_by`, `updated_date`, `updated_by`';
$placeholders = '?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();
$parameters[] = [NULL, $site_id, $post_custom_fields, $install_update_username, $install_update_username];

$results->getInsertMultipleRecords(__LINE__, __FILE__, 'custom_fields_global', $column_names, $placeholders, $parameters);