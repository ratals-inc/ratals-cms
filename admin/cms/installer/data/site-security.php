<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Site Security
$column_names = '`id`, `site_id`, `top_ip_address`, `site_blocked_ips`, `site_allowed_ips`, `block_ip_failed_login`, `number_of_failed_login_attempts`, `failed_login_attempts_email_me`, `failed_login_attempts_email_address`, `failed_login_attempts_to_name`, `failed_login_attempts_email_cc`, `failed_login_attempts_email_bcc`, `failed_login_attempts_email_from`, `failed_login_attempts_email_from_name`, `failed_login_attempts_email_server_url`, `failed_login_attempts_email_server_port`, `failed_login_attempts_email_username`, `failed_login_attempts_email_password`, `failed_login_blocked_ips`, `sql_injection_email_me`, `sql_injection_email_address`, `sql_injection_to_name`, `sql_injection_email_cc`, `sql_injection_email_bcc`, `sql_injection_email_from`, `sql_injection_email_from_name`, `sql_injection_email_server_url`, `sql_injection_email_server_port`, `sql_injection_email_username`, `sql_injection_email_password`, `max_pageviews_block`, `time_period_block`, `auto_blocked_ip_email_me`, `auto_blocked_email_address`, `ddos_to_name`, `ddos_email_cc`, `ddos_email_bcc`, `ddos_email_from`, `ddos_email_from_name`, `ddos_email_server_url`, `ddos_email_server_port`, `ddos_email_username`, `ddos_email_password`, `ddos_blocked_ips`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';
$placeholders = 'NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [$site_id, 10, '', $_SERVER['REMOTE_ADDR'], 'Yes', '5', 'Yes', $server_email, $first_last_name, '', '', $server_email, $server_email_name, $server_smpt_url, $email_port, $server_email, $server_email_password, '', 'Yes', $server_email, $first_last_name, '', '', $server_email, $server_email_name, $server_smpt_url, $email_port, $server_email, $server_email_password, '500', '60', 'Email Me', $server_email, $first_last_name, '', '', $server_email, $server_email_name, $server_smpt_url, $email_port, $server_email, $server_email_password, '', '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'site_security', $column_names, $placeholders, $parameters);