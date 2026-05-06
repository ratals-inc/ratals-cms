<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD MISSING WEBSITE PAGES URLS
try
{
	//Save $site_id that started update as we have to loop through site_ids for each url.
	$hold_site_id = $site_id;
	
	$all_sites_to_update = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'sites', '', [], 'id');
	
	$admin_webiste_pages_urls_packages = array();
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/data/urls.php'))
	{
		$admin_webiste_pages_urls_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/data/urls.php'))
	{
		$admin_webiste_pages_urls_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/urls.php'))
	{
		$admin_webiste_pages_urls_packages[] = 'ai';
	}
	
	if(!empty($all_sites_to_update))
	{
		foreach($all_sites_to_update as $site_data_array)
		{
			if(!empty($admin_webiste_pages_urls_packages))
			{
				foreach($admin_webiste_pages_urls_packages as $admin_webiste_pages_urls_package)
				{
					$site_id = $site_data_array['id'];
					include($temp_extract_dir.'/admin/'.$admin_webiste_pages_urls_package.'/installer/data/urls.php');
				}
			}
		}
	}
	
	//Set site_id back when urls are done adding.
	$site_id = $hold_site_id;
	
	writeToInstallLog('Completed pages URLs update check.');
}
catch(\Throwable $e)
{
	writeToInstallLog('Failed pages URLs update: '.$e->getMessage());
}