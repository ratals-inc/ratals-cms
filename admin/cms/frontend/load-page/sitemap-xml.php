<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/sitemap-xml.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/sitemap-xml.php');
}
else
{
	if(isset($template_file_type_row['assigned_type']) && $template_file_type_row['assigned_type'] == 'Pages > Sitemap - XML')
	{
		//Get distinct table names that urls are connecting to.
		$record_tables = $results->getSelectMultipleRecords(__LINE__, __FILE__, 'DISTINCT `table_name`', 'urls', 'WHERE site_id = ? AND `include_in_xml_sitemap` = ?', [$site_id, 'Yes']);
		
		if(!empty($record_tables))
		{
			//Get all records for tables that urls are connected to.
			foreach($record_tables as $record_table)
			{
				$get_records[$record_table['table_name']] = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', $record_table['table_name'], 'WHERE site_id = ?', [$site_id], 'urls_id');
			}
		}
		
		//Get all urls.
		$all_urls = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE site_id = ? AND `url_status` = ? AND `include_in_xml_sitemap` = ? ORDER BY `id` ASC', [$site_id, 1, 'Yes']);
		
		$pages_data['xml_data'] = array();
		
		if(!empty($all_urls))
		{
			foreach($all_urls as $all_url)
			{
				if(!empty($all_url) && isset($get_records[$all_url['table_name']][$all_url['id']]) )
				{
					$updated_date = utcToUserTimeZone($get_records[$all_url['table_name']][$all_url['id']]['updated_date'], 'Y-m-d');
					
					if($all_url["id"] != $home_page)
					{
						$pages_data['xml_data'][] = array('url' => getUrl($all_url["custom_link"], $all_url["url_extension"], $sites_end_urls_with, $url_structure, $domain, $all_url["hierarchy_url"], $all_url["flat_url"], '', $pages_data['id'], $home_page), 'updated_date' => $updated_date);
					}
					else
					{
						$pages_data['xml_data'][] = array('url' => $domain, 'updated_date' => $updated_date);
					}
				}
			}
		}
	}
}