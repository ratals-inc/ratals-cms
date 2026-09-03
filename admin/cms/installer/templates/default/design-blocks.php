<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('design-blocks.php', $data_array['active_template_includes'])) { ?>
<?php 
$slider_unqiue_counter = 10000;
foreach($data_array['design_blocks'] as $group) 
{
	if($group['design_blocks_type'] == 'Code (html/css)')
	{
		echo $group['design_blocks_code'];
	}
	elseif($group['design_blocks_type'] == 'Include File')
	{
		if(!empty($group['design_blocks_load_template_include_file']) && file_exists(INSTALLATION_ROOT.'/sites/'.$site_id.'/templates/'.$active_template_path.'/'.$group['design_blocks_load_template_include_file']))
		{
			include(INSTALLATION_ROOT.'/sites/'.$site_id.'/templates/'.$active_template_path.'/'.$group['design_blocks_load_template_include_file']);
		}
		else
		{
			echo 'The template file for the sub item doesn\'t exist: /sites/'.$site_id.'/templates/'.$active_template_path.'/'.$group['design_blocks_load_template_include_file'];
		}
	}
	elseif($group['design_blocks_type'] == 'Block Items')
	{
		$slider_unqiue_counter ++;
		//Open - display as slider
		if($group['display_as_slider'] == 'Yes')
		{
			include_once(INSTALLATION_ROOT.'/sites/slider-js.php');
			
			//Get correct count of slides to show based on device type. This is important for Cumulative Layout Shifts.
			$slides_in_viewport = getDeviceType($group['slide_minimum_width'], $group['slides_in_view']);
			$columns_to_display = $slides_in_viewport;
			$calculate_columns_based_on_device = 100 / $columns_to_display;
			
			//If mobile, only use bullets, no thumnails as screen is small.
			if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(iphone|ipod|android.*mobile|windows.*phone|blackberry|webos)/i', $_SERVER['HTTP_USER_AGENT']))
			{
				$group['display_thumbnails'] = 'No';
			}
			
			//Adjust bottom and padding if thumbnails vs bullets.
			$set_pagination_over_image = '';
			if($group['display_pagination'] == 'Yes' && $group['pagination_over_image'] == 'Yes')
			{
				$set_pagination_over_image = '.slider_'.$slider_unqiue_counter.' .slider-pager { bottom: -40px; }';
			}
			elseif($group['display_pagination'] == 'No')
			{
				$set_pagination_over_image = '.slider_'.$slider_unqiue_counter.' .slider-pager { display: none; }';
			}
			
			//Pagination alignment.
			$set_pagination_alignment = '';
			if($group['pagination_alignment'] == 'center')
			{
				$set_pagination_alignment = '.slider_'.$slider_unqiue_counter.' .slider-pager { justify-content: center; }';
			}
			elseif($group['pagination_alignment'] == 'right')
			{
				$set_pagination_alignment = '.slider_'.$slider_unqiue_counter.' .slider-pager { justify-content: flex-end; }';
			}
			
			$sub_items_grid_gap = '0';
			if(is_numeric($group['slide_margin'])) { $sub_items_grid_gap = $group['slide_margin']; }
			
			echo "
			<style nonce=\"".NONCE."\">
			.design-blocks-outter-wrapper-".$slider_unqiue_counter." { ".$group['outter_css_box_styles']." }
			.design-blocks-inner-wrapper-".$slider_unqiue_counter." { ".$group['inner_css_box_styles']." }
			.slider_".$slider_unqiue_counter.".design-blocks ul.flex-".$columns_to_display." { display: flex; align-items: flex-start; gap: ".$sub_items_grid_gap."px; padding: 0; margin: 0; }
			.slider_".$slider_unqiue_counter.".design-blocks ul.flex-".$columns_to_display." > li { list-style-type: none; background-color: transparent; }
			.slider_".$slider_unqiue_counter.".design-blocks ul.flex-".$columns_to_display." > li .img { border-radius: var(--border-radius-1); }
			.slider_".$slider_unqiue_counter." .container { width: ".$calculate_columns_based_on_device."%; }
			.slider_".$slider_unqiue_counter.".design-blocks .img a { background-color: #f3f3f3; border-radius: 10px; }
			".$set_pagination_over_image."
			".$set_pagination_alignment."
			.slider_".$slider_unqiue_counter." .thumbnail { width: ".$group['pagination_thumbnail_width']."px; } 
			.slider_".$slider_unqiue_counter." .slider-pager { gap: ".$group['pagination_margin']."px; }
			</style>";
			
			echo "
			<script nonce=\"".NONCE."\">
			var videoIconSrc = `".$_SESSION['media_video_icon_no_picture_tag']."`;
			var FileIconSrc = `".$_SESSION['media_file_icon_no_picture_tag']."`;
			document.addEventListener('DOMContentLoaded', function () {
			slider('.slider_".$slider_unqiue_counter."', '".strtolower($group['auto_slide_media'])."', '".strtolower($group['display_pagination'])."', '".strtolower($group['display_thumbnails'])."', '".strtolower($group['slide_all_at_once'])."', ".$columns_to_display.", ".$group['slide_speed'].", ".$group['pause_time'].", ".$sub_items_grid_gap.", 200, ".$columns_to_display.", ".count($group['group_rows']).", videoIconSrc, FileIconSrc);
			});
			</script>";
			
			echo '<div class="grid-text '.$data_array['flat_url'].' design-blocks-outter-wrapper-'.$slider_unqiue_counter.' slider-wrapper">';
				if(!empty($group['title']) || !empty($group['content']))
				{
					echo '<div class="top-text container-width">
						<h2 class="title">'.$group['title'].'</h2>
						<div class="sub-text">'.$group['content'].'</div>
					</div>';
				}
				echo '<div class="slider_'.$slider_unqiue_counter.' design-blocks container-width design-blocks-inner-wrapper-'.$slider_unqiue_counter.'">';
					echo '<div class="slider">
						<div class="prev">&#8249;</div>
						<div class="next">&#8250;</div>
						<ul class="slider-holder flex-'.$columns_to_display.'">';
		}
		//Open - display as css grid.
		else
		{
			$slider_unqiue_counter ++;
			
			$columns_to_display = $group['columns'];

			$sub_items_grid_gap = '';
			if(is_numeric($group['gap_between_items'])) { $sub_items_grid_gap = '.design-blocks-inner-wrapper-'.$slider_unqiue_counter.' ul.grid-'.$columns_to_display.' { --grid-gap: '.$group['gap_between_items'].'px; }'; }
			
			$display_content_under_block_items = '';
			if($group['display_content_under_block_items'] == 'No') { $display_content_under_block_items = '.design-blocks-inner-wrapper-'.$slider_unqiue_counter.' li.container { grid-template-rows: auto; grid-row: auto; }'; }
			
			echo "<style nonce=\"".NONCE."\">
			.design-blocks-outter-wrapper-".$slider_unqiue_counter." { ".$group['outter_css_box_styles']." }
			.design-blocks-inner-wrapper-".$slider_unqiue_counter." { ".$group['inner_css_box_styles']." }
			".$sub_items_grid_gap."
			".$display_content_under_block_items."
			</style>";
			
			echo '<div class="grid-text '.$data_array['flat_url'].' design-blocks-outter-wrapper-'.$slider_unqiue_counter.'">';
				if(!empty($group['title']) || !empty($group['content']))
				{
					echo '<div class="top-text container-width">
							<h2 class="title">'.$group['title'].'</h2>
							<div class="sub-text">'.$group['content'].'</div>
						  </div>';
				}
				echo '<div class="design-blocks container-width design-blocks-inner-wrapper-'.$slider_unqiue_counter.'">
					<ul class="grid-'.$columns_to_display.'">';
		}
		
		$pager_counter = 0;
		$slider_pager = '';
		
		//Loop through all items in group/
		foreach($group['group_rows'] as $group_items) 
		{
			$sub_item_media = '';
			if(!empty($group_items['media_html_code']))
			{
				$sub_item_media = $group_items['media_html_code'];
			}
			
			if($group_items['type'] == 'products' || $group_items['type'] == 'inventory')
			{
				$save_amount = '';
				$display_price = '';
				if($group_items["sale_price_active"] == 'Yes')
				{
					$save_amount = '
					<span class="old-price">Was: <span class="was-price">'.currencyFormatWithSymbol($group_items["price"]).'</span> / 
					<span class="save">Save: '.currencyFormatWithSymbol($group_items["save_amount"]).'</span>
					</span>'; 
					$display_price = '<span class="price">'.currencyFormatWithSymbol($group_items["sale_price"]).'</span>';
				} 
				elseif($group_items["price"] > 0)
				{
					$display_price = '<span class="price">'.currencyFormatWithSymbol($group_items["price"]).'</span>';
				}
				$review_score = '';
				if($group_items['review_score'] > 0)
				{
					$review_score = '<div class="review-score">'.getReviewStars($group_items['review_score']).' <span class="score">('.$group_items['review_score'].' out of 5)</span></div>';
				}
				$day_or_days = '';
				if($group_items['inventory_ships_within'] == 1)
				{
					$day_or_days = '<div class="ships"><i class="ships-truck"><svg viewBox="0 0 512 512"><path d="M5 131A47 47 0 0 1 52 84H334A47 47 0 0 1 381 131V178H413A47 47 0 0 1 450 195L497 253A47 47 0 0 1 507 283V350A47 47 0 0 1 460 397H444A63 63 0 1 1 319 397H162A63 63 0 1 1 37 394 47 47 0 0 1 5 350ZM46 364A63 63 0 0 1 154 366H327A63 63 0 0 1 350 343V131A16 16 0 0 0 334 115H52A16 16 0 0 0 36 131V350A16 16 0 0 0 46 364M381 334A63 63 0 0 1 436 366H460A16 16 0 0 0 476 350V283A16 16 0 0 0 472 273L426 215A16 16 0 0 0 413 209H381ZM99 366A31 31 0 1 0 99 428 31 31 0 0 0 99 366M381 366A31 31 0 1 0 381 428 31 31 0 0 0 381 366"></path></svg></i>Ships Within '.$group_items['inventory_ships_within'].' Day</div>';
				}
				elseif($group_items['inventory_ships_within'] > 1)
				{
					$day_or_days = '<div class="ships"><i class="ships-truck"><svg viewBox="0 0 512 512"><path d="M5 131A47 47 0 0 1 52 84H334A47 47 0 0 1 381 131V178H413A47 47 0 0 1 450 195L497 253A47 47 0 0 1 507 283V350A47 47 0 0 1 460 397H444A63 63 0 1 1 319 397H162A63 63 0 1 1 37 394 47 47 0 0 1 5 350ZM46 364A63 63 0 0 1 154 366H327A63 63 0 0 1 350 343V131A16 16 0 0 0 334 115H52A16 16 0 0 0 36 131V350A16 16 0 0 0 46 364M381 334A63 63 0 0 1 436 366H460A16 16 0 0 0 476 350V283A16 16 0 0 0 472 273L426 215A16 16 0 0 0 413 209H381ZM99 366A31 31 0 1 0 99 428 31 31 0 0 0 99 366M381 366A31 31 0 1 0 381 428 31 31 0 0 0 381 366"></path></svg></i>Ships Within '.$group_items['inventory_ships_within'].' Days</div>';
				}
				
				$link_type = ''; 
				if(!empty($group_items['link_type'])) { $link_type = ' rel="'.$group_items['link_type'].'"'; }
				
				echo '
				<!-- Start Product -->
				<li class="container">
					
						<div class="img"><a href="'.$group_items["url"].'"'.$link_type.'>'.$sub_item_media.'</a></div>';
						if($group['display_content_under_block_items'] == 'Yes')
						{
							echo '<div class="text-product">
								<h2 class="title"><a href="'.$group_items["url"].'"'.$link_type.'>'.$group_items['meta_title'].'</a></h2>
								<div class="description">'.$group_items['meta_description'].'</div>
								'.$review_score.'
								<div class="prices">'.$display_price.$save_amount.'</div>
								'.$day_or_days.'
							</div>';
						}
					echo'
				</li>
				<!-- End Product -->'; 
			}
			elseif($group_items['type'] == 'sub_products')
			{
				$save_amount = '';
				$display_price = '';
				if($group_items["sale_price_active"] == 'Yes')
				{
					$save_amount = '
					<span class="old-price">Was: <span class="was-price">'.currencyFormatWithSymbol($group_items["price"]).'</span> / 
					<span class="save">Save: '.currencyFormatWithSymbol($group_items["save_amount"]).'</span>
					</span>'; 
					$display_price = '<div class="font-size-12px">Starting at</div><span class="price">'.currencyFormatWithSymbol($group_items["sale_price"]).'</span>';
				} 
				elseif($group_items["price"] > 0)
				{
					$display_price = '<div class="font-size-12px">Starting at</div><span class="price">'.currencyFormatWithSymbol($group_items["price"]).'</span>';
				}
				$review_score = '';
				if($group_items['review_score'] > 0)
				{
					$review_score = '<div class="review-score">'.getReviewStars($group_items['review_score']).' <span class="score">('.$group_items['review_score'].' out of 5)</span></div>';
				}
				$day_or_days = '';
				if($group_items['inventory_ships_within'] == 1)
				{
					$day_or_days = '<div class="ships"><i class="ships-truck"><svg viewBox="0 0 512 512"><path d="M5 131A47 47 0 0 1 52 84H334A47 47 0 0 1 381 131V178H413A47 47 0 0 1 450 195L497 253A47 47 0 0 1 507 283V350A47 47 0 0 1 460 397H444A63 63 0 1 1 319 397H162A63 63 0 1 1 37 394 47 47 0 0 1 5 350ZM46 364A63 63 0 0 1 154 366H327A63 63 0 0 1 350 343V131A16 16 0 0 0 334 115H52A16 16 0 0 0 36 131V350A16 16 0 0 0 46 364M381 334A63 63 0 0 1 436 366H460A16 16 0 0 0 476 350V283A16 16 0 0 0 472 273L426 215A16 16 0 0 0 413 209H381ZM99 366A31 31 0 1 0 99 428 31 31 0 0 0 99 366M381 366A31 31 0 1 0 381 428 31 31 0 0 0 381 366"></path></svg></i>Ships Within '.$group_items['inventory_ships_within'].' Day</div>';
				}
				elseif($group_items['inventory_ships_within'] > 1)
				{
					$day_or_days = '<div class="ships"><i class="ships-truck"><svg viewBox="0 0 512 512"><path d="M5 131A47 47 0 0 1 52 84H334A47 47 0 0 1 381 131V178H413A47 47 0 0 1 450 195L497 253A47 47 0 0 1 507 283V350A47 47 0 0 1 460 397H444A63 63 0 1 1 319 397H162A63 63 0 1 1 37 394 47 47 0 0 1 5 350ZM46 364A63 63 0 0 1 154 366H327A63 63 0 0 1 350 343V131A16 16 0 0 0 334 115H52A16 16 0 0 0 36 131V350A16 16 0 0 0 46 364M381 334A63 63 0 0 1 436 366H460A16 16 0 0 0 476 350V283A16 16 0 0 0 472 273L426 215A16 16 0 0 0 413 209H381ZM99 366A31 31 0 1 0 99 428 31 31 0 0 0 99 366M381 366A31 31 0 1 0 381 428 31 31 0 0 0 381 366"></path></svg></i>Ships Within '.$group_items['inventory_ships_within'].' Days</div>';
				}
				
				$link_type = ''; 
				if(!empty($group_items['link_type'])) { $link_type = ' rel="'.$group_items['link_type'].'"'; }
				
				echo '
				<!-- Start Product -->
				<li class="container">
					
					  <div class="img"><a href="'.$group_items["url"].'"'.$link_type.'>'.$sub_item_media.'</a></div>';
					  if($group['display_content_under_block_items'] == 'Yes')
					  {
						  echo '<div class="text-product">
							<h2 class="title"><a href="'.$group_items["url"].'"'.$link_type.'>'.$group_items['meta_title'].'</a></h2>
							<div class="description">'.$group_items['meta_description'].'</div>
							'.$review_score.'
							<div class="prices">'.$display_price.$save_amount.'</div>
							'.$day_or_days.'
						  </div>';
					  }
					echo '
				</li>
				<!-- End Product -->'; 
			}
			elseif($group_items['type'] == 'lead_form')
			{
				$review_score = '';
				if($group_items['review_score'] > 0)
				{
					$review_score = '<div class="review-score">'.getReviewStars($group_items['review_score']).' <span class="score">('.$group_items['review_score'].' out of 5)</span></div>';
				}
				$day_or_days = '';
				if($group_items['inventory_ships_within'] == 1)
				{
					$day_or_days = '<div class="ships"><i class="ships-truck"><svg viewBox="0 0 512 512"><path d="M5 131A47 47 0 0 1 52 84H334A47 47 0 0 1 381 131V178H413A47 47 0 0 1 450 195L497 253A47 47 0 0 1 507 283V350A47 47 0 0 1 460 397H444A63 63 0 1 1 319 397H162A63 63 0 1 1 37 394 47 47 0 0 1 5 350ZM46 364A63 63 0 0 1 154 366H327A63 63 0 0 1 350 343V131A16 16 0 0 0 334 115H52A16 16 0 0 0 36 131V350A16 16 0 0 0 46 364M381 334A63 63 0 0 1 436 366H460A16 16 0 0 0 476 350V283A16 16 0 0 0 472 273L426 215A16 16 0 0 0 413 209H381ZM99 366A31 31 0 1 0 99 428 31 31 0 0 0 99 366M381 366A31 31 0 1 0 381 428 31 31 0 0 0 381 366"></path></svg></i>Ships Within '.$group_items['inventory_ships_within'].' Day</div>';
				}
				elseif($group_items['inventory_ships_within'] > 1)
				{
					$day_or_days = '<div class="ships"><i class="ships-truck"><svg viewBox="0 0 512 512"><path d="M5 131A47 47 0 0 1 52 84H334A47 47 0 0 1 381 131V178H413A47 47 0 0 1 450 195L497 253A47 47 0 0 1 507 283V350A47 47 0 0 1 460 397H444A63 63 0 1 1 319 397H162A63 63 0 1 1 37 394 47 47 0 0 1 5 350ZM46 364A63 63 0 0 1 154 366H327A63 63 0 0 1 350 343V131A16 16 0 0 0 334 115H52A16 16 0 0 0 36 131V350A16 16 0 0 0 46 364M381 334A63 63 0 0 1 436 366H460A16 16 0 0 0 476 350V283A16 16 0 0 0 472 273L426 215A16 16 0 0 0 413 209H381ZM99 366A31 31 0 1 0 99 428 31 31 0 0 0 99 366M381 366A31 31 0 1 0 381 428 31 31 0 0 0 381 366"></path></svg></i>Ships Within '.$group_items['inventory_ships_within'].' Days</div>';
				}
				
				$link_type = ''; 
				if(!empty($group_items['link_type'])) { $link_type = ' rel="'.$group_items['link_type'].'"'; }
				
				echo '
				<!-- Start Product -->
				<li class="container">
				  
					  <div class="img"><a href="'.$group_items["url"].'"'.$link_type.'>'.$sub_item_media.'</a></div>';
					  if($group['display_content_under_block_items'] == 'Yes')
					  {
						echo '<div class="text-product">
							<h2 class="title"><a href="'.$group_items["url"].'"'.$link_type.'>'.$group_items['meta_title'].'</a></h2>
							<div class="description">'.$group_items['meta_description'].'</div>
							'.$review_score.'
							'.$day_or_days.'
						</div>';
					   }
				  echo '
				</li>
				<!-- End Product -->'; 
			}
			else
			{
				$link_type = ''; 
				if(!empty($group_items['link_type'])) { $link_type = ' rel="'.$group_items['link_type'].'"'; }
				
				echo '
				<!-- Start Item -->
				<li class="container">
				  
				    <div class="img"><a href="'.$group_items["url"].'"'.$link_type.'>'.$sub_item_media.'</a></div>';
				    if($group['display_content_under_block_items'] == 'Yes')
				    {
					  echo'<div class="text">
					  	<h2 class="title"><a href="'.$group_items["url"].'"'.$link_type.'>'.$group_items['meta_title'].'</a></h2>
					    <div class="description">'.$group_items['meta_description'].'</div>
					  </div>';
				    }
				  echo '
				</li>
				<!-- End Item -->'; 
			}
			
			//Add pagers to slider.
			if($group['display_pagination'] == 'Yes')
			{
				if(isset($group_items["media_type"]))
				{
					if($group_items["media_type"] == 'Image')
					{
						if($group['display_thumbnails'] == 'Yes')
						{
							$slider_pager .= '<span class="thumbnail" data-index="'.$pager_counter.'">'.$sub_item_media.'</span>';
						}
						else
						{
							$slider_pager .= '<span class="pager" data-index="'.$pager_counter.'"></span>';
						}
					}
					elseif($group_items["media_type"] == 'Video')
					{
						$slider_pager .= $sub_item_media;
					}
				}
				else
				{
					$slider_pager .= '<span class="pager" data-index="'.$pager_counter.'"></span>';
				}
			}
			
			$pager_counter ++;
		}
		
		//Close - display as slider
		if($group['display_as_slider'] == 'Yes')
		{
			echo '</ul>
				</div>
					<div class="slider-pager container-width">';
					if($group['display_pagination'] == 'Yes')
					{
						echo $slider_pager;
					}
					echo '</div>
			</div>
		</div>';
		}
		//Close - display as css grid.
		else
		{
			echo '</ul>
			  </div>
			</div>';
		}
	}
}
?>
<?php } ?>
