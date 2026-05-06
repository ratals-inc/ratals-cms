<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD WEBSITE TEMPLATE FILES
try
{
	$install_template = 'No';
	$update_template = 'Yes';
	
	//Save $site_id that started update as we have to loop through site_ids for each template.
	$hold_site_id = $site_id;
	
	//Get last count for menu items, templates, etc.
	include($temp_extract_dir.'/admin/cms/installer/data/counters.php');
	
	//counters.php sets the next site id so put current $site_id back.
	$site_id = $hold_site_id;
	
	//Get all templates installed to install missing template files for each template.
	$all_website_templates = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'templates', '', [], 'id');
	
	$website_template_packages = array();
	
	$website_template_packages[] = 'cms'; 
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/data/template-files.php')) 
	{
		$website_template_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/data/template-files.php'))
	{
		$website_template_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/template-files.php'))
	{
		$website_template_packages[] = 'ai';
	}
	
	foreach($all_website_templates as $template_id => $template_data)
	{
		$template_to_install = $template_data['directory_folder_name'];
		$site_id = $template_data['site_id'];
		
		foreach($website_template_packages as $website_template_package)
		{
			include($temp_extract_dir.'/admin/'.$website_template_package.'/installer/data/template-files.php');
		}
	}
	
	//Set site_id back when templates are done adding.
	$site_id = $hold_site_id;

	writeToInstallLog('Completed website template files check.');
}
catch(\Throwable $e)
{
	writeToInstallLog('Failed checking/adding website template files: '.$e->getMessage());
}