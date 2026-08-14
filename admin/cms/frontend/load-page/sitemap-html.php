<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/sitemap-html.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/sitemap-html.php');
}
else
{
	if(isset($template_file_type_row['assigned_type']) && $template_file_type_row['assigned_type'] == 'Pages > Sitemap - HTML')
	{
		//Get all tables that URLs are connecting to.
		$tables_with_urls_set = $results->getSelectMultipleRecords(__LINE__, __FILE__, 'DISTINCT `table_name`', 'urls', 'WHERE site_id = ? AND url_status = ? AND `include_in_html_sitemap` = ?', [$site_id, 1, 'Yes']);
		
		foreach($tables_with_urls_set as $table_name)
		{
			$table_name = $table_name['table_name'];
			
			//Get all urls and records for each table that has a url.
			$all_urls_record_data = $results->getSelectLeftJoinMultipleRecords(__LINE__, __FILE__, '*', $table_name, '`urls` ON `urls`.record_id = `'.$table_name.'`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`table_name` = ? AND `urls`.`id` != ? AND `urls`.`url_status` = ? AND `urls`.`include_in_html_sitemap` = ? ORDER BY `urls`.`id` DESC LIMIT 15', [$site_id, ''.$table_name.'' , $id, 1, 'Yes']);
			
			if(!empty($all_urls_record_data))
			{
				$pages_data['sitemap'][$table_name] = array();
				
				foreach($all_urls_record_data as $urls_record_data)
				{
					if($urls_record_data["id"] != $home_page)
					{						
						$pages_data['sitemap'][$table_name][] = array('url' => getUrl($urls_record_data['custom_link'], $urls_record_data['url_extension'], $sites_end_urls_with, $url_structure, $domain, $urls_record_data['hierarchy_url'], $urls_record_data['flat_url'], '', $urls_record_data['id'], $home_page), 'meta_title' => $urls_record_data["meta_title"]);
					}
					else
					{						
						$pages_data['sitemap'][$table_name][] = array('url' => $domain, 'meta_title' => $urls_record_data["meta_title"]);
					}
				}
			}
		}
	}
}