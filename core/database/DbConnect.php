<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

namespace core\database;

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/DbConnect.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/DbConnect.php');
}
else
{
	//PDO database connection to site database.
	if(!class_exists('DbConnect'))
	{
		class DbConnect
		{
			protected function connect()
			{
				try
				{
					$dsn = 'mysql:host='.$this->host.'; dbname='.$this->db_name;
					$pdo = new \PDO($dsn, $this->username, $this->password);
					$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
					$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
					$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
					
					//Force MySQL strict mode.
					$pdo->exec("SET SESSION sql_mode = CONCAT(@@sql_mode, ',STRICT_TRANS_TABLES,STRICT_ALL_TABLES,ONLY_FULL_GROUP_BY,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION')");
					
					return $pdo;
				} 
				catch(\PDOException $e)
				{
					error_log($e->getMessage());
					echo 'Database connection error. Check your database login credentials in the file path of /core/database/DbCredentials.php';
					//throw $e;
					die();
				}
			}
		}
	}
	
	//PDO database connection to information_schema database to get table data.
	if(!class_exists('DbConnectSchema'))
	{
		class DbConnectSchema
		{
			protected function connect()
			{
				try
				{
					$dsn = 'mysql:host='.$this->host.'; dbname='.$this->db_name;
					$pdo_schema = new \PDO($dsn, $this->username, $this->password);
					$pdo_schema->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
					$pdo_schema->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
					$pdo_schema->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
					
					//Force MySQL strict mode.
					$pdo_schema->exec("SET SESSION sql_mode = CONCAT(@@sql_mode, ',STRICT_TRANS_TABLES,STRICT_ALL_TABLES,ONLY_FULL_GROUP_BY,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION')");
					
					return $pdo_schema;
				} 
				catch(\PDOException $e)
				{
					error_log($e->getMessage());
					echo 'Database connection error. Check your database login credentials in the file path of /core/database/DbCredentials.php';
					//throw $e;
					die();
				}
			}
		}
	}
}