<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-templates.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-templates.php');
}
else
{
	//Delete Templates and Template Items
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && 'delete-templates')
	{
		$get_rid = $_POST['getRid'];
		
		//Start function to delete all template files on server
		$all_directories = array();
		function getAllDirectories($files)
		{
			global $all_directories;
			
			foreach($files as $file)
			{
				if(strpos($file,'[') !== false || strpos($file,']') !== false)
				{
					$file_new_name = str_replace(array("[","]"),'',$file);
					rename($file,$file_new_name);
					$file = $file_new_name;
				}
				
				if(!is_dir($file))
				{
					unlink($file);
					getAllDirectories(GLOB($file."\*"));
				}
				else
				{
					$all_directories[] = $file;
					getAllDirectories(GLOB($file."\*"));
				}
			}
		}
		//End function to delete all template files on server	
		
		foreach($_POST['deleteRow'] as $row_id)
		{
			if($_SESSION['admin_table_name'] == "templates")
			{
				$sql_get_directory_name_to_delete_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				$template_directy_name = $sql_get_directory_name_to_delete_data['directory_folder_name'];
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'templates', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				//Start remove all template files and directories & call function
				if(!empty($template_directy_name) && file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$template_directy_name))
				{
					$files = GLOB(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$template_directy_name."/*");
	
					if(!empty($files)) 
					{
						getAllDirectories($files);
						rsort($all_directories);
					}
					if(!empty($all_directories))
					{
						foreach($all_directories as $directory)
						{
							if(file_exists($directory))
							{
								rmdir($directory);
							}
						}
					}
					
					rmdir(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$template_directy_name);
				}
				//End remove all template files and directories & call function
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'template_files', 'WHERE `site_id` = ? AND `templates_id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
			}
			elseif($_SESSION['admin_table_name'] == "template_files")
			{
				$sql_get_directory_name_to_delete_data =  $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $get_rid]);
				$template_directory_name_to_delete = $sql_get_directory_name_to_delete_data['directory_folder_name'];
				
				$sql_get_template_file_name_to_delete_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'template_files', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				$template_file_name = $sql_get_template_file_name_to_delete_data['filename'];
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'template_files', 'WHERE `site_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $row_id]);
				
				if(!empty($template_directory_name_to_delete) && !empty($template_file_name) && file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$template_directory_name_to_delete."/".$template_file_name))
				{
					unlink(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$template_directory_name_to_delete."/".$template_file_name);
				}
			}
		}
		
		if($_SESSION['admin_table_name'] == "template_files")
		{
			//Get template files count
			$sql_template_files_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'template_files', 'WHERE `templates_id` = ? AND `site_id` = ?', [$get_rid, $_SESSION["site_set_for_editing"]]);
			
			//Update template files count
			$results->getUpdateRecord(__LINE__, __FILE__, 'templates', '`sub_items` = ?, `updated_date` = UTC_TIMESTAMP(),`updated_by` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$sql_template_files_check, $_SESSION['user_username'], $get_rid, $_SESSION["site_set_for_editing"]]);
		}
		
		//Clear cache on save.
		if($_SESSION['admin_site_id_global'] == 'No')
		{
			clearSiteCache($_SESSION['site_set_for_editing']);
		}
		else
		{
			clearAllSiteCache();
		}
		
		echo "1";
		exit;
	}
}