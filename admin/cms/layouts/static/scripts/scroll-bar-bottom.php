<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/scripts/scroll-bar-bottom.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/scripts/scroll-bar-bottom.php');
}
else
{
	//Global include: loaded from /cms so it is available when admin pages are at a higher directory level than the CMS structure.
	include_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/layouts/table/scripts/scroll-bar-bottom.php');
}
