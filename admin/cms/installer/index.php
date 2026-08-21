<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Make sure PHP GD library is installed
if(!(extension_loaded('gd') && function_exists('imagecreate')))
{
    echo '<p><strong>Missing Requirement: PHP GD Library</strong></p><p>Ratals requires the PHP GD image library to be installed and enabled on your server. This library is used during installation to process and convert images (JPG, PNG, GIF) and generate optimized WebP and AVIF versions. It is also used to create default "coming soon" placeholder images during setup.</p><p>Once the PHP GD library is installed and enabled, the Ratals installation page will load. If you are unsure how to install it, please contact your hosting provider for assistance.</p>';
    exit;
}

//Make sure server software allows .htaccess rules to run.
require_once(INSTALLATION_ROOT.'/core/server-software.php');

//Detect if user is on https.
$https_status = false;
if((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (!empty($_SERVER['HTTP_X_FORWARDED_SSL']) && $_SERVER['HTTP_X_FORWARDED_SSL'] === 'on') || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443))
{
    $https_status = true;
}

//If a session has not been started, start it.
if(session_status() === PHP_SESSION_NONE)
{
	session_start();
}

$tld = $_SERVER['SERVER_NAME'] ?? '';
$tld = preg_replace('#^www\.#i', '', $tld);

$site_name = '';
//Set site_name if tld has 1 period in it for something like .com, .net, .org, etc.
$site_name_array = explode('.', $tld);
if(count($site_name_array) == 2)
{
	$site_name = ucfirst($site_name_array[0]);
}

//Get all timezones from php library and put in array.
$php_timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
$timezone_rows = array();
if(!empty($php_timezones))
{
	foreach($php_timezones as $timezone)
	{
		$current_time = '';
		date_default_timezone_set($timezone);
		$current_time_24 = date('H:i');
		$current_time = date('g:i A');
		$timezones_row = array('time_24' => $current_time_24, 'time' => $current_time, 'timezone' => $timezone);
		$timezone_rows[] = $timezones_row;
	}
	sort($timezone_rows);
}

$all_time_zone_options = '';
if(!empty($timezone_rows))
{
	foreach($timezone_rows as $timezone_row)
	{
		$selected_item = '';
		
		if(isset($timezone_row["timezone"]))
		{
			$timezone_id = $timezone_row["timezone"];
			$timezone_label = $timezone_row["time"];
			
			if(isset($_POST['timezone']) && !empty($_POST['timezone']))
			{
				if($_POST['timezone'] == $timezone_row["timezone"])
				{
					$selected_item = " selected";
				}
			}
			else
			{
				if('America/Denver' == $timezone_row["timezone"])
				{
					$selected_item = " selected";
				}
			}
		}
		
		$all_time_zone_options .= '<option value="'.htmlspecialchars($timezone_id ?? '').'"'.$selected_item.'>'.htmlspecialchars($timezone_label.' - '.$timezone_id ?? '').'</option>';
	}
}

$new_site_languages = array(
array('language' => 'Amharic (am)', 'code' => 'am'),
array('language' => 'Arabic (ar)', 'code' => 'ar'),
array('language' => 'Basque (eu)', 'code' => 'eu'),
array('language' => 'Bengali (bn)', 'code' => 'bn'),
array('language' => 'Bulgarian (bg)', 'code' => 'bg'),
array('language' => 'Catalan (ca)', 'code' => 'ca'),
array('language' => 'Cherokee (chr)', 'code' => 'chr'),
array('language' => 'Chinese (PRC) (zh-CN)', 'code' => 'zh-CN'),
array('language' => 'Chinese (Taiwan) (zh-TW)', 'code' => 'zh-TW'),
array('language' => 'Croatian (hr)', 'code' => 'hr'),
array('language' => 'Czech (cs)', 'code' => 'cs'),
array('language' => 'Danish (da)', 'code' => 'da'),
array('language' => 'Dutch (nl)', 'code' => 'nl'),
array('language' => 'English (UK) (en-GB)', 'code' => 'en-GB'),
array('language' => 'English (US) (en)', 'code' => 'en'),
array('language' => 'Estonian (et)', 'code' => 'et'),
array('language' => 'Filipino (fil)', 'code' => 'fil'),
array('language' => 'Finnish (fi)', 'code' => 'fi'),
array('language' => 'French (fr)', 'code' => 'fr'),
array('language' => 'German (de)', 'code' => 'de'),
array('language' => 'Greek (el)', 'code' => 'el'),
array('language' => 'Gujarati (gu)', 'code' => 'gu'),
array('language' => 'Hebrew (iw)', 'code' => 'iw'),
array('language' => 'Hindi (hi)', 'code' => 'hi'),
array('language' => 'Hungarian (hu)', 'code' => 'hu'),
array('language' => 'Icelandic (is)', 'code' => 'is'),
array('language' => 'Indonesian (id)', 'code' => 'id'),
array('language' => 'Italian (it)', 'code' => 'it'),
array('language' => 'Japanese (ja)', 'code' => 'ja'),
array('language' => 'Kannada (kn)', 'code' => 'kn'),
array('language' => 'Korean (ko)', 'code' => 'ko'),
array('language' => 'Latvian (lv)', 'code' => 'lv'),
array('language' => 'Lithuanian (lt)', 'code' => 'lt'),
array('language' => 'Malay (ms)', 'code' => 'ms'),
array('language' => 'Malayalam (ml)', 'code' => 'ml'),
array('language' => 'Marathi (mr)', 'code' => 'mr'),
array('language' => 'Norwegian (no)', 'code' => 'no'),
array('language' => 'Polish (pl)', 'code' => 'pl'),
array('language' => 'Portuguese (Brazil) (pt-BR)', 'code' => 'pt-BR'),
array('language' => 'Portuguese (Portugal) (pt-PT)', 'code' => 'pt-PT'),
array('language' => 'Romanian (ro)', 'code' => 'ro'),
array('language' => 'Serbian (sr)', 'code' => 'sr'),
array('language' => 'Slovak (sk)', 'code' => 'sk'),
array('language' => 'Slovenian (sl)', 'code' => 'sl'),
array('language' => 'Spanish (es)', 'code' => 'es'),
array('language' => 'Swahili (sw)', 'code' => 'sw'),
array('language' => 'Swedish (sv)', 'code' => 'sv'),
array('language' => 'Tamil (ta)', 'code' => 'ta'),
array('language' => 'Telugu (te)', 'code' => 'te'),
array('language' => 'Thai (th)', 'code' => 'th'),
array('language' => 'Turkish (tr)', 'code' => 'tr'),
array('language' => 'Ukrainian (uk)', 'code' => 'uk'),
array('language' => 'Urdu (ur)', 'code' => 'ur'),
array('language' => 'Vietnamese (vi)', 'code' => 'vi'),
array('language' => 'Welsh (cy)', 'code' => 'cy')
);

$all_language_options = '';
if(!empty($new_site_languages))
{
	foreach($new_site_languages as $site_language)
	{
		$selected_item = '';
		
		if(isset($site_language["language"]))
		{
			if(isset($_POST['site_language']) && !empty($_POST['site_language']))
			{
				if($_POST['site_language'] == $site_language["code"])
				{
					$selected_item = " selected";
				}
			}
			else
			{
				if('en' == $site_language["code"])
				{
					$selected_item = " selected";
				}
			}
		}
		
		$all_language_options .= '<option value="'.htmlspecialchars($site_language['code'] ?? '').'"'.$selected_item.'>'.htmlspecialchars($site_language["language"] ?? '').'</option>';
	}
}

$errors = array();
if(isset($_POST['submit']))
{
	$database_hostname = trim($_POST['database_hostname'] ?? '');
	if(empty($database_hostname))
	{
		$errors['database_hostname'] = '<span class="error">Database Hostname</span>';
	}
	elseif(strpos($database_hostname, "'") !== false)
	{
		$errors['database_hostname_quote'] = '<span class="error">Cannot contain single quotes.</span>';
	}
	
	$database_name = trim($_POST['database_name'] ?? '');
	if(empty($database_name))
	{
		$errors['database_name'] = '<span class="error">Database Name</span>';
	}
	elseif(strpos($database_name, "'") !== false)
	{
		$errors['database_name_quote'] = '<span class="error">Cannot contain single quotes.</span>';
	}
	
	$database_username = trim($_POST['database_username'] ?? '');
	if(empty($database_username))
	{
		$errors['database_username'] = '<span class="error">Database Username</span>';
	}
	elseif(strpos($database_username, "'") !== false)
	{
		$errors['database_username_quote'] = '<span class="error">Cannot contain single quotes.</span>';
	}
	
	$database_password = trim($_POST['database_password'] ?? '');
	if(empty($database_password))
	{
		$errors['database_password'] = '<span class="error">Database Password</span>';
	}
	elseif(strpos($database_password, "'") !== false)
	{
		$errors['database_password_quote'] = '<span class="error">Cannot contain single quotes.</span>';
	}
	
	if(!empty($database_hostname) && !empty($database_name) && !empty($database_username) && !empty($database_password) && !isset($errors['database_hostname']) && !isset($errors['database_name']) && !isset($errors['database_username']) && !isset($errors['database_password']))
	{
		try
		{
			$dsn = 'mysql:host='.$database_hostname.';dbname='.$database_name;
			$pdo = new PDO($dsn, $database_username, $database_password);
			
			//Make sure PDO throws exceptions for database errors.
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			
			//Make sure the database is empty before installing.
			try
			{
				$database_tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
				
				if(!empty($database_tables))
				{
					$errors['database_empty'] = '<span class="center error error-top-margin">The selected database is not empty. Please use an empty database before installing Ratals.</span>';
				}
			}
			catch(Exception $e)
			{
				$errors['database_empty_check'] = '<span class="center error error-top-margin">Ratals connected to the database, but could not verify that it is empty. Please make sure the database user has permission to view and manage tables.</span>';
			}
			
			//Make sure required database collation is available.
			try
			{
				$collation_check = $pdo->query("SHOW COLLATION LIKE 'utf8mb4_unicode_ci'")->fetch(PDO::FETCH_ASSOC);
				
				if(empty($collation_check))
				{
					$errors['database_collation'] = '<span class="center error error-top-margin">The database server does not support the required utf8mb4_unicode_ci collation. Please contact your hosting provider before installing Ratals.</span>';
				}
			}
			catch(Exception $e)
			{
				$errors['database_collation_check'] = '<span class="center error error-top-margin">Ratals connected to the database, but could not verify the required utf8mb4_unicode_ci collation. Please contact your hosting provider.</span>';
			}
		}
		catch(Exception $e)
		{
			$errors['database_connection'] = '<span class="center error error-top-margin">Couldn\'t connect to the database with the provided credentials.</span>';
		}
	}
	
	$site_name = trim($_POST['site_name'] ?? '');
	if(empty($site_name))
	{
		$errors['site_name'] = '<span class="error">Site Name</span>';
	}
	
	$https_in_url = trim($_POST['https_in_url'] ?? '');
	if(empty($https_in_url))
	{
		$errors['https_in_url'] = '<span class="error">Select if you want "https" or "http" in your doamin</span>';
	}
	
	$www_in_url = trim($_POST['www_in_url'] ?? '');
	if(empty($www_in_url))
	{
		$errors['www_in_url'] = '<span class="error">Select if you want "www." or "no www." in your domain</span>';
	}
	
	$tld = trim($_POST['tld'] ?? '');
	if(empty($tld))
	{
		$errors['tld'] = '<span class="error">Enter your domain name (e.g. ratals.com)</span>';
	}
	else
	{
		$tld = rtrim($tld, '/');
		if(preg_match('#^https?://#i', $tld))
		{
			$errors['tld'] = '<span class="error">Remove http/https and use dropdown</span>';
		}
		elseif(stripos($tld, 'www.') === 0)
		{
			$errors['tld'] = '<span class="error">Remove www. and use dropdown</span>';
		}
		elseif(!preg_match('/^[a-zA-Z0-9.-]+$/', $tld))
		{
			$errors['tld'] = '<span class="error">Invalid domain characters</span>';
		}
	}
	
	$site_language = trim($_POST['site_language'] ?? '');
	if(empty($site_language))
	{
		$errors['site_language'] = '<span class="error">Site Language</span>';
	}
	
	$timezone = trim($_POST['timezone'] ?? '');
	if(empty($timezone))
	{
		$errors['timezone'] = '<span class="error">Time Zone</span>';
	}
	
	$load_with_cache = trim($_POST['load_with_cache'] ?? '');
	if(empty($load_with_cache))
	{
		$errors['load_with_cache'] = '<span class="error">Load the Site with Cached Results</span>';
	}
	
	$currency_type = trim($_POST['currency_type'] ?? '');
	if(empty($currency_type))
	{
		$errors['currency_type'] = '<span class="error">Currency Type</span>';
	}
	
	$front_symbol = ltrim($_POST['front_symbol'] ?? '');
	$back_symbol = rtrim($_POST['back_symbol'] ?? '');
	
	$thousand_separator = $_POST['thousand_separator'];
	if(empty($thousand_separator))
	{
		$errors['thousand_separator'] = '<span class="error">Thousand Separator</span>';
	}
		
	$fractional_separator = trim($_POST['fractional_separator'] ?? '');
	if(empty($fractional_separator))
	{
		$errors['fractional_separator'] = '<span class="error">Fractional Separator</span>';
	}
	
	$zeros_after_separator = trim($_POST['zeros_after_separator'] ?? '');
	if(empty($zeros_after_separator))
	{
		$errors['zeros_after_separator'] = '<span class="error">Zeros After Separator</span>';
	}
	
	$email_connector = '';
	if(isset($_POST['email_connector']))
	{
		$email_connector = ' checked';
		$email_port = '25';
	}
	else
	{
		$email_port = '587';
	}
	
	$server_email_name = trim($_POST['server_email_name'] ?? '');
	if(empty($server_email_name))
	{
		$errors['server_email_name'] = '<span class="error">Server Email Name</span>';
	}
	
	$server_email = trim($_POST['server_email'] ?? '');
	if(empty($server_email))
	{
		$errors['server_email'] = '<span class="error">Server Email Address</span>';
	}
	
	$server_smpt_url = trim($_POST['server_smpt_url'] ?? '');
	if(empty($server_smpt_url))
	{
		$errors['server_smpt_url'] = '<span class="error">SMTP Server / Hostname</span>';
	}
	
	$server_email_username = trim($_POST['server_email_username'] ?? '');
	if(empty($server_email_username) && !isset($_POST['email_connector']))
	{
		$errors['server_email_username'] = '<span class="error">Server Email Username</span>';
	}
	
	$server_email_password = trim($_POST['server_email_password'] ?? '');
	if(empty($server_email_password) && !isset($_POST['email_connector']))
	{
		$errors['server_email_password'] = '<span class="error">Server Email Password</span>';
	}
	
	$first_name = trim($_POST['first_name'] ?? '');
	if(empty($first_name))
	{
		$errors['first_name'] = '<span class="error">First Name</span>';
	}
	
	$last_name = trim($_POST['last_name'] ?? '');
	if(empty($last_name))
	{
		$errors['last_name'] = '<span class="error">Last Name</span>';
	}
	
	$country = trim($_POST['country'] ?? '');
	if(empty($country))
	{
		$errors['country'] = '<span class="error">Country</span>';
	}
	
	$street_address = trim($_POST['street_address'] ?? '');
	if(empty($street_address))
	{
		$errors['street_address'] = '<span class="error">Street Address</span>';
	}
	
	$city = trim($_POST['city'] ?? '');
	if(empty($city))
	{
		$errors['city'] = '<span class="error">City</span>';
	}
	
	$state = trim($_POST['state'] ?? '');
	if(empty($state))
	{
		$errors['state'] = '<span class="error">State / Province / Region</span>';
	}
	
	$postal_code = trim($_POST['postal_code'] ?? '');
	if(empty($postal_code))
	{
		$errors['postal_code'] = '<span class="error">Postal Code</span>';
	}
	
	$phone_number = trim($_POST['phone_number'] ?? '');
	if(empty($phone_number))
	{
		$errors['phone_number'] = '<span class="error">Phone Number</span>';
	}
	
	$display_contact_inforamtion = trim($_POST['display_contact_inforamtion'] ?? '');
	if(empty($display_contact_inforamtion))
	{
		$errors['display_contact_inforamtion'] = '<span class="error">Display Contact Information on the Website</span>';
	}
	
	//Make sure Admin Login Path is not easy to find for for brute force login attackes and make sure new admin url directory is avaible to use. 
	$admin_directory = trim($_POST['admin_directory'] ?? '');
	if(empty($admin_directory))
	{
		$errors['admin_directory'] = '<span class="error">Admin Login Path</span>';
	}
	elseif(strtolower($admin_directory) == 'ratals' || strtolower($admin_directory) == 'admin' || strtolower($admin_directory) == 'administrator' || strtolower($admin_directory) == 'admin-panel' || strtolower($admin_directory) == 'admin-login' || strtolower($admin_directory) == 'backend' || strtolower($admin_directory) == 'cms' || strtolower($admin_directory) == 'control' || strtolower($admin_directory) == 'control-panel' || strtolower($admin_directory) == 'cp' || strtolower($admin_directory) == 'dashboard' || strtolower($admin_directory) == 'login' || strtolower($admin_directory) == 'manage' || strtolower($admin_directory) == 'manager' || strtolower($admin_directory) == 'management' || strtolower($admin_directory) == 'panel' || strtolower($admin_directory) == 'root' || strtolower($admin_directory) == 'webadmin' || strtolower($admin_directory) == strtolower($first_name) || strtolower($admin_directory) == strtolower($last_name) || strtolower($admin_directory) == strtolower($site_name))
	{
		$errors['admin_directory'] = '<span class="error">Enter a stronger Admin Login Path that\'s difficult to guess</span>';
	}
	elseif(!preg_match('/^[a-z0-9-]+$/', $admin_directory))
	{
		$errors['admin_directory'] = '<span class="error">Admin Login Path can only contain lowercase a-z, 0-9, and -</span>';
	}
	elseif(is_dir(INSTALLATION_ROOT."/".$admin_directory))
	{
		$errors['admin_directory'] = '<span class="error">Admin Login Path is not available - choose something different</span>';
	}
	
	$username = trim($_POST['username'] ?? '');
	if(empty($username))
	{
		$errors['username'] = '<span class="error">Admin Username</span>';
	}
	elseif(strtolower($username) == 'admin' || strtolower($username) == 'administrator' || strtolower($username) == 'root' || strtolower($username) == strtolower($first_name) || strtolower($username) == strtolower($last_name) || strtolower($username) == strtolower($site_name) || strtolower($username) == strtolower($admin_directory))
	{
		$errors['username'] = '<span class="error">Enter A Stronger Username</span>';
	}
	
	$password = trim($_POST['password'] ?? '');
	if(empty($password))
	{
		$errors['password'] = '<span class="error">Admin Password</span>';
	}
	
	$confirm_password = trim($_POST['confirm_password'] ?? '');
	if(empty($confirm_password))
	{
		$errors['confirm_password'] = '<span class="error">Confirm Admin Password</span>';
	}
	
	if(!empty($password) && !empty($confirm_password) && $password != $confirm_password)
	{
		$errors['password_confirm_password'] = '<span class="center error error-top-margin">Password & Confirm Password didn\'t match.</span>';
	}
	
	if(!empty($password) && !empty($confirm_password) && $password == $confirm_password)
	{
		//Initiated in /config.php. This validates passwords when accounts are created to make sure they have a character, digit and special character in them.
		require_once(INSTALLATION_ROOT.'/admin/cms/functions/password-validation.php');
		$password_validation = passwordValidation($password);
		
		if(strlen($password) < 10)
		{
			$errors['password'] = '<span class="error">Password must be at least 10 characters in length.</span>';
		}
		elseif($password_validation['sepcial_character_in_password'] == 'No')
		{
			$errors['password'] = '<span class="error">Password must have at least 1 special character.</span>';
		}
		elseif($password_validation['letter_in_password'] == 'No')
		{
			$errors['password'] = '<span class="error">Password must have at least 1 letter.</span>';
		}
		elseif($password_validation['number_in_password'] == 'No')
		{
			$errors['password'] = '<span class="error">Password must have at least 1 number.</span>';
		}
		else
		{
			$password = hash("sha512", $password);
		}
	}
	
	if(count($errors) == 0) 
	{
		$installation_step = 'Starting installation';
		$original_htaccess_contents = null;
		$original_admin_htaccess_contents = null;
		$original_user_ini_contents = null;
		$original_admin_user_ini_contents = null;
		
		try
		{
			$installation_step = 'Updating server configuration files';
			
			//Get the .htaccess file path - for Apache and Lightspeed servers
			$htaccess_path = INSTALLATION_ROOT.'/.htaccess';
			if(file_exists($htaccess_path))
			{
				//Read the /.htaccess content.
				$htaccess_contents = file_get_contents($htaccess_path);
				if($htaccess_contents === false)
				{
					throw new Exception('Could not read frontend .htaccess file.');
				}
				$original_htaccess_contents = $htaccess_contents;
				//Replace YOUR_ADMIN_URL_PATH with virtual admin path
				$htaccess_contents = str_replace('YOUR_ADMIN_URL_PATH', $admin_directory, $htaccess_contents);
				//Replace auto_prepend_file for frontend with absolute path
				$htaccess_contents = preg_replace('~php_value\s+auto_prepend_file\s+.*~i', 'php_value auto_prepend_file "'.rtrim(INSTALLATION_ROOT, '/').'/core/session-check-frontend.php"', $htaccess_contents);
				if($htaccess_contents === null)
				{
					throw new Exception('Could not update frontend .htaccess configuration.');
				}
				//Write back the updated /.htaccess content with new admin URL.
				if(file_put_contents($htaccess_path, $htaccess_contents) === false)
				{
					throw new Exception('Could not update frontend .htaccess file.');
				}
			}
			
			//Get the /admin/.htaccess file path - for Apache and Lightspeed servers
			$admin_htaccess_path = INSTALLATION_ROOT.'/admin/.htaccess';
			if(file_exists($admin_htaccess_path))
			{
				//Read the /admin/.htacces content.
				$admin_htaccess_contents = file_get_contents($admin_htaccess_path);
				if($admin_htaccess_contents === false)
				{
					throw new Exception('Could not read admin .htaccess file.');
				}
				$original_admin_htaccess_contents = $admin_htaccess_contents;
				//Replace YOUR_ADMIN_URL_PATH with virtual admin path
				$admin_htaccess_contents = str_replace('YOUR_ADMIN_URL_PATH', $admin_directory, $admin_htaccess_contents);
				//Replace auto_prepend_file for admin with absolute path
				$admin_htaccess_contents = preg_replace('~php_value\s+auto_prepend_file\s+.*~i', 'php_value auto_prepend_file "'.rtrim(INSTALLATION_ROOT, '/').'/core/session-check-admin.php"', $admin_htaccess_contents);
				if($admin_htaccess_contents === null)
				{
					throw new Exception('Could not update admin .htaccess configuration.');
				}
				//Write back the updated /admin/.htacces content with new admin URL.
				if(file_put_contents($admin_htaccess_path, $admin_htaccess_contents) === false)
				{
					throw new Exception('Could not update admin .htaccess file.');
				}
			}
			
			//Update /.user.ini (frontend) - for Nginx / PHP-FPM environments
			$user_ini_path = INSTALLATION_ROOT.'/.user.ini';
			if(file_exists($user_ini_path))
			{
				$doc_root = rtrim(INSTALLATION_ROOT, '/');
				$frontend_path = $doc_root.'/core/session-check-frontend.php';
				$user_ini_contents = file_get_contents($user_ini_path);
				if($user_ini_contents === false)
				{
					throw new Exception('Could not read frontend .user.ini file.');
				}
				$original_user_ini_contents = $user_ini_contents;
				//Replace ONLY the auto_prepend_file line
				$user_ini_contents = preg_replace('/;auto_prepend_file\s*=\s*".*?"/', 'auto_prepend_file = "'.$frontend_path.'"', $user_ini_contents);
				if($user_ini_contents === null)
				{
					throw new Exception('Could not update frontend .user.ini configuration.');
				}
				if(file_put_contents($user_ini_path, $user_ini_contents, LOCK_EX) === false)
				{
					throw new Exception('Could not update frontend .user.ini file.');
				}
			}
			
			//Update /admin/.user.ini (admin) - for Nginx / PHP-FPM environments
			$admin_user_ini_path = INSTALLATION_ROOT.'/admin/.user.ini';
			if(file_exists($admin_user_ini_path))
			{
				$doc_root = rtrim(INSTALLATION_ROOT, '/');
				$admin_path = $doc_root.'/core/session-check-admin.php';
				$admin_user_ini_contents = file_get_contents($admin_user_ini_path);
				if($admin_user_ini_contents === false)
				{
					throw new Exception('Could not read admin .user.ini file.');
				}
				$original_admin_user_ini_contents = $admin_user_ini_contents;
				//Replace ONLY the auto_prepend_file line
				$admin_user_ini_contents = preg_replace('/;auto_prepend_file\s*=\s*".*?"/', 'auto_prepend_file = "'.$admin_path.'"', $admin_user_ini_contents);
				if($admin_user_ini_contents === null)
				{
					throw new Exception('Could not update admin .user.ini configuration.');
				}
				if(file_put_contents($admin_user_ini_path, $admin_user_ini_contents, LOCK_EX) === false)
				{
					throw new Exception('Could not update admin .user.ini file.');
				}
			}
			
			$email = $server_email;
			$redirect_to_opposite_url = 'Yes';
			$auto_generate_canonical_url = 'Yes';
			$url_extension = '/';
			$add_site_name_to_title_tag = 'Yes';
			$separate_site_name_in_title_tag_with = '-';
			$pagination = 30;
			$first_last_name = $first_name.' '.$last_name;
			$new_user_id = 1;
			
			//Make sure last ids for installs are unset. These session variables are set in template-files.php.
			unset($_SESSION['last_menu_id']);
			unset($_SESSION['last_slider_id']);
			unset($_SESSION['last_custom_field_id']);
			unset($_SESSION['last_pages_id']);
			unset($_SESSION['last_url_id']);
			$_SESSION['install_ids'] = array();
			
			$installation_step = 'Creating database credentials';
			
			//Set database connection credentials
			$database_connection_file = file_get_contents(INSTALLATION_ROOT."/admin/cms/installer/data/credentials.php");
			if($database_connection_file === false)
			{
				throw new Exception('Could not read database credentials template.');
			}
			
			$old_database_hostname = '[DATABASE_HOSTNAME]'; 
			$new_database_hostname = $database_hostname;
			$database_connection_file = str_replace($old_database_hostname, $new_database_hostname, $database_connection_file);
			
			$old_database_name = '[DATABASE_NAME]'; 
			$new_database_name = $database_name;
			$database_connection_file = str_replace($old_database_name, $new_database_name, $database_connection_file);
			
			$old_database_username = '[DATABASE_USERNAME]'; 
			$new_database_username = $database_username;
			$database_connection_file = str_replace($old_database_username, $new_database_username, $database_connection_file);
			
			$old_database_password = '[DATABASE_PASSWORD]'; 
			$new_database_password = $database_password;
			$database_connection_file = str_replace($old_database_password, $new_database_password, $database_connection_file);
			
			clearstatcache(); //Clear file cache to make sure its writting to the real file and not buffer version/cache.
			
			$set_database_connection_file = fopen(INSTALLATION_ROOT."/core/database/DbCredentials.php", "w");
			if($set_database_connection_file === false)
			{
				throw new Exception('Could not create database credentials file.');
			}
			$bytes_written = fwrite($set_database_connection_file, $database_connection_file);
			if($bytes_written === false || $bytes_written < strlen($database_connection_file))
			{
				fclose($set_database_connection_file);
				throw new Exception('Could not completely write database credentials file.');
			}
			if(!fclose($set_database_connection_file))
			{
				throw new Exception('Could not close database credentials file after writing.');
			}
			
			$installation_step = 'Creating installation security settings';
			
			//Create unique secret for this install.
			try
			{
				//Preferred: php cryptographically secure.
				$hash_secret = bin2hex(random_bytes(16)); //32 chars
			}
			catch(Exception $e)
			{
				//Fallback if php cryptographically secure fails.
				$hash_secret = '0123456789abcdefghijklmnopqrstuvwxyz';
				$hash_secret = substr(str_shuffle($hash_secret), 0, 32);
			}
			$config_file_path = INSTALLATION_ROOT."/core/config.php";
			$config_contents = file_get_contents($config_file_path);
			if($config_contents === false)
			{
				throw new Exception('Could not read config.php.');
			}
			$config_contents = str_replace('[SET_HASH_SECRET]', $hash_secret, $config_contents);
			if(file_put_contents($config_file_path, $config_contents) === false)
			{
				throw new Exception('Could not update config.php.');
			}
			
			$installation_step = 'Connecting to the installed database';
			
			//Get all classes and connect to database.
			require_once(INSTALLATION_ROOT.'/core/database/index.php');
			
			$installation_step = 'Creating database tables';
			
			//Get function that creates queries so databases can be installed.
			require_once(INSTALLATION_ROOT.'/admin/cms/functions/build-database-table-create-query.php');
			
			//Create database tables.
			$existing_database_tables = array();
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/database/tables/index.php');
			
			$installation_step = 'Preparing database records';
			
			//Get auto increment id columns to install new site on next id.
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/counters.php');
			
			$installation_step = 'Creating website directories';
			
			//Create Account Template Folders on Server
			$directory_path = INSTALLATION_ROOT.'/sites/'.$site_id.'/templates/default';
			if(!is_dir($directory_path) && !mkdir($directory_path, 0755, true))
			{
				throw new Exception('Could not create website template directory.');
			}
			$directory_path = INSTALLATION_ROOT.'/sites/media';
			if(!is_dir($directory_path) && !mkdir($directory_path, 0755, true))
			{
				throw new Exception('Could not create website media directory.');
			}
			$directory_path = INSTALLATION_ROOT.'/sites/media/images';
			if(!is_dir($directory_path) && !mkdir($directory_path, 0755, true))
			{
				throw new Exception('Could not create website images directory.');
			}
			$directory_path = INSTALLATION_ROOT.'/sites/media/videos';
			if(!is_dir($directory_path) && !mkdir($directory_path, 0755, true))
			{
				throw new Exception('Could not create website videos directory.');
			}
			$directory_path = INSTALLATION_ROOT.'/sites/media/files';
			if(!is_dir($directory_path) && !mkdir($directory_path, 0755, true))
			{
				throw new Exception('Could not create website files directory.');
			}
			
			$installation_step = 'Installing website template and media';
			
			//Install Template Files
			$install_template = 'Yes';
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/template.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/template-files.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/media.php');
			
			$installation_step = 'Creating website record';
			
			//Install new site database row.
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/sites.php');
			
			if($site_id == 1)
			{
				$installation_step = 'Installing core admin data';
				
				//These records are only needed once within an account.
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/database/columns/index.php'); //Must stay before admin-fields-lists.php as admin-fields-lists.php needs the state row id.
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/admin-fields-lists.php'); //Must stay before admin-fields-values.php as admin-fields-values.php is updated with admin-fields-lists.php row ids.
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/admin-fields-values.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/admin-field-sections.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/admin-pages.php'); //Must stay before admin-menus-items.php as admin-menus-items.php is updated with admin-pages.php row ids for menu links.
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/admin-menus.php'); //Must run before admin-menus-items.php so we can get the admin_menus ids.
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/admin-menus-items.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/database-column-ids.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/form-fields.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/form-values.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/forms.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/license.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/notices.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/users.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/assigned-fields.php'); //Must run after admin_fields/columns, admin_pages, and users as we assign fields from these tables.
			}
			
			$installation_step = 'Installing site data';
			
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/assignments-sub-items.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/blocking-spam.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/currency.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/custom-fields-global.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/custom-fields.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/menus-items.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/menus.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/page-groups.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/pages.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/search-engines.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/site-contact-info.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/site-security.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/site-settings.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/sliders-items.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/sliders.php');
			require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/urls.php');
			
			$installation_step = 'Completing installation';
			
			//Pause for 10 seconds to ensure the new admin URL is updated in the .htaccess file and the cache is cleared, allowing it to load properly.
			sleep(10);
			
			header("Location: /".$admin_directory."/?signup=success");
			exit;
		}
		//Remove anything installed if the installation failed.
		catch(Throwable $e)
		{
			$cleanup_failed = false;
			
			//Log actual technical failure.
			error_log('Ratals installation failed during '.$installation_step.': '.$e->getMessage().' in '.$e->getFile().' on line '.$e->getLine());
			
			//Delete DbCredentials.php if it was created.
			$database_credentials_file = INSTALLATION_ROOT.'/core/database/DbCredentials.php';
			
			if(file_exists($database_credentials_file))
			{
				if(!unlink($database_credentials_file))
				{
					$cleanup_failed = true;
					error_log('Ratals installation cleanup could not delete DbCredentials.php.');
				}
			}
			
			//Remove any database tables created during the failed installation.
			if(isset($pdo) && $pdo instanceof PDO)
			{
				try
				{
					$pdo->exec('SET FOREIGN_KEY_CHECKS=0');
					
					$database_tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
					
					foreach($database_tables as $database_table)
					{
						$database_table = str_replace('`', '``', $database_table);
						$pdo->exec('DROP TABLE IF EXISTS `'.$database_table.'`');
					}
					
					$pdo->exec('SET FOREIGN_KEY_CHECKS=1');
				}
				catch(Throwable $cleanup_error)
				{
					$cleanup_failed = true;
					error_log('Ratals installation cleanup could not remove database tables: '.$cleanup_error->getMessage());
					
					//Make sure foreign key checks are turned back on if cleanup failed.
					try
					{
						$pdo->exec('SET FOREIGN_KEY_CHECKS=1');
					}
					catch(Throwable $foreign_key_error)
					{
						$cleanup_failed = true;
						error_log('Ratals installation cleanup could not restore foreign key checks: '.$foreign_key_error->getMessage());
					}
				}
			}
			
			//Function used to remove directories created during installation.
			$remove_install_directory = function($directory_path) use (&$remove_install_directory, &$cleanup_failed)
			{
				if(!is_dir($directory_path))
				{
					return;
				}
				
				$directory_contents = scandir($directory_path);
				
				if($directory_contents === false)
				{
					$cleanup_failed = true;
					error_log('Ratals installation cleanup could not read directory: '.$directory_path);
					return;
				}
				
				foreach($directory_contents as $item)
				{
					if($item !== '.' && $item !== '..')
					{
						$item_path = $directory_path.'/'.$item;
						
						if(is_dir($item_path))
						{
							$remove_install_directory($item_path);
						}
						else
						{
							if(!unlink($item_path))
							{
								$cleanup_failed = true;
								error_log('Ratals installation cleanup could not delete file: '.$item_path);
							}
						}
					}
				}
				
				if(!rmdir($directory_path))
				{
					$cleanup_failed = true;
					error_log('Ratals installation cleanup could not remove directory: '.$directory_path);
				}
			};
			
			//Remove site 1 files created during the failed installation.
			$site_directory = INSTALLATION_ROOT.'/sites/1';
			
			if(is_dir($site_directory))
			{
				$remove_install_directory($site_directory);
			}
			
			//Remove media files created during the failed installation.
			$media_directory = INSTALLATION_ROOT.'/sites/media';
			
			if(is_dir($media_directory))
			{
				$remove_install_directory($media_directory);
			}
			
			//Restore original frontend .htaccess file.
			if(isset($original_htaccess_contents) && isset($htaccess_path))
			{
				if(file_put_contents($htaccess_path, $original_htaccess_contents) === false)
				{
					$cleanup_failed = true;
					error_log('Ratals installation cleanup could not restore frontend .htaccess file.');
				}
			}
			
			//Restore original admin .htaccess file.
			if(isset($original_admin_htaccess_contents) && isset($admin_htaccess_path))
			{
				if(file_put_contents($admin_htaccess_path, $original_admin_htaccess_contents) === false)
				{
					$cleanup_failed = true;
					error_log('Ratals installation cleanup could not restore admin .htaccess file.');
				}
			}
			
			//Restore original frontend .user.ini file.
			if(isset($original_user_ini_contents) && isset($user_ini_path))
			{
				if(file_put_contents($user_ini_path, $original_user_ini_contents, LOCK_EX) === false)
				{
					$cleanup_failed = true;
					error_log('Ratals installation cleanup could not restore frontend .user.ini file.');
				}
			}
			
			//Restore original admin .user.ini file.
			if(isset($original_admin_user_ini_contents) && isset($admin_user_ini_path))
			{
				if(file_put_contents($admin_user_ini_path, $original_admin_user_ini_contents, LOCK_EX) === false)
				{
					$cleanup_failed = true;
					error_log('Ratals installation cleanup could not restore admin .user.ini file.');
				}
			}
			
			//Clear PHP file status cache after cleanup.
			clearstatcache();
			
			//Show installer-friendly error.
			if($cleanup_failed === true)
			{
				$errors['installation'] = '<span class="center error error-top-margin">Installation failed while '.$installation_step.'. Ratals could not fully remove the partial installation. Please check your server error log for the exact cause of the failure and to see what could not be cleaned up before trying again.</span>';
			}
			else
			{
				$errors['installation'] = '<span class="center error error-top-margin">Installation failed while '.$installation_step.'. The partial installation was removed. Please check your server error log for the exact cause of the failure before trying again.</span>';
			}
		}
	}
}
?><!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Install Ratals - Ratals Inc.</title>
<script src="/sites/libraries/jquery-3.7.1.min.js"></script>
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { margin: 0px; padding: 0px; font-family: sans-serif, FontAwesome; box-sizing: border-box; font-size: 16px;
--text-color: #1a1a1a;
--muted-text-color: #656565;
--box-bg-color: #ffffff;
--box-border-color: #d6d6d6;
--section-bg-color: #f3f3f3;
--border-color: #e3e3e3;
--primary-link-color: #2f8ec5;
--link-hover-color: #24aef3;
--primary-bg-color: #07344a;
--primary-bg-color-text: #ffffff;
--button-primary-bg-color: #07344a;
--button-primary-border-color: #07344a;
--button-primary-text-color: #ffffff;
--button-primary-hover-bg-color: #106da3;
--button-primary-hover-border-color: #106da3;
--button-primary-focus-shadow-color: rgba(7, 52, 74, 0.22);
--input-bg-color: #ffffff;
--input-text-color: #1a1a1a;
--input-border-color: #c6c6c6;
--input-focus-border-color: #24aef3;
--input-focus-shadow-color: rgba(36, 174, 243, 0.18);
--error-color: #d11d1d;
--success-color: #3f8f46;
--notice-bg-color: #f3f3f3;
--notice-border-color: #d6d6d6;
--overlay-bg-color: rgb(40 40 40 / 65%);
--main-border-radius: 5px;
}
body, div, input, select { box-sizing: border-box; }
input, select { background-color: var(--input-bg-color); color: var(--input-text-color); padding: 8px 10px; height: 40px; border: 1px solid var(--input-border-color); width: 100%; border-radius: var(--main-border-radius); font-size: 15px; transition: border-color 150ms ease, box-shadow 150ms ease; }
input:focus, select:focus { border-color: var(--input-focus-border-color); box-shadow: 0 0 0 3px var(--input-focus-shadow-color); outline: none; }
.body-pending-ajax { margin: 0px; height: 100%; overflow: hidden; }
.pending-ajax { background-color: var(--overlay-bg-color); top: 0px; right: 0px; bottom: 0px; left: 0px; position: fixed; text-align: center; z-index: 9999; }
.pending-ajax-outer-container { display: table; width: 100%; height: 100%; }
.pending-ajax-inner-container { display: table-cell; color: var(--text-color); vertical-align: middle; }
.pending-ajax-inner-container span { background-color: var(--box-bg-color); padding: 10px 20px; border-radius: 25px; display: inline-block; box-shadow: 0px 4px 15px rgba(0,0,0,0.12); }
h1 { font-size: 40px; text-align: center; font-weight: 700; margin: 0px; padding-bottom: 12px; color: var(--text-color); }
h2 { font-size: 16px; text-align: center; font-weight: 400; margin: 0px 0px 20px 0px; padding: 0px; color: var(--muted-text-color); line-height: 22px; }
a { color: var(--primary-link-color); text-decoration: none; }
a:hover { color: var(--link-hover-color); text-decoration: underline; }
.box-wrapper { margin: 20px; }
.box-wrapper span { display: block; padding-bottom: 4px; }
.box-wrapper .box { max-width: 1200px; margin: 30px auto 50px auto; background-color: var(--box-bg-color); border: 1px solid var(--box-border-color); border-radius: var(--main-border-radius); box-shadow: 0px 12px 35px rgba(0,0,0,0.12); padding: 30px; }
.box-wrapper .box .ratals-logo { text-align: center; margin-bottom: 5px; }
.box-wrapper .box .ratals-logo img { width: auto; max-height: 60px; }
.box-wrapper .box .need-help { text-align: center; margin: 12px 0px 0px 0px; font-size: 14px; }
.box-wrapper .box ul.two-column { margin: 0px; padding: 0px; --n: 2; display: grid; grid-template-columns: repeat(auto-fill, minmax(max(300px,(100% - (var(--n) - 1)*20px)/var(--n)), 1fr)); gap: 20px; width: calc(100% - 3px); }
.box-wrapper .box ul.three-column { margin: 0px; padding: 0px; --n: 3; display: grid; grid-template-columns: repeat(auto-fill, minmax(max(180px,(100% - (var(--n) - 1)*20px)/var(--n)), 1fr)); gap: 20px; width: calc(100% - 3px); }
.box-wrapper .box ul.four-column { margin: 0px; padding: 0px; --n: 4; display: grid; grid-template-columns: repeat(auto-fill, minmax(max(180px,(100% - (var(--n) - 1)*20px)/var(--n)), 1fr)); gap: 20px; width: calc(100% - 3px); }
.box-wrapper .box ul.five-column { margin: 0px; padding: 0px; --n: 5; display: grid; grid-template-columns: repeat(auto-fill, minmax(max(180px,(100% - (var(--n) - 1)*20px)/var(--n)), 1fr)); gap: 20px; width: calc(100% - 3px); }
.box-wrapper .box ul li { list-style: none; margin: 0px; }
.box-wrapper .box ul li.full-row { grid-column: 1 / -1; }
.box-wrapper .headline { font-weight: 600; padding: 10px 12px; background-color: var(--primary-bg-color); color: var(--primary-bg-color-text); border-radius: var(--main-border-radius); margin-top: 20px; }
.box-wrapper .http-s { width: 87px; vertical-align: top; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-right: 0px; }
.box-wrapper .www { width: 87px; vertical-align: top; border-radius: 0px; border-right: 1px dashed var(--input-border-color); border-left: 1px dashed var(--input-border-color); }
.box-wrapper .tld { width: calc(100% - 175px); vertical-align: top; border-left: 0px; border-top-left-radius: 0px; border-bottom-left-radius: 0px; }
.box-wrapper button { font-size: 16px; font-weight: 600; padding: 10px 15px; display: inline-block; width: 100%; border: 1px solid var(--button-primary-border-color); background-color: var(--button-primary-bg-color); color: var(--button-primary-text-color); cursor: pointer; border-radius: var(--main-border-radius); transition: background-color 150ms ease, border-color 150ms ease, box-shadow 150ms ease; }
.box-wrapper button:hover { background-color: var(--button-primary-hover-bg-color); border-color: var(--button-primary-hover-border-color); }
.box-wrapper button:focus { box-shadow: 0 0 0 3px var(--button-primary-focus-shadow-color); outline: none; }
.box-wrapper .small-font { font-size: 14px; line-height: 20px; }
.box-wrapper .note-small-font { font-size: 11px; margin-top: 4px; color: var(--muted-text-color); line-height: 16px; }
.box-wrapper .sub-text { background-color: var(--section-bg-color); padding: 12px 14px; line-height: 22px; color: var(--text-color); border: 1px solid var(--border-color); border-radius: var(--main-border-radius); }
.box-wrapper .currency_format { font-weight: 600; }
.box-wrapper .currency_format, .box-wrapper .currency_format span { display: inline; }
.box-wrapper .email_connector { margin: 6px 0px; }
.box-wrapper .email_connector label { cursor: pointer; display: inline-flex; align-items: center; gap: 5px; }
.box-wrapper .email_connector input { height: 18px; width: 18px; vertical-align: middle; margin: 0px; cursor: pointer; accent-color: var(--primary-bg-color); }
.box-wrapper .be-patient { font-size: 12px; line-height: 18px; text-align: center; color: var(--muted-text-color); margin-top: 10px; }
.box-wrapper .password-requirements-wrap { background-color: var(--notice-bg-color); border: 1px solid var(--notice-border-color); padding: 12px 14px; border-radius: var(--main-border-radius); font-size: 13px; line-height: 19px; }
.box-wrapper .box ul li .password-requirements { margin: 0px; padding: 8px 8px 0px 22px; }
.box-wrapper .box ul li .password-requirements li { list-style: initial; padding-bottom: 3px; }
.box-wrapper .center { text-align: center; }
.box-wrapper .error { color: var(--error-color); }
.box-wrapper .error-top-margin { margin-top: 12px; }
.footer { margin-bottom: 30px; text-align: center; font-size: 12px; color: var(--muted-text-color); }
.footer .footer-wrap { margin: 0px auto; max-width: 800px; }
.footer .footer-wrap .text { margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid var(--border-color); border-left: 100px solid transparent; border-right: 100px solid transparent; }
.footer .footer-wrap .powered-by span { color: var(--muted-text-color); }
.footer a { color: var(--primary-link-color); }
.footer a:hover { color: var(--link-hover-color); }
</style>
<script>
$(document).ready(function()
{
	//Disable install button when clicked and show message of installing.
	$("#install-button").click(function()
	{
		var $installButton = $(this);
	
		//Disable install button after 10 ms so form can submit first
		setTimeout(function()
		{
			$installButton.prop("disabled", true);
			$installButton.text("INSTALLING RATALS...");
		}, 10);
		
		//Optionally, show a message somewhere on the page
		$("body").addClass("body-pending-ajax");
		$(".pending-ajax").show();
	});
	
	//Format Currency front_symbol on load.
	var frontSymbol = $('.front_symbol_field').val();
	$('.front_symbol').text(frontSymbol);
	//Format Currency back_symbol on load.
	var backSymbol = $('.back_symbol_field').val();
	$('.back_symbol').text(backSymbol);
	//Format Currency thousand_separator on load.
	$('.thousand_separator_field').each(function() 
	{
		var thousandSeparator = $(this).val();
		$('.thousand_separator').text(thousandSeparator);
	});
	//Format Currency fractional_separator on load.
	$('.fractional_separator_field').each(function() 
	{
		var fractionalSeparator = $(this).val();
		$('.fractional_separator').text(fractionalSeparator);
	});
	//Format Currency zeros_after_separator on load.
	$('.zeros_after_separator_field').each(function() 
	{
		var zerosAfterSeparator = $(this).val();
		if(zerosAfterSeparator == '1')
		{
			$('.zeros_after_separator').text('0');
		}
		else if(zerosAfterSeparator == '2')
		{
			$('.zeros_after_separator').text('00');
		}
		else if(zerosAfterSeparator == '3')
		{
			$('.zeros_after_separator').text('000');
		}
		else if(zerosAfterSeparator == '4')
		{
			$('.zeros_after_separator').text('0000');
		}
		else if(zerosAfterSeparator == '5')
		{
			$('.zeros_after_separator').text('00000');
		}
		else if(zerosAfterSeparator == '6')
		{
			$('.zeros_after_separator').text('000000');
		}
	});
	
	//Format Currency front_symbol on change.
	$(document).on('input', '.front_symbol_field', function() {
		var frontSymbol = $(this).val();
		$('.front_symbol').text(frontSymbol);
	});
	//Format Currency back_symbol on change.
	$(document).on('input', '.back_symbol_field', function() {
		var backSymbol = $(this).val();
		$('.back_symbol').text(backSymbol);
	});
	//Format Currency thousand_separator on change.
	$(document).on('change', '.thousand_separator_field', function()
	{
		var thousandSeparator = $(this).val();
		$('.thousand_separator').text(thousandSeparator);
	});
	//Format Currency fractional_separator on change.
	$(document).on('change', '.fractional_separator_field', function()
	{
		var fractionalSeparator = $(this).val();
		$('.fractional_separator').text(fractionalSeparator);
	});
	//Format Currency zeros_after_separator on change.
	$(document).on('change', '.zeros_after_separator_field', function()
	{
		var zerosAfterSeparator = $(this).val();
		
		if(zerosAfterSeparator == '1')
		{
			$('.zeros_after_separator').text('0');
		}
		else if(zerosAfterSeparator == '2')
		{
			$('.zeros_after_separator').text('00');
		}
		else if(zerosAfterSeparator == '3')
		{
			$('.zeros_after_separator').text('000');
		}
		else if(zerosAfterSeparator == '4')
		{
			$('.zeros_after_separator').text('0000');
		}
		else if(zerosAfterSeparator == '5')
		{
			$('.zeros_after_separator').text('00000');
		}
		else if(zerosAfterSeparator == '6')
		{
			$('.zeros_after_separator').text('000000');
		}
	});
});
</script>
</head>

<body>
<!-- Start Pending Overlay -->
<style>.pending-ajax { display: none; }</style>
<div class="pending-ajax">
	<div class="pending-ajax-outer-container">
		<div class="pending-ajax-inner-container">
			<span>Hang tight... Installing Ratals. This can take up to 30 seconds.</span>
		</div>
	</div>
</div>
<!-- End Pending Overlay -->
<div class="box-wrapper">
	<div class="box">
        <div class="ratals-logo"><img src="/sites/ratals-logo.png" width="506" height="137" alt="Ratals Logo"></div>
<?php if(!empty($nginx_warning)) { echo $nginx_warning; } ?>
        <p class="need-help">Need help? Watch our <a href="https://www.ratals.com/tutorials/installation/ratals-installation-guide/" target="_blank">Installation Guide videos</a> on ratals.com.</p>
		<?php if(!empty($errors)) { echo '<span class="center error error-top-margin">Oh Snap! Something isn\'t right.</span>'; } ?>
		<?php if(!empty($errors['database_connection'])) { echo $errors['database_connection']; } ?>
        <?php if(!empty($errors['database_empty'])) { echo $errors['database_empty']; } ?>
        <?php if(!empty($errors['database_empty_check'])) { echo $errors['database_empty_check']; } ?>
        <?php if(!empty($errors['database_collation'])) { echo $errors['database_collation']; } ?>
        <?php if(!empty($errors['database_collation_check'])) { echo $errors['database_collation_check']; } ?>
        <?php if(!empty($errors['installation'])) { echo $errors['installation']; } ?>
		<?php if(isset($errors['password_confirm_password'])) { echo $errors['password_confirm_password']; } ?>
		
		<form action="" method="POST">
		<ul class="four-column">
			<li class="full-row">
				<div class="headline">DATABASE CONNECTION</div>
			</li>
			<li>
				<?php if(isset($errors['database_hostname'])) { echo $errors['database_hostname']; } else { echo '<span>Database Hostname</span>'; } ?>
				<input name="database_hostname" type="text" value="<?php if(isset($_POST['submit'])) { echo $database_hostname; } else { echo 'localhost'; } ?>" />
				<div class="note-small-font"><?php if(isset($errors['database_hostname_quote'])) { echo $errors['database_hostname_quote']; } ?></div>
			</li>
            <li>
				<?php if(isset($errors['database_name'])) { echo $errors['database_name']; } else { echo '<span>Database Name</span>'; } ?>
				<input name="database_name" type="text" value="<?php if(isset($_POST['submit'])) { echo $database_name; } ?>" />
				<div class="note-small-font"><?php if(isset($errors['database_name_quote'])) { echo $errors['database_name_quote']; } ?></div>
			</li>
			<li>
				<?php if(isset($errors['database_username'])) { echo $errors['database_username']; } else { echo '<span>Database Username</span>'; } ?>
				<input name="database_username" type="text" value="<?php if(isset($_POST['submit'])) { echo $database_username; } ?>" />
				<div class="note-small-font"><?php if(isset($errors['database_username_quote'])) { echo $errors['database_username_quote']; } ?></div>
			</li>
			<li>
				<?php if(isset($errors['database_password'])) { echo $errors['database_password']; } else { echo '<span>Database Password</span>'; } ?>
				<input name="database_password" type="password" value="" />
				<div class="note-small-font"><?php if(isset($errors['database_password_quote'])) { echo $errors['database_password_quote']; } ?></div>
			</li>
		</ul>
		<ul class="two-column">
			<li class="full-row">
				<div class="headline">SITE SETTINGS</div>
			</li>
			<li>
				<?php if(isset($errors['site_name'])) { echo $errors['site_name']; } else { echo '<span>Site Name</span>'; } ?>
				<input name="site_name" type="text" value="<?php echo $site_name; ?>" />
			</li>
			<li>
				<?php if(isset($errors['https_in_url'])) { echo $errors['https_in_url']; } ?>
				<?php if(isset($errors['www_in_url'])) { echo $errors['www_in_url']; } ?>
				<?php if(isset($errors['tld'])) { echo $errors['tld']; } else { echo '<span>Domain</span>'; } ?>
                <select name="https_in_url" class="http-s">
					<option value="">Select</option>
                    <option value="No"<?php if(isset($_POST['https_in_url']) && $_POST['https_in_url'] == 'No') { echo ' selected'; } ?>>http://</option>
                    <option value="Yes"<?php if(!isset($_POST['https_in_url']) && $https_status === true) { echo ' selected'; } elseif(isset($_POST['https_in_url']) && $_POST['https_in_url'] == 'Yes') { echo ' selected'; } ?>>https://</option>
				</select><select name="www_in_url" class="www">
					<option value="">Select</option>
                    <option value="No"<?php if(isset($_POST['www_in_url']) && $_POST['www_in_url'] == 'No') { echo ' selected'; } ?>>none</option>
                    <option value="Yes"<?php if(isset($_POST['www_in_url']) && $_POST['www_in_url'] == 'Yes') { echo ' selected'; } ?>>www.</option>
				</select><input name="tld" class="tld" type="text" value="<?php echo htmlspecialchars($tld ?? '', ENT_QUOTES); ?>" />
			</li>
			<li>
				<?php if(isset($errors['admin_directory'])) { echo $errors['admin_directory']; } else { echo '<span>Admin Login Path</span>'; } ?>
				<input name="admin_directory" placeholder="Enter path only - no domain" type="text" value="<?php if(isset($_POST['submit'])) { echo $admin_directory; } else { echo 'admin-login'; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['site_language'])) { echo $errors['site_language']; } else { echo '<span>Site Language</span>'; } ?>
				<select name="site_language" class="site_language">
					<?php echo $all_language_options; ?>
				</select>
			</li>
			<li>
				<?php if(isset($errors['timezone'])) { echo $errors['timezone']; } else { echo '<span>Time Zone</span>'; } ?>
				<select name="timezone" class="timezone">
					<?php echo $all_time_zone_options; ?>
				</select>
			</li>
			<li>
				<?php if(isset($errors['load_with_cache'])) { echo $errors['load_with_cache']; } else { echo '<span>Load the Site with Cached Results</span>'; } ?>
				<select name="load_with_cache" class="load_with_cache">
					<option value="Yes"<?php if(isset($_POST['load_with_cache']) && $_POST['load_with_cache'] == 'Yes') { echo ' selected'; } ?>>Yes</option>
					<option value="No"<?php if(isset($_POST['load_with_cache']) && $_POST['load_with_cache'] == 'No') { echo ' selected'; } ?>>No</option>
				</select>
                <div class="note-small-font">Cached pages reduce server resources during higher website traffic and automatically refresh on content updates.</div>
			</li>
		</ul>
		<ul class="three-column">
			<li class="full-row">
				<div class="headline">CURRENCY FORMAT SETUP</div>
			</li>
			<li class="full-row">
				Currency format: <span class="currency_format"><span class="front_symbol"></span>1<span class="thousand_separator"></span>000<span class="fractional_separator"></span><span class="zeros_after_separator"></span><span class="back_symbol"></span></span>
			</li>
			<li>
				<?php if(isset($errors['currency_type'])) { echo $errors['currency_type']; } else { echo '<span>Currency Type</span>'; } ?>
				<select name="currency_type">
					<option value=""></option>
					<option value="AED"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'AED') { echo ' selected'; } ?>>AED</option>
					<option value="AUD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'AUD') { echo ' selected'; } ?>>AUD</option>
					<option value="BRL"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'BRL') { echo ' selected'; } ?>>BRL</option>
					<option value="CAD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'CAD') { echo ' selected'; } ?>>CAD</option>
					<option value="CHF"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'CHF') { echo ' selected'; } ?>>CHF</option>
					<option value="CNY"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'CNY') { echo ' selected'; } ?>>CNY</option>
					<option value="DKK"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'DKK') { echo ' selected'; } ?>>DKK</option>
					<option value="EUR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'EUR') { echo ' selected'; } ?>>EUR</option>
					<option value="GBP"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'GBP') { echo ' selected'; } ?>>GBP</option>
					<option value="HKD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'HKD') { echo ' selected'; } ?>>HKD</option>
					<option value="IDR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'IDR') { echo ' selected'; } ?>>IDR</option>
					<option value="INR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'INR') { echo ' selected'; } ?>>INR</option>
					<option value="JPY"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'JPY') { echo ' selected'; } ?>>JPY</option>
					<option value="KRW"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'KRW') { echo ' selected'; } ?>>KRW</option>
					<option value="MXN"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'MXN') { echo ' selected'; } ?>>MXN</option>
					<option value="MYR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'MYR') { echo ' selected'; } ?>>MYR</option>
					<option value="NOK"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'NOK') { echo ' selected'; } ?>>NOK</option>
					<option value="NZD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'NZD') { echo ' selected'; } ?>>NZD</option>
					<option value="PLN"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'PLN') { echo ' selected'; } ?>>PLN</option>
					<option value="RUB"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'RUB') { echo ' selected'; } ?>>RUB</option>
					<option value="SAR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'SAR') { echo ' selected'; } ?>>SAR</option>
					<option value="SEK"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'SEK') { echo ' selected'; } ?>>SEK</option>
					<option value="SGD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'SGD') { echo ' selected'; } ?>>SGD</option>
					<option value="THB"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'THB') { echo ' selected'; } ?>>THB</option>
					<option value="TRY"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'TRY') { echo ' selected'; } ?>>TRY</option>
					<option value="USD"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'USD') { echo ' selected'; } elseif(!isset($_POST['currency_type'])) { echo ' selected'; } ?>>USD</option>
					<option value="ZAR"<?php if(isset($_POST['currency_type']) && $_POST['currency_type'] == 'ZAR') { echo ' selected'; } ?>>ZAR</option>
				</select>
			</li>
			<li>
				<?php if(isset($errors['front_symbol'])) { echo $errors['front_symbol']; } else { echo '<span>Front Symbol</span>'; } ?>
				<input name="front_symbol" type="text" class="front_symbol_field" value="<?php if(isset($_POST['submit'])) { echo $front_symbol; } else { echo '$'; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['back_symbol'])) { echo $errors['back_symbol']; } else { echo '<span>Back Symbol</span>'; } ?>
				<input name="back_symbol" type="text" class="back_symbol_field" value="<?php if(isset($_POST['submit'])) { echo $back_symbol; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['thousand_separator'])) { echo $errors['thousand_separator']; } else { echo '<span>Thousand Separator</span>'; } ?>
				<select name="thousand_separator" class="thousand_separator_field">
					<option value=","<?php if(isset($_POST['thousand_separator']) && $_POST['thousand_separator'] == ',') { echo ' selected'; } elseif(!isset($_POST['thousand_separator'])) { echo ' selected'; } ?>>, (comma)</option>
					<option value="."<?php if(isset($_POST['thousand_separator']) && $_POST['thousand_separator'] == '.') { echo ' selected'; } ?>>. (decimal point)</option>
					<option value="'"<?php if(isset($_POST['thousand_separator']) && $_POST['thousand_separator'] == "'") { echo ' selected'; } ?>>' (single quote)</option>
					<option value=" "<?php if(isset($_POST['thousand_separator']) && $_POST['thousand_separator'] == ' ') { echo ' selected'; } ?>>" " (space)</option>
				</select>
			</li>
			<li>
				<?php if(isset($errors['fractional_separator'])) { echo $errors['fractional_separator']; } else { echo '<span>Fractional Separator</span>'; } ?>
				<select name="fractional_separator" class="fractional_separator_field">
					<option value=","<?php if(isset($_POST['fractional_separator']) && $_POST['fractional_separator'] == ',') { echo ' selected'; } ?>>, (comma)</option>
					<option value="."<?php if(isset($_POST['fractional_separator']) && $_POST['fractional_separator'] == '.') { echo ' selected'; } elseif(!isset($_POST['fractional_separator'])) { echo ' selected'; } ?>>. (decimal point)</option>
				</select>
			</li>
			<li>
				<?php if(isset($errors['zeros_after_separator'])) { echo $errors['zeros_after_separator']; } else { echo '<span>Zeros After Fractional Separator</span>'; } ?>
				<select name="zeros_after_separator" class="zeros_after_separator_field">
					<option value="1"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '1') { echo ' selected'; } ?>>1</option>
					<option value="2"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '2') { echo ' selected'; } elseif(!isset($_POST['zeros_after_separator'])) { echo ' selected'; } ?>>2</option>
					<option value="3"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '3') { echo ' selected'; } ?>>3</option>
					<option value="4"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '4') { echo ' selected'; } ?>>4</option>
					<option value="5"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '5') { echo ' selected'; } ?>>5</option>
					<option value="6"<?php if(isset($_POST['zeros_after_separator']) && $_POST['zeros_after_separator'] == '6') { echo ' selected'; } ?>>6</option>
				</select>
			</li>
		</ul> 
		<ul class="five-column">
			<li class="full-row">
				<div class="headline">EMAIL SERVER SETUP</div>
			</li>
			<li class="full-row small-font sub-text">
				Enter the email account settings associated with <?php echo $tld; ?>. These settings should match the email account you set up in your hosting account and will be used for outgoing email throughout your Ratals installation, including password recovery and security alerts. The default values are examples and may need to be changed for your hosting account.

			</li>
			<li class="full-row small-font">
				<div class="email_connector"><label><input name="email_connector" type="checkbox"<?php if(isset($email_connector)) { echo $email_connector; } ?>> Email server is configured with a relay or connector and does not require a username and password.</label></div>
			</li>
			<li>
				<?php if(isset($errors['server_email_name'])) { echo $errors['server_email_name']; } else { echo '<span>Server Email Name</span>'; } ?>
				<input name="server_email_name" placeholder="i.e. Support" type="text" value="<?php if(isset($_POST['submit'])) { echo $server_email_name; } else { echo 'Support'; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['server_email'])) { echo $errors['server_email']; } else { echo '<span>Server Email Address</span>'; } ?>
				<input name="server_email" placeholder="i.e. support@<?php echo $tld; ?>" type="text" value="<?php if(isset($_POST['submit'])) { echo $server_email; } else { echo 'support@'.$tld; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['server_smpt_url'])) { echo $errors['server_smpt_url']; } else { echo '<span>SMTP Server / Hostname</span>'; } ?>
				<input name="server_smpt_url" placeholder="i.e. mail.<?php echo $tld; ?>" type="text" value="<?php if(isset($_POST['submit'])) { echo $server_smpt_url; } else { echo 'mail.'.$tld; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['server_email_username'])) { echo $errors['server_email_username']; } else { echo '<span>Server Email Username</span>'; } ?>
				<input name="server_email_username" type="text" value="<?php if(isset($_POST['submit'])) { echo $server_email_username; } else { echo 'support@'.$tld; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['server_email_password'])) { echo $errors['server_email_password']; } else { echo '<span>Server Email Password</span>'; } ?>
				<input name="server_email_password" type="password" value="" />
			</li>
		</ul>
		<ul class="three-column">
			<li class="full-row">
				<div class="headline">USER & COMPANY INFORMATION</div>
			</li>
            <li class="full-row small-font sub-text">
				This information is used to create your administrator profile and company contact information that can be displayed on your website. To prevent your company contact information from being displayed, select No for Display This Contact Information on the Website.
			</li>
			<li>
				<?php if(isset($errors['first_name'])) { echo $errors['first_name']; } else { echo '<span>First Name</span>'; } ?>
				<input name="first_name" type="text" value="<?php if(isset($_POST['submit'])) { echo $first_name; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['last_name'])) { echo $errors['last_name']; } else { echo '<span>Last Name</span>'; } ?>
				<input name="last_name" type="text" value="<?php if(isset($_POST['submit'])) { echo $last_name; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['country'])) { echo $errors['country']; } else { echo '<span>Country</span>'; } ?>
				<select name="country">
					<option value=""></option>
					<option value="AF"<?php if(isset($_POST['country']) && $_POST['country'] == 'AF') { echo ' selected'; } ?>>Afghanistan</option>
					<option value="AL"<?php if(isset($_POST['country']) && $_POST['country'] == 'AL') { echo ' selected'; } ?>>Albania</option>
					<option value="DZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'DZ') { echo ' selected'; } ?>>Algeria</option>
					<option value="AD"<?php if(isset($_POST['country']) && $_POST['country'] == 'AD') { echo ' selected'; } ?>>Andorra</option>
					<option value="AO"<?php if(isset($_POST['country']) && $_POST['country'] == 'AO') { echo ' selected'; } ?>>Angola</option>
					<option value="AG"<?php if(isset($_POST['country']) && $_POST['country'] == 'AG') { echo ' selected'; } ?>>Antigua and Barbuda</option>
					<option value="AR"<?php if(isset($_POST['country']) && $_POST['country'] == 'AR') { echo ' selected'; } ?>>Argentina</option>
					<option value="AM"<?php if(isset($_POST['country']) && $_POST['country'] == 'AM') { echo ' selected'; } ?>>Armenia</option>
					<option value="AU"<?php if(isset($_POST['country']) && $_POST['country'] == 'AU') { echo ' selected'; } ?>>Australia</option>
					<option value="AT"<?php if(isset($_POST['country']) && $_POST['country'] == 'AT') { echo ' selected'; } ?>>Austria</option>
					<option value="AZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'AZ') { echo ' selected'; } ?>>Azerbaijan</option>
					<option value="BS"<?php if(isset($_POST['country']) && $_POST['country'] == 'BS') { echo ' selected'; } ?>>Bahamas</option>
					<option value="BH"<?php if(isset($_POST['country']) && $_POST['country'] == 'BH') { echo ' selected'; } ?>>Bahrain</option>
					<option value="BD"<?php if(isset($_POST['country']) && $_POST['country'] == 'BD') { echo ' selected'; } ?>>Bangladesh</option>
					<option value="BB"<?php if(isset($_POST['country']) && $_POST['country'] == 'BB') { echo ' selected'; } ?>>Barbados</option>
					<option value="BY"<?php if(isset($_POST['country']) && $_POST['country'] == 'BY') { echo ' selected'; } ?>>Belarus</option>
					<option value="BE"<?php if(isset($_POST['country']) && $_POST['country'] == 'BE') { echo ' selected'; } ?>>Belgium</option>
					<option value="BZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'BZ') { echo ' selected'; } ?>>Belize</option>
					<option value="BJ"<?php if(isset($_POST['country']) && $_POST['country'] == 'BJ') { echo ' selected'; } ?>>Benin</option>
					<option value="BT"<?php if(isset($_POST['country']) && $_POST['country'] == 'BT') { echo ' selected'; } ?>>Bhutan</option>
					<option value="BO"<?php if(isset($_POST['country']) && $_POST['country'] == 'BO') { echo ' selected'; } ?>>Bolivia</option>
					<option value="BA"<?php if(isset($_POST['country']) && $_POST['country'] == 'BA') { echo ' selected'; } ?>>Bosnia and Herzegovina</option>
					<option value="BW"<?php if(isset($_POST['country']) && $_POST['country'] == 'BW') { echo ' selected'; } ?>>Botswana</option>
					<option value="BR"<?php if(isset($_POST['country']) && $_POST['country'] == 'BR') { echo ' selected'; } ?>>Brazil</option>
					<option value="BN"<?php if(isset($_POST['country']) && $_POST['country'] == 'BN') { echo ' selected'; } ?>>Brunei</option>
					<option value="BG"<?php if(isset($_POST['country']) && $_POST['country'] == 'BG') { echo ' selected'; } ?>>Bulgaria</option>
					<option value="BF"<?php if(isset($_POST['country']) && $_POST['country'] == 'BF') { echo ' selected'; } ?>>Burkina Faso</option>
					<option value="BI"<?php if(isset($_POST['country']) && $_POST['country'] == 'BI') { echo ' selected'; } ?>>Burundi</option>
					<option value="CI"<?php if(isset($_POST['country']) && $_POST['country'] == 'CI') { echo ' selected'; } ?>>Cote d'Ivoire</option>
					<option value="CV"<?php if(isset($_POST['country']) && $_POST['country'] == 'CV') { echo ' selected'; } ?>>Cabo Verde</option>
					<option value="KH"<?php if(isset($_POST['country']) && $_POST['country'] == 'KH') { echo ' selected'; } ?>>Cambodia</option>
					<option value="CM"<?php if(isset($_POST['country']) && $_POST['country'] == 'CM') { echo ' selected'; } ?>>Cameroon</option>
					<option value="CA"<?php if(isset($_POST['country']) && $_POST['country'] == 'CA') { echo ' selected'; } ?>>Canada</option>
					<option value="CF"<?php if(isset($_POST['country']) && $_POST['country'] == 'CF') { echo ' selected'; } ?>>Central African Republic</option>
					<option value="TD"<?php if(isset($_POST['country']) && $_POST['country'] == 'TD') { echo ' selected'; } ?>>Chad</option>
					<option value="CL"<?php if(isset($_POST['country']) && $_POST['country'] == 'CL') { echo ' selected'; } ?>>Chile</option>
					<option value="CN"<?php if(isset($_POST['country']) && $_POST['country'] == 'CN') { echo ' selected'; } ?>>China</option>
					<option value="CO"<?php if(isset($_POST['country']) && $_POST['country'] == 'CO') { echo ' selected'; } ?>>Colombia</option>
					<option value="KM"<?php if(isset($_POST['country']) && $_POST['country'] == 'KM') { echo ' selected'; } ?>>Comoros</option>
					<option value="CG"<?php if(isset($_POST['country']) && $_POST['country'] == 'CG') { echo ' selected'; } ?>>Congo (Congo-Brazzaville)</option>
					<option value="CR"<?php if(isset($_POST['country']) && $_POST['country'] == 'CR') { echo ' selected'; } ?>>Costa Rica</option>
					<option value="HR"<?php if(isset($_POST['country']) && $_POST['country'] == 'HR') { echo ' selected'; } ?>>Croatia</option>
					<option value="CU"<?php if(isset($_POST['country']) && $_POST['country'] == 'CU') { echo ' selected'; } ?>>Cuba</option>
					<option value="CY"<?php if(isset($_POST['country']) && $_POST['country'] == 'CY') { echo ' selected'; } ?>>Cyprus</option>
					<option value="CZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'CZ') { echo ' selected'; } ?>>Czechia (Czech Republic)</option>
					<option value="CD"<?php if(isset($_POST['country']) && $_POST['country'] == 'CD') { echo ' selected'; } ?>>Democratic Republic of the Congo</option>
					<option value="DK"<?php if(isset($_POST['country']) && $_POST['country'] == 'DK') { echo ' selected'; } ?>>Denmark</option>
					<option value="DJ"<?php if(isset($_POST['country']) && $_POST['country'] == 'DJ') { echo ' selected'; } ?>>Djibouti</option>
					<option value="DM"<?php if(isset($_POST['country']) && $_POST['country'] == 'DM') { echo ' selected'; } ?>>Dominica</option>
					<option value="DO"<?php if(isset($_POST['country']) && $_POST['country'] == 'DO') { echo ' selected'; } ?>>Dominican Republic</option>
					<option value="EC"<?php if(isset($_POST['country']) && $_POST['country'] == 'EC') { echo ' selected'; } ?>>Ecuador</option>
					<option value="EG"<?php if(isset($_POST['country']) && $_POST['country'] == 'EG') { echo ' selected'; } ?>>Egypt</option>
					<option value="SV"<?php if(isset($_POST['country']) && $_POST['country'] == 'SV') { echo ' selected'; } ?>>El Salvador</option>
					<option value="GQ"<?php if(isset($_POST['country']) && $_POST['country'] == 'GQ') { echo ' selected'; } ?>>Equatorial Guinea</option>
					<option value="ER"<?php if(isset($_POST['country']) && $_POST['country'] == 'ER') { echo ' selected'; } ?>>Eritrea</option>
					<option value="EE"<?php if(isset($_POST['country']) && $_POST['country'] == 'EE') { echo ' selected'; } ?>>Estonia</option>
					<option value="SZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'SZ') { echo ' selected'; } ?>>Eswatini (fmr. "Swaziland")</option>
					<option value="ET"<?php if(isset($_POST['country']) && $_POST['country'] == 'ET') { echo ' selected'; } ?>>Ethiopia</option>
					<option value="FJ"<?php if(isset($_POST['country']) && $_POST['country'] == 'FJ') { echo ' selected'; } ?>>Fiji</option>
					<option value="FI"<?php if(isset($_POST['country']) && $_POST['country'] == 'FI') { echo ' selected'; } ?>>Finland</option>
					<option value="FR"<?php if(isset($_POST['country']) && $_POST['country'] == 'FR') { echo ' selected'; } ?>>France</option>
					<option value="GA"<?php if(isset($_POST['country']) && $_POST['country'] == 'GA') { echo ' selected'; } ?>>Gabon</option>
					<option value="GM"<?php if(isset($_POST['country']) && $_POST['country'] == 'GM') { echo ' selected'; } ?>>Gambia</option>
					<option value="GE"<?php if(isset($_POST['country']) && $_POST['country'] == 'GE') { echo ' selected'; } ?>>Georgia</option>
					<option value="DE"<?php if(isset($_POST['country']) && $_POST['country'] == 'DE') { echo ' selected'; } ?>>Germany</option>
					<option value="GH"<?php if(isset($_POST['country']) && $_POST['country'] == 'GH') { echo ' selected'; } ?>>Ghana</option>
					<option value="GR"<?php if(isset($_POST['country']) && $_POST['country'] == 'GR') { echo ' selected'; } ?>>Greece</option>
					<option value="GD"<?php if(isset($_POST['country']) && $_POST['country'] == 'GD') { echo ' selected'; } ?>>Grenada</option>
					<option value="GT"<?php if(isset($_POST['country']) && $_POST['country'] == 'GT') { echo ' selected'; } ?>>Guatemala</option>
					<option value="GN"<?php if(isset($_POST['country']) && $_POST['country'] == 'GN') { echo ' selected'; } ?>>Guinea</option>
					<option value="GW"<?php if(isset($_POST['country']) && $_POST['country'] == 'GW') { echo ' selected'; } ?>>Guinea-Bissau</option>
					<option value="GY"<?php if(isset($_POST['country']) && $_POST['country'] == 'GY') { echo ' selected'; } ?>>Guyana</option>
					<option value="HT"<?php if(isset($_POST['country']) && $_POST['country'] == 'HT') { echo ' selected'; } ?>>Haiti</option>
					<option value="VA"<?php if(isset($_POST['country']) && $_POST['country'] == 'VA') { echo ' selected'; } ?>>Holy See</option>
					<option value="HN"<?php if(isset($_POST['country']) && $_POST['country'] == 'HN') { echo ' selected'; } ?>>Honduras</option>
					<option value="HK"<?php if(isset($_POST['country']) && $_POST['country'] == 'HK') { echo ' selected'; } ?>>Hong Kong SAR</option>
					<option value="HU"<?php if(isset($_POST['country']) && $_POST['country'] == 'HU') { echo ' selected'; } ?>>Hungary</option>
					<option value="IS"<?php if(isset($_POST['country']) && $_POST['country'] == 'IS') { echo ' selected'; } ?>>Iceland</option>
					<option value="IN"<?php if(isset($_POST['country']) && $_POST['country'] == 'IN') { echo ' selected'; } ?>>India</option>
					<option value="ID"<?php if(isset($_POST['country']) && $_POST['country'] == 'ID') { echo ' selected'; } ?>>Indonesia</option>
					<option value="IQ"<?php if(isset($_POST['country']) && $_POST['country'] == 'IQ') { echo ' selected'; } ?>>Iraq</option>
					<option value="IR"<?php if(isset($_POST['country']) && $_POST['country'] == 'IR') { echo ' selected'; } ?>>Iran</option>
					<option value="IE"<?php if(isset($_POST['country']) && $_POST['country'] == 'IE') { echo ' selected'; } ?>>Ireland</option>
					<option value="IL"<?php if(isset($_POST['country']) && $_POST['country'] == 'IL') { echo ' selected'; } ?>>Israel</option>
					<option value="IT"<?php if(isset($_POST['country']) && $_POST['country'] == 'IT') { echo ' selected'; } ?>>Italy</option>
					<option value="JM"<?php if(isset($_POST['country']) && $_POST['country'] == 'JM') { echo ' selected'; } ?>>Jamaica</option>
					<option value="JP"<?php if(isset($_POST['country']) && $_POST['country'] == 'JP') { echo ' selected'; } ?>>Japan</option>
					<option value="JO"<?php if(isset($_POST['country']) && $_POST['country'] == 'JO') { echo ' selected'; } ?>>Jordan</option>
					<option value="KZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'KZ') { echo ' selected'; } ?>>Kazakhstan</option>
					<option value="KE"<?php if(isset($_POST['country']) && $_POST['country'] == 'KE') { echo ' selected'; } ?>>Kenya</option>
					<option value="KI"<?php if(isset($_POST['country']) && $_POST['country'] == 'KI') { echo ' selected'; } ?>>Kiribati</option>
					<option value="XK"<?php if(isset($_POST['country']) && $_POST['country'] == 'XK') { echo ' selected'; } ?>>Kosovo</option>
					<option value="KW"<?php if(isset($_POST['country']) && $_POST['country'] == 'KW') { echo ' selected'; } ?>>Kuwait</option>
					<option value="KG"<?php if(isset($_POST['country']) && $_POST['country'] == 'KG') { echo ' selected'; } ?>>Kyrgyzstan</option>
					<option value="LA"<?php if(isset($_POST['country']) && $_POST['country'] == 'LA') { echo ' selected'; } ?>>Laos</option>
					<option value="LV"<?php if(isset($_POST['country']) && $_POST['country'] == 'LV') { echo ' selected'; } ?>>Latvia</option>
					<option value="LB"<?php if(isset($_POST['country']) && $_POST['country'] == 'LB') { echo ' selected'; } ?>>Lebanon</option>
					<option value="LS"<?php if(isset($_POST['country']) && $_POST['country'] == 'LS') { echo ' selected'; } ?>>Lesotho</option>
					<option value="LR"<?php if(isset($_POST['country']) && $_POST['country'] == 'LR') { echo ' selected'; } ?>>Liberia</option>
					<option value="LY"<?php if(isset($_POST['country']) && $_POST['country'] == 'LY') { echo ' selected'; } ?>>Libya</option>
					<option value="LI"<?php if(isset($_POST['country']) && $_POST['country'] == 'LI') { echo ' selected'; } ?>>Liechtenstein</option>
					<option value="LT"<?php if(isset($_POST['country']) && $_POST['country'] == 'LT') { echo ' selected'; } ?>>Lithuania</option>
					<option value="LU"<?php if(isset($_POST['country']) && $_POST['country'] == 'LU') { echo ' selected'; } ?>>Luxembourg</option>
					<option value="MO"<?php if(isset($_POST['country']) && $_POST['country'] == 'MO') { echo ' selected'; } ?>>Macao SAR</option>
					<option value="MG"<?php if(isset($_POST['country']) && $_POST['country'] == 'MG') { echo ' selected'; } ?>>Madagascar</option>
					<option value="MW"<?php if(isset($_POST['country']) && $_POST['country'] == 'MW') { echo ' selected'; } ?>>Malawi</option>
					<option value="MY"<?php if(isset($_POST['country']) && $_POST['country'] == 'MY') { echo ' selected'; } ?>>Malaysia</option>
					<option value="MV"<?php if(isset($_POST['country']) && $_POST['country'] == 'MV') { echo ' selected'; } ?>>Maldives</option>
					<option value="ML"<?php if(isset($_POST['country']) && $_POST['country'] == 'ML') { echo ' selected'; } ?>>Mali</option>
					<option value="MT"<?php if(isset($_POST['country']) && $_POST['country'] == 'MT') { echo ' selected'; } ?>>Malta</option>
					<option value="MH"<?php if(isset($_POST['country']) && $_POST['country'] == 'MH') { echo ' selected'; } ?>>Marshall Islands</option>
					<option value="MR"<?php if(isset($_POST['country']) && $_POST['country'] == 'MR') { echo ' selected'; } ?>>Mauritania</option>
					<option value="MU"<?php if(isset($_POST['country']) && $_POST['country'] == 'MU') { echo ' selected'; } ?>>Mauritius</option>
					<option value="MX"<?php if(isset($_POST['country']) && $_POST['country'] == 'MX') { echo ' selected'; } ?>>Mexico</option>
					<option value="FM"<?php if(isset($_POST['country']) && $_POST['country'] == 'FM') { echo ' selected'; } ?>>Micronesia</option>
					<option value="MD"<?php if(isset($_POST['country']) && $_POST['country'] == 'MD') { echo ' selected'; } ?>>Moldova</option>
					<option value="MC"<?php if(isset($_POST['country']) && $_POST['country'] == 'MC') { echo ' selected'; } ?>>Monaco</option>
					<option value="MN"<?php if(isset($_POST['country']) && $_POST['country'] == 'MN') { echo ' selected'; } ?>>Mongolia</option>
					<option value="ME"<?php if(isset($_POST['country']) && $_POST['country'] == 'ME') { echo ' selected'; } ?>>Montenegro</option>
					<option value="MA"<?php if(isset($_POST['country']) && $_POST['country'] == 'MA') { echo ' selected'; } ?>>Morocco</option>
					<option value="MZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'MZ') { echo ' selected'; } ?>>Mozambique</option>
					<option value="MM"<?php if(isset($_POST['country']) && $_POST['country'] == 'MM') { echo ' selected'; } ?>>Myanmar (formerly Burma)</option>
					<option value="NA"<?php if(isset($_POST['country']) && $_POST['country'] == 'NA') { echo ' selected'; } ?>>Namibia</option>
					<option value="NR"<?php if(isset($_POST['country']) && $_POST['country'] == 'NR') { echo ' selected'; } ?>>Nauru</option>
					<option value="NP"<?php if(isset($_POST['country']) && $_POST['country'] == 'NP') { echo ' selected'; } ?>>Nepal</option>
					<option value="NL"<?php if(isset($_POST['country']) && $_POST['country'] == 'NL') { echo ' selected'; } ?>>Netherlands</option>
					<option value="NZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'NZ') { echo ' selected'; } ?>>New Zealand</option>
					<option value="NI"<?php if(isset($_POST['country']) && $_POST['country'] == 'NI') { echo ' selected'; } ?>>Nicaragua</option>
					<option value="NE"<?php if(isset($_POST['country']) && $_POST['country'] == 'NE') { echo ' selected'; } ?>>Niger</option>
					<option value="NG"<?php if(isset($_POST['country']) && $_POST['country'] == 'NG') { echo ' selected'; } ?>>Nigeria</option>
					<option value="KP"<?php if(isset($_POST['country']) && $_POST['country'] == 'KP') { echo ' selected'; } ?>>North Korea</option>
					<option value="MK"<?php if(isset($_POST['country']) && $_POST['country'] == 'MK') { echo ' selected'; } ?>>North Macedonia</option>
					<option value="NO"<?php if(isset($_POST['country']) && $_POST['country'] == 'NO') { echo ' selected'; } ?>>Norway</option>
					<option value="OM"<?php if(isset($_POST['country']) && $_POST['country'] == 'OM') { echo ' selected'; } ?>>Oman</option>
					<option value="PK"<?php if(isset($_POST['country']) && $_POST['country'] == 'PK') { echo ' selected'; } ?>>Pakistan</option>
					<option value="PW"<?php if(isset($_POST['country']) && $_POST['country'] == 'PW') { echo ' selected'; } ?>>Palau</option>
					<option value="PS"<?php if(isset($_POST['country']) && $_POST['country'] == 'PS') { echo ' selected'; } ?>>Palestine State</option>
					<option value="PA"<?php if(isset($_POST['country']) && $_POST['country'] == 'PA') { echo ' selected'; } ?>>Panama</option>
					<option value="PG"<?php if(isset($_POST['country']) && $_POST['country'] == 'PG') { echo ' selected'; } ?>>Papua New Guinea</option>
					<option value="PY"<?php if(isset($_POST['country']) && $_POST['country'] == 'PY') { echo ' selected'; } ?>>Paraguay</option>
					<option value="PE"<?php if(isset($_POST['country']) && $_POST['country'] == 'PE') { echo ' selected'; } ?>>Peru</option>
					<option value="PH"<?php if(isset($_POST['country']) && $_POST['country'] == 'PH') { echo ' selected'; } ?>>Philippines</option>
					<option value="PL"<?php if(isset($_POST['country']) && $_POST['country'] == 'PL') { echo ' selected'; } ?>>Poland</option>
					<option value="PT"<?php if(isset($_POST['country']) && $_POST['country'] == 'PT') { echo ' selected'; } ?>>Portugal</option>
					<option value="QA"<?php if(isset($_POST['country']) && $_POST['country'] == 'QA') { echo ' selected'; } ?>>Qatar</option>
					<option value="RO"<?php if(isset($_POST['country']) && $_POST['country'] == 'RO') { echo ' selected'; } ?>>Romania</option>
					<option value="RU"<?php if(isset($_POST['country']) && $_POST['country'] == 'RU') { echo ' selected'; } ?>>Russia</option>
					<option value="RW"<?php if(isset($_POST['country']) && $_POST['country'] == 'RW') { echo ' selected'; } ?>>Rwanda</option>
					<option value="KN"<?php if(isset($_POST['country']) && $_POST['country'] == 'KN') { echo ' selected'; } ?>>Saint Kitts and Nevis</option>
					<option value="LC"<?php if(isset($_POST['country']) && $_POST['country'] == 'LC') { echo ' selected'; } ?>>Saint Lucia</option>
					<option value="VC"<?php if(isset($_POST['country']) && $_POST['country'] == 'VC') { echo ' selected'; } ?>>Saint Vincent and the Grenadines</option>
					<option value="WS"<?php if(isset($_POST['country']) && $_POST['country'] == 'WS') { echo ' selected'; } ?>>Samoa</option>
					<option value="SM"<?php if(isset($_POST['country']) && $_POST['country'] == 'SM') { echo ' selected'; } ?>>San Marino</option>
					<option value="ST"<?php if(isset($_POST['country']) && $_POST['country'] == 'ST') { echo ' selected'; } ?>>Sao Tome and Principe</option>
					<option value="SA"<?php if(isset($_POST['country']) && $_POST['country'] == 'SA') { echo ' selected'; } ?>>Saudi Arabia</option>
					<option value="SN"<?php if(isset($_POST['country']) && $_POST['country'] == 'SN') { echo ' selected'; } ?>>Senegal</option>
					<option value="RS"<?php if(isset($_POST['country']) && $_POST['country'] == 'RS') { echo ' selected'; } ?>>Serbia</option>
					<option value="SC"<?php if(isset($_POST['country']) && $_POST['country'] == 'SC') { echo ' selected'; } ?>>Seychelles</option>
					<option value="SL"<?php if(isset($_POST['country']) && $_POST['country'] == 'SL') { echo ' selected'; } ?>>Sierra Leone</option>
					<option value="SG"<?php if(isset($_POST['country']) && $_POST['country'] == 'SG') { echo ' selected'; } ?>>Singapore</option>
					<option value="SK"<?php if(isset($_POST['country']) && $_POST['country'] == 'SK') { echo ' selected'; } ?>>Slovakia</option>
					<option value="SI"<?php if(isset($_POST['country']) && $_POST['country'] == 'SI') { echo ' selected'; } ?>>Slovenia</option>
					<option value="SB"<?php if(isset($_POST['country']) && $_POST['country'] == 'SB') { echo ' selected'; } ?>>Solomon Islands</option>
					<option value="SO"<?php if(isset($_POST['country']) && $_POST['country'] == 'SO') { echo ' selected'; } ?>>Somalia</option>
					<option value="ZA"<?php if(isset($_POST['country']) && $_POST['country'] == 'ZA') { echo ' selected'; } ?>>South Africa</option>
					<option value="KR"<?php if(isset($_POST['country']) && $_POST['country'] == 'KR') { echo ' selected'; } ?>>South Korea</option>
					<option value="SS"<?php if(isset($_POST['country']) && $_POST['country'] == 'SS') { echo ' selected'; } ?>>South Sudan</option>
					<option value="ES"<?php if(isset($_POST['country']) && $_POST['country'] == 'ES') { echo ' selected'; } ?>>Spain</option>
					<option value="LK"<?php if(isset($_POST['country']) && $_POST['country'] == 'LK') { echo ' selected'; } ?>>Sri Lanka</option>
					<option value="SD"<?php if(isset($_POST['country']) && $_POST['country'] == 'SD') { echo ' selected'; } ?>>Sudan</option>
					<option value="SR"<?php if(isset($_POST['country']) && $_POST['country'] == 'SR') { echo ' selected'; } ?>>Suriname</option>
					<option value="SE"<?php if(isset($_POST['country']) && $_POST['country'] == 'SE') { echo ' selected'; } ?>>Sweden</option>
					<option value="CH"<?php if(isset($_POST['country']) && $_POST['country'] == 'CH') { echo ' selected'; } ?>>Switzerland</option>
					<option value="SY"<?php if(isset($_POST['country']) && $_POST['country'] == 'SY') { echo ' selected'; } ?>>Syria</option>
					<option value="TW"<?php if(isset($_POST['country']) && $_POST['country'] == 'TW') { echo ' selected'; } ?>>Taiwan</option>
					<option value="TJ"<?php if(isset($_POST['country']) && $_POST['country'] == 'TJ') { echo ' selected'; } ?>>Tajikistan</option>
					<option value="TZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'TZ') { echo ' selected'; } ?>>Tanzania</option>
					<option value="TH"<?php if(isset($_POST['country']) && $_POST['country'] == 'TH') { echo ' selected'; } ?>>Thailand</option>
					<option value="TL"<?php if(isset($_POST['country']) && $_POST['country'] == 'TL') { echo ' selected'; } ?>>Timor-Leste</option>
					<option value="TG"<?php if(isset($_POST['country']) && $_POST['country'] == 'TG') { echo ' selected'; } ?>>Togo</option>
					<option value="TO"<?php if(isset($_POST['country']) && $_POST['country'] == 'TO') { echo ' selected'; } ?>>Tonga</option>
					<option value="TT"<?php if(isset($_POST['country']) && $_POST['country'] == 'TT') { echo ' selected'; } ?>>Trinidad and Tobago</option>
					<option value="TN"<?php if(isset($_POST['country']) && $_POST['country'] == 'TN') { echo ' selected'; } ?>>Tunisia</option>
					<option value="TR"<?php if(isset($_POST['country']) && $_POST['country'] == 'TR') { echo ' selected'; } ?>>Turkey</option>
					<option value="TM"<?php if(isset($_POST['country']) && $_POST['country'] == 'TM') { echo ' selected'; } ?>>Turkmenistan</option>
					<option value="TV"<?php if(isset($_POST['country']) && $_POST['country'] == 'TV') { echo ' selected'; } ?>>Tuvalu</option>
					<option value="UG"<?php if(isset($_POST['country']) && $_POST['country'] == 'UG') { echo ' selected'; } ?>>Uganda</option>
					<option value="UA"<?php if(isset($_POST['country']) && $_POST['country'] == 'UA') { echo ' selected'; } ?>>Ukraine</option>
					<option value="AE"<?php if(isset($_POST['country']) && $_POST['country'] == 'AE') { echo ' selected'; } ?>>United Arab Emirates</option>
					<option value="GB"<?php if(isset($_POST['country']) && $_POST['country'] == 'GB') { echo ' selected'; } ?>>United Kingdom</option>
					<option value="US"<?php if(isset($_POST['country']) && $_POST['country'] == 'US') { echo ' selected'; } ?>>United States</option>
					<option value="UY"<?php if(isset($_POST['country']) && $_POST['country'] == 'UY') { echo ' selected'; } ?>>Uruguay</option>
					<option value="UZ"<?php if(isset($_POST['country']) && $_POST['country'] == 'UZ') { echo ' selected'; } ?>>Uzbekistan</option>
					<option value="VU"<?php if(isset($_POST['country']) && $_POST['country'] == 'VU') { echo ' selected'; } ?>>Vanuatu</option>
					<option value="VE"<?php if(isset($_POST['country']) && $_POST['country'] == 'VE') { echo ' selected'; } ?>>Venezuela</option>
					<option value="VN"<?php if(isset($_POST['country']) && $_POST['country'] == 'VN') { echo ' selected'; } ?>>Vietnam</option>
					<option value="YE"<?php if(isset($_POST['country']) && $_POST['country'] == 'YE') { echo ' selected'; } ?>>Yemen</option>
					<option value="ZM"<?php if(isset($_POST['country']) && $_POST['country'] == 'ZM') { echo ' selected'; } ?>>Zambia</option>
					<option value="ZW"<?php if(isset($_POST['country']) && $_POST['country'] == 'ZW') { echo ' selected'; } ?>>Zimbabwe</option>
				</select>
			</li>
			<li>
				<?php if(isset($errors['street_address'])) { echo $errors['street_address']; } else { echo '<span>Street Address</span>'; } ?>
				<input name="street_address" type="text" value="<?php if(isset($_POST['submit'])) { echo $street_address; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['city'])) { echo $errors['city']; } else { echo '<span>City</span>'; } ?>
				<input name="city" type="text" value="<?php if(isset($_POST['submit'])) { echo $city; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['state'])) { echo $errors['state']; } else { echo '<span>State / Province / Region</span>'; } ?>
				<input name="state" type="text" value="<?php if(isset($_POST['submit'])) { echo $state; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['postal_code'])) { echo $errors['postal_code']; } else { echo '<span>Postal Code</span>'; } ?>
				<input name="postal_code" type="text" value="<?php if(isset($_POST['submit'])) { echo $postal_code; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['phone_number'])) { echo $errors['phone_number']; } else { echo '<span>Phone Number</span>'; } ?>
				<input name="phone_number" type="text" value="<?php if(isset($_POST['submit'])) { echo $phone_number; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['display_contact_inforamtion'])) { echo $errors['display_contact_inforamtion']; } else { echo '<span>Display This Contact Information on the Website</span>'; } ?>
				<select name="display_contact_inforamtion" class="display_contact_inforamtion">
					<option value="No"<?php if(isset($_POST['display_contact_inforamtion']) && $_POST['display_contact_inforamtion'] == 'No') { echo ' selected'; } ?>>No</option>
					<option value="Yes"<?php if(isset($_POST['display_contact_inforamtion']) && $_POST['display_contact_inforamtion'] == 'Yes') { echo ' selected'; } ?>>Yes</option>
				</select>
				<div class="note-small-font"><strong>Note:</strong> First and Last Name will not display on the website.</div>
			</li>
		</ul>
		<ul class="three-column">
			<li class="full-row">
				<div class="headline">ADMIN LOGIN CREDENTIALS</div>
			</li>
			<li>
				<?php if(isset($errors['username'])) { echo $errors['username']; } else { echo '<span>Admin Username</span>'; } ?>
				<input name="username" type="text" value="<?php if(isset($_POST['submit'])) { echo $username; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['password'])) { echo $errors['password']; } else { echo '<span>Admin Password</span>'; } ?>
				<input name="password" type="password" value="" />
			</li>
			<li>
				<?php if(isset($errors['confirm_password'])) { echo $errors['confirm_password']; } else { echo '<span>Confirm Admin Password</span>'; } ?>
				<input name="confirm_password" type="password" value="" />
			</li>
			<li class="full-row">
				<div class="password-requirements-wrap">
					<span>Admin Password Requirements</span>
					<ul class="password-requirements">
						<li>At least 10 characters long</li>
						<li>At least one special character (e.g., `~!@#$%^&amp;*()-_+=[{]}\|;:'",.?/)</li>
						<li>At least one letter (anywhere from A to Z)</li>
						<li>At least one number (from 0 to 9)</li>
					</ul>
				</div>
			</li>
			<li class="full-row">
				<button id="install-button" name="submit" type="submit" class="button">INSTALL RATALS</button>
				<div class="be-patient"><strong>Note:</strong> After clicking "INSTALL RATALS," it takes approximately 20 to 30 seconds for the software to create all database tables and set up the website theme. Please be patient.</div>
			</li>
		</ul>
		</form>
	</div>
</div>
<div class="footer">
	<div class="footer-wrap">
		<div class="text"><a href="https://www.ratals.com/terms-of-use/" target="_blank">Terms of Use</a> | <a href="https://www.ratals.com/privacy-policy/" target="_blank">Privacy Policy</a></div>
		<div class="powered-by"><span>Copyright &copy; 2025-2025 - Powered By</span> <a href="https://www.ratals.com/" target="_blank">Ratals Inc.</a> <span>- All Rights Reserved.</span></div>
	</div>
</div>
</body>
</html>