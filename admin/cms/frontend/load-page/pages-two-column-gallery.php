<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/load-page/pages-two-column-gallery.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/load-page/pages-two-column-gallery.php');
}
else
{
	if(isset($template_file_type_row['assigned_type']) && $template_file_type_row['assigned_type'] == 'Pages > Two Column Gallery')
	{
	}
}