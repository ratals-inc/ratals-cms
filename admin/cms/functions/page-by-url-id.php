<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/page-by-url-id.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/page-by-url-id.php');
}
else
{
	if(!function_exists('getPageWithUrlId'))
	{
		function getPageWithUrlId($id, $site_id, $domain, $home_page, $url_structure, $sites_end_urls_with)
		{
			$page_url_data = array();
			
			$url_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ? AND `site_id` = ? AND url_status = ? LIMIT 1', [$id, $site_id, '1']);
				
			if(!empty($url_row))
			{
				$page_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', $url_row['table_name'], 'WHERE `site_id` = ? AND `urls_id` = ? LIMIT 1', [$site_id, $url_row['id']]);
				
				if(!empty($page_row))
				{
					if(empty($url_row['custom_link'])) 
					{ 
						if(!empty($url_row['page_extension']))
						{
						   $url_end_url_with = $url_row['page_extension'];
						}
						else
						{
						   $url_end_url_with = $sites_end_urls_with;
						}
						
						if($url_structure == 'Hierarchy')
						{
						   $url = $domain."/".$url_row['hierarchy_url'].$url_end_url_with; 
						}
						elseif($url_structure == 'Flat')
						{
						   $url = $domain."/".$url_row['flat_url'].$url_end_url_with; 
						}
					}
					else
					{
					   $url = $url_row['custom_link'];
					}
					
					if($home_page == $url_row['id'] && empty($url_row['custom_link']))
					{
					   $url = $domain."/";
					}
					else
					{
					   if(!empty($url_row['custom_link']))
					   {
						   $url = $url_row['custom_link'];
					   }
					}
					
					$page_url_data = array_merge($page_row, array('url_data' => $url_row + array('url' => $url)));
				}
			}
			return $page_url_data;
		}
	}
}