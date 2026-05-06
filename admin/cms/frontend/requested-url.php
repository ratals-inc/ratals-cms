<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/requested-url.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/requested-url.php');
}
else
{
	//Get Requested URL.
	$url = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
	
	//Get Homepage URL.
	if(strpos($url, 'https://www.') !== false)
	{
		$homepage_url = str_replace("https://www.","",$url);
	}
	elseif(strpos($url, 'http://www.') !== false)
	{
		$homepage_url = str_replace("http://www.","",$url);
	}
	elseif(strpos($url, 'https://') !== false)
	{
		$homepage_url = str_replace("https://","",$url);
	}
	elseif(strpos($url, 'http://') !== false)
	{
		$homepage_url = str_replace("http://","",$url);
	}
	elseif(strpos($url, 'www.') !== false)
	{
		$homepage_url = str_replace("www.","",$url);
	}
	
	if(strpos($homepage_url, '/') !== false)
	{
		$homepage_url_explode = explode("/",$homepage_url);
		$homepage_url = $homepage_url_explode[0];
	}
	
	if(strpos($homepage_url, '?') !== false)
	{
		$homepage_url_explode = explode("?",$homepage_url);
		$homepage_url = $homepage_url_explode[0];
	}
	
	//Get Path URL.
	if(strpos($url, 'https://www.') !== false)
	{
		$path_url = str_replace("https://www.","",$url);
	}
	elseif(strpos($url, 'http://www.') !== false)
	{
		$path_url = str_replace("http://www.","",$url);
	}
	elseif(strpos($url, 'https://') !== false)
	{
		$path_url = str_replace("https://","",$url);
	}
	elseif(strpos($url, 'http://') !== false)
	{
		$path_url = str_replace("http://","",$url);
	}
	elseif(strpos($url, 'www.') !== false)
	{
		$path_url = str_replace("www.","",$url);
	}
	
	if(strpos($path_url, '/') !== false)
	{
		$path_url = substr($path_url,strpos($path_url,"/"));
	}
	
	if(strpos($path_url, '?') !== false)
	{
		$path_url_explode = explode("?",$path_url);
		$questionmark_in_url = '?'.$path_url_explode[1];
		$path_url = $path_url_explode[0];
	}
	else
	{
		$questionmark_in_url = '';
	}
	
	if(strpos($path_url, '.') !== false)
	{
		$path_url_explode = explode(".",$path_url);
		$path_url = $path_url_explode[0];
	}
	
	$path_url = trim($path_url,"/");
	$path_url = trim($path_url," ");
}