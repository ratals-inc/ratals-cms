<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/media_url.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/media_url.php');
}
else
{
	if(!class_exists('media_url_aecn'))
	{
		class media_url_aecn
		{
			public function media_url_aecn($table_name, $admin_field, &$post_values, &$errors, $current_values)
			{
				//If media filename is changed or added, make sure the new template filename doesn't already exist.
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					$new_image_url = array();
					if(strpos($_POST[$table_name][$admin_field["column_name"]], '.') !== false)
					{
						$new_image_url = explode('.', $_POST[$table_name][$admin_field["column_name"]]);
					}
					
					$media_type = strtolower($_POST[$table_name]['media_type'].'s');
					
					if($_SESSION['admin_type'] == 'edit' && count($new_image_url) < 2 || (empty($new_image_url[0]) || empty(end($new_image_url))))
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Enter a vaild file name. Example: my-image.gif.';
					}
					elseif($_SESSION['admin_type'] == 'edit' && $_POST[$table_name][$admin_field["column_name"]] != $current_values[$table_name][$admin_field["column_name"]] && file_exists(INSTALLATION_ROOT."/sites/media/".$media_type."/".$_POST[$table_name]['original_media_id']."/".$_POST[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' is being used on another media file here: '.INSTALLATION_ROOT."/sites/media/".$media_type."/".$_POST[$table_name]['original_media_id']."/".$_POST[$table_name][$admin_field["column_name"]];
					}
					elseif($_SESSION['admin_type'] == 'edit' && !file_exists(INSTALLATION_ROOT."/sites/media/".$media_type."/".$current_values[$table_name]['original_media_id']."/".$current_values[$table_name][$admin_field["column_name"]]) && $_POST[$table_name]['media_type'] != 'Video Embed')
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Original '.$admin_field["name"].' cannot be found on the server to change it here: '.INSTALLATION_ROOT."/sites/media/".$media_type."/".$current_values[$table_name]['original_media_id']."/".$current_values[$table_name][$admin_field["column_name"]];
					}
				}
			}
		}
		
		$class_media_url_aecn = new media_url_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_media_url_aecn->media_url_aecn($table_name, $admin_field, $post_values, $errors, $current_values);
	}
}