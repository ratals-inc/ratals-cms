<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/active-template.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/active-template.php');
}
else
{
	//Get Active Template Path
	$sql_templates_installed = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `status` = ?', [$site_id, '1']);
	
	$active_template_path = $sql_templates_installed["directory_folder_name"] ?? '';
}