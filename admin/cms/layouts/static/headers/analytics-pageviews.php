<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/analytics-pageviews.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/analytics-pageviews.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'analytics_pageviews')
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
		if(isset($_GET['from_date']) && !empty($_GET['from_date']) && isset($_GET['to_date']) && !empty($_GET['to_date']))
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
		
		$analytics_pageviews = $_SESSION['results']->getSelectMultipleRecords(
			__LINE__, __FILE__,
			'COUNT(*) AS pageviews, ANY_VALUE(`pageview_url`) AS pageview_url',
			'analytics',
			'WHERE `site_id` = ? AND `created_date` BETWEEN ? AND ? 
			  GROUP BY `pageview_hash` 
			  ORDER BY `pageviews` DESC 
			  LIMIT 1000',
			[$_SESSION["site_set_for_editing"], $from_date, $to_date]
		);
	}
}