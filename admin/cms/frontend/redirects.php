<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/redirects.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/redirects.php');
}
else
{
	//REDIRECTS - Check if redirects exist and get last redirect record for path being requested.
	$all_redirects = array();
	$last_redirect_url = array();
	$path_url_array = array();
	
	if(!empty($path_url)) 
	{
		function urlRedirects($site_id, $path_url, $path_url_array) 
		{
			$url_redirects = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'redirects', 'WHERE `site_id` = ? AND `status` = ? AND `old_url` = ? LIMIT 1', [$site_id, '1', $path_url]);
			
			if(!empty($url_redirects)) 
			{
				$path_url_array[] = array($url_redirects["new_url"], $url_redirects["redirect_type"]);
				$check_redirect_loop = array_search($url_redirects["old_url"], $path_url_array);
				
				if($check_redirect_loop === false)
				{
					if($url_redirects["old_url"] != $url_redirects["new_url"])	
					{
						$path_url_array = urlRedirects($site_id, $url_redirects["new_url"], $path_url_array);
					}
				}
			}
			
			return $path_url_array;
		}
		
		$all_redirects = urlRedirects($site_id, $path_url, $path_url_array);
	}
	
	if(!empty($all_redirects)) { $last_redirect_url = end($all_redirects); }
	if(!empty($last_redirect_url)) { $path_url = $last_redirect_url[0]; }
}