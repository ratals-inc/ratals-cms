<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$database_table_name = 'site_contact_info';
$current_database_table_names[] = $database_table_name;
$column_counter = 1;

$table_schema = 
array(
	'id' => array('column_name' => 'id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => 'auto_increment', 'column_key' => 'pri', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'site_id' => array('column_name' => 'site_id', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '0', 'ordinal_position' => $column_counter++),
	'display_contact_info_on_site' => array('column_name' => 'display_contact_info_on_site', 'column_type' => 'varchar(3)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'street_address' => array('column_name' => 'street_address', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'city' => array('column_name' => 'city', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'country' => array('column_name' => 'country', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'state' => array('column_name' => 'state', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'postal_code' => array('column_name' => 'postal_code', 'column_type' => 'varchar(30)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'store_latitude' => array('column_name' => 'store_latitude', 'column_type' => 'varchar(30)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'store_longitude' => array('column_name' => 'store_longitude', 'column_type' => 'varchar(30)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'hours' => array('column_name' => 'hours', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'days_of_the_week_open' => array('column_name' => 'days_of_the_week_open', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'phone_number' => array('column_name' => 'phone_number', 'column_type' => 'varchar(30)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'area_served' => array('column_name' => 'area_served', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'available_language' => array('column_name' => 'available_language', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'other_business_urls' => array('column_name' => 'other_business_urls', 'column_type' => 'text', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'site_email' => array('column_name' => 'site_email', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'email_cc' => array('column_name' => 'email_cc', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'email_bcc' => array('column_name' => 'email_bcc', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'email_from' => array('column_name' => 'email_from', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'email_from_name' => array('column_name' => 'email_from_name', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'email_server_url' => array('column_name' => 'email_server_url', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'email_server_port' => array('column_name' => 'email_server_port', 'column_type' => 'int', 'character_set_name' => '', 'collation_name' => '', 'extra' => '', 'column_key' => '', 'is_nullable' => 'yes', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'email_username' => array('column_name' => 'email_username', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
	'email_password' => array('column_name' => 'email_password', 'column_type' => 'varchar(255)', 'character_set_name' => 'utf8mb4', 'collation_name' => 'utf8mb4_0900_ai_ci', 'extra' => '', 'column_key' => '', 'is_nullable' => 'no', 'column_default' => '', 'ordinal_position' => $column_counter++),
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