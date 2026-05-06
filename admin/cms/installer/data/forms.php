<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Forms
$column_names = '`id`, `site_id`, `status`, `frontend_name`, `admin_form_name`, `form_auto_complete`, `sub_text`, `call_phone_number_text`, `call_phone_number`, `display_call_phone_number`, `sms_phone_number_text`, `sms_phone_number`, `display_sms_phone_number`, `submit_button_text`, `form_fields_ids`, `thank_you_url`, `in_form_thank_you`, `form_name_class`, `frontend_name_class`, `sub_text_class`, `call_phone_number_text_class`, `call_phone_number_class`, `sms_phone_number_text_class`, `sms_phone_number_class`, `submit_button_text_class`, `in_form_thank_you_class`, `form_conversion_value`, `embed_form`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = '?,0,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();
$parameters[] = [1, 1, 'Request a Quote', 'request_a_quote', 'On', 'We hate spam just as much as you do. We will not sell your information to any third parties. We will ONLY use the information you provide to give you your free quote.', 'Call us for a quote:', $phone_number, $phone_number, ' | Text us for a quote:', $phone_number, $phone_number, 'REQUEST QUOTE', '8,9,11,12,13,14,15,1,4,16', NULL, 'Submitted successfully. Thank you!', 'form-1', 'title', 'note', 'call-text-for-quote', '', 'call-text-for-quote', '', 'button', 'in-form-thank-you', NULL, '', '{}', $first_last_name, $first_last_name];
$parameters[] = [2, 1, 'Send Us a Message', 'send_us_a_message', 'On', '', '', '', '', '', '', '', 'Send Message', '10,12,11,13,17', NULL, 'Submitted successfully. Thank you!', 'form-2', 'title', '', '', '', '', '', 'button', 'in-form-thank-you', NULL,  '', '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'forms', $column_names, $placeholders, $parameters);