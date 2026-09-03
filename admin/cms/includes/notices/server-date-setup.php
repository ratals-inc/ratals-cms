<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Get current MySQL UTC date and time.
$database_current_date = $results->getRawQuery(__LINE__, __FILE__, 'SELECT UTC_TIMESTAMP() AS current_utc_date', []);
$database_current_utc_date = $database_current_date[0]['current_utc_date'] ?? '';

//Check if MySQL supports named timezone conversions.
$database_timezone_check = $results->getRawQuery(__LINE__, __FILE__, "SELECT CONVERT_TZ(UTC_TIMESTAMP(), 'UTC', 'America/New_York') AS converted_date", []);
$database_timezone_supported = !empty($database_timezone_check[0]['converted_date']);

//Get current PHP UTC date and time.
$php_current_utc_date = gmdate('Y-m-d H:i:s');

//Set server date and time warning.
$server_date_time_warning = false;

//Check PHP server date.
if((int)gmdate('Y') < 2025)
{
	$server_date_time_warning = true;
}

//Check MySQL server date.
if(!empty($database_current_utc_date))
{
	$database_timestamp = strtotime($database_current_utc_date.' UTC');
	
	if($database_timestamp === false || (int)gmdate('Y', $database_timestamp) < 2025)
	{
		$server_date_time_warning = true;
	}
	else
	{
		//Check if PHP and MySQL server times differ by more than 60 seconds.
		$server_time_difference = abs(time() - $database_timestamp);
		
		if($server_time_difference > 60)
		{
			$server_date_time_warning = true;
		}
	}
}
else
{
	$server_date_time_warning = true;
}

if($database_timezone_supported === false)
{
	$display_message .= '<div class="setup-message"><strong>Database Timezone Support Is Not Available:</strong> Your database server is unable to convert named timezones. Ratals uses database timezone conversions to display dates and times correctly throughout the system. Please make sure the MySQL or MariaDB timezone tables are installed and populated.</div>';
}

if($server_date_time_warning === true)
{
	if(!empty($database_current_utc_date))
	{
		$database_current_date = $database_current_utc_date.' UTC';
	}
	else
	{
		$database_current_date = 'an unknown date and time';
	}
	
	$display_message .= '<div class="setup-message"><strong>Server Date and Time May Be Incorrect:</strong> Your PHP server is currently reporting '.$php_current_utc_date.' UTC and your database server is currently reporting '.$database_current_date.'. Ratals uses these server dates and times for analytics, orders, email scheduling, security logs, subscriptions, and other timestamped activity. Please verify that both server dates and times are correct and synchronized to ensure timestamps and scheduled features work correctly.</div>';
}