<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$database_table_name = 'assignments_sub_items';
$current_database_table_names[] = $database_table_name;
$column_counter = 1;

$table_schema = 
array(
	'id' => array('column_name' => 'id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => 'auto_increment', 'column_key' => 'pri', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'site_id' => array('column_name' => 'site_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'status' => array('column_name' => 'status', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'item_status' => array('column_name' => 'item_status', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'inventory_status' => array('column_name' => 'inventory_status', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'inventory_assigned_to_product_status' => array('column_name' => 'inventory_assigned_to_product_status', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'parent_id_table_name' => array('column_name' => 'parent_id_table_name', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'child_id_table_name' => array('column_name' => 'child_id_table_name', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'type' => array('column_name' => 'type', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'pages_groups_id' => array('column_name' => 'pages_groups_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'parent_id' => array('column_name' => 'parent_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'child_id' => array('column_name' => 'child_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'inventory_attribute_value_ids' => array('column_name' => 'inventory_attribute_value_ids', 'column_type' => 'text', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'sub_products_ids' => array('column_name' => 'sub_products_ids', 'column_type' => 'text', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'product_price' => array('column_name' => 'product_price', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0.000000', 'ordinal_position' => $column_counter++),
	'product_sale_price' => array('column_name' => 'product_sale_price', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0.000000', 'ordinal_position' => $column_counter++),
	'product_sale_price_from' => array('column_name' => 'product_sale_price_from', 'column_type' => 'date', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'product_sale_price_to' => array('column_name' => 'product_sale_price_to', 'column_type' => 'date', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'product_review_score' => array('column_name' => 'product_review_score', 'column_type' => 'decimal(2,1)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0.0', 'ordinal_position' => $column_counter++),
	'inventory_id' => array('column_name' => 'inventory_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'inventory_price' => array('column_name' => 'inventory_price', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0.000000', 'ordinal_position' => $column_counter++),
	'inventory_sale_price' => array('column_name' => 'inventory_sale_price', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0.000000', 'ordinal_position' => $column_counter++),
	'inventory_sale_price_from' => array('column_name' => 'inventory_sale_price_from', 'column_type' => 'date', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'inventory_sale_price_to' => array('column_name' => 'inventory_sale_price_to', 'column_type' => 'date', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'inventory_url' => array('column_name' => 'inventory_url', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'media_id' => array('column_name' => 'media_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'media_tag' => array('column_name' => 'media_tag', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'inventory_track_quantity' => array('column_name' => 'inventory_track_quantity', 'column_type' => 'varchar(3)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => 'no', 'ordinal_position' => $column_counter++),
	'inventory_quantity_available' => array('column_name' => 'inventory_quantity_available', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'inventory_allow_backorders' => array('column_name' => 'inventory_allow_backorders', 'column_type' => 'varchar(3)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => 'yes', 'ordinal_position' => $column_counter++),
	'inventory_ships_within' => array('column_name' => 'inventory_ships_within', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'sort' => array('column_name' => 'sort', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'updated_date' => array('column_name' => 'updated_date', 'column_type' => 'datetime', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++)
);

$keys_schema = 
array(
	'ratals_index_1' => 
	array(
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '1', 'column_name' => 'site_id', 'index_type' => 'btree'),
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '2', 'column_name' => 'parent_id', 'index_type' => 'btree'),
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '3', 'column_name' => 'pages_groups_id', 'index_type' => 'btree'),
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '4', 'column_name' => 'status', 'index_type' => 'btree'),
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '5', 'column_name' => 'sort', 'index_type' => 'btree')
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