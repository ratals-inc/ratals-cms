<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/add/includes/insert-data.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/add/includes/insert-data.php');
}
else
{
	//Insert new record into database.
	if(!empty($_POST) && !empty($post_values) && empty($errors) && !isset($_POST['change_site']))
	{
		//echo '<pre>'; print_r($post_values); echo '</pre>';
		foreach($post_values as $table_name => $value_1)
		{
			$column_names = '';
			$column_placeholders = '';
			$column_value = array();
			
			foreach($value_1 as $key_2 => $value_2)
			{
				$column_data_type = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `column_name` = ?', [$key_2]);
				
				$column_names .= '`'.$key_2.'`,';
				
				if($key_2 == 'updated_date' || $key_2 == 'created_date')
				{
					$column_placeholders .= 'UTC_TIMESTAMP(),';
				}
				elseif($key_2 == 'updated_by' || $key_2 == 'created_by')
				{
					$column_placeholders .= '?,';
					$column_value[] = $_SESSION['user_first_last_name'];
				}
				elseif($key_2 == 'site_id')
				{
					if($value_2 == '0')
					{
						$column_placeholders .= '?,';
						$column_value[] = '0';
					}
					else
					{
						$column_placeholders .= '?,';
						$column_value[] = $_SESSION["site_set_for_editing"];
					}
				}
				elseif($key_2 == 'custom_fields' && empty($value_2))
				{
					$column_placeholders .= '?,';
					$column_value[] = '{}';
				}
				elseif($key_2 == 'filename')
				{
					$column_placeholders .= '?,';
					$column_value[] = $value_2;
					$posted_template_file_name = $value_2;
				}
				elseif($key_2 == 'file_code')
				{
					$column_placeholders .= '?,';
					$column_value[] = '';
					$posted_template_file_code = $value_2;
				}
				elseif($key_2 == 'table_name' && $_SESSION['admin_table_name'] != 'admin_pages')
				{
					$column_placeholders .= '?,';
					$column_value[] = $_SESSION['admin_table_name'];
				}
				elseif($key_2 == 'admin_pages_id' && empty($value_2))
				{
					$column_placeholders .= '?,';
					$column_value[] = 0;
				}
				elseif($key_2 == 'admin_pages_parent_code' && empty($value_2))
				{
					$column_placeholders .= '?,';
					$column_value[] = 0;
				}
				elseif($table_name == 'urls' && $key_2 == 'record_id')
				{
					$column_placeholders .= '?,';
					$column_value[] = 0;
				}
				elseif($key_2 == 'urls_id' && isset($url_created['id']) && !empty($url_created['id']))
				{
					$column_placeholders .= '?,';
					$column_value[] = $url_created['id'];
				}
				elseif($key_2 == 'urls_id' && (!isset($url_created['id']) || !empty($url_created['id'])))
				{
					echo '<p>There was an issue getting the url ID created. Please check the file of /admin/add/insert-data.php to figure out why the url cannot be fecthed.</p>';
					echo '<p>Here is what you were trying to create in code format. If you\'re not a developer, please copy all of this message and give it to your developer.</p>';
					echo '<pre>'; print_r($post_values); echo '</pre>';
					die;
				}
				elseif(strpos($column_data_type["data_type"], 'decimal') !== false)
				{
					$column_placeholders .= '?,';
					if(!empty($value_2))
					{
						$column_value[] = str_replace(',', '.', $value_2);
					}
					else
					{
						$column_value[] = NULL;
					}
				}
				else
				{
					$column_placeholders .= '?,';
					$column_value[] = $value_2;
				}
			}
			$column_names = trim($column_names, ',');
			$column_placeholders = trim($column_placeholders, ',');
			
			//echo '<br><br>'.$table_name;
			//echo '<br><br>'.$column_names;
			//echo '<br><br>'.$column_placeholders;
			//echo '<pre>'; print_r($column_value); echo '</pre>';
			//die;
			
			//Insert new url record and get the id for the record url record inserted.
			if($table_name == 'urls')
			{
				$created_row_id = $results->getInsertRecord(__LINE__, __FILE__, $table_name, $column_names, $column_placeholders, $column_value);
				$url_created = $results->getSelectLastRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ?', [$_SESSION["site_set_for_editing"]]);
			}
			//admin_one_record tells us that there is one row in the daabase for the whole site.
			elseif($_SESSION['admin_one_record'] == 'Yes')
			{
				$created_row_id = $results->getInsertRecord(__LINE__, __FILE__, $table_name, $column_names, $column_placeholders, $column_value);
				
				//Update url record if urls_id is on record.
				if(isset($post_values['urls']))
				{
					//Get last record id created.
					$record_created = $results->getSelectLastRecord(__LINE__, __FILE__, '*', $table_name, 'WHERE `site_id` = ?', [$_SESSION["site_set_for_editing"]]);
					//Update url record with last record id created.
					$results->getUpdateRecord(__LINE__, __FILE__, 'urls', 'record_id = ?', 'WHERE `id` = ? AND `site_id` = ?', [$record_created['id'], $url_created['id'], $_SESSION["site_set_for_editing"]]);
				}
			}
			//Insert new record into database.
			else
			{
				//Create record
				$created_row_id = $results->getInsertRecord(__LINE__, __FILE__, $table_name, $column_names, $column_placeholders, $column_value);
				
				//Update url record if urls_id is on record.
				if(isset($post_values['urls']))
				{
					//Get last record id created.
					$record_created = $results->getSelectLastRecord(__LINE__, __FILE__, '*', $table_name, 'WHERE `site_id` = ?', [$_SESSION["site_set_for_editing"]]);
					
					//Update url record with last record id created.
					$results->getUpdateRecord(__LINE__, __FILE__, 'urls', 'record_id = ?', 'WHERE `id` = ? AND `site_id` = ?', [$record_created['id'], $url_created['id'], $_SESSION["site_set_for_editing"]]);
				}
			}
			
			//Clear cache on save.
			if($_SESSION['admin_site_id_global'] == 'No')
			{
				clearSiteCache($_SESSION['site_set_for_editing']);
			}
			else
			{
				clearAllSiteCache();
			}
			
			//Insert data after save
			if(file_exists(INSTALLATION_ROOT.'/admin/cms/layouts/add/includes/insert-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/cms/layouts/add/includes/insert-data-after.php';
			}
			
			if($commerce_installed && file_exists(INSTALLATION_ROOT.'/admin/commerce/layouts/add/includes/insert-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/commerce/layouts/add/includes/insert-data-after.php';
			}
			
			if($erp_installed && file_exists(INSTALLATION_ROOT.'/admin/erp/layouts/add/includes/insert-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/erp/layouts/add/includes/insert-data-after.php';
			}
			
			//Update data after save
			if(file_exists(INSTALLATION_ROOT.'/admin/cms/layouts/add/includes/update-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/cms/layouts/add/includes/update-data-after.php';
			}
			
			if($commerce_installed && file_exists(INSTALLATION_ROOT.'/admin/commerce/layouts/add/includes/update-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/commerce/layouts/add/includes/update-data-after.php';
			}
			
			if($erp_installed && file_exists(INSTALLATION_ROOT.'/admin/erp/layouts/add/includes/update-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/erp/layouts/add/includes/update-data-after.php';
			}
		}
		
		if(!empty($row['save_url']) && !empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET["sub-rid"] ?? '')))
		{
			header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&sub-rid=".trim($_GET["sub-rid"] ?? '')."&created=success"); exit();
		}
		elseif(!empty($row['save_url']) && !empty(trim($_GET["rid"] ?? '')) && empty(trim($_GET["sub-rid"] ?? '')))
		{
			header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&created=success"); exit();
		}
		elseif(!empty($row['save_url']))
		{
			header("Location: /".$_SESSION['admin_save_url']."/?created=success"); exit();
		}
	}
}