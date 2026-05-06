<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//License
$column_names = '`id`, `site_id`, `license_key`, `install_id`, `license_status`, `license_type`, `license_last_billing_date`, `license_next_billing_date`, `license_next_billing_amount`, `license_billing_line_items`, `last_seen`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';

$placeholders = 'NULL,0,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

//Set install_id.
try
{
	//Preferred: php cryptographically secure.
	$install_id = bin2hex(random_bytes(16)); //32 chars
}
catch(Exception $e)
{
	//Fallback if php cryptographically secure fails.
	$install_id = '0123456789abcdefghijklmnopqrstuvwxyz';
	$install_id = substr(str_shuffle($install_id), 0, 32);
}

$parameters = array();
$parameters[] = ['', $install_id, 'Active', 'CMS', NULL, NULL, 0, '', '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'license', $column_names, $placeholders, $parameters);