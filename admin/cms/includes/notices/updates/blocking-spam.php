<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//UPDATE BLOCKING SPAM DEFAULTS ON ACCOUNT UPGRADE TO COMMERCE.
try
{
	$all_sites_to_update = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'sites', '', [], 'id');
	$all_blocking_spam_to_update = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'blocking_spam', '', [], 'site_id');
	
	if(!empty($all_sites_to_update))
	{
		foreach($all_sites_to_update as $site_id_to_update)
		{
			if(isset($site_id_to_update['id']) && !empty($site_id_to_update['id']) && isset($all_blocking_spam_to_update[$site_id_to_update['id']]['id']) && !empty($all_blocking_spam_to_update[$site_id_to_update['id']]['id']) && isset($all_blocking_spam_to_update[$site_id_to_update['id']]['reviews_block_links']) && empty($all_blocking_spam_to_update[$site_id_to_update['id']]['reviews_block_links']) && isset($all_blocking_spam_to_update[$site_id_to_update['id']]['q_and_a_block_links']) && empty($all_blocking_spam_to_update[$site_id_to_update['id']]['q_and_a_block_links']) )
			{
				$update_columns = '`reviews_block_links` = ?, `q_and_a_block_links` = ?';
				$update_where_clause = 'WHERE `id` = ?';
				$fields_to_update = array('No', 'No', $all_blocking_spam_to_update[$site_id_to_update['id']]['id']);
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'blocking_spam', $update_columns, $update_where_clause, $fields_to_update);
			}
		}
	}
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Completed blocking spam update check.');
	}
}
catch(\Throwable $e)
{
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Failed blocking spam update: '.$e->getMessage());
	}
}