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
require_once($_SERVER['DOCUMENT_ROOT'].'/core/server-software.php');

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
$subdomain = $tld;
$site_name_array = explode('.', $tld);
if(count($site_name_array) == 2)
{
	$site_name = ucfirst($site_name_array[0]);
	$subdomain = $site_name_array[0].'.'.$tld;
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
	
	if(!empty($database_name) && !empty($database_username) && !empty($database_password) && !isset($errors['database_name']) && !isset($errors['database_username']) && !isset($errors['database_password']))
	{
		try
		{
			$dsn = 'mysql:host=localhost; dbname='.$database_name;
			$pdo = new PDO($dsn, $database_username, $database_password);
		} 
		catch(Exception $e)
		{
			$errors['database_connection'] = '<span class="center error">Couldn\'t connect to database with provide credentials.</span>';
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
		elseif(preg_match('#^www\.#i', $tld))
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
		$errors['server_smpt_url'] = '<span class="error">Server SMTP Email URL</span>';
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
	
	//Make sure admin login URL is not easy to find for for brute force login attackes and make sure new admin url directory is avaible to use. 
	$admin_directory = trim($_POST['admin_directory'] ?? '');
	if(empty($admin_directory))
	{
		$errors['admin_directory'] = '<span class="error">Admin Login URL</span>';
	}
	elseif(strtolower($admin_directory) == 'i-love-ratals' || strtolower($admin_directory) == 'admin' || strtolower($admin_directory) == 'administrator' || strtolower($admin_directory) == 'root' || strtolower($admin_directory) == 'login' || strtolower($admin_directory) == 'backend' || strtolower($admin_directory) == strtolower($first_name) || strtolower($admin_directory) == strtolower($last_name) || strtolower($admin_directory) == strtolower($site_name))
	{
		$errors['admin_directory'] = '<span class="error">Enter A Stronger Admin Login URL</span>';
	}
	elseif(!preg_match('/^[a-z0-9-]+$/', $admin_directory))
	{
		$errors['admin_directory'] = '<span class="error">Admin Login URL Can Only Contain Lowercase a-z, 0-9, and -</span>';
	}
	elseif(is_dir($_SERVER['DOCUMENT_ROOT']."/".$admin_directory))
	{
		$errors['admin_directory'] = '<span class="error">Admin Login URL Is Not Available</span>';
	}
	
	$username = trim($_POST['username'] ?? '');
	if(empty($username))
	{
		$errors['username'] = '<span class="error">Username</span>';
	}
	elseif(strtolower($username) == 'admin' || strtolower($username) == 'administrator' || strtolower($username) == 'root' || strtolower($username) == strtolower($first_name) || strtolower($username) == strtolower($last_name) || strtolower($username) == strtolower($site_name) || strtolower($username) == strtolower($admin_directory))
	{
		$errors['username'] = '<span class="error">Enter A Stronger Username</span>';
	}
	
	$password = trim($_POST['password'] ?? '');
	if(empty($password))
	{
		$errors['password'] = '<span class="error">Password</span>';
	}
	
	$confirm_password = trim($_POST['confirm_password'] ?? '');
	if(empty($confirm_password))
	{
		$errors['confirm_password'] = '<span class="error">Confirm Password</span>';
	}
	
	if(!empty($password) && !empty($confirm_password) && $password != $confirm_password)
	{
		$errors['password_confirm_password'] = '<span class="center error">Password & Confirm Password didn\'t match.</span>';
	}
	
	if(!empty($password) && !empty($confirm_password) && $password == $confirm_password)
	{
		//Initiated in /config.php. This validates passwords when accounts are created to make sure they have a character, digit and special character in them.
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/password-validation.php');
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
		//Get the .htaccess file path - for Apache and Lightspeed servers
		$htaccess_path = $_SERVER['DOCUMENT_ROOT'].'/.htaccess';
		if(file_exists($htaccess_path))
		{
			//Read the /.htaccess content.
			$htaccess_contents = file_get_contents($htaccess_path);
			//Replace YOUR_ADMIN_URL_PATH with virtual admin path
			$htaccess_contents = str_replace('YOUR_ADMIN_URL_PATH', $admin_directory, $htaccess_contents);
			//Replace auto_prepend_file for frontend with absolute path
			$htaccess_contents = preg_replace('~php_value\s+auto_prepend_file\s+.*~i', 'php_value auto_prepend_file "'.rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/core/session-check-frontend.php"', $htaccess_contents);
			//Write back the updated /.htaccess content with new admin URL.
			file_put_contents($htaccess_path, $htaccess_contents);
		}
		
		//Get the /admin/.htaccess file path - for Apache and Lightspeed servers
		$admin_htaccess_path = $_SERVER['DOCUMENT_ROOT'].'/admin/.htaccess';
		if(file_exists($admin_htaccess_path))
		{
			//Read the /admin/.htacces content.
			$admin_htaccess_contents = file_get_contents($admin_htaccess_path);
			//Replace YOUR_ADMIN_URL_PATH with virtual admin path
			$admin_htaccess_contents = str_replace('YOUR_ADMIN_URL_PATH', $admin_directory, $admin_htaccess_contents);
			//Replace auto_prepend_file for admin with absolute path
			$admin_htaccess_contents = preg_replace('~php_value\s+auto_prepend_file\s+.*~i', 'php_value auto_prepend_file "'.rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/core/session-check-admin.php"', $admin_htaccess_contents);
			//Write back the updated /admin/.htacces content with new admin URL.
			file_put_contents($admin_htaccess_path, $admin_htaccess_contents);
		}
		
		//Update /.user.ini (frontend) - for Nginx / PHP-FPM environments
		$user_ini_path = $_SERVER['DOCUMENT_ROOT'].'/.user.ini';
		if(file_exists($user_ini_path))
		{
			$doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
			$frontend_path = $doc_root.'/core/session-check-frontend.php';
			$user_ini_contents = file_get_contents($user_ini_path);
			//Replace ONLY the auto_prepend_file line
			$user_ini_contents = preg_replace('/;auto_prepend_file\s*=\s*".*?"/', 'auto_prepend_file = "'.$frontend_path.'"', $user_ini_contents);
			file_put_contents($user_ini_path, $user_ini_contents, LOCK_EX);
		}
		
		//Update /admin/.user.ini (admin) - for Nginx / PHP-FPM environments
		$admin_user_ini_path = $_SERVER['DOCUMENT_ROOT'].'/admin/.user.ini';
		if(file_exists($admin_user_ini_path))
		{
			$doc_root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
			$admin_path = $doc_root.'/core/session-check-admin.php';
			$admin_user_ini_contents = file_get_contents($admin_user_ini_path);
			//Replace ONLY the auto_prepend_file line
			$admin_user_ini_contents = preg_replace('/;auto_prepend_file\s*=\s*".*?"/', 'auto_prepend_file = "'.$admin_path.'"', $admin_user_ini_contents);
			file_put_contents($admin_user_ini_path, $admin_user_ini_contents, LOCK_EX);
		}
		
		$email = $server_email;
		$subdomain = $subdomain;
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
		
		//Set database connection credentials
		$database_connection_file = file_get_contents($_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/data/credentials.php");
		
		$old_datbase_name = '[DATABASE_NAME]'; 
		$new_datbase_name = $database_name;
		$database_connection_file = str_replace($old_datbase_name, $new_datbase_name, $database_connection_file);
		
		$old_datbase_username = '[DATABASE_USERNAME]'; 
		$new_datbase_username = $database_username;
		$database_connection_file = str_replace($old_datbase_username, $new_datbase_username, $database_connection_file);
		
		$old_datbase_password = '[DATABASE_PASSWORD]'; 
		$new_datbase_password = $database_password;
		$database_connection_file = str_replace($old_datbase_password, $new_datbase_password, $database_connection_file);
		
		clearstatcache(); //Clear file cache to make sure its writting to the real file and not buffer version/cache.
		
		$set_database_connection_file = fopen($_SERVER['DOCUMENT_ROOT']."/core/database/DbCredentials.php", "w");
		fwrite($set_database_connection_file, $database_connection_file);
		fclose($set_database_connection_file);
		
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
		$config_file_path = $_SERVER['DOCUMENT_ROOT']."/core/config.php";
		$config_contents = file_get_contents($config_file_path);
		$config_contents = str_replace('[SET_HASH_SECRET]', $hash_secret, $config_contents);
		file_put_contents($config_file_path, $config_contents);
		
		//Get all classes and connect to database.
		require_once($_SERVER['DOCUMENT_ROOT'].'/core/database/index.php');
		
		//Get function that creates queries so databases can be installed.
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/build-database-table-create-query.php');
		
		//Create database tables.
		$existing_database_tables = array();
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/database/tables/index.php');
		
		//Get auto increment id columns to install new site on next id.
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/counters.php');
		
		//Create Account Template Folders on Server
		if(!is_dir($_SERVER['DOCUMENT_ROOT']."/sites/".$site_id."/templates/default")) { mkdir($_SERVER['DOCUMENT_ROOT']."/sites/".$site_id."/templates/default", 0755, true); }
		if(!is_dir($_SERVER['DOCUMENT_ROOT']."/sites/media")) { mkdir($_SERVER['DOCUMENT_ROOT']."/sites/media", 0755, true); }
		if(!is_dir($_SERVER['DOCUMENT_ROOT']."/sites/media/images")) { mkdir($_SERVER['DOCUMENT_ROOT']."/sites/media/images", 0755, true); }
		if(!is_dir($_SERVER['DOCUMENT_ROOT']."/sites/media/videos")) { mkdir($_SERVER['DOCUMENT_ROOT']."/sites/media/videos", 0755, true); }
		if(!is_dir($_SERVER['DOCUMENT_ROOT']."/sites/media/files")) { mkdir($_SERVER['DOCUMENT_ROOT']."/sites/media/files", 0755, true); }
		
		//Install Template Files
		$install_template = 'Yes';
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/template.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/template-files.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/media.php');
		
		//Install new site database row.
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/sites.php');
		
		if($site_id == 1)
		{
			//These records are only needed once within an account.
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/database/columns/index.php'); //Must stay before admin-fields-lists.php as admin-fields-lists.php needs the state row id.
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/admin-fields-lists.php'); //Must stay before admin-fields-values.php as admin-fields-values.php is updated with admin-fields-lists.php row ids.
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/admin-fields-values.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/admin-field-sections.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/admin-pages.php'); //Must stay before admin-menus-items.php as admin-menus-items.php is updated with admin-pages.php row ids for menu links.
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/admin-menus.php'); //Must run before admin-menus-items.php so we can get the admin_menus ids.
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/admin-menus-items.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/database-column-ids.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/form-fields.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/form-values.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/forms.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/license.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/notices.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/users.php');
			require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/assigned-fields.php'); //Must run after admin_fields/columns, admin_pages, and users as we assign fields from these tables.
		}
		
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/assignments-sub-items.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/blocking-spam.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/currency.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/custom-fields-global.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/custom-fields.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/menus-items.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/menus.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/page-groups.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/pages.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/search-engines.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/site-contact-info.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/site-security.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/site-settings.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/sliders-items.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/sliders.php');
		require_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/data/urls.php');
		
		//Pause for 10 seconds to ensure the new admin URL is updated in the .htaccess file and the cache is cleared, allowing it to load properly.
		sleep(10);
		
		header("Location: /".$admin_directory."/?signup=success");
		exit;
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
body { font-family: sans-serif, FontAwesome; margin: 0px; padding: 0px; font-size: 16px; }
body, div, input, select { box-sizing: border-box; }
input, select { background-color: #fff; padding: 8px; height: 37px; border: 1px solid #dedede; width: 100%; border-radius: 5px; font-size: 15px;  }
.body-pending-ajax { margin: 0; height: 100%; overflow: hidden; }
.pending-ajax { background-color: #000000ad; top: 0; right: 0; bottom: 0; left: 0; position: fixed; text-align: center; z-index: 9999; }
.pending-ajax-outer-container { display: table; width: 100%; height: 100%; }
.pending-ajax-inner-container { display: table-cell; color: #000; vertical-align: middle; }
.pending-ajax-inner-container span { background-color: #f1f1f1; padding: 10px 20px; border-radius: 25px; display: inline-block; }
h1 { font-size: 40px; text-align: center; font-weight: 700; margin: 0px; padding-bottom: 12px; }
h2 { font-size: 16px; text-align: center; font-weight: 400; margin: 0px 0px 20px 0px; padding: 0px; }
a { color: #589fc3; }
.box-wrapper { margin: 10px; }
.box-wrapper span { display: block; padding-bottom: 4px; }
.box-wrapper .box { max-width: 1200px; margin: 30px auto 50px auto; box-shadow: 0 20px 50px 0 rgba(0,0,0,0.2); padding: 25px; }
.box-wrapper .box ul.two-column { margin: 0px; padding: 0px; --n: 2; display: grid; grid-template-columns: repeat(auto-fill, minmax(max(300px,(100% - (var(--n) - 1)*20px)/var(--n)), 1fr)); gap: 20px; width: calc(100% - 3px); }
.box-wrapper .box ul.three-column { margin: 0px; padding: 0px; --n: 3; display: grid; grid-template-columns: repeat(auto-fill, minmax(max(180px,(100% - (var(--n) - 1)*20px)/var(--n)), 1fr)); gap: 20px; width: calc(100% - 3px); }
.box-wrapper .box ul.five-column { margin: 0px; padding: 0px; --n: 5; display: grid; grid-template-columns: repeat(auto-fill, minmax(max(180px,(100% - (var(--n) - 1)*20px)/var(--n)), 1fr)); gap: 20px; width: calc(100% - 3px); }
.box-wrapper .box ul li { list-style: none; margin: 0px; }
.box-wrapper .box ul li.full-row { grid-column: 1 / -1; }
.box-wrapper .headline { font-size: 20px; text-align: center; padding: 10px; background: #f1f1f1; margin-top: 15px; }
.box-wrapper .http-s { width: 82px; vertical-align: top; border-top-right-radius: 0px; border-bottom-right-radius: 0px; border-right: 0px; }
.box-wrapper .www { width: 82px; vertical-align: top; border-radius: 0px; border-right: 1px dashed #dedede; border-left: 1px dashed #dedede; }
.box-wrapper .tld { width: calc(100% - 165px); vertical-align: top; border-left: 0px; border-top-left-radius: 0px; border-bottom-left-radius: 0px; }
.box-wrapper button { font-size: 18px; padding: 10px; display: inline-block; width: 100%; border: 0px; background-color: #195c95; color: #fff; cursor: pointer; border-radius: 5px; }
.box-wrapper .small-font { font-size: 14px; line-height: 20px; }
.box-wrapper .note-small-font { font-size: 11px; margin-top: 3px; color: #7c7c7c; }
.box-wrapper .currency_format { font-weight: 600; }
.box-wrapper .currency_format, .box-wrapper .currency_format span { display: inline; }
.box-wrapper .email_connector { margin: 5px 0px; }
.box-wrapper .email_connector label { cursor: pointer; }
.box-wrapper .email_connector input { height: 18px; width: 18px; vertical-align: sub; margin: 0px 2px 0px 0px; cursor: pointer; }
.box-wrapper .be-patient { font-size: 12px; line-height: 18px; text-align: center; color: #7c7c7c; margin-top: 10px; }
.box-wrapper .password-requirements-wrap { background-color: #e7e7e7; padding: 10px; border-radius: 5px; font-size: 14px; }
.box-wrapper .box ul li .password-requirements { margin: 0; padding: 8px 8px 0px 25px; }
.box-wrapper .box ul li .password-requirements li { list-style: initial; padding-bottom: 2px;}
.box-wrapper .center { text-align: center; }
.box-wrapper .error { color: #ff0000; }
.footer { margin-bottom: 30px; text-align: center; font-size: 12px; }
.footer .footer-wrap { margin: 0px auto; max-width: 800px; }
.footer .footer-wrap .text { margin-bottom: 8px; padding-bottom: 8px; border-bottom: 1px solid #e3e3e3; border-left: 100px solid transparent; border-right: 100px solid transparent; }
.footer .footer-wrap .powered-by span { color: #757575 }
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
			$installButton.text("Installing Ratals...");
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
	<h1>Install Ratals</h1>
    <?php if(!empty($nginx_warning)) { echo $nginx_warning; } ?>
	<h2>Need Help? Watch our Installation Guide videos <a href="https://www.ratals.com/tutorials/installation/ratals-installation-guide/" target="_blank">here</a>.</h2>
		<?php if(!empty($errors)) { echo '<span class="center error">Oh Snap! Something isn\'t right.</span>'; } ?>
		<?php if(!empty($errors['database_connection'])) { echo $errors['database_connection']; } ?>
		<?php if(isset($errors['password_confirm_password'])) { echo $errors['password_confirm_password']; } ?>
		
		<form action="" method="POST">
		<ul class="three-column">
			<li class="full-row">
				<div class="headline">DATABASE CONNECTION</div>
			</li>
			<li>
				<?php if(isset($errors['database_name'])) { echo $errors['database_name']; } else { echo '<span>Database Name</span>'; } ?>
				<input name="database_name" type="text" value="<?php if(isset($_POST['submit'])) { echo $database_name; } ?>" />
				<div class="note-small-font"><?php if(isset($errors['database_name_quote'])) { echo $errors['database_name_quote']; } else { echo 'Cannot contain single quotes.'; } ?></div>
			</li>
			<li>
				<?php if(isset($errors['database_username'])) { echo $errors['database_username']; } else { echo '<span>Database Username</span>'; } ?>
				<input name="database_username" type="text" value="<?php if(isset($_POST['submit'])) { echo $database_username; } ?>" />
				<div class="note-small-font"><?php if(isset($errors['database_username_quote'])) { echo $errors['database_username_quote']; } else { echo 'Cannot contain single quotes.'; } ?></div>
			</li>
			<li>
				<?php if(isset($errors['database_password'])) { echo $errors['database_password']; } else { echo '<span>Database Password</span>'; } ?>
				<input name="database_password" type="password" value="" />
				<div class="note-small-font"><?php if(isset($errors['database_password_quote'])) { echo $errors['database_password_quote']; } else { echo 'Cannot contain single quotes.'; } ?></div>
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
				<?php if(isset($errors['admin_directory'])) { echo $errors['admin_directory']; } else { echo '<span>Admin Login URL</span>'; } ?>
				<input name="admin_directory" placeholder="i.e: i-love-ratals" type="text" value="<?php if(isset($_POST['submit'])) { echo $admin_directory; } ?>" />
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
			<li class="full-row small-font">
				Please enter a default server email address associated with <?php echo $tld; ?>. This email will be used for sending order confirmations, password recovery, security alerts, and more.
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
				<?php if(isset($errors['server_smpt_url'])) { echo $errors['server_smpt_url']; } else { echo '<span>Server SMTP Email URL</span>'; } ?>
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
				<div class="headline">CREATE USER</div>
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
				<div class="headline">CREATE LOGIN CREDENTIALS</div>
			</li>
			<li>
				<?php if(isset($errors['username'])) { echo $errors['username']; } else { echo '<span>Username</span>'; } ?>
				<input name="username" type="text" value="<?php if(isset($_POST['submit'])) { echo $username; } ?>" />
			</li>
			<li>
				<?php if(isset($errors['password'])) { echo $errors['password']; } else { echo '<span>Password</span>'; } ?>
				<input name="password" type="password" value="" />
			</li>
			<li>
				<?php if(isset($errors['confirm_password'])) { echo $errors['confirm_password']; } else { echo '<span>Confirm Password</span>'; } ?>
				<input name="confirm_password" type="password" value="" />
			</li>
			<li class="full-row">
				<div class="password-requirements-wrap">
					<span>Password Requirements</span>
					<ul class="password-requirements">
						<li>At least 10 characters long</li>
						<li>At least one special character (e.g., `~!@#$%^&amp;*()-_+=[{]}\|;:'",.?/)</li>
						<li>At least one letter (anywhere from A to Z)</li>
						<li>At least one number (from 0 to 9)</li>
					</ul>
				</div>
			</li>
			<li class="full-row">
				<button id="install-button" name="submit" type="submit" class="button">Install Ratals</button>
				<div class="be-patient"><strong>Note:</strong> After clicking "Install Ratals," it takes approximately 20 to 30 seconds for the software to create all database tables and set up the website theme. Please be patient.</div>
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