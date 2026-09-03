<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-site-searches.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-site-searches.php');
}
else
{
	//Delete site search keyword records
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-site-searches')
	{
		foreach($_POST['deleteRow'] as $row_id)
		{
			$sql_search_word_being_deleted = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'site_search', 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$row_id, $_SESSION["site_set_for_editing"]]);
			if(!empty($sql_search_word_being_deleted))
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'site_search', 'WHERE `keyword` = ? AND `site_id` = ?', [$sql_search_word_being_deleted['keyword'], $_SESSION["site_set_for_editing"]]);
			}
		}
		
		echo "1";
		exit;
	}
}