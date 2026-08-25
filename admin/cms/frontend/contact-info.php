<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/contact-info.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/contact-info.php');
}
else
{
	//SITE CONTACT INFO - Select everything from site_contact_info table.
	$site_contact_info = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'site_contact_info', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
	
	$contact_info_display_contact_info = $site_contact_info["display_contact_info_on_site"] ?? 'No';
	$_SESSION['contact_info_display_contact_info'] = $site_contact_info["display_contact_info_on_site"] ?? 'No';
	$contact_info_street_address = $site_contact_info["street_address"] ?? '';
	$_SESSION['contact_info_street_address'] = $site_contact_info["street_address"] ?? '';
	$contact_info_city = $site_contact_info["city"] ?? '';
	$_SESSION['contact_info_city'] = $site_contact_info["city"] ?? '';
	$contact_info_state = $site_contact_info["state"] ?? '';
	$_SESSION['contact_info_state'] = $site_contact_info["state"] ?? '';
	$contact_info_country = $site_contact_info["country"] ?? '';
	$_SESSION['contact_info_country'] = $site_contact_info["country"] ?? '';
	$contact_info_postal_code = $site_contact_info["postal_code"] ?? '';
	$_SESSION['contact_info_postal_code'] = $site_contact_info["postal_code"] ?? '';
	$contact_info_latitude = $site_contact_info["store_latitude"] ?? 0;
	$contact_info_longitude = $site_contact_info["store_longitude"] ?? 0;
	$contact_info_hours = $site_contact_info["hours"] ?? '';
	$contact_info_days_of_the_week_open = $site_contact_info["days_of_the_week_open"] ?? '';
	$contact_info_phone_number = $site_contact_info["phone_number"] ?? '';
	$_SESSION['contact_info_phone_number'] = $site_contact_info["phone_number"] ?? '';
	$contact_info_area_served = $site_contact_info["area_served"] ?? '';
	$contact_info_available_language = $site_contact_info["available_language"] ?? '';
	$contact_info_other_business_urls = $site_contact_info["other_business_urls"] ?? '';
	$contact_info_smtp_email_address = $site_contact_info["smtp_email_address"] ?? '';
	$_SESSION['contact_info_smtp_email_address'] = $site_contact_info["smtp_email_address"] ?? '';
	$contact_info_smtp_email_name = $site_contact_info["smtp_email_name"] ?? '';
	$_SESSION['contact_info_smtp_email_name'] = $site_contact_info["smtp_email_name"] ?? '';
	$contact_info_smtp_email_cc = $site_contact_info["smtp_email_cc"] ?? '';
	$_SESSION['contact_info_smtp_email_cc'] = $site_contact_info["smtp_email_cc"] ?? '';
	$contact_info_smtp_email_bcc = $site_contact_info["smtp_email_bcc"] ?? '';
	$_SESSION['contact_info_smtp_email_bcc'] = $site_contact_info["smtp_email_bcc"] ?? '';
	$contact_info_smtp_email_hostname = $site_contact_info["smtp_email_hostname"] ?? '';
	$_SESSION['contact_info_smtp_email_hostname'] = $site_contact_info["smtp_email_hostname"] ?? '';
	$contact_info_smtp_email_port = $site_contact_info["smtp_email_port"] ?? '';
	$_SESSION['contact_info_smtp_email_port'] = $site_contact_info["smtp_email_port"] ?? NULL;
	$contact_info_smtp_email_username = $site_contact_info["smtp_email_username"] ?? '';
	$_SESSION['contact_info_smtp_email_username'] = $site_contact_info["smtp_email_username"] ?? '';
	$contact_info_smtp_email_password = $site_contact_info["smtp_email_password"] ?? '';
	$_SESSION['contact_info_smtp_email_password'] = $site_contact_info["smtp_email_password"] ?? '';	
	$contact_info_custom_fields = $site_contact_info["custom_fields"] ?? '{}';
	$contact_info_created_by = $site_contact_info["created_by"] ?? '';
	$contact_info_created_date = $site_contact_info["created_date"] ?? NULL;
	$contact_info_updated_by = $site_contact_info["updated_by"] ?? '';
	$contact_info_updated_date = $site_contact_info["updated_date"] ?? NULL;
}