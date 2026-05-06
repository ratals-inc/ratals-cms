<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//UPDATE SITE_SECURITY DEFAULTS ON ACCOUNT UPGRADE TO COMMERCE.
try
{
	$all_sites_to_update = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'sites', '', [], 'id');
	$all_site_security_to_update = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'site_security', '', [], 'site_id');
	
	if(!empty($all_sites_to_update))
	{
		foreach($all_sites_to_update as $site_id_to_update)
		{
			if(isset($site_id_to_update['id']) && !empty($site_id_to_update['id']) && isset($all_site_security_to_update[$site_id_to_update['id']]['id']) && !empty($all_site_security_to_update[$site_id_to_update['id']]['id']) && isset($all_site_security_to_update[$site_id_to_update['id']]['days_save_declined_attempts']) && empty($all_site_security_to_update[$site_id_to_update['id']]['days_save_declined_attempts']) && isset($all_site_security_to_update[$site_id_to_update['id']]['max_declined_cards_block']) && empty($all_site_security_to_update[$site_id_to_update['id']]['max_declined_cards_block']) && isset($all_site_security_to_update[$site_id_to_update['id']]['declined_cards_block_ip_email_me']) && empty($all_site_security_to_update[$site_id_to_update['id']]['declined_cards_block_ip_email_me']) && isset($all_site_security_to_update[$site_id_to_update['id']]['declined_cards_email_address']) && empty($all_site_security_to_update[$site_id_to_update['id']]['declined_cards_email_address']) && isset($all_site_security_to_update[$site_id_to_update['id']]['declined_cards_email_server_url']) && empty($all_site_security_to_update[$site_id_to_update['id']]['declined_cards_email_server_url']))
			{
				$update_columns = '`days_save_declined_attempts` = ?, `max_declined_cards_block` = ?, `declined_cards_block_ip_email_me` = ?, `declined_cards_email_address` = ?, `declined_cards_to_name` = ?, `declined_cards_email_from` = ?, `declined_cards_email_from_name` = ?, `declined_cards_email_server_url` = ?, `declined_cards_email_server_port` = ?, `declined_cards_email_username` = ?, `declined_cards_email_password` = ?';
				$update_where_clause = 'WHERE `id` = ?';
				$fields_to_update = array(30, 10, 'Email Me', $all_site_security_to_update[$site_id_to_update['id']]['auto_blocked_email_address'], $all_site_security_to_update[$site_id_to_update['id']]['ddos_to_name'], $all_site_security_to_update[$site_id_to_update['id']]['ddos_email_from'], $all_site_security_to_update[$site_id_to_update['id']]['ddos_email_from_name'], $all_site_security_to_update[$site_id_to_update['id']]['ddos_email_server_url'], $all_site_security_to_update[$site_id_to_update['id']]['ddos_email_server_port'], $all_site_security_to_update[$site_id_to_update['id']]['ddos_email_username'], $all_site_security_to_update[$site_id_to_update['id']]['ddos_email_password'], $all_site_security_to_update[$site_id_to_update['id']]['id']);
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'site_security', $update_columns, $update_where_clause, $fields_to_update);
				
			}
		}
	}
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Completed site security update check.');
	}
}
catch(\Throwable $e)
{
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Failed site security update: '.$e->getMessage());
	}
}