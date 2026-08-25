<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Site Contact Info
$column_names = '`id`, `site_id`, `display_contact_info_on_site`, `street_address`, `city`, `country`, `state`, `postal_code`, `store_latitude`, `store_longitude`, `hours`, `days_of_the_week_open`, `phone_number`, `area_served`, `available_language`, `other_business_urls`, `smtp_email_address`, `smtp_email_name`, `smtp_email_cc`, `smtp_email_bcc`, `smtp_email_hostname`, `smtp_email_port`, `smtp_email_username`, `smtp_email_password`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';
$placeholders = 'NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [$site_id, $display_contact_information, $street_address, $city, $country, $state, $postal_code, '00.0000', '00.0000', 'Monday - Friday, 8:00 am - 5:00 pm', 'Monday|08:00|16:00||,Tuesday|08:00|16:00||,Wednesday|08:00|16:00||,Thursday|08:00|16:00||,Friday|08:00|16:00||', $phone_number, $country, $site_language, '"facebook_url","twitter_url","youtube_url"', $smtp_email_address, $smtp_email_name, '', '',	$smtp_email_hostname, $smtp_email_port, $smtp_email_username, $smtp_email_password, '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'site_contact_info', $column_names, $placeholders, $parameters);