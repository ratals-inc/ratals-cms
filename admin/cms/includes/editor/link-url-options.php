<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/editor/link-url-options.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/editor/link-url-options.php');
}
else
{
	if(!isset($editor_urls))
	{
		//Get all URLs to create array of them that the WYSIWYG editor JS can use to create the URL link list with.
		$wysiwyg_editor_urls = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '`id`, `hierarchy_url`', 'urls', 'WHERE `site_id` = ? ORDER BY `hierarchy_url` ASC', [$_SESSION["site_set_for_editing"]]);
		
		$editor_urls = array();
		
		if(!empty($wysiwyg_editor_urls))
		{
			foreach($wysiwyg_editor_urls as $wysiwyg_editor_url)
			{
				if(!empty($wysiwyg_editor_url['id']) && !empty($wysiwyg_editor_url['hierarchy_url']))
				{
					$editor_urls[$wysiwyg_editor_url['id']] = $wysiwyg_editor_url['hierarchy_url'];
				}
			}
		}
	}
}