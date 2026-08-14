<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

namespace core\database;

if(file_exists(INSTALLATION_ROOT.'/hooks/core/database/Query.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/core/database/Query.php');
}
else
{
	//SELECT Class
	class Query extends DbCredentials
	{
		protected function rawQuery($line, $file, $raw_query, $parameters)
		{
			try 
			{
				$sql = $raw_query;
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
	
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_record_array = $stmt->fetchAll();
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectSingleRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters)
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
					$select_record_array = $stmt->fetch();
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectLastRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` ".$where_clause." ORDER BY `id` DESC LIMIT 1;";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_record_array = $stmt->fetch();
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectMultipleRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters)
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
					$select_record_array = $stmt->fetchAll();
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectCountRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_records = $stmt->rowCount();
				
				return $select_records;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectMultipleRecordsOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $column_one)
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
		
		protected function selectMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name)
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
		
		protected function selectMultipleRecordsKeyNameOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $column_one)
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
						$select_record_array[$select_record[$key_name]] = $select_record[$column_one];
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
		
		protected function selectMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name)
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
		
		protected function selectMultipleRecordsKeyNameTwo($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2)
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
						$select_record_array[$select_record[$key_name]] = $select_record[$key_name_2];
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
		
		protected function selectMultipleRecordsKeyNameTwoFull($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2)
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
						$select_record_array[$select_record[$key_name]][$select_record[$key_name_2]] = $select_record;
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
		
		protected function selectMultipleRecordsKeyNameTwoFullArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2)
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
						$select_record_array[$select_record[$key_name]][$select_record[$key_name_2]][] = $select_record;
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
		
		protected function selectMultipleRecordsKeyNameThree($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2, $key_name_3)
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
						$select_record_array[$key_name][$select_record[$key_name_2]] = $select_record[$key_name_3];
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
		
		protected function selectLeftJoinSingleRecord($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` LEFT JOIN ".$join_on." ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_record_array = $stmt->fetch();
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectLeftJoinMultipleRecords($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` LEFT JOIN ".$join_on." ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_record_array = $stmt->fetchAll();
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectLeftJoinMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` LEFT JOIN ".$join_on." ".$where_clause.";";
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
		
		protected function selectLeftJoinMultipleRecordsKeyNameTwo($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name, $key_name_two)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` LEFT JOIN ".$join_on." ".$where_clause.";";
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
						$select_record_array[$select_record[$key_name]] = $select_record[$key_name_two];
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
		
		protected function selectLeftJoinMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` LEFT JOIN ".$join_on." ".$where_clause.";";
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
		
		protected function selectLeftJoinMultipleRecordsKeyNameTwoFull($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name, $key_name_2)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` LEFT JOIN ".$join_on." ".$where_clause.";";
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
						$select_record_array[$select_record[$key_name]][$select_record[$key_name_2]] = $select_record;
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
		
		protected function selectLeftJoinCountRecords($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT ".$table_columns." FROM `".$table_name."` LEFT JOIN ".$join_on." ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_records = $stmt->rowCount();
				
				return $select_records;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectUnionSingleRecord($line, $file, $table_columns, $table_name_1, $table_name_2, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT * FROM (SELECT ".$table_columns." FROM `".$table_name_1."` UNION SELECT ".$table_columns." FROM `".$table_name_2."`) both_tables ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_record_array = $stmt->fetch();
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function selectUnionMultipleRecords($line, $file, $table_columns, $table_name_1, $table_name_2, $where_clause, $parameters)
		{
			try 
			{
				$sql = "SELECT * FROM (SELECT ".$table_columns." FROM `".$table_name_1."` UNION SELECT ".$table_columns." FROM `".$table_name_2."`) both_tables ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				
				$select_record_array = array();
				if($stmt->rowCount() > 0)
				{
					$select_record_array = $stmt->fetchAll();
				}
				
				return $select_record_array;
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		//UPDATE
		protected function updateRecord($line, $file, $table_name, $column_names, $where_clause, $parameters)
		{
			try 
			{
				$sql = "UPDATE `".$table_name."` SET ".$column_names." ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				//Return number of rows affected
				return $stmt->rowCount();
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function updateMultipleRecords($line, $file, $table_name, $column_names, $where_clause, $parameters)
		{
			try 
			{
				$sql = "UPDATE `".$table_name."` SET ".$column_names." ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				
				foreach($parameters as $parameter)
				{
					if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
					$stmt->execute($parameter);
					if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameter, $line, $file); }
				}
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		//INSERT
		protected function insertRecord($line, $file, $table_name, $column_names, $placeholders, $parameters)
		{
			try 
			{
				$sql = "INSERT INTO `".$table_name."` (".$column_names.") VALUES (".$placeholders.");";
				$pdo = $this->connect();
				$stmt = $pdo->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
				//Return the ID of the last inserted row
				return $pdo->lastInsertId();
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		protected function insertMultipleRecords($line, $file, $table_name, $column_names, $placeholders, $parameters)
		{
			try 
			{
				$sql = "INSERT INTO `".$table_name."` (".$column_names.") VALUES (".$placeholders.");";
				$stmt = $this->connect()->prepare($sql);
				
				foreach($parameters as $parameter)
				{
					if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
					$stmt->execute($parameter);
					if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameter, $line, $file); }
				}
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		//DELETE
		protected function deleteRecord($line, $file, $table_name, $where_clause, $parameters)
		{
			try 
			{
				$sql = "DELETE FROM `".$table_name."` ".$where_clause.";";
				$stmt = $this->connect()->prepare($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->execute($parameters);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		//CREATE NEW DATABASE TABLE
		protected function createDatabaseTable($line, $file, $table_name, $table_columns, $table_setup)
		{
			try 
			{
				$parameters = array();
				$sql = "CREATE TABLE IF NOT EXISTS `".$table_name."` (".$table_columns.") ".$table_setup.";";
				$stmt = $this->connect();
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->exec($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		//ALTER DATABASE TABLE
		protected function alterDatabaseTable($line, $file, $table_name, $table_columns)
		{
			try 
			{
				$parameters = array();
				$sql = "ALTER TABLE `".$table_name."` ".$table_columns.";";
				$stmt = $this->connect();
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->exec($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
		
		//DROP DATABASE TABLE
		protected function dropDatabaseTable($line, $file, $table_name)
		{
			try 
			{
				$parameters = array();
				$sql = "DROP TABLE `".$table_name."`;";
				$stmt = $this->connect();
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_start = microtime(true); }
				$stmt->exec($sql);
				if(isset($_SESSION['display_sql_queries']) && $_SESSION['display_sql_queries'] == 'Yes') { $query_end = microtime(true); all_sql_queries($query_start, $query_end, $sql, $parameters, $line, $file); }
			}
			catch(\PDOException $e)
			{
				error_log($e->getMessage());
				throw $e;
			}
		}
	}
}