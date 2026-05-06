<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$database_table_name = 'site_search';
$current_database_table_names[] = $database_table_name;
$column_counter = 1;

$table_schema = 
array(
	'id' => array('column_name' => 'id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => 'auto_increment', 'column_key' => 'pri', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'site_id' => array('column_name' => 'site_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'total_searches' => array('column_name' => 'total_searches', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'keyword' => array('column_name' => 'keyword', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'created_date' => array('column_name' => 'created_date', 'column_type' => 'datetime', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++)
);

$keys_schema = 
array(
	'ratals_index_1' => 
	array(
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '1', 'column_name' => 'site_id', 'index_type' => 'btree'),
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '2', 'column_name' => 'keyword', 'index_type' => 'btree')
	),
	'ratals_index_2' => 
	array(
		array('index_name' => 'ratals_index_2', 'non_unique' => '1', 'seq_in_index' => '1', 'column_name' => 'created_date', 'index_type' => 'btree')
	)
);

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