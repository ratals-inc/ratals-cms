<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Form Fields
$column_names = '`id`, `site_id`, `frontend_name`, `admin_name`, `sub_items`, `form_field_type`, `auto_complete`, `swap_form_field`, `required`, `name_class`, `custom_fields`, `updated_by`, `updated_date`, `created_by`, `created_date`';
$placeholders = '?,0,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [1, 'Country', 'country', 200, 'Dropdown', 'country-name', '4', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [2, 'Ship To Country', 'ship_to_country', 200, 'Dropdown', 'country-name', '4', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [3, 'Bill To Country', 'bill_to_country', 200, 'Dropdown', 'country-name', '4', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [4, 'State / Province / Region', 'state_province_region', 0, 'Dropdown', 'address-level1', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [5, 'State', 'state', 51, 'Dropdown', 'address-level1', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [6, 'Province', 'province_canada', 14, 'Dropdown', 'address-level1', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [7, 'Card Payment Types', 'card-type', 5, 'Dropdown', '', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [8, 'First Name', 'first_name', 0, 'Textfield', 'given-name', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [9, 'Last Name', 'last_name', 0, 'Textfield', 'family-name', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [10, 'Name', 'name', 0, 'Textfield', 'name', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [11, 'Company Name', 'company_name', 0, 'Textfield', 'organization', '', 'No', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [12, 'Email', 'email', 0, 'Textfield', 'email', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [13, 'Phone Number', 'phone_number', 0, 'Textfield', 'tel', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [14, 'Street Address', 'street_address', 0, 'Textfield', '', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [15, 'City', 'city', 0, 'Textfield', 'address-level2', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [16, 'Zip Code', 'zip_code', 0, 'Textfield', 'postal-code', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];
$parameters[] = [17, 'Message', 'message', 0, 'Textarea', '', '', 'Yes', 'text', '{}', $install_update_username, $install_update_username];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'form_fields', $column_names, $placeholders, $parameters);