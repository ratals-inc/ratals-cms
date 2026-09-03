<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/add/includes/insert-data-after.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/add/includes/insert-data-after.php');
}
else
{
	if(!empty($_POST) && empty($errors) && !isset($_POST['change_site']))
	{
		//If a Post Page is being inserted, insert assignments_posts table so the Post is displaying in the correct blog categories that were selected.
		if($_SESSION['admin_table_name'] == 'posts')
		{
			if(isset($post_values['posts']['display_post_in']) && !empty($post_values['posts']['display_post_in']))
			{
				$display_post_in_array = trim($post_values['posts']['display_post_in'] ?? '', ',');
			
				if(strpos($display_post_in_array, ',') !== false)
				{
					$posts_assigments_updates = explode(',', $display_post_in_array);
				}
				else
				{
					$posts_assigments_updates[] = $display_post_in_array;
				}
				
				//Get last urls id created.
				$last_inerted_urls_id = $results->getSelectSingleRecord(__LINE__, __FILE__, '`id`', 'urls', 'WHERE `site_id` = ? AND `table_name` = ? ORDER BY `id` DESC LIMIT 1', [$_SESSION["site_set_for_editing"], 'posts']);
				
				foreach($posts_assigments_updates as $posts_assigments_update)
				{
					$column_names = '`id`, `site_id`, `parent_id`, `child_id`, `created_date`';
					$column_placeholders = 'NULL, ?, ?, ?, UTC_TIMESTAMP()';
					$column_value = array($_SESSION["site_set_for_editing"], $posts_assigments_update, $last_inerted_urls_id['id']);
					
					$results->getInsertRecord(__LINE__, __FILE__, 'assignments_posts', $column_names, $column_placeholders, $column_value);
				}
			}
		}
		
		if($_SESSION['admin_table_name'] == 'templates' && isset($post_values['templates']['directory_folder_name']) && !empty($post_values['templates']['directory_folder_name'])  )
		{
			if(!file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$post_values['templates']['directory_folder_name']))
			{
				mkdir(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$post_values['templates']['directory_folder_name'], 0777, true);
			}
		}
		
		if(isset($posted_template_file_name) && !empty($posted_template_file_name) && isset($posted_template_file_code))
		{
			$active_template = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `id` = ? AND site_id = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
			
			if(isset($active_template) && !empty($active_template['directory_folder_name']))
			{
				if(!file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']))
				{
					mkdir(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name'], 0777, true);
				}
		
				$myfile = fopen(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$posted_template_file_name, "w");
				fwrite($myfile, $posted_template_file_code);
				fclose($myfile);
			}
		}
		
		if($_SESSION['admin_table_name'] == 'database_tables' && isset($post_values['database_tables']['database_table_name']) && !empty($post_values['database_tables']['database_table_name']) && isset($post_values['database_tables']['admin_fields_ids']) && !empty($post_values['database_tables']['admin_fields_ids']))
		{
			$all_admin_fields_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', '', [], 'id');
			
			$database_columns_to_create = explode(',', trim($post_values['database_tables']['admin_fields_ids'], ','));
			
			//Data types that do not need a default set, so skip these when adding a new column to a table.
			$data_types_flip_is_nullable = ['text','tinytext','mediumtext','longtext','blob','tinyblob','mediumblob','longblob'];
			$numeric_types = ['int','tinyint','smallint','mediumint','bigint','decimal','float','double'];
			
			$table_columns = '';
			
			foreach($database_columns_to_create as $database_column_to_create)
			{
				$table_columns .= '`'.$all_admin_fields_array[$database_column_to_create]['column_name'].'` ';
				$table_columns .= $all_admin_fields_array[$database_column_to_create]['data_type'].' ';
				if(!empty($all_admin_fields_array[$database_column_to_create]['character_set_and_collate']))
				{
					$table_columns .= $all_admin_fields_array[$database_column_to_create]['character_set_and_collate'].' ';
				}
				if(!in_array(strtolower($all_admin_fields_array[$database_column_to_create]['data_type']), $data_types_flip_is_nullable))
				{
					//Set if column can be null or not
					if($all_admin_fields_array[$database_column_to_create]['is_nullable'] == 'Yes')
					{
						$table_columns .= 'NULL ';
					}
					elseif($all_admin_fields_array[$database_column_to_create]['is_nullable'] == 'No')
					{
						$table_columns .= 'NOT NULL ';
					}
					//set default value for column
					if(strtolower($all_admin_fields_array[$database_column_to_create]['data_type']) == 'timestamp')
					{
						$table_columns .= 'default current_timestamp ';
					}
					elseif(strtolower($all_admin_fields_array[$database_column_to_create]['data_type']) == 'date' || strtolower($all_admin_fields_array[$database_column_to_create]['data_type']) == 'datetime')
					{
						$table_columns .= 'default null ';
					}
					elseif(strpos(strtolower($all_admin_fields_array[$database_column_to_create]['data_type']), 'char') !== false || strpos(strtolower($all_admin_fields_array[$database_column_to_create]['data_type']), 'varchar') !== false)
					{
						$table_columns .= 'default "" ';
					}
					elseif($all_admin_fields_array[$database_column_to_create]['is_nullable'] == 'Yes')
					{
						$table_columns .= 'default null ';
					}
				}
				if(in_array(strtolower($all_admin_fields_array[$database_column_to_create]['data_type']), $numeric_types) && $all_admin_fields_array[$database_column_to_create]['is_nullable'] == 'No' && strpos($table_columns, 'default') === false && $all_admin_fields_array[$database_column_to_create]['is_auto_increment'] == 'No') 
				{
					$table_columns .= 'default 0 ';
				}
				if($all_admin_fields_array[$database_column_to_create]['is_auto_increment'] == 'Yes')
				{
					$table_columns .= 'AUTO_INCREMENT ';
				}
				if($all_admin_fields_array[$database_column_to_create]['is_primary_key'] == 'Yes')
				{
					$table_columns .= 'PRIMARY KEY ';
				}
				//Add comma at end of each column to create long string of columns to add within the table this is creating.
				$table_columns .= ', ';
			}
			
			//Right trim commas so table create does not think there should be a next colun to add and give error.
			$table_columns = rtrim($table_columns, ', ');
			
			$results->getCreateDatabaseTable(__LINE__, __FILE__, $post_values['database_tables']['database_table_name'], $table_columns, '');
		}
		
		//When creating a new admin user add the default table columns to users account so they see all columns in table view in admin.
		if($_SESSION['admin_table_name'] == 'users' && isset($post_values['users']['username']) && !empty($post_values['users']['username']))
		{
			$parameters = array();
			$sql_new_users_id = $results->getSelectSingleRecord(__LINE__, __FILE__, '`id`', 'users', 'ORDER BY `id` DESC LIMIT 1', []);
			
			$sql_all_admin_fields = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', '', [], 'column_name');
			
			$sql_all_admin_pages = $results->getSelectMultipleRecordsOneColumn(__LINE__, __FILE__, 'table_name', 'admin_pages', 'WHERE `type` = ?', ['table'], 'table_name');
			
			$sql_all_database_tables = $results_schema->getSchemaSelectMultipleRecords(__LINE__, __FILE__, '`table_name`', 'tables', 'WHERE table_schema = ? ORDER BY table_name ASC', [$_SESSION['site_db_name']]);
			
			if(!empty($sql_all_database_tables))
			{
				foreach($sql_all_database_tables as $sql_all_database_table)
				{
					if(!empty($sql_all_database_table['table_name']))
					{
						$sql_all_column_names_in_table = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, '`column_name`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `ordinal_position` ASC', [$_SESSION['site_db_name'], $sql_all_database_table['table_name']], 'column_name');
						
						if(!empty($sql_all_column_names_in_table) && in_array($sql_all_database_table['table_name'], $sql_all_admin_pages))
						{
							$counter = 1;
							
							//Add URL table columns with parent table has urls_id column in it. When parent table has urls_id in it, it means it runs with urls to load on frontend of the site.
							if(in_array('urls_id', $sql_all_column_names_in_table))
							{
								$sql_all_urls_columns = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, '`column_name`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `ordinal_position` ASC', [$_SESSION['site_db_name'], 'urls'], 'column_name');
								
								foreach($sql_all_urls_columns as $sql_all_urls_column)
								{
									$parameters[] = array(NULL, 0, $sql_new_users_id['id'], $sql_all_admin_fields[$sql_all_urls_column]['id'], $sql_all_database_table['table_name'], 'default', $counter);
									
									$counter ++;
								}
							}
							
							foreach($sql_all_column_names_in_table as $sql_column_name)
							{
								$parameters[] = array(NULL, 0, $sql_new_users_id['id'], $sql_all_admin_fields[$sql_column_name]['id'], $sql_all_database_table['table_name'], 'default', $counter);
								
								$counter ++;
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
}