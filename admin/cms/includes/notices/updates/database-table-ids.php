<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//UPDATE DATABASE_TABLES WITH THE CORRECT COLUMN IDS - THIS ALLOWS THE ADMIN AREA TO KNOW WHICH COLUMNS ARE IN A TABLE
if(file_exists($temp_extract_dir.'/admin/cms/installer/data/database-column-ids.php'))
{
	$database_name = $_SESSION['site_db_name'];
	
	try
	{
		$results->getDeleteRecord(__LINE__, __FILE__, 'database_tables', '', []);
		writeToInstallLog('Successfully deleted database table column ids.');
		
		//Only attempt installation if deletion was successful
		try
		{
			include($temp_extract_dir.'/admin/cms/installer/data/database-column-ids.php');
			writeToInstallLog('Successfully installed database table column ids.');
		}
		catch(\Throwable $e)
		{
			writeToInstallLog('Failed installing database table column ids: '.$e->getMessage());
		}
	}
	catch(\Throwable $e)
	{
		writeToInstallLog('Failed deleting database table column ids: '.$e->getMessage());
	}
}