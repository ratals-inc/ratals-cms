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

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/enable-disable-toggle.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/enable-disable-toggle.php');
}
else
{
	//Update Enabled/Disabled
	if(isset($_POST['field']) && !empty($_POST['field']) && isset($_POST['value']) && !empty($_POST['value']) && isset($_POST['tableName']) && !empty($_POST['tableName']) && isset($_POST['type']) && !empty($_POST['type']) && $_POST['type'] == 'changeActive')
	{
		changeActive($_POST['field'], $_POST['value'], $_POST['tableName']);
		
		//Clear cache on save.
		if($_SESSION['admin_site_id_global'] == 'No')
		{
			clearSiteCache($_SESSION['site_set_for_editing']);
		}
		else
		{
			clearAllSiteCache();
		}
		
		exit;
	}
}