<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/blocking-spam.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/blocking-spam.php');
}
else
{
	//Blocking Spam
	$blocking_spam_settings = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'blocking_spam', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
	
	$reviews_blocked_keywords = $blocking_spam_settings["reviews_blocked_keywords"] ?? '';
	$reviews_block_links = $blocking_spam_settings["reviews_block_links"] ?? 'No';
	$q_and_a_blocked_keywords = $blocking_spam_settings["q_and_a_blocked_keywords"] ?? '';
	$q_and_a_block_links = $blocking_spam_settings["q_and_a_block_links"] ?? 'No';
	$comments_blocked_keywords = $blocking_spam_settings["comments_blocked_keywords"] ?? '';
	$comments_block_links = $blocking_spam_settings["comments_block_links"] ?? 'No';
	$forms_blocked_keywords = $blocking_spam_settings["forms_blocked_keywords"] ?? '';
	$forms_block_links = $blocking_spam_settings["forms_block_links"] ?? 'No';
	$forms_time_on_site_set = $blocking_spam_settings["forms_time_on_site"] ?? 0;
	$forms_pageviews_set = $blocking_spam_settings["forms_pageviews"] ?? 1;
}