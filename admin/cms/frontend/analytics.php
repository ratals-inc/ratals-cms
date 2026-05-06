<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/analytics.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/analytics.php');
}
else
{
	if($collect_analytics_data == 'Yes')
	{
		//If 'analytics' cookie is set, use it to set 'analytics_cookie_id'
		if(isset($_COOKIE['analytics']) && !empty($_COOKIE['analytics']))
		{
			$_SESSION['analytics_cookie_id'] = $_COOKIE['analytics'];
		}
		//If 'analytics' cookie is not set, create one and set on 'analytics_cookie_id'.
		elseif(!isset($_COOKIE['analytics']))
		{
			function get_analytics_cookie($analytics_cookie_string)
			{
				$sql_analytics_cookie_count = $_SESSION['results']->getSelectCountRecords(__LINE__, __FILE__, '*', 'analytics', 'WHERE `site_id` = ? AND `analytics_cookie_id` = ? LIMIT 1', [$_SESSION['site_id'], $analytics_cookie_string]);
				
				if(!empty($sql_analytics_cookie_count))
				{
					//Create unique secret for this install.
					try
					{
						//Preferred: php cryptographically secure.
						$analytics_cookie_string = bin2hex(random_bytes(16)); //32 chars
					}
					catch(Exception $e)
					{
						//Fallback if php cryptographically secure fails.
						$analytics_cookie_string = '0123456789abcdefghijklmnopqrstuvwxyz';
						$analytics_cookie_string = substr(str_shuffle($analytics_cookie_string), 0, 32);
					}
					
					//Test a different cookie number if last cookie number is in uses.
					get_analytics_cookie($analytics_cookie_string);
				}
				
				//Return cookie number thats not being used.
				return $analytics_cookie_string;
			}
			
			//Generate a analytics cookie id that does not exist in db. This unquie cookie id will be used if a visitor does not have a cookie set in their browser already.
			try
			{
				//Preferred: php cryptographically secure.
				$analytics_cookie_string = bin2hex(random_bytes(16)); //32 chars
			}
			catch(Exception $e)
			{
				//Fallback if php cryptographically secure fails.
				$analytics_cookie_string = '0123456789abcdefghijklmnopqrstuvwxyz';
				$analytics_cookie_string = substr(str_shuffle($analytics_cookie_string), 0, 32);
			}
			
			$analytics_cookie = get_analytics_cookie($analytics_cookie_string);
			
			$_SESSION['analytics_cookie_id'] = $analytics_cookie; 
		}
		
		if(!isset($_SESSION['referer_domain']))
		{
			$_SESSION['referer_domain'] = '';
		}
		
		if(!isset($_SESSION['referer_url']))
		{
			$_SESSION['referer_url'] = '';
		}
		
		if(!isset($_SESSION['affiliate_id']))
		{
			$_SESSION['affiliate_id'] = NULL;
		}
		
		if(isset($_SESSION['user_id']) && !empty(trim($_SESSION['user_first_last_name'] ?? '')))
		{
			$_SESSION['referer_domain'] = $_SESSION['user_first_last_name'];
		}
		elseif(isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'https://www.'.$domain_only) === false && strpos($_SERVER['HTTP_REFERER'], 'https://'.$domain_only) === false && strpos($_SERVER['HTTP_REFERER'], 'http://www.'.$domain_only) === false && strpos($_SERVER['HTTP_REFERER'], 'http://'.$domain_only) === false)
		{
			$referer_domain_string = str_replace('https://www.', '', $_SERVER['HTTP_REFERER']);
			$referer_domain_string = str_replace('http://www.', '', $referer_domain_string);
			$referer_domain_string = str_replace('https://', '', $referer_domain_string);
			$referer_domain_string = str_replace('http://', '', $referer_domain_string);
			$referer_domain_array = explode('/', $referer_domain_string);
			$_SESSION['referer_domain'] = $referer_domain_array[0];
			
			$_SESSION['referer_url'] = $_SERVER['HTTP_REFERER'];
			
			//If there is a traffic source set in the url that match traffic sources, set the source name as the referer domain so all of the traffic goes into the same bucket.
			$sql_tracking_sources = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ?', ['analytics_traffic_sources']);
			if(!empty($sql_tracking_sources))
			{
				foreach($sql_tracking_sources as $sql_tracking_source)
				{
					if(!empty($_SESSION['referer_url']) && strpos($_SESSION['referer_url'], $sql_tracking_source['value']) !== false)
					{
						$_SESSION['referer_domain'] = $sql_tracking_source['label'];
						break;
					}
				}
			}
		}
		
		//If order is placed, get order data.
		$conversion_order_number = NULL;
		if(isset($_SESSION['order_number']) && !empty($_SESSION['order_number']))
		{
			$conversion_order_number_array = explode('-', $_SESSION['order_number']);
			$conversion_order_number = $conversion_order_number_array[0];
			
			//Insert analytics with order data
			$sql_order_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'orders', 'WHERE `id` = ? AND `site_id` = ?', [$conversion_order_number, $site_id]);
		}
		
		$has_cart_contact_info = 0;
		if(isset($_SESSION['abandonment_cart_lead']) && $_SESSION['abandonment_cart_lead'] == 'Yes')
		{
			$has_cart_contact_info = '1';
		}
		
		if(!isset($_SESSION['analytics_unique_id']))
		{
			$_SESSION['analytics_unique_id'] = NULL;
		}
		
		//md5 urls for a short value for indexing database column
		$pageview_hash = md5($url); 
		
		$cookie_ip_hash = md5($_SESSION['analytics_cookie_id'].'_'.$masked_ip_hash);
		
		//Get utc time then offset for acutally visit date for admin user timezone.
		$utc_now = gmdate('Y-m-d H:i:s');
		$visit_date = utcToUserTimeZone($utc_now, 'Y-m-d');
		
		//Insert analytics data
		if(isset($sql_order_data) && !empty($sql_order_data))
		{
			//Insert analytics data when order is placed.
			$inserted_analytics_row_id = $results->getInsertRecord(__LINE__, __FILE__, 'analytics', '`site_id`, `analytics_unique_id`, `analytics_cookie_id`, `masked_ip_hash`, `cookie_ip_hash`, `referer_source`, `referer_url`, `pageview_url`, `pageview_hash`, `cart_id`, `has_cart_contact_info`, `orders_id`, `has_order`, `total_product_amount`, `total_shipping_amount`, `total_tax_amount`, `total_coupon_discount_amount`, `total_order_amount`, `total_order_standard_cost_amount`, `total_order_landed_cost_amount`, `leads_id`, `has_form_conversion`, `form_conversion_value`, `event_name`, `event_value`, `affiliate_account_id`, `visit_date`, `created_date`', '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()', [$site_id, $_SESSION['analytics_unique_id'], $_SESSION['analytics_cookie_id'], $masked_ip_hash, $cookie_ip_hash, $_SESSION['referer_domain'], $_SESSION['referer_url'], $url, $pageview_hash, NULL, 0, $sql_order_data['id'], 1, $sql_order_data['total_product_amount'], $sql_order_data['total_shipping_amount'], $sql_order_data['total_tax_amount'], $sql_order_data['total_coupon_discount_amount'], $sql_order_data['total_order_amount'], $sql_order_data['total_order_standard_cost_amount'], $sql_order_data['total_order_landed_cost_amount'], NULL, 0, NULL, '', NULL, $_SESSION['affiliate_id'], $visit_date]);
			
			//Update analytics data rows where abandonment cart id was set as they checked out.
			if(isset($analytics_cart_cookie_id) && !empty($analytics_cart_cookie_id))
			{
				$results->getUpdateRecord(__LINE__, __FILE__, 'analytics', '`cart_id` = ?, `has_cart_contact_info` = ?', 'WHERE `cart_id` = ?', [NULL, 0, $analytics_cart_cookie_id]);
			}
		}
		elseif(isset($_SESSION['cart_id']) && !empty($_SESSION['cart_id']) && $has_cart_contact_info == 1)
		{
			//Insert analytics data when abandonment cart.
			$inserted_analytics_row_id = $results->getInsertRecord(__LINE__, __FILE__, 'analytics', '`site_id`, `analytics_unique_id`, `analytics_cookie_id`, `masked_ip_hash`, `cookie_ip_hash`, `referer_source`, `referer_url`, `pageview_url`, `pageview_hash`, `cart_id`, `has_cart_contact_info`, `orders_id`, `has_order`, `total_product_amount`, `total_shipping_amount`, `total_tax_amount`, `total_coupon_discount_amount`, `total_order_amount`, `total_order_standard_cost_amount`, `total_order_landed_cost_amount`, `leads_id`, `has_form_conversion`, `form_conversion_value`, `event_name`, `event_value`, `affiliate_account_id`, `visit_date`, `created_date`', '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()', [$site_id, $_SESSION['analytics_unique_id'], $_SESSION['analytics_cookie_id'], $masked_ip_hash, $cookie_ip_hash, $_SESSION['referer_domain'], $_SESSION['referer_url'], $url, $pageview_hash, $_SESSION['cart_id'], $has_cart_contact_info, NULL, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, '', NULL, $_SESSION['affiliate_id'], $visit_date]);
		}
		elseif(isset($_SESSION['form_conversion']) && !empty($_SESSION['form_conversion']))
		{
			//Insert analytics data when form conversion out.
			$inserted_analytics_row_id = $results->getInsertRecord(__LINE__, __FILE__, 'analytics', '`site_id`, `analytics_unique_id`, `analytics_cookie_id`, `masked_ip_hash`, `cookie_ip_hash`, `referer_source`, `referer_url`, `pageview_url`, `pageview_hash`, `cart_id`, `has_cart_contact_info`, `orders_id`, `has_order`, `total_product_amount`, `total_shipping_amount`, `total_tax_amount`, `total_coupon_discount_amount`, `total_order_amount`, `total_order_standard_cost_amount`, `total_order_landed_cost_amount`, `leads_id`, `has_form_conversion`, `form_conversion_value`, `event_name`, `event_value`, `affiliate_account_id`, `visit_date`, `created_date`', '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()', [$site_id, $_SESSION['analytics_unique_id'], $_SESSION['analytics_cookie_id'], $masked_ip_hash, $cookie_ip_hash, $_SESSION['referer_domain'], $_SESSION['referer_url'], $url, $pageview_hash, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, NULL, NULL, $_SESSION['form_lead_id'], 1, $_SESSION['form_conversion_value'], '', NULL, $_SESSION['affiliate_id'], $visit_date]);
		}
		else
		{
			//Insert analytics data when normal pageview.
			$inserted_analytics_row_id = $results->getInsertRecord(__LINE__, __FILE__, 'analytics', '`site_id`, `analytics_unique_id`, `analytics_cookie_id`, `masked_ip_hash`, `cookie_ip_hash`, `referer_source`, `referer_url`, `pageview_url`, `pageview_hash`, `cart_id`, `has_cart_contact_info`, `orders_id`, `has_order`, `total_product_amount`, `total_shipping_amount`, `total_tax_amount`, `total_coupon_discount_amount`, `total_order_amount`, `total_order_standard_cost_amount`, `total_order_landed_cost_amount`, `leads_id`, `has_form_conversion`, `form_conversion_value`, `event_name`, `event_value`, `affiliate_account_id`, `visit_date`, `created_date`', '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()', [$site_id, $_SESSION['analytics_unique_id'], $_SESSION['analytics_cookie_id'], $masked_ip_hash, $cookie_ip_hash, $_SESSION['referer_domain'], $_SESSION['referer_url'], $url, $pageview_hash, NULL, 0, NULL, 0, 0, 0, 0, NULL, 0, NULL, NULL, NULL, 0, NULL, '', NULL, $_SESSION['affiliate_id'], $visit_date]);
		}
		
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
	
	unset($analytics_cart_cookie_id);
	unset($_SESSION['form_lead_id']);
	unset($_SESSION['form_conversion']);
	unset($_SESSION['form_conversion_value']);
	unset($_SESSION['abandonment_cart_lead']);
}