<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Search Engines
$column_names = '`id`, `site_id`, `meta_robots`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';
$placeholders = 'NULL,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [$site_id, '', '{}', $install_update_username, $install_update_username];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'search_engines', $column_names, $placeholders, $parameters);