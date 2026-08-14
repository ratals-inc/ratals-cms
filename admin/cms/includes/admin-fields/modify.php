<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify.php');
}
else
{
	//Auto loader - modify
	$types_to_load = array();
	
	if(is_dir(INSTALLATION_ROOT.'/admin/cms/includes/admin-fields/modify')) 
	{
		$types_to_load[] = 'cms';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/commerce/includes/admin-fields/modify')) 
	{
		$types_to_load[] = 'commerce';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/erp/includes/admin-fields/modify'))
	{
		$types_to_load[] = 'erp';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/ai/includes/admin-fields/modify')) 
	{
		$types_to_load[] = 'ai';
	}
	
	foreach($types_to_load as $type_to_load)
	{
		$directory_path = '/admin/'.$type_to_load.'/includes/admin-fields/modify';
		$existing_files = array();
		$auto_loader_files = INSTALLATION_ROOT.$directory_path;
		$auto_loader_files = array_diff(scandir($auto_loader_files), array('.', '..'));
		if(!empty($auto_loader_files))
		{
			foreach($auto_loader_files as $auto_loader_file)
			{
				$existing_files[] = $auto_loader_file;
				
				if(file_exists(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file))
				{
					include_once(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file);
				}
				else
				{			
					include_once(INSTALLATION_ROOT.$directory_path.'/'.$auto_loader_file);
				}
			}
		}
	}
}