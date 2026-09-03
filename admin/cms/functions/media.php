<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/media.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/media.php');
}
else
{	
	if(!function_exists('mediaIdArray'))
	{
		function mediaIdArray($media)
		{
			global $domain, $site_id;
			
			$get_media_file = array();
			
			if(!empty($media))
			{
				if(strpos($media, '*||*') !== false)
				{
					$media_items = explode('*||*', $media);
					
					foreach($media_items as $media_item)
					{
						$media_array[] = explode('~||~', $media_item);
					}
				}
				elseif(strpos($media, '~||~') !== false)
				{
					$media_array[] = explode('~||~', $media);
				}
				elseif(is_numeric($media))
				{
					$media_array[] = array($media, '');
				}
				
				$all_media_ids = array();
				$all_media_placeholders = array();
				foreach($media_array as $media_array_item)
				{
					if(!in_array($media_array_item[0], $all_media_ids))
					{
						$all_media_ids[] = $media_array_item[0];
						$all_media_placeholders[] = '?';
					}
				}
				
				if(!empty($all_media_ids) && !empty($all_media_placeholders))
				{
					$all_media_placeholders = implode(',', $all_media_placeholders);
					
					$media_results = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'media', 'WHERE `id` IN ('.$all_media_placeholders.')', $all_media_ids, 'id');
				}
				
				foreach($media_array as $media_array_item)
				{
					$media_result = $media_results[$media_array_item[0]];
					
					$installation_path_url = trim(INSTALLATION_URL_PATH, '/');
					
					if(!empty($media_result))
					{
						if($media_result['media_type'] == 'Image')
						{
							$original_media_id = $media_result["original_media_id"];
							
							$media_result['path_url'] = $installation_path_url.'/sites/media/images/'.$original_media_id.'/'.$media_result['media_url'];
							$media_result['full_url'] = $domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$media_result['media_url'];
						}
						elseif($media_result['media_type'] == 'Video')
						{
							$media_result['path_url'] = $installation_path_url.'/sites/media/videos/'.$media_result['media_url'];
							$media_result['full_url'] = $domain.INSTALLATION_URL_PATH.'/sites/media/videos/'.$media_result['media_url'];
						}
						elseif($media_result['media_type'] == 'File')
						{
							$media_result['path_url'] = $installation_path_url.'/sites/media/files/'.$media_result['media_url'];
							$media_result['full_url'] = $domain.INSTALLATION_URL_PATH.'/sites/media/files/'.$media_result['media_url'];
						}
						
						if(!empty($media_result))
						{
							if(!empty($media_array_item[1])) 
							{
							   $media_result['media_tag'] = $media_array_item[1];
							} 
							else 
							{
							   $media_result['media_tag'] = $media_result["media_tag"];
							}
							
							$get_media_file[] = $media_result;
						}
					}
				}
			}
			
			return $get_media_file;
		}
	}
	
	if(!function_exists('mediaId'))
	{
		function mediaId($media_id, $lazy_load = '', $fetch_priority = '', $max_display_width = '', $custom_media_tag = '')
		{
			global $domain;
			
			//Get media when mediaId(); is embed as a string in content.
			if(strpos(($media_id ?? 0), "mediaId(") !== false)
			{
				//Explode content string to find all media in it.
				$embed_media_array = explode("[?!!!?]", str_replace(array("mediaId(",");"), "[?!!!?]", $media_id));
				
				if(count($embed_media_array) > 0)
				{
					$replace_media_array = array();
					
					foreach($embed_media_array as $media_array)
					{
						//Check if string has lazyloadyes, lazyloadno, fetchpriorityhigh, fetchpriorityauto, maxdisplaypixelwidth(", or alttitletag(" to know if its media in the string of content.
						if(strpos(strtolower(str_replace(' ', '', $media_array)), 'lazyloadyes') !== false 
							|| strpos(strtolower(str_replace(' ', '', $media_array)), 'lazyloadno') !== false
							|| strpos(strtolower(str_replace(' ', '', $media_array)), 'fetchpriorityhigh') !== false
							|| strpos(strtolower(str_replace(' ', '', $media_array)), 'fetchpriorityauto') !== false
							|| strpos(strtolower(str_replace(' ', '', $media_array)), 'maxdisplaypixelwidth("') !== false
							|| strpos(strtolower(str_replace(' ', '', $media_array)), 'alttitletag("') !== false)
						{
							$media_id_lazy_load_media_tag = explode(',', $media_array ?? '', 5);
							
							$requested_media_id = strtolower(str_replace(' ', '', trim($media_id_lazy_load_media_tag[0] ?? '')));
							$lazy_load = strtolower(str_replace(' ', '', trim($media_id_lazy_load_media_tag[1] ?? '')));
							$fetch_priority = strtolower(str_replace(' ', '', trim($media_id_lazy_load_media_tag[2] ?? '')));
							$max_display_width = trim($media_id_lazy_load_media_tag[3] ?? '');
							$custom_media_tag = trim($media_id_lazy_load_media_tag[4] ?? '');
							
							$sql_media_rows = array();
							if(is_numeric($requested_media_id))
							{
								//Limit to 30 media files. The original image format (GIF, PNG, etc.), along with AVIF and WebP conversions across all generated sizes, should never exceed 30 media files.
								$sql_media_rows = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? OR `original_media_id` = ? LIMIT 30', [$requested_media_id, $requested_media_id], 'id');
								rsort($sql_media_rows); //list the 'max-width' queries from smallest to largest
							}
							
							$lazy_load_set = 'no';
							if($lazy_load == 'lazyloadyes')
							{
								$lazy_load_set = 'yes';
							}
							
							$fetch_priority_set = 'no';
							if($fetch_priority == 'fetchpriorityhigh')
							{
								$fetch_priority_set = 'yes';
							}
							
							$custom_media_tag_set = '';
							if(isset($custom_media_tag) && !empty($custom_media_tag) && $custom_media_tag != 'altTitleTag("")')
							{
								$custom_media_tag_set = str_replace(array('altTitleTag("','")'), '', $custom_media_tag);
							}
							
							$max_display_width_set = '';
							if(isset($max_display_width) && !empty($max_display_width) && $max_display_width != 'maxDisplayPixelWidth("")')
							{
								$max_display_width_set = str_replace(array('maxDisplayPixelWidth("','")'), '', $max_display_width);
							}
							
							$media_output = '';
							if(!empty($sql_media_rows))
							{
								$media_output = displayMedia($sql_media_rows, $domain, $lazy_load_set, $fetch_priority_set, $custom_media_tag_set, $max_display_width_set);
							}
							
							if(!empty($media_output))
							{
								$replace_media_array[] = array("replace" => "mediaId(".$media_array.");", "media" => $media_output);
							}
						}
					}
					
					//If any media found, replace it.
					if(!empty($replace_media_array))
					{
						foreach($replace_media_array as $replace_media)
						{
							$media_id = str_replace($replace_media["replace"], $replace_media["media"], $media_id);
						}
					}
				}
				
				$return_media = $media_id;
			}
			//Get media when mediaId(); is a php function.
			else
			{
				$return_media = $media_id;
				
				$sql_media_rows = array();
				if(is_numeric($media_id))
				{
					$sql_media_rows = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? OR `original_media_id` = ? LIMIT 30', [$media_id, $media_id], 'id');
					rsort($sql_media_rows); //list the 'max-width' queries from smallest to largest
				}
				
				$lazy_load_set = 'no';
				$lazy_load = strtolower(str_replace(' ', '', $lazy_load));
				if($lazy_load =='lazyloadyes' || $lazy_load =='yes')
				{
					$lazy_load_set = 'yes';
				}
				
				$fetch_priority_set = 'no';
				$fetch_priority = strtolower(str_replace(' ', '', $fetch_priority));
				if($fetch_priority =='fetchpriorityhigh' || $fetch_priority =='yes')
				{
					$fetch_priority_set = 'yes';
				}
				
				$max_display_width = trim($max_display_width ?? '');
				
				if(!empty($sql_media_rows))
				{
					$return_media = displayMedia($sql_media_rows, $domain, $lazy_load_set, $fetch_priority_set, $custom_media_tag, $max_display_width);
				}
			}
			
			return $return_media;
		}
	}
	
	if(!function_exists('displayMedia'))
	{
		function displayMedia($sql_media_rows, $domain, $lazy_load, $fetch_priority, $custom_media_tag, $max_display_width)
		{
			$display_media = '';
			$lazy_load_image_iframe = '';
			$lazy_load_file = '';
			$lazy_load_video = '';
			$image_fetch_priority = '';
			
			if(strtolower($lazy_load ?? '') == 'lazyloadyes' || strtolower($lazy_load ?? '') == 'yes')
			{
				$lazy_load_image_iframe = ' loading="lazy" decoding="async"';
				$lazy_load_file = '';
				$lazy_load_video = ' preload="none"';
			}
			
			if(strtolower($fetch_priority ?? '') == 'fetchpriorityhigh' || strtolower($fetch_priority ?? '') == 'yes')
			{
				$image_fetch_priority = ' fetchpriority="high"';
			}
			
			if(isset($sql_media_rows[0]['media_type']) && !empty($sql_media_rows[0]['media_type']))
			{
				if($sql_media_rows[0]['media_type'] == 'Image')
				{
					$media_output_avif = '';
					$media_output_webp = '';
					$media_output_other = '';
					$media_output_srcset_avif = '';
					$media_output_srcset_webp = '';
					$media_output_srcset_other = '';
					$original_media = '';
					$original_image_file_type = '';
					$media_sizes = '';
					$media_css = '';
					$media_class_name = '';
					$original_image_size = '';
					
					foreach($sql_media_rows as $sql_media_row)
					{
						$type = $sql_media_row['media_type'];
						$original_media_id = $sql_media_row["original_media_id"];
						$file_name = $sql_media_row["media_url"];
						$media_output_data = explode('.', $sql_media_row["media_url"] ?? '');
						if(!empty($custom_media_tag))
						{
							$alt_title = $custom_media_tag;
						}
						else
						{
							$alt_title = $sql_media_row["media_tag"];
						}
						$width = $sql_media_row["width"];
						$height = $sql_media_row["height"];
						$media_poster = $sql_media_row["video_poster"];
						
						if(!empty($media_output_data))
						{
							if($media_output_data[1] == 'avif' && file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$media_output_data[0].'.avif'))
							{
								$media_output_srcset_avif .= $domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$media_output_data[0].'.avif '.$width.'w, ';
							}
							elseif($media_output_data[1] == 'webp' && file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$media_output_data[0].'.webp'))
							{
								$media_output_srcset_webp .= $domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$media_output_data[0].'.webp '.$width.'w, ';
							}
							else
							{
								$media_output_srcset_other .= $domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$file_name.' '.$width.'w, ';
								
								//Width of 240px is the smallest variant image created by default.
								if(!empty($max_display_width) && is_numeric($max_display_width) && $max_display_width > 0)
								{
									$max_display_width = intval($max_display_width);
									$media_sizes = $max_display_width.'px';
									$media_css = '<style nonce="'.NONCE.'">.max-width-'.$max_display_width.', .max-width-'.$max_display_width.' img { max-width: '.$max_display_width.'px; }</style>';
									$media_class_name = ' class="max-width-'.$max_display_width.'"';
								}
								elseif($width >= 240)
								{
									$media_sizes .= '(max-width: '.$width.'px) 100vw, ';
								}
								
								if($sql_media_row['original_media'] == 'Yes')
								{
									$original_image_file_type = $media_output_data[1];
									
									$original_media = '<img'.$lazy_load_image_iframe.$image_fetch_priority.' src="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$file_name.'" alt="'.$alt_title.'" aria-label="'.$alt_title.'" width="'.$width.'" height="'.$height.'" class="max-width-height-display" />';
								}
							}
						}
					}
					
					if(!empty($max_display_width) && is_numeric($max_display_width) && $max_display_width > 0)
					{
						$media_sizes_output = trim($media_sizes, ', ');
					}
					else
					{
						$media_sizes_output = trim($media_sizes, ', ').$original_image_size;
					}
					
					if(!empty($media_output_srcset_avif))
					{
						$media_output_avif = '<source type="image/avif" srcset="'.trim($media_output_srcset_avif, ', ').'" 
						sizes="'.$media_sizes_output.'">';
					}
					
					if(!empty($media_output_srcset_webp))
					{
						$media_output_webp = '<source type="image/webp" srcset="'.trim($media_output_srcset_webp, ', ').'" 
						sizes="'.$media_sizes_output.'">';
					}
					
					if(!empty($media_output_srcset_other))
					{
						$media_output_other = '<source type="image/'.$original_image_file_type.'" srcset="'.trim($media_output_srcset_other, ', ').'" 
						sizes="'.$media_sizes_output.'">';
					}
					
					$display_media = $media_css.'<picture'.$media_class_name.'>'.$media_output_avif.$media_output_webp.$media_output_other.$original_media.'</picture>';
				}
				elseif($sql_media_rows[0]['media_type'] == 'Video')
				{
					$type = $sql_media_rows[0]['media_type'];
					$file_name = $sql_media_rows[0]["media_url"];
					$media_output_data = explode('.', $sql_media_rows[0]["media_url"] ?? '');
					if(!empty($custom_media_tag))
					{
						$alt_title = $custom_media_tag;
					}
					else
					{
						$alt_title = $sql_media_rows[0]["media_tag"];
					}
					$width = $sql_media_rows[0]["width"];
					$height = $sql_media_rows[0]["height"];
					$media_poster_id = $sql_media_rows[0]["video_poster"];
						
					$media_poster_url = '';
					if(!empty($media_poster_id))
					{
						$media_array = explode('~||~', $media_poster_id ?? '');
						
						if(!empty($media_array[0]))
						{
							$sql_media_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? LIMIT 1', [$media_array[0]]);
							
							if(!empty($sql_media_rows['media_url']) && $sql_media_rows['media_type'] == 'Image')
							{
								$original_media_id = $sql_media_rows["original_media_id"];
								
								$media_poster_url = INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$sql_media_rows['media_url'];
							}
						}
					}
					
					$video_poster = '';
					if(!empty($media_poster_url) && file_exists(INSTALLATION_ROOT.$media_poster_url))
					{
						$video_poster = ' poster="'.$domain.$media_poster_url.'"';
					}
					
					$display_media = '<video controls'.$lazy_load_video.$video_poster.'><source src="'.$domain.INSTALLATION_URL_PATH.'/sites/media/videos/'.$file_name.'" title="'.$alt_title.'" type="video/'.$media_output_data[1].'"><track kind="captions">Your browser does not support the video tag.</video>';
				}
				elseif($sql_media_rows[0]['media_type'] == 'File')
				{
					if(!empty($custom_media_tag))
					{
						$alt_title = $custom_media_tag;
					}
					else
					{
						$alt_title = $sql_media_rows[0]["media_tag"];
					}
					
					$file_name = $sql_media_rows[0]["media_url"];
					$media_output_data = explode('.', $sql_media_rows[0]["media_url"] ?? '');
					
					$display_media = '<object data="'.$domain.INSTALLATION_URL_PATH.'/sites/media/files/'.$file_name.'" type="application/'.$media_output_data[1].'" title="'.$alt_title.'" aria-label="'.$alt_title.'" width="100%" height="100%">Your browser does not support PDFs. You can <a href="'.$domain.INSTALLATION_URL_PATH.'/sites/media/files/'.$file_name.'">download the PDF here</a></object>';
				}
				elseif($sql_media_rows[0]['media_type'] == 'Video Embed')
				{
					$file_name = $sql_media_rows[0]["media_url"];
					if(!empty($custom_media_tag))
					{
						$alt_title = $custom_media_tag;
					}
					else
					{
						$alt_title = $sql_media_rows[0]["media_tag"];
					}
					$width = $sql_media_rows[0]["width"];
					$height = $sql_media_rows[0]["height"];
					
					$display_media = '<div class="video-embed"><iframe'.$lazy_load_image_iframe.' width="'.$width.'" height="'.$height.'" src="'.$file_name.'" title="'.$alt_title.'" aria-label="'.$alt_title.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>';
				}
			}
			
			return $display_media;
		}
	}
}