<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/site.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/site.php');
}
else
{	
	//SITES - Check if domain exist in Data Base that is being requested.
	$sites = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'sites', 'WHERE `domain` = ? LIMIT 1', [$homepage_url]);
	
	$_SESSION['site_id'] = '';
	
	if(!empty($sites)) 
	{
		$site_id = $sites["id"];
		$_SESSION['site_id'] = $sites["id"];
		$_SESSION['site_language'] = $sites['site_language'];
		$home_page = $sites["homepage"];
		$tld_domain = $sites["domain"];
		$https_in_url = $sites["https_in_url"];
		$www_in_url = $sites["www_in_url"];
		$url_structure = $sites["url_structure"];
		$redirect_to_opposite_url = $sites["redirect_to_opposite_url"];
		$auto_create_canonical_url = $sites["auto_generate_canonical_url"];
		$sites_end_urls_with = $sites["global_url_extension"];
		$_SESSION['admin_directory'] = $sites["admin_directory"];
		
		//$https_in_url - from SITES table
		if($https_in_url == 'Yes') { $http = "https://"; } else { $http = "http://"; }
		
		//$www_in_url - from SITES table
		if($www_in_url == 'Yes') { $www = "www."; } else { $www = ""; }
		
		//Build live domain URL.
		$domain = $http.$www.$tld_domain;
		$_SESSION['domain'] = $http.$www.$tld_domain;
		$domain_only = $tld_domain;
		$_SESSION['domain_only'] = $tld_domain;
	}
	else//if($path_url != 'create-account')
	{
		//Site does not exist in DB.
		header("HTTP/1.1 404"); 
		include_once($_SERVER['DOCUMENT_ROOT'].'/sites/site-not-found.php');
		die();
	}
}