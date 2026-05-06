<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Currency
$column_names = '`id`, `site_id`, `currency_type`, `front_symbol`, `back_symbol`, `thousand_separator`, `fractional_separator`, `zeros_after_separator`, `exchange_rate_difference`, `custom_fields`, `created_date`, `created_by`, `updated_date`, `updated_by`';
$placeholders = 'NULL,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();
$parameters[] = [$site_id, $currency_type, $front_symbol, $back_symbol, $thousand_separator, $fractional_separator, $zeros_after_separator, 1.0000, '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'currency', $column_names, $placeholders, $parameters);