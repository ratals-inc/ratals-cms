<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/validation.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/validation.php');
}
else
{
	$errors = array();
	$post_values = array();
	
	if(!empty($admin_fields) && !empty($_POST[$_SESSION['admin_table_name']]) && !isset($_POST['change_site']))
	{
		foreach($admin_fields as $table_name => $table_column)
		{
			foreach($table_column as $key => $admin_field) 
			{
				if($_SESSION['admin_type'] == 'add' || ($_SESSION['admin_type'] == 'edit' && $admin_field['update_field_on_save'] == 'Yes'))
				{
					//Set submitted value posted into $post_values
					if(!isset($post_values[$table_name][$admin_field["column_name"]]))
					{
						if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
						{
							$post_values[$table_name][$admin_field["column_name"]] = $_POST[$table_name][$admin_field["column_name"]];
						}
						elseif(isset($_POST[$table_name][$admin_field["column_name"]]) && is_numeric($_POST[$table_name][$admin_field["column_name"]]))
						{
							$post_values[$table_name][$admin_field["column_name"]] = $_POST[$table_name][$admin_field["column_name"]];
						}
						elseif((strpos($admin_field["data_type"], 'int') !== false || strpos($admin_field["data_type"], 'decimal') !== false || strpos($admin_field["data_type"], 'date') !== false) && empty($_POST[$table_name][$admin_field["column_name"]]))
						{
							$post_values[$table_name][$admin_field["column_name"]] = NULL;
						}
						else
						{
							$post_values[$table_name][$admin_field["column_name"]] = '';
						}
					}
					
					$db_column_name = $admin_field['column_name'];
					$db_display_as = $admin_field['display_as'];
					
					//column_names run first as they are a higher level rule. If you create a file for a column name, it will process any data you pass through that admin field column.
					//If you want to customize an existing /classes/add-edit/column-names/ file, copy the file to the folder of /hooks/classes/add-edit/column-names/. This allows you to edit existing files that software updates will not override.
					if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/'.$db_column_name.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/'.$db_column_name.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/add-edit/column-names/'.$db_column_name.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/add-edit/column-names/'.$db_column_name.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/erp/classes/add-edit/column-names/'.$db_column_name.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/erp/classes/add-edit/column-names/'.$db_column_name.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/cms/classes/add-edit/column-names/'.$db_column_name.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/cms/classes/add-edit/column-names/'.$db_column_name.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/commerce/classes/add-edit/column-names/'.$db_column_name.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/commerce/classes/add-edit/column-names/'.$db_column_name.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/erp/classes/add-edit/column-names/'.$db_column_name.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/erp/classes/add-edit/column-names/'.$db_column_name.'.php');
					}
					//display_as runs second as its a lower level rule. If you set an admin_field / column to display_as singleMedia, all column_names will run through the singleMedia display_as. This allows you to have multiple column_names that can run through the same display_as name.
					//If you want to customize an existing /classes/add-edit/display-as/ file, copy the file to the folder of /hooks/classes/add-edit/display-as/. This allows you to edit existing files that software updates will not override.
					elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/display-as/'.$db_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/display-as/'.$db_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/add-edit/display-as/'.$db_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/add-edit/display-as/'.$db_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/erp/classes/add-edit/display-as/'.$db_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/hooks/admin/erp/classes/add-edit/display-as/'.$db_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/cms/classes/add-edit/display-as/'.$db_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/cms/classes/add-edit/display-as/'.$db_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/commerce/classes/add-edit/display-as/'.$db_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/commerce/classes/add-edit/display-as/'.$db_display_as.'.php');
					}
					elseif(file_exists(INSTALLATION_ROOT.'/admin/erp/classes/add-edit/display-as/'.$db_display_as.'.php'))
					{
						include(INSTALLATION_ROOT.'/admin/erp/classes/add-edit/display-as/'.$db_display_as.'.php');
					}
					
					//Validate / set error to display if value submitted and column_name data type do not match.
					if(strpos($admin_field["data_type"], 'varchar') !== false && $admin_field["data_length"] == 0)
					{
						//When a field is of type varchar and its data_length is set to 0, this catches the validation so it stops here. For admin fields such as template_files[file_code], which save code to a file rather than the database, specifying a length is unnecessary and this stops the validation process.
					}
					elseif($admin_field["required"] == "Yes" && $admin_field["column_name"] != 'site_id' && (empty($post_values[$table_name][$admin_field["column_name"]]) || $post_values[$table_name][$admin_field["column_name"]] == '0.0'  || $post_values[$table_name][$admin_field["column_name"]] == '0.00'  || $post_values[$table_name][$admin_field["column_name"]] == '0.000'  || $post_values[$table_name][$admin_field["column_name"]] == '0.0000'))
					{
						$errors[$table_name][$admin_field["column_name"]] = $admin_field["name"].' cannot be empty.';
					}
					//If not empty and string, make sure string is not longer than what database column can accept.
					elseif(!empty($post_values[$table_name][$admin_field["column_name"]]) && (strpos($admin_field["data_type"], 'varchar') !== false || strpos($admin_field["data_type"], 'text') !== false ||  strpos($admin_field["data_type"], 'longtext') !== false) && strlen($post_values[$table_name][$admin_field["column_name"]]) > $admin_field["data_length"])
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Please enter a shorter value. Database column can only handle up to "'.$admin_field["data_length"].'" characters.';
					}
					//If database field is an int, make sure data submitted is numberic.
					elseif(strpos($admin_field["data_type"], 'int') !== false && !is_numeric($post_values[$table_name][$admin_field["column_name"]]) && !empty($post_values[$table_name][$admin_field["column_name"]]))
					{
						$errors[$table_name][$admin_field["column_name"]] = 'Enter a valid '.$admin_field["name"];
					}
					//If database field is a decimal, make sure data submitted is float format.
					elseif(strpos($admin_field["data_type"], 'decimal') !== false && !empty($post_values[$table_name][$admin_field["column_name"]]) && (!is_numeric(str_replace(',', '.' ,$post_values[$table_name][$admin_field["column_name"]] ?? '')) || strpos($post_values[$table_name][$admin_field["column_name"]], $_SESSION['currency_fractional_separator']) === false || strpos($post_values[$table_name][$admin_field["column_name"]], $_SESSION['currency_fractional_separator']) > $admin_field["data_length"] || strpos(strrev($post_values[$table_name][$admin_field["column_name"]]), $_SESSION['currency_fractional_separator']) > $admin_field["data_length_back"]))
					{
						$max_front = '';
						$max_back = '';
						for ($i = 1; $i <= $admin_field["data_length"]; $i++) { $max_front .= '0'; }
						for ($i = 1; $i <= $admin_field["data_length_back"]; $i++) { $max_back .= '0'; }
					
						$errors[$table_name][$admin_field["column_name"]] = 'Enter a valid '.$admin_field["name"].' that is a float number. It must be less than '.$admin_field["data_length"].' digits before the "'.$_SESSION['currency_fractional_separator'].'" and less than '.$admin_field["data_length_back"].' digits behind the "'.$_SESSION['currency_fractional_separator'].'". The database column can handle a max structure of: '.$max_front.$_SESSION['currency_fractional_separator'].$max_back;
					}
					//If database field is a date, make sure data submitted is a valid date format.
					elseif((strpos($admin_field["data_type"], 'date') !== false || strpos($admin_field["data_type"], 'timestamp') !== false) && !empty($post_values[$table_name][$admin_field["column_name"]]) && $post_values[$table_name][$admin_field["column_name"]] != '0000-00-00 00:00:00')
					{
						//Check if date has time with it. Date and time are separated with a space if time is included.
						if(strpos($post_values[$table_name][$admin_field["column_name"]], ' ') !== false)
						{
							$date_and_time_array = explode(' ', $post_values[$table_name][$admin_field["column_name"]]);
							$date_and_time = $date_and_time_array[0];
						}
						else
						{
							$date_and_time = $post_values[$table_name][$admin_field["column_name"]];
						}
						
						//Check that date is formated correct with 2 dashes and check that date numbers fall within a real date number.
						if(substr_count($date_and_time, '-') == 2)
						{
							$posted_date = explode('-', $date_and_time);
							if(!is_numeric($posted_date[0]) || $posted_date[0] < 0001 || $posted_date[0] > 3000 || !is_numeric($posted_date[1]) || $posted_date[1] < 1 || $posted_date[1] > 12 || !is_numeric($posted_date[2]) || $posted_date[2] < 1 || $posted_date[2] > 31)
							{
								$errors[$table_name][$admin_field["column_name"]] = 'Enter a valid '.$admin_field["name"].'. Correct format is '.date('Y').'-01-01 / year-month-date.';
							}
						}
						else
						{
							$errors[$table_name][$admin_field["column_name"]] = 'Enter a valid '.$admin_field["name"].'. Correct format is '.date('Y').'-01-01 / year-month-date.';
						}
					}
				}
			}
		}
	}
}