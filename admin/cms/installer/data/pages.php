<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$column_names = '`id`, `site_id`, `urls_id`, `number_of_posts`, `number_of_sitemap_results`, `grid_columns`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$placeholders = '?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = array();

//Homepage
$parameters[] = [$_SESSION['install_ids'][$site_id]['homepage_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['homepage_url_id'] ?? 0, NULL, NULL, '2', '{}', $first_last_name, $first_last_name];

//Blog
$parameters[] = [$_SESSION['install_ids'][$site_id]['blog_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['blog_page_url_id'] ?? 0, NULL, NULL, '2', '{}', $first_last_name, $first_last_name];

//About Us
$parameters[] = [$_SESSION['install_ids'][$site_id]['about_us_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['about_us_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Contact Us
$parameters[] = [$_SESSION['install_ids'][$site_id]['contact_us_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['contact_us_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Search
$parameters[] = [$_SESSION['install_ids'][$site_id]['site_search_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['site_search_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Cookie Policy
$parameters[] = [$_SESSION['install_ids'][$site_id]['cookie_policy_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['cookie_policy_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Privacy Policy
$parameters[] = [$_SESSION['install_ids'][$site_id]['privacy_policy_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['privacy_policy_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Terms of Use
$parameters[] = [$_SESSION['install_ids'][$site_id]['terms_of_use_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['terms_of_use_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Clients
$parameters[] = [$_SESSION['install_ids'][$site_id]['clients_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['clients_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Portfolio
$parameters[] = [$_SESSION['install_ids'][$site_id]['portfolio_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['portfolio_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Our Story
$parameters[] = [$_SESSION['install_ids'][$site_id]['our_story_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['our_story_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//FAQs
$parameters[] = [$_SESSION['install_ids'][$site_id]['faqs_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['faqs_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//404
$parameters[] = [$_SESSION['install_ids'][$site_id]['a404_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['a404_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Message Confirmation
$parameters[] = [$_SESSION['install_ids'][$site_id]['message_confirmation_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['message_confirmation_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Quote Confirmation
$parameters[] = [$_SESSION['install_ids'][$site_id]['quote_confirmation_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['quote_confirmation_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//HTML Sitemap
$parameters[] = [$_SESSION['install_ids'][$site_id]['sitemap_html_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['sitemap_html_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//HTML Sitemap Section
$parameters[] = [$_SESSION['install_ids'][$site_id]['sitemap_html_section_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['sitemap_html_section_page_url_id'] ?? 0, 50, NULL, '', '{}', $first_last_name, $first_last_name];

//XML Sitemap
$parameters[] = [$_SESSION['install_ids'][$site_id]['sitemap_xml_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['sitemap_xml_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

//Robots
$parameters[] = [$_SESSION['install_ids'][$site_id]['robots_page_id'] ?? NULL, $site_id, $_SESSION['install_ids'][$site_id]['robots_page_url_id'] ?? 0, NULL, NULL, '', '{}', $first_last_name, $first_last_name];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'pages', $column_names, $placeholders, $parameters);