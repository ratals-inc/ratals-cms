<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(isset($_POST['noticeId']) && !empty($_POST['noticeId']) && isset($_POST['versionNumber']) && !empty($_POST['versionNumber']) && isset($_POST['upgradeTo']) && isset($_POST['current_update_log']) && !empty($_POST['current_update_log']) && isset($_POST['type']) && $_POST['type'] == 'updateSoftwareNow') 
{
	
	if(!isset($_POST['token_file'], $_POST['update_token']) || !file_exists($_POST['token_file']) || trim(file_get_contents($_POST['token_file'])) !== $_POST['update_token']) 
	{
		//Cleanup just in case.
		if(isset($_POST['token_file']) && file_exists($_POST['token_file']))
		{
			unlink($_POST['token_file']);
		}
		
		http_response_code(403);
		die('Forbidden');
	}
	
	//Mark as started immediately so cron can stop trying to start.
	$started_file = $_SERVER['DOCUMENT_ROOT'].'/update-started.txt';
	file_put_contents($started_file, '');
	
	//Delete the token immediately after verification so it can't be reused.
	if(file_exists($_POST['token_file']))
	{
		unlink($_POST['token_file']);
	}
	
	//We have to unset count ids when updating incase they have already been set from installing. These session variables are set in template-files.php.
	unset($_SESSION['last_menu_id']);
	unset($_SESSION['last_slider_id']);
	unset($_SESSION['last_custom_field_id']);
	unset($_SESSION['last_pages_id']);
	unset($_SESSION['last_url_id']);
	$_SESSION['install_ids'] = array();
	
	//Ensures no output buffer interferes with PHP continuing execution in some environments (like FPM or LiteSpeed).
	while(ob_get_level())
	{
		ob_end_clean();
	}
	
	ignore_user_abort(true); //continue even if user disconnects
	set_time_limit(0); //no time limit so update completes
	session_write_close(); //release session lock so user browser connection can freely run
	
	$progress_log_file = $_POST['current_update_log'];
	$allowed_dir = $_SERVER['DOCUMENT_ROOT'];
	if(strpos(realpath($progress_log_file), $allowed_dir) !== 0)
	{
		throw new \Exception('Invalid or missing progress log file.');
	}
	file_put_contents($progress_log_file, '');
	
	//Make sure log file directory exist.
	if(!is_dir($_SERVER['DOCUMENT_ROOT'].'/storage/logs'))
	{
		mkdir($_SERVER['DOCUMENT_ROOT'].'/storage/logs', 0755, true);
	}
	
	//Function to create install log changes and log any errors on update.
	function writeToInstallLog($log_content)
	{
		global $progress_log_file;
		
		//Create file if it doesn't exist
		$log_file = $_SERVER['DOCUMENT_ROOT'].'/storage/logs/software-update.txt';
		if(!file_exists($log_file))
		{
			file_put_contents($log_file, '');
		}
		
		date_default_timezone_set($_SESSION['timezone'] ?? 'UTC');
		$timestamp = date('M. d, Y - h:i:s A');
		$formatted_message = '['.$timestamp.'] '.$log_content.PHP_EOL.PHP_EOL; //PHP_EOL adds line breaks
		
		file_put_contents($log_file, $formatted_message, FILE_APPEND | LOCK_EX);
		
		file_put_contents($progress_log_file, $formatted_message, FILE_APPEND | LOCK_EX);
		clearstatcache(true, $progress_log_file); //Flushed immediately to ensure latest content
	}
	
	//Global error/exception handlers
	//Catch all uncaught exceptions
	set_exception_handler(function($e)
	{
		writeToInstallLog('UNCAUGHT EXCEPTION: '.$e->getMessage().' | File: '.$e->getFile().' | Line: '.$e->getLine());
	});
	
	//Catch all PHP errors, warnings, notices
	set_error_handler(function($errno, $errstr, $errfile, $errline)
	{
		writeToInstallLog('PHP ERROR ['.$errno.']: '.$errstr.' | File: '.$errfile.' | Line: '.$errline);
		return false;
	});
	
	//Catch fatal errors on shutdown
	register_shutdown_function(function()
	{
		$error = error_get_last();
		if($error)
		{
			writeToInstallLog('FATAL ERROR ['.$error['type'].']: '.$error['message'].' | File: '.$error['file'].' | Line: '.$error['line']);
		}
	});
	
	function removeInstallDirectory($directory_path)
	{
		if(is_dir($directory_path))
		{
			$directory_contents = scandir($directory_path);
			foreach($directory_contents as $item)
			{
				if($item !== "." && $item !== "..")
				{
					$item_path = $directory_path.'/'.$item;
					if(is_dir($item_path))
					{
						removeInstallDirectory($item_path);
					}
					else
					{
						unlink($item_path);
					}
				}
			}
			rmdir($directory_path);
		}
	}
	
	function recursiveCopy($staging_directory, $live_directory)
	{
		if(!is_dir($live_directory))
		{
			if(mkdir($live_directory, 0755, true))
			{
				chmod($live_directory, 0755);
			}
		}
	
		//Get all files and directories in staging, excluding '.' and '..'
		$items = array_diff(scandir($staging_directory), ['.', '..']);
	
		foreach($items as $file)
		{
			$staging_file_path = $staging_directory.'/'.$file;
			$live_file_path = $live_directory.'/'.$file;
	
			if(is_dir($staging_file_path))
			{
				recursiveCopy($staging_file_path, $live_file_path);
			}
			else
			{
				if(!copy($staging_file_path, $live_file_path))
				{
					throw new \Exception('Failed to copy staging update file: '.$staging_file_path);
				}
				chmod($live_file_path, 0644);
			}
		}
	}
	
	try
	{
		//Location of extracted file that start-update.php created.
		$temp_extract_dir = $_SERVER['DOCUMENT_ROOT'].'/admin/temp_extract';
		
		if(!is_dir($temp_extract_dir))
		{
			throw new \Exception("Temp extract folder not found: ".$temp_extract_dir);
		}
		
		//Ensure directory is writable
		if(!is_writable($temp_extract_dir))
		{
			throw new \Exception("Warning: Could not write to temp extract directory (".$temp_extract_dir."). Check server permissions.");
		}
		
		if(!is_writable($_SERVER['DOCUMENT_ROOT']))
		{
			throw new \Exception("Live directory is not writable. Update cannot continue.");
		}
		
		writeToInstallLog('---------------------------------- START UPDATE ----------------------------------');
		
		writeToInstallLog('Files extracted to staging folder.');
		
		$license_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'license', 'WHERE `site_id` = ? LIMIT 1', [0]);
		$license_type = 'cms';
		$license_key = $license_data['license_key'] ?? '';
		$install_id = $license_data['install_id'] ?? '';
		
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
			'license_key' => $license_key,
			'install_id' => $install_id,
			'domains' => $all_domains
		];
		
		$ch = curl_init($license_check_endpoint);
		
		curl_setopt_array($ch, [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_POSTFIELDS => http_build_query($post_data),
			CURLOPT_TIMEOUT => 10,
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_SSL_VERIFYPEER => true,
			CURLOPT_SSL_VERIFYHOST => 2,
		]);
		
		$response = curl_exec($ch);
		
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
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
		}
		
		$modules_array = array();
		if($license_type === 'cms')
		{
			$modules_array[] = $temp_extract_dir.'/admin/commerce';
			$modules_array[] = $temp_extract_dir.'/admin/erp';
			$modules_array[] = $temp_extract_dir.'/admin/ai';
		}
		elseif($license_type === 'commerce')
		{
			$modules_array[] = $temp_extract_dir.'/admin/erp';
			$modules_array[] = $temp_extract_dir.'/admin/ai';
		}
		elseif($license_type === 'erp')
		{
			$modules_array[] = $temp_extract_dir.'/admin/ai';
		}
		
		foreach($modules_array as $module)
		{
			if(is_dir($module))
			{
				removeInstallDirectory($module);
			}
		}
		
		//Update /.htaccess - for Apache and Lightspeed servers
		$temp_htaccess_path = $temp_extract_dir.'/.htaccess';
		if(file_exists($temp_htaccess_path))
		{
			writeToInstallLog('Updating /.htaccess rules in staging update folder...');
			$htaccess_contents = file_get_contents($temp_htaccess_path);
			
			//Replace YOUR_ADMIN_URL_PATH with virtual admin path
			$htaccess_contents = str_replace('YOUR_ADMIN_URL_PATH', $_SESSION['admin_directory'], $htaccess_contents);
			
			$htaccess_contents = preg_replace('~php_value\s+auto_prepend_file\s+.*~i', 'php_value auto_prepend_file "'.rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/core/session-check-frontend.php"', $htaccess_contents);
			
			if(file_put_contents($temp_htaccess_path, $htaccess_contents, LOCK_EX) === false)
			{
				throw new \Exception('Failed to update .htaccess in staging update folder.');
			}
		}
		
		//Update /admin/.htaccess - for Apache and Lightspeed servers
		$temp_admin_htaccess_path = $temp_extract_dir.'/admin/.htaccess';
		if(file_exists($temp_admin_htaccess_path))
		{
			writeToInstallLog('Updating /admin/.htaccess rules in staging update folder...');
			$admin_htaccess_contents = file_get_contents($temp_admin_htaccess_path);
			
			//Replace YOUR_ADMIN_URL_PATH with virtual admin path
			$admin_htaccess_contents = str_replace('YOUR_ADMIN_URL_PATH', $_SESSION['admin_directory'], $admin_htaccess_contents);
			
			$admin_htaccess_contents = preg_replace('~php_value\s+auto_prepend_file\s+.*~i', 'php_value auto_prepend_file "'.rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/core/session-check-admin.php"', $admin_htaccess_contents);
			
			if(file_put_contents($temp_admin_htaccess_path, $admin_htaccess_contents, LOCK_EX) === false)
			{
				throw new \Exception('Failed to update /admin/.htaccess in staging update folder.');
			}
		}
		
		//Update /.user.ini (frontend) - for Nginx servers / PHP-FPM environments
		$temp_user_ini_path = $temp_extract_dir.'/.user.ini';
		if(file_exists($temp_user_ini_path))
		{
			writeToInstallLog('Updating /.user.ini auto_prepend_file path in staging update folder...');
			
			$doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
			$frontend_path = $doc_root.'/core/session-check-frontend.php';
			
			$user_ini_contents = file_get_contents($temp_user_ini_path);
			
			//Replace ONLY the auto_prepend_file line
			$user_ini_contents = preg_replace('/;auto_prepend_file\s*=\s*".*?"/', 'auto_prepend_file = "'.$frontend_path.'"', $user_ini_contents);
			
			if(file_put_contents($temp_user_ini_path, $user_ini_contents, LOCK_EX) === false)
			{
				throw new \Exception('Failed to update /.user.ini in staging update folder.');
			}
		}
		
		//Update /admin/.user.ini (admin) - for Nginx servers / PHP-FPM environments
		$temp_admin_user_ini_path = $temp_extract_dir.'/admin/.user.ini';
		if(file_exists($temp_admin_user_ini_path))
		{
			writeToInstallLog('Updating /admin/.user.ini auto_prepend_file path in staging update folder...');
			
			$doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
			$admin_path = $doc_root.'/core/session-check-admin.php';
			
			$admin_user_ini_contents = file_get_contents($temp_admin_user_ini_path);
			
			//Replace ONLY the auto_prepend_file line
			$admin_user_ini_contents = preg_replace('/;auto_prepend_file\s*=\s*".*?"/', 'auto_prepend_file = "'.$admin_path.'"', $admin_user_ini_contents);
			
			if(file_put_contents($temp_admin_user_ini_path, $admin_user_ini_contents, LOCK_EX) === false)
			{
				throw new \Exception('Failed to update /admin/.user.ini in staging update folder.');
			}
		}
		
		//Update config.php
		$temp_config_path = $temp_extract_dir.'/core/config.php';
		$current_config_path = $_SERVER['DOCUMENT_ROOT'].'/core/config.php';
		if(file_exists($temp_config_path))
		{
			writeToInstallLog('Updating config.php in staging update folder...');
			
			$current_hash_secret = '';
			
			if(file_exists($current_config_path))
			{
				$current_config_contents = file_get_contents($current_config_path);
				
				if(preg_match('/\$hash_secret\s*=\s*[\'"](.*?)[\'"];/s', $current_config_contents, $matches))
				{
					$current_hash_secret = $matches[1];
				}
			}
			
			if(empty($current_hash_secret) || $current_hash_secret === '[SET_HASH_SECRET]')
			{
				try
				{
					$current_hash_secret = bin2hex(random_bytes(16));
				}
				catch(\Exception $e)
				{
					$current_hash_secret = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 32);
					writeToInstallLog('Warning: secure random generation failed, using fallback hash secret.');
				}
			}
			
			$config_contents = file_get_contents($temp_config_path);
			$config_contents = str_replace('[SET_HASH_SECRET]', $current_hash_secret, $config_contents);
			
			if(file_put_contents($temp_config_path, $config_contents, LOCK_EX) === false)
			{
				throw new \Exception('Failed to write hash secret to staging config.php.');
			}
		}
		
		//Set $first_last_name
		$first_last_name = 'System Update';
		
		//UPDATE DATABASE
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/database.php'))
		{
			writeToInstallLog('Installing/updating database tables, columns, and indexes...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/database.php');
			writeToInstallLog('Database update completed successfully.');
		}
		else
		{
			throw new \Exception('Database update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/database.php');
		}
		
		//Get all installed tables after running the table update to determine which are available for updates.
		$existing_database_tables = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, 'TABLE_NAME', 'tables', 'WHERE `table_schema` = ?', [$_SESSION['site_db_name']], 'TABLE_NAME');
		
		//UPDATE ADMIN FIELDS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-fields.php'))
		{
			writeToInstallLog('Updating admin fields...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-fields.php');
			writeToInstallLog('Admin fields update completed successfully.');
		}
		else
		{
			throw new \Exception('Admin fields update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/admin-fields.php');
		}
		
		//UPDATE DATABASE TABLE IDS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/database-table-ids.php'))
		{
			writeToInstallLog('Updating database table IDs...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/database-table-ids.php');
			writeToInstallLog('Database table IDs update completed successfully.');
		}
		else
		{
			throw new \Exception('Database table IDs update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/database-table-ids.php');
		}

		//UPDATE ADMIN PAGES
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-pages.php'))
		{
			writeToInstallLog('Updating admin pages...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-pages.php');
			writeToInstallLog('Admin pages update completed successfully.');
		}
		else
		{
			throw new \Exception('Admin pages update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/admin-pages.php');
		}
		
		//UPDATE ADMIN MENUS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-menus.php'))
		{
			writeToInstallLog('Updating admin menus...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-menus.php');
			writeToInstallLog('Admin menus update completed successfully.');
		}
		else
		{
			throw new \Exception('Admin menus update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/admin-menus.php');
		}
		
		//UPDATE ADMIN MENU ITEMS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-menu-items.php'))
		{
			writeToInstallLog('Updating admin menu items...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-menu-items.php');
			writeToInstallLog('Admin menu items update completed successfully.');
		}
		else
		{
			throw new \Exception('Admin menu items update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/admin-menu-items.php');
		}
		
		//UPDATE ADMIN FIELD LISTS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-lists.php'))
		{
			writeToInstallLog('Updating admin field lists...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-lists.php');
			writeToInstallLog('Admin field lists update completed successfully.');
		}
		else
		{
			throw new \Exception('Admin field lists update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-lists.php');
		}
		
		//UPDATE ADMIN FIELD VALUES
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-values.php'))
		{
			writeToInstallLog('Updating admin field values...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-values.php');
			writeToInstallLog('Admin field values update completed successfully.');
		}
		else
		{
			throw new \Exception('Admin field values update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-values.php');
		}
		
		//UPDATE ADMIN FIELD SECTIONS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-sections.php'))
		{
			writeToInstallLog('Updating admin field sections...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-sections.php');
			writeToInstallLog('Admin field sections update completed successfully.');
		}
		else
		{
			throw new \Exception('Admin field sections update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/admin-field-sections.php');
		}
		
		//UPDATE WEBSITE TEMPLATE
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/template-files.php'))
		{
			writeToInstallLog('Updating website template files...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/template-files.php');
			writeToInstallLog('Website template files update completed successfully.');
		}
		else
		{
			throw new \Exception('Website template files update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/template-files.php');
		}
		
		//UPDATE WEBSITE MENUS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/menus.php'))
		{
			writeToInstallLog('Updating website menus...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/menus.php');
			writeToInstallLog('Website menus update completed successfully.');
		}
		else
		{
			throw new \Exception('Website menus update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/menus.php');
		}
		
		//UPDATE WEBSITE MENU ITEMS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/menu-items.php'))
		{
			writeToInstallLog('Updating website menu items...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/menu-items.php');
			writeToInstallLog('Website menu items update completed successfully.');
		}
		else
		{
			throw new \Exception('Website menu items update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/menu-items.php');
		}
		
		//UPDATE PAGE URLS
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/urls.php'))
		{
			writeToInstallLog('Updating website pages URLs...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/urls.php');
			writeToInstallLog('Website pages URLs update completed successfully.');
		}
		else
		{
			throw new \Exception('Website pages URLs update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/urls.php');
		}
		
		//UPDATE PAGES - PAGES MUST RUN AFTER "PAGE URLS OR URLS.PHP" ABOVE TO KNOW WHAT PAGES TO INSTALL
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/pages.php'))
		{
			writeToInstallLog('Updating website pages...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/pages.php');
			writeToInstallLog('Website pages update completed successfully.');
		}
		else
		{
			throw new \Exception('Website pages update file not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/pages.php');
		}
		
		//SET SITE_SETTINGS FOR COMMERCE PACKAGE ON UPGRADE.
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/site-settings.php'))
		{
			writeToInstallLog('Updating site settings for each site...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/site-settings.php');
			writeToInstallLog('Site settings update completed successfully.');
		}
		else
		{
			throw new \Exception('Site settings update not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/site-settings.php');
		}
		
		//SET SITE_SECURITY FOR COMMERCE PACKAGE ON UPGRADE.
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/site-security.php'))
		{
			writeToInstallLog('Updating site security for each site...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/site-security.php');
			writeToInstallLog('Site security update completed successfully.');
		}
		else
		{
			throw new \Exception('Site security update not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/site-security.php');
		}
		
		//SET BLOCKING SPAM FOR COMMERCE PACKAGE ON UPGRADE.
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/blocking-spam.php'))
		{
			writeToInstallLog('Updating blocking spam for each site...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/blocking-spam.php');
			writeToInstallLog('Blocking spam update completed successfully.');
		}
		else
		{
			throw new \Exception('Blocking spam update not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/blocking-spam.php');
		}
		
		//SET ACCOUNTING SETTINGS FOR ERP PACKAGE ON UPGRADE.
		if(file_exists($temp_extract_dir.'/admin/cms/includes/notices/updates/accounting-settings.php'))
		{
			writeToInstallLog('Updating accounting settings...');
			//sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/includes/notices/updates/accounting-settings.php');
			writeToInstallLog('Accounting settings update completed successfully.');
		}
		else
		{
			throw new \Exception('Accounting settings update not found: '.$temp_extract_dir.'/admin/cms/includes/notices/updates/accounting-settings.php');
		}
		
		//SET COLUMNS TO DISPLAY ON TABLE VIEW FOR ALL USERS
		if(file_exists($temp_extract_dir.'/admin/cms/installer/data/assigned-fields.php'))
		{
			writeToInstallLog('Updating assigned fields for each user...');
			sleep(2); //Allow user to read progress bar step
			include($temp_extract_dir.'/admin/cms/installer/data/assigned-fields.php');
			writeToInstallLog('Assigned fields update completed successfully.');
		}
		else
		{
			throw new \Exception('Assigned fields update not found: '.$temp_extract_dir.'/admin/cms/installer/data/assigned-fields.php');
		}
		
		//Get current software version that is set in /config.php
		$old_version = '';
		if(isset($current_software_version) && !empty($current_software_version))
		{
			$old_version = $current_software_version;
		}
		
		//Copy temp update files to live
		try
		{
			writeToInstallLog('Copying update files from staging folder to live directory...');
			sleep(2); //Allow user to read progress bar step
			recursiveCopy($temp_extract_dir, $_SERVER['DOCUMENT_ROOT']);
			writeToInstallLog('All staging update files copied to live directory successfully.');
		}
		catch(\Throwable $e)
		{
			writeToInstallLog('Error copying staging update files to live: ' . $e->getMessage());
			throw $e;
		}
		
		//Clear OPcache so new PHP files take effect
		if(function_exists('opcache_reset'))
		{
			opcache_reset();
			writeToInstallLog('OPcache cleared after update so new PHP files take effect.');
		}
		
		writeToInstallLog('Software update process completed successfully.');
		
		writeToInstallLog('---------------------------------- END UPDATE ----------------------------------');
		
		//Clean up temp once after loop copy to live
		if(is_dir($temp_extract_dir))
		{
			removeInstallDirectory($temp_extract_dir);
		}
		
		if(file_exists($started_file))
		{
			unlink($started_file);
		}
		
		//Delete update progress file.
		unset($_SESSION['current_update_log']);
		if(file_exists($progress_log_file))
		{
			unlink($progress_log_file);
		}
		
		//CREATE NOTICE THAT SOFTWARE HAS BEEN UPDATED.
		//Get php version.
		$php_version = phpversion();
		
		//Get mysql version
		$mysql_version_result = $results->getRawQuery(__LINE__, __FILE__, 'select version()', []);
		
		$mysql_version = '';
		if(isset($mysql_version_result[0]['version()']))
		{
			$mysql_version = $mysql_version_result[0]['version()'];
		}
		
		$new_version = 'unknown';
		if(isset($_POST['versionNumber']) && !empty($_POST['versionNumber']))
		{
			$new_version = preg_replace('/[^0-9a-zA-Z.\-_]/', '', trim($_POST['versionNumber']));
		}
		
		if($_POST['upgradeTo'] == 'Commerce')
		{
			$message_to_insert = 
			array(
				'id' => NULL,
				'subject' => 'Your Commerce Upgrade Was Successful!',
				'message' => '<div class="">To configure your website frontend as an online store, there are a few final steps to complete. Follow <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-commerce/" target="_blank">this tutorial</a> to get your store fully set up and running.</div>',
				'link' => '',
				'update_software' => 'No', //This tells the notice to show the update button or not. Dont't show button as software has been updated.
				'software_version' => $new_version,
				'required_php' => '',
				'required_mysql' => ''
			);
		}
		elseif($_POST['upgradeTo'] == 'ERP')
		{
			$message_to_insert = 
			array(
				'id' => NULL,
				'subject' => 'Your ERP Upgrade Was Successful!',
				'message' => '<div class="">If your website frontend is not already configured as an online store and you would like it to be, please follow <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-commerce/" target="_blank">this commerce tutorial</a> to set up your store. Once your store is ready, follow <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-erp/" target="_blank">this ERP tutorial</a> to configure your ERP features and complete your setup.</div>',
				'link' => '',
				'update_software' => 'No', //This tells the notice to show the update button or not. Dont't show button as software has been updated.
				'software_version' => $new_version,
				'required_php' => '',
				'required_mysql' => ''
			);
		}
		elseif($_POST['upgradeTo'] == 'AI')
		{
			$message_to_insert = 
			array(
				'id' => NULL,
				'subject' => 'Your AI Upgrade Was Successful!',
				'message' => '<div class="">If your website frontend is not already configured as an online store and you would like it to be, please follow <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-commerce/" target="_blank">this commerce tutorial</a> to set up your store. Once your store is ready, follow <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-erp/" target="_blank">this ERP tutorial</a> to configure your ERP features and complete your setup.</div>',
				'link' => '',
				'update_software' => 'No', //This tells the notice to show the update button or not. Dont't show button as software has been updated.
				'software_version' => $new_version,
				'required_php' => '',
				'required_mysql' => ''
			);
		}
		else
		{
			$message_to_insert = 
			array(
				'id' => NULL,
				'subject' => 'Your Ratals Software Has Been Updated!',
				'message' => '<div class="">Your software has been successfully updated from version '.$old_version.' to version '.$new_version.'.</div>',
				'link' => '',
				'update_software' => 'No', //This tells the notice to show the update button or not. Dont't show button as software has been updated.
				'software_version' => $new_version,
				'required_php' => '',
				'required_mysql' => ''
			);
		}
		
		//Insert notice that software has been updated.
		$results->getInsertRecord(__LINE__, __FILE__, 'notices', '`id`, `site_id`, `status`, `notice_subject`, `notice_message`, `notice_url`, `notice_update_software`, `notice_upgrade_from`, `notice_upgrade_to`, `notice_software_version`, `required_php_version`, `required_mysql_version`, `system_code`, `custom_fields`, `created_date`', '?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()', [NULL, 0, 1, $message_to_insert['subject'], $message_to_insert['message'], $message_to_insert['link'], $message_to_insert['update_software'], '', '', $message_to_insert['software_version'], $message_to_insert['required_php'], $message_to_insert['required_mysql'], 'updated_from_'.$old_version.'_to_'.$message_to_insert['software_version'], '{}']);
		
		echo "1";
		exit;
	}
	catch(\Throwable $e)
	{
		//Remove extrated directory if error is thrown
		if(isset($temp_extract_dir) && is_dir($temp_extract_dir))
		{
			removeInstallDirectory($temp_extract_dir);
		}
		
		if(file_exists($started_file))
		{
			unlink($started_file);
		}
		
		//Delete update progress file.
		unset($_SESSION['current_update_log']);
		if(file_exists($progress_log_file))
		{
			unlink($progress_log_file);
		}
		
		//Log and return the error to AJAX
		$errorMessage = $e->getMessage();
		$errorFile = $e->getFile();
		$errorLine = $e->getLine();
		$errorTrace = $e->getTraceAsString();
		
		$fullError = "Update failed:\n"."Message: ".$errorMessage."\n"."File: ".$errorFile."\n"."Line: ".$errorLine."\n"."Trace:\n".$errorTrace;
		writeToInstallLog($fullError);
		
		echo json_encode(['status'  => 'error', 'message' => $errorMessage, 'file'    => $errorFile, 'line'    => $errorLine]);
		
		exit;
	}
}