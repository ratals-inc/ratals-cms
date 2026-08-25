<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__));
}

require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/index.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/index.php');
}
else
{
	header("Cache-Control: no-cache, no-store, must-revalidate");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	//Make sure server software allows .htaccess rules to run.
	require_once(INSTALLATION_ROOT.'/core/server-software.php');
	
	//If user not logged in display login page.
	if(!isset($_SESSION['user_id']))
	{
		include_once 'login.php';
	}
	//If user is logged in get admin_pages data to load the admin page.
	elseif(isset($_SESSION['user_id']))
	{
		//Get sites in account.
		$sql_sites_in_account = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'sites', '', [], 'id');
		
		if(!empty($sql_sites_in_account))
		{
			foreach($sql_sites_in_account as $sql_sites_in_account_rows)
			{
				//Set selected site language.
				$sites_language_array[$sql_sites_in_account_rows["id"]] = $sql_sites_in_account_rows["site_language"];
				
				//Set selected site domain.
				$url_as_https = 'http://';
				if($sql_sites_in_account_rows["https_in_url"] == 'Yes')
				{
					$url_as_https = 'https://';
				}
				
				$url_as_www = '';
				if($sql_sites_in_account_rows["www_in_url"] == 'Yes')
				{
					$url_as_www = 'www.';
				}
				
				$view_frontend_of_site_array[$sql_sites_in_account_rows["id"]] = $url_as_https.$url_as_www.$sql_sites_in_account_rows["domain"];
				
				if($_SESSION["site_set_for_editing"] == $sql_sites_in_account_rows["id"])
				{
					$view_frontend_of_site = $url_as_https.$url_as_www.$sql_sites_in_account_rows["domain"];
					
					$_SESSION['view_frontend_of_site'] = $url_as_https.$url_as_www.$sql_sites_in_account_rows["domain"];
				}
			}
		}
		
		//Update admin user sessions on every page load in case setting change on admin user.
		$admin_user_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'users', 'WHERE `id` = ? AND `status` = ?', [$_SESSION['user_id'], 1]);
		
		if(!empty($admin_user_row)) 
		{
			$_SESSION['user_username'] = $admin_user_row['username'];
			$_SESSION['user_first_last_name'] = $admin_user_row['first_name']." ".$admin_user_row['last_name'];
			$_SESSION['user_street_address_1'] = $admin_user_row['street_address_1'];
			$_SESSION['user_street_address_2'] = $admin_user_row['street_address_2'];
			$_SESSION['user_city'] = $admin_user_row['city'];
			$_SESSION['user_state'] = $admin_user_row['state'];
			$_SESSION['user_postal_code'] = $admin_user_row['postal_code'];
			$_SESSION['user_phone_number'] = $admin_user_row['phone_number'];
			$_SESSION['user_phone_number_ext'] = $admin_user_row['phone_number_ext'];
			$_SESSION['user_email_address'] = $admin_user_row['user_email_address'];
			$_SESSION['user_email_cc'] = $admin_user_row['user_email_cc'];
			$_SESSION['user_email_bcc'] = $admin_user_row['user_email_bcc'];
			$_SESSION['user_email_signature'] = $admin_user_row['user_email_signature'];
			$_SESSION['admin_language'] = $sites_language_array[$_SESSION["site_set_for_editing"]];
			$_SESSION['user_admin_permissions_id'] = $admin_user_row['admin_permissions_id'];
			$_SESSION['user_allow_software_update_messages'] = $admin_user_row['allow_software_update_messages'];
			$_SESSION['user_admin_permissions_set_ids'] = '';
			$_SESSION['user_admin_permissions_default_url'] = '';
			$_SESSION['user_permissions_admin_page_system_codes'] = array();
			if(!empty($_SESSION['user_admin_permissions_id']))
			{
				$permissions_admin_pages_ids = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'permissions', 'WHERE `id` = ?', [$_SESSION['user_admin_permissions_id']]);
				//admin_pages ids approved to use because of permissions set.
				if(isset($permissions_admin_pages_ids['admin_pages_ids']) && !empty($permissions_admin_pages_ids['admin_pages_ids']))
				{
					$_SESSION['user_admin_permissions_set_ids'] = $permissions_admin_pages_ids['admin_pages_ids'];
					
					$permissions_admin_pages = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_pages', '', [], 'id');
					
					if(!empty($permissions_admin_pages))
					{
						$permissions_admin_pages_ids_set = explode(',' ,trim($permissions_admin_pages_ids['admin_pages_ids'], ','));
						
						if(!empty($permissions_admin_pages_ids_set))
						{
							foreach($permissions_admin_pages_ids_set as $permissions_admin_page_id)
							{
								if(array_key_exists($permissions_admin_page_id, $permissions_admin_pages))
								{
									$_SESSION['user_permissions_admin_page_system_codes'][] = $permissions_admin_pages[$permissions_admin_page_id]['system_code'];
								}
							}
						}
					}
				}
				
				//Default admin page set within permissions.
				if(isset($permissions_admin_pages_ids['default_admin_page']) && !empty($permissions_admin_pages_ids['default_admin_page']))
				{
					$default_admin_url = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', 'WHERE `id` = ?', [$permissions_admin_pages_ids['default_admin_page']]);
					
					$_SESSION['user_admin_permissions_default_url'] = $domain.'/'.$_SESSION['admin_directory'].'/'.$default_admin_url['url'].'/';
				}
			}
			$_SESSION['user_site_permissions_id'] = $admin_user_row['site_permissions_id'];
		}
		else
		{
			session_unset();
			session_destroy();
			include_once 'login.php';
			die;
		}
		
		//If an admin user is logged in and a super admin removes the site from the user, set the next site avaiable to the admin user.
		if(!empty($_SESSION['user_site_permissions_id']) && strpos($_SESSION['user_site_permissions_id'], ','.$_SESSION["site_set_for_editing"].',') === false)
		{
			$new_site_ids = explode(',', trim($_SESSION['user_site_permissions_id'], ','));
			$_SESSION["site_set_for_editing"] = $new_site_ids[0];
		}
	
		//If logged in and revisit /$_SESSION['admin_directory']/ send to default admin page.
		$current_url = explode('?', $url);
		if($current_url[0] == $domain.'/'.$_SESSION['admin_directory'].'/')
		{
			//If permissions id is set on user, send to default admin page set on the permission. 
			if(!empty($_SESSION['user_admin_permissions_default_url']))
			{
				header("Location: ".$_SESSION['user_admin_permissions_default_url']); 
				exit();
			}
			//If no permissions id is set on user, send to dashboard. 
			else
			{
				header("Location: ".$domain."/".$_SESSION['admin_directory']."/dashboard/"); 
				exit();
			}
		}
		
		//Get admin url being requested and see if its in admin_pages table to load admin page.
		$admin_url = trim(ltrim($path_url ?? '', $_SESSION['admin_directory']), '/');
		
		$row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', 'WHERE `url` = ?', [$admin_url]);
		
		if(!empty($row))
		{
			//If admin user does not have permissions to access admin page stop admin from loading.
			if(!empty($_SESSION['user_admin_permissions_id']) && strpos($_SESSION['user_admin_permissions_set_ids'], ','.$row['id'].',') === false)
			{
				echo 'You don\'t have admin permissions to access this area of the admin. Please contact your administrator to grant access.';
				die;
			}
			
			$_SESSION['admin_id'] = $row['id'];
			$_SESSION['admin_title'] = str_replace('[ADMIN_DIRECTORY]', $_SESSION['admin_directory'], $row['admin_pages_name']);
			$_SESSION['admin_url'] = $_SESSION['admin_directory'].'/'.$row['url'];
			$_SESSION['admin_add_url'] = $_SESSION['admin_directory'].'/'.$row['add_url'];
			$_SESSION['admin_edit_url'] = $_SESSION['admin_directory'].'/'.$row['edit_url'];
			$_SESSION['admin_sub_items_url'] = $_SESSION['admin_directory'].'/'.$row['sub_items_url'];
			$_SESSION['admin_sub_items_add_url'] = $_SESSION['admin_directory'].'/'.$row['sub_items_add_url'];
			$_SESSION['admin_sub_items_edit_url'] = $_SESSION['admin_directory'].'/'.$row['sub_items_edit_url'];
			$_SESSION['admin_save_url'] = $_SESSION['admin_directory'].'/'.$row['save_url'];
			$_SESSION['admin_url_no_records'] = '';
			if(!empty($row['no_record_url'])){ $_SESSION['admin_url_no_records'] = '/'.$_SESSION['admin_directory'].'/'.$row['no_record_url'].'/'; }
			$_SESSION['admin_help_video_url'] = $row['help_video_url'];
			$_SESSION['admin_type'] = $row['type'];
			$_SESSION['admin_table_name'] = $row['table_name'];
			$_SESSION['admin_table_link_column'] = $row['table_link_column'];
			$_SESSION['admin_parent_table_name'] = $row['parent_table_name'];
			$_SESSION['admin_parent_table_link_column'] = $row['parent_table_link_column'];
			$_SESSION['admin_child_table_name'] = $row['child_table_name'];
			$_SESSION['admin_child_table_link_column'] = $row['child_table_link_column'];
			$_SESSION['admin_pages_parent_code'] = $row['admin_pages_parent_code'];
			$_SESSION['admin_system_code'] = $row['system_code'];
			$_SESSION['admin_page_level'] = $row['admin_page_level'];
			$_SESSION['admin_sub_page'] = $row['sub_page'];
			$_SESSION['admin_sort_or_dragdrop'] = $row['sort_or_dragdrop'];
			$_SESSION['admin_site_id_global'] = $row['global'];
			$_SESSION['admin_one_record'] = $row['one_record'];
			$_SESSION['admin_parent_indicator'] = $row['parent_indicator'];
			$_SESSION['admin_assigned_type'] = $row['admin_pages_assigned_type'];
			$_SESSION['admin_js_name'] = $row['js_name'];
			$_SESSION['admin_class'] = $row['class'];
			$_SESSION['admin_submit_button_label'] = $row['submit_button_label'];
			$_SESSION['admin_submit_button_type'] = $row['submit_button_type'];
			
			//Look up database column ids for the main table of the admin page.
			if(!empty($_SESSION['admin_table_name']))
			{
				$database_table_set = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `database_table_name` = ?', [$_SESSION['admin_table_name']]);
				
				if(!empty($database_table_set))
				{
					$_SESSION['admin_fields_ids'] = trim($database_table_set['admin_fields_ids'] ?? '', ',');
				}
				else
				{
					echo 'This section of the admin area is trying to connect to the database table of "'.$_SESSION['admin_table_name'].'". and cannot find it in the database table of "database_tables".';
					die;
				}
			}
			else
			{
				echo 'Make sure you have set a database "Table Name" on this admin page.';
				die;
			}
			
			//Look up database column ids for the parent table of the admin page.
			if(!empty($_SESSION['admin_parent_table_name']))
			{
				$database_parent_table_set = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `database_table_name` = ?', [$_SESSION['admin_parent_table_name']]);
				
				if(!empty($database_parent_table_set))
				{
					$_SESSION['admin_parent_table_fields_ids'] = trim($database_parent_table_set['admin_fields_ids'] ?? '', ',');
				}
				else
				{
					echo 'This section of the admin area is trying to connect to the database table of "'.$_SESSION['admin_parent_table_name'].'". and cannot find it in the database table of "database_tables".';
					die;
				}
			}
			
			//Look up database column ids for the child table of the admin page.
			if(!empty($_SESSION['admin_child_table_name']))
			{
				$database_child_table_set = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `database_table_name` = ?', [$_SESSION['admin_child_table_name']]);
				
				if(!empty($database_child_table_set))
				{
					$_SESSION['admin_child_table_fields_ids'] = trim($database_child_table_set['admin_fields_ids'] ?? '', ',');
				}
				else
				{
					echo 'This section of the admin area is trying to connect to the database table of "'.$_SESSION['admin_child_table_name'].'". and cannot find it in the database table of "database_tables".';
					die;
				}
			}
			
			//Get admin_fields urls_id row. This is used to know if an admin_pages has a urls_id associated with it.
			$_SESSION['admin_fields_urls_id'] = '';
			$_SESSION['admin_urls_column_placeholders'] = '';
			$_SESSION['admin_urls_column_ids'] = '';
			$_SESSION['admin_urls_column_ids_doubled'] = array();
			
			if(!empty($admin_fields_urls_id))
			{
				$_SESSION['admin_fields_urls_id'] = $admin_fields_urls_id;
				
				$database_table_urls = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `database_table_name` = ?', ['urls']);
				
				if(!empty($database_table_urls['admin_fields_ids']))
				{
					$url_admin_fields_ids = explode(',', trim($database_table_urls['admin_fields_ids'], ','));
					
					$url_admin_fields_id_placeholders = '';
					for ($i = 1; $i <= count($url_admin_fields_ids); $i++)
					{
						$url_admin_fields_id_placeholders .= '?,';
					}
					
					$_SESSION['admin_urls_column_placeholders'] = trim($url_admin_fields_id_placeholders, ',');
					$_SESSION['admin_urls_column_ids'] = trim($database_table_urls['admin_fields_ids'], ',');
					$_SESSION['admin_urls_column_ids_doubled'] = array_merge($url_admin_fields_ids, $url_admin_fields_ids);
				}
			}
			
			//Checks to see if the admin page being requested has the urls_id column.
			$_SESSION['record_has_url'] = 'No';
			if(strpos($_SESSION['admin_fields_ids'] ?? '', ','.$admin_fields_urls_id.',') !== false)
			{
				$_SESSION['record_has_url'] = 'Yes';
			}
			
			//Checks to see if the parent table of admin page being requested has the urls_id column.
			$_SESSION['parent_table_record_has_url'] = 'No';
			if(strpos($_SESSION['admin_parent_table_fields_ids'] ?? '', ','.$admin_fields_urls_id.',') !== false)
			{
				$_SESSION['parent_table_record_has_url'] = 'Yes';
			}
			
			//Checks to see if the child table of admin page being requested has the urls_id column.
			$_SESSION['child_table_record_has_url'] = 'No';
			if(strpos($_SESSION['admin_child_table_fields_ids'] ?? '', ','.$admin_fields_urls_id.',') !== false)
			{
				$_SESSION['child_table_record_has_url'] = 'Yes';
			}
			
			$_SESSION['admin_edit_rid'] = ''; 
			$_SESSION['admin_edit_sub_rid'] = '';
			$_SESSION['admin_sub_page_rid'] = '';
			$_SESSION['admin_url_with_rid'] = $_SESSION['admin_url'].'/';
			$_SESSION['admin_sub_items_url_with_rid'] = $_SESSION['admin_url'].'/';
			$_SESSION['admin_sub_items_add_url_with_rid'] = $_SESSION['admin_url'].'/';
			$_SESSION['admin_sub_items_edit_url_with_rid'] = $_SESSION['admin_url'].'/';
			$_SESSION['admin_save_url_with_rid'] = $_SESSION['admin_url'].'/';
			
			if(isset($_GET["rid"]) && isset($_GET["sub-rid"])) 
			{
				$_SESSION['admin_edit_rid'] = trim($_GET["rid"] ?? ''); 
				$_SESSION['admin_edit_sub_rid'] = trim($_GET["sub-rid"] ?? ''); 
				$_SESSION['admin_url_with_rid'] = $_SESSION['admin_url']."/?rid=".$_SESSION['admin_edit_rid']."&sub-rid=".$_SESSION['admin_edit_sub_rid'];
				$_SESSION['admin_sub_items_url_with_rid'] = $_SESSION['admin_sub_items_url']."/?rid=".$_SESSION['admin_edit_rid']."&sub-rid=".$_SESSION['admin_edit_sub_rid'];
				$_SESSION['admin_sub_items_add_url_with_rid'] = $_SESSION['admin_sub_items_add_url']."/?rid=".$_SESSION['admin_edit_rid']."&sub-rid=".$_SESSION['admin_edit_sub_rid'];
				$_SESSION['admin_sub_items_edit_url_with_rid'] = $_SESSION['admin_sub_items_edit_url']."/?rid=".$_SESSION['admin_edit_rid']."&sub-rid=".$_SESSION['admin_edit_sub_rid'];
				$_SESSION['admin_save_url_with_rid'] = $_SESSION['admin_save_url']."/?rid=".$_SESSION['admin_edit_rid']."&sub-rid=".$_SESSION['admin_edit_sub_rid'];
				
			} 
			elseif(isset($_GET["rid"]))
			{
				$_SESSION['admin_edit_rid'] = trim($_GET["rid"] ?? ''); 
				$_SESSION['admin_url_with_rid'] = $_SESSION['admin_url']."/?rid=".$_SESSION['admin_edit_rid'];
				$_SESSION['admin_sub_items_url_with_rid'] = $_SESSION['admin_sub_items_url']."/?rid=".$_SESSION['admin_edit_rid'];
				$_SESSION['admin_sub_items_add_url_with_rid'] = $_SESSION['admin_sub_items_add_url']."/?rid=".$_SESSION['admin_edit_rid'];
				$_SESSION['admin_sub_items_edit_url_with_rid'] = $_SESSION['admin_sub_items_edit_url']."/?rid=".$_SESSION['admin_edit_rid'];
				$_SESSION['admin_save_url_with_rid'] = $_SESSION['admin_save_url']."/?rid=".$_SESSION['admin_edit_rid'];
			}
			
			if(isset($_GET['sub-page-rid']))
			{
				$_SESSION['admin_sub_page_rid'] = $_GET['sub-page-rid'];
			}
			
			//Need to get these lists so database row ids can display the correct label name.
			//Get all dynamic lists from admin_fields_lists in db.
			$label_names = array();
			$dynamic_options_list = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_lists', '', [], 'system_code');
			$column_data_dynamic_lists = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `dynamic` = ?', ['Yes'], 'system_code');
			
			if(!empty($column_data_dynamic_lists))
			{
				foreach($column_data_dynamic_lists as $column_data_dynamic_list)
				{
					if(!empty($column_data_dynamic_list['dynamic_table_name']) && !empty($column_data_dynamic_list['dynamic_column_id']) && !empty($column_data_dynamic_list['dynamic_column_label']))
					{
						$label_names[$column_data_dynamic_list['dynamic_table_name'].'_'.$column_data_dynamic_list['system_code']] = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '`'.$column_data_dynamic_list['dynamic_column_id'].'`, `'.$column_data_dynamic_list['dynamic_column_label'].'`', $column_data_dynamic_list['dynamic_table_name'], 'WHERE '.$column_data_dynamic_list['dynamic_column_label'].' != ""' , [], $column_data_dynamic_list['dynamic_column_id']);
					}
				}
			}
			
			//Get all lists from admin_fields_values in db.
			$label_names['admin_fields_values'] = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_values', '', [], 'id');
			
			//Get all lists from custom_fields_options in db with key as ID.
			$custom_fields_options = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'custom_fields_options', 'WHERE (`site_id` = ? OR `site_id` = ?)', [$_SESSION["site_set_for_editing"], 0], 'id');
			foreach($custom_fields_options as $key => $value)
			{
				$option_data = JSON_DECODE($value['option_data'] ?? '', true);
				
				$value['label'] = $option_data[$sites_language_array[$_SESSION["site_set_for_editing"]]]['label'] ?? '';
				$value['value'] = $option_data[$sites_language_array[$_SESSION["site_set_for_editing"]]]['value'] ?? '';
				
				$label_names['custom_fields_options'][$key] = $value;
			}
			
			//If sub_url not empty, get the name of it for sub menus h1 heading.
			$page_sub_url_name = '';
			if(!empty($row['sub_url']))
			{
				$page_sub_url_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', 'WHERE `url` = ?', [$row['sub_url']]);
				
				if(!empty($page_sub_url_row))
				{
					$page_sub_url_name = str_replace('[ADMIN_DIRECTORY]', $_SESSION['admin_directory'], $page_sub_url_row['admin_pages_name']);
				}
			}
			
			//If record id does not exist redirect to no record URL.
			if($_SESSION['admin_table_name'] == 'accounting_journal_entries' && !empty($_SESSION['admin_assigned_type']) && $_SESSION['admin_assigned_type'] == 'accounting_edit_journal_entry' && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `journal_group_number` = ?', [$_GET["rid"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['admin_one_record'] == 'No' && $_SESSION['admin_site_id_global'] == 'No' && !empty($_SESSION['admin_table_name']) && !empty($_SESSION['admin_table_link_column']) && isset($_GET["rid"]) && !empty($_GET["rid"]) && isset($_GET["sub-page-rid"]) && !empty($_GET["sub-page-rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `id` = ? AND `'.$_SESSION['admin_table_link_column'].'` = ? AND `site_id` = ?', [$_GET["rid"], $_GET["sub-page-rid"], $_SESSION["site_set_for_editing"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['admin_one_record'] == 'No' && $_SESSION['admin_site_id_global'] == 'Yes' && !empty($_SESSION['admin_table_name']) && !empty($_SESSION['admin_table_link_column']) && isset($_GET["rid"]) && !empty($_GET["rid"]) && isset($_GET["sub-page-rid"]) && !empty($_GET["sub-page-rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `id` = ? AND `'.$_SESSION['admin_table_link_column'].'` = ?', [$_GET["rid"], $_GET["sub-page-rid"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['child_table_record_has_url'] == 'Yes' && $_SESSION['admin_sub_page'] == 'No' && !empty($_SESSION['admin_child_table_name']) && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_child_table_name'], 'WHERE `urls_id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['parent_table_record_has_url'] == 'Yes' && $_SESSION['admin_sub_page'] == 'No' && !empty($_SESSION['admin_parent_table_name']) && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_parent_table_name'], 'WHERE `urls_id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['record_has_url'] == 'Yes' && $_SESSION['admin_sub_page'] == 'No' && !empty($_SESSION['admin_table_name']) && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `urls_id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['admin_sub_page'] == 'Yes' && $_SESSION['admin_one_record'] == 'Yes' && !empty($_SESSION['admin_table_name']) && !empty($_SESSION['admin_table_link_column']) && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `'.$_SESSION['admin_table_link_column'].'` = ?', [trim($_GET["rid"] ?? '')]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['admin_one_record'] == 'No' && $_SESSION['admin_site_id_global'] == 'No' && !empty($_SESSION['admin_parent_table_name']) && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_parent_table_name'], 'WHERE `id` = ? AND `site_id` = ?', [$_GET["rid"], $_SESSION["site_set_for_editing"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['admin_one_record'] == 'No' && $_SESSION['admin_site_id_global'] == 'No' && !empty($_SESSION['admin_table_name']) && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['admin_one_record'] == 'No' && $_SESSION['admin_site_id_global'] == 'Yes' && !empty($_SESSION['admin_parent_table_name']) && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_parent_table_name'], 'WHERE `id` = ?', [$_GET["rid"]]);
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			elseif($_SESSION['admin_one_record'] == 'No' && $_SESSION['admin_site_id_global'] == 'Yes' && !empty($_SESSION['admin_table_name']) && isset($_GET["rid"]) && !empty($_GET["rid"]))
			{
				$redirect_no_record = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
				
				if(empty($redirect_no_record) && !empty($_SESSION['admin_url_no_records'])) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
			}
			
			//Dont allow insert, update or delete after stutus level set on parent table.
			$status_lock_insert_update_delete = 'No';
			$admin_page_status = '';
			if(!empty($_SESSION['admin_parent_table_name']))
			{
				if($_SESSION['admin_type'] == 'edit')
				{
					$parent_table_status = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_parent_table_name'], 'WHERE `id` = ?', [trim($_GET["sub-page-rid"] ?? '')]);
				}
				else
				{
					$parent_table_status = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_parent_table_name'], 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
					//echo 'here<pre>'; print_r($parent_table_status); echo '</pre>';
				}
				
				if(!empty($parent_table_status))
				{
					if(isset($parent_table_status['bills_status']) && $parent_table_status['bills_status'] != 'Draft')
					{
						$status_lock_insert_update_delete = 'Yes';
						$admin_page_status = $parent_table_status['bills_status'];
					}
				}
			}
			
			$account_message = '<div class="account-message"><p>Your Ratals subscription could not be renewed. Please update your payment information in your <a href="https://www.ratals.com/account/" target="_blank">Ratals account</a> to restore access.</p></div>';
			
			//Make sure session-check-admin.php is being included within all admin page url requests. If not, warn admin user to fix.
			$doc_root = rtrim(INSTALLATION_ROOT, '/');
			$server_software = strtolower($_SERVER['SERVER_SOFTWARE'] ?? '');
			if(stripos($server_software, 'apache') !== false || stripos($server_software, 'litespeed') !== false)
			{
				$admin_htaccess_path = $doc_root.'/admin/.htaccess';
				$expected_line = 'php_value auto_prepend_file "'.$doc_root.'/core/session-check-admin.php"';
				
				if(is_readable($admin_htaccess_path)) 
				{
					$contents = file_get_contents($admin_htaccess_path);
					
					if(strpos($contents, $expected_line) === false) 
					{
						echo '<p><strong>Security Warning:</strong> Your "/admin/.htaccess" file is not properly configured to include "session-check-admin.php" on all admin requests.</p>
						<p>This could allow unauthorized access to admin pages or files.</p>
						<p>Please update your "/admin/.htaccess" file to include:<br><code>'.htmlspecialchars($expected_line).'</code></p>
						<p>See documentation for instructions: <a href="https://www.ratals.com/" target="_blank">Fix Instructions</a>.</p>';
					}
				}
				else
				{
					echo '<p><strong>Warning:</strong> Unable to read "/admin/.htaccess". Please ensure the file exists and has proper permissions.</p>';
				}
			}
			elseif(stripos($server_software, 'nginx') !== false)
			{
				$admin_htaccess_path = $doc_root.'/admin/.user.ini';
				$expected_line = 'auto_prepend_file = "'.$doc_root.'/core/session-check-admin.php"';
				
				if(is_readable($admin_htaccess_path)) 
				{
					$contents = file_get_contents($admin_htaccess_path);
					
					if(strpos($contents, $expected_line) === false) 
					{
						echo '<p><strong>Security Warning:</strong> Your "/admin/.user.ini" file is not properly configured to include "session-check-admin.php" on all admin requests.</p>
						<p>This could allow unauthorized access to admin pages or files.</p>
						<p>Please update your "/admin/.user.ini" file to include:<br><code>'.htmlspecialchars($expected_line).'</code></p>
						<p>See documentation for instructions: <a href="https://www.ratals.com/" target="_blank">Fix Instructions</a>.</p>';
					}
				}
				else
				{
					echo '<p><strong>Warning:</strong> Unable to read "/admin/.user.ini". Please ensure the file exists and has proper permissions.</p>';
				}
			}
			
			//Load admin page type with router index.php file.
			if($_SESSION['admin_type'] == 'table')
			{
				include_once INSTALLATION_ROOT.'/admin/cms/layouts/table/index.php';
			}
			elseif($_SESSION['admin_type'] == 'add')
			{
				include_once INSTALLATION_ROOT.'/admin/cms/layouts/add/index.php';
			}
			elseif($_SESSION['admin_type'] == 'edit')
			{
				include_once INSTALLATION_ROOT.'/admin/cms/layouts/edit/index.php';
			}
			elseif($_SESSION['admin_type'] == 'static')
			{
				include_once INSTALLATION_ROOT.'/admin/cms/layouts/static/index.php';
			}
			elseif($_SESSION['admin_type'] == 'blank')
			{
				include_once INSTALLATION_ROOT.'/admin/cms/layouts/blank/index.php';
			}
		}
	}
	
	//This will display all MySQL queries for the admin of the site. To do so, go to /config.php and set $_SESSION['display_sql_queries'] = 'Yes'. To stop displaying them set $_SESSION['display_sql_queries'] = 'No'. You should not use this in a live environment if you would be concerned about visitors seeing them. Once set to Yes, refresh a page on the admin of the site and scroll to the bottom to see all of the queries that were used to load that page.
	if($_SESSION['display_sql_queries'] == 'Yes' && !empty($_SESSION['all_queries']))
	{
		echo '<div class="right">';
		rsort($_SESSION['all_queries']);
		$sql_time = 0.00000000;
		foreach($_SESSION['all_queries'] as $all_queries)
		{
			$sql_time = $sql_time + $all_queries['MS_Time'];
		}
		
		echo 'Time to load all MySQL queries: '.$sql_time.' ms';
		echo '<pre>'; print_r($_SESSION['all_queries']); echo '</pre>';
		echo '</div>';
	}
}

//clear database connection.
$pdo = null;
$pdo_schema = null;
$sql = null;
$stmt = null;
die(); 