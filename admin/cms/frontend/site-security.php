<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/site-security.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/site-security.php');
}
else
{
	//Site Security Settings - Select everything from site_security table.
	$site_security_settings = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'site_security', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
	
	$top_ip_address = $site_security_settings["top_ip_address"] ?? 10;
	$site_blocked_ips = $site_security_settings["site_blocked_ips"] ?? '';
	$site_allowed_ips = $site_security_settings["site_allowed_ips"] ?? '';
	$block_ip_failed_login = $site_security_settings["block_ip_failed_login"] ?? 'Yes';
	$number_of_failed_login_attempts = $site_security_settings["number_of_failed_login_attempts"] ?? 5;
	$failed_login_attempts_email_me = $site_security_settings["failed_login_attempts_email_me"] ?? 'No';
	$failed_login_attempts_to_email_address = $site_security_settings["failed_login_attempts_to_email_address"] ?? '';
	$failed_login_attempts_to_email_name = $site_security_settings["failed_login_attempts_to_email_name"] ?? '';
	$failed_login_attempts_email_cc = $site_security_settings["failed_login_attempts_email_cc"] ?? '';
	$failed_login_attempts_email_bcc = $site_security_settings["failed_login_attempts_email_bcc"] ?? '';
	$failed_login_blocked_ips = $site_security_settings["failed_login_blocked_ips"] ?? '';
	$sql_injection_email_me = $site_security_settings["sql_injection_email_me"] ?? 'No';
	$sql_injection_to_email_address = $site_security_settings["sql_injection_to_email_address"] ?? '';
	$sql_injection_to_email_name = $site_security_settings["sql_injection_to_email_name"] ?? '';
	$sql_injection_email_cc = $site_security_settings["sql_injection_email_cc"] ?? '';
	$sql_injection_email_bcc = $site_security_settings["sql_injection_email_bcc"] ?? '';
	$max_pageviews_block = $site_security_settings["max_pageviews_block"] ?? 500;
	$time_period_block = $site_security_settings["time_period_block"] ?? 60;
	$auto_blocked_ip_email_me = $site_security_settings["auto_blocked_ip_email_me"] ?? '';
	$ddos_to_email_address = $site_security_settings["ddos_to_email_address"] ?? '';
	$ddos_to_email_name = $site_security_settings["ddos_to_email_name"] ?? '';
	$ddos_email_cc = $site_security_settings["ddos_email_cc"] ?? '';
	$ddos_email_bcc = $site_security_settings["ddos_email_bcc"] ?? '';
	$ddos_blocked_ips = $site_security_settings["ddos_blocked_ips"] ?? '';
	$days_save_declined_attempts = $site_security_settings["days_save_declined_attempts"] ?? 30;
	$max_declined_cards_block = $site_security_settings["max_declined_cards_block"] ?? 10;
	$declined_cards_block_ip_email_me = $site_security_settings["declined_cards_block_ip_email_me"] ?? '';
	$declined_cards_to_email_address = $site_security_settings["declined_cards_to_email_address"] ?? '';
	$declined_cards_to_name = $site_security_settings["declined_cards_to_name"] ?? '';
	$declined_cards_email_cc = $site_security_settings["declined_cards_email_cc"] ?? '';
	$declined_cards_email_bcc = $site_security_settings["declined_cards_email_bcc"] ?? '';
	$declined_cards_blocked_ips = $site_security_settings["declined_cards_blocked_ips"] ?? '';
	
	//Get Blocked IP Addresses
	$blocked_ip_addresses = array();
	if(!empty($site_blocked_ips))
	{
		$blocked_ip_addresses = array_filter(array_map('trim', explode(',', $site_blocked_ips)));
	}
	
	//Get Allowed IP Addresses
	$allowed_ip_addresses = array();
	if(!empty($site_allowed_ips))
	{
		$allowed_ip_addresses = array_filter(array_map('trim', explode(',', $site_allowed_ips)));
	}
}