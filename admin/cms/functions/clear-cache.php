<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/clear-cache.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/clear-cache.php');
}
else
{
	function clearSiteCache($site_id)
	{
		if(empty($site_id))
		{
			return false;
		}

		$directory_path = INSTALLATION_ROOT.'/storage/cache/'.$site_id;
		
		if(!is_dir($directory_path))
		{
			return true;
		}
		
		$directory_contents = scandir($directory_path);
		
		foreach($directory_contents as $item)
		{
			if($item !== '.' && $item !== '..')
			{
				$item_path = $directory_path.'/'.$item;
				
				if(is_dir($item_path))
				{
					clearSiteCacheDirectory($item_path);
				}
				else
				{
					unlink($item_path);
				}
			}
		}
		
		rmdir($directory_path);
		
		return true;
	}

	function clearAllSiteCache()
	{
		$directory_path = INSTALLATION_ROOT.'/storage/cache';
		
		if(!is_dir($directory_path))
		{
			return true;
		}
		
		$directory_contents = scandir($directory_path);
		
		foreach($directory_contents as $item)
		{
			if($item !== '.' && $item !== '..')
			{
				$item_path = $directory_path.'/'.$item;
				
				if(is_dir($item_path))
				{
					clearSiteCacheDirectory($item_path);
				}
				else
				{
					unlink($item_path);
				}
			}
		}
		
		return true;
	}

	function clearSiteCacheDirectory($directory_path)
	{
		$directory_contents = scandir($directory_path);
		
		foreach($directory_contents as $item)
		{
			if($item !== '.' && $item !== '..')
			{
				$item_path = $directory_path.'/'.$item;
				
				if(is_dir($item_path))
				{
					clearSiteCacheDirectory($item_path);
				}
				else
				{
					unlink($item_path);
				}
			}
		}
		
		rmdir($directory_path);
	}
}