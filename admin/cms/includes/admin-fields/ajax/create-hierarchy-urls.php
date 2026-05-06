<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/ajax/create-hierarchy-urls.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/ajax/create-hierarchy-urls.php');
}
else
{
	//Get sub categories to build url paths
	if(isset($_POST['edit_id']) && isset($_POST["last_id"]) && isset($_POST["all_ids"]) && $_POST['fetch_categories'] == 'yes')
	{
		if(!isset($_SESSION['user_id']))
		{
			echo '<div class="validation">Looks like your login session has ended. Please <a href="/'.$_SESSION['admin_directory'].'/login.php">login here</a>.</div>';
			exit();
		}
		
		$all_sub_category_id_selected = array();
		$last_sub_category_id_selected = $_POST['last_id'];
		$all_sub_category_id_selected = $_POST["all_ids"];
		$sub_category_type = $_POST["type"];
		
		if(strpos($all_sub_category_id_selected, ',') !== false)
		{
			//Explode string of ids submitted on the last id submitted, then add the last id back on that was used to explode string.
			//If the urls is like this 1/2/3/4/5 and someone changes 3 to empty it removs 4/5 and hides the dropdowns for 4/5.
			//If the urls is like this 1/2/3/4/5 and someone changes 3 to 6, it get sub ids under 6 and hide dropdowns for 4/5. It will also how sub under 6 if there are any.
			if($last_sub_category_id_selected != '')
			{
				$all_sub_category_id_selected = explode($last_sub_category_id_selected, $all_sub_category_id_selected);
				
				$all_sub_category_id_selected = $all_sub_category_id_selected[0].$last_sub_category_id_selected;
			}
		}
		
		if(!empty($all_sub_category_id_selected))
		{
			$all_sub_category_id_selected = str_replace(",,", ",", $all_sub_category_id_selected);
		}
		
		$all_sub_category_id_selected = trim($all_sub_category_id_selected, ",");
		$all_sub_category_id_selected = explode(",", $all_sub_category_id_selected);
		$all_sub_category_id_selected = array_unique($all_sub_category_id_selected);
		$all_sub_category_id_selected = array_filter($all_sub_category_id_selected);
		
		$selected_counter = '1';
		$sub_category_path_level = '';
		
		if(!empty($all_sub_category_id_selected))
		{
			foreach($all_sub_category_id_selected as $all_sub_category_id)
			{
				foreach($all_sub_category_id_selected as $sub_category_path)
				{
					$sub_category_path_level .= $sub_category_path."/";
					
					if($all_sub_category_id == $sub_category_path)
					{
						$sub_category_path_levels = $sub_category_path_level;
						break;
					}
				}
				
				$sub_categories_results = '';
				
				$sql_sub_categories = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `path_level` = ? AND `id` != ? ORDER BY `meta_title` ASC', [$_SESSION["site_set_for_editing"], $sub_category_path_levels, $_POST['edit_id']]);
				
				if(!empty($sql_sub_categories))
				{
					foreach($sql_sub_categories as $sql_sub_categories_row)
					{			
						$selected = '';
						if(array_key_exists($selected_counter, $all_sub_category_id_selected))
						{
							if($all_sub_category_id_selected[$selected_counter] == $sql_sub_categories_row["id"]) 
							{ 
								$selected = " selected";
							}
						}
						
						$sub_categories_results .= '<option value="'.$sql_sub_categories_row["id"].'"'.$selected.'>'.$sql_sub_categories_row["meta_title"].'</option>';
					}
					
					echo '<div class="edit-field-padding">
					<select name="urls[path_level][]" class="pathLevelUrls" data-click="'.htmlspecialchars($_POST['edit_id'] ?? '').'">
					<option value="">Select URL</option>';
					echo $sub_categories_results;
					echo '</select>
					</div>';
				}
				
				$sub_categories_results = '';
				$sub_category_path_level  = '';
				$selected_counter++;
			}
		}
		exit;
	}
	
	//Get hierarchy_url for last category selected and display it
	if(isset($_POST["last_id"]) && isset($_POST["all_ids"]) && $_POST['fetch_url'] == 'yes')
	{
		$all_sub_category_id_selected = array();
		$last_sub_category_id_selected = $_POST['last_id'];
		$all_sub_category_id_selected = $_POST["all_ids"];
		$sub_category_type = $_POST["type"];
		
		if(strpos($all_sub_category_id_selected, ',') !== false)
		{
			//Explode string of ids submitted on the last id submitted, then add the last id back on that was used to explode string.
			//If the urls is like this 1/2/3/4/5 and someone changes 3 to empty it removs 4/5 and hides the dropdowns for 4/5.
			//If the urls is like this 1/2/3/4/5 and someone changes 3 to 6, it get sub ids under 6 and hide dropdowns for 4/5. It will also how sub under 6 if there are any.
			if($last_sub_category_id_selected != '')
			{
				$all_sub_category_id_selected = explode($last_sub_category_id_selected, $all_sub_category_id_selected);
				
				$all_sub_category_id_selected = $all_sub_category_id_selected[0].$last_sub_category_id_selected;
			}
		}
		
		if(!empty($all_sub_category_id_selected))
		{
			$all_sub_category_id_selected = str_replace(",,", ",", $all_sub_category_id_selected);
		}
		
		$all_sub_category_id_selected = trim($all_sub_category_id_selected, ",");
		$all_sub_category_id_selected = explode(",", $all_sub_category_id_selected);
		$all_sub_category_id_selected = array_unique($all_sub_category_id_selected);
		$all_sub_category_id_selected = array_filter($all_sub_category_id_selected);
		$all_sub_category_id_selected = end($all_sub_category_id_selected);
		
		$sql_category_name_for_url_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `id` = ? ORDER BY `meta_title` ASC', [$_SESSION["site_set_for_editing"], $all_sub_category_id_selected]);
		
		$display_pages_hierarchy_url = '';
		if(!empty($sql_category_name_for_url_rows["hierarchy_url"]))
		{
			$display_pages_hierarchy_url = $sql_category_name_for_url_rows["hierarchy_url"];
		}
		
		$url_path_name = '';
		$url_path_name .= "/".$display_pages_hierarchy_url;
		$url_path_name = trim($url_path_name, "/");
		
		if(!empty($url_path_name))
		{
			$url_path_name = $url_path_name."/";
		}
		
		echo $url_path_name;
		exit;
	}
}