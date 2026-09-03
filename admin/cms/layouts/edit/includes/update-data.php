<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/edit/includes/update-data.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/edit/includes/update-data.php');
}
else
{
	//echo '<pre>'; print_r($errors); echo '</pre>';
	//echo '<pre>'; print_r($post_values); echo '</pre>';
	//die;
	if(!empty($_POST) && !empty($post_values) && empty($errors) && !isset($_POST['change_site']))
	{
		//echo '<pre>'; print_r($admin_fields); echo '<pre>';
		//echo '<pre>'; print_r($post_values); echo '<pre>';
		
		foreach($post_values as $table_name => $value_1)
		{
			$column_names = '';
			$column_value = array();
			
			foreach($value_1 as $key_2 => $value_2)
			{
				$column_data_type = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `column_name` = ?', [$key_2]);
				
				//Only update data for admins fields that are set to Yes for saving data.
				if($column_data_type['update_field_on_save'] == 'Yes')
				{
					//These are not allowed, $key_2 != 'flat_url' && $key_2 != 'path_level' && $key_2 != 'hierarchy_url', as the redirect page updates these if they are changed.
					if(
					   ($key_2 == 'password' && !empty($value_2)) 
					   || ($key_2 == 'api_client_secret' && !empty($value_2)) 
					   || ($key_2 == 'api_public_key' && !empty($value_2)) 
					   || ($key_2 == 'api_secret_key' && !empty($value_2))
					   || ($key_2 == 'smtp_email_password' && !empty($value_2))
					   
					   || ($key_2 != 'id' && $key_2 != 'created_date' && $key_2 != 'created_by' && $key_2 != 'flat_url' && $key_2 != 'path_level' && $key_2 != 'hierarchy_url' && $key_2 != 'password' && $key_2 != 'api_client_secret' && $key_2 != 'api_public_key' && $key_2 != 'api_secret_key' && $key_2 != 'smtp_email_password')
					   )
					{
						if($key_2 == 'updated_date' || $key_2 == 'answered_date' || $key_2 == 'approved_date')
						{
							$column_names .= '`'.$key_2.'` = UTC_TIMESTAMP(),';
						}
						elseif($key_2 == 'answered_by' || $key_2 == 'approved_by')
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = $_SESSION['user_first_last_name'];
						}
						elseif($key_2 == 'updated_by')
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = $_SESSION['user_username'];
						}
						elseif($key_2 == 'site_id')
						{
							if($value_2 == '0')
							{
								$column_names .= '`'.$key_2.'` = ?,';
								$column_value[] = '0';
							}
							else
							{
								$column_names .= '`'.$key_2.'` = ?,';
								$column_value[] = $_SESSION["site_set_for_editing"];
							}
						}
						elseif($key_2 == 'custom_fields' && empty($value_2))
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = '{}';
						}
						elseif($key_2 == 'file_code')
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = '';
							$posted_template_file_code = $value_2;
						}
						elseif($key_2 == 'filename')
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = $value_2;
							$current_template_file_name = $current_values['template_files']['filename'];
							$posted_template_file_name = $value_2;
						}
						elseif($key_2 == 'table_name' && $_SESSION['admin_table_name'] != 'admin_pages')
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = $_SESSION['admin_table_name'];
						}
						elseif($key_2 == 'password')
						{
							if(!empty($value_2))
							{
								$column_names .= '`'.$key_2.'` = ?,';
								$column_value[] = $value_2;
							}
						}
						elseif(strpos($column_data_type["data_type"], 'decimal') !== false)
						{
							$column_names .= '`'.$key_2.'` = ?,';
							if(!empty($value_2))
							{
								$column_value[] = str_replace(',', '.', $value_2);
							}
							else
							{
								$column_value[] = NULL;
							}
						}
						elseif($key_2 == 'admin_pages_id' && empty($value_2))
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = '0';
						}
						elseif($key_2 == 'admin_pages_parent_code' && empty($value_2))
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = '0';
						}
						else
						{
							$column_names .= '`'.$key_2.'` = ?,';
							$column_value[] = $value_2;
						}
					}
				}
			}
			
			$column_names = trim($column_names, ',');
			
			//admin_one_record tells us that there is one row in the daabase for the whole site. 
			if($table_name == 'sites')
			{
				$column_value[] = $_SESSION["site_set_for_editing"];
				$results->getUpdateRecord(__LINE__, __FILE__, $table_name, $column_names, 'WHERE `id` = ?', $column_value);
			}
			//admin_one_record tells us that this will use a one page form for the parent page. admin_site_id_global tells us that there will be one record for all site so use site_id == 0.  
			elseif($_SESSION['admin_one_record'] == 'Yes' && $_SESSION['admin_site_id_global'] == 'Yes')
			{
				$column_value[] = 0;
				$results->getUpdateRecord(__LINE__, __FILE__, $table_name, $column_names, 'WHERE `site_id` = ?', $column_value);
			}
			//admin_one_record tells us that this will use a one page form for the parent page. //Ship To, Bill To, Etc., on orders uses this.
			elseif($_SESSION['admin_one_record'] == 'Yes' && $_SESSION['admin_sub_page'] == 'Yes' && !empty($_SESSION['admin_table_link_column']) && !empty($_SESSION['admin_parent_table_name']))
			{
				$column_value[] = trim($_GET["rid"] ?? '');
				$results->getUpdateRecord(__LINE__, __FILE__, $table_name, $column_names, 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ?', $column_value);
			}
			//admin_one_record tells us that this will use a one page form for the parent page.
			elseif($_SESSION['admin_one_record'] == 'Yes')
			{
				$column_value[] = $_SESSION["site_set_for_editing"];
				$results->getUpdateRecord(__LINE__, __FILE__, $table_name, $column_names, 'WHERE `site_id` = ?', $column_value);
			}
			//If main record has urls_column save record with urls_id
			elseif(isset($value_1['urls_id']))
			{
				$column_value[] = trim($_GET["rid"] ?? '');
				$results->getUpdateRecord(__LINE__, __FILE__, $table_name, $column_names, 'WHERE `urls_id` = ?', $column_value);
			}
			//If not one_record for the site, then get the id from the url for the row being requested in database.
			elseif($table_name == 'urls')
			{
				$column_value[] = trim($_GET["rid"] ?? '');
				$column_value[] = $_SESSION['admin_table_name'];
				$results->getUpdateRecord(__LINE__, __FILE__, $table_name, $column_names, 'WHERE `id` = ? AND `table_name` = ? ', $column_value);
			}
			else
			{
				$column_value[] = trim($_GET["rid"] ?? '');
				$results->getUpdateRecord(__LINE__, __FILE__, $table_name, $column_names, 'WHERE `id` = ?', $column_value);
			}
			
			//echo '<br><br>'.$table_name;
			//echo '<br><br>'.$column_names;
			//echo '<pre>'; print_r($column_value); echo '</pre>';
			//die;
			
			//Clear cache on save.
			if($_SESSION['admin_site_id_global'] == 'No')
			{
				clearSiteCache($_SESSION['site_set_for_editing']);
			}
			else
			{
				clearAllSiteCache();
			}
			
			//Update data after save
			if(file_exists(INSTALLATION_ROOT.'/admin/cms/layouts/edit/includes/update-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/cms/layouts/edit/includes/update-data-after.php';
			}
			
			if($commerce_installed && file_exists(INSTALLATION_ROOT.'/admin/commerce/layouts/edit/includes/update-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/commerce/layouts/edit/includes/update-data-after.php';
			}
			
			if($erp_installed && file_exists(INSTALLATION_ROOT.'/admin/erp/layouts/edit/includes/update-data-after.php'))
			{
				include_once INSTALLATION_ROOT.'/admin/erp/layouts/edit/includes/update-data-after.php';
			}
		}
		
		if(!empty($row['save_url']) && isset($_GET['sub-page-rid']) && !empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET["sub-rid"] ?? '')))
		{
			header("Location: ".$_SESSION['admin_save_url']."/?rid=".$_GET['sub-page-rid']."&sub-rid=".trim($_GET["sub-rid"] ?? '')."&updated=success");
			exit();
		}
		elseif(!empty($row['save_url']) && isset($_GET['sub-page-rid']) && !empty($_GET['sub-page-rid']) && !empty(trim($_GET["rid"] ?? '')) && empty(trim($_GET["sub-rid"] ?? '')) && strpos($_SESSION['admin_save_url'].'/', '/edit/') !== false)
		{
			//Added this rule for /admin/website/template-files/edit/?sub-page-rid=1&rid=41
			header("Location: ".$_SESSION['admin_save_url']."/?sub-page-rid=".$_GET['sub-page-rid']."&rid=".trim($_GET["rid"] ?? '')."&updated=success");
			exit();
		}
		elseif(!empty($row['save_url']) && isset($_GET['sub-page-rid']) && !empty($_GET['sub-page-rid']) && !empty(trim($_GET["rid"] ?? '')) && empty(trim($_GET["sub-rid"] ?? '')))
		{
			header("Location: ".$_SESSION['admin_save_url']."/?rid=".$_GET['sub-page-rid']."&updated=success");
			exit();
		}
		elseif(!empty($row['save_url']))
		{
			header("Location: ".$_SESSION['admin_save_url']."/?updated=success");
			exit();
		}
		elseif(!empty(trim($_GET["rid"] ?? '')))
		{
			header("Location: ?rid=".trim($_GET["rid"] ?? '')."&updated=success");
			exit();
		}
	}
}