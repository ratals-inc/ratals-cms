<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/headers/analytics-unique-visitors.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/headers/analytics-unique-visitors.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'analytics_unique_visitors')
	{
		$todays_date = usersTodaysDate();
		
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
		
		if(!isset($_GET['counts_by_url']))
		{
			$sql_analytics_unique_visitors = $_SESSION['results']->getRawQuery(
				__LINE__, __FILE__, 
				'SELECT 
					`data`.*, 
					CONVERT_TZ(`data`.`created_date`, "UTC", ?) AS date_for_timezone
				FROM (
					SELECT *,
						COUNT(*) OVER (PARTITION BY `analytics_unique_id`) AS total_pageviews
					FROM `analytics`
					WHERE `site_id` = ? AND `created_date` BETWEEN ? AND ?
				) AS data
				ORDER BY `data`.`id` DESC
				LIMIT 5000',
				[$_SESSION['timezone'], $_SESSION["site_set_for_editing"], $from_date, $to_date]
			);
			
			$analytics_unique_visitors = array();
			if(!empty($sql_analytics_unique_visitors))
			{
				foreach($sql_analytics_unique_visitors as $select_record)
				{
					$analytics_unique_visitors[$select_record['analytics_unique_id']][] = $select_record;
				}
			}
		}
		else
		{
			$analytics_unique_visitor_counts = $_SESSION['results']->getRawQuery(
				__LINE__, __FILE__,
				'SELECT 
					data.pageview_hash,
					ANY_VALUE(data.pageview_url) AS pageview_url,
					data.referer_source,
					COUNT(DISTINCT data.analytics_unique_id) AS unique_pageviews,
					SUM(CASE WHEN data.total_pageviews = 1 THEN 1 ELSE 0 END) AS bounces,
					(SUM(CASE WHEN data.total_pageviews = 1 THEN 1 ELSE 0 END) * 100 / COUNT(DISTINCT data.analytics_unique_id)) AS bounce_rate,
					CONVERT_TZ(MIN(data.created_date), "UTC", ?) AS date_for_timezone
				FROM (
					SELECT 
						analytics_unique_id,
						pageview_hash,
						pageview_url,
						referer_source,
						created_date,
						COUNT(*) OVER (PARTITION BY analytics_unique_id) AS total_pageviews
					FROM analytics
					WHERE site_id = ? AND created_date BETWEEN ? AND ?
				) AS data
				GROUP BY data.pageview_hash, data.referer_source
				ORDER BY bounces DESC, bounce_rate DESC
				LIMIT 5000',
				[
					$_SESSION['timezone'],
					$_SESSION["site_set_for_editing"],
					$from_date,
					$to_date
				]
			);
		}
	}
}