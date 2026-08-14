<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/currency-format.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/currency-format.php');
}
else
{
	if(!function_exists('currencyFormat'))
	{
		function currencyFormat($price, $qty = 1)
		{
			//Set sessions to variables when running during normal checkout and user is on the site with sessions enabled.
			if(session_status() === PHP_SESSION_ACTIVE)
			{
				$zeros_after_separator = $_SESSION['currency_zeros_after_separator'] ?? 2;
				$fractional_separator = $_SESSION['currency_fractional_separator'] ?? '.';
				$thousand_separator = $_SESSION['currency_thousand_separator'] ?? ',';
			}
			//Sessions are not enabled when recurring scripts run so we need to have this.
			else
			{
				if(isset($results) && !empty($results) && isset($site_id) && !empty($site_id))
				{
					$currency = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'currency', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
				}
				
				$zeros_after_separator = $currency['zeros_after_separator'] ?? 2;
				$fractional_separator = $currency['fractional_separator'] ?? '.';
				$thousand_separator = $currency['thousand_separator'] ?? ',';
			}
			
			$currency_format = number_format((float)$price * $qty, $zeros_after_separator, $fractional_separator, $thousand_separator);
			
			return $currency_format;
		}
	}
	
	if(!function_exists('currencyFormatWithSymbol'))
	{
		function currencyFormatWithSymbol($price, $qty = 1)
		{
			//Set sessions to variables when running during normal checkout and user is on the site with sessions enabled.
			if(session_status() === PHP_SESSION_ACTIVE)
			{
				$zeros_after_separator = $_SESSION['currency_zeros_after_separator'] ?? 2;
				$fractional_separator = $_SESSION['currency_fractional_separator'] ?? '.';
				$thousand_separator = $_SESSION['currency_thousand_separator'] ?? ',';
				$front_symbol = $_SESSION['currency_front_symbol'] ?? '$';
				$back_symbol = $_SESSION['currency_back_symbol'] ?? '';
			}
			//Sessions are not enabled when recurring scripts run so we need to have this.
			else
			{
				if(isset($results) && !empty($results) && isset($site_id) && !empty($site_id))
				{
					$currency = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'currency', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
				}
				
				$zeros_after_separator = $currency['zeros_after_separator'] ?? 2;
				$fractional_separator = $currency['fractional_separator'] ?? '.';
				$thousand_separator = $currency['thousand_separator'] ?? ',';
				$front_symbol = $currency['front_symbol'] ?? '$';
				$back_symbol = $currency['back_symbol'] ?? '';
			}
			
			$currency_format = $front_symbol.number_format((float)$price * $qty, $zeros_after_separator, $fractional_separator, $thousand_separator).$back_symbol;
			
			return $currency_format;
		}
	}
	
	if(!function_exists('formatCurrencyParentheses'))
	{
		function formatCurrencyParentheses($amount, $decimals = 6)
		{
			//Set sessions to variables when running during normal checkout and user is on the site with sessions enabled.
			if(session_status() === PHP_SESSION_ACTIVE)
			{
				$fractional_separator = $_SESSION['currency_fractional_separator'] ?? '.';
				$thousand_separator = $_SESSION['currency_thousand_separator'] ?? ',';
				$front_symbol = $_SESSION['currency_front_symbol'] ?? '$';
				$back_symbol = $_SESSION['currency_back_symbol'] ?? '';
			}
			//Sessions are not enabled when recurring scripts run so we need to have this.
			else
			{
				if(isset($results) && !empty($results) && isset($site_id) && !empty($site_id))
				{
					$currency = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'currency', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
				}
				
				$fractional_separator = $currency['fractional_separator'] ?? '.';
				$thousand_separator = $currency['thousand_separator'] ?? ',';
				$front_symbol = $currency['front_symbol'] ?? '$';
				$back_symbol = $currency['back_symbol'] ?? '';
			}
			
			$abs_amount = number_format(abs($amount), $decimals, $fractional_separator, $thousand_separator);
			$formatted = ($front_symbol).$abs_amount.($back_symbol);
			
			if($amount < 0)
			{
				return '('.$formatted.')';
			}
			
			return $formatted;
		}
	}
}