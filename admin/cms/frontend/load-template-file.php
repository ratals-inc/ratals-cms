<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-template-file.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/load-template-file.php');
}
else
{
	$templates_files = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'template_files', 'WHERE `site_id` = ? AND `status` = ? AND `id` = ?', [$site_id, '1', $template_filename]);
	
	if(isset($templates_files['filename']) && file_exists("sites/".$site_id."/templates/".$active_template_path.'/'.$templates_files['filename'])) 
	{
		include_once(INSTALLATION_ROOT."/sites/".$site_id."/templates/".$active_template_path.'/'.$templates_files['filename']);
	} 
	else 
	{
		echo "Template file ID '".$template_filename."' does not exist or is disabled.";
	}
}