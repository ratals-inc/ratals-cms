<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/hreflang.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/hreflang.php');
}
else
{
	$hreflang = '';
	
	if(!empty($hreflang_url_id))
	{
		$url_results = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE (`id` = ? OR `hreflang_url_id` = ?)', [$hreflang_url_id, $hreflang_url_id]);
	}
	else
	{
		$url_results = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE (`id` = ? OR `hreflang_url_id` = ?)', [$id, $id]);
	}
	
	if(count($url_results) > 1)
	{
		$site_results = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'sites', '', [], 'id');
		
		foreach($url_results as $url_result)
		{
			//$https_in_url - from SITES table
			if($site_results[$url_result['site_id']]['https_in_url'] == 'Yes') { $hl_http = "https://"; } else { $hl_http = "http://"; }
			//$www_in_url - from SITES table
			if($site_results[$url_result['site_id']]['www_in_url'] == 'Yes') { $hl_www = "www."; } else { $hl_www = ""; }
			
			$hl_domain = $hl_http.$hl_www.$site_results[$url_result['site_id']]['domain'];
			
			if($url_result['id'] != $site_results[$url_result['site_id']]['homepage'])
			{
				$hl_url_extention = '';
				
				if(!empty($url_result['url_extension']))
				{
					$hl_url_extention = $url_result['url_extension'];
				}
				elseif(!empty($site_results[$url_result['site_id']]['global_url_extension']))
				{
					$hl_url_extention = $site_results[$url_result['site_id']]['global_url_extension'];
				}
				
				if($site_results[$url_result['site_id']]['url_structure'] == 'Hierarchy')
				{
					$hreflang .= '<link rel="alternate" href="'.$hl_domain.'/'.$url_result['hierarchy_url'].$hl_url_extention.'" hreflang="'.$site_results[$url_result['site_id']]['site_language'].'" />';
				}
				elseif($site_results[$url_result['site_id']]['url_structure'] == 'Flat')
				{
					$hreflang .= '<link rel="alternate" href="'.$hl_domain.'/'.$url_result['flat_url'].$hl_url_extention.'" hreflang="'.$site_results[$url_result['site_id']]['site_language'].'" />';
				}
			}
			else
			{
				$hreflang .= '<link rel="alternate" href="'.$hl_domain.'" hreflang="'.$site_results[$url_result['site_id']]['site_language'].'" />';
			}
		}
	}
}