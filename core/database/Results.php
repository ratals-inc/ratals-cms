<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

namespace core\database;

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/Results.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/Results.php');
}
else
{
	class Results extends Query
	{
		public function getRawQuery($line, $file, $raw_query, $parameters)
		{
			$myResults = $this->rawQuery($line, $file, $raw_query, $parameters);
			
			return $myResults;
		}
		
		public function getSelectSingleRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			$myResults = $this->selectSingleRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSelectLastRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			$myResults = $this->selectLastRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			$myResults = $this->selectMultipleRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSelectCountRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			$myResults = $this->selectCountRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecordsOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $column_one)
		{
			$myResults = $this->selectMultipleRecordsOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $column_one);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name)
		{
			$myResults = $this->selectMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecordsKeyNameOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $column_one)
		{
			$myResults = $this->selectMultipleRecordsKeyNameOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $column_one);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name)
		{
			$myResults = $this->selectMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecordsKeyNameTwo($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2)
		{
			$myResults = $this->selectMultipleRecordsKeyNameTwo($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecordsKeyNameTwoFull($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2)
		{
			$myResults = $this->selectMultipleRecordsKeyNameTwoFull($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecordsKeyNameTwoFullArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2)
		{
			$myResults = $this->selectMultipleRecordsKeyNameTwoFullArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2);
			
			return $myResults;
		}
		
		public function getSelectMultipleRecordsKeyNameThree($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2, $key_name_3)
		{
			$myResults = $this->selectMultipleRecordsKeyNameThree($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name, $key_name_2, $key_name_3);
			
			return $myResults;
		}
		
		public function getSelectLeftJoinSingleRecord($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters)
		{
			$myResults = $this->selectLeftJoinSingleRecord($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSelectLeftJoinMultipleRecords($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters)
		{
			$myResults = $this->selectLeftJoinMultipleRecords($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSelectLeftJoinMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name)
		{
			$myResults = $this->selectLeftJoinMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name);
			
			return $myResults;
		}
		
		public function getSelectLeftJoinMultipleRecordsKeyNameTwo($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name, $key_name_two)
		{
			$myResults = $this->selectLeftJoinMultipleRecordsKeyNameTwo($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name, $key_name_two);
			
			return $myResults;
		}
		
		public function getSelectLeftJoinMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name)
		{
			$myResults = $this->selectLeftJoinMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name);
			
			return $myResults;
		}
		
		public function getSelectLeftJoinMultipleRecordsKeyNameTwoFull($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name, $key_name_2)
		{
			$myResults = $this->selectLeftJoinMultipleRecordsKeyNameTwoFull($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters, $key_name, $key_name_2);
			
			return $myResults;
		}
		
		public function getSelectLeftJoinCountRecords($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters)
		{
			$myResults = $this->selectLeftJoinCountRecords($line, $file, $table_columns, $table_name, $join_on, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSelectUnionSingleRecord($line, $file, $table_columns, $table_name_1, $table_name_2, $where_clause, $parameters)
		{
			$myResults = $this->selectUnionSingleRecord($line, $file, $table_columns, $table_name_1, $table_name_2, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSelectUnionMultipleRecords($line, $file, $table_columns, $table_name_1, $table_name_2, $where_clause, $parameters)
		{
			$myResults = $this->selectUnionMultipleRecords($line, $file, $table_columns, $table_name_1, $table_name_2, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getUpdateRecord($line, $file, $table_name, $column_names, $where_clause, $parameters)
		{
			$myResults = $this->updateRecord($line, $file, $table_name, $column_names, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getUpdateMultipleRecords($line, $file, $table_name, $column_names, $where_clause, $parameters)
		{
			$myResults = $this->updateMultipleRecords($line, $file, $table_name, $column_names, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getInsertRecord($line, $file, $table_name, $column_names, $placeholders, $parameters)
		{
			$myResults = $this->insertRecord($line, $file, $table_name, $column_names, $placeholders, $parameters);
			
			return $myResults;
		}
		
		public function getInsertMultipleRecords($line, $file, $table_name, $column_names, $placeholders, $parameters)
		{
			$myResults = $this->insertMultipleRecords($line, $file, $table_name, $column_names, $placeholders, $parameters);
			
			return $myResults;
		}
		
		public function getDeleteRecord($line, $file, $table_name, $where_clause, $parameters)
		{
			$myResults = $this->deleteRecord($line, $file, $table_name, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getCreateDatabaseTable($line, $file, $table_name, $table_columns, $table_setup)
		{
			$myResults = $this->createDatabaseTable($line, $file, $table_name, $table_columns, $table_setup);
			
			return $myResults;
		}
		
		public function getAlterDatabaseTable($line, $file, $table_name, $table_columns)
		{
			$myResults = $this->alterDatabaseTable($line, $file, $table_name, $table_columns);
			
			return $myResults;
		}
		
		public function getDropDatabaseTable($line, $file, $table_name)
		{
			$myResults = $this->dropDatabaseTable($line, $file, $table_name);
			
			return $myResults;
		}
	}
}