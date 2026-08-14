<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/build-database-table-create-query.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/build-database-table-create-query.php');
}
else
{
	//Build database table query to create the table.
	if(!function_exists('buildDatabaseTableCreateQuery'))
	{
		function buildDatabaseTableCreateQuery($database_table_name, $table_schema, $keys_schema)
		{
			$install_table = '';
			
			if(!empty($table_schema))
			{
				$primary_key = '';
				
				//Data types that do not need a default set, so skip these when adding a new column to a table.
				$data_types_flip_is_nullable = ['text','tinytext','mediumtext','longtext','blob','tinyblob','mediumblob','longblob'];
				$numeric_types = ['int','tinyint','smallint','mediumint','bigint','decimal','float','double'];
				
				foreach($table_schema as $table_columns)
				{
					$install_table .= '`'.$table_columns['column_name'].'` ';
					
					$install_table .= $table_columns['column_type'].' '; //Data Type
					
					if(!empty($table_columns['character_set_name']))
					{
						$install_table .= 'character set '.$table_columns['character_set_name'].' ';
					}
					
					if(!empty($table_columns['collation_name']))
					{
						$install_table .= 'collate '.$table_columns['collation_name'].' ';
					}
					
					if(!in_array($table_columns['column_type'], $data_types_flip_is_nullable))
					{
						//Set if column can be null or not
						if($table_columns['is_nullable'] == 'yes')
						{
							$install_table .= 'null ';
						}
						elseif($table_columns['is_nullable'] == 'no')
						{
							$install_table .= 'not null ';
						}
						
						//set default value for column
						if(!empty($table_columns['column_default']) && strtolower($table_columns['column_default']) == 'current_timestamp')
						{
							$install_table .= 'default current_timestamp ';
						}
						elseif(empty($table_columns['column_default']) && (strtolower($table_columns['column_type']) == 'date' || strtolower($table_columns['column_type']) == 'datetime'))
						{
							$install_table .= 'default null ';
						}
						elseif(!empty($table_columns['column_default']) && !is_numeric($table_columns['column_default']))
						{
							$install_table .= "default '".$table_columns['column_default']."' ";
						}
						elseif(empty($table_columns['column_default']) && (strpos(strtolower($table_columns['column_type']), 'char') !== false || strpos(strtolower($table_columns['column_type']), 'varchar') !== false))
						{
							$install_table .= 'default "" ';
						}
						elseif($table_columns['is_nullable'] === 'yes' && ($table_columns['column_default'] === null || (is_string($table_columns['column_default']) && strtolower($table_columns['column_default']) === 'null')))
						{
							$install_table .= 'default null ';
						}
						elseif(is_numeric($table_columns['column_default']))
						{
							$install_table .= 'default '.$table_columns['column_default'].' ';
						}
					}
					
					if(in_array(strtolower($table_columns['column_type']), $numeric_types) && $table_columns['is_nullable'] == 'no' && strpos($install_table, 'default') === false && $table_columns['extra'] != 'auto_increment') 
					{
						$install_table .= 'default 0 ';
					}
					
					if($table_columns['extra'] == 'auto_increment')
					{
						$install_table .= 'auto_increment ';
					}
					
					$install_table .= ', ';
					
					if($table_columns['column_key'] == 'pri')
					{
						$primary_key = ' primary key (`'.$table_columns['column_name'].'`) , '; 
					}
					
					/*
					$install_table .= '`'.$table_columns['column_name'].'` ';
					
					$install_table .= $table_columns['column_type'].' ';
					
					if(!empty($table_columns['character_set_name']))
					{
						$install_table .= 'CHARACTER SET '.$table_columns['character_set_name'].' ';
					}
					
					if(!empty($table_columns['collation_name']))
					{
						$install_table .= 'COLLATE '.$table_columns['collation_name'].' ';
					}
					
					$colType = strtolower($table_columns['column_type']);
					$is_numeric_type = preg_match('/\b(int|decimal|float|double|tinyint|bigint)\b/', $colType);
					
					if($table_columns['is_nullable'] == 'yes')
					{
						$install_table .= 'NULL DEFAULT NULL ';
					}
					else
					{
						$install_table .= 'NOT NULL ';
						
						if(strpos($table_columns['extra'], 'auto_increment') === false)
						{
							if($table_columns['column_default'] !== '')
							{
								if($is_numeric_type)
								{
									$install_table .= "DEFAULT ".$table_columns['column_default']." ";
								}
								else
								{
									$install_table .= "DEFAULT '".addslashes($table_columns['column_default'])."' ";
								}
							}
							elseif(strpos($colType, 'varchar') !== false || strpos($colType, 'char') !== false)
							{
								$install_table .= "DEFAULT '' ";
							}
							elseif($is_numeric_type)
							{
								$install_table .= "DEFAULT 0 ";
							}
						}
					}
					
					if(!empty($table_columns['extra']))
					{
						$install_table .= $table_columns['extra'].' ';
					}
					
					$install_table = trim($install_table);
					$install_table .= ', ';
					
					if($table_columns['column_key'] == 'pri')
					{
						$primary_key = ' primary key (`'.$table_columns['column_name'].'`), '; 
					}
					*/
				}
				
				$install_table .= $primary_key;
				
				if(!empty($keys_schema))
				{
					$install_keys = '';
					
					foreach($keys_schema as $key_name => $keys)
					{
						$install_key = '';
						
						foreach($keys as $key)
						{
							$install_key .= '`'.$key['column_name'].'`, ';
						}
						
						$install_key = trim($install_key, ', ');
						
						$install_keys .= 'key `'.$key_name.'` ('.$install_key.') , ';
					}
					
					$install_keys = trim($install_keys, ' , ');
					
					$install_table .= $install_keys;
				}
			}
			
			$install_table = rtrim($install_table, ', ');
			
			return $install_table;
		}
	}
}