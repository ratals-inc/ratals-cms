<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-media.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-media.php');
}
else
{
	//Delete Media Rows and files
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-media')
	{
		foreach($_POST['deleteRow'] as $delete_media_row_id)
		{
			$sql_get_media_name_to_delete_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? LIMIT 1', [$delete_media_row_id]);
			
			if(!empty($sql_get_media_name_to_delete_data))
			{
				$original_media_id = $sql_get_media_name_to_delete_data['original_media_id'];
				
				$path = '';
				if(file_exists($_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$sql_get_media_name_to_delete_data['media_url']) && $sql_get_media_name_to_delete_data['media_type'] == 'Image')
				{
					$path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$sql_get_media_name_to_delete_data['media_url'];
					unlink($path);
					
					//If directory becomes empty, remove it.
					$dir_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id;
					if(is_readable($dir_path) && is_dir($dir_path) && count(scandir($dir_path)) === 2)
					{
						rmdir($dir_path);
					}
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/sites/media/videos/'.$sql_get_media_name_to_delete_data['media_url']) && $sql_get_media_name_to_delete_data['media_type'] == 'Video')
				{
					$path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/videos/'.$sql_get_media_name_to_delete_data['media_url'];
					unlink($path);
				}
				elseif(file_exists($_SERVER['DOCUMENT_ROOT'].'/sites/media/files/'.$sql_get_media_name_to_delete_data['media_url']) && $sql_get_media_name_to_delete_data['media_type'] == 'File')
				{
					$path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/files/'.$sql_get_media_name_to_delete_data['media_url'];
					unlink($path);
				}
		
				if(!empty($sql_get_media_name_to_delete_data['id']))
				{
					$results->getDeleteRecord(__LINE__, __FILE__, 'media', 'WHERE `id` = ?', [$sql_get_media_name_to_delete_data['id']]);
				}
			}
		}
		
		echo "1";
		exit;
	}
}