<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/headers/dashboard.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/headers/dashboard.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'dashboard')
	{
		$todays_date = usersTodaysDate();
		
		$interval = 'day';
		$interval_range = '%Y-%m-%d';
		if(isset($_GET['interval']) && $_GET['interval'] == 'day')
		{
			$interval = 'day';
			$interval_range = '%Y-%m-%d';
		}
		elseif(isset($_GET['interval']) && $_GET['interval'] == 'month')
		{
			$interval = 'month';
			$interval_range = '%Y-%m';
		}
		elseif(isset($_GET['interval']) && $_GET['interval'] == 'year')
		{
			$interval = 'year';
			$interval_range = '%Y';
		}
		
		//Date range search
		if(!empty($_GET['from_date']) && !empty($_GET['to_date']))
		{
			$from_date = dateToUtc(trim($_GET['from_date']), '00:00:00');
			$to_date = dateToUtc(trim($_GET['to_date']), '23:59:59');
		
			$from_date_date_only = trim($_GET['from_date']);
			$to_date_date_only = trim($_GET['to_date']);
		}
		//Current Day - loads by default
		else
		{
			$from_date = dateToUtc($todays_date, '00:00:00');
			$to_date = dateToUtc($todays_date, '23:59:59');
		
			$from_date_date_only = $todays_date;
			$to_date_date_only = $todays_date;
		}
		
		if(isset($_GET['source']))
		{
			$sql_analytics = $results->getSelectMultipleRecordsKeyNameTwoFull(__LINE__, __FILE__, 
				'
				DATE_FORMAT(CONVERT_TZ(`analytics`.`created_date`,"UTC","'.$_SESSION['timezone'].'"), "'.$interval_range.'") AS date_for_timezone,
				(CASE WHEN `analytics`.`referer_source` != "" THEN `analytics`.`referer_source` ELSE "Direct" END) AS referer_source,
				COUNT(DISTINCT analytics_unique_id) AS unique_visitors,
				COUNT(`analytics`.`analytics_cookie_id`) AS pageviews,
				COUNT(`analytics`.`orders_id`) AS orders,
				(COUNT(`analytics`.`orders_id`) / NULLIF(COUNT(DISTINCT `analytics`.`analytics_unique_id`), 0)) * 100 AS unique_visitors_conversion_rate,
				(COUNT(`analytics`.`orders_id`) / COUNT(`analytics`.`analytics_cookie_id`)) * 100 AS pageviews_conversion_rate,
				SUM(`analytics`.`total_product_amount`) AS total_product_amount,
				SUM(`analytics`.`total_shipping_amount`) AS total_shipping_amount,
				SUM(`analytics`.`total_tax_amount`) AS total_tax_amount,
				SUM(`analytics`.`total_coupon_discount_amount`) AS total_coupon_discount_amount,
				SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) / NULLIF(COUNT(`analytics`.`orders_id`),0) AS average_order_amount,
				SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) AS order_total,
				SUM(`analytics`.`total_order_standard_cost_amount`) AS standard_cost,
				SUM(`analytics`.`total_order_landed_cost_amount`) AS landed_cost,
				(SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) - SUM(`analytics`.`total_order_standard_cost_amount`)) AS standard_cost_profit_amount,
				((SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) - SUM(`analytics`.`total_order_standard_cost_amount`)) / NULLIF(SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`),0)) * 100 AS standard_cost_profit_percentage,
				(SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) - SUM(`analytics`.`total_order_landed_cost_amount`)) AS landed_cost_profit_amount,
				((SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) - SUM(`analytics`.`total_order_landed_cost_amount`)) / NULLIF(SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`),0)) * 100 AS landed_cost_profit_percentage,
				SUM(`analytics`.`has_cart_contact_info`) AS abandonment_cart_leads,
				SUM(`analytics`.`has_form_conversion`) AS form_conversions,
				SUM(`analytics`.`form_conversion_value`) AS form_conversion_values
				', 
				'analytics', 
				'WHERE `analytics`.`site_id` = ? 
				  AND `analytics`.`created_date` BETWEEN ? AND ? 
				GROUP BY DATE_FORMAT(CONVERT_TZ(`analytics`.`created_date`,"UTC","'.$_SESSION['timezone'].'"), "'.$interval_range.'"), CASE WHEN `analytics`.`referer_source` != "" THEN `analytics`.`referer_source` ELSE "Direct" END
				ORDER BY date_for_timezone DESC, unique_visitors DESC, referer_source ASC',
				[
					$_SESSION["site_set_for_editing"], $from_date, $to_date
				], 
				'date_for_timezone', 'referer_source'
			);
		}
		else
		{
			$sql_analytics = $results->getSelectMultipleRecordsKeyNameArray(__LINE__, __FILE__, 
				'
				DATE_FORMAT(CONVERT_TZ(`analytics`.`created_date`,"UTC","'.$_SESSION['timezone'].'"), "'.$interval_range.'") AS date_for_timezone,
				COUNT(DISTINCT analytics_unique_id) AS unique_visitors,
				COUNT(`analytics`.`analytics_cookie_id`) AS pageviews,
				COUNT(`analytics`.`orders_id`) AS orders,
				(COUNT(`analytics`.`orders_id`) / NULLIF(COUNT(DISTINCT `analytics`.`analytics_unique_id`), 0)) * 100 AS unique_visitors_conversion_rate,
				(COUNT(`analytics`.`orders_id`) / COUNT(`analytics`.`analytics_cookie_id`)) * 100 AS pageviews_conversion_rate,
				SUM(`analytics`.`total_product_amount`) AS total_product_amount,
				SUM(`analytics`.`total_shipping_amount`) AS total_shipping_amount,
				SUM(`analytics`.`total_tax_amount`) AS total_tax_amount,
				SUM(`analytics`.`total_coupon_discount_amount`) AS total_coupon_discount_amount,
				SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) / NULLIF(COUNT(`analytics`.`orders_id`),0) AS average_order_amount,
				SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) AS order_total,
				SUM(`analytics`.`total_order_standard_cost_amount`) AS standard_cost,
				SUM(`analytics`.`total_order_landed_cost_amount`) AS landed_cost,
				(SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) - SUM(`analytics`.`total_order_standard_cost_amount`)) AS standard_cost_profit_amount,
				((SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) - SUM(`analytics`.`total_order_standard_cost_amount`)) / NULLIF(SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`),0)) * 100 AS standard_cost_profit_percentage,
				(SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) - SUM(`analytics`.`total_order_landed_cost_amount`)) AS landed_cost_profit_amount,
				((SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`) - SUM(`analytics`.`total_order_landed_cost_amount`)) / NULLIF(SUM(`analytics`.`total_order_amount` - `analytics`.`total_tax_amount`),0)) * 100 AS landed_cost_profit_percentage,
				SUM(`analytics`.`has_cart_contact_info`) AS abandonment_cart_leads,
				SUM(`analytics`.`has_form_conversion`) AS form_conversions,
				SUM(`analytics`.`form_conversion_value`) AS form_conversion_values
				', 
				'analytics', 
				'WHERE `analytics`.`site_id` = ? 
				  AND `analytics`.`created_date` BETWEEN ? AND ? 
				GROUP BY DATE_FORMAT(CONVERT_TZ(`analytics`.`created_date`,"UTC","'.$_SESSION['timezone'].'"), "'.$interval_range.'")
				ORDER BY date_for_timezone DESC, unique_visitors DESC',
				[
					$_SESSION["site_set_for_editing"], $from_date, $to_date
				], 
				'date_for_timezone'
			);
		}
		
		$add_mysql_timezones = '';
		if(empty($sql_analytics))
		{
			$first_group = $sql_analytics[array_key_first($sql_analytics)] ?? [];
			$first_row = $first_group[0] ?? [];
			if(!empty($first_row['date_for_timezone']))
			{
				$add_mysql_timezones = '<div class="mysql-timezones">It appears that MySQL timezones are not installed or configured on this server. Please install MySQL timezone data to ensure dates display correctly in the admin area. This message will automatically disappear once the timezones are installed.</div>';
			}
		}
		
		$sql_form_leads = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'leads', 'WHERE `site_id` = ? AND `lead_status` = ? AND `created_date` BETWEEN ? AND ? ', [$_SESSION["site_set_for_editing"], 'Active', $from_date, $to_date]);
		
		$sql_pending_posts_comments = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'comments', 'WHERE `site_id` = ? AND `status` = ? AND `created_date` BETWEEN ? AND ? ', [$_SESSION["site_set_for_editing"], '2', $from_date, $to_date]);
		
		$sql_top_site_searches = $results->getSelectMultipleRecords(__LINE__, __FILE__, '`keyword`, COUNT(*) AS total_searches', 'site_search', 'WHERE `site_id` = ? AND `created_date` BETWEEN ? AND ?  GROUP BY `keyword` ORDER BY `total_searches` DESC LIMIT 5', [$_SESSION["site_set_for_editing"], $from_date, $to_date]);
		
		$sql_top_ip_hits = $results->getSelectMultipleRecords(__LINE__, __FILE__, '`ip_address`,COUNT(`ip_address`) AS counter', 'ddos_tracking', 'WHERE `created_date` > UTC_TIMESTAMP() - INTERVAL ? MINUTE GROUP BY `ip_address` ORDER BY counter DESC LIMIT 5', [1440]);
		
		$sql_top_ip_with_failed_login_attempts = $results->getSelectMultipleRecords(__LINE__, __FILE__, '`ip_address`,COUNT(`ip_address`) AS counter', 'failed_logins', 'WHERE `created_date` > UTC_TIMESTAMP() - INTERVAL ? MINUTE GROUP BY `ip_address` ORDER BY counter DESC LIMIT 5', [1440]);
		
		$sql_top_404_errors = $results->getSelectMultipleRecords(__LINE__, __FILE__, '`url_404`, COUNT(*) AS total_404s', 'errors_404', 'WHERE `site_id` = ? AND `created_date` BETWEEN ? AND ?  GROUP BY `url_404` ORDER BY `total_404s` DESC LIMIT 5', [$_SESSION["site_set_for_editing"], $from_date, $to_date]);
		
		$sql_lifetime_order_amount = 0;
		$sql_pending_affiliate_applications = array();
		$sql_pending_reviews = array();
		$sql_pending_q_and_a = array();
		$sql_lowest_inventory = array();
		$sql_shipping_feeds = array();
		$sql_top_ip_with_declined_card_attempts = array();
		
		if($commerce_installed)
		{
			$sql_lifetime_order_amount = $results->getSelectSingleRecord(__LINE__, __FILE__, 'SUM(total_order_amount) as lifetime_order_amount', 'orders', 'WHERE `site_id` = ?', [$_SESSION["site_set_for_editing"]]);
			
			$sql_pending_affiliate_applications = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'affiliates', 'WHERE `site_id` = ? AND `status` = ?', [$_SESSION["site_set_for_editing"], 2]);
			
			$sql_pending_reviews = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'reviews', 'WHERE `site_id` = ? AND `status` = ? AND `created_date` BETWEEN ? AND ? ', [$_SESSION["site_set_for_editing"], '2', $from_date, $to_date]);
			
			$sql_pending_q_and_a = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'q_and_a', 'WHERE `site_id` = ? AND `status` = ? AND `created_date` BETWEEN ? AND ? ', [$_SESSION["site_set_for_editing"], '2', $from_date, $to_date]);
			
			$sql_lowest_inventory = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'inventory', 'WHERE `status` = ? AND `has_dropship_center` = ? ORDER BY `total_qty_available` ASC LIMIT 5', ['1', 'No']);
			
			$sql_shipping_feeds = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'shopping_feeds', 'ORDER BY `sub_items` DESC LIMIT 5', []);
			
			$sql_top_ip_with_declined_card_attempts = $results->getSelectMultipleRecords(__LINE__, __FILE__, '`ip_address`,COUNT(`ip_address`) AS counter', 'declined_card_attempts', 'WHERE `created_date` > UTC_TIMESTAMP() - INTERVAL ? MINUTE GROUP BY `ip_address` ORDER BY counter DESC LIMIT 5', [1440]);
		}
	}
}