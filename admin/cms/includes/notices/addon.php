<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Display update in progress bar. This is outside of the $sql_get_messages condition so its included on every admin page load so user can see progress.
if(isset($_SESSION['current_update_log']) && file_exists($_SESSION['current_update_log']))
{
	$display_message .= '
	<style nonce="'.NONCE.'">
	.outer-update-progress-bar { width: 100%; background: #ddd; height: 20px; border-radius: 4px; overflow: hidden; }
	.inner-update-progress-bar { height: 20px; width: 0%; background: #4caf50; transition: width 0.3s; }
	.update-progress-bar-text { text-align: center; margin-top: 6px; margin-bottom: 10px; }
	</style>
	<div class="outer-update-progress-bar">
		<div id="progress-bar" class="inner-update-progress-bar"></div>
	</div>
	<div id="progress-text" class="update-progress-bar-text">Preparing update...</div>
	';
}

if(!empty($sql_get_messages))
{
	foreach($sql_get_messages as $sql_get_message)
	{
		$new_message = $sql_get_message['notice_message'];
		
		$message_date = utcToUserTimeZone($sql_get_message['created_date'], 'M. d, Y - g:i A');
		
		$new_software_version = '';
		//This is used during beta when version number is formatted like: Beta 0.01.
		if(strpos($sql_get_message['notice_software_version'] ?? '', ' '))
		{
			$new_software_version_array = explode(' ', $sql_get_message['notice_software_version']);
			$new_software_version = $new_software_version_array[1];
			$new_full_software_version = $sql_get_message['notice_software_version'];
			
		}
		elseif(!empty($sql_get_message['notice_software_version']))
		{
			$new_software_version = $sql_get_message['notice_software_version'];
			$new_full_software_version = $sql_get_message['notice_software_version'];
		}
		
		$curr_software_version = '';
		if(isset($current_software_version) && !empty($current_software_version))
		{
			$curr_software_version = $current_software_version;
			
			if(strpos($current_software_version, 'Beta ') !== false)
			{
				$current_software_version_array = explode(' ', $current_software_version);
				$curr_software_version = $current_software_version_array[1];
			}
		}
		
		//Get software version.
		$old_software_version = "[YOUR_SOFTWARE_VERSION]";
		$new_message = str_replace($old_software_version, $current_software_version, $new_message);
		
		//Get php version.
		$php_version = phpversion();
		$old_php_version = "[CURRENT_PHP_VERSION]"; 
		$new_php_version = $php_version; 
		$new_message = str_replace($old_php_version, $new_php_version, $new_message);
		
		$mysql_version = '';
		if(isset($my_sql_version[0]['version()']))
		{
			$mysql_version = explode('-', $my_sql_version[0]['version()']);
			
			$old_mysql_version = "[CURRENT_MYSQL_VERSION]"; 
			$new_mysql_version = $mysql_version[0];
			$new_message = str_replace($old_mysql_version, $new_mysql_version, $new_message);
		}
		
		//If message is a software update and everything passes to be updated, show message with update button.
		if($sql_get_message['notice_update_software'] == 'Yes' && isset($php_version) && !empty($php_version) && isset($new_mysql_version) && !empty($new_mysql_version) && !empty($new_software_version) && !empty($curr_software_version) && $new_software_version >= $curr_software_version)
		{
			if($_SESSION['user_allow_software_update_messages'] == 'Yes')
			{
				$notice_upgrade_to = "''";
				if(!empty($sql_get_message['notice_upgrade_to']))
				{
					$notice_upgrade_to = $sql_get_message['notice_upgrade_to'];
				}
				
				$display_update_message = '<p class="version-requirements">The "Update Now" button is unavailable because your PHP and/or MySQL version does not meet the minimum requirements.</p>';
				$display_update_button = '';
				if($php_version >= $sql_get_message['required_php_version'] && $new_mysql_version >= $sql_get_message['required_mysql_version'])
				{
					$display_update_message = '';
					$display_update_button = '<button class="update-software-now" data-click="'.$sql_get_message['id'].','.$new_full_software_version.','.$notice_upgrade_to.'">Update Now</button>';
				}
				
				$display_message .= '<div class="notices">
					<div class="message">
						<div>
							<h1>'.$sql_get_message['notice_subject'].'</h1>
							<div class="meassge-content">'.$new_message.'</div>
							'.$display_update_message.'
							<div class="meassge-info">'.$message_date.' <span class="spacer">|</span> <span class="mark-as-read" data-click="'.$sql_get_message['id'].'">Mark as Read</span></div>
						</div>
						'.$display_update_button.'
					</div>
				</div>';
			}
		}
		//Display regular messages.
		else
		{
			$display_message .= '<div class="notices">
				<div class="message">
					<div>
						<h1>'.$sql_get_message['notice_subject'].'</h1>
						<div class="meassge-content">'.$new_message.'</div>
						<div class="meassge-info">'.$message_date.' <span class="spacer">|</span> <span class="mark-as-read" data-click="'.$sql_get_message['id'].'">Mark as Read</span></div>
					</div>
				</div>
			</div>';
		}
	}
}