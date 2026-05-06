<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/multipleMedia.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/multipleMedia.php');
}
else
{
	if(!class_exists('multipleMediaAeaf'))
	{
		class multipleMediaAeaf
		{
			public function multipleMediaAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $domain)
			{
				if($_SESSION['admin_table_name'] == "custom_fields_options")
				{
					if(!empty(trim($_GET['sub-page-rid'] ?? '')))
					{
						$custom_field_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ?', [trim($_GET['sub-page-rid'] ?? '')]);
						$custom_field_type['search_as'] = $custom_field_type['cf_search_as'];
						$custom_field_type['display_as'] = $custom_field_type['cf_display_as'];
					}
					elseif(!empty(trim($_GET["rid"] ?? '')))
					{
						$custom_field_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
						$custom_field_type['search_as'] = $custom_field_type['cf_search_as'];
						$custom_field_type['display_as'] = $custom_field_type['cf_display_as'];
					}
				}
				
				if($_SESSION['admin_table_name'] == "custom_fields_options" && ((isset($custom_field_type) && $custom_field_type['field_type'] == 'Content Field' && $custom_field_type['display_as'] != 'multipleMedia' && $custom_field_type['display_as'] != 'singleMedia') ||  (isset($custom_field_type) && $custom_field_type['field_type'] == 'Inventory Attribute' && $custom_field_type['display_as'] != 'boxes' && $custom_field_type['display_as'] != 'swatch') ||  (isset($custom_field_type) && $custom_field_type['field_type'] == 'Product Option' && $custom_field_type['display_as'] != 'boxes' && $custom_field_type['display_as'] != 'swatch')))
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="">';
				}
				else
				{
					include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/admin-fields/modify-js/sort-multiple-media.php';
					
					$media = array();
					if(!empty($field_value))
					{
						$multiple_media = array();
						 
						//!~~! splits each media
						if(strpos($field_value, '*||*') !== false)
						{
							$multiple_media = explode('*||*', $field_value);
						}
						else
						{
							$multiple_media[] = $field_value;
						}
						
						foreach($multiple_media as $media_ids_tags)
						{
							//!~~! splits media id and media tag
							$media_id_tag = explode('~||~',  $media_ids_tags);
							
							//Query db for media
							$sql_media_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$media_id_tag[0]]);
							
							if(!empty($sql_media_rows))
							{
								if($sql_media_rows['media_type'] == 'Image')
								{
									$original_media_id = $sql_media_rows["original_media_id"];
									
									$media[] = array('type' => 'Image', 'media_url' => $domain.'/sites/media/images/'.$original_media_id.'/'.$sql_media_rows['media_url'], 'media_tag' => $sql_media_rows['media_tag'], 'media_tag_value' => $media_id_tag[1], 'media_id_value' => $media_id_tag[0]);
								}
								elseif($sql_media_rows['media_type'] == 'File')
								{
									$media[] = array('type' => 'File', 'media_url' => $domain.'/sites/media/files/'.$sql_media_rows['media_url'], 'media_tag' => $sql_media_rows['media_tag'], 'media_tag_value' => $media_id_tag[1], 'media_id_value' => $media_id_tag[0]);
								}
								elseif($sql_media_rows['media_type'] == 'Video')
								{
									$media[] = array('type' => 'Video', 'media_url' => $domain.'/sites/media/videos/'.$sql_media_rows['media_url'], 'media_tag' => $sql_media_rows['media_tag'], 'media_tag_value' => $media_id_tag[1], 'media_id_value' => $media_id_tag[0]);
								}
								elseif($sql_media_rows['media_type'] == 'Video Embed')
								{
									$media[] = array('type' => 'Video Embed', 'media_url' => $sql_media_rows['media_url'], 'media_tag' => $sql_media_rows['media_tag'], 'media_tag_value' => $media_id_tag[1], 'media_id_value' => $media_id_tag[0]);
								}
							}
						}
					}
					
					if(isset($admin_field['custom_field_name']))
					{
						$custom_field_name = JSON_DECODE($admin_field['custom_field_name'] ?? '', true);
						
						$admin_field['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
					}
					
					echo '<div class="edit">
					<div class="edit-label">'.((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? '')).'</div>
					<div class="edit-field text">
					<ul class="multiple-media multiple-media-'.htmlspecialchars($admin_field["column_name"] ?? '').'" id="sortMultipleMedia-'.htmlspecialchars($admin_field["column_name"] ?? '').'">';
					
					$counter = 0;
					
					if(!empty($media))
					{
						foreach($media as $media_data)
						{
							if($media_data['type'] == 'Image')
							{
								echo '<li class="media" id="removeMultipleMedia_'.$_SESSION['multiple_media_counter'].'">
								<i class="close removeMultipleMedia" data-click="'.$_SESSION['multiple_media_counter'].',single-media-button-'.htmlspecialchars($admin_field["column_name"] ?? '').'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i>
								<i class="move move-handle"title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i>
								<img src="'.htmlspecialchars($media_data['media_url'] ?? '').'" id="media_swap_image_'.$_SESSION['multiple_media_counter'].'">
								<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'['.$counter.'][]" type="hidden" value="'.htmlspecialchars($media_data['media_id_value'] ?? '').'">
								<div class="text"><div class="tag"><input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'['.$counter.'][]" type="text" value="'.htmlspecialchars($media_data['media_tag_value'] ?? '').'" placeholder="'.htmlspecialchars($media_data['media_tag'] ?? '').'"></div>Media ID: <a href="'.$domain.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.htmlspecialchars($media_data['media_id_value'] ?? '').'" target="_blank">'.htmlspecialchars($media_data['media_id_value'] ?? '').'</a>
								</div></li>';
							}
							elseif($media_data['type'] == 'File')
							{
								$media_popup_file_array = explode('.', $media_data['media_url']);
								echo '<li class="media" id="removeMultipleMedia_'.$_SESSION['multiple_media_counter'].'">
								<i class="close removeMultipleMedia" data-click="'.$_SESSION['multiple_media_counter'].',single-media-button-'.htmlspecialchars($admin_field["column_name"] ?? '').'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i>
								<i class="move move-handle" aria-hidden="true" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i>
								<object data="'.htmlspecialchars($media_data['media_url'] ?? '').'" type="application/'.end($media_popup_file_array).'" id="selected_image_'.htmlspecialchars($all_media_popup_row["id"] ?? '').'" width="100%" height="100%"></object>
								<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'['.$counter.'][]" type="hidden" value="'.htmlspecialchars($media_data['media_id_value'] ?? '').'">
								<div class="text"><div class="tag"><input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'['.$counter.'][]" type="text" value="'.htmlspecialchars($media_data['media_tag_value'] ?? '').'" placeholder="'.htmlspecialchars($media_data['media_tag'] ?? '').'"></div>Media ID: <a href="'.$domain.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.htmlspecialchars($media_data['media_id_value'] ?? '').'" target="_blank">'.htmlspecialchars($media_data['media_id_value'] ?? '').'</a>
								</div></li>';
							}
							elseif($media_data['type'] == 'Video')
							{
								$media_popup_video_array = explode('.', $media_data['media_url']);
								echo '<li class="media" id="removeMultipleMedia_'.$_SESSION['multiple_media_counter'].'">
								<i class="close removeMultipleMedia" data-click="'.$_SESSION['multiple_media_counter'].',single-media-button-'.htmlspecialchars($admin_field["column_name"] ?? '').'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i>
								<i class="move move-handle" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i>
								<video controls="" preload="none"><source src="'.htmlspecialchars($media_data['media_url'] ?? '').'" type="video/'.$media_popup_video_array[1].'"></video>
								<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'['.$counter.'][]" type="hidden" value="'.htmlspecialchars($media_data['media_id_value'] ?? '').'">
								<div class="text"><div class="tag"><input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'['.$counter.'][]" type="text" value="'.htmlspecialchars($media_data['media_tag_value'] ?? '').'" placeholder="'.htmlspecialchars($media_data['media_tag'] ?? '').'"></div>Media ID: <a href="'.$domain.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.htmlspecialchars($media_data['media_id_value'] ?? '').'" target="_blank">'.htmlspecialchars($media_data['media_id_value'] ?? '').'</a>
								</div></li>';
							}
							elseif($media_data['type'] == 'Video Embed')
							{
								echo '<li class="media" id="removeMultipleMedia_'.$_SESSION['multiple_media_counter'].'">
								<i class="close removeMultipleMedia" data-click="'.$_SESSION['multiple_media_counter'].',single-media-button-'.htmlspecialchars($admin_field["column_name"] ?? '').'" title="Remove Media"><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i>
								<i class="move move-handle" title="Sort Media"><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i>
								<div class="video-embed"><iframe width="100%" height="" src="'.htmlspecialchars($media_data['media_url'] ?? '').'" title="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>
								<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'['.$counter.'][]" type="hidden" value="'.htmlspecialchars($media_data['media_id_value'] ?? '').'">
								<div class="text"><div class="tag"><input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'['.$counter.'][]" type="text" value="'.htmlspecialchars($media_data['media_tag_value'] ?? '').'" placeholder="'.htmlspecialchars($media_data['media_tag'] ?? '').'"></div>Media ID: <a href="'.$domain.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.htmlspecialchars($media_data['media_id_value'] ?? '').'" target="_blank">'.htmlspecialchars($media_data['media_id_value'] ?? '').'</a>
								</div></li>';
							}
							
							$counter ++;
							$_SESSION['multiple_media_counter']++;
						}
					}
					echo '<input name="next_media_counter" id="selectedMediaId" type="hidden" value="'.($_SESSION['multiple_media_counter'] - 1).'">';
					
					$media_buttons = '<button type="button" class="openMultipleMediaPopup" data-click="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').','.$admin_field["column_name"].','.$counter.'">Add Media</button>';
					
					echo '</ul>
					<div class="button media-button-left">
					'.$media_buttons.'
					</div>
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>';
				}
			}
		}
		
		$class_multipleMediaAeaf = new multipleMediaAeaf();
	}
	
	$class_multipleMediaAeaf->multipleMediaAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $domain);
}