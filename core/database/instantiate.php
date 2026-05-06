<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/instantiate.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/instantiate.php');
}
else
{
	$results = new core\database\Results;
	$results_schema = new core\database\ResultsSchema;
	
	//Only set these when session are avaliable. They are not avaliable in webhooks
	if(session_status() === PHP_SESSION_ACTIVE)
	{
		$_SESSION['results'] = $results;
		$_SESSION['results_schema'] = $results_schema;
	}
}
