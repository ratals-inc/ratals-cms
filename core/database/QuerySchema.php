<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

namespace core\database;

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/QuerySchema.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/QuerySchema.php');
}
else
{
	////////////////////////QUERIES TO INFORMATION_SECHEMA TABLE////////////////////////
	//These queries allow us to get table data like table names, table columns within a table, etc.
	class QuerySchema extends DbCredentialsSchema
	{
		//Select
		protected function schemaSelectSingleRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				$select_record_array = $stmt->fetch();
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function schemaSelectMultipleRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				$select_record_array = $stmt->fetchAll();
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function schemaSelectMultipleRecordsOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $column_one)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_records = $stmt->fetchAll();
					
					foreach($select_records as $select_record)
					{
						$select_record_array[] = $select_record[$column_one];
					}
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function schemaSelectMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_records = $stmt->fetchAll();
					
					foreach($select_records as $select_record)
					{
						$select_record_array[$select_record[$key_name]] = $select_record;
					}
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function schemaSelectMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_records = $stmt->fetchAll();
					
					foreach($select_records as $select_record)
					{
						$select_record_array[$select_record[$key_name]][] = $select_record;
					}
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
	}
}