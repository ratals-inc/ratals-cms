<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/add-a-site.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/add-a-site.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'add_a_site')
	{
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
		
		$all_sites_in_account = $results->getSelectMultipleRecordsKeyNameOneColumn(__LINE__, __FILE__, '*', 'sites', '', [], 'id', 'domain');
		
		$errors = array();
		if(isset($_POST['submit']))
		{	
			$https_in_url = trim($_POST['https_in_url'] ?? '');
			if(empty($https_in_url))
			{
				$errors['https_in_url'] = '<div class="error">Select if you want "https" or "http" in your doamin</div>';
			}
			
			$www_in_url = trim($_POST['www_in_url'] ?? '');
			if(empty($www_in_url))
			{
				$errors['www_in_url'] = '<div class="error">Select if you want "www." or "no www." in your domain</div>';
			}
			
			$tld = trim($_POST['tld'] ?? '');
			if(empty($tld))
			{
				$errors['tld'] = '<div class="error">Enter your domain name (e.g. ratals.com)</div>';
			}
			else
			{
				$tld = rtrim($tld, '/');
				if(preg_match('#^https?://#i', $tld))
				{
					$errors['tld'] = '<div class="error">Remove http/https and use dropdown</div>';
				}
				elseif(preg_match('#^www\.#i', $tld))
				{
					$errors['tld'] = '<div class="error">Remove www. and use dropdown</div>';
				}
				elseif(!preg_match('/^[a-zA-Z0-9.-]+$/', $tld))
				{
					$errors['tld'] = '<div class="error">Invalid domain characters</div>';
				}
				elseif(in_array($tld, $all_sites_in_account))
				{
					$errors['tld'] = '<div class="error">Domain is already added.</div>';
				}
			}
			
			$site_name = trim($_POST['site_name'] ?? '');
			if(empty($site_name))
			{
				$errors['site_name'] = '<span class="error">Site Name</span>';
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
			
			$country = trim($_POST['country'] ?? '');
			if(empty($country))
			{
				$errors['country'] = '<span class="error">Country</span>';
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
			
			if(count($errors) == 0) 
			{
				$admin_directory = $_SESSION['admin_directory'];
				$first_last_name = $_SESSION['user_first_last_name'];
				$email = $server_email;
				$tld = trim($tld, '/');
				$redirect_to_opposite_url = 'Yes';
				$auto_generate_canonical_url = 'Yes';
				$url_extension = '/';
				$add_site_name_to_title_tag = 'Yes';
				$separate_site_name_in_title_tag_with = '-';
				$pagination = 30;
				
				$install_template = 'Yes';
				
				//We have to unset count ids when adding sites in case an account upgrade happens between adding site.
				//The upgrade will add rows to the db and cause the last id count to be wrong for addig the next site. These session variables are set in template-files.php.
				unset($_SESSION['last_menu_id']);
				unset($_SESSION['last_slider_id']);
				unset($_SESSION['last_custom_field_id']);
				unset($_SESSION['last_pages_id']);
				unset($_SESSION['last_url_id']);
				$_SESSION['install_ids'] = array();
				
				//Set default media ids
				$default_media_id = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'media', 'WHERE `original_media` = ? AND `media_url` IN (?,?,?,?,?,?,?,?,?,?,?,?,?)', ['Yes', 'template-screenshot-default.png', 'photo-coming-soon-250-250.gif', 'image-coming-soon-650-650.gif', 'image-coming-soon-600-300.gif', 'image-coming-soon-375-375.gif', 'image-coming-soon-1500-300.gif', 'image-coming-soon-1050-500.gif', 'image-coming-soon-1025-300.gif', 'video-icon.gif', 'file-icon.gif', 'favicon-16x16.png', 'favicon-32x32.png', 'favicon-180x180.png'], 'media_url');
				
				$_SESSION['template-screenshot-default.png'] = $default_media_id['template-screenshot-default.png']['id'] ?? 0;
				$_SESSION['photo-coming-soon-250-250.gif'] = $default_media_id['photo-coming-soon-250-250.gif']['id'] ?? 0;
				$_SESSION['image-coming-soon-650-650.gif'] = $default_media_id['image-coming-soon-650-650.gif']['id'] ?? 0;
				$_SESSION['image-coming-soon-600-300.gif'] = $default_media_id['image-coming-soon-600-300.gif']['id'] ?? 0;
				$_SESSION['image-coming-soon-375-375.gif'] = $default_media_id['image-coming-soon-375-375.gif']['id'] ?? 0;
				$_SESSION['image-coming-soon-1500-300.gif'] = $default_media_id['image-coming-soon-1500-300.gif']['id'] ?? 0;
				$_SESSION['image-coming-soon-1050-500.gif'] = $default_media_id['image-coming-soon-1050-500.gif']['id'] ?? 0;
				$_SESSION['image-coming-soon-1025-300.gif'] = $default_media_id['image-coming-soon-1025-300.gif']['id'] ?? 0;
				$_SESSION['video-icon.gif'] = $default_media_id['video-icon.gif']['id'] ?? 0;
				$_SESSION['file-icon.gif'] = $default_media_id['file-icon.gif']['id'] ?? 0;
				$_SESSION['favicon-16x16.png'] = $default_media_id['favicon-16x16.png']['id'] ?? 0;
				$_SESSION['favicon-32x32.png'] = $default_media_id['favicon-32x32.png']['id'] ?? 0;
				$_SESSION['favicon-180x180.png'] = $default_media_id['favicon-180x180.png']['id'] ?? 0;
				
				//Get auto increment id columns to install new site on next id.
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/counters.php');
				
				//Create Account Template Folders on Server
				if(!is_dir(INSTALLATION_ROOT."/sites/".$site_id."/templates/default")) { mkdir(INSTALLATION_ROOT."/sites/".$site_id."/templates/default", 0755, true); }
				if(!is_dir(INSTALLATION_ROOT."/sites/media")) { mkdir(INSTALLATION_ROOT."/sites/media", 0755, true); }
				if(!is_dir(INSTALLATION_ROOT."/sites/media/images")) { mkdir(INSTALLATION_ROOT."/sites/media/images", 0755, true); }
				if(!is_dir(INSTALLATION_ROOT."/sites/media/videos")) { mkdir(INSTALLATION_ROOT."/sites/media/videos", 0755, true); }
				if(!is_dir(INSTALLATION_ROOT."/sites/media/files")) { mkdir(INSTALLATION_ROOT."/sites/media/files", 0755, true); }
				
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/template.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/template-files.php'); //template-files.php must run first as template-files.php sets URL IDs.
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/sites.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/assignments-sub-items.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/blocking-spam.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/currency.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/custom-fields-global.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/custom-fields.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/menus.php'); //menus.php must run before menus-items.php
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/menus-items.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/page-groups.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/urls.php'); //urls.php must run before pages.php as the pages need the URL IDs.
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/pages.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/search-engines.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/site-contact-info.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/site-security.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/site-settings.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/sliders-items.php');
				require_once(INSTALLATION_ROOT.'/admin/cms/installer/data/sliders.php');
				
				if($commerce_installed)
				{
					require_once(INSTALLATION_ROOT.'/admin/commerce/installer/data/template-files.php'); //template-files.php must run first as template-files.php sets URL IDs.
					require_once(INSTALLATION_ROOT.'/admin/commerce/installer/data/menus.php'); //menus.php must run before menus-items.php
					require_once(INSTALLATION_ROOT.'/admin/commerce/installer/data/menus-items.php');
					require_once(INSTALLATION_ROOT.'/admin/commerce/installer/data/urls.php'); //urls.php must run before pages.php as the pages need the URL IDs.
					require_once(INSTALLATION_ROOT.'/admin/commerce/installer/data/pages.php');
					require_once(INSTALLATION_ROOT.'/admin/commerce/installer/data/cart-recovery-emails.php');
					require_once(INSTALLATION_ROOT.'/admin/commerce/installer/data/review-request-emails.php');
					//Use updater files to make sure we set default values at the commerce level for the new site added.
					require_once(INSTALLATION_ROOT.'/admin/cms/includes/notices/updates/blocking-spam.php');
					require_once(INSTALLATION_ROOT.'/admin/cms/includes/notices/updates/site-security.php');
					require_once(INSTALLATION_ROOT.'/admin/cms/includes/notices/updates/site-settings.php');
				}
				
				if(file_exists(rtrim(INSTALLATION_ROOT, '/').'/admin/cms/api/connect.php'))
				{
					require_once(rtrim(INSTALLATION_ROOT, '/').'/admin/cms/api/connect.php');
				}
				
				header("Location: /".$_SESSION['admin_directory']."/add-a-site/?created=success");
				exit();
			}
		}
	}
}