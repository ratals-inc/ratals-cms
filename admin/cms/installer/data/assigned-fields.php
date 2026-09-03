<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$parameters = array();

$sql_all_admin_users = $results->getSelectMultipleRecordsOneColumn(__LINE__, __FILE__, '*', 'users', '', [], 'id');

$sql_all_database_tables = $results_schema->getSchemaSelectMultipleRecords(__LINE__, __FILE__, '`table_name`', 'tables', 'WHERE table_schema = ? ORDER BY table_name ASC', [$database_name]);

$sql_all_admin_fields = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', '', [], 'column_name');

$sql_all_admin_pages = $results->getSelectMultipleRecordsOneColumn(__LINE__, __FILE__, 'table_name', 'admin_pages', 'WHERE `type` = ?', ['table'], 'table_name');

if(!empty($sql_all_admin_users))
{
	foreach($sql_all_admin_users as $admin_user_id)
	{
		$sql_all_assigned_fields = $results->getSelectMultipleRecordsKeyNameTwo(__LINE__, __FILE__, '*', 'assigned_fields', 'WHERE `user_id` = ?', [$admin_user_id], 'table_name', 'table_name');
		
		if(!empty($sql_all_database_tables))
		{
			foreach($sql_all_database_tables as $sql_all_database_table)
			{
				//If no columns assign for user, assign all columns as its likely a new table installed. Leave existing alone so we dont change existing column sort orders that users have set.
				if(!in_array($sql_all_database_table['table_name'], $sql_all_assigned_fields))
				{
					if(!empty($sql_all_database_table['table_name']))
					{
						$sql_all_column_names_in_table = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, '`column_name`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `ordinal_position` ASC', [$database_name, $sql_all_database_table['table_name']], 'column_name');
						
						if(!empty($sql_all_column_names_in_table) && in_array($sql_all_database_table['table_name'], $sql_all_admin_pages))
						{
							$counter = 1;
							
							//Add URL table columns with parent table has urls_id column in it. When parent table has urls_id in it, it means it runs with urls to load on frontend of the site.
							if(in_array('urls_id', $sql_all_column_names_in_table))
							{
								$sql_all_urls_columns = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, '`column_name`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `ordinal_position` ASC', [$database_name, 'urls'], 'column_name');
								
								foreach($sql_all_urls_columns as $sql_all_urls_column)
								{
									$parameters[] = array(NULL, 0, $admin_user_id, $sql_all_admin_fields[$sql_all_urls_column]['id'], $sql_all_database_table['table_name'], 'default', $counter);
									
									$counter ++;
								}
							}
							
							//Add orders_ship_to columns when table is orders so users can search by the shipping information on the order within orders.
							if($sql_all_database_table['table_name'] == 'orders')
							{
								$sql_all_orders_ship_to = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, '`column_name`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `ordinal_position` ASC', [$database_name, 'orders_ship_to'], 'column_name');
								
								foreach($sql_all_orders_ship_to as $all_orders_ship_to)
								{
									$parameters[] = array(NULL, 0, $admin_user_id, $sql_all_admin_fields[$all_orders_ship_to]['id'], $sql_all_database_table['table_name'], 'default', $counter);
									
									$counter ++;
								}
							}
							
							foreach($sql_all_column_names_in_table as $sql_column_name)
							{
								if(($sql_column_name == 'id' || $sql_column_name == 'site_id') && (in_array('urls_id', $sql_all_column_names_in_table) || $sql_all_database_table['table_name'] == 'orders'))
								{
									//Skip id and site_id columns when the urls or orders_ship_to tables have already added them above.
									continue;
								}
								
								$parameters[] = array(NULL, 0, $admin_user_id, $sql_all_admin_fields[$sql_column_name]['id'], $sql_all_database_table['table_name'], 'default', $counter);
								
								$counter ++;
							}
						}
					}
				}
			}
			
			$column_names = '`id`, `site_id`, `user_id`, `field_id`, `table_name`, `default_or_custom`, `sort`';
			$placeholders = '?,?,?,?,?,?,?';
			
			$results->getInsertMultipleRecords(__LINE__, __FILE__, 'assigned_fields', $column_names, $placeholders, $parameters);
		}
	}
}