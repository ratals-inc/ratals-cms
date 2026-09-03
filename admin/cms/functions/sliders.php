<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/sliders.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/sliders.php');
}
else
{
	//Select slider data
	//$slider_id = 1;
	if(!function_exists('slider_id'))
	{
		function sliderId($slider_id)
		{
			global $site_id, $home_page, $domain, $url_structure, $sites_end_urls_with;
			
			$sql_sliders = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'sliders', 'WHERE `site_id` = ? AND `id` = ? AND `status` = ? LIMIT 1', [$site_id, $slider_id, '1']);
			
			$slider = array();
			
			if(!empty($sql_sliders))
			{
				///////////////Start slider settings///////////////
				$slider['slider_settings']['slides_in_view'] = $sql_sliders['slides_in_view']; //Max number of slides to show at once in viewport
				$slider['slider_settings']['slide_all_at_once'] = $sql_sliders['slide_all_at_once']; //New variable: "yes" to slide all at once, "no" to slide one at a time
				$slider['slider_settings']['min_slide_width'] = $sql_sliders['slide_minimum_width']; //Min slide width for responsive to display less slides in viewport (in pixels)
				$slider['slider_settings']['should_auto_slide'] = $sql_sliders['auto_slide_media']; //Auto slide slideshow (yes or no)
				$slider['slider_settings']['pause_time'] = $sql_sliders['pause_time']; //Slide pause time between each slide
				$slider['slider_settings']['slide_speed'] = $sql_sliders['slide_speed']; //Slide transition speed
				$slider['slider_settings']['slide_margin'] = $sql_sliders['slide_margin'] ?? 0; //Slider gap or marin for left and right gap
				$slider['slider_settings']['use_pagination'] = $sql_sliders['display_pagination']; //Controls whether to display pagination (thumbnails or bullets) - yes or no
				$slider['slider_settings']['pagination_align'] = $sql_sliders['pagination_alignment']; //CSS - Pagination left, center, right
				$slider['slider_settings']['pagination_over_image'] = $sql_sliders['pagination_over_image']; //CSS - Display pagination over slide image. yes or no
				$slider['slider_settings']['use_thumbnails'] = $sql_sliders['display_thumbnails']; //Controls whether to display thumbnails or bullets - "yes" for Thumbnails, "no" for bullets
				$slider['slider_settings']['thumbnail_width'] = $sql_sliders['pagination_thumbnail_width']; //Pager thumbnail width
				$slider['slider_settings']['pagination_margin'] = $sql_sliders['pagination_margin'] ?? 0; //Pager margin
				
				//Get correct count of slides to show based on device type. This is important for Cumulative Layout Shifts.
				$slides_in_viewport = getDeviceType($sql_sliders['slide_minimum_width'], $sql_sliders['slides_in_view']);
				
				//If mobile, only use bullets, no thumnails as screen is small.
				if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(iphone|ipod|android.*mobile|windows.*phone|blackberry|webos)/i', $_SERVER['HTTP_USER_AGENT']))
				{
					$sql_sliders['display_thumbnails'] = 'No';
					$sql_sliders['slider_settings']['use_thumbnails'] = 'No';
				}
				
				//Adjust bottom and padding if thumbnails vs bullets.
				$set_pagination_over_image = '';
				if($sql_sliders['display_pagination'] == 'Yes' && $sql_sliders['pagination_over_image'] == 'Yes')
				{
					$set_pagination_over_image = ' .slider_'.$slider_id.' .slider-pager { position: absolute; bottom: 0px; width: 100%; }';
				}
				elseif($sql_sliders['display_pagination'] == 'No')
				{
					$set_pagination_over_image = ' .slider_'.$slider_id.' .slider-pager { display: none; }';
				}
				
				//Align thumbnails.
				$set_pagination_center = '';
				if($sql_sliders['pagination_alignment'] == 'center')
				{
					$set_pagination_center = ' .slider_'.$slider_id.' .slider-pager { justify-content: center; }';
				}
				elseif($sql_sliders['pagination_alignment'] == 'right')
				{
					$set_pagination_center = ' .slider_'.$slider_id.' .slider-pager { justify-content: flex-end; }';
				}
				
				//Set pager gap / margin.
				$set_pagination_gap = '';
				if($sql_sliders['pagination_margin'] !== NULL)
				{
					$set_pagination_gap = ' .slider_'.$slider_id.' .slider-pager { gap: '.$sql_sliders['pagination_margin'].'px; }';
				}
				
				//Set pager with and height is auto.
				$set_thumbnail_width = '';
				if($sql_sliders['pagination_thumbnail_width'] !== NULL)
				{
					$set_thumbnail_width = ' .slider_'.$slider_id.' .thumbnail { width: '.$sql_sliders['pagination_thumbnail_width'].'px; }';
				}
				///////////////End slider settings///////////////
				
				$slider_items = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'slider_items', 'WHERE `site_id` = ? AND `sliders_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$site_id, $sql_sliders["id"], '1']);
				
				if(!empty($slider_items))
				{
					//Set slider css.
					echo "<style nonce=\"".NONCE."\">
					.slider_".$slider_id." .slider-holder { gap: ".($sql_sliders['slide_margin'] ?? 0)."px; } .slider_".$slider_id." .container { width: calc(100% / ".$slides_in_viewport."); }".$set_pagination_over_image.$set_pagination_center.$set_thumbnail_width.$set_pagination_gap."
					</style>";
					
					//Call slider function.
					echo "<script nonce=\"".NONCE."\">
					var videoIconSrc = `".$_SESSION['media_video_icon_no_picture_tag']."`;
					var FileIconSrc = `".$_SESSION['media_file_icon_no_picture_tag']."`;
					document.addEventListener('DOMContentLoaded', function () {
						slider('.slider_".$sql_sliders['id']."', '".strtolower($sql_sliders['auto_slide_media'])."', '".strtolower($sql_sliders['display_pagination'])."', '".strtolower($sql_sliders['display_thumbnails'])."', '".strtolower($sql_sliders['slide_all_at_once'])."', ".$slides_in_viewport.", ".$sql_sliders['slide_speed'].", ".$sql_sliders['pause_time'].", ".$sql_sliders['slide_margin'].", ".$sql_sliders['slide_minimum_width'].", ".$slides_in_viewport.", ".count($slider_items).", videoIconSrc, FileIconSrc);
					});
					</script>";
					
					foreach($slider_items as $sql_sliders_rows)
					{
						//Set Link on Slider Item
						$sql_sliders_rows["slider_url"] = '';
						$sql_sliders_rows["slider_url_link_type"] = '';
						
						if(is_numeric($sql_sliders_rows["links_to"]) && $sql_sliders_rows["links_to"] == '0')
						{
							$sql_sliders_rows["links_to"] = $home_page;
						}
						
						if(!empty($sql_sliders_rows["links_to"]))
						{
							$sql_slider_items_pages_url_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ? AND `site_id` = ? AND `url_status` = ? LIMIT 1', [$sql_sliders_rows["links_to"], $site_id, '1']);
						}
						
						if(!empty($sql_slider_items_pages_url_rows) && empty($sql_sliders_rows["custom_link"]) && empty($sql_slider_items_pages_url_rows["custom_link"]))
						{
							if(!empty($sql_slider_items_pages_url_rows["url_extension"]))
							{
								$silder_end_url_with = $sql_slider_items_pages_url_rows["url_extension"];
							}
							else
							{
								$silder_end_url_with = $sites_end_urls_with;
							}
							
							if($url_structure == 'Hierarchy')
							{
								$sql_sliders_rows["slider_url"] = $domain.INSTALLATION_URL_PATH."/".$sql_slider_items_pages_url_rows["hierarchy_url"].$silder_end_url_with;
								
								if(!empty($sql_sliders_rows["link_type"]))
								{
									$sql_sliders_rows["slider_url_link_type"] = $sql_sliders_rows["link_type"];
								}
								else
								{
									$sql_sliders_rows["slider_url_link_type"] = $sql_slider_items_pages_url_rows["link_type"];
								}
							}
							elseif($url_structure == 'Flat')
							{
								$sql_sliders_rows["slider_url"] = $domain.INSTALLATION_URL_PATH."/".$sql_slider_items_pages_url_rows["flat_url"].$silder_end_url_with;
								
								if(!empty($sql_sliders_rows["link_type"]))
								{
									$sql_sliders_rows["slider_url_link_type"] = $sql_sliders_rows["link_type"];
								}
								else
								{
									$sql_sliders_rows["slider_url_link_type"] = $sql_slider_items_pages_url_rows["link_type"];
								}
							} 
							
							if($home_page == $sql_slider_items_pages_url_rows["id"])
							{
								$sql_sliders_rows["slider_url"] = $domain.INSTALLATION_URL_PATH."/";
								
								if(!empty($sql_sliders_rows["link_type"]))
								{
									$sql_sliders_rows["slider_url_link_type"] = $sql_sliders_rows["link_type"];
								}
								else
								{
									$sql_sliders_rows["slider_url_link_type"] = $sql_slider_items_pages_url_rows["link_type"];
								}
							} 
						}
						elseif(!empty($sql_sliders_rows["custom_link"]))
						{
							$sql_sliders_rows["slider_url"] = $sql_sliders_rows["custom_link"];
							
							if(!empty($sql_sliders_rows["link_type"]))
							{
								$sql_sliders_rows["slider_url_link_type"] = $sql_sliders_rows["link_type"];
							}
							else
							{
								$sql_sliders_rows["slider_url_link_type"] = $sql_slider_items_pages_url_rows["link_type"];
							}
						}
						elseif(!empty($sql_slider_items_pages_url_rows["custom_link"]))
						{
							$sql_sliders_rows["slider_url"] = $sql_slider_items_pages_url_rows["custom_link"];
							
							if(!empty($sql_sliders_rows["link_type"]))
							{
								$sql_sliders_rows["slider_url_link_type"] = $sql_sliders_rows["link_type"];
							}
							else
							{
								$sql_sliders_rows["slider_url_link_type"] = $sql_slider_items_pages_url_rows["link_type"];
							}
						}
						
						//Set Desktop Slider Media
						$sql_sliders_rows["desktop_media_type"] = '';
						$sql_sliders_rows["desktop_original_media_id"] = 0;
						$sql_sliders_rows["desktop_media_url"] = '';
						$sql_sliders_rows["desktop_media_width"] = 0;
						$sql_sliders_rows["desktop_media_height"] = 0;
						$sql_sliders_rows["desktop_media_tag"] = '';
						if(!empty($sql_sliders_rows["desktop_media"]))
						{
							$media_array = explode('~||~', $sql_sliders_rows["desktop_media"]);
							
							$sql_slider_items_desktop_media_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? LIMIT 1', [$media_array[0]]);
							
							if(!empty($sql_slider_items_desktop_media_rows)) 
							{
								$sql_sliders_rows["desktop_media_type"] = $sql_slider_items_desktop_media_rows["media_type"];
								$sql_sliders_rows["desktop_original_media_id"] = $sql_slider_items_desktop_media_rows["original_media_id"];
								$sql_sliders_rows["desktop_media_url"] = $sql_slider_items_desktop_media_rows["media_url"];
								$sql_sliders_rows["desktop_media_width"] = $sql_slider_items_desktop_media_rows["width"];
								$sql_sliders_rows["desktop_media_height"] = $sql_slider_items_desktop_media_rows["height"];
								
								if(!empty($media_array[1])) 
								{
									$sql_sliders_rows["desktop_media_tag"] = $media_array[1];
								}
								else
								{
									$sql_sliders_rows["desktop_media_tag"] = $sql_slider_items_desktop_media_rows["media_tag"];
								}
							}
						}
						
						//Set Tablet Slider Media
						$sql_sliders_rows["tablet_media_type"] = '';
						$sql_sliders_rows["tablet_original_media_id"] = 0;
						$sql_sliders_rows["tablet_media_url"] = '';
						$sql_sliders_rows["tablet_media_width"] = 0;
						$sql_sliders_rows["tablet_media_height"] = 0;
						$sql_sliders_rows["tablet_media_tag"] = '';
						if(!empty($sql_sliders_rows["tablet_media"]))
						{
							$media_array = explode('~||~', $sql_sliders_rows["tablet_media"]);
							
							$sql_slider_items_tablet_media_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? LIMIT 1', [$media_array[0]]);
							
							if(!empty($sql_slider_items_tablet_media_rows)) 
							{
								$sql_sliders_rows["tablet_media_type"] = $sql_slider_items_tablet_media_rows["media_type"];
								$sql_sliders_rows["tablet_original_media_id"] = $sql_slider_items_tablet_media_rows["original_media_id"];
								$sql_sliders_rows["tablet_media_url"] = $sql_slider_items_tablet_media_rows["media_url"];
								$sql_sliders_rows["tablet_media_width"] = $sql_slider_items_tablet_media_rows["width"];
								$sql_sliders_rows["tablet_media_height"] = $sql_slider_items_tablet_media_rows["height"];
								
								if(!empty($media_array[1])) 
								{
									$sql_sliders_rows["tablet_media_tag"] = $media_array[1];
								}
								else
								{
									$sql_sliders_rows["tablet_media_tag"] = $sql_slider_items_tablet_media_rows["media_tag"];
								}
							}
						}
						
						//Set Mobile Slider Media
						$sql_sliders_rows["mobile_media_type"] = '';
						$sql_sliders_rows["mobile_original_media_id"] = 0;
						$sql_sliders_rows["mobile_media_url"] = '';
						$sql_sliders_rows["mobile_media_width"] = 0;
						$sql_sliders_rows["mobile_media_height"] = 0;
						$sql_sliders_rows["mobile_media_tag"] = '';
						if(!empty($sql_sliders_rows["mobile_media"]))
						{
							$media_array = explode('~||~', $sql_sliders_rows["mobile_media"]);
							
							$sql_slider_items_mobile_media_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? LIMIT 1', [$media_array[0]]);
							
							if(!empty($sql_slider_items_mobile_media_rows)) 
							{ 
								$sql_sliders_rows["mobile_media_type"] = $sql_slider_items_mobile_media_rows["media_type"];
								$sql_sliders_rows["mobile_original_media_id"] = $sql_slider_items_mobile_media_rows["original_media_id"];
								$sql_sliders_rows["mobile_media_url"] = $sql_slider_items_mobile_media_rows["media_url"];
								$sql_sliders_rows["mobile_media_width"] = $sql_slider_items_mobile_media_rows["width"];
								$sql_sliders_rows["mobile_media_height"] = $sql_slider_items_mobile_media_rows["height"];
								
								if(!empty($media_array[1])) 
								{
									$sql_sliders_rows["mobile_media_tag"] = $media_array[1];
								}
								else
								{
									$sql_sliders_rows["mobile_media_tag"] = $sql_slider_items_mobile_media_rows["media_tag"];
								}
							}
						}
						
						//If video, see if video poster is set.
						$sql_sliders_rows["video_poster"] = '';
						if(!empty($sql_sliders_rows["desktop_media"]) && $sql_sliders_rows["desktop_media_type"] == 'Video' && !empty($sql_slider_items_desktop_media_rows["video_poster"]))
						{
							$media_array = explode('~||~', $sql_slider_items_desktop_media_rows["video_poster"]);
							
							$sql_slider_items_desktop_media_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? LIMIT 1', [$media_array[0]]);
							
							if(!empty($sql_slider_items_desktop_media_row))
							{
								$sql_sliders_rows["video_poster"] = $sql_slider_items_desktop_media_row["media_url"];
							}
						}
						elseif(!empty($sql_sliders_rows["tablet_media"]) && $sql_sliders_rows["tablet_media_type"] == 'Video' && !empty($sql_slider_items_tablet_media_rows["video_poster"]))
						{
							$media_array = explode('~||~', $sql_slider_items_tablet_media_row["video_poster"]);
							
							$sql_slider_items_tablet_media_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? LIMIT 1', [$media_array[0]]);
							
							if(!empty($sql_slider_items_desktop_media_row))
							{
								$sql_sliders_rows["video_poster"] = $sql_slider_items_tablet_media_row["media_url"];
							}
						}
						elseif(!empty($sql_sliders_rows["mobile_media"]) && $sql_sliders_rows["mobile_media_type"] == 'Video')
						{
							$media_array = explode('~||~', $sql_sliders_rows["mobile_media"]);
							
							$sql_slider_items_mobile_media_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ? LIMIT 1', [$media_array[0]]);
							
							if(!empty($sql_slider_items_mobile_media_row))
							{
								$sql_sliders_rows["video_poster"] = $sql_slider_items_mobile_media_row["media_url"];
							}
						}
						
						$slider[] = $sql_sliders_rows + array('lazy_load_media' => $sql_sliders['lazy_load_media'], 'fetch_priority' => $sql_sliders['fetch_priority']);
					}
				}
			}
			
			return $slider;
		}
	}
	//$slider2 = sliderId(2);
	//echo '<pre>'; print_r($slider2); echo '</pre>';
}