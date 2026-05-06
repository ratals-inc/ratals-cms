<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Database Tables
$parameters = array();
$sql_all_admin_fields = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', '', [], 'column_name');

$sql_all_database_tables = $results_schema->getSchemaSelectMultipleRecords(__LINE__, __FILE__, '`TABLE_NAME`', 'tables', 'WHERE table_schema = ? ORDER BY table_name ASC', [$database_name]);

if(!empty($sql_all_database_tables))
{
	foreach($sql_all_database_tables as $sql_all_database_table)
	{
		if(!empty($sql_all_database_table['TABLE_NAME']))
		{
			$sql_all_column_names_in_table = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, '`COLUMN_NAME`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `ORDINAL_POSITION` ASC', [$database_name, $sql_all_database_table['TABLE_NAME']], 'COLUMN_NAME');
			
			if(!empty($sql_all_column_names_in_table))
			{
				$database_assigned_fields_column_ids = '';
				
				foreach($sql_all_column_names_in_table as $sql_column_name)
				{
					$database_assigned_fields_column_ids .= $sql_all_admin_fields[$sql_column_name]['id'].',';
				}
				
				if(!empty($database_assigned_fields_column_ids))
				{
					$database_assigned_fields_column_ids = trim($database_assigned_fields_column_ids, ',');
					
					$parameters[] = array($sql_all_database_table['TABLE_NAME'], ','.$database_assigned_fields_column_ids.',', '{}', $first_last_name, $first_last_name);
				}
			}
		}
	}
	
	$column_names = '`id`, `site_id`, `database_table_name`, `admin_fields_ids`, `custom_fields`, `updated_date`,  `updated_by`, `created_date`, `created_by`';
	$placeholders = 'NULL,0,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';
	
	$results->getInsertMultipleRecords(__LINE__, __FILE__, 'database_tables', $column_names, $placeholders, $parameters);
}