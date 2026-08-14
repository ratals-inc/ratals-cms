<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/media_type.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/media_type.php');
}
else
{
	if(!class_exists('media_type_aecn'))
	{
		class media_type_aecn
		{
			public function media_type_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'media' && $_SESSION['admin_type'] == 'add' && $_SESSION['admin_class'] == 'add-video-embed' && isset($_POST[$table_name]['media_url']) && !empty($_POST[$table_name]['media_url']))
				{
					$post_values[$table_name]['media_url'] = $_POST[$table_name]['media_url'];
					
					//Video Embed URL
					//If a user adds an embed video URL from a site like YouTube, this will set values for other fields on add media so they do not validate while adding an embed url.
					$_POST[$table_name]['media_type'] = 'Video Embed';
					$post_values[$table_name]['media_type'] = 'Video Embed';
					
					$_POST[$table_name]['original_media'] = 'Yes';
					$post_values[$table_name]['original_media'] = 'Yes';
					
					$_POST[$table_name]['original_media_id'] = NULL;
					$post_values[$table_name]['original_media_id'] = NULL;
					
					$_POST[$table_name]['media_tag'] = $_POST[$table_name]['media_url'];
					$post_values[$table_name]['media_tag'] = $_POST[$table_name]['media_url'];
					
					$_POST[$table_name]['width'] = '1920';
					$post_values[$table_name]['width'] = '1920';
					
					$_POST[$table_name]['height'] = '1080';
					$post_values[$table_name]['height'] = '1080';
				}
			}
		}
		
		$class_media_type_aecn = new media_type_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_media_type_aecn->media_type_aecn($table_name, $admin_field, $post_values, $errors);
	}
}