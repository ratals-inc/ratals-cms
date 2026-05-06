<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$database_table_name = 'authors';
$current_database_table_names[] = $database_table_name;
$column_counter = 1;

$table_schema = 
array(
	'id' => array('column_name' => 'id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => 'auto_increment', 'column_key' => 'pri', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'site_id' => array('column_name' => 'site_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'urls_id' => array('column_name' => 'urls_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'author_photo' => array('column_name' => 'author_photo', 'column_type' => 'text', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'author_name' => array('column_name' => 'author_name', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'author_job_title' => array('column_name' => 'author_job_title', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'author_description' => array('column_name' => 'author_description', 'column_type' => 'text', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'author_personal_website_url' => array('column_name' => 'author_personal_website_url', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'author_same_as_urls' => array('column_name' => 'author_same_as_urls', 'column_type' => 'text', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'custom_fields' => array('column_name' => 'custom_fields', 'column_type' => 'longtext', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'created_by' => array('column_name' => 'created_by', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'created_date' => array('column_name' => 'created_date', 'column_type' => 'datetime', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'updated_by' => array('column_name' => 'updated_by', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'updated_date' => array('column_name' => 'updated_date', 'column_type' => 'datetime', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++)
);

$keys_schema = array();

if(!in_array($database_table_name, $existing_database_tables))
{
	//Install table when not installed.
	$table_columns = buildDatabaseTableCreateQuery($database_table_name, $table_schema, $keys_schema);
	
	$table_setup = 'ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;';
	$results->getCreateDatabaseTable(__LINE__, __FILE__, $database_table_name, $table_columns, $table_setup);
}
else
{
	//Update table when installed.
	$update_table_columns[$database_table_name] = $table_schema;
	$update_table_keys[$database_table_name] = $keys_schema;
}