<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/add/includes/urls-header.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/add/includes/urls-header.php');
}
else
{
	$display_url_data = '';
}