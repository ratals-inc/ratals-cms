<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/pages-data.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/pages-data.php');
}
else
{
	$current_date = date('Y-m-d');
	if(isset($_SESSION['user_id'])) { $page_status_setting = " AND (`url_status` = '1' OR `url_status` = '3' OR (`url_status` = '4' AND `scheduled_date` <= '".$current_date."')) "; } else { $page_status_setting = " AND (`url_status` = '1' OR (`url_status` = '4' AND `scheduled_date` <= '".$current_date."')) "; }
	
	//Check if homepage is being requested.
	$url_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ? AND `site_id` = ?'.$page_status_setting.' LIMIT 1', [$home_page, $site_id]);
	
	$pages_data = array();
	if(!empty($url_data) && ($path_url == $url_data['hierarchy_url'] || $path_url == $url_data['flat_url'] || $path_url == '/' ||  $path_url == 'index' || $_SERVER["REQUEST_URI"] == '/' || strpos($_SERVER["REQUEST_URI"], '/?') === 0)) 
	{ 
		//Get homepage record data
		if(!empty($url_data))
		{
			$page_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $url_data['table_name'], 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$url_data['record_id'], $site_id]);
			
			if(!empty($page_data))
			{
				$pages_data = array_merge($page_data, $url_data, array('home_page_record_id' => $page_data["urls_id"], 'path_url' => '', 'page_not_found_404' => 'No'));
			}
		}
	}
	elseif($url_structure == 'Hierarchy') 
	{ 
		//Get URL data
		$url_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `hierarchy_url` = ?'.$page_status_setting.' LIMIT 1', [$site_id, $path_url]);
		
		if(!empty($url_data)) 
		{
			//Get record data
			$page_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $url_data['table_name'], 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$url_data['record_id'], $site_id]);
			
			if(!empty($page_data))
			{
				$pages_data = array_merge($page_data, $url_data, array('home_page_record_id' => '', 'path_url' => $url_data["hierarchy_url"], 'page_not_found_404' => 'No'));
			}
		}
		
		if(empty($pages_data["id"]) && $redirect_to_opposite_url == 'Yes') 
		{
			//Get URL data
			$url_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `flat_url` = ?'.$page_status_setting.' LIMIT 1', [$site_id, $path_url]);
			
			if(!empty($url_data))
			{
				//Get record data
				$page_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $url_data['table_name'], 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$url_data['record_id'], $site_id]);
				
				if(!empty($page_data))
				{
					$pages_data = array_merge($page_data, $url_data, array('home_page_record_id' => '', 'path_url' => $url_data["hierarchy_url"], 'page_not_found_404' => 'No'));
				}
			}
		}
	}
	elseif($url_structure == 'Flat')
	{ 
		//Get URL data
		$url_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `flat_url` = ?'.$page_status_setting.' LIMIT 1', [$site_id, $path_url]);
		
		if(!empty($url_data)) 
		{
			//Get record data
			$page_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $url_data['table_name'], 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$url_data['record_id'], $site_id]);
			
			if(!empty($page_data))
			{
				$pages_data = array_merge($page_data, $url_data, array('home_page_record_id' => '', 'path_url' => $url_data["flat_url"], 'page_not_found_404' => 'No'));
			}
		}
		
		if(empty($pages_data["id"]) && $redirect_to_opposite_url == 'Yes') 
		{
			//Get URL data
			$url_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `hierarchy_url` = ?'.$page_status_setting.' LIMIT 1', [$site_id, $path_url]);
			
			if(!empty($url_data))
			{
				//Get record data
				$page_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $url_data['table_name'], 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$url_data['record_id'], $site_id]);
				
				if(!empty($page_data))
				{
					$pages_data = array_merge($page_data, $url_data, array('home_page_record_id' => '', 'path_url' => $url_data["flat_url"], 'page_not_found_404' => 'No'));
				}
			}
		}
	}
	
	if(empty($pages_data["id"]) )
	{
		//Get URL data
		$url_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND (`flat_url` = ? OR `hierarchy_url` = ?)'.$page_status_setting.' LIMIT 1', [$site_id, '404', '404']);
		
		if(!empty($url_data)) 
		{
			//Get record data
			$page_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $url_data['table_name'], 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$url_data['record_id'], $site_id]);
			
			if(!empty($page_data))
			{
				$pages_data = array_merge($page_data, $url_data, array('home_page_record_id' => '', 'path_url' => '', 'page_not_found_404' => 'Yes'));
			}
		}
		
		//Log 404 page loads.
		$results->getInsertRecord(__LINE__, __FILE__, 'errors_404', '`site_id`, `total_404s`, `url_404`, `custom_fields`, `created_date`', '?, ?, ?, ?, UTC_TIMESTAMP()', [$site_id, 0, $url, '{}']);
	}
	
	if(empty($pages_data["id"]) )
	{
		header("HTTP/2 404"); echo "404 - The page you're looking for cannot be found. If you're the webmaster of this domain you should consider adding a custom 404 page in your template files.";
		exit;
	}
	
	$page_not_found_404 = $pages_data['page_not_found_404'];
	$path_url = $pages_data['path_url'];
	$home_page_record_id = $pages_data['home_page_record_id'];
	
	//urls table variables
	$id = $pages_data['id'];
	$page_type = $pages_data['table_name'];
	$rid = $pages_data['record_id'];
	$record_id = $pages_data['urls_id'];
	$is_active = $pages_data['url_status'];
	$scheduled_date = $pages_data['scheduled_date'];
	$meta_title = $pages_data['meta_title'];
	$flat_url = $pages_data['flat_url'];
	$path_level = $pages_data['path_level'];
	$path_level = $pages_data['path_level'];
	$parent_url_id = 0;
	if(!empty($path_level))
	{
		$parent_url_id = trim($path_level ?? '', '/');
		$parent_url_id_array = explode('/', $parent_url_id);
		$parent_url_id = end($parent_url_id_array);
	}
	$hierarchy_url = $pages_data['hierarchy_url'];
	$end_url_with = $pages_data['url_extension'];
	$canonical_url = urlId($pages_data['canonical_url']);
	$custom_link = $pages_data['custom_link'];
	$link_type = $pages_data['link_type'];
	$meta_robots = $pages_data['meta_robots'];
	$hreflang_url_id = $pages_data['hreflang_url_id'];
	$meta_description = $pages_data['meta_description'];
	$meta_keywords = $pages_data['meta_keywords'];
	$content_title = $pages_data['content_title'];
	$table_of_contents = urlId($pages_data['table_of_contents']);
	$table_of_contents = mediaId($table_of_contents, '', '', '');
	$table_of_contents = getNonce($table_of_contents);
	$pages_data['table_of_contents'] = $table_of_contents;
	$top_content = urlId($pages_data['top_content']);
	$top_content = mediaId($top_content, '', '', '');
	$top_content = getNonce($top_content);
	$pages_data['top_content'] = $top_content;
	$bottom_content = urlId($pages_data['bottom_content']);
	$bottom_content = mediaId($bottom_content, '', '', '');
	$bottom_content = getNonce($bottom_content);
	$pages_data['bottom_content'] = $bottom_content;
	$pages_media = $pages_data['media'];
	$focus_keyword = $pages_data['seo_keyword'];
	$pages_data['seo_score'];
	$pages_display_breadcrumbs = $pages_data['display_breadcrumbs'];
	$pages_breadcrumbs_name = $pages_data['breadcrumbs_label'];
	$include_in_html_sitemap = $pages_data['include_in_html_sitemap'];
	$include_in_xml_sitemap = $pages_data['include_in_xml_sitemap'];
	$include_in_site_search = $pages_data['include_in_site_search'];
	$template_filename = $pages_data['template'];
	$author_bio_id = $pages_data['author_bio_id'];
	$display_posted_on = $pages_data['display_posted_on'];
	$display_last_updated = $pages_data['display_last_updated'];
	
	//Record data variables
	//The below columns that are being put into a variable are for the default tables the CMS comes with of categories, pages, posts, and products.
	//If you create a custom admin area that uses urls, you can call the record data table with $pages_data['YOUR_COLUMN_NAME_HERE'].
	if(isset($pages_data['urls_id']))
	{
		$record_urls_id = $pages_data['urls_id'];
	}
	
	if(isset($pages_data['product_type']))
	{
		$product_type = $pages_data['product_type'];
	}
	
	if(isset($pages_data['lead_form_id']))
	{
		$lead_form_id = $pages_data['lead_form_id'];
	}
	
	if(isset($pages_data['item_number']))
	{
		$item_number = $pages_data['item_number'];
	}
	
	if(isset($pages_data['products_assigned']))
	{
		$products_assigned = $pages_data['products_assigned'];
	}
	
	if(isset($pages_data['inventory_assigned']))
	{
		$inventory_assigned = $pages_data['inventory_assigned'];
	}
	
	if(isset($pages_data['inventory_attribute_ids']))
	{
		$inventory_attribute_ids = $pages_data['inventory_attribute_ids'];
	}
	
	if(isset($pages_data['product_option_ids']))
	{
		$product_option_ids = $pages_data['product_option_ids'];
	}
	
	if(isset($pages_data['review_score']))
	{
		$pages_review_score = $pages_data['review_score'];
	}
	
	if(isset($pages_data['products_price']))
	{
		$products_price = $pages_data['products_price'];
	}
	
	if(isset($pages_data['products_sale_price']))
	{
		$products_sale_price = $pages_data['products_sale_price'];
	}
	
	if(isset($pages_data['products_sale_price_from']))
	{
		$products_sale_price_from = $pages_data['products_sale_price_from'];
	}
	
	if(isset($pages_data['products_sale_price_to']))
	{
		$products_sale_price_to = $pages_data['products_sale_price_to'];
	}
	
	if(isset($pages_data['display_post_in']))
	{
		$display_post_in = $pages_data['display_post_in'];
	}
	
	if(isset($pages_data['number_of_posts']))
	{
		$number_of_posts = $pages_data['number_of_posts'];
	}
	
	if(isset($pages_data['number_of_products']))
	{
		$number_of_products = $pages_data['number_of_products'];
	}
	
	if(isset($pages_data['display_filters']))
	{
		$display_filters = $pages_data['display_filters'];
	}
	
	if(isset($pages_data['display_posts']))
	{
		$display_posts = $pages_data['display_posts'];
	}
	
	$grid_columns = 5;
	if(isset($pages_data['grid_columns']) && !empty($pages_data['grid_columns']))
	{
		$grid_columns = $pages_data['grid_columns'];
	}
	
	if(isset($pages_data['inventory_assigned']))
	{
		$inventory_assigned = $pages_data['inventory_assigned'];
	}
	
	if(isset($pages_data['filter_attribute_ids']))
	{
		$filter_attribute_ids = $pages_data['filter_attribute_ids'];
	}
	
	if(isset($pages_data['custom_fields']))
	{
		$custom_fields = $pages_data['custom_fields'];
	}
	
	if(isset($pages_data['number_of_sitemap_results']))
	{
		$number_of_sitemap_results = $pages_data['number_of_sitemap_results'];
	}
	
	if(isset($pages_data['updated_date']))
	{
		$last_modified_date = $pages_data['updated_date'];
	}
	
	if(isset($pages_data['updated_by']))
	{
		$last_modified_by = $pages_data['updated_by'];
	}
	
	if(isset($pages_data['created_date']))
	{
		$created_date = $pages_data['created_date'];
	}
	
	if(isset($pages_data['created_by']))
	{
		$created_by = $pages_data['created_by'];
	}
}