<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 3));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/media-popup-ajax.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/media-popup-ajax.php');
}
else
{
	if(isset($_POST["limit"]) && isset($_POST["start"]) && is_numeric($_POST["limit"]) && is_numeric($_POST["start"]))
	{	
		$query_paramortors = " AND `original_media` = 'Yes'";
		$query_values = array();
		$sort_by = '';
		
		if($_POST["images"] == 1)
		{
			$query_paramortors .= " AND `media_type` = 'Image'";
		}
		elseif($_POST["images"] == 2)
		{
			$query_paramortors .= " AND `media_type` = 'File'";
		}
		elseif($_POST["images"] == 3)
		{
			$query_paramortors .= " AND `media_type` = 'Video'";
		}
		elseif($_POST["images"] == 4)
		{
			$query_paramortors .= " AND `media_type` = 'Video Embed'";
		}
		
		if(!empty($_POST["searchMedia"]))
		{
			$query_paramortors .= " AND (`media_tag` LIKE ? OR `media_url` LIKE ? OR `media_url` LIKE ?)";
			
			$searched_word = $_POST["searchMedia"]; 
			$query_values[] = '%'.$_POST["searchMedia"].'%'; //search for media tags.
			$query_values[] = '%'.str_replace(' ', '-',  $_POST["searchMedia"]).'%'; //search for media urls with dashes in palce of spaces.
			$query_values[] = '%'.str_replace(' ', '_',  $_POST["searchMedia"]).'%'; //search for media urls with underscores in palce of spaces.
		}
		
		if(!empty($query_paramortors))
		{
			$query_paramortors = ' WHERE '.trim($query_paramortors, ' AND ');
		}
		
		if($_POST["sortBy"] == 1)
		{
			$sort_by = " ORDER BY `id` ASC";
		}
		else
		{
			$sort_by = " ORDER BY `id` DESC";
		}
		
		$query_paramortors .= $sort_by.' LIMIT ?, ?';
		
		$query_values[] = $_POST["start"];
		$query_values[] = $_POST["limit"];
		
		$all_media_popup_rows = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'media', $query_paramortors, $query_values);
		
		if(!empty($all_media_popup_rows))
		{
			foreach($all_media_popup_rows as $all_media_popup_row)
			{
				if($all_media_popup_row["media_type"] == 'Image')
				{
					$original_media_id = $all_media_popup_row["original_media_id"];
					
					echo '<li><div class="wrapper pointer selectMedia" data-click="'.htmlspecialchars($all_media_popup_row["id"] ?? '').'"><img src="'.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.htmlspecialchars($all_media_popup_row["media_url"] ?? '').'" alt="'.htmlspecialchars($all_media_popup_row["media_tag"] ?? '').'" id="selected_image_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'"/><div class="alt" id="selected_tag_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'">'.htmlspecialchars($all_media_popup_row["media_tag"] ?? '').'</div>'.htmlspecialchars($all_media_popup_row["media_url"] ?? '').'</div></li>';
				}
				elseif($all_media_popup_row["media_type"] == 'File')
				{
					$media_popup_file_array = explode('.', $all_media_popup_row["media_url"]);
					echo '<li><div class="wrapper pointer selectMedia" data-click="'.htmlspecialchars($all_media_popup_row["id"] ?? '').'">
					<object loading="lazy" decoding="async" data="'.INSTALLATION_URL_PATH.'/sites/media/files/'.htmlspecialchars($all_media_popup_row["media_url"] ?? '').'" type="application/'.$media_popup_file_array[1].'" id="selected_image_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'" width="100%" height="100%"></object>
			<div class="alt" id="selected_tag_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'">'.htmlspecialchars($all_media_popup_row["media_tag"] ?? '').'</div>'.htmlspecialchars($all_media_popup_row["media_url"] ?? '').'</div></li>';
				}
				elseif($all_media_popup_row["media_type"] == 'Video')
				{
					$media_popup_video_array = explode('.', $all_media_popup_row["media_url"]);
					echo '<li><div class="wrapper pointer selectMedia" data-click="'.htmlspecialchars($all_media_popup_row["id"] ?? '').'">
			<video loading="lazy" decoding="async" width="100%" height="auto" controls="controls" muted><source src="'.INSTALLATION_URL_PATH.'/sites/media/videos/'.htmlspecialchars($all_media_popup_row["media_url"] ?? '').'" type="video/'.$media_popup_video_array[1].'" title="'.htmlspecialchars($all_media_popup_row["media_tag"] ?? '').'" id="selected_image_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'"></video>
			<div class="alt" id="selected_tag_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'">'.htmlspecialchars($all_media_popup_row["media_tag"] ?? '').'</div>'.htmlspecialchars($all_media_popup_row["media_url"] ?? '').'</div></li>';
				}
				elseif($all_media_popup_row["media_type"] == 'Video Embed')
				{
					echo '<li><div class="wrapper pointer selectMedia" data-click="'.htmlspecialchars($all_media_popup_row["id"] ?? '').'">
					<div class="video-embed"><iframe loading="lazy" decoding="async" width="100%" height="" src="'.htmlspecialchars($all_media_popup_row["media_url"] ?? '').'" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen id="selected_image_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'"></iframe></div>				
			<div class="alt" id="selected_tag_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'">'.htmlspecialchars($all_media_popup_row["media_tag"] ?? '').'</div>'.htmlspecialchars($all_media_popup_row["media_url"] ?? '').'</div></li>';
				}
			}
		}
		exit();
	}
}