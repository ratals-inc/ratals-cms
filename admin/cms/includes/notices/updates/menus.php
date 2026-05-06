<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//ADD WEBSITE MENUS
try
{
	$admin_webiste_menus_packages = array();
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/data/menus.php'))
	{
		$admin_webiste_menus_packages[] = 'commerce';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/data/menus.php'))
	{
		$admin_webiste_menus_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/data/menus.php'))
	{
		$admin_webiste_menus_packages[] = 'ai';
	}
	
	if(!empty($admin_webiste_menus_packages))
	{
		foreach($admin_webiste_menus_packages as $admin_webiste_menus_package)
		{
			include($temp_extract_dir.'/admin/'.$admin_webiste_menus_package.'/installer/data/menus.php');
		}
	}
	
	writeToInstallLog('Completed website menus update check.');
}
catch(\Throwable $e)
{
	writeToInstallLog('Failed website menus update: '.$e->getMessage());
}