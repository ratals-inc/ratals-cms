<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-404-errors.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-404-errors.php');
}
else
{
	//Delete 404 Error records
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-404-errors')
	{
		foreach($_POST['deleteRow'] as $row_id)
		{
			$sql_404_url_being_deleted = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'errors_404', 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$row_id, $_SESSION["site_set_for_editing"]]);
			if(!empty($sql_404_url_being_deleted))
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'errors_404', 'WHERE `url_404` = ? AND `site_id` = ?', [$sql_404_url_being_deleted['url_404'], $_SESSION["site_set_for_editing"]]);
			}
		}
		
		echo "1";
		exit;
	}
}