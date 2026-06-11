<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//UPDATE SITE_SETTING DEFAULTS ON ACCOUNT UPGRADE TO COMMERCE.
try
{
	$all_sites_to_update = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'sites', '', [], 'id');
	$all_site_settings_to_update = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'site_settings', '', [], 'site_id');
	
	if(!empty($all_sites_to_update))
	{
		foreach($all_sites_to_update as $site_id_to_update)
		{
			if(isset($site_id_to_update['id']) && !empty($site_id_to_update['id']) && isset($all_site_settings_to_update[$site_id_to_update['id']]['id']) && !empty($all_site_settings_to_update[$site_id_to_update['id']]['id']) && isset($all_site_settings_to_update[$site_id_to_update['id']]['display_stock_status']) && empty($all_site_settings_to_update[$site_id_to_update['id']]['display_stock_status']) && isset($all_site_settings_to_update[$site_id_to_update['id']]['inventory_variant_builder_max']) && empty($all_site_settings_to_update[$site_id_to_update['id']]['inventory_variant_builder_max']) && isset($all_site_settings_to_update[$site_id_to_update['id']]['allow_checkout_as_guest']) && empty($all_site_settings_to_update[$site_id_to_update['id']]['allow_checkout_as_guest']) && isset($all_site_settings_to_update[$site_id_to_update['id']]['customers_save_cards']) && empty($all_site_settings_to_update[$site_id_to_update['id']]['customers_save_cards']) && isset($all_site_settings_to_update[$site_id_to_update['id']]['free_shipping']) && empty($all_site_settings_to_update[$site_id_to_update['id']]['free_shipping']))
			{
				$update_columns = '`store_pagination` = ?, `display_stock_status` = ?, `inventory_left_instock` = ?, `call_for_availability_phone_number` = ?, `inventory_variant_builder_max` = ?, `cart_line_item_max_qty` = ?, `display_discount_code_on_cart` = ?, `add_to_cart_redirect` = ?, `allow_checkout_as_guest` = ?, `customers_save_cards` = ?, `customer_account_url` = ?, `order_confirmation_url` = ?, `free_shipping` = ?, `free_shipping_cart_minimum` = ?, `state_or_postal_code` = ?, `unit_of_measure` = ?, `unit_of_weight` = ?';
				$update_where_clause = 'WHERE `id` = ?';
				$fields_to_update = array(30, 'Yes', 5, 'Call for availability.', '3000', 9999, 'No', 'Yes', 'Yes', 'Yes', $_SESSION['install_ids'][$site_id_to_update['id']]['customer_account_page_url_id'] ?? 0, $_SESSION['install_ids'][$site_id_to_update['id']]['order_confirmation_page_url_id'] ?? 0, 'Yes', 199.0000, 'State', 'in', 'lb', $all_site_settings_to_update[$site_id_to_update['id']]['id']);
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'site_settings', $update_columns, $update_where_clause, $fields_to_update);
			}
		}
	}
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Completed site settings update check.');
	}
}
catch(\Throwable $e)
{
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Failed site settings update: '.$e->getMessage());
	}
}