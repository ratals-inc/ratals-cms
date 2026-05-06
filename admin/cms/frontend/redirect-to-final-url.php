<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/redirect-to-final-url.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/redirect-to-final-url.php');
}
else
{
	//redirects to homepage url
	if($home_page == $home_page_record_id && $url != $final_url_home_page_with_question_mark) 
	{
		if(isset($last_redirect_url[1]) && $last_redirect_url[1] == "302") 
		{
			header("HTTP/2 302 Found");
			header("Location: ".$final_url_home_page); 
			exit;
		}
		elseif(!empty($questionmark_in_url))
		{
			//Went back and forth on 301 or 302 here. If we redirect a page with parameters as 302 and the URL with parameters has link juice, it will not pass to the new URL. If we redirect with 301, it's going to tell the search engines that the new URL with parameters is the URL to index. We're hoping the canonical meta tag with push everything to the page with no parameters. We went with a 301 so the links are at least following to the new URL.
			header("HTTP/2 301 Moved Permanently");
			header("Location: ".$final_url_home_page_with_question_mark);
			exit;
		}
		else
		{
			header("HTTP/2 301 Moved Permanently");
			header("Location: ".$final_url_home_page); 
			exit;
		}
	}
	
	//redirects to non homepage urls - $url_path not empty
	if($home_page != $home_page_record_id && $url != $final_url_with_question_mark) 
	{
		if(isset($last_redirect_url[1]) && $last_redirect_url[1] == "302")
		{
			header("HTTP/2 302 Found");
			header("Location: ".$final_url); 
			exit;
		}
		elseif(!empty($questionmark_in_url))
		{
			//Went back and forth on 301 or 302 here. If we redirect a page with parameters as 302 and the URL with parameters has link juice, it will not pass to the new URL. If we redirect with 301, it's going to tell the search engines that the new URL with parameters is the URL to index. We're hoping the canonical meta tag with push everything to the page with no parameters. We went with a 301 so the links are at least following to the new URL.
			header("HTTP/2 301 Moved Permanently");
			header("Location: ".$final_url_with_question_mark);
			exit;
		}
		else
		{
			header("HTTP/2 301 Moved Permanently");
			header("Location: ".$final_url); 
			exit;
		}
	}
}