<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/contact-us.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-page/contact-us.php');
}
else
{
	if(isset($template_file_type_row['assigned_type']) && $template_file_type_row['assigned_type'] == 'Authors > Bio')
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
					$pages_data['author_bio'] = $sql_author_bio_row + array('author_page_url' => $author_page_url) + array('author_photo_url' => $author_photo_url) + array('author_media' => mediaId($author_bio_data[0], 'No', 'Yes', $author_bio_data[1]));
				}
				else
				{
					$pages_data['author_bio'] = $sql_author_bio_row + array('author_page_url' => $author_page_url) + array('author_photo_url' => $author_photo_url) + array('author_media' => '');
				}
			}
		}
	}
}