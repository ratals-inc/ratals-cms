<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/site-settings.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/site-settings.php');
}
else
{
	//SITE SETTING - Select everything from site_settings table.
	$site_settings = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'site_settings', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
	
	$site_name = $site_settings["site_name"] ?? '';
	$_SESSION['site_name'] = $site_settings["site_name"] ?? '';
	$add_site_name_to_title_tag = $site_settings["name_in_title_tag"] ?? 'Yes';
	$separate_site_name_in_title_tag_with = $site_settings["title_separator"] ?? '-';
	$store_pagination = $site_settings["store_pagination"] ?? 30;
	$blog_pagination = $site_settings["blog_pagination"] ?? 10;
	$display_breadcrumbs = $site_settings["display_breadcrumbs"] ?? 'Yes';
	$site_maintenance_mode = $site_settings["site_maintenance_mode"] ?? 'No';
	$display_stock_status = $site_settings["display_stock_status"] ?? 'Yes';
	$inventory_left_instock = $site_settings["inventory_left_instock"] ?? 5;
	$call_for_availability_phone_number = $site_settings["call_for_availability_phone_number"] ?? '';
	$inventory_variant_builder_max = $site_settings["inventory_variant_builder_max"] ?? 3000;
	$_SESSION['cart_line_item_max_qty'] = $site_settings["cart_line_item_max_qty"] ?? 9999;
	$display_discount_code_on_cart = $site_settings["display_discount_code_on_cart"] ?? 'Yes';
	$add_to_cart_redirect = $site_settings["add_to_cart_redirect"] ?? 'No';
	$allow_checkout_as_guest = $site_settings["allow_checkout_as_guest"] ?? 'Yes';
	$allow_customers_save_cards = $site_settings["customers_save_cards"] ?? 'No';
	$customer_account_url = $site_settings["customer_account_url"] ?? 0;
	$order_confirmation_url = $site_settings["order_confirmation_url"] ?? 0;
	$_SESSION['free_shipping'] = $site_settings["free_shipping"] ?? 'Yes';
	$_SESSION['free_shipping_cart_minimum'] = $site_settings["free_shipping_cart_minimum"] ?? 199.00;
	$_SESSION['state_or_postal_code'] = $site_settings["state_or_postal_code"] ?? 'State';
	$_SESSION['unit_of_measure'] = $site_settings["unit_of_measure"] ?? 'in';
	$_SESSION['unit_of_weight'] = $site_settings["unit_of_weight"] ?? 'lb';
	$site_search_results_per_page = $site_settings["site_search_results_per_page"] ?? 30;
	$site_search_max_results = $site_settings["site_search_max_results"] ?? 300;
	$lazy_load_media_row = $site_settings["lazy_load_media_row"] ?? 5; //used on sub-products page
	$default_video_icon = $site_settings["default_video_icon"] ?? 25;
	$default_file_icon = $site_settings["default_file_icon"] ?? 28;
	$display_cookie_notice = $site_settings["display_cookie_notice"] ?? 'Yes';
	$cookie_notice_url_id = $site_settings["cookie_notice_url"] ?? 22;
	$privacy_notice_url_id = $site_settings["privacy_notice_url"] ?? 23;
	$load_pages_with_cached_results = $site_settings["load_pages_with_cached_results"] ?? 'Yes';
	$seconds_between_cache_refreshing = $site_settings["seconds_between_cache_refreshing"] ?? 14400;
	$pages_not_to_cache = $site_settings["pages_not_to_cache"] ?? 'account, addresses, account/addresses, add-address, account/addresses/add-address, edit-address, account/addresses/edit-address, affiliate, account/affiliate, cards-on-file, account/cards-on-file, add-card, account/cards-on-file/add-card, edit-card, account/cards-on-file/edit-card, invoice, account/invoice, license-keys, account/license-keys, orders, account/orders, order-details, account/orders/order-details, profile, account/profile, receipt, account/receipt, subscriptions, account/subscriptions, cancel-order, cart, checkout, order-confirmation, reset-password, robots';
	$collect_analytics_data = $site_settings["collect_analytics_data"] ?? 'Yes';
	$google_analytics_tag_id = $site_settings["google_analytics_tag_id"] ?? '';
	$microsoft_advertising_tag_id = $site_settings["microsoft_advertising_tag_id"] ?? '';
	
	//Set timezone
	if(!empty($site_settings["timezone"]))
	{
		$_SESSION['timezone'] = $site_settings["timezone"];
		date_default_timezone_set($site_settings["timezone"]);
		
		$time_zone = new \DateTime('now', new DateTimeZone($site_settings["timezone"]));
		$time_zone_offset = $time_zone->format('P');
	}
	else
	{
		$_SESSION['timezone'] = 'America/New_York';
		date_default_timezone_set('America/New_York');
		
		$time_zone = new \DateTime('now', new DateTimeZone('America/New_York'));
		$time_zone_offset = $time_zone->format('P');
	}
	
	//Set site logo variables
	$logo_media = '';
	$logo_media_url = '';
	$_SESSION['logo_media'] = '';
	if(!empty($site_settings["site_logo_media_id"]))
	{
		$media_array = explode('~||~', $site_settings["site_logo_media_id"]);
		
		$logo_media = mediaId($media_array[0], 'lazyLoadNo', 'fetchPriorityHigh', $media_array[1]);
		$_SESSION['logo_media'] = $logo_media;
		
		$logo_data_array = mediaIdArray($media_array[0]);
		$logo_media_url = $logo_data_array[0]['full_url'];
	}
	
	//Set site Favicon 16px x 16px
	$favicon_16px_16px = '';
	if(!empty($site_settings["favicon_16px_16px"]))
	{
		$media_array = explode('~||~', $site_settings["favicon_16px_16px"]);
		
		$favicon_16px_16px_data_array = mediaIdArray($media_array[0]);
		$favicon_16px_16px = $favicon_16px_16px_data_array[0]['full_url'];
	}
	
	//Set site Favicon 32px x 32px
	$favicon_32px_32x = '';
	if(!empty($site_settings["favicon_32px_32px"]))
	{
		$media_array = explode('~||~', $site_settings["favicon_32px_32px"]);
		
		$favicon_32px_32px_data_array = mediaIdArray($media_array[0]);
		$favicon_32px_32px = $favicon_32px_32px_data_array[0]['full_url'];
	}
	
	//Set site Favicon 180px x 180px
	$favicon_180px_180px = '';
	if(!empty($site_settings["favicon_180px_180px"]))
	{
		$media_array = explode('~||~', $site_settings["favicon_180px_180px"]);
		
		$favicon_180px_180px_data_array = mediaIdArray($media_array[0]);
		$favicon_180px_180px = $favicon_180px_180px_data_array[0]['full_url'];
	}
	
	//Set PDF logo variables
	$_SESSION['pdf_logo_media'] = '';
	$_SESSION['pdf_logo_media_url_path'] = '';
	$_SESSION['pdf_logo_media_width'] = '';
	$_SESSION['pdf_logo_media_height'] = '';
	if(!empty($site_settings["pdf_logo_media_id"]))
	{
		$media_array = explode('~||~', $site_settings["pdf_logo_media_id"]);
		$logo_data_array = mediaIdArray($media_array[0]);
		
		$_SESSION['pdf_logo_media'] = $logo_data_array[0]['full_url'];
		$_SESSION['pdf_logo_media_url_path'] = $logo_data_array[0]['path_url'];
		$_SESSION['pdf_logo_media_width'] = $logo_data_array[0]['width'];
		$_SESSION['pdf_logo_media_height'] = $logo_data_array[0]['height'];
	}
	
	//Set video icon variables
	$media_video_icon = 'No Video Icon Set in: Admin > Settings > Site Settings > General Settings';
	$_SESSION['media_video_icon'] = 'No Video Icon Set in: Admin > Settings > Site Settings > General Settings';
	$_SESSION['media_video_icon_no_picture_tag'] = '';
	$media_video_icon_url = 'no-video-icon-set-in-admin-site-settings.gif';
	$_SESSION['media_video_icon_url'] = 'no-video-icon-set-in-admin-site-settings.gif';
	if(!empty($default_video_icon))
	{
		$media_array = explode('~||~', $default_video_icon);
		
		$sql_media_video_icon_row = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? OR `original_media_id` = ? LIMIT 3', [$media_array[0], $media_array[0]]);
		if(!empty($sql_media_video_icon_row))
		{
			if(!empty($media_array[1]))
			{
				$custom_video_icon_meta_tag = $media_array[1];
			}
			else
			{
				$custom_video_icon_meta_tag = $sql_media_video_icon_row[0]["media_tag"];
			}
			
			$media_video_icon = displayMedia($sql_media_video_icon_row, $domain, 'No', 'Yes', $custom_video_icon_meta_tag);
			$_SESSION['media_video_icon'] = $media_video_icon;
			$_SESSION['media_video_icon_no_picture_tag'] = str_replace(array('<picture>', '</picture>'), '', $media_video_icon ?? '');
			$media_video_icon_url = $sql_media_video_icon_row[0]["media_url"];
			$_SESSION['media_video_icon_url'] = $sql_media_video_icon_row[0]["media_url"];
		}
	}
	
	//Set file icon variables
	$media_file_icon = 'No File Icon Set in: Admin > Settings > Site Settings > General Settings';
	$_SESSION['media_file_icon'] = 'No File Icon Set in: Admin > Settings > Site Settings > General Settings';
	$_SESSION['media_file_icon_no_picture_tag'] = '';
	$media_file_icon_url = 'no-file-icon-set-in-admin-site-settings.gif';
	$_SESSION['media_file_icon_url'] = 'no-file-icon-set-in-admin-site-settings.gif';
	if(!empty($default_file_icon))
	{
		$media_array = explode('~||~', $default_file_icon);
		
		$sql_media_file_icon_row = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? OR `original_media_id` = ? LIMIT 3', [$media_array[0], $media_array[0]]);
		if(!empty($sql_media_file_icon_row))
		{
			if(!empty($media_array[1]))
			{
				$custom_video_icon_meta_tag = $media_array[1];
			}
			else
			{
				$custom_video_icon_meta_tag = $sql_media_file_icon_row[0]["media_tag"];
			}
			
			$media_file_icon = displayMedia($sql_media_file_icon_row, $domain, 'No', 'Yes', $custom_video_icon_meta_tag);
			$_SESSION['media_file_icon'] = $media_file_icon;
			$_SESSION['media_file_icon_no_picture_tag'] = str_replace(array('<picture>', '</picture>'), '', $media_file_icon ?? '');
			$media_file_icon_url = $sql_media_file_icon_row[0]["media_url"];
			$_SESSION['media_file_icon_url'] = $sql_media_file_icon_row[0]["media_url"];
		}
	}
}