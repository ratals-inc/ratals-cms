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

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-leads.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/ajax/delete-leads.php');
}
else
{
	//Delete form leads.
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-leads')
	{
		foreach($_POST['deleteRow'] as $delete_lead_id)
		{
			$sql_get_lead_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'leads', 'WHERE `id` = ? LIMIT 1', [$delete_lead_id]);
			
			if(!empty($sql_get_lead_data))
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'leads', 'WHERE `id` = ?', [$sql_get_lead_data['id']]);
				
				$results->getDeleteRecord(__LINE__, __FILE__, 'leads_values', 'WHERE `leads_id` = ?', [$sql_get_lead_data['id']]);
				
				//Remove the lead conversion value to fix anayltics numbers.
				$results->getUpdateRecord(__LINE__, __FILE__, 'analytics', '`leads_id` = ?, `has_form_conversion` = ?, `form_conversion_value` = ?', 'WHERE `leads_id` = ?', [NULL, 0, NULL, $delete_lead_id]);
			}
		}
		
		echo "1";
		exit;
	}
}