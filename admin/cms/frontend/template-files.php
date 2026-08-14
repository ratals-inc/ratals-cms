<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/template-files.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/template-files.php');
}
else
{
	$template_file_type_row = array();
	
	if(!empty($pages_data['template']))
	{
		$template_file_type_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'template_files', 'WHERE `id` = ?', [$pages_data['template']]);
	}
}