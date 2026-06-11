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
			protected static $pdo = null;
			
			protected function connect()
			{
				try
				{
					if(self::$pdo === null)
					{
						if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
						
						$dsn = 'mysql:host='.$this->host.';dbname='.$this->db_name;
						self::$pdo = new \PDO($dsn,$this->username,$this->password);
						self::$pdo->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
						self::$pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
						self::$pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
						
						//Force MySQL strict mode.
						self::$pdo->exec("SET SESSION sql_mode = CONCAT(@@sql_mode, ',STRICT_TRANS_TABLES,STRICT_ALL_TABLES,ONLY_FULL_GROUP_BY,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION')");
						
						if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $this->db_name.' database connection', 'Credentials hidden', __LINE__, __FILE__); }
					}
					
					return self::$pdo;
				}
				catch(\PDOException $e)
				{
					error_log($e->getMessage());
					echo 'Database connection error. Check your database login credentials in the file path of /core/database/DbCredentials.php';
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
			protected static $pdo_schema = null;
			
			protected function connect()
			{
				try
				{
					if(self::$pdo_schema === null)
					{
						if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
						
						$dsn = 'mysql:host='.$this->host.';dbname='.$this->db_name;
						self::$pdo_schema = new \PDO($dsn,$this->username,$this->password);
						self::$pdo_schema->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false);
						self::$pdo_schema->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
						self::$pdo_schema->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
			
						// Force MySQL strict mode.
						self::$pdo_schema->exec("SET SESSION sql_mode = CONCAT(@@sql_mode, ',STRICT_TRANS_TABLES,STRICT_ALL_TABLES,ONLY_FULL_GROUP_BY,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION')");
						
						if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, 'Schema database connection', 'Credentials hidden', __LINE__, __FILE__); }
					}
					
					return self::$pdo_schema;
				}
				catch(\PDOException $e)
				{
					error_log($e->getMessage());
					echo 'Database connection error. Check your database login credentials in the file path of /core/database/DbCredentials.php';
					die();
				}
			}
		}
	}
}