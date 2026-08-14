<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

namespace core\database;

if(file_exists(INSTALLATION_ROOT.'/hooks/core/database/DbCredentials.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/core/database/DbCredentials.php');
}
else
{
	// IMPORTANT
	// If the application installation path changes, make sure the auto_prepend_file
	// paths in the server configuration files point to the correct installation.
	//
	// /.htaccess
	// /admin/.htaccess
	//
	// If you are using Nginx, update the path in these files instead:
	//
	// /.user.ini
	// /admin/.user.ini
	//
	// Failure to update these paths will prevent the site from loading properly.
	
	if(session_status() === PHP_SESSION_ACTIVE)
	{
		$_SESSION['site_db_name'] = '[DATABASE_NAME]';
	}
	
	//Database credentials
	class DbCredentials extends DbConnect
	{
		protected $host = '[DATABASE_HOSTNAME]';
		protected $username = '[DATABASE_USERNAME]';
		protected $password = '[DATABASE_PASSWORD]';
		protected $db_name = '[DATABASE_NAME]';
	
	}
	
	//Database credentials
	class DbCredentialsSchema extends DbConnectSchema
	{
		protected $host = '[DATABASE_HOSTNAME]';
		protected $username = '[DATABASE_USERNAME]';
		protected $password = '[DATABASE_PASSWORD]';
		protected $db_name = 'information_schema';
	
	}
}