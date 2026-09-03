<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$column_names = '`id`, `site_id`, `status`, `notice_subject`, `notice_message`, `notice_url`, `notice_update_software`, `notice_upgrade_from`, `notice_upgrade_to`, `notice_software_version`, `required_php_version`, `required_mysql_version`, `system_code`, `custom_fields`, `created_date`';
$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()';

$parameters = array();

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
	$parameters[] = [NULL, 0, 1, 'Database Timezones Are Not Installed', '<div class="">Your database server is unable to convert named timezones. Ratals uses database timezone conversions to display dates and times correctly throughout the system. Please make sure the MySQL or MariaDB timezone tables are installed and populated.</div>', '', 'No', '', '', '', '', '', 'database_timezones_are_not_installed', '{}'];
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
	
	$parameters[] = [NULL, 0, 1, 'Server Date and Time May Be Incorrect', '<div class="">Your PHP server is currently reporting '.$php_current_utc_date.' UTC and your database server is currently reporting '.$database_current_date.'. Ratals uses these server dates and times for analytics, orders, email scheduling, security logs, subscriptions, and other timestamped activity. Please verify that both server clocks are correct and synchronized to ensure timestamps and scheduled features work correctly.</div>', '', 'No', '', '', '', '', '', 'server_dates_and_times_incorrect', '{}'];
}

//Check if SMTP email was setup to set message.
$left_to_fill_in = array();

if(empty($smtp_email_address))
{
	$left_to_fill_in[] = 'SMTP Email Address';
}

if(empty($smtp_email_name))
{
	$left_to_fill_in[] = 'SMTP Email Name';
}

if(empty($smtp_email_hostname))
{
	$left_to_fill_in[] = 'SMTP Email Hostname';
}

if(empty($smtp_email_port))
{
	$left_to_fill_in[] = 'SMTP Email Port';
}

if(!empty($left_to_fill_in))
{
	$parameters[] = [NULL, 0, 1, 'Email Delivery Setup Incomplete', '<div class="">Outgoing SMTP email delivery has not been fully configured. Ratals will attempt to send email using your server\'s PHP mail function, but delivery may be less reliable and messages may be sent to spam or junk folders. To complete SMTP setup, enter the required email delivery settings under <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/site-settings/contact-information/">Site Contact Information</a>. If your email server requires authentication, also enter the SMTP Email Username and SMTP Email Password.</div>', '', 'No', '', '', '', '', '', 'email_delivery_setup_incomplete', '{}'];
}

$parameters[] = [NULL, 0, 1, 'Welcome to Ratals!', '<div class="ratals-getting-started"><div class="ratals-getting-started-content">Your installation is complete. Now it\'s time to set up your website and start building. If this is your first time using Ratals, we recommend watching the Getting Started video. It walks you through the initial setup, introduces the Ratals admin area, and shows you where to begin.<p>For additional help, visit the <a href="https://www.ratals.com/tutorials/" target="_blank">Ratals Help &amp; Tutorial Library</a> for more videos and step-by-step guides.</p><p>Once you understand the basics, you can begin building your site and <a href="https://www.ratals.com/pricing/" target="_blank">explore additional Ratals features</a> as needed.</p></div><div class="ratals-getting-started-video"><iframe src="https://www.youtube.com/embed/rzSw5yeyxHw?si=-Y2eYNfqePDQzu0C" title="Ratals CMS Getting Started" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen=""></iframe></div></div>', '', 'No', '', '', '', '', '', 'welcome_to_ratals', '{}'];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'notices', $column_names, $placeholders, $parameters);