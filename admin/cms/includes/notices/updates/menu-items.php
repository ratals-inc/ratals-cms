<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD WEBSITE MENU ITEMS
try
{		
	$admin_webiste_menu_items_packages = array();
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/data/menus-items.php'))
	{
		$admin_webiste_menu_items_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/data/menus-items.php'))
	{
		$admin_webiste_menu_items_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/menus-items.php'))
	{
		$admin_webiste_menu_items_packages[] = 'ai';
	}
	
	if(!empty($admin_webiste_menu_items_packages))
	{
		foreach($admin_webiste_menu_items_packages as $admin_webiste_menu_items_package)
		{
			include($temp_extract_dir.'/admin/'.$admin_webiste_menu_items_package.'/installer/data/menus-items.php');
		}
	}
	
	writeToInstallLog('Completed website menu items update check.');
}
catch(\Throwable $e)
{
	writeToInstallLog('Failed website menu items update: '.$e->getMessage());
}