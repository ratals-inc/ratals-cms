<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Admin Field Sections
$column_names = '`id`, `site_id`, `before_admin_field_id`, `field_section_name`, `system_code`, `notes`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = 'NULL,0,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

//Get all admin_fields so we can look up the admin field id that the section heading goes right before.
$admin_field_section_ids = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', '', [], 'column_name');

$parameters = array();

if(isset($admin_field_section_ids['admin_permissions_id']['id']))
{
	$parameters[] = [$admin_field_section_ids['admin_permissions_id']['id'], 'Admin User Login Credentials', 'admin_user_login_credentials', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['allow_software_update_messages']['id']))
{
	$parameters[] = [$admin_field_section_ids['allow_software_update_messages']['id'], 'Software Updates', 'software_updates', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['block_ip_failed_login']['id']))
{
	$parameters[] = [$admin_field_section_ids['block_ip_failed_login']['id'], 'Failed Login\'s / Brute Force Attacks', 'failed_logins', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['collect_analytics_data']['id']))
{
	$parameters[] = [$admin_field_section_ids['collect_analytics_data']['id'], 'Analytics Settings', 'analytics_settings', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['comments_blocked_keywords']['id']))
{
	$parameters[] = [$admin_field_section_ids['comments_blocked_keywords']['id'], 'Blog Post Comments', 'blog_post_comments', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['display_cookie_notice']['id']))
{
	$parameters[] = [$admin_field_section_ids['display_cookie_notice']['id'], 'Cookie Notice Settings', 'cookie_notice_settings', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['form_name_class']['id']))
{
	$parameters[] = [$admin_field_section_ids['form_name_class']['id'], 'CSS Class Names to Above Fields', 'form_css_class_names_to_above_fields', 'CSS classes should be defined in your website template stylesheet to customize the look and feel of this form.', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['forms_blocked_keywords']['id']))
{
	$parameters[] = [$admin_field_section_ids['forms_blocked_keywords']['id'], 'Form Submissions', 'form_submissions', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['lazy_load_media_row']['id']))
{
	$parameters[] = [$admin_field_section_ids['lazy_load_media_row']['id'], 'Media Settings', 'media_settings', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['lead']['id']))
{
	$parameters[] = [$admin_field_section_ids['lead']['id'], 'Lead Submission', 'lead_submission', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['load_pages_with_cached_results']['id']))
{
	$parameters[] = [$admin_field_section_ids['load_pages_with_cached_results']['id'], 'Page Caching', 'page_caching', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['max_pageviews_block']['id']))
{
	$parameters[] = [$admin_field_section_ids['max_pageviews_block']['id'], 'DDOS / Denial of Service Attacks', 'ddos', '<span class="color-f00"><strong>Important: </strong></span> Be careful here. You could block search engines like Google and Bing if you\'re too aggressive or don\'t know what you\'re doing. Blocking search engine IP addresses will cause you to lose your search engine rankings. Most search engines will give you their IP\'s so you can confirm if it\'s valid or not. Here are a few popular search engine IP lists: <a href="https://developers.google.com/static/crawling/ipranges/common-crawlers.json" target="_blank">Googlebot IP\'s</a>, <a href="https://developers.google.com/static/crawling/ipranges/special-crawlers.json" target="_blank">Google AdsBot IP\'s</a>, <a href="https://www.bing.com/toolbox/bingbot.json" target="_blank">Bing IP\'s</a>. Automatically blocking IP\'s is dangerous. If you select automatic IP blocking below, make sure you\'re constantly watching what is getting blocked.', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['name_class']['id']))
{
	$parameters[] = [$admin_field_section_ids['name_class']['id'], 'CSS Class Names to Above Fields', 'css_class_names_to_above_fields', 'CSS classes should be defined in your website template stylesheet to customize the look and feel of this form field.', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['site_search_results_per_page']['id']))
{
	$parameters[] = [$admin_field_section_ids['site_search_results_per_page']['id'], 'Site Search Settings', 'site_search_settings', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['slides_in_view']['id']))
{
	$parameters[] = [$admin_field_section_ids['slides_in_view']['id'], 'Media Slider Settings', 'media_slider_settings', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['smtp_email_address']['id']))
{
	$parameters[] = [$admin_field_section_ids['smtp_email_address']['id'], 'Outgoing SMTP Email Delivery', 'outgoing_smtp_email_delivery', 'Configure the outgoing SMTP email account Ratals will use to send password recovery emails, security alerts, order notifications, and other system emails. If SMTP is not configured, Ratals will attempt to send email using your server\'s PHP mail function, which may be less reliable.', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['sql_injection_email_me']['id']))
{
	$parameters[] = [$admin_field_section_ids['sql_injection_email_me']['id'], 'SQL Injection Attacks', 'sql_injection_attacks', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['timer']['id']))
{
	$parameters[] = [$admin_field_section_ids['timer']['id'], 'Visitor Information', 'visitor_information', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['timezone']['id']))
{
	$parameters[] = [$admin_field_section_ids['timezone']['id'], 'Timezone Settings', 'timezone_settings', '', '{}', $first_last_name, $first_last_name];
}

if(isset($admin_field_section_ids['user_email_address']['id']))
{
	$parameters[] = [$admin_field_section_ids['user_email_address']['id'], 'User Email Information', 'user_email_information', '', '{}', $first_last_name, $first_last_name];
}

if(!isset($update_admin_field_sections) && !empty($parameters))
{
	$results->getinsertMultipleRecords(__LINE__, __FILE__, 'admin_fields_sections', $column_names, $placeholders, $parameters);
}
else
{
	$update_parameters = $parameters;
	$parameters = array();
	
	foreach($update_parameters as $param)
	{
		$parameters[] = ['before_admin_field_id' => $param[0], 
						 'field_section_name' => $param[1], 
						 'system_code' => $param[2], 
						 'notes' => $param[3], 
						 'custom_fields' => $param[4], 
						 'updated_by' => $first_last_name, 
						 'created_by' => $first_last_name];
	}
}