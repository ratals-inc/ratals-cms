<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/modules.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/frontend/modules.php');
}
else
{
	//MODULES - Select everything from modules table.
	$modules = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'modules', '', []);
	
	$_SESSION['accounting_enabled'] = $modules["accounting_enabled"] ?? 'No';
	$accounting_enabled = $modules["accounting_enabled"] ?? 'No';
}