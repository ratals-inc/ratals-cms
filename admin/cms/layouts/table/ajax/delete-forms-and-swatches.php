<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-forms-and-swatches.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-forms-and-swatches.php');
}
else
{
	//Delete Forms
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-forms-and-swatches')
	{
		foreach($_POST['deleteRow'] as $delete_forms_row_id)
		{
			$sql_get_form_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'forms', 'WHERE `id` = ? LIMIT 1', [$delete_forms_row_id]);
			
			if(!empty($sql_get_form_data))
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'forms', 'WHERE `id` = ?', [$sql_get_form_data['id']]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'form_media', 'WHERE `form_id` = ?', [$sql_get_form_data['id']]);
			}
		}
		
		//Clear cache on save.
		if($_SESSION['admin_site_id_global'] == 'No')
		{
			clearSiteCache($_SESSION['site_set_for_editing']);
		}
		else
		{
			clearAllSiteCache();
		}
		
		echo "1";
		exit;
	}
}