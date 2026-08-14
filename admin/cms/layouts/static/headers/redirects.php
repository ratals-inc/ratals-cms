<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/redirects.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/redirects.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'redirects')
	{
		$sql_record_data_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `table_name` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], $_SESSION['admin_table_name'], trim($_GET["rid"] ?? '')]);
		if(empty($sql_record_data_rows)) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
		
		if($sites["url_structure"] == 'Hierarchy') { $url_structure_set = "hierarchy"; } else { $url_structure_set = "flat"; }
		
		if(isset($_POST['change_urls']) || isset($_POST['change_urls_redirect']))
		{
			$submitted_redirects = $_POST["redirects"];
		}
		
		if(isset($_POST['delete_conflicting_redirects']))
		{
			$conflicting_redirects = $_POST["conflicting_redirects"];
		}
		
		//Delete conflicting url redirects
		if(isset($_POST['cancel'])) 
		{
			header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&redirects=canceled");
			exit();
		}
		
		//Delete conflicting url redirects
		if(isset($_POST['delete_conflicting_redirects']) && !empty($conflicting_redirects)) 
		{
			foreach($conflicting_redirects as $conflicting_redirect)
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'redirects', 'WHERE `site_id` = ? AND `old_url` = ?', [$_SESSION["site_set_for_editing"], $conflicting_redirect]);
			}
			
			header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&redirects=deleted");
			exit();
		}
		
		//Update URLs, Build Paths, Create Redirects
		if((isset($_POST['change_urls']) || isset($_POST['change_urls_redirect'])) && !empty($submitted_redirects)) 
		{
			//Update all URLs to New URL
			foreach($submitted_redirects as $update_urls)
			{
				if($update_urls["url_type"] == "hierarchy") { $url_column = "hierarchy_url"; } else { $url_column = "flat_url"; } 
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'urls', '`'.$url_column.'` = ?', 'WHERE `site_id` = ? AND `id` = ? AND `'.$url_column.'` = ?', [$update_urls["new_url"], $_SESSION["site_set_for_editing"], $update_urls["id"], $update_urls["old_url"]]);
			}
		
			//Build correct Hierarchy path from new urls
			$url_changes_array = array();
			foreach($submitted_redirects as $get_new_url_path_level)
			{
				$hierarchy_url_path = '';
				$full_url_path_ids = '';
				$full_url_path_ids_array = array();
				
				if(strpos($get_new_url_path_level["new_url"], "/") !== false)
				{
					$hierarchy_url_explode = explode("/", $get_new_url_path_level["new_url"], -1);
					
					foreach($hierarchy_url_explode as $hierarchy_url_paths)
					{
						$hierarchy_url_path .= $hierarchy_url_paths."/";
						
						$sql_get_hierarchy_url_path = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `hierarchy_url` = ?', [$_SESSION["site_set_for_editing"], trim($hierarchy_url_path, "/")]);
						
						if(!empty($sql_get_hierarchy_url_path))
						{
							foreach($sql_get_hierarchy_url_path as $sql_get_hierarchy_url_path_rows)
							{
								$full_url_path_ids .= $sql_get_hierarchy_url_path_rows["id"]."/";
								$full_url_path_ids_array = array("new_path_level_ids" => $full_url_path_ids);
							}
						}
					}
				}
				else
				{
					$full_url_path_ids_array = array("new_path_level_ids" => 0);
				}
				
				$url_changes_array[] = array_merge($get_new_url_path_level,$full_url_path_ids_array);	
			}
			
			//Update all Path ids to match New URLs
			foreach($url_changes_array as $update_path_levels)
			{
				if($update_path_levels["url_type"] == "hierarchy") 
				{
					$results->getUpdateRecord(__LINE__, __FILE__, 'urls', '`path_level` = ?', 'WHERE `site_id` = ? AND `id` = ? AND `hierarchy_url` = ?', [$update_path_levels["new_path_level_ids"], $_SESSION["site_set_for_editing"], $update_path_levels["id"], $update_path_levels["new_url"]]);
				}
			}
		
			//Create Redirects
			if(isset($_POST['change_urls_redirect'])) 
			{
				foreach($url_changes_array as $insert_redirects)
				{
					if($insert_redirects["url_type"] == $url_structure_set && $insert_redirects["redirect_type"] != "404")	
					{
						$results->getInsertRecord(__LINE__, __FILE__, 'redirects', '`site_id`, `status`, `redirect_type`, `old_url`, `new_url`, `custom_fields`, `updated_by`, `updated_date`, `created_by`, `created_date`', '?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()', [$_SESSION["site_set_for_editing"], '1', $insert_redirects["redirect_type"], $insert_redirects["old_url"], $insert_redirects["new_url"], '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
					}
				}
				
				header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&redirects=updated-created");
				exit();	
			}
			
			header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&redirects=updated");
			exit();	
		}
	}
}