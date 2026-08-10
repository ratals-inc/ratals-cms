<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Check for messages with Ratals.

//Get php version.
$php_version = phpversion();

//Get mysql version
$my_sql_version = $results->getRawQuery(__LINE__, __FILE__, 'select version()', []);
$mysql_version = '';
if(isset($my_sql_version[0]['version()']))
{
	$mysql_version = $my_sql_version[0]['version()'];
}

$get_all_message_system_codes_received = $results->getselectMultipleRecordsOneColumn(__LINE__, __FILE__, '`system_code`', 'notices', '', [], 'system_code');

$post_url = 'https://www.ratals.com/api/notices/index.php?'.$url;

$api_data = [
	"authentication" => [
		"domain" => $domain,
		"user_ip" => $_SERVER['REMOTE_ADDR'],
		"country" => $_SESSION['contact_info_country'] ?? '',
		"state_region_province" => $_SESSION['contact_info_state'] ?? ''
	],
	"system_data" => [
		"php_version" => $php_version,
		"mysql_version" => $mysql_version,
		"current_version" => $current_software_version
	],
	"last_logged_in" => [
		"date" => $_SESSION['last_logged_in_user'] ?? NULL
	],
	"system_codes_already_sent" => $get_all_message_system_codes_received
];
$api_json = json_encode($api_data, JSON_UNESCAPED_SLASHES);

$curl_request = curl_init($post_url);
curl_setopt($curl_request, CURLOPT_POST, true);
curl_setopt($curl_request, CURLOPT_POSTFIELDS, $api_json);
curl_setopt($curl_request, CURLOPT_HEADER, 0);
curl_setopt($curl_request, CURLOPT_TIMEOUT, 5);
curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($curl_request, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($curl_request, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
$api_response = curl_exec($curl_request);
$curl_error = curl_error($curl_request);
$info = curl_getinfo($curl_request);
curl_close($curl_request);

$messages_to_insert = json_decode($api_response, true);

//View messages sent back.
//echo '<pre>'; print_r($messages_to_insert); echo '</pre>';
//die;

if(!empty($messages_to_insert))
{
	foreach($messages_to_insert as $message_to_insert)
	{
		$notice_upgrade_from = '';
		$notice_upgrade_to = '';
		
		if($message_to_insert['update_software'])
		{
			$notice_upgrade_from = '';
			$notice_upgrade_to = '';
		}
		
		$results->getInsertRecord(__LINE__, __FILE__, 'notices', '`id`, `site_id`, `status`, `notice_subject`, `notice_message`, `notice_url`, `notice_update_software`, `notice_upgrade_from`, `notice_upgrade_to`, `notice_software_version`, `required_php_version`, `required_mysql_version`, `system_code`, `custom_fields`, `created_date`', '?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP', [NULL, 0, 1, $message_to_insert['subject'], $message_to_insert['message'], 'ratals-core', $message_to_insert['update_software'], $notice_upgrade_from, $notice_upgrade_to, $message_to_insert['software_version'], $message_to_insert['required_php'], $message_to_insert['required_mysql'], $message_to_insert['system_code'], '{}']);
	}
}

$license_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'license', 'WHERE `site_id` = ? LIMIT 1', [0]);
$license_key = $license_data['license_key'] ?? '';
$install_id = $license_data['install_id'] ?? '';

$all_domains = array();
$domains = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'sites', '', [], '');
if(!empty($domains))
{
	foreach($domains as $set_domain)
	{
		$all_domains[] = $set_domain['domain'];
	}
}

$license_check_endpoint = 'https://www.ratals.com/api/license/index.php';

$post_data = [
	'domain' => $_SERVER['HTTP_HOST'] ?? '',
	'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
	'license_key' => $license_key,
	'install_id' => $install_id,
	'domains' => $all_domains
	
];

$ch = curl_init($license_check_endpoint);
curl_setopt_array($ch, [
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => http_build_query($post_data),
	CURLOPT_TIMEOUT => 5,
	CURLOPT_CONNECTTIMEOUT => 5,
	CURLOPT_SSL_VERIFYPEER => true,
	CURLOPT_SSL_VERIFYHOST => 2,
]);

$response = curl_exec($ch);
curl_close($ch);

$license_check = json_decode($response, true);

//If no valid response, don't update.
if(isset($license_check['license_status']) && isset($license_check['license_type']))
{
	$license_status = $license_check['license_status'] ?? 'Active';
	$license_type = strtolower($license_check['license_type'] ?? 'cms');
	$license_last_billing_date = $license_check['license_last_billing_date'] ?? NULL;
	$license_next_billing_date = $license_check['license_next_billing_date'] ?? NULL;
	$license_next_billing_amount = $license_check['license_next_billing_amount'] ?? 0;
	$license_billing_line_items = $license_check['license_billing_line_items'] ?? '';
	
	$current_license_type = 'CMS';
	if($license_type == 'commerce')
	{
		$current_license_type = 'Commerce';
	}
	elseif($license_type == 'erp')
	{
		$current_license_type = 'ERP';
	}
	elseif($license_type == 'ai')
	{
		$current_license_type = 'AI';
	}
	$results->getUpdateRecord(__LINE__, __FILE__, 'license', '`license_status` = ?, `license_type` = ?, `license_last_billing_date` = ?, `license_next_billing_date` = ?, `license_next_billing_amount` = ?, `license_billing_line_items` = ?, `last_seen` = UTC_TIMESTAMP', 'WHERE `site_id` = ?', [$license_status, $current_license_type, $license_last_billing_date, $license_next_billing_date, $license_next_billing_amount, $license_billing_line_items, 0]);
}