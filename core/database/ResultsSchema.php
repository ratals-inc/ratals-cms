<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

namespace core\database;

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/ResultsSchema.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/core/database/ResultsSchema.php');
}
else
{
	class ResultsSchema extends QuerySchema
	{
		public function getSchemaSelectSingleRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			$myResults = $this->schemaSelectSingleRecord($line, $file, $table_columns, $table_name, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSchemaSelectMultipleRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters)
		{
			$myResults = $this->schemaSelectMultipleRecords($line, $file, $table_columns, $table_name, $where_clause, $parameters);
			
			return $myResults;
		}
		
		public function getSchemaSelectMultipleRecordsOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $column_one)
		{
			$myResults = $this->schemaSelectMultipleRecordsOneColumn($line, $file, $table_columns, $table_name, $where_clause, $parameters, $column_one);
			
			return $myResults;
		}
		
		public function getSchemaSelectMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name)
		{
			$myResults = $this->schemaSelectMultipleRecordsKeyName($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name);
			
			return $myResults;
		}
		
		public function getSchemaSelectMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name)
		{
			$myResults = $this->schemaSelectMultipleRecordsKeyNameArray($line, $file, $table_columns, $table_name, $where_clause, $parameters, $key_name);
			
			return $myResults;
		}
	}
}
