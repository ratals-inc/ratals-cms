<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/sitemap-html-section.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/sitemap-html-section.php');
}
else
{
	if(isset($template_file_type_row['assigned_type']) && $template_file_type_row['assigned_type'] == 'Pages > Sitemap - HTML Section')
	{
		if(!isset($_GET['section']))
		{
			//Redirect to 404 page if section is not set in url.
			header("Location: ".$domain.INSTALLATION_URL_PATH."/404/");
			exit();
		}
		
		$validate_url_section_name = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `table_name` = ? AND url_status = ? AND `include_in_html_sitemap` = ? LIMIT 1', [$_GET['section'], 1, 'Yes']);
		
		if(empty($validate_url_section_name['table_name']))
		{
			//If section set in url is not found in the urls table, redirect to 404 page.
			header("Location: ".$domain.INSTALLATION_URL_PATH."/404/");
			exit();
		}
		//print_r($validate_url_section_name);
		
		$pages_data['total_number_of_results'] = 0;
		$page_number = 1;
		$number_of_paginated_pages = 0;
		$pagination_results_per_page = 50;
		
		if(!empty($validate_url_section_name['table_name']))
		{
			$table_name = $validate_url_section_name['table_name'];
			$table_name_text = ucwords(str_replace('_', ' ', $validate_url_section_name['table_name']));
			
			//Get sitemap total count
			$sitemap_count = $results->getSelectLeftJoinMultipleRecords(__LINE__, __FILE__, '`urls`.`id`', $table_name, '`urls` ON `urls`.record_id = `'.$table_name.'`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`table_name` = ? AND `urls`.`id` != ? AND `urls`.`url_status` = ? AND `urls`.`include_in_html_sitemap` = ? ORDER BY `urls`.`id`', [$site_id, $table_name, $id, 1, 'Yes']);
			
			$pages_data['total_number_of_results'] = count($sitemap_count);				
			
			if(!empty($pages_data['number_of_sitemap_results']))
			{
				$pagination_results_per_page = $pages_data['number_of_sitemap_results'];
			}
			
			if(!empty($_GET["page"]) && is_numeric($_GET["page"]))
			{
				$page_number = $_GET["page"];
			}
			
			if(!empty($pages_data['total_number_of_results']))
			{
				$number_of_paginated_pages = ceil($pages_data['total_number_of_results'] / $pagination_results_per_page);
			}
			
			if($page_number > 1 && $page_number > $number_of_paginated_pages)
			{
				header("Location: ".$pages_data["pages_url"].'?section='.$_GET['section']); exit();
			}
			
			$offset = ($page_number * $pagination_results_per_page) - $pagination_results_per_page;
			
			//Get all records with offset for page number
			$all_records = $results->getSelectLeftJoinMultipleRecords(__LINE__, __FILE__, '*', $table_name, '`urls` ON `urls`.record_id = `'.$table_name.'`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`table_name` = ? AND `urls`.`id` != ? AND `urls`.`url_status` = ? AND `urls`.`include_in_html_sitemap` = ? ORDER BY `urls`.`id` DESC LIMIT '.$offset.', '.$pagination_results_per_page, [$site_id, $table_name, $id, 1, 'Yes']);
			
			$pages_data['html_data_'.$table_name] = array();
			
			if(!empty($all_records))
			{
				foreach($all_records as $all_record)
				{
					if($all_record["id"] != $home_page)
					{						
						$pages_data['html_data_'.$table_name][] = array('url' => getUrl($all_record['custom_link'], $all_record['url_extension'], $sites_end_urls_with, $url_structure, $domain, $all_record['hierarchy_url'], $all_record['flat_url'], '', $all_record['id'], $home_page), 'meta_title' => $all_record["meta_title"]);
					}
					else
					{						
						$pages_data['html_data_'.$table_name][] = array('url' => $domain, 'meta_title' => $all_record["meta_title"]);
					}
				}
			}
		}
	}
}