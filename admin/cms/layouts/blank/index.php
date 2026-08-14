<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/blank/index.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/blank/index.php');
}
else
{
	if($level >= $_SESSION['admin_page_level'])
	{
		//Auto loader - headers
		$types_to_load = array();
		
		if(is_dir(INSTALLATION_ROOT.'/admin/cms/layouts/blank/headers')) 
		{
			$types_to_load[] = 'cms'; 
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/commerce/layouts/blank/headers')) 
		{
			$types_to_load[] = 'commerce';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/erp/layouts/blank/headers'))
		{
			$types_to_load[] = 'erp';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/ai/layouts/blank/headers')) 
		{
			$types_to_load[] = 'ai';
		}
		
		foreach($types_to_load as $type_to_load)
		{
			$existing_files = array();
			$directory_path = '/admin/'.$type_to_load.'/layouts/blank/headers';
			$auto_loader_path = INSTALLATION_ROOT.$directory_path;
			$auto_loader_files = array_diff(scandir($auto_loader_path), array('.', '..'));
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
		
		//Auto loader - scripts
		$types_to_load = array();
		
		if(is_dir(INSTALLATION_ROOT.'/admin/cms/layouts/blank/scripts')) 
		{
			$types_to_load[] = 'cms'; 
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/commerce/layouts/blank/scripts')) 
		{
			$types_to_load[] = 'commerce';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/erp/layouts/blank/scripts'))
		{
			$types_to_load[] = 'erp';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/ai/layouts/blank/scripts')) 
		{
			$types_to_load[] = 'ai';
		}
		
		foreach($types_to_load as $type_to_load)
		{
			$existing_files = array();
			$directory_path = '/admin/'.$type_to_load.'/layouts/blank/scripts';
			$auto_loader_path = INSTALLATION_ROOT.$directory_path;
			$auto_loader_files = array_diff(scandir($auto_loader_path), array('.', '..'));
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
		
		//Auto loader - addons
		$types_to_load = array();
		
		if(is_dir(INSTALLATION_ROOT.'/admin/cms/layouts/blank/addons')) 
		{
			$types_to_load[] = 'cms';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/commerce/layouts/blank/addons')) 
		{
			$types_to_load[] = 'commerce';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/erp/layouts/blank/addons'))
		{
			$types_to_load[] = 'erp';
		}
		
		if(is_dir(INSTALLATION_ROOT.'/admin/ai/layouts/blank/addons')) 
		{
			$types_to_load[] = 'ai';
		}
		
		foreach($types_to_load as $type_to_load)
		{
			$existing_files = array();
			$directory_path = '/admin/'.$type_to_load.'/layouts/blank/addons';
			$auto_loader_path = INSTALLATION_ROOT.$directory_path;
			$auto_loader_files = array_diff(scandir($auto_loader_path), array('.', '..'));
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
	else
	{
		echo $account_message;
    }
}