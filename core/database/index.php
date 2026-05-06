<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/index.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/index.php');
}
else
{
	include_once('autoloader.php');
	include_once('instantiate.php');
}