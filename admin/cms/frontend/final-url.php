<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/final-url.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/final-url.php');
}
else
{
	if(!empty($end_url_with)) 
	{
		//End URL Extension at page level
		$end_urls_with = $end_url_with;
	}
	else
	{
		//End URL Extension at gobal level
		$end_urls_with = $sites_end_urls_with;
	}
	
	//Build Final URLs
	if(!empty($domain))
	{
		$final_url_home_page = $domain.'/';
		$final_url_home_page_with_question_mark = $final_url_home_page.$questionmark_in_url;
		
		if(empty($path_url))
		{
			//$final_url is for a url with a path in it like store/something. If the path is empty, this means the sub page url was not found in /load-sites/pages-data.php. In this case, load the requested sub page url as a 404.
			
			//redirect to the correct main domain. With or without https, http, www, non, www so 404 page load on correct tld.
			$new_404_url = $url;
			
			if($https_in_url == 'Yes' && strpos($new_404_url, 'http://') !== false)
			{
				$new_404_url = str_replace('http://', 'https://', $new_404_url);
			}
			
			if($https_in_url == 'No' && strpos($new_404_url, 'https://') !== false)
			{
				$new_404_url = str_replace('https://', 'http://', $new_404_url);
			}
			
			if($https_in_url == 'Yes' && $www_in_url == 'Yes' && strpos($new_404_url, 'https://www.') === false)
			{
				$new_404_url = str_replace('https://', 'https://www.', $new_404_url);
			}
			
			if($https_in_url == 'Yes' && $www_in_url == 'No' && strpos($new_404_url, 'https://www.') !== false)
			{
				$new_404_url = str_replace('https://www.', 'https://', $new_404_url);
			}
			
			if($https_in_url == 'No' && $www_in_url == 'Yes' && strpos($new_404_url, 'http://www.') === false)
			{
				$new_404_url = str_replace('http://', 'http://www.', $new_404_url);
			}
			
			if($https_in_url == 'No' && $www_in_url == 'No' && strpos($new_404_url, 'http://www.') !== false)
			{
				$new_404_url = str_replace('http://www.', 'http://', $new_404_url);
			}
			
			$final_url = $new_404_url;
			$final_url_with_question_mark = $new_404_url;
		}
		else
		{
			$final_url = $domain.'/'.$path_url.$end_urls_with;
			$final_url_with_question_mark = $final_url.$questionmark_in_url;
		}
	}
	else
	{
		header("HTTP/2 404"); include_once('site-not-found.php');
		exit();
	}
}