<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('slider.php', $data_array['active_template_includes'])) { ?>
	<?php 
	include_once(INSTALLATION_ROOT.'/sites/slider-js.php');
	$slider_id = [SLIDER_ID];
    $slider = sliderId($slider_id);
	$slider_pagers = '';
	$pager_counter = 0;
    if(!empty($slider)) 
    {
	?>
        <!-- Start Slider -->
        <div class="slider_<?php echo $slider_id; ?> slider-wrapper container-width">
            <div class="slider">
                <div class="prev">&#8249;</div>
                <div class="next">&#8250;</div>
                <div class="slider-holder">
                    <?php 
                    foreach($slider as $key => $slider_items) 
                    {
						if($key != 'slider_settings')
						{
							$slider_images = '';
							$slider_pager = '';
							
							$lazy_load_image_iframe = '';
							$lazy_load_video = '';
							
							if($slider_items["lazy_load_media"] == 'Yes')
							{
								$lazy_load_image_iframe = ' loading="lazy" decoding="async"';
								$lazy_load_video = ' preload="none"';
							}
							
							$fetch_priority = '';
							
							if($slider_items["fetch_priority"] == 'Yes')
							{
								$fetch_priority = ' fetchpriority="high"';
							}
							
							if(!empty($slider_items["mobile_media_url"]))
							{
								$mobile_media_urls = '';
								$mobile_media_data = explode('.', $slider_items["mobile_media_url"]);
							  
								if($slider_items["mobile_media_type"] == 'Image')
								{
									$original_media_id = $slider_items["mobile_original_media_id"];
									
									if(file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$mobile_media_data[0].'.avif'))
									{
										$mobile_media_urls .= '<source type="image/avif" media="(max-width: '.$slider_items["mobile_media_width"].'px)" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$mobile_media_data[0].'.avif">';
									}
									
									if(file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$mobile_media_data[0].'.webp'))
									{
										$mobile_media_urls .= '<source type="image/webp" media="(max-width: '.$slider_items["mobile_media_width"].'px)" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$mobile_media_data[0].'.webp">';
									}
									
									$mobile_media_urls .= '<source type="image/'.$mobile_media_data[1].'" media="(max-width: '.$slider_items["mobile_media_width"].'px)" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$slider_items["mobile_media_url"].'">';
								}
								elseif($slider_items["mobile_media_type"] == 'Video')
								{
									$mobile_media_urls = '<source type="video/'.$mobile_media_data[1].'" media="(max-width: '.$slider_items["mobile_media_width"].'px)" src="'.$domain.INSTALLATION_URL_PATH.'/sites/media/videos/'.$slider_items["mobile_media_url"].'">';
								}
								elseif($slider_items["mobile_media_type"] == 'File')
								{
									$mobile_media_urls = '<object media="(max-width: '.$slider_items["mobile_media_width"].'px)" data="'.$domain.INSTALLATION_URL_PATH.'/sites/media/files/'.$slider_items["mobile_media_url"].'" type="application/'.$mobile_media_data[1].'" width="100%" height="100%"></object>';
								}
								elseif($slider_items["mobile_media_type"] == 'Video Embed')
								{
									$mobile_media_urls = '<div class="video-embed"><iframe'.$lazy_load_image_iframe.' width="'.$slider_items["mobile_media_width"].'" height="'.$slider_items["mobile_media_height"].'" src="'.$slider_items["mobile_media_url"].'" title="'.$slider_items["mobile_media_tag"].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>';
								}
								
								$slider_images .= $mobile_media_urls;
							}
							
							if(!empty($slider_items["tablet_media_url"]))
							{
								$tablet_media_urls = '';
								$tablet_media_data = explode('.', $slider_items["tablet_media_url"]);
								
								if($slider_items["tablet_media_type"] == 'Image')
								{
									$original_media_id = $slider_items["tablet_original_media_id"];
									
									if(file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$tablet_media_data[0].'.avif'))
									{
										$tablet_media_urls .= '<source type="image/avif" media="(max-width: '.$slider_items["tablet_media_width"].'px)" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$tablet_media_data[0].'.avif">';
									}
									
									if(file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$tablet_media_data[0].'.webp'))
									{
										$tablet_media_urls .= '<source type="image/webp" media="(max-width: '.$slider_items["tablet_media_width"].'px)" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$tablet_media_data[0].'.webp">';
									}
									
									$tablet_media_urls .= '<source type="image/'.$tablet_media_data[1].'" media="(max-width: '.$slider_items["tablet_media_width"].'px)" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$slider_items["tablet_media_url"].'">';
								}
								elseif($slider_items["tablet_media_type"] == 'Video')
								{
									$tablet_media_urls = '<source type="video/'.$tablet_media_data[1].'" media="(max-width: '.$slider_items["tablet_media_width"].'px)" src="'.$domain.INSTALLATION_URL_PATH.'/sites/media/videos/'.$slider_items["tablet_media_url"].'">';
								}
								elseif($slider_items["tablet_media_type"] == 'File')
								{
									$tablet_media_urls = '<object media="(max-width: '.$slider_items["tablet_media_width"].'px)" data="'.$domain.INSTALLATION_URL_PATH.'/sites/media/files/'.$slider_items["tablet_media_url"].'" type="application/'.$tablet_media_data[1].'" width="100%" height="100%"></object>';
								}
								elseif($slider_items["tablet_media_type"] == 'Video Embed')
								{
									$tablet_media_urls = '<div class="video-embed"><iframe'.$lazy_load_image_iframe.' width="'.$slider_items["tablet_media_width"].'" height="'.$slider_items["tablet_media_height"].'" src="'.$slider_items["tablet_media_url"].'" title="'.$slider_items["tablet_media_tag"].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>';
								}
								
								$slider_images .= $tablet_media_urls;
							}
							
							if(!empty($slider_items["desktop_media_url"]))
							{
								$desktop_media_urls = '';
								$desktop_media_data = explode('.', $slider_items["desktop_media_url"]);
								
								if($slider_items["desktop_media_type"] == 'Image')
								{
									$original_media_id = $slider_items["desktop_original_media_id"];
									
									if(file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$desktop_media_data[0].'.avif'))
									{
										$desktop_media_urls .= '<source type="image/avif" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$desktop_media_data[0].'.avif">';
									}
									
									if(file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$desktop_media_data[0].'.webp'))
									{
										$desktop_media_urls .= '<source type="image/webp" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$desktop_media_data[0].'.webp">';
									}
									
									$desktop_media_urls .= '<source type="image/'.$desktop_media_data[1].'" srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$slider_items["desktop_media_url"].'"><img'.$lazy_load_image_iframe.$fetch_priority.' srcset="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$slider_items["desktop_media_url"].'" alt="'.$slider_items["desktop_media_tag"].'" width="'.$slider_items["desktop_media_width"].'" height="'.$slider_items["desktop_media_height"].'" class="object-fit-scale-down">';
								}
								elseif($slider_items["desktop_media_type"] == 'Video')
								{
									$desktop_media_urls = '<source type="video/'.$desktop_media_data[1].'" src="'.$domain.INSTALLATION_URL_PATH.'/sites/media/videos/'.$slider_items["desktop_media_url"].'">';
								}
								elseif($slider_items["desktop_media_type"] == 'File')
								{
									$desktop_media_urls = '<object data="'.$domain.INSTALLATION_URL_PATH.'/sites/media/files/'.$slider_items["desktop_media_url"].'" type="application/'.$desktop_media_data[1].'" width="100%" height="100%"></object>';
								}
								elseif($slider_items["desktop_media_type"] == 'Video Embed')
								{
									$desktop_media_urls = '<div class="video-embed"><iframe'.$lazy_load_image_iframe.' width="'.$slider_items["desktop_media_width"].'" height="'.$slider_items["desktop_media_height"].'" src="'.$slider_items["desktop_media_url"].'" title="'.$slider_items["desktop_media_tag"].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>';
								}
								
								$slider_images .= $desktop_media_urls;
							}
							
							$link_type = ''; 
							if(!empty($slider_items['slider_url_link_type'])) { $link_type = ' rel="'.$slider_items['slider_url_link_type'].'"'; }
							
							$slider_link_open = '';
							$slider_link_close = '';
							if(!empty($slider_items["slider_url"]))
							{
								$slider_link_open = '<a href="'.$slider_items["slider_url"].'"'.$link_type.'>';
								$slider_link_close = '</a>';
							}
							
							//Add pagers to slider.
							if($slider['slider_settings']['use_pagination'] == 'Yes')
							{
								if($slider_items["desktop_media_type"] == 'Image')
								{
									if($slider['slider_settings']['use_thumbnails'] == 'Yes')
									{
										$slider_pager .= '<picture class="thumbnail" data-index="'.$pager_counter.'">'.$slider_images.'</picture>';
									}
									else
									{
										$slider_pager .= '<span class="pager" data-index="'.$pager_counter.'"></span>';
									}
									
									$slider_images = '<picture>'.$slider_images.'</picture>';
								}
								elseif($slider_items["desktop_media_type"] == 'Video')
								{
									$original_media_id = $slider_items["original_media_id"];
									
									$video_poster = '';
									if(!empty($slider_items["video_poster"]) && file_exists(INSTALLATION_ROOT.'/sites/media/images/'.$original_media_id.'/'.$slider_items["video_poster"]))
									{
										$video_poster = ' poster="'.$domain.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$slider_items["video_poster"].'"';
									}
									
									$slider_images = '<video controls'.$lazy_load_video.$video_poster.' width="100%" height="100%" class="position-absolute-height-width-display">'.$slider_images.'</video>';
								}
							}
							
							echo '<div class="container">
							'.$slider_link_open.'
							  '.$slider_images.'
							'.$slider_link_close.'
							</div>'; 
							
							$slider_pagers .= $slider_pager;
							
							$pager_counter ++;
						}
                    } 
                    ?>
                </div>
            </div>
            <div class="slider-pager">
            <?php echo $slider_pagers; ?>
            </div>
        </div>
        <!-- End Slider -->
    <?php } ?>
<?php } ?>
