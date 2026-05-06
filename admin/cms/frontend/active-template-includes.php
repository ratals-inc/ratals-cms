<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/active-template-includes.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/active-template-includes.php');
}
else
{
	//Start Active Sidebar Template Items
	$sidebar_active_template_items['active_template_includes'] = array();
	
	$sql_get_active_sidebar_items_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `status` = ? LIMIT 1', [$site_id, '1']);
	
	if(!empty($sql_get_active_sidebar_items_rows))
	{
		$sql_all_template_files = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'template_files', 'WHERE `templates_id` = ? AND `status` = ? AND `template_type` = ?', [$sql_get_active_sidebar_items_rows['id'], '1', 'includes', ]);
		
		if(!empty($sql_all_template_files))
		{
			foreach($sql_all_template_files as $sql_all_template_files_rows)
			{
				if($sql_get_active_sidebar_items_rows['status'] == '1')
				{
				  $sidebar_active_template_items['active_template_includes'][] = $sql_all_template_files_rows['filename'];
				}
			}
		}
	}
}