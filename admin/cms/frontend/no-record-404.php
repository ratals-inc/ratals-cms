<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/no-record-404.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/no-record-404.php');
}
else
{
	//Load 404 Template if No PAGES Record Found for $url_path But 404 Template is Found and Active
	if(($page_not_found_404 == 'Yes' || $pages_data["flat_url"] == "404" || $pages_data["hierarchy_url"] == "404") && file_exists("sites/".$site_id."/templates/".$active_template_path.'/'.$template_file_type_row['filename']) && !empty($sql_templates_installed)) 
	{ 
		header("HTTP/2 404");
		include_once("sites/".$site_id."/templates/".$active_template_path.'/'.$template_file_type_row['filename']);
		exit;
	}
	elseif($page_not_found_404 == 'Yes')
	{
		header("HTTP/2 404");
		echo "404 - The page you're looking for cannot be found. If you're the webmaster of this domain you should consider adding a custom 404 page in your template files.";
		exit;
	}
}