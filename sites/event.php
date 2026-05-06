<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/sites/event.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/sites/event.php');
}
else
{
	if($collect_analytics_data == 'Yes' && isset($_SESSION['analytics_cookie_id']) && !empty($_SESSION['analytics_cookie_id']) && isset($_POST['event_name']) && !empty($_POST['event_name']))
	{
		if($_SERVER['REQUEST_METHOD'] !== 'POST')
		{
			http_response_code(405);
			exit;
		}
		
		$event_name = $_POST['event_name'];
		$event_name_value = 'Event name: '.$event_name;
		
		$event_value = NULL;
		if(isset($_POST['event_value']) && !empty($_POST['event_value']) && is_numeric($_POST['event_value']))
		{
			$event_value = $_POST['event_value'];
			$event_name_value .= ' | Event value: '.$event_value;
		}
		
		$event_name_hashed = md5($event_name_value);
		
		$cookie_ip_hash = md5($_SESSION['analytics_cookie_id'].'_'.$masked_ip_hash);
		
		if(!isset($_SESSION['referer_domain']))
		{
			$_SESSION['referer_domain'] = '';
		}
		
		if(!isset($_SESSION['referer_url']))
		{
			$_SESSION['referer_url'] = '';
		}
		
		if(!isset($_SESSION['analytics_unique_id']))
		{
			$_SESSION['analytics_unique_id'] = NULL;
		}
		
		//Get time with offset for acutally visit date
		$utc_now = gmdate('Y-m-d H:i:s');
		$visit_date = utcToUserTimeZone($utc_now, 'Y-m-d');
		
		//Insert analytics when an event is triggered
		$inserted_analytics_row_id = $results->getInsertRecord(__LINE__, __FILE__, 'analytics', '`site_id`, `analytics_unique_id`, `analytics_cookie_id`, `masked_ip_hash`, `cookie_ip_hash`, `referer_source`, `referer_url`, `pageview_url`, `pageview_hash`, `cart_id`, `has_cart_contact_info`, `orders_id`, `has_order`, `total_product_amount`, `total_shipping_amount`, `total_tax_amount`, `total_coupon_discount_amount`, `total_order_amount`, `total_order_standard_cost_amount`, `total_order_landed_cost_amount`, `leads_id`, `has_form_conversion`, `form_conversion_value`, `event_name`, `event_value`, `affiliate_account_id`, `visit_date`, `created_date`', '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()', [$_SESSION['site_id'], $_SESSION['analytics_unique_id'], $_SESSION['analytics_cookie_id'], $masked_ip_hash, $cookie_ip_hash, $_SESSION['referer_domain'], $_SESSION['referer_url'], $event_name_value, $event_name_hashed, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, $event_name, $event_value, $_SESSION['affiliate_id'], $visit_date]);
		
		if(empty($_SESSION['analytics_unique_id']))
		{
			//First record ever inserted for analytics cookie id.
			$sql_analytics_cookie_id = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'analytics', 'WHERE `site_id` = ? AND `analytics_cookie_id` = ? ORDER BY `id` ASC LIMIT 1', [$site_id, $_SESSION['analytics_cookie_id']]);
			
			//First record ever inserted for analytics cookie id.
			$sql_masked_ip_hash = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'analytics', 'WHERE `site_id` = ? AND `masked_ip_hash` = ? ORDER BY `id` ASC LIMIT 1', [$site_id,  $masked_ip_hash]);
			
			$set_analytics_first_id = NULL;
			
			$analytics_ids = [];
			if(!empty($sql_analytics_cookie_id))
			{
				$analytics_ids[] = $sql_analytics_cookie_id['id'];
				$set_analytics_first_id = min($analytics_ids);
			}
			if(!empty($sql_masked_ip_hash))
			{
				$analytics_ids[] = $sql_masked_ip_hash['id'];
				$set_analytics_first_id = min($analytics_ids);
			}
			
			if(isset($set_analytics_first_id) && !empty($set_analytics_first_id))
			{
				$_SESSION['analytics_unique_id'] = $set_analytics_first_id;
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'analytics', '`analytics_unique_id` = ?', 'WHERE `id` = ?', [$_SESSION['analytics_unique_id'], $inserted_analytics_row_id]);
			}
		}
	}
}