<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Site Settings
$column_names = '`id`, `site_id`, `accounting_enabled`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';
$placeholders = 'NULL,0,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = ['No', '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'modules', $column_names, $placeholders, $parameters);