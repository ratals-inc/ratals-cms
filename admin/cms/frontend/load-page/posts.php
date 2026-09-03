<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/posts.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/posts.php');
}
else
{
	if(isset($template_file_type_row['assigned_type']) && $template_file_type_row['assigned_type'] == 'Posts > Posts')
	{
		$pages_data['author_bio'] = array();
		if(!empty($pages_data['author_bio_id']))
		{
			$sql_author_bio_row = $results->getSelectLeftJoinSingleRecord(__LINE__, __FILE__, '*', 'authors', '`urls` ON `urls`.record_id = `authors`.`id`', 'WHERE `urls`.`id` = ? AND `urls`.`site_id` = ? AND `urls`.`table_name` = ? AND `urls`.`url_status` = ?', [$pages_data['author_bio_id'], $site_id, 'authors', 1]);
			
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
					$pages_data['author_bio'] = $sql_author_bio_row + array('author_page_url' => $author_page_url) + array('author_photo_url' => $author_photo_url) + array('author_media' => mediaId($author_bio_data[0], 'No', 'Yes', '', $author_bio_data[1]));
				}
				else
				{
					$pages_data['author_bio'] = $sql_author_bio_row + array('author_page_url' => $author_page_url) + array('author_photo_url' => $author_photo_url) + array('author_media' => '');
				}
			}
		}
		
		$_SESSION['allow_comments'] = $pages_data['allow_comments'];
		
		//Start getting posted comments on a post.
		$sql_get_comments_total_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'comments', 'WHERE `site_id` = ? AND `post_url_id` = ? AND `status` = ?', [$site_id, $pages_data['id'], '1']);
		
		function getComments($pages_id, $comment_parent_id = 0)
		{
			global $site_id;
			
			if($comment_parent_id == 0)
			{
				$sql_get_comments = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'comments', 'WHERE `site_id` = ? AND `post_url_id` = ? AND `status` = ? AND `comment_parent_id` IS NULL ORDER BY `created_date` DESC', [$site_id, $pages_id, '1']);
			}
			else
			{
				$sql_get_comments = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'comments', 'WHERE `site_id` = ? AND `post_url_id` = ? AND `status` = ? AND `comment_parent_id` = ? ORDER BY `created_date` DESC', [$site_id, $pages_id, '1', $comment_parent_id]);
			}
			
			$main_comments = array();
			
			if(!empty($sql_get_comments))
			{
				foreach($sql_get_comments as $sql_get_comments_rows)
				{
					if($comment_parent_id == 0)
					{
						$sub_comments = getComments($pages_id, $sql_get_comments_rows["id"]);
					}
					elseif($comment_parent_id != 0)
					{
						$sub_comments = getComments($pages_id, $sql_get_comments_rows["id"]);
					}
					
					if(!empty($sub_comments))
					{
						$sql_get_comments_rows['sub_comments'] = $sub_comments;
					}
					$main_comments[] = $sql_get_comments_rows;
				}
			}
			
			return $main_comments;
		}
		//End getting posted comments on a post.
	}
}