<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Users
$column_names = '`id`, `site_id`, `status`, `first_name`, `last_name`, `street_address_1`, `street_address_2`, `city`, `country`, `state`, `postal_code`, `phone_number`, `phone_number_ext`, `admin_language`, `last_logged_in`, `admin_permissions_id`, `site_permissions_id`, `username`, `password`, `user_email_address`, `user_email_cc`, `user_email_bcc`, `user_email_signature`, `allow_software_update_messages`, `custom_fields`, `updated_by`, `updated_date`, `created_by`, `created_date`';
$placeholders = 'NULL,0,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [$new_user_id, $first_name, $last_name, $street_address, '', $city, $country, $state, $postal_code, $phone_number, '', $site_language, NULL, '', $username, $password, $user_email, '', '', '<p>Thank you,<br>'.$first_last_name.'<br>'.$phone_number.'</p>', 'Yes', '{}', $install_update_username, $install_update_username];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'users', $column_names, $placeholders, $parameters);