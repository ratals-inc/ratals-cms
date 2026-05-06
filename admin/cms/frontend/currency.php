<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/currency.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/currency.php');
}
else
{
	//CURRENCY - Select everything from currency table.
	$site_currency = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'currency', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
			
	$_SESSION['currency_type'] = $site_currency["currency_type"] ?? 'USD';
	$_SESSION['currency_front_symbol'] = $site_currency["front_symbol"] ?? '$';
	$_SESSION['currency_back_symbol'] = $site_currency["back_symbol"] ?? '';
	$_SESSION['currency_thousand_separator'] = $site_currency["thousand_separator"] ?? ',';
	$_SESSION['currency_fractional_separator'] = $site_currency["fractional_separator"] ?? '.';
	$_SESSION['currency_zeros_after_separator'] = $site_currency["zeros_after_separator"] ?? 2;
	$_SESSION['exchange_rate_difference'] = $site_currency["exchange_rate_difference"] ?? 1.00;
}