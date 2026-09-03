<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/blog.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/blog.php');
}
else
{
	if(isset($template_file_type_row['assigned_type']) && $template_file_type_row['assigned_type'] == 'Pages > Blog')
	{
		//Get blog post total count
		$blog_posts_count = $results->getSelectLeftJoinMultipleRecords(__LINE__, __FILE__, '`urls`.`id`', 'posts', '`urls` ON `urls`.record_id = `posts`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`table_name` = ? AND `urls`.`url_status` = ?', [$site_id, 'posts', 1]);
		
		$pagination_results_per_page = 10;
		$page_number = 1;
		$number_of_paginated_pages = 0;
		$pages_data['total_number_of_results'] = count($blog_posts_count);
		
		if(!empty($blog_posts_count))
		{
			if($pages_data['number_of_posts'] > 0 && is_numeric($pages_data['number_of_posts']))
			{
				$pagination_results_per_page = $pages_data['number_of_posts'];
			}
			elseif($site_settings['blog_pagination'] > 0 && is_numeric($site_settings['blog_pagination']))
			{
				$pagination_results_per_page = $site_settings['blog_pagination'];
			}
			
			if(!empty($_GET["page"]) && is_numeric($_GET["page"]))
			{
				$page_number = $_GET["page"];
			}
			
			if($pages_data['total_number_of_results'] > 0)
			{
				$number_of_paginated_pages = ceil($pages_data['total_number_of_results'] / $pagination_results_per_page);
			}
			
			if($page_number > 1 && $page_number > $number_of_paginated_pages)
			{
				header("Location: ".$pages_data["pages_url"]); exit();
			}
			
			$offset = ($page_number * $pagination_results_per_page) - $pagination_results_per_page;
			
			//Get all blog posts
			$all_blog_posts = $results->getSelectLeftJoinMultipleRecords(__LINE__, __FILE__, '*', 'posts', '`urls` ON `urls`.record_id = `posts`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`table_name` = ? AND `urls`.`url_status` = ? ORDER BY `created_date` DESC LIMIT '.$offset.', '.$pagination_results_per_page, [$site_id, 'posts', 1]);
			
			if(!empty($all_blog_posts))
			{
				$lazy_load_item_counter = 0;
				$fetch_priority_high_counter = 0;
				$items_done = 0;
				
				//$lazy_load_media_row variable is set in load-sites/site-settings.php
				if($lazy_load_media_row == 1)
				{
					$lazy_load_item_counter = $lazy_load_media_row;
				}
				if($lazy_load_media_row > 1)
				{
					$lazy_load_item_counter = (2 * ($lazy_load_media_row - 1)) + 1;
				}
				
				if(empty($lazy_load_media_row) || $lazy_load_media_row == 0 || $lazy_load_media_row == 1)
				{
					$fetch_priority_high_counter = (2 * 1);
				}
				elseif($lazy_load_media_row == 2)
				{
					$fetch_priority_high_counter = (2 * 2);
				}
				elseif($lazy_load_media_row > 2)
				{
					$fetch_priority_high_counter = (2 * 3);
				}
				
				foreach($all_blog_posts as $blog_post)
				{
					//Get author bio for each post
					$author_bio = array();
					if(!empty($blog_post['author_bio_id']))
					{
						$sql_author_bio_row = $results->getSelectLeftJoinSingleRecord(__LINE__, __FILE__, '*', 'authors', '`urls` ON `urls`.record_id = `authors`.`id`', 'WHERE `urls`.`id` = ? AND `urls`.`site_id` = ? AND `urls`.`table_name` = ? AND `urls`.`url_status` = ?', [$blog_post['author_bio_id'], $site_id, 'authors', 1]);
						
						if(!empty($sql_author_bio_row))
						{
							$author_page_url = '';
							if(!empty($sql_author_bio_row))
							{
								$author_page_url = urlId($sql_author_bio_row['urls_id']);
							}
							
							$author_photo_url = '';
							if(isset($sql_author_bio_row['author_photo']) && !empty($sql_author_bio_row['author_photo']))
							{
								$author_photo_data = mediaIdArray($sql_author_bio_row['author_photo']);
								if(isset($author_photo_data[0]['full_url']) && !empty($author_photo_data[0]['full_url']))
								{
									$author_photo_url = $author_photo_data[0]['full_url'];
								}
							}
							
							if(!empty($sql_author_bio_row['author_photo']))
							{
								$author_bio_data = explode('~||~', $sql_author_bio_row['author_photo']);
								$author_bio = array('author_bio' => $sql_author_bio_row + array('author_page_url' => $author_page_url) + array('author_photo_url' => $author_photo_url) + array('author_media' => mediaId($author_bio_data[0], 'No', 'Yes', '', $author_bio_data[1])));
							}
							else
							{
								$author_bio = array('author_bio' => $sql_author_bio_row + array('author_page_url' => $author_page_url) + array('author_photo_url' => $author_photo_url) + array('author_media' => ''));
							}
						}
					}
					
					$posted_in_category = array();
					if(!empty($blog_post))
					{
						//Get post url path lvel ids to see what the parent url categoriy is.
						$blog_post_path_level = trim($blog_post['path_level'], '/');
						
						if(strpos($blog_post_path_level, '/') !== false)
						{
							$blog_post_path_level_array = explode('/', $blog_post_path_level);
							$blog_post_path_level = end($blog_post_path_level_array);
						}
						//If post url is homepage/post_url, set the homepage url id as teh parent location. URL paths in the urls table will set it to 0 so we have to set the homepage id.
						elseif(empty($blog_post_path_level))
						{
							$blog_post_path_level = $home_page;
						}
						
						$sql_get_category_pages_url_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `id` = ? AND `url_status` = ?', [$site_id, $blog_post_path_level, '1']);
						
						if(!empty($sql_get_category_pages_url_rows))
						{
							$posted_category_anchor_text = $sql_get_category_pages_url_rows['meta_title'];
							
							if(!empty($sql_get_category_pages_url_rows['breadcrumbs_label']))
							{
								$posted_category_anchor_text = $sql_get_category_pages_url_rows['breadcrumbs_label'];
							}
							
							$posted_in_category = array('posted_in_category' => array('url' => getUrl($sql_get_category_pages_url_rows["custom_link"], $sql_get_category_pages_url_rows["url_extension"], $sites_end_urls_with, $url_structure, $domain, $sql_get_category_pages_url_rows["hierarchy_url"], $sql_get_category_pages_url_rows["flat_url"], '', $sql_get_category_pages_url_rows['id'], $home_page)) + array('meta_title' => $posted_category_anchor_text));
						}
					}
					
					$lazy_load_item = 'No';
					$items_done++;
					
					if($lazy_load_item_counter > 0)
					{
						if($items_done >= $lazy_load_item_counter)
						{
							$lazy_load_item = 'Yes';
						}
					}
					
					$fetch_priority_sub_products = 'fetchPriorityAuto';
					
					if($fetch_priority_high_counter > 0)
					{
						if($items_done <= $fetch_priority_high_counter)
						{
							$fetch_priority_sub_products = 'fetchPriorityHigh';
						}
					}
					
					$media_array = array();
					$first_media_assigned = array();
					if(!empty($blog_post['media']))
					{
						if(strpos($blog_post['media'], '*||*') !== false)
						{
							$media_items = explode('*||*', $blog_post['media']);
							
							foreach($media_items as $media_item)
							{
								$media_array = explode('~||~', $media_item);
								$first_media_assigned[] = mediaId($media_array[0], $lazy_load_item, $fetch_priority_sub_products, '', $media_array[1]);
								break; //break to only get the first media.
							}
						}
						else
						{
							$media_array = explode('~||~', $blog_post['media']);
							$first_media_assigned[] = mediaId($media_array[0], $lazy_load_item, $fetch_priority_sub_products, '', $media_array[1]);
						}
					}
					
					//Merge all data into $pages_data array
					$pages_data['posts'][] = $blog_post 
					+ array('final_url' => getUrl($blog_post['custom_link'], $blog_post['url_extension'], $sites_end_urls_with, $url_structure, $domain, $blog_post['hierarchy_url'], $blog_post['flat_url'], '', $blog_post['id'], $home_page))
					+ array('media_data' => $first_media_assigned)
					+ $author_bio
					+ $posted_in_category;
				}
			}
		}
	}
}