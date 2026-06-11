<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$database_table_name = 'analytics';
$current_database_table_names[] = $database_table_name;
$column_counter = 1;

$table_schema = 
array(
	'id' => array('column_name' => 'id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => 'auto_increment', 'column_key' => 'pri', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'site_id' => array('column_name' => 'site_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'analytics_unique_id' => array('column_name' => 'analytics_unique_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'analytics_cookie_id' => array('column_name' => 'analytics_cookie_id', 'column_type' => 'char(32)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'masked_ip_hash' => array('column_name' => 'masked_ip_hash', 'column_type' => 'varchar(64)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'cookie_ip_hash' => array('column_name' => 'cookie_ip_hash', 'column_type' => 'char(32)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'referer_source' => array('column_name' => 'referer_source', 'column_type' => 'varchar(100)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'referer_url' => array('column_name' => 'referer_url', 'column_type' => 'varchar(512)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'pageview_url' => array('column_name' => 'pageview_url', 'column_type' => 'text', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'pageview_hash' => array('column_name' => 'pageview_hash', 'column_type' => 'char(32)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'cart_id' => array('column_name' => 'cart_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'has_cart_contact_info' => array('column_name' => 'has_cart_contact_info', 'column_type' => 'tinyint(1)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'orders_id' => array('column_name' => 'orders_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'has_order' => array('column_name' => 'has_order', 'column_type' => 'tinyint(1)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'total_product_amount' => array('column_name' => 'total_product_amount', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0.000000', 'ordinal_position' => $column_counter++),
	'total_shipping_amount' => array('column_name' => 'total_shipping_amount', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0.000000', 'ordinal_position' => $column_counter++),
	'total_tax_amount' => array('column_name' => 'total_tax_amount', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0.000000', 'ordinal_position' => $column_counter++),
	'total_coupon_discount_amount' => array('column_name' => 'total_coupon_discount_amount', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'total_order_amount' => array('column_name' => 'total_order_amount', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0.000000', 'ordinal_position' => $column_counter++),
	'total_order_standard_cost_amount' => array('column_name' => 'total_order_standard_cost_amount', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'total_order_landed_cost_amount' => array('column_name' => 'total_order_landed_cost_amount', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'leads_id' => array('column_name' => 'leads_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'has_form_conversion' => array('column_name' => 'has_form_conversion', 'column_type' => 'tinyint(1)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'form_conversion_value' => array('column_name' => 'form_conversion_value', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'event_name' => array('column_name' => 'event_name', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'event_value' => array('column_name' => 'event_value', 'column_type' => 'decimal(16,6)', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'affiliate_account_id' => array('column_name' => 'affiliate_account_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'visit_date' => array('column_name' => 'visit_date', 'column_type' => 'date', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'created_date' => array('column_name' => 'created_date', 'column_type' => 'datetime', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++)
);

$keys_schema = 
array(
	'ratals_index_1' => 
	array(
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '1', 'column_name' => 'site_id', 'index_type' => 'btree'),
		array('index_name' => 'ratals_index_1', 'non_unique' => '1', 'seq_in_index' => '2', 'column_name' => 'analytics_cookie_id', 'index_type' => 'btree')
	),
	'ratals_index_2' => 
	array(
		array('index_name' => 'ratals_index_2', 'non_unique' => '1', 'seq_in_index' => '1', 'column_name' => 'site_id', 'index_type' => 'btree'),
		array('index_name' => 'ratals_index_2', 'non_unique' => '1', 'seq_in_index' => '2', 'column_name' => 'masked_ip_hash', 'index_type' => 'btree')
	),
	'ratals_index_3' => 
	array(
		array('index_name' => 'ratals_index_3', 'non_unique' => '1', 'seq_in_index' => '1', 'column_name' => 'site_id', 'index_type' => 'btree'),
		array('index_name' => 'ratals_index_3', 'non_unique' => '1', 'seq_in_index' => '2', 'column_name' => 'created_date', 'index_type' => 'btree')
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