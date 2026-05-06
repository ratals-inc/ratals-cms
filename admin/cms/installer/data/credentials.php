<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

namespace core\database;

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/DbCredentials.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/DbCredentials.php');
}
else
{
	if(session_status() === PHP_SESSION_ACTIVE)
	{
		$_SESSION['site_db_name'] = '[DATABASE_NAME]';
	}
	
	//Database credentials
	class DbCredentials extends DbConnect
	{
		protected $host = 'localhost';
		protected $username = '[DATABASE_USERNAME]';
		protected $password = '[DATABASE_PASSWORD]';
		protected $db_name = '[DATABASE_NAME]';
	
	}
	
	//Database credentials
	class DbCredentialsSchema extends DbConnectSchema
	{
		protected $host = 'localhost';
		protected $username = '[DATABASE_USERNAME]';
		protected $password = '[DATABASE_PASSWORD]';
		protected $db_name = 'information_schema';
	
	}
}