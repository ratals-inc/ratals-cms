<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/ddos-tracking.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/ddos-tracking.php');
}
else
{
	//Delete records that are more than 1 day old from ddos_tracking table. This is here to make sure the ddos_tracking table never becomes to large and starts slowing the site down.
	$results->getDeleteRecord(__LINE__, __FILE__, 'ddos_tracking', 'WHERE `site_id` = ? AND `created_date` < UTC_TIMESTAMP() - INTERVAL 1 Day', [$site_id]);
	
	if(!in_array($_SERVER['REMOTE_ADDR'], $allowed_ip_addresses) && $_SERVER['REQUEST_URI'] != '/favicon.ico' && $_SERVER['REQUEST_URI'] != '/favicon/' && strpos($_SERVER['REQUEST_URI'], 'includes/media-popup-ajax.php') === false)
	{
		//Insert new pageviews for DDOS tracking.
		$results->getInsertRecord(__LINE__, __FILE__, 'ddos_tracking', '`site_id`, `ip_address`, `emailed`, `created_date`', '?, ?, ?, UTC_TIMESTAMP()', [$site_id, $_SERVER['REMOTE_ADDR'], '']);
		
		//Check if visitor hits max pageviews within time period.
		if(!empty($max_pageviews_block) && is_numeric($max_pageviews_block) && !empty($time_period_block) && is_numeric($time_period_block))
		{
			//Get how many pageviews user has made.
			$site_security_record_count = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'ddos_tracking', 'WHERE `site_id` = ? AND `created_date` > UTC_TIMESTAMP() - INTERVAL ? MINUTE AND `ip_address` = ?', [$site_id, $time_period_block, $_SERVER['REMOTE_ADDR']]);
			
			//Check if IP has a record for being blocked. We flag a row with `emailed` = yes when blocking them. This means we email the admin that user was blocked.
			$site_security_record_emailed_count = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'ddos_tracking', 'WHERE `site_id` = ? AND `created_date` > UTC_TIMESTAMP() - INTERVAL ? MINUTE AND `ip_address` = ? AND `emailed` = ? LIMIT 1', [$site_id, $time_period_block, $_SERVER['REMOTE_ADDR'], 'Yes']);
			
			if($site_security_record_count >= $max_pageviews_block && !empty($auto_blocked_email_address)&& !empty($ddos_email_from) && !empty($ddos_email_server_url) && !empty($ddos_email_server_port) && empty($site_security_record_emailed_count))
			{
				if($auto_blocked_ip_email_me == 'Email Me and Block IP')
				{
					//Get log record and append IP Address being blocked to it.
					if(strpos($ddos_blocked_ips, $_SERVER['REMOTE_ADDR']) === false)
					{
						if(!empty($ddos_blocked_ips))
						{
							$ddos_blocked_ips_log = $_SERVER['REMOTE_ADDR'].', '.trim($ddos_blocked_ips, ',');
						}
						else
						{
							$ddos_blocked_ips_log = $_SERVER['REMOTE_ADDR'];
						}
						
						//Update IP Address to Automatically Block in Log. This is a log so admin user can see what was recently blocked. Column of ddos_blocked_ips does not block users. Its only a log.
						$results->getUpdateRecord(__LINE__, __FILE__, 'site_security', '`ddos_blocked_ips` = ?', 'WHERE `site_id` = ?', [$ddos_blocked_ips_log, $site_id]);
					}
					
					//Get blocked IP's and append IP Address being blocked to it.
					if(strpos($site_blocked_ips, $_SERVER['REMOTE_ADDR']) === false)
					{
						if(!empty($site_blocked_ips))
						{
							$ddos_blocked_ips_string = $_SERVER['REMOTE_ADDR'].', '.trim($site_blocked_ips, ',');
						}
						else
						{
							$ddos_blocked_ips_string = $_SERVER['REMOTE_ADDR'];
						}
						
						//Update IP Addresses to Automatically Block. Column of "site_blocked_ips" is the column that blocks visitors. Add visitor IP to block them when over limits.
						$results->getUpdateRecord(__LINE__, __FILE__, 'site_security', '`site_blocked_ips` = ?', 'WHERE `site_id` = ?', [$ddos_blocked_ips_string, $site_id]);
					}
				}
				
				//Get live template directory name.
				$email_template_record = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `status` = ? LIMIT 1', [$_SESSION['site_id'], 1]);
				
				//Get Email Template.
				$subject = '';
				$message = '';
				include 'sites/'.$_SESSION['site_id'].'/templates/'.$email_template_record['directory_folder_name'].'/email-template-possible-ddos-attack.php';
				
				if(isset($warp_with_email_template) && $warp_with_email_template == 'Yes')
				{
					//Get Email Template Frame.
					include 'sites/'.$_SESSION['site_id'].'/templates/'.$email_template_record['directory_folder_name'].'/email-template.php';
					
					$message = str_replace('[EMAIL_MESSAGE]', $message, $email_template);
				}
				
				//Send DDOS Attack Email.
				smtpSendEmail($auto_blocked_email_address, $ddos_to_name, $ddos_email_cc, $ddos_email_bcc, $ddos_email_from, $ddos_email_from_name, $ddos_email_from, $subject, $message, $ddos_email_server_url, $ddos_email_server_port, $ddos_email_username, $ddos_email_password, '');
				
				//Insert new pageviews for DDOS tracking so we know we emailed. This record inserted tells us that the admin was emailed that an IP was blocked.
				$results->getInsertRecord(__LINE__, __FILE__, 'ddos_tracking', '`site_id`, `ip_address`, `emailed`, `created_date`', '?, ?, ?, UTC_TIMESTAMP()', [$site_id, $_SERVER['REMOTE_ADDR'], 'Yes', ]);
			}
		}
	}
	
	//Stop loading site if IP Address is blocked and not set as an Allowed IP.
	if(!in_array($_SERVER['REMOTE_ADDR'], $allowed_ip_addresses))
	{
		foreach($blocked_ip_addresses as $blocked)
		{
			//Exact match OR prefix match. So you can block 1.2.3.4 exact match for all 4 IP blocks A-D or just A-C like 1.2.3.
			if($_SERVER['REMOTE_ADDR'] === $blocked || str_starts_with($_SERVER['REMOTE_ADDR'], $blocked))
			{
				$site_phone_number_blocked = '';
				if(!empty($contact_info_phone_number))
				{
					$site_phone_number_blocked = ' or '.$contact_info_phone_number;
				}
				
				echo '<div class="text-align-padding">Oops! It looks like something went wrong. Please contat us at "'.$contact_info_email.$site_phone_number_blocked.'" for help.</div>';
				exit();
			}
		}
	}
}