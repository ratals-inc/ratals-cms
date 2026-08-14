<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/update-leads-from-junk-to-active.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/update-leads-from-junk-to-active.php');
}
else
{
	//Update leads from junk to active.
	if(!function_exists('updateLeadsFromJunkToActive'))
	{
		function updateLeadsFromJunkToActive($lead_row_id)
		{
			foreach($lead_row_id as $active_junk_leads)
			{
				$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, 'leads', '`lead_status` = ?', 'WHERE `id` = ?', ['Active', $active_junk_leads]);
			}
			echo "1";
		}
	}
}