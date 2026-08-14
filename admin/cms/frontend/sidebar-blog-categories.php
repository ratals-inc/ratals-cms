<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/sidebar-blog-categories.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/sidebar-blog-categories.php');
}
else
{
	//Start Sidebar Blog Categories
	$sidebar_blog_categories['sidebar_blog_categories'] = array();
	
	$sql_get_blog_categories = $_SESSION['results']->getSelectLeftJoinMultipleRecords(__LINE__, __FILE__, '*', 'categories', '`urls` ON `categories`.`urls_id` = `urls`.`id`', 'WHERE `urls`.`site_id` = ? AND `urls`.`url_status` = ? AND `categories`.`blog_sidebar_link_display` = ? ORDER BY `blog_sidebar_link_order` ASC', [$site_id, '1', 'Yes']);
	
	if(!empty($sql_get_blog_categories))
	{
		foreach($sql_get_blog_categories as $sql_get_blog_category)
		{
			$sidebar_anchor_text = $sql_get_blog_category['meta_title'];
			
			if(!empty($sql_get_blog_category['breadcrumbs_label']))
			{
				$sidebar_anchor_text = $sql_get_blog_category['breadcrumbs_label'];
			}
			
			$sidebar_blog_categories['sidebar_blog_categories'][] = array('meta_title' => $sidebar_anchor_text, 'url' => getUrl($sql_get_blog_category["custom_link"], $sql_get_blog_category["url_extension"], $sites_end_urls_with, $url_structure, $domain, $sql_get_blog_category["hierarchy_url"], $sql_get_blog_category["flat_url"], '', $sql_get_blog_category['id'], $home_page));
		}
	}
}