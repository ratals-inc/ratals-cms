<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/posts-comments.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/posts-comments.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'posts_comments')
	{
		$sql_record_data_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'posts', 'WHERE `urls_id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
		if(empty($sql_record_data_rows)) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
		
		if(isset($_POST['approve']))
		{
			$results->getUpdateRecord(__LINE__, __FILE__, 'comments', '`status` = ?, `approved_by` = ?, `approved_date` = UTC_TIMESTAMP()', 'WHERE `id` = ? AND `site_id` = ? AND `post_url_id` = ?', ['1', $_SESSION['user_first_last_name'], $_POST['id'], $_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? '')]);
			
			$results->getUpdateRecord(__LINE__, __FILE__, 'posts', '`updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', 'WHERE `urls_id` = ? AND `site_id` = ?', [$_SESSION['user_username'], trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
			
			//Clear cache on save.
			if($_SESSION['admin_site_id_global'] == 'No')
			{
				clearSiteCache($_SESSION['site_set_for_editing']);
			}
			else
			{
				clearAllSiteCache();
			}
			
			header("Location: ".$_SESSION['admin_url_with_rid']."");
			exit();
		}
		
		if(isset($_POST['delete']))
		{
			$results->getDeleteRecord(__LINE__, __FILE__, 'comments', 'WHERE `site_id` = ? AND `id` = ? AND `post_url_id` = ?', [$_SESSION["site_set_for_editing"], $_POST['id'], trim($_GET["rid"] ?? '')]);
			
			$results->getUpdateRecord(__LINE__, __FILE__, 'posts', '`updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', 'WHERE `urls_id` = ? AND `site_id` = ?', [$_SESSION['user_username'], trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
			
			//Clear cache on save.
			if($_SESSION['admin_site_id_global'] == 'No')
			{
				clearSiteCache($_SESSION['site_set_for_editing']);
			}
			else
			{
				clearAllSiteCache();
			}
			
			header("Location: ".$_SESSION['admin_url_with_rid']."");
			exit();
		}
	}
}