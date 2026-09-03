<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Blocking Spam
$column_names = '`id`, `site_id`, `comments_blocked_keywords`, `comments_block_links`, `forms_blocked_keywords`, `forms_block_links`, `forms_time_on_site`, `forms_pageviews`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';
$placeholders = 'NULL,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [$site_id, '', 'No', '', 'No', '0', '1', '{}', $install_update_username, $install_update_username];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'blocking_spam', $column_names, $placeholders, $parameters);