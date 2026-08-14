<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/edit/includes/update-data-after.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/edit/includes/update-data-after.php');
}
else
{
	if(!empty($_POST) && empty($errors) && !isset($_POST['change_site']))
	{
		//When some creates a new edit admin page and its set as one record, which means they should not have an add page, we have to insert the record that can be updated on the edit page if it does not exist yet.
		if($_SESSION['admin_one_record'] == 'Yes' && $_SESSION['admin_type'] == 'edit')
		{
			//Check if row already exist.
			if($_SESSION['admin_table_name'] == 'sites')
			{
				$does_record_exist = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `id` = ?', [$_SESSION["site_set_for_editing"]]);
			}
			elseif($_SESSION['admin_site_id_global'] == 'Yes')
			{
				$does_record_exist = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `site_id` = ?', [0]);
			}
			else
			{
				$does_record_exist = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `site_id` = ?', [$_SESSION["site_set_for_editing"]]);
			}
			
			//If record doesn't exist insert the record with submitted values
			if(empty($does_record_exist))
			{
				$column_names = '';
				$column_placeholders = '';
				$column_value = array();
				
				foreach($value_1 as $key_2 => $value_2)
				{
					if($key_2 != 'id')
					{
						if($key_2 == 'created_date' || $key_2 == 'updated_date' || $key_2 == 'answered_date' || $key_2 == 'approved_date')
						{
							$column_names .= $key_2.',';
							$column_placeholders .= 'UTC_TIMESTAMP(),';
						}
						elseif($key_2 == 'created_by' || $key_2 == 'updated_by' || $key_2 == 'answered_by' || $key_2 == 'approved_by')
						{
							$column_names .= $key_2.',';
							$column_placeholders .= '?,';
							$column_value[] = $_SESSION['user_first_last_name'];
						}
						elseif($key_2 == 'site_id')
						{
							if($value_2 == '0')
							{
								$column_names .= $key_2.',';
								$column_placeholders .= '?,';
								$column_value[] = '0';
							}
							else
							{
								$column_names .= $key_2.',';
								$column_placeholders .= '?,';
								$column_value[] = $_SESSION["site_set_for_editing"];
							}
						}
						elseif($key_2 == 'custom_fields' && empty($value_2))
						{
							$column_names .= $key_2.',';
							$column_placeholders .= '?,';
							$column_value[] = '{}';
						}
						elseif($key_2 == 'table_name' && $_SESSION['admin_table_name'] != 'admin_pages')
						{
							$column_names .= $key_2.',';
							$column_placeholders .= '?,';
							$column_value[] = $_SESSION['admin_table_name'];
						}
						elseif($key_2 == 'password')
						{
							if(!empty($value_2))
							{
								$column_names .= $key_2.',';
								$column_placeholders .= '?,';
								$column_value[] = $value_2;
							}
						}
						elseif(strpos($column_data_type["data_type"], 'decimal') !== false)
						{
							$column_names .= $key_2.',';
							if(!empty($value_2))
							{
								$column_placeholders .= '?,';
								$column_value[] = str_replace(',', '.', $value_2);
							}
							else
							{
								$column_placeholders .= '?,';
								$column_value[] = NULL;
							}
						}
						elseif($key_2 == 'admin_pages_id' && empty($value_2))
						{
							$column_names .= $key_2.',';
							$column_placeholders .= '?,';
							$column_value[] = '0';
						}
						elseif($key_2 == 'admin_pages_parent_code' && empty($value_2))
						{
							$column_names .= $key_2.',';
							$column_placeholders .= '?,';
							$column_value[] = '0';
						}
						else
						{
							$column_names .= $key_2.',';
							$column_placeholders .= '?,';
							$column_value[] = $value_2;
						}
					}
				}
				
				$column_names = trim($column_names, ',');
				$column_placeholders = trim($column_placeholders, ',');
				
				$created_row_id = $results->getInsertRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], $column_names, $column_placeholders, $column_value);
			}
		}
		
		//If record has a URL, update this data.
		if($admin_fields_has_url == 'Yes' && isset($post_values['urls']['url_status']))
		{
			//Update media meta tag on assignment tables in case it was changed on a product, post, category, page, or anything else with a URL.
			$first_media_id = NULL;
			$first_media_tag = '';
			if(isset($post_values['urls']['media']) && !empty($post_values['urls']['media']))
			{
				if(strpos($post_values['urls']['media'], '*||*') !== false)
				{
					$first_media_array = explode('*||*', $post_values['urls']['media']);
					$first_media_array = explode('~||~', $first_media_array[0]);
					$first_media_id = $first_media_array[0];
					$first_media_tag = $first_media_array[1];
				}
				else
				{
					$first_media_array = explode('~||~', $post_values['urls']['media']);
					$first_media_id = $first_media_array[0];
					$first_media_tag = $first_media_array[1];
				}
				
				if(empty($first_media_tag))
				{
					$media_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$first_media_id]);
					
					$first_media_tag = $media_data['media_tag'];
				}
			
				$column_names = '`media_id` = ?, `media_tag` = ?';
				$where_clause = 'WHERE `site_id` = ? AND `child_id` = ? AND type != ?';
				$post_values_array = array($first_media_id, $first_media_tag, $_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? ''), 'inventory');
				if($commerce_installed)
				{
					$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_products', $column_names, $where_clause, $post_values_array);
				}
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_sub_items', $column_names, $where_clause, $post_values_array);
			}
			
			//If admin edit page is being updated for a table that has a url, update the assignments_sub_items table for any records with this URL ID for the current status.
			//$admin_fields_has_url variable is being set in the file of /admin/includes/admin-fields/get-fields.php
			$column_names = '`item_status` = ?';
			$where_clause = 'WHERE `site_id` = ? AND `child_id` = ?';
			$parameters = array($post_values['urls']['url_status'], $_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? ''));
			$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_sub_items', $column_names, $where_clause, $parameters);
		}
		
		//If a Post Page is being updated, update assignments_posts table so the Post is displaying in the correct blog categories.
		if($_SESSION['admin_table_name'] == 'posts')
		{
			$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_posts', 'WHERE `site_id` = ? AND `child_id` = ?', [$_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? '')]);
			
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
				
				foreach($posts_assigments_updates as $posts_assigments_update)
				{
					$column_names = '`id`, `site_id`, `parent_id`, `child_id`, `created_date`';
					$column_placeholders = 'NULL, ?, ?, ?, UTC_TIMESTAMP()';
					$column_value = array($_SESSION["site_set_for_editing"], $posts_assigments_update, trim($_GET["rid"] ?? ''));
					
					$results->getInsertRecord(__LINE__, __FILE__, 'assignments_posts', $column_names, $column_placeholders, $column_value);
				}
			}
		}
		
		//If any record is being updated and it has a urls_id admin field, we have to redirect user to redirects so they create redirect for changed URLs.
		if($admin_fields_has_url == 'Yes')
		{
			if($post_values['urls']['flat_url'] != $current_values['urls']['flat_url'] || $post_values['urls']['hierarchy_url'] != $current_values['urls']['hierarchy_url'])
			{
				$_SESSION["old_flat_url"] = $current_values['urls']['flat_url'];
				$_SESSION["new_flat_url"] = $post_values['urls']['flat_url'];
				
				$_SESSION["old_hierarchy_url"] = $current_values['urls']['hierarchy_url'];
				$_SESSION["new_hierarchy_url"] = $post_values['urls']['hierarchy_url'];
				
				$_SESSION["old_path_level"] = $current_values['urls']['path_level'];
				$_SESSION["new_path_level"] = $post_values['urls']['path_level'];
				
				header("Location: ".$_SESSION['admin_url_no_records']."redirects/?rid=".trim($_GET["rid"] ?? '')); exit();
			}
		}
		
		//If admin_page has a child_table set and a column for sub_items, count the number of the child rows for the sub_items to save the count on parent and main record.
		if(isset($post_values[$_SESSION['admin_table_name']]['sub_items']))
		{
			if(!empty($_SESSION['admin_child_table_name']) && !empty(trim($_GET["rid"] ?? '')) && is_numeric(trim($_GET["rid"] ?? '')))
			{
				//Update main table sub_items total
				$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_child_table_name'], 'WHERE `'.$_SESSION['admin_table_name'].'_id` = ?', [trim($_GET["rid"] ?? '')]);
				$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), trim($_GET["rid"] ?? '')]);
			}
			elseif(!empty($_SESSION['admin_table_link_column']) && !empty($_SESSION['admin_parent_table_name']) && !empty(trim($_GET["rid"] ?? '')) && is_numeric(trim($_GET["rid"] ?? '')))
			{
				if(isset($_GET['sub-page-rid']) && is_numeric($_GET['sub-page-rid']))
				{
					//Update main table sub_items total
					$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ?', [$_GET['sub-page-rid']]);
					$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_parent_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), $_GET['sub-page-rid']]);
				}
				
				//Update item table sub_items total
				$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `parent_id` = ?', [trim($_GET["rid"] ?? '')]);
				$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), trim($_GET["rid"] ?? '')]);
			}
		}
		
		if(isset($post_values) && !empty($_SESSION['admin_table_link_column']) && $_SESSION['admin_sub_page'] == 'Yes' && isset($_GET['sub-page-rid']) && $_SESSION['admin_table_name'] != 'customer_addresses')
		{
			//Update main table sub_items total
			$result = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ?', [$_GET['sub-page-rid']]);
			$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_parent_table_name'], '`sub_items` = ?', 'WHERE `id` = ?', [count($result), $_GET['sub-page-rid']]);
		}
		
		if($_SESSION['admin_table_name'] == 'template_files' && isset($current_template_file_name) && isset($posted_template_file_name) && !empty($posted_template_file_name) && $current_template_file_name != $posted_template_file_name)
		{
			$active_template = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `id` = ? AND site_id = ?', [trim($_GET['sub-page-rid'] ?? ''), $_SESSION["site_set_for_editing"]]);
			
			if(!empty($active_template['directory_folder_name']) && !file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']))
			{
				mkdir(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name'], 0777, true);
			}
			
			if(isset($active_template['directory_folder_name']) && !empty($active_template['directory_folder_name']))
			{
				rename(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$current_template_file_name, 
					   INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$posted_template_file_name);
			}
		}
		
		if($_SESSION['admin_table_name'] == 'templates' && isset($post_values['templates']['directory_folder_name']) && !empty($post_values['templates']['directory_folder_name']) && isset($current_values['templates']['directory_folder_name']) && $post_values['templates']['directory_folder_name'] != $current_values['templates']['directory_folder_name'])
		{
			if(file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$current_values['templates']['directory_folder_name']))
			{
				rename(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$current_values['templates']['directory_folder_name'], 
					   INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$post_values['templates']['directory_folder_name']);
			}
		}
		
		if($_SESSION['admin_table_name'] == 'template_files' && isset($posted_template_file_code))
		{
			$active_template = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `id` = ? AND site_id = ?', [trim($_GET['sub-page-rid'] ?? ''), $_SESSION["site_set_for_editing"]]);
			
			if(isset($active_template['directory_folder_name']) && !empty($active_template['directory_folder_name']) && isset($post_values['template_files']['filename']) && !empty($post_values['template_files']['filename']) && file_exists(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$post_values['template_files']['filename']))
			{
				$myfile = fopen(INSTALLATION_ROOT."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$post_values['template_files']['filename'], "w");
				fwrite($myfile, $posted_template_file_code);
				fclose($myfile);
			}
		}
		
		if($_SESSION['admin_table_name'] == 'media' && isset($post_values['media']['media_url']) && isset($current_values['media']['media_url']) && $post_values['media']['media_url'] != $current_values['media']['media_url'])
		{
			$media_type = strtolower($current_values['media']['media_type'].'s');
			
			if(file_exists(INSTALLATION_ROOT."/sites/media/".$media_type."/".$current_values['media']['original_media_id']."/".$current_values['media']['media_url']))
			{
				rename(INSTALLATION_ROOT."/sites/media/".$media_type."/".$current_values['media']['original_media_id']."/".$current_values['media']['media_url'], 
					   INSTALLATION_ROOT."/sites/media/".$media_type."/".$current_values['media']['original_media_id']."/".$post_values['media']['media_url']);
			}
		}
		
		if($_SESSION['admin_table_name'] == 'media' && isset($current_values['media']['media_tag']) && isset($post_values['media']['media_tag']))
		{
			//Update media meta tag on assignment tables if its using the default media tag on media and it changes.
			$column_names = '`media_tag` = ?';
			$where_clause = 'WHERE media_id = ? AND media_tag = ?';
			$post_values_array = array($post_values['media']['media_tag'], $post_values['media']['id'], $current_values['media']['media_tag']);
			if($commerce_installed)
			{
				$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_products', $column_names, $where_clause, $post_values_array);
			}
			
			$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_sub_items', $column_names, $where_clause, $post_values_array);
		}
		
		//Update admin directory folder name to what user saves it as.
		if($_SESSION['admin_table_name'] == 'sites' && isset($post_values['sites']['admin_directory']) && isset($current_values['sites']['admin_directory']) && $post_values['sites']['admin_directory'] != $current_values['sites']['admin_directory'])
		{
			//Get the /.htaccess file path.
			$htaccess_path = INSTALLATION_ROOT.'/.htaccess';
			if(file_exists($htaccess_path))
			{
				//Read the .htaccess content.
				$htaccess_contents = file_get_contents($htaccess_path);
				//Update .htaccess to use the new virtual admin directory name.
				$htaccess_contents = str_replace($current_values['sites']['admin_directory'], $post_values['sites']['admin_directory'], $htaccess_contents);
				//Write back the updated .htaccess content with new admin URL.
				file_put_contents($htaccess_path, $htaccess_contents);
			}
			
			//Get the /admin/.htaccess file path.
			$admin_htaccess_path = INSTALLATION_ROOT.'/admin/.htaccess';
			if(file_exists($admin_htaccess_path))
			{
				//Read the .htaccess content.
				$admin_htaccess_contents = file_get_contents($admin_htaccess_path);
				//Update .htaccess to use the new virtual admin directory name.
				$admin_htaccess_contents = str_replace($current_values['sites']['admin_directory'], $post_values['sites']['admin_directory'], $admin_htaccess_contents);
				//Write back the updated .htaccess content with new admin URL.
				file_put_contents($admin_htaccess_path, $admin_htaccess_contents);
			}
			
			//Update database with new virtual admin directory name.
			$results->getUpdateRecord(__LINE__, __FILE__, 'sites', '`admin_directory` = ?, `updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', '', [$post_values['sites']['admin_directory'], $_SESSION['user_first_last_name']]);
			
			//Pause for 10 seconds to ensure the new admin URL is updated in the .htaccess file and the cache is cleared, allowing it to load properly.
			sleep(10);
			
			header("Location: /".$post_values['sites']['admin_directory']."/website/site-settings/url-settings/?updated=success&update-admin-url=".$post_values['sites']['admin_directory']);
			exit();
		}
		
		//When an admin user exports a database table the eport is done with this code.
		if($_SESSION['admin_table_name'] == 'export_data' && isset($post_values['export_data']['table_to_export']) && !empty($post_values['export_data']['table_to_export']) )
		{
			$export_table_setup = $results_schema->getSchemaSelectSingleRecord(__LINE__, __FILE__, '*', 'tables', 'WHERE `table_schema` = ? AND `table_name` = ?', [$_SESSION['site_db_name'], $post_values['export_data']['table_to_export']]);
			
			$export_table_columns = $results_schema->getSchemaSelectMultipleRecords(__LINE__, __FILE__, '*', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? ORDER BY `columns`.`ORDINAL_POSITION` ASC', [$_SESSION['site_db_name'], $export_table_setup['TABLE_NAME']]);
			
			$row_values = '';
			
			//Create spreadsheet header.
			if(!empty($export_table_columns))
			{
				$export_where_statement = '';
				$export_parameters = array();
				
				if(!empty($post_values['export_data']['site_id_to_export']))
				{
					$export_where_statement .= ' AND `site_id` = ?';
					$export_parameters[] = $post_values['export_data']['site_id_to_export'];
				}
				
				if(!empty($post_values['export_data']['start_row_id']))
				{
					$export_where_statement .= ' AND `id` >= ?';
					$export_parameters[] = $post_values['export_data']['start_row_id'];
				}
				
				if(!empty($post_values['export_data']['end_row_id']))
				{
					$export_where_statement .= ' AND `id` <= ?';
					$export_parameters[] = $post_values['export_data']['end_row_id'];
				}
				
				if(!empty($export_where_statement))
				{
					$export_where_statement = 'WHERE '.trim($export_where_statement, ' AND');
				}
				
				$exported_results = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $export_table_setup['TABLE_NAME'], $export_where_statement, $export_parameters);
				
				header("Content-Type: application/vnd.ms-excel; charset=utf-8");
				header("Content-Disposition: attachment; filename=".$export_table_setup['TABLE_NAME'].".xls");
				header("Pragma: no-cache");
				header("Expires: 0");
				
				echo '<style nonce="'.NONCE.'">.sheet-export { border: 0.1pt solid #cccccc; white-space: nowrap; }</style>';
				
				$row_values .= '<body class="sheet-export">';
				$row_values .= '<table>';
				
				$row_values .= '<tr>';
				foreach($export_table_columns as $export_table_column)
				{
					$row_values .= '<th>'.$export_table_column['COLUMN_NAME'].'</th>';
				}
				$row_values .= '</tr>';
				
				foreach($exported_results as $exported_result)
				{
					$row_values .= '<tr>';
					foreach($exported_result as $exported_value)
					{
						$row_values .= '<td>'.htmlentities($exported_value ?? '').'</td>';
					}
					$row_values .= '</tr>';
				}
				$row_values .= '</table>';
				$row_values .= '</body>';
				
				print($row_values);
				
				//Stop the page from running so the export can fully execute and push the spreadsheet download to the browser.
				die;
			}
		}
		
		//When an admin user imports into a database table the insert/update is done with this code. The insert/update array that is perpared for the import is done in admin/includes/admin-fields/validation.
		if($_SESSION['admin_table_name'] == 'import_data' && isset($post_values['import_data']['select_file_to_import']) && !empty($post_values['import_data']['select_file_to_import']) && isset($export_table_setup['TABLE_NAME']) && !empty($export_table_setup['TABLE_NAME']))
		{
			if(!empty($insert_parameters) && empty($errors))
			{
				//echo '<br>INSERT<br>';
				//echo '<br>'.$insert_columns;
				//echo '<br>'.$insert_placeholders;
				//echo '<pre>'; print_r($insert_parameters); echo '</pre>';
				
				$results->getInsertMultipleRecords(__LINE__, __FILE__, $export_table_setup['TABLE_NAME'], $insert_columns, $insert_placeholders, $insert_parameters);
			}
			
			if(!empty($update_parameters) && empty($errors))
			{
				//echo '<br>UPDATE HEREE<br>';
				//echo '<br>'.$update_columns;
				//echo '<pre>'; print_r($update_parameters); echo '</pre>';
				
				$results->getUpdateMultipleRecords(__LINE__, __FILE__, $export_table_setup['TABLE_NAME'], $update_columns, 'WHERE `id` = ?', $update_parameters);
			}
			
			//die;
		}
		
		if($_SESSION['admin_table_name'] == 'database_tables' && isset($post_values['database_tables']['database_table_name']) && !empty($post_values['database_tables']['database_table_name']) && isset($post_values['database_tables']['admin_fields_ids']) && !empty($post_values['database_tables']['admin_fields_ids']))
		{
			$all_admin_fields_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', '', [], 'id');
			
			$database_columns_submitted = explode(',', trim($post_values['database_tables']['admin_fields_ids'], ','));
			
			//$database_tables_data variable is being set in admin/includes/admin-fields/validation.php. This holds what the table column were before updating.
			$database_columns_before_updating = explode(',', trim($database_tables_data['admin_fields_ids'], ','));
			
			//Data types that do not need a default set, so skip these when adding a new column to a table.
			$data_types_flip_is_nullable = ['text','tinytext','mediumtext','longtext','blob','tinyblob','mediumblob','longblob'];
			$numeric_types = ['int','tinyint','smallint','mediumint','bigint','decimal','float','double'];
			
			//Add new columns to table that were added.
			foreach($database_columns_submitted as $database_column_submitted)
			{
				$table_columns = '';
				
				if(!empty($database_tables_data['admin_fields_ids']) && strpos($database_tables_data['admin_fields_ids'], ','.$database_column_submitted.',') === false)
				{
					$table_columns .= '`'.$all_admin_fields_array[$database_column_submitted]['column_name'].'` ';
					$table_columns .= $all_admin_fields_array[$database_column_submitted]['data_type'].' ';
					if(!empty($all_admin_fields_array[$database_column_submitted]['character_set_and_collate']))
					{
						$table_columns .= $all_admin_fields_array[$database_column_submitted]['character_set_and_collate'].' ';
					}
					if(!in_array(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']), $data_types_flip_is_nullable))
					{
						//Set if column can be null or not
						if($all_admin_fields_array[$database_column_submitted]['is_nullable'] == 'Yes')
						{
							$table_columns .= 'NULL ';
						}
						elseif($all_admin_fields_array[$database_column_submitted]['is_nullable'] == 'No')
						{
							$table_columns .= 'NOT NULL ';
						}
						//set default value for column
						if(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']) == 'timestamp')
						{
							$table_columns .= 'default current_timestamp ';
						}
						elseif(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']) == 'date' || strtolower($all_admin_fields_array[$database_column_submitted]['data_type']) == 'datetime')
						{
							$table_columns .= 'default null ';
						}
						elseif(strpos(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']), 'char') !== false || strpos(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']), 'varchar') !== false)
						{
							$table_columns .= 'default "" ';
						}
						elseif($all_admin_fields_array[$database_column_submitted]['is_nullable'] == 'Yes')
						{
							$table_columns .= 'default null ';
						}
					}
					if(in_array(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']), $numeric_types) && $all_admin_fields_array[$database_column_submitted]['is_nullable'] == 'No' && strpos($table_columns, 'default') === false) 
					{
						$table_columns .= 'default 0 ';
					}
					if($all_admin_fields_array[$database_column_submitted]['is_auto_increment'] == 'Yes')
					{
						$table_columns .= 'AUTO_INCREMENT ';
					}
					if($all_admin_fields_array[$database_column_submitted]['is_primary_key'] == 'Yes')
					{
						$table_columns .= 'PRIMARY KEY ';
					}
					
					$results->getAlterDatabaseTable(__LINE__, __FILE__, $post_values['database_tables']['database_table_name'], 'ADD '.$table_columns);
				}
			}
			
			//Drop columns on table that were removed.
			foreach($database_columns_before_updating as $database_column_before_updating)
			{
				if(!empty($post_values['database_tables']['admin_fields_ids']) && strpos($post_values['database_tables']['admin_fields_ids'], ','.$database_column_before_updating.',') === false)
				{
					if(isset($all_admin_fields_array[$database_column_before_updating]['column_name']) && !empty($all_admin_fields_array[$database_column_before_updating]['column_name']))
					{
						$results->getAlterDatabaseTable(__LINE__, __FILE__, $post_values['database_tables']['database_table_name'], 'DROP '.$all_admin_fields_array[$database_column_before_updating]['column_name']);
					}
				}
			}
			
			//Get schema data so default avlue can be set back to the column.
			$current_database_columns = $results_schema->getSchemaSelectMultipleRecordsKeyName(__LINE__, __FILE__, '`column_name`, `column_default`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ?', [$_SESSION['site_db_name'], $post_values['database_tables']['database_table_name']], 'COLUMN_NAME');
			
			//Order all columns to correct order.
			$last_column_name_modified = '';
			foreach($database_columns_submitted as $database_column_submitted)
			{
				$column_default = '';
				if(isset($current_database_columns[$all_admin_fields_array[$database_column_submitted]['column_name']]['COLUMN_DEFAULT']))
				{
					$column_default = $current_database_columns[$all_admin_fields_array[$database_column_submitted]['column_name']]['COLUMN_DEFAULT'];
				}
				
				$table_columns = '';
				
				if(isset($all_admin_fields_array[$database_column_submitted]['column_name']) && !empty($all_admin_fields_array[$database_column_submitted]['column_name']))
				{
					$table_columns .= '`'.$all_admin_fields_array[$database_column_submitted]['column_name'].'` ';
					$table_columns .= $all_admin_fields_array[$database_column_submitted]['data_type'].' ';
					if(!empty($all_admin_fields_array[$database_column_submitted]['character_set_and_collate']))
					{
						$table_columns .= $all_admin_fields_array[$database_column_submitted]['character_set_and_collate'].' ';
					}
					if(!in_array(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']), $data_types_flip_is_nullable))
					{
						//Set if column can be null or not
						if($all_admin_fields_array[$database_column_submitted]['is_nullable'] == 'Yes')
						{
							$table_columns .= 'NULL ';
						}
						elseif($all_admin_fields_array[$database_column_submitted]['is_nullable'] == 'No')
						{
							$table_columns .= 'NOT NULL ';
						}
						//set default value for column
						if(!empty($column_default) && strtolower($column_default) == 'current_timestamp')
						{
							$table_columns .= 'default current_timestamp ';
						}
						elseif($column_default == null && (strtolower($all_admin_fields_array[$database_column_submitted]['data_type']) == 'date' || strtolower($all_admin_fields_array[$database_column_submitted]['data_type']) == 'datetime'))
						{
							$table_columns .= 'default null ';
						}
						elseif(!empty($column_default) && !is_numeric($column_default))
						{
							$table_columns .= "default '".$column_default."' ";
						}
						elseif(empty($column_default) && (strpos(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']), 'char') !== false || strpos(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']), 'varchar') !== false))
						{
							$table_columns .= 'default "" ';
						}
						elseif($all_admin_fields_array[$database_column_submitted]['is_nullable'] == 'Yes' && (strtolower($column_default) === 'null' || $column_default === null))
						{
							$table_columns .= 'default null ';
						}
						elseif(is_numeric($column_default))
						{
							$table_columns .= 'default '.$column_default.' ';
						}
					}
					if(in_array(strtolower($all_admin_fields_array[$database_column_submitted]['data_type']), $numeric_types) && $all_admin_fields_array[$database_column_submitted]['is_nullable'] == 'No' && strpos($table_columns, 'default') === false && $all_admin_fields_array[$database_column_submitted]['is_auto_increment'] == 'No') 
					{
						$table_columns .= 'default 0 ';
					}
					if($all_admin_fields_array[$database_column_submitted]['is_auto_increment'] == 'Yes')
					{
						$table_columns .= 'AUTO_INCREMENT ';
					}
					if(empty($last_column_name_modified))
					{
						$table_columns .= 'FIRST';
					}
					else
					{
						$table_columns .= 'AFTER `'.$last_column_name_modified.'`';
					}
					
					$results->getAlterDatabaseTable(__LINE__, __FILE__, $post_values['database_tables']['database_table_name'], 'MODIFY '.$table_columns);
					
					$last_column_name_modified = $all_admin_fields_array[$database_column_submitted]['column_name'];
				}
			}
		}
		
		if($_SESSION['admin_table_name'] == 'admin_fields' && isset($database_tables_with_old_column_name) && !empty($database_tables_with_old_column_name) && isset($post_values['admin_fields']['id']) && !empty($post_values['admin_fields']['id']))
		{
			//Data types that do not need a default set, so skip these when adding a new column to a table.
			$data_types_flip_is_nullable = ['text','tinytext','mediumtext','longtext','blob','tinyblob','mediumblob','longblob'];
			$numeric_types = ['int','tinyint','smallint','mediumint','bigint','decimal','float','double'];
			
			foreach($database_tables_with_old_column_name as $database_table_with_old_column_name)
			{
				//Get schema data so default value can be set back to the column.
				$current_database_columns = $results_schema->getSchemaSelectMultipleRecordsKeyName(__LINE__, __FILE__, '`column_name`, `column_default`', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ?', [$_SESSION['site_db_name'], $database_table_with_old_column_name['database_table_name']], 'COLUMN_NAME');
			
				$column_default = '';
				if(isset($current_database_columns[$database_old_column_name['column_name']]['COLUMN_DEFAULT']))
				{
					$column_default = $current_database_columns[$database_old_column_name['column_name']]['COLUMN_DEFAULT'];
				}
				
				if(isset($database_old_column_name['column_name']) && !empty($database_old_column_name['column_name']) && isset($database_new_column_name) && !empty($database_new_column_name) && $database_old_column_name['column_name'] != $database_new_column_name)
				{
					$results->getAlterDatabaseTable(__LINE__, __FILE__, $database_table_with_old_column_name['database_table_name'], 'RENAME COLUMN '.$database_old_column_name['column_name'].' TO '.$database_new_column_name);
				}
				
				$all_admin_fields_array = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', '', [], 'id');
				
				$table_columns = '';
				$table_columns .= '`'.$all_admin_fields_array[$post_values['admin_fields']['id']]['column_name'].'` ';
				$table_columns .= $all_admin_fields_array[$post_values['admin_fields']['id']]['data_type'].' ';
				if(!empty($all_admin_fields_array[$post_values['admin_fields']['id']]['character_set_and_collate']))
				{
					$table_columns .= $all_admin_fields_array[$post_values['admin_fields']['id']]['character_set_and_collate'].' ';
				}
				if(!in_array(strtolower($all_admin_fields_array[$post_values['admin_fields']['id']]['data_type']), $data_types_flip_is_nullable))
				{
					//Set if column can be null or not
					if($all_admin_fields_array[$post_values['admin_fields']['id']]['is_nullable'] == 'Yes')
					{
						$table_columns .= 'NULL ';
					}
					elseif($all_admin_fields_array[$post_values['admin_fields']['id']]['is_nullable'] == 'No')
					{
						$table_columns .= 'NOT NULL ';
					}
					//set default value for column
					if(!empty($column_default) && strtolower($column_default) == 'current_timestamp')
					{
						$table_columns .= 'default current_timestamp ';
					}
					elseif($column_default == null && (strtolower($all_admin_fields_array[$post_values['admin_fields']['id']]['data_type']) == 'date' || strtolower($all_admin_fields_array[$post_values['admin_fields']['id']]['data_type']) == 'datetime'))
					{
						$table_columns .= 'default null ';
					}
					elseif(!empty($column_default) && !is_numeric($column_default))
					{
						$table_columns .= "default '".$column_default."' ";
					}
					elseif((strpos(strtolower($all_admin_fields_array[$post_values['admin_fields']['id']]['data_type']), 'char') !== false || strpos(strtolower($all_admin_fields_array[$post_values['admin_fields']['id']]['data_type']), 'varchar') !== false))
					{
						$table_columns .= 'default "" ';
					}
					elseif($all_admin_fields_array[$post_values['admin_fields']['id']]['is_nullable'] == 'Yes' && (strtolower($column_default) === 'null' || $column_default === null))
					{
						$table_columns .= 'default null ';
					}
					elseif(is_numeric($column_default))
					{
						$table_columns .= 'default '.$column_default.' ';
					}
				}
				if(in_array(strtolower($all_admin_fields_array[$post_values['admin_fields']['id']]['data_type']), $numeric_types) && $all_admin_fields_array[$post_values['admin_fields']['id']]['is_nullable'] == 'No' && strpos($table_columns, 'default') === false && $all_admin_fields_array[$post_values['admin_fields']['id']]['is_auto_increment'] == 'No') 
				{
					$table_columns .= 'default 0 ';
				}
				if($all_admin_fields_array[$post_values['admin_fields']['id']]['is_auto_increment'] == 'Yes')
				{
					$table_columns .= 'AUTO_INCREMENT ';
				}
				
				$results->getAlterDatabaseTable(__LINE__, __FILE__, $database_table_with_old_column_name['database_table_name'], 'MODIFY '.$table_columns);
			}
		}
		
		if($_SESSION['admin_table_name'] == 'custom_fields' || $_SESSION['admin_table_name'] == 'custom_fields_options')
		{
			$assignment_tables = array('assignments_products', 'assignments_sub_items');
			
			foreach($assignment_tables as $assignment_table)
			{
				$sql_select_assignments_rows = array();
				
				if($assignment_table == 'assignments_sub_items' || ($commerce_installed && $assignment_table == 'assignments_products'))
				{
					//Update inventroy on the assignment table rows impacted by this custom field change.
					if($_SESSION['admin_table_name'] == 'custom_fields' && isset($_GET['rid']) && !empty($_GET['rid']))
					{
						$custom_field_id = $_GET['rid'];
						$custom_field_option_id = '';
						
						$sql_select_assignments_rows = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $assignment_table, 'WHERE `inventory_attribute_value_ids` LIKE ? AND `type` = ?', ['%,'.$_GET['rid'].':%', 'inventory']);
					}
					elseif($_SESSION['admin_table_name'] == 'custom_fields_options' && isset($_GET['sub-page-rid']) && !empty($_GET['sub-page-rid']) && isset($_GET['rid']) && !empty($_GET['rid']))
					{
						$custom_field_id = $_GET['sub-page-rid'];
						$custom_field_option_id = $_GET['rid'];
						
						$sql_select_assignments_rows = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', $assignment_table, 'WHERE `inventory_attribute_value_ids` LIKE ? AND `type` = ?', ['%,'.$_GET['sub-page-rid'].':'.$_GET['rid'].',%', 'inventory']);
					}
				}
				
				//Get the unqiue custom field id so we can get there values.
				$inventory_attribute_ids = array();
				if(!empty($sql_select_assignments_rows))
				{
					foreach($sql_select_assignments_rows as $sql_select_assignments_row)
					{
						if(!empty($sql_select_assignments_row['inventory_attribute_value_ids']))
						{
							$inventory_attribute_ids_array = explode(',', trim($sql_select_assignments_row['inventory_attribute_value_ids'], ','));
							
							foreach($inventory_attribute_ids_array as $inventory_attribute_id_array)
							{
								$inventory_attribute_id = explode(':', $inventory_attribute_id_array);
								
								if(!in_array($inventory_attribute_id[0], $inventory_attribute_ids))
								{
									$inventory_attribute_ids[] = $inventory_attribute_id[0];
								}
							}
						}
					}
					
					//Get custom field url values
					$custom_field_url_names = array();
					$custom_field_option_url_names = array();
					if(!empty($inventory_attribute_ids))
					{
						foreach($inventory_attribute_ids as $inventory_attribute_id)
						{
							$sql_custom_field_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ? AND `status` = ?', [$inventory_attribute_id, '1']);
							
							$custom_field_name = JSON_DECODE($sql_custom_field_data['custom_field_name'] ?? '', true);
							
							if(!empty($custom_field_name))
							{
								foreach($custom_field_name as $key => $value)
								{
									$custom_field_url_names[$inventory_attribute_id][$key] = $custom_field_name[$key]['admin_name'] ?? '';
								}
							}
							
							$sql_get_custom_field_options = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields_options', 'WHERE `custom_fields_id` = ?', [$inventory_attribute_id]);
							if(!empty($sql_get_custom_field_options))
							{
								foreach($sql_get_custom_field_options as $sql_get_custom_field_option)
								{
									$option_data = JSON_DECODE($sql_get_custom_field_option['option_data'] ?? '', true);
									
									if(!empty($option_data))
									{
										foreach($option_data as $key => $value)
										{
											$custom_field_option_url_name = $option_data[$key]['value'] ?? '';
											$custom_field_option_url_names[$inventory_attribute_id][$sql_get_custom_field_option['id']][$key] = $custom_field_option_url_name;
										}
									}
								}
							}
						}
					}
					
					//Create inventory url to update assignment tables
					$update_data = array();
					foreach($sql_select_assignments_rows as $sql_select_assignments_row)
					{
						$inventory_attribute_url = '';
						
						if(!empty($sql_select_assignments_row['inventory_attribute_value_ids']))
						{
							$inventory_attribute_ids_array = explode(',', trim($sql_select_assignments_row['inventory_attribute_value_ids'], ','));
							
							foreach($inventory_attribute_ids_array as $inventory_attribute_id_array)
							{
								$inventory_attribute_id = explode(':', $inventory_attribute_id_array);
								
								if(isset($custom_field_url_names[$inventory_attribute_id[0]][$sites_language_array[$sql_select_assignments_row['site_id']]]) && !empty($custom_field_url_names[$inventory_attribute_id[0]][$sites_language_array[$sql_select_assignments_row['site_id']]]) && isset($custom_field_option_url_names[$inventory_attribute_id[0]][$inventory_attribute_id[1]][$sites_language_array[$sql_select_assignments_row['site_id']]]) && !empty($custom_field_option_url_names[$inventory_attribute_id[0]][$inventory_attribute_id[1]][$sites_language_array[$sql_select_assignments_row['site_id']]]))
								{
									$inventory_attribute_url .= $custom_field_url_names[$inventory_attribute_id[0]][$sites_language_array[$sql_select_assignments_row['site_id']]].'='.$custom_field_option_url_names[$inventory_attribute_id[0]][$inventory_attribute_id[1]][$sites_language_array[$sql_select_assignments_row['site_id']]].'&';
								}
								
							}
						}
						
						if(!empty($inventory_attribute_url))
						{
							$update_data[] = array('?'.trim($inventory_attribute_url, '&'), $sql_select_assignments_row['id'], $sql_select_assignments_row['site_id']);
						}
					}
					
					if(!empty($update_data))
					{
						$results->getUpdateMultipleRecords(__LINE__, __FILE__, $assignment_table, '`inventory_url` = ?', 'WHERE `id` = ? AND `site_id` = ?', $update_data);
					}
				}
			}
		}
		
		if($_SESSION['admin_table_name'] == 'license' && $post_values['license']['license_key'] != $current_values['license']['license_key'])
		{
			$all_domains = array();
			$domains = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'sites', '', [], '');
			if(!empty($domains))
			{
				foreach($domains as $set_domain)
				{
					$all_domains[] = $set_domain['domain'];
				}
			}
			
			$license_check_endpoint = 'https://www.ratals.com/api/license/index.php';
			
			$post_data = [
				'domain' => $_SERVER['HTTP_HOST'] ?? '',
				'ip' => $_SERVER['REMOTE_ADDR'] ?? '',
				'license_key' => $post_values['license']['license_key'],
				'install_id' => $post_values['license']['install_id'],
				'domains' => $all_domains
			];
			
			$ch = curl_init($license_check_endpoint);
			
			curl_setopt_array($ch, [
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_POSTFIELDS => http_build_query($post_data),
				CURLOPT_TIMEOUT => 5,
				CURLOPT_CONNECTTIMEOUT => 5,
				CURLOPT_SSL_VERIFYPEER => true,
				CURLOPT_SSL_VERIFYHOST => 2,
			]);
			
			$response = curl_exec($ch);
			curl_close($ch);
			
			$license_check = json_decode($response, true);
			
			//If no valid response, don't update.
			if(isset($license_check['license_status']) && isset($license_check['license_type']))
			{
				$license_status = $license_check['license_status'] ?? 'Active';
				$license_type = strtolower($license_check['license_type'] ?? 'cms');
				$license_last_billing_date = $license_check['license_last_billing_date'] ?? NULL;
				$license_next_billing_date = $license_check['license_next_billing_date'] ?? NULL;
				$license_next_billing_amount = $license_check['license_next_billing_amount'] ?? 0;
				$license_billing_line_items = $license_check['license_billing_line_items'] ?? '';
				
				$current_license_type = 'CMS';
				if($license_type == 'commerce')
				{
					$current_license_type = 'Commerce';
				}
				elseif($license_type == 'erp')
				{
					$current_license_type = 'ERP';
				}
				elseif($license_type == 'ai')
				{
					$current_license_type = 'AI';
				}
				$results->getUpdateRecord(__LINE__, __FILE__, 'license', '`license_status` = ?, `license_type` = ?, `license_last_billing_date` = ?, `license_next_billing_date` = ?, `license_next_billing_amount` = ?, `license_billing_line_items` = ?, `last_seen` = UTC_TIMESTAMP', 'WHERE `site_id` = ?', [$license_status, $current_license_type, $license_last_billing_date, $license_next_billing_date, $license_next_billing_amount, $license_billing_line_items, 0]);
				
				$subject_to_insert = '';
				$message_to_insert = '';
				if($current_values['license']['license_type'] == 'CMS' && $current_license_type == 'Commerce')
				{
					$subject_to_insert = 'Upgrade Your Software to Enable Commerce Features';
					$message_to_insert = 'We\'ve detected that you have entered a valid license key for the Commerce package. Before installing, please make sure you have a complete backup of your current setup. If you are unsure how to do this, follow <a href="https://www.ratals.com/tutorials/installation/backup-ratals-software/" target="_blank">this tutorial</a>. Once your backup is complete, click the "Update Now" button to begin installing the Commerce package. This upgrade will enable all Commerce features, allowing you to run an online store on top of your CMS.';
				}
				elseif(($current_values['license']['license_type'] == 'CMS' || $current_values['license']['license_type'] == 'Commerce') && $current_license_type == 'ERP')
				{
					$subject_to_insert = 'Upgrade Your Software to Enable ERP Features';
					$message_to_insert = 'We\'ve detected that you have entered a valid license key for the ERP package. Before installing, please make sure you have a complete backup of your current setup. If you are unsure how to do this, follow <a href="https://www.ratals.com/tutorials/installation/backup-ratals-software/" target="_blank">this tutorial</a>. Once your backup is complete, click the "Update Now" button to begin installing the ERP package. This upgrade will enable both Commerce and ERP features, allowing you to run an online store with full business and financial management on top of your CMS.';
				}
				elseif(($current_values['license']['license_type'] == 'CMS' || $current_values['license']['license_type'] == 'Commerce' || $current_values['license']['license_type'] == 'ERP') && $current_license_type == 'AI')
				{
					$subject_to_insert = 'Upgrade Your Software to Enable AI Features';
					$message_to_insert = 'We\'ve detected that you have entered a valid license key for the AI package. Before installing, please make sure you have a complete backup of your current setup. If you are unsure how to do this, follow <a href="https://www.ratals.com/tutorials/installation/backup-ratals-software/" target="_blank">this tutorial</a>. Once your backup is complete, click the "Update Now" button to begin installing the AI package. This upgrade will enable Commerce, ERP, and AI features, allowing you to run an online store with full business and financial management with AI on top of your CMS.';
				}
				
				if(!empty($message_to_insert))
				{
					//API call to get most current software version and requirements when upgrading.
					$current_version_endpoint = 'https://www.ratals.com/api/current-version/index.php';
					
					$ch = curl_init($current_version_endpoint);
					curl_setopt_array($ch, [
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_HTTPGET => true,
						CURLOPT_TIMEOUT => 5,
						CURLOPT_CONNECTTIMEOUT => 5,
						CURLOPT_SSL_VERIFYPEER => true,
						CURLOPT_SSL_VERIFYHOST => 2,
					]);
					
					$response = curl_exec($ch);
					curl_close($ch);
					
					$current_version = json_decode($response, true);
					
					//Upgrade message
					$upgrade_file_name = 'ratals-core';
					$update_software = 'Yes';
					$notice_upgrade_from = $current_values['license']['license_type'];
					$notice_upgrade_to = $current_license_type;
					$software_version = $current_version['software_version'] ?? '';
					$required_php = $current_version['required_php'] ?? '';
					$required_mysql = $current_version['required_mysql'] ?? '';
					$system_code = 'upgrade_'.$current_values['license']['license_type'].'_to_'.$current_license_type;
						
					$message_to_insert .= '<ul><li><strong>PHP Requirement:</strong> '.$required_php.' or greater. You are currently running PHP [CURRENT_PHP_VERSION].</li><li><strong>MySQL Requirement:</strong> '.$required_mysql.' or greater. You are currently running MySQL [CURRENT_MYSQL_VERSION].</li></ul>';
					
					$results->getInsertRecord(__LINE__, __FILE__, 'notices', '`id`, `site_id`, `status`, `notice_subject`, `notice_message`, `notice_url`, `notice_update_software`, `notice_upgrade_from`, `notice_upgrade_to`, `notice_software_version`, `required_php_version`, `required_mysql_version`, `system_code`, `custom_fields`, `created_date`', '?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP', [NULL, 0, 1, $subject_to_insert, $message_to_insert, $upgrade_file_name, $update_software, $notice_upgrade_from, $notice_upgrade_to, $software_version, $required_php, $required_mysql, $system_code, '{}']);
				}
			}
		}
	}
}