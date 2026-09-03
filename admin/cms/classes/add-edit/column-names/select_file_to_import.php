<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/select_file_to_import.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/select_file_to_import.php');
}
else
{
	if(!class_exists('select_file_to_import_aecn'))
	{
		class select_file_to_import_aecn
		{
			public function select_file_to_import_aecn($table_name, $admin_field, &$post_values, &$errors)
			{
				if($_SESSION['admin_table_name'] == 'import_data' && isset($_FILES['import_data']['name']['select_file_to_import']) && !empty($_FILES['import_data']['name']['select_file_to_import']) && isset($post_values[$table_name]['import_into_table']) && !empty($post_values[$table_name]['import_into_table']))
				{
					clearstatcache();
					
					$post_values[$table_name][$admin_field["column_name"]] = strtolower($_FILES['import_data']['name']['select_file_to_import']);
					
					$_POST[$table_name][$admin_field["column_name"]] = strtolower($_FILES['import_data']['name']['select_file_to_import']);
					
					$excel_file_path = $_FILES['import_data']['tmp_name']['select_file_to_import'];
					
					$import_file_name = explode('.', strtolower($_FILES['import_data']['name']['select_file_to_import']));
					
					if(end($import_file_name) == 'csv')
					{
						$csv_file_data = file_get_contents($excel_file_path);
						$csv_rows = array_map("rtrim", explode("\n", $csv_file_data));
						
						$count_header_columns = 0;
						$valid_parsed_rows = array();
						$bad_parsed_rows = array();
						
						foreach($csv_rows as $csv_row)
						{
							if(!empty($csv_row))
							{
								//Parse the row into an array
								$parsed_line = str_getcsv($csv_row, ',');
								
								//Get count of the first row which should be the header or columns importing into.
								if(empty($count_header_columns))
								{
									$count_header_columns = count($parsed_line);
								}
								
								//If the header column count matches the data column count it should have parse it correctly.
								if(count($parsed_line) == $count_header_columns)
								{
									$valid_parsed_rows[] = $parsed_line;
								}
								//If the header column count does not match the data row count there is a chance that a special charitors is causing the row to parse worng.
								else
								{
									$bad_parsed_rows[] = $parsed_line;
								}
							}
						}
						
						if($count_header_columns <= 1)
						{
							$errors[$table_name][$admin_field["column_name"]] = 'You must include the column of id plus one other column you\'re looking to update. If you\'re looking to insert new rows into the database table, all columns in the database table must be included in your csv and be in the same order as database table. Idea for you... Export the table you\'re looking to important into and use it as a template to get the columns/ordering structure correct.';
						}
						elseif(!empty($bad_parsed_rows))
						{
							$errors[$table_name][$admin_field["column_name"]] = 'These rows within your csv file could not be parse correctly. Please make sure they are not containing special characters that could import the data incorrectly.';
							$errors[$table_name][$admin_field["column_name"]] = $bad_parsed_rows;
						}
						elseif(count($valid_parsed_rows) < 2)
						{
							$errors[$table_name][$admin_field["column_name"]] = 'Please make sure you have added data rows to insert or update.';
						}
						elseif(!empty($valid_parsed_rows))
						{
							//Create query to inset and update.
							$export_table_setup = $_SESSION['results_schema']->getSchemaSelectSingleRecord(__LINE__, __FILE__, '*', 'tables', 'WHERE `table_schema` = ? AND `table_name` = ?', [$_SESSION['site_db_name'], $post_values[$table_name]['import_into_table']]);
							
							if(!empty($export_table_setup))
							{
								$export_table_columns = $_SESSION['results_schema']->getSchemaSelectMultipleRecords(__LINE__, __FILE__, '*', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `columns`.`ordinal_position` ASC', [$_SESSION['site_db_name'], $export_table_setup['table_name']]);
							}
							else
							{
								$errors[$table_name][$admin_field["column_name"]] = 'Database table name "admin_fields" does not exist. Please make sure to name your csv file with the database table you are looking to update. Example: my_table_name.csv.';
							}
							if(isset($export_table_columns) && !empty($export_table_columns))
							{
								$column_table_layout = array();
								//Make sure all headers are in csv in order to inset.
								$column_counter = 0;
								foreach($export_table_columns as $export_table_column)
								{
									$column_table_layout[] = $export_table_column['column_name'];
									
									if($export_table_column['column_name'] == 'id')
									{
										$id_column_count = $column_counter;
									}
									
									$column_counter ++;
								}
								
								if(!isset($id_column_count))
								{
									$errors[$table_name][$admin_field["column_name"]] = 'It looks like you\'re trying to import into a table that doesn\'t have a column id. Import is only available for tables that have a column id.';
								}
								
								//echo '<pre>'; print_r($header_columns); echo '</pre>';
								//echo '<pre>'; print_r($column_table_layout); echo '</pre>';
								
								$headers_columns_match = 'No';
								$header_columns = $valid_parsed_rows[0]; 
								$insert_columns = '';
								$insert_placeholders = '';
								$update_columns = '';
								$header_column_id_exist = 'No';
								
								if($header_columns == $column_table_layout)
								{
									$headers_columns_match = 'Yes';
								}
								
								$column_do_not_exist = array();
								foreach($header_columns as $header_column)
								{
									if(!in_array($header_column, $column_table_layout))
									{
										$column_do_not_exist[] = $header_column;
									}
								}
								
								if(!empty($column_do_not_exist))
								{
									$errors[$table_name][$admin_field["column_name"]] = 'Columns in your csv are not in the database table you\'re importing into. Make sure all column names in your csv are within the table you\'re importing into.';
									$errors[$table_name][$admin_field["column_name"]] = $column_do_not_exist;
								}
								
								$column_counter = 0;
								foreach($header_columns as $header_column)
								{
									$insert_columns .= '`'.$header_column.'`,';
									$insert_placeholders .= '?,';
									$update_columns .= '`'.$header_column.'` = ?,';
									
									if($header_column == 'id')
									{
										$header_column_id_exist = 'Yes';
										$csv_id_column_count = $column_counter;
									}
									
									$column_counter ++;
								}
								
								//Make sure column name id is in the csv.
								if($header_column_id_exist == 'No')
								{
									$errors[$table_name][$admin_field["column_name"]] = 'Make sure the id column is in your csv. If you\'re inserting rows, leave the id empty for each row. If you\'re updating rows, make sure the id is filled in with the database row id you\'re updating.';
								}
								
								$insert_columns = trim($insert_columns ?? '', ',');
								$insert_placeholders = trim($insert_placeholders ?? '', ',');
								$update_columns = trim($update_columns ?? '', ',');
								
								//Remove the first row in the array so the header is removed.
								array_shift($valid_parsed_rows);
								
								//Check if row is being inerted or updated. If ID column is empty, its an insert. If ID column is set, its an update.
								$insert_parameters = array();
								$update_parameters = array();
								foreach($valid_parsed_rows as $each_line)
								{
									$is_insert_item = '';
									foreach($each_line as $each_item)
									{
										//If no database table row id is set, this means the item will be inserted.
										if(!is_numeric($each_line[$id_column_count]) && empty($each_line[$id_column_count]))
										{
											$is_insert_item = 'Yes';
											$insert_parameter[] = $each_item;
										}
										//If a database table row id is set, this means the item will be updated.
										else
										{
											$is_insert_item = 'No';
											$update_parameter[] = $each_item;
										}
									}
									
									if($is_insert_item == 'No')
									{
										//On update, we have to add the id to the end of our parameters so the query knows which row to update in the database.
										//use $csv_id_column_count as id column can be anywhere in the csv file when only updating.
										$update_parameter[] = $update_parameter[$csv_id_column_count];
										$update_parameters[] = $update_parameter;
										$update_parameter = array();
									}
									elseif($is_insert_item == 'Yes')
									{
										//use $id_column_count as csv column order should match database table.
										$insert_parameter[$id_column_count] = NULL;
										$insert_parameters[] = $insert_parameter;
										$insert_parameter = array();
									}
									
									//break;
								}
								
								if(!empty($insert_parameters) && $headers_columns_match == 'No')
								{
									$errors[$table_name][$admin_field["column_name"]] = 'Your import was not executed because the columns in your csv file do not match the database table columns. It\'s must have every column in your csv and be in the same order as the database table columns when inserting new records into the database table. Idea for you... Export the table you\'re looking to important into and use it as a template to get the columns/ordering structure correct.';
								}
							}
						}
					}
					else
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Your import file must be a csv file with delimiters as commas.';
					}
				}
			}
		}
		
		$class_select_file_to_import_aecn = new select_file_to_import_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_select_file_to_import_aecn->select_file_to_import_aecn($table_name, $admin_field, $post_values, $errors);
	}
}