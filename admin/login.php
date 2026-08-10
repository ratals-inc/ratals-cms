<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/login.php'))
{
	require($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/login.php');
}
else
{
	if(!isset($_SESSION['admin_directory']))
	{
		header('Cache-Control: no-cache');
		header('Pragma: no-cache');
		//header("Content-Security-Policy: default-src 'self' *.googleapis.com *.gstatic.com; script-src 'self'; style-src 'self' 'unsafe-inline' *.googleapis.com *.gstatic.com;");
	}
	
	//If logged in and revisit /$_SESSION['admin_directory']/login.php send to default admin page.
	$current_url = explode('?', $url);
	if(isset($_SESSION['user_id']) && $current_url[0] == $domain.'/'.$_SESSION['admin_directory'].'/login.php')
	{
		header("Location: ".$domain."/".$_SESSION['admin_directory']."/dashboard/"); 
		exit();
	}
	
	if(isset($_POST['username'])) { $post_username = trim($_POST['username'] ?? ''); } else { $post_username = ''; }
	if(isset($_POST['password'])) { $post_password = trim($_POST['password'] ?? ''); } else { $post_password = ''; }
	
	//Delete failed login attempts that are more than 1 hour old from failed_logins table. We have to delete the records so the user can try logging in again after an hour. This also keeps the failed_login table small in size.
	$results->getDeleteRecord(__LINE__, __FILE__, 'failed_logins', 'WHERE `site_id` = ? AND `created_date` < UTC_TIMESTAMP() - INTERVAL 1 HOUR', [$site_id]);
	
	//If user had to wait, check to see if they still have to wait.
	$get_failed_wait = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'failed_logins', 'WHERE (`ip_address` = ? OR `email` = ?) AND `site_id` = ? AND `wait` = ?', [$_SERVER['REMOTE_ADDR'], $post_username, $site_id, 'Yes']);
	
	if(isset($_POST['submit']) && empty($get_failed_wait))
	{
		$errors = array();
		if(empty($post_username))
		{
			$errors['username1'] = '<div class="validate-red-field">Your username cannot be empty.</div>';
		}
		if(empty($post_password))
		{
			$errors['password1'] = '<div class="validate-red-field">Your password cannot be empty.</div>';
		}
		
		if(!empty($post_username) && !empty($post_password)) 
		{
			$row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'users', 'WHERE `username` = ? AND `status` = ?', [$post_username, 1]);
			
			if(empty($row))
			{
				$errors['valid_user'] = '<div class="success-blue">Oops! Something doesn\'t look right. Please enter a valid username and password.</div>';
			}
			else
			{ 
				$hash_password = hash("sha512", $post_password);
				
				if($hash_password != $row['password'])
				{
					$errors['valid_user'] = '<div class="success-blue">Oops! Something doesn\'t look right. Please enter a valid username and password.</div>';
				}
			}
			
			if(count($errors) == 0) 
			{
				//Get the last time someone logged in. This date is used to see when was the last time we checked for new notices.
				$last_logged_in_user = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'users', 'ORDER BY `last_logged_in` DESC LIMIT 1', []);
				$_SESSION['last_logged_in_user'] = $last_logged_in_user['last_logged_in'];
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'users', '`last_logged_in` = UTC_TIMESTAMP()', 'WHERE `id` = ?', [$row['id']]);
				
				$_SESSION['user_id'] = $row['id'];
				$_SESSION['user_username'] = $row['username'];
				$_SESSION['user_first_last_name'] = $row['first_name']." ".$row['last_name'];
				$_SESSION['user_street_address_1'] = $row['street_address_1'];
				$_SESSION['user_street_address_2'] = $row['street_address_2'];
				$_SESSION['user_city'] = $row['city'];
				$_SESSION['user_state'] = $row['state'];
				$_SESSION['user_postal_code'] = $row['postal_code'];
				$_SESSION['user_phone_number'] = $row['phone_number'];
				$_SESSION['user_phone_number_ext'] = $row['phone_number_ext'];
				$_SESSION['user_email'] = $row['email'];
				$_SESSION['user_email_signiture'] = $row['email_signiture'];
				$_SESSION['user_email_cc'] = $row['email_cc'];
				$_SESSION['user_email_bcc'] = $row['email_bcc'];
				$_SESSION['user_email_server_url'] = $row['email_server_url'];
				$_SESSION['user_email_server_port'] = $row['email_server_port'];
				$_SESSION['user_email_username'] = $row['email_username'];
				$_SESSION['user_email_password'] = $row['email_password'];
				$_SESSION['admin_language'] = '';
				$_SESSION['user_admin_permissions_id'] = $row['admin_permissions_id'];
				$_SESSION['user_allow_software_update_messages'] = $row['allow_software_update_messages'];
				$_SESSION['user_site_permissions_id'] = $row['site_permissions_id'];
				$_SESSION['user_admin_permissions_default_url'] = '';
				if(!empty($_SESSION['user_admin_permissions_id']))
				{
					$permissions_admin_pages_ids = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'permissions', 'WHERE `id` = ?', [$_SESSION['user_admin_permissions_id']]);
					
					//Default admin page set within permissions.
					if(isset($permissions_admin_pages_ids['default_admin_page']) && !empty($permissions_admin_pages_ids['default_admin_page']))
					{
						$default_admin_url = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', 'WHERE `id` = ?', [$permissions_admin_pages_ids['default_admin_page']]);
						
						$_SESSION['user_admin_permissions_default_url'] = $domain.'/'.$_SESSION['admin_directory'].'/'.$default_admin_url['url'].'/';
					}
				}
				
				//Get sites in account.
				$sql_sites_in_account = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'sites', '', [], 'id');
				
				foreach($sql_sites_in_account as $sql_sites_in_account_rows) 
				{
					if(empty($_SESSION['user_site_permissions_id']) || (!empty($_SESSION['user_site_permissions_id']) && strpos($_SESSION['user_site_permissions_id'], ','.$sql_sites_in_account_rows["id"].',') !== false))
					{
						$sites_in_account_array[] = $sql_sites_in_account_rows;
					}
				}
				
				//Set first site that user has access to.
				if(isset($sites_in_account_array[0]["id"]) && !empty($sites_in_account_array[0]["id"]))
				{
					$_SESSION["site_set_for_editing"] = $sites_in_account_array[0]["id"];
					
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
					
					$_SESSION['admin_language'] = $sites_language_array[$_SESSION["site_set_for_editing"]];
				}
				
				//Connect to API messages.
				if(file_exists(rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/admin/cms/api/connect.php'))
				{
					require_once(rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/admin/cms/api/connect.php');
				}
				
				//Take admin user back to same url if they got logged out.
				if(empty($_SESSION['user_admin_permissions_default_url']) && strpos($_SERVER['REQUEST_URI'], $_SESSION['admin_directory'].'/?') !== false)
				{
					header("Location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].'/'.$_SESSION['admin_directory'].'/dashboard/');
				}
				elseif(!empty($_SESSION['user_admin_permissions_default_url']) && strpos($_SERVER['REQUEST_URI'], $_SESSION['admin_directory'].'/?') !== false)
				{
					header("Location: ".$_SESSION['user_admin_permissions_default_url']);
				}
				else
				{
					header("Location: ".$_SERVER['REQUEST_SCHEME']."://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				}
				exit();
			}
			elseif($block_ip_failed_login == 'Yes' && !empty($number_of_failed_login_attempts) && is_numeric($number_of_failed_login_attempts))
			{
				$get_failed_logins = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'failed_logins', 'WHERE (`ip_address` = ? OR `email` = ?) AND `site_id` = ?', [$_SERVER['REMOTE_ADDR'], $post_username, $site_id]);
				
				//Have to subtract 1 becuase count is checked before the insert happens.
				$number_of_failed_login_attempts = $number_of_failed_login_attempts - 1;
				
				$wait = '';
				if($get_failed_logins >= $number_of_failed_login_attempts) 
				{
					$wait = 'Yes';
					
					//Add failed login IP Address to the log;
					if(strpos($failed_login_blocked_ips, $_SERVER['REMOTE_ADDR']) === false)
					{
						$failed_login_blocked_ips .= ', '.$_SERVER['REMOTE_ADDR'];
						$failed_login_blocked_ips = trim($failed_login_blocked_ips, ', ');
						$results->getUpdateRecord(__LINE__, __FILE__, 'site_security', '`failed_login_blocked_ips` = ?', 'WHERE `site_id` = ?', [$failed_login_blocked_ips, $site_id]);
					}
					
					//Send email if failed login max attempts.
					if($failed_login_attempts_email_me == 'Yes' && !empty($failed_login_attempts_email_address) && !empty($failed_login_attempts_email_from) && !empty($failed_login_attempts_email_server_url) && !empty($failed_login_attempts_email_server_port))
					{
						//Get live template directory name.
						$email_template_record = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `status` = ? LIMIT 1', [$_SESSION['site_id'], 1]);
						
						//Get Email Template.
						$subject = '';
						$message = '';
						include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['site_id'].'/templates/'.$email_template_record['directory_folder_name'].'/email-template-max-failed-login-attempts.php';
						
						if(isset($warp_with_email_template) && $warp_with_email_template == 'Yes')
						{
							//Get Email Template Frame.
							include $_SERVER['DOCUMENT_ROOT'].'/sites/'.$_SESSION['site_id'].'/templates/'.$email_template_record['directory_folder_name'].'/email-template.php';
							
							$message = str_replace('[EMAIL_MESSAGE]', $message, $email_template);
						}
						
						//Send Too Many Failed Login Attempts Email.
						smtpSendEmail($failed_login_attempts_email_address, $failed_login_attempts_to_name, $failed_login_attempts_email_cc, $failed_login_attempts_email_bcc, $failed_login_attempts_email_from, $failed_login_attempts_email_from_name, $failed_login_attempts_email_from, $subject, $message, $failed_login_attempts_email_server_url, $failed_login_attempts_email_server_port, $failed_login_attempts_email_username, $failed_login_attempts_email_password, '');
					}
				}
				
				//Insert failed login for tracking brute force.
				$results->getInsertRecord(__LINE__, __FILE__, 'failed_logins', '`site_id`, `ip_address`, `email`, `url`, `wait`, `created_date`', '?,?,?,?,?,UTC_TIMESTAMP()', [$site_id, $_SERVER['REMOTE_ADDR'], $post_username, $url, $wait]);
			}
		}
	}
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Admin Login - <?php echo $site_name; ?></title>
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/head-files.php');?>
    <script nonce="<?php echo NONCE; ?>">
    $(document).ready(function () {
      $(".username").focus();
    });
	</script>
	</head>
	<body>
	<div class="box-wrapper">
	  <div class="box">
		<div class="headline">ADMIN LOGIN</div>
		<?php
		if(empty($get_failed_wait))
		{
		?>
			<?php if(isset($errors['valid_user'])) { echo $errors['valid_user']; } ?>
			<?php if(isset($_GET['login']) && $_GET['login'] == 'logout') { echo '<div class="success-blue">You have logged out successfully.</div>'; } ?>
			<?php if(isset($_GET['login']) && $_GET['login'] == 'login') { echo '<div class="success-blue">Please login.</div>'; } ?>
			<?php if(isset($_GET['signup']) && $_GET['signup'] == 'success') { echo '<div class="success-blue">Your account has been created successfully.</div>'; } ?>
			<?php if(isset($_GET['password-updated']) && $_GET['password-updated'] == 'success') { echo '<div class="success-blue">Your password has been reset successfully.</div>'; } ?>
			<?php if(isset($_GET['recovery']) && $_GET['recovery'] == 'success') { echo '<div class="success-blue">Please check your email for a reset password link.</div>'; } ?>
			<form method="POST">
			  <div class="field">
				<input name="username" class="username" type="text" placeholder="Username" />
				<?php if(isset($errors['username1'])) { echo $errors['username1']; } ?>
			  </div>
			  <div class="field">
				<input name="password" type="password" placeholder="Password" />
				<?php if(isset($errors['password1'])) { echo $errors['password1']; } ?>
			  </div>
			  <div class="button">
				<button name="submit" type="submit" class="button">Login</button>
			  </div>
			  <div class="recover"><a href="<?php echo '/'.$_SESSION['admin_directory'].'/' ?>recover-login.php">Recover Login</a></div>
			</form>
		<?php
		}
		else 
		{
			$try_back_time = $get_failed_wait['created_date'];
			$wait_time_left = (61 - ceil((strtotime(gmdate("Y-m-d H:i:s")) - strtotime(gmdate($try_back_time))) / 60));
			
			if($wait_time_left == 1)
			{
				$minute_or_minutes = 'minute';
			}
			else
			{
				$minute_or_minutes = 'minutes';
			}
			echo '<div class="validate-red">You have run out of login attempts. Please try back in about '.$wait_time_left.' '.$minute_or_minutes.'. You will see the login fields when your waiting time is up.</div>';
		}
		?>
	  </div>
	</div>
	</body>
	</html>
<?php } ?>