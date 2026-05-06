<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/date-time-format.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/date-time-format.php');
}
else
{
	if(!function_exists('utcToUserTimeZone'))
	{
		function utcToUserTimeZone($utc_datetime, $format = 'M. d, Y - g:i A')
		{
			if(!empty($utc_datetime))
			{
				$date_time = new DateTime($utc_datetime, new DateTimeZone('UTC'));
				return $date_time->setTimezone(new DateTimeZone($_SESSION['timezone']))->format($format);
			}
			
			return '-';
		}
	}
	
	if(!function_exists('dateToUtc'))
	{
		function dateToUtc($searched_date, $time = '00:00:00', $format = 'Y-m-d H:i:s')
		{
			if(!empty($searched_date))
			{
				$date_time = new DateTime($searched_date.' '.$time, new DateTimeZone($_SESSION['timezone']));
				return $date_time->setTimezone(new DateTimeZone('UTC'))->format($format);
			}
			
			return NULL;
		}
	}
	
	if(!function_exists('submittedDate'))
	{
		function submittedDate($submitted_date, $format = 'F d, Y')
		{
			if(!empty($submitted_date))
			{
				return (new DateTime($submitted_date))->format($format);
			}
			
			return NULL;
		}
	}
	
	if(!function_exists('usersTodaysDate'))
	{
		function usersTodaysDate($format = 'Y-m-d')
		{
			$todays_date = new DateTime('now', new DateTimeZone($_SESSION['timezone']));
			$todays_date->setTime(0, 0, 0);
			
			return $todays_date->format($format);
		}
	}
	
	if(!function_exists('firstDayOfMonth'))
	{
		function firstDayOfMonth($format = 'Y-m-d')
		{
			$today_user_timezone = new DateTime('now', new DateTimeZone($_SESSION['timezone']));
			$today_user_timezone->modify('first day of this month')->setTime(0, 0, 0);
			return $today_user_timezone->format($format);
		}
	}
}