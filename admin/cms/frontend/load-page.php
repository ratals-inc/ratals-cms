<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page.php');
}
else
{
	//Build array to load a page
	if(!empty($pages_data))
	{
		//Get page url for loaded page
		$pages_data["pages_url"] = getUrl($pages_data["custom_link"], $pages_data["url_extension"], $sites_end_urls_with, $url_structure, $domain, $pages_data["hierarchy_url"], $pages_data["flat_url"], '', $pages_data['id'], $home_page);
		
		$pages_data['author_bio'] = array();
		
		//Get media for loaded page
		$pages_data['media_data'] = mediaIdArray($pages_data['media']);
		
		//Auto Loader - First, get all files in directory of /hooks/admin/cms and commerce and erp and ai/frontend/load-page/all-files where you have over written a core file. Second get core file if a hook has not been created for it. Last it will check the hooks/custom folder for custom fields you have created that are not in the core file structure. 
		$types_to_load = array();
		
		if(is_dir(INSTALLATION_ROOT.'/admin/cms/frontend/load-page')) 
		{
			$types_to_load[] = 'cms'; 
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/commerce/frontend/load-page')) 
		{
			$types_to_load[] = 'commerce';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/erp/frontend/load-page'))
		{
			$types_to_load[] = 'erp';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/ai/frontend/load-page')) 
		{
			$types_to_load[] = 'ai';
		}
		
		foreach($types_to_load as $type_to_load)
		{
			$directory_path = '/admin/'.$type_to_load.'/frontend/load-page';
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
		
		//Get sub items assigned.
		$pages_data['sub_items'] = getSubItems($pages_data, $site_id, $pages_data['id'], $domain, $home_page, $url_structure, $sites_end_urls_with, $url);
		
		//This gets blog categories that show in the sidebar.
		include "sidebar-blog-categories.php";
		
		//This gets all template includes that are active. Example of incldues are header, footer, etc.
		include "active-template-includes.php";
		
		//Add hreflang string into $pages_data
		$pages_data['hreflang'] = $hreflang;
		
		//This outputs all the data to load a page template.
		$data_array = $pages_data + $sidebar_blog_categories + $sidebar_active_template_items;
	}
}