<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//WARNING ABOUT INDEXED TEXT COLUMNS:
//If a column uses a data type such as TEXT, LONGTEXT, or BLOB and has an index, changing its data type is risky and error-prone. In those cases, you would need to:
//  1. Drop the existing index.
//  2. Change the column's data type.
//  3. Recreate the index, making sure to apply an index size limit.
//
//This update script does NOT currently handle that process automatically.
//Therefore, never attempt to change column data types through this update.
//Always create a new column instead.
//
//ALSO...
//This script does NOT calculate index sizes.
//All default Ratals tables use InnoDB, which supports indexes up to 3072 bytes.
//All columns are installed as utf8mb4 (4 bytes per character).
//Therefore, no index should exceed 3072 / 4 = 768 characters.
//
//Default Ratals indexes comply with these limits, but custom user-created indexes may cause issues if they do not follow these rules.
//In the future, we may extend this logic to safely handle data type changes and calculate index sizes to reduce updating errors.

//SAFETY FALLBACK for writeToInstallLog()
if(!function_exists('writeToInstallLog'))
{
	//Function to create install log changes and log any errors on update.
	function writeToInstallLog($log_content)
	{
		$log_file = $_SERVER['DOCUMENT_ROOT'].'/software-update-log.txt';
		
		//Create file if it doesn't exist
		if(!file_exists($log_file))
		{
			file_put_contents($log_file, '');
		}
		
		date_default_timezone_set($_SESSION['timezone'] ?? 'UTC');
		$timestamp = date('M. d, Y - h:i:s A');
		$formatted_message = '['.$timestamp.'] '.$log_content.PHP_EOL.PHP_EOL; //PHP_EOL adds line breaks
		
		file_put_contents($log_file, $formatted_message, FILE_APPEND | LOCK_EX);
	}
}

if(file_exists($temp_extract_dir.'/admin/cms/installer/database/tables/index.php'))
{
	//Set to update database tables
	$install_database_tables = 'No';
	$update_database_tables = 'Yes';
	
	$tables_processed = array();
	$database_tables_packages = array();
	
	//Process highest level package folders first as code below skips lower package folders as they have already been processed.
	//Database table update files contain all database columns up to the package level.
	
	if(file_exists($temp_extract_dir.'/admin/ai/installer/database/tables/index.php'))
	{
		$database_tables_packages[] = 'ai';
	}
	
	if(file_exists($temp_extract_dir.'/admin/erp/installer/database/tables/index.php'))
	{
		$database_tables_packages[] = 'erp';
	}
	
	if(file_exists($temp_extract_dir.'/admin/commerce/installer/database/tables/index.php'))
	{
		$database_tables_packages[] = 'commerce';
	}
	
	$database_tables_packages[] = 'cms';
	
	foreach($database_tables_packages as $database_tables_package)
	{
		//This hold the database table columns for each table being installed
		//Reset so the next package tables and columns are not added to the last package tables and columns.
		$update_table_columns = array();
		$update_table_keys = array();
		
		include($temp_extract_dir.'/admin/'.$database_tables_package.'/installer/database/tables/index.php');
		
		//////////ADD NEW DATABASE TABLE COLUMNS INSTALLED WITH UPDATE AND UPDATE ANY EXISTING COLUMNS THAT HAVE BEEN MODIFIED. IF TABLE HAS NOT CHANGED THIS WILL NOT RUN ON THAT TABLE.//////////
		if(isset($update_table_columns) && !empty($update_table_columns))
		{
			//Loop through core table coloumns
			foreach($update_table_columns as $db_core_table_name => $db_core_table_values)
			{
				//Skip if already handled by a higher package.
				if(isset($tables_processed[$db_core_table_name]))
				{
					continue;
				}
				
				//Mark as processed.
				$tables_processed[$db_core_table_name] = true;
				
				$db_core_table_values = strtolower(json_encode($db_core_table_values));
				$db_core_table_values = json_decode($db_core_table_values, true);
				
				//Get current table columns to compare for differences.
				$current_database_columns = $results_schema->getSchemaSelectMultipleRecordsKeyName(__LINE__, __FILE__, '`column_name`, `column_type`, `character_set_name`, `collation_name`, `extra`, `column_key`, `is_nullable`, `column_default`, `ordinal_position`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `ORDINAL_POSITION` ASC', [$_SESSION['site_db_name'], $db_core_table_name], 'COLUMN_NAME');
				
				//Normalize current DB columns.
				if(!empty($current_database_columns))
				{
					$current_database_columns = strtolower(json_encode($current_database_columns));
					$current_database_columns = json_decode($current_database_columns, true);
					
					foreach($current_database_columns as $current_column_key => $current_database_column)
					{
						if($current_database_columns[$current_column_key]['column_key'] != 'pri')
						{
							$current_database_columns[$current_column_key]['column_key'] = '';
						}
					}
				}
				
				//Build map of where custom columns belong (relative to existing DB order).
				$custom_after_map = array();
				$last_seen_core = '';
				
				foreach($current_database_columns as $col => $data)
				{
					if(!isset($db_core_table_values[$col]))
					{
						$custom_after_map[$last_seen_core][] = $col;
					}
					else
					{
						$last_seen_core = $col;
					}
				}
				
				//Build final ordered column list.
				$new_database_columns = array();
				
				//Custom columns that appeared BEFORE first core column.
				if(isset($custom_after_map['']))
				{
					foreach($custom_after_map[''] as $custom_col)
					{
						$new_database_columns[$custom_col] = $current_database_columns[$custom_col];
					}
				}
				
				//Loop schema in correct order.
				foreach($db_core_table_values as $col => $schema_def)
				{
					//Use schema definition if exists, otherwise fallback to DB.
					if(isset($current_database_columns[$col]))
					{
						//If changed, use schema version, else keep DB version.
						if($current_database_columns[$col] != $schema_def)
						{
							$new_database_columns[$col] = $schema_def;
						}
						else
						{
							$new_database_columns[$col] = $current_database_columns[$col];
						}
					}
					else
					{
						//New column in schema.
						$new_database_columns[$col] = $schema_def;
					}
					
					//Add any custom columns that originally followed this column.
					if(isset($custom_after_map[$col]))
					{
						foreach($custom_after_map[$col] as $custom_col)
						{
							$new_database_columns[$custom_col] = $current_database_columns[$custom_col];
						}
					}
				}
				
				//Set ordinal positions (for comparison only).
				if(!empty($new_database_columns))
				{
					$column_counter = 1;
					foreach($new_database_columns as $new_database_column_name => $new_database_column_values)
					{
						$new_database_columns[$new_database_column_name]['ordinal_position'] = $column_counter;
						$column_counter++;
					}
				}
				
				//If current database columns are not the same as the new columns, update the columns in the database table.
				if($current_database_columns != $new_database_columns)
				{
					$last_column_name_modified = '';
					
					//Data types that do not need a default set, so skip these when adding a new column to a table.
					$data_types_flip_is_nullable = ['text','tinytext','mediumtext','longtext','blob','tinyblob','mediumblob','longblob'];
					$numeric_types = ['int','tinyint','smallint','mediumint','bigint','decimal','float','double'];
					
					foreach($new_database_columns as $new_database_column_key => $new_database_column)
					{
						$table_columns_to_update = '';
						
						if(isset($new_database_column['column_name']) && !empty($new_database_column['column_name']))
						{
							//Add table column.
							if(!isset($current_database_columns[$new_database_column_key]))
							{
								$table_columns_to_update .= '`'.$new_database_column['column_name'].'` ';
								$table_columns_to_update .= $new_database_column['column_type'].' '; //Data Type
								if(!empty($new_database_column['character_set_name']))
								{
									$table_columns_to_update .= 'character set '.$new_database_column['character_set_name'].' ';
								}
								if(!empty($new_database_column['collation_name']))
								{
									$table_columns_to_update .= 'collate '.$new_database_column['collation_name'].' ';
								}
								if(!in_array($new_database_column['column_type'], $data_types_flip_is_nullable))
								{
									//Set if column can be null or not
									if($new_database_column['is_nullable'] == 'yes')
									{
										$table_columns_to_update .= 'null ';
									}
									elseif($new_database_column['is_nullable'] == 'no')
									{
										$table_columns_to_update .= 'not null ';
									}
									//set default value for column
									if(!empty($new_database_column['column_default']) && strtolower($new_database_column['column_default']) == 'current_timestamp')
									{
										$table_columns_to_update .= 'default current_timestamp ';
									}
									elseif(empty($new_database_column['column_default']) && (strtolower($new_database_column['column_type']) == 'date' || strtolower($new_database_column['column_type']) == 'datetime'))
									{
										$table_columns_to_update .= 'default null ';
									}
									elseif(!empty($new_database_column['column_default']) && !is_numeric($new_database_column['column_default']))
									{
										$table_columns_to_update .= "default '".$new_database_column['column_default']."' ";
									}
									elseif(empty($new_database_column['column_default']) && (strpos(strtolower($new_database_column['column_type']), 'char') !== false || strpos(strtolower($new_database_column['column_type']), 'varchar') !== false))
									{
										$table_columns_to_update .= 'default "" ';
									}
									elseif($new_database_column['is_nullable'] === 'yes' && ($new_database_column['column_default'] === null || (is_string($new_database_column['column_default']) && strtolower($new_database_column['column_default']) === 'null')))
									{
										$table_columns_to_update .= 'default null ';
									}
									elseif(is_numeric($new_database_column['column_default']))
									{
										$table_columns_to_update .= 'default '.$new_database_column['column_default'].' ';
									}
								}
								if(in_array(strtolower($new_database_column['column_type']), $numeric_types) && $new_database_column['is_nullable'] == 'no' && strpos($table_columns_to_update, 'default') === false && $new_database_column['extra'] != 'auto_increment') 
								{
									$table_columns_to_update .= 'default 0 ';
								}
								if($new_database_column['extra'] == 'auto_increment')
								{
									$table_columns_to_update .= 'auto_increment ';
								}
								if($new_database_column['column_key'] == 'pri')
								{
									$table_columns_to_update .= 'primary key ';
								}
								if(empty($last_column_name_modified))
								{
									$table_columns_to_update .= 'first';
								}
								else
								{
									$table_columns_to_update .= 'after `'.$last_column_name_modified.'`';
								}
								
								try
								{
									//Fast, non-blocking add column
									writeToInstallLog('ALTER TABLE `'.$db_core_table_name.'` ADD '.$table_columns_to_update.', ALGORITHM=INPLACE, LOCK=NONE;');
									$results->getAlterDatabaseTable(__LINE__, __FILE__, $db_core_table_name, 'ADD '.$table_columns_to_update.', ALGORITHM=INPLACE, LOCK=NONE');
								}
								catch(\PDOException $e)
								{
									writeToInstallLog('Fast add failed for table name: '.$db_core_table_name.' > column name: '.$new_database_column['column_name'].': '.$e->getMessage());
									
									try
									{
										writeToInstallLog('Fallback:  ALTER TABLE `'.$db_core_table_name.'` ADD '.$table_columns_to_update.';');
										//If fast, non-blocking add fails, fall back
										$results->getAlterDatabaseTable(__LINE__, __FILE__, $db_core_table_name, 'ADD '.$table_columns_to_update);
									}
									catch(\PDOException $e2)
									{
										//Log and RE-THROW the exception to let outer catch handle it in update.php
										writeToInstallLog('Fallback failed: '.$e2->getMessage());
										throw $e2;
									}
									
								}
								
								$last_column_name_modified = $new_database_column['column_name'];
							}
							//Modify the table column if the column has changed.
							elseif(isset($db_core_table_values[$new_database_column_key]) && isset($current_database_columns[$new_database_column_key]) && $current_database_columns[$new_database_column_key] != $db_core_table_values[$new_database_column_key])
							{
								$table_columns_to_update .= '`'.$new_database_column['column_name'].'` ';
								$table_columns_to_update .= $new_database_column['column_type'].' '; //Data Type
								if(!empty($new_database_column['character_set_name']))
								{
									$table_columns_to_update .= 'character set '.$new_database_column['character_set_name'].' ';
								}
								if(!empty($new_database_column['collation_name']))
								{
									$table_columns_to_update .= 'collate '.$new_database_column['collation_name'].' ';
								}
								if(!in_array($new_database_column['column_type'], $data_types_flip_is_nullable))
								{
									//Set if column can be null or not
									if($new_database_column['is_nullable'] == 'yes')
									{
										$table_columns_to_update .= 'null ';
									}
									elseif($new_database_column['is_nullable'] == 'no')
									{
										$table_columns_to_update .= 'not null ';
									}
									//set default value for column
									if(!empty($new_database_column['column_default']) && strtolower($new_database_column['column_default']) == 'current_timestamp')
									{
										$table_columns_to_update .= 'default current_timestamp ';
									}
									elseif(empty($new_database_column['column_default']) && (strtolower($new_database_column['column_type']) == 'date' || strtolower($new_database_column['column_type']) == 'datetime'))
									{
										$table_columns_to_update .= 'default null ';
									}
									elseif(!empty($new_database_column['column_default']) && !is_numeric($new_database_column['column_default']))
									{
										$table_columns_to_update .= "default '".$new_database_column['column_default']."' ";
									}
									elseif(empty($new_database_column['column_default']) && (strpos(strtolower($new_database_column['column_type']), 'char') !== false || strpos(strtolower($new_database_column['column_type']), 'varchar') !== false))
									{
										$table_columns_to_update .= 'default "" ';
									}
									elseif($new_database_column['is_nullable'] === 'yes' && ($new_database_column['column_default'] === null || (is_string($new_database_column['column_default']) && strtolower($new_database_column['column_default']) === 'null')))
									{
										$table_columns_to_update .= 'default null ';
									}
									elseif(is_numeric($new_database_column['column_default']))
									{
										$table_columns_to_update .= 'default '.$new_database_column['column_default'].' ';
									}
								}
								if(in_array(strtolower($new_database_column['column_type']), $numeric_types) && $new_database_column['is_nullable'] == 'no' && strpos($table_columns_to_update, 'default') === false && $new_database_column['extra'] != 'auto_increment') 
								{
									$table_columns_to_update .= 'default 0 ';
								}
								if($new_database_column['extra'] == 'auto_increment')
								{
									$table_columns_to_update .= 'auto_increment ';
								}
								if(empty($last_column_name_modified))
								{
									$table_columns_to_update .= 'first';
								}
								else
								{
									$table_columns_to_update .= 'after `'.$last_column_name_modified.'`';
								}
								
								try
								{
									//Fast, non-blocking modify column
									writeToInstallLog('ALTER TABLE `'.$db_core_table_name.'` MODIFY '.$table_columns_to_update.', ALGORITHM=INPLACE, LOCK=NONE;');
									$results->getAlterDatabaseTable(__LINE__, __FILE__, $db_core_table_name, 'MODIFY '.$table_columns_to_update.', ALGORITHM=INPLACE, LOCK=NONE');
								}
								catch(\PDOException $e)
								{
									writeToInstallLog('Fast modify failed for table name: '.$db_core_table_name.' > column name: '.$new_database_column['column_name'].': '.$e->getMessage());
									
									try
									{
										writeToInstallLog('Fallback: ALTER TABLE `'.$db_core_table_name.'` MODIFY '.$table_columns_to_update.';');
										//If fast, non-blocking modify fails, fall back
										$results->getAlterDatabaseTable(__LINE__, __FILE__, $db_core_table_name, 'MODIFY '.$table_columns_to_update);
									}
									catch(\PDOException $e2)
									{
										//Log and RE-THROW the exception to let outer catch handle it in update.php
										writeToInstallLog('Fallback failed: '.$e2->getMessage());
										throw $e2;
									}
								}
								
								$last_column_name_modified = $new_database_column['column_name'];
							}
							else
							{
								//Skip unchanged + custom columns.
								$last_column_name_modified = $new_database_column['column_name'];
								continue;
							}
						}
					}
				}
			}
		}
		
		//Get all tables in the database to compare with current tables.
		$current_database_tables = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, '`TABLE_NAME`', 'tables', 'WHERE table_schema = ? ORDER BY table_name ASC', [$_SESSION['site_db_name']], 'TABLE_NAME');
		
		//////////REMOVE ALL KEYS/INDEXS THAT HAVE CHANGED, THEN ADD BACK WITH CHANGES PLUS NEW KEYS/INDEXS - WILL ONLY CHANGE RATALS DEFAULT INDEXES STARTING WITH 'ratals_'//////////
		if(isset($update_table_keys) && !empty($update_table_keys))
		{
			//Loop through update keys array
			foreach($update_table_keys as $db_core_table_name => $db_core_table_values)
			{
				//If table exist, check if keys needs to be installed or updated
				if(in_array($db_core_table_name, $current_database_tables))
				{
					$db_core_table_values = strtolower(json_encode($db_core_table_values));
					$db_core_table_values = json_decode($db_core_table_values, true);
					
					$current_database_keys = $results_schema->getSchemaSelectMultipleRecordsKeyNameArray(__LINE__, __FILE__, '`index_name`, `non_unique`, `seq_in_index`, `column_name`, `index_type`', 'statistics', 'WHERE `table_schema` = ? AND `table_name` = ? AND `index_name` != ? AND index_name LIKE ? ORDER BY `index_name` ASC, `seq_in_index`', [$_SESSION['site_db_name'], $db_core_table_name, 'PRIMARY', 'ratals_%'], 'INDEX_NAME');
					
					$current_database_keys = strtolower(json_encode($current_database_keys));
					$current_database_keys = json_decode($current_database_keys, true);
					
					//Create list of keys/indexes that have changed
					$keys_to_delete = array();
					if(!empty($current_database_keys))
					{
						foreach($current_database_keys as $key => $array_values)
						{
							if(!array_key_exists($key, $db_core_table_values))
							{
								$keys_to_delete[] = $key;
								continue;
							}
							
							if($array_values != $db_core_table_values[$key])
							{
								$keys_to_delete[] = $key;
							}
						}
					}
					
					//Delete keys/indexes that have changed
					if(!empty($keys_to_delete))
					{
						foreach($keys_to_delete as $key_name_to_delete)
						{
							try
							{
								//Fast, non-blocking drop index
								writeToInstallLog('ALTER TABLE `'.$db_core_table_name.'` DROP INDEX `'.$key_name_to_delete.'`, ALGORITHM=INPLACE, LOCK=NONE;');
								$results->getAlterDatabaseTable(__LINE__, __FILE__, $db_core_table_name, 'DROP INDEX `'.$key_name_to_delete.'`, ALGORITHM=INPLACE, LOCK=NONE');
							}
							catch(\PDOException $e)
							{
								writeToInstallLog('Fast drop index failed for table name: '.$db_core_table_name.' > index name: `'.$key_name_to_delete.'`: '.$e->getMessage());
								
								try
								{
									writeToInstallLog('Fallback: ALTER TABLE `'.$db_core_table_name.'` DROP INDEX `'.$key_name_to_delete.'`;');
									//If fast, non-blocking drop index fails, fall back
									$results->getAlterDatabaseTable(__LINE__, __FILE__, $db_core_table_name, 'DROP INDEX `'.$key_name_to_delete.'`');
								}
								catch(\PDOException $e2)
								{
									//Log and RE-THROW the exception to let outer catch handle it in update.php
									writeToInstallLog('Fallback failed: '.$e2->getMessage());
									throw $e2;
								}
							}
						}
						
						//Re-get keys/indexes for this table after old keys have been delete.
						$current_database_keys = $results_schema->getSchemaSelectMultipleRecordsKeyNameArray(__LINE__, __FILE__, '`index_name`, `non_unique`, `seq_in_index`, `column_name`, `index_type`', 'statistics', 'WHERE `table_schema` = ? AND `table_name` = ? AND `index_name` != ? AND index_name LIKE ? ORDER BY `index_name` ASC, `seq_in_index`', [$_SESSION['site_db_name'], $db_core_table_name, 'PRIMARY', 'ratals_%'], 'INDEX_NAME');
						
						$current_database_keys = strtolower(json_encode($current_database_keys));
						$current_database_keys = json_decode($current_database_keys, true);
					}
					
					//Create list of keys/indexes to add
					$keys_to_add = array();
					if(!empty($db_core_table_values))
					{
						foreach($db_core_table_values as $key => $values)
						{
							if(!array_key_exists($key, $current_database_keys))
							{
								$keys_to_add[] = $key;
							}
						}
					}
					
					//Add keys/indexes that have changed or that are new
					if(!empty($keys_to_add))
					{
						foreach($keys_to_add as $key_name_to_add)
						{
							$unique_key = '';
							$key_columns = array();
							$index_type = 'BTREE';
							
							if(!empty($db_core_table_values[$key_name_to_add]))
							{
								foreach($db_core_table_values[$key_name_to_add] as $new_key_to_add)
								{
									if($new_key_to_add['non_unique'] == 0)
									{
										$unique_key = 'UNIQUE';
									}
									
									$key_columns[] = '`'.$new_key_to_add['column_name'].'`';
									
									if(!empty($new_key_to_add['index_type']))
									{
										$index_type = strtoupper($new_key_to_add['index_type']);
									}
								}
								
								$key_columns = implode(',', $key_columns);
								
								try
								{
									//Fast, non-blocking add index
									writeToInstallLog('ALTER TABLE `'.$db_core_table_name.'` ADD '.$unique_key.' INDEX `'.$key_name_to_add.'` ('.$key_columns.') USING '.$index_type.', ALGORITHM=INPLACE, LOCK=NONE;');
									$results->getAlterDatabaseTable(__LINE__, __FILE__, $db_core_table_name, 'ADD '.$unique_key.' INDEX `'.$key_name_to_add.'` ('.$key_columns.') USING '.$index_type.', ALGORITHM=INPLACE, LOCK=NONE');
								}
								catch(\PDOException $e)
								{
									writeToInstallLog('Fast add index failed for table name: '.$db_core_table_name.' > index name: '.$key_name_to_add.': '.$e->getMessage());
									
									try
									{
										writeToInstallLog('Fallback: ALTER TABLE `'.$db_core_table_name.'` ADD '.$unique_key.' INDEX `'.$key_name_to_add.'` ('.$key_columns.') USING '.$index_type.';');
										//If fast, non-blocking add index fails, fall back
										$results->getAlterDatabaseTable(__LINE__, __FILE__, $db_core_table_name, 'ADD '.$unique_key.' INDEX `'.$key_name_to_add.'` ('.$key_columns.') USING '.$index_type);
									}
									catch(\PDOException $e2)
									{
										//Log and RE-THROW the exception to let outer catch handle it in update.php
										writeToInstallLog('Fallback failed: '.$e2->getMessage());
										throw $e2;
									}
								}
							}
						}
					}
				}
			}
		}
		
		//////////INSTALL ANY NEW DATABASE TABLES NOT ALREADY INSTALLED//////////
		if(isset($current_database_table_names) && !empty($current_database_table_names))
		{
			foreach($current_database_table_names as $current_database_table_name)
			{
				//If table does not exist, install it
				if(!in_array($current_database_table_name, $current_database_tables))
				{
					if(file_exists($temp_extract_dir.'/admin/'.$database_tables_package.'/installer/database/tables/'.$current_database_table_name.'.php'))
					{
						writeToInstallLog('Installing new database table: '.$current_database_table_name);
						include($temp_extract_dir.'/admin/'.$database_tables_package.'/installer/database/tables/'.$current_database_table_name.'.php');
						writeToInstallLog('Successfully installed database table: '.$current_database_table_name);
					}
				}
			}
		}
	}
}

//////////UNSET ALL VARIABLES USED TO INSTALLAND AND UPDATE TABLES.//////////
//Install variables
unset($install_database_tables);
unset($database_table_name);
unset($current_database_table_names);
unset($table_columns);
unset($table_setup);
//Update variables
unset($update_database_tables);
unset($update_table_columns);
unset($column_counter);
unset($update_table_keys);