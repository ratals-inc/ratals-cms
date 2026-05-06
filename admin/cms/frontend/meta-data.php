<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/meta-data.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/meta-data.php');
}
else
{
	//Build Correct Full Meta Title
	$meta_title_separator = '';
	if(!empty($separate_site_name_in_title_tag_with) && $add_site_name_to_title_tag == "Yes") 
	{
		$meta_title_separator = " ".$separate_site_name_in_title_tag_with;
	}
	
	$meta_title_site_name = '';
	if($add_site_name_to_title_tag == "Yes")
	{
		$meta_title_site_name = " ".$site_name;
	}
	
	$full_meta_title = $meta_title.$meta_title_separator.$meta_title_site_name;
	
	//Get Correct Canonical URL
	if($auto_create_canonical_url == 'Yes' && empty($canonical_url))
	{
		if($home_page == $home_page_record_id)
		{
			$canonical_url = $final_url_home_page;
		}
		else
		{
			$canonical_url = $final_url;
		}
	}
	
	//Get Sitewide Correct Meta Robots Tag
	if(empty($meta_robots) && !empty($site_wide_meta_robots)) 
	{
		$meta_robots = $site_wide_meta_robots;
	}
}