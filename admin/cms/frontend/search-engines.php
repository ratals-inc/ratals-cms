<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/search-engines.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/search-engines.php');
}
else
{
	//Search Engines Settings - Select everything from search_engines table.
	$search_engines_settings = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'search_engines', 'WHERE `site_id` = ? LIMIT 1', [$site_id]);
	
	$site_wide_meta_robots = $search_engines_settings["meta_robots"] ?? '';
}