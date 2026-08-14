<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/breadcrumbs.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/breadcrumbs.php');
}
else
{
	//Build breadcurmbs
	$breadcrumbs = array();
	
	if($home_page == $pages_data['id'])
	{
		$sql_breadcrumbs_array = array($home_page);
	}
	elseif(empty($pages_data["path_level"]) || $pages_data["path_level"] == 0)
	{
		$sql_breadcrumbs_array = array($home_page, $pages_data["id"]);
	}
	else
	{
		$sql_breadcrumbs_trim = trim($pages_data["path_level"], "/");
		$sql_breadcrumbs_trim = $home_page.'/'.$sql_breadcrumbs_trim.'/'.$pages_data["id"];
		$sql_breadcrumbs_array = explode('/', $sql_breadcrumbs_trim);
	}
	
	if(!empty($sql_breadcrumbs_array))
	{
		foreach($sql_breadcrumbs_array as $sql_breadcrumbs_ids) 
		{
			$breadcrumbs_data = getPageWithUrlId($sql_breadcrumbs_ids, $site_id, $domain, $home_page, $url_structure, $sites_end_urls_with);
			
			if(isset($breadcrumbs_data['url_data']) && !empty($breadcrumbs_data['url_data']))
			{
				//if sitemap, append section to breadcrumb url if set.
				if(strpos($breadcrumbs_data['url_data']['url'], 'site-map/site-map-section') !== false && isset($_GET['section']) && !empty($_GET['section']) && (strpos($url, '&section=') !== false || strpos($url, '?section=') !== false))
				{
					$table_name_text = ucwords(str_replace('_', ' ', $_GET['section']));
					
					$breadcrumbs_data['url_data']['meta_title'] = $table_name_text.' Sitemap ';
					
					$breadcrumbs_data['url_data']['url'] = $breadcrumbs_data['url_data']['url'].'?section='.$_GET['section'];
				}
				
				$breadcrumbs[] = $breadcrumbs_data;
			}
		}
	}
	//echo '<pre>'; print_r($breadcrumbs); echo '</pre>';
}