<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Site Security
$column_names = '`id`, `site_id`, `top_ip_address`, `site_blocked_ips`, `site_allowed_ips`, `block_ip_failed_login`, `number_of_failed_login_attempts`, `failed_login_attempts_email_me`, `failed_login_attempts_to_email_address`, `failed_login_attempts_to_email_name`, `failed_login_attempts_email_cc`, `failed_login_attempts_email_bcc`, `failed_login_blocked_ips`, `sql_injection_email_me`, `sql_injection_to_email_address`, `sql_injection_to_email_name`, `sql_injection_email_cc`, `sql_injection_email_bcc`, `max_pageviews_block`, `time_period_block`, `auto_blocked_ip_email_me`, `ddos_to_email_address`, `ddos_to_email_name`, `ddos_email_cc`, `ddos_email_bcc`, `ddos_blocked_ips`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';
$placeholders = 'NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [$site_id, 10, '', $_SERVER['REMOTE_ADDR'], 'Yes', '5', 'Yes', $user_email, $to_email_name, '', '', '', 'Yes', $user_email, $to_email_name, '', '', '500', '60', 'Email Me', $user_email, $to_email_name, '', '', '', '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'site_security', $column_names, $placeholders, $parameters);