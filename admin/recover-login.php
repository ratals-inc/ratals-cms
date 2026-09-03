<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/recover-login.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/recover-login.php');
}
else
{
	$post_username = '';
	if(isset($_POST['username']))
	{
		$post_username = trim($_POST['username'] ?? '');
	}
	
	if(isset($_POST['submit'])) 
	{
		//csrf_token validation.
		if($_SERVER['REQUEST_METHOD'] === 'POST' && (empty($_SESSION['csrf_token']) || empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])))
		{
			//unset after fail so it will regenerate and create a new secure token for next post.
			unset($_SESSION['csrf_token']);
			csrfFormSubmitToken(); //Set new token for page reload after post.
			header("Location: ".urlId($record_id).'?security=failed');
			exit;
		}
		elseif($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			//unset after pass so it will regenerate and create a new secure token for next post.
			unset($_SESSION['csrf_token']);
			csrfFormSubmitToken(); //Set new token for page reload after post.
		}
		
		$errors = array();
		
		if(empty(trim($post_username ?? '')))
		{
			$errors['username1'] = '<div class="validate-red-field">Email or username cannot be empty.</div>';
		}
		
		if(!empty(trim($post_username ?? '')))
		{
			$user_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'users', 'WHERE (`user_email_address` = ? OR `username` = ?)', [$post_username, $post_username]);
			
			if(empty($user_row)) 
			{
				//Send them to a success page when not successful so we dont tell hackers if user is valid.
				header("Location: ".INSTALLATION_URL_PATH."/".$_SESSION['admin_directory']."/?recovery=success"); exit();
			} 
			else 
			{
				$selector = bin2hex(random_bytes(8));
				$token = bin2hex(random_bytes(32));
				
				$hashed_token = password_hash($token, PASSWORD_DEFAULT);
				$expires = date('U') + 1800;
				
				//Delete old recover password tokesn from database.
				$results->getDeleteRecord(__LINE__, __FILE__, 'password_reset_tokens', 'WHERE `user_email` = ?', [$user_row['user_email_address']]);
				
				//Insert new password token to reset password.
				$results->getInsertRecord(__LINE__, __FILE__, 'password_reset_tokens', '`site_id`, `user_email`, `reset_selector`, `reset_token`, `reset_expires`', '?,?,?,?,?', [$site_id, $user_row['user_email_address'], $selector, $hashed_token, $expires]);
				
				
				if(!empty($user_row['user_email_address']))
				{
					//Get live template directory name.
					$email_template_record = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `status` = ? LIMIT 1', [$_SESSION['site_id'], 1]);
					
					//Get Email Template.
					$subject = '';
					$message = '';
					include INSTALLATION_ROOT.'/sites/'.$_SESSION['site_id'].'/templates/'.$email_template_record['directory_folder_name'].'/email-template-password-reset-admin.php';
					
					if(isset($warp_with_email_template) && $warp_with_email_template == 'Yes')
					{
						//Get Email Template Frame.
						include INSTALLATION_ROOT.'/sites/'.$_SESSION['site_id'].'/templates/'.$email_template_record['directory_folder_name'].'/email-template.php';
						
						$message = str_replace('[EMAIL_MESSAGE]', $message, $email_template);
					}
					
					//Send Password Reset Email.
					smtpSendEmail($user_row['user_email_address'], $user_row['first_name'], $contact_info_smtp_email_cc, $contact_info_smtp_email_bcc, $contact_info_smtp_email_address, $contact_info_smtp_email_name, $contact_info_smtp_email_address, $subject, $message, $contact_info_smtp_email_hostname, $contact_info_smtp_email_port, $contact_info_smtp_email_username, $contact_info_smtp_email_password, '', '');
				}
			} 
		}
	
		if(count($errors) == 0) 
		{
			header("Location: ".INSTALLATION_URL_PATH."/".$_SESSION['admin_directory']."/?recovery=success"); exit();
		}
	}
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reciver Login - <?php echo $site_name; ?></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<?php include_once(INSTALLATION_ROOT.'/admin/cms/includes/head-files.php');?>
	</head>
	<body>
	<div class="box-wrapper">
	  <div class="box">
		<div class="headline">RECOVER LOGIN</div>
        <?php if(isset($_GET['security']) && $_GET['security'] == 'failed') { echo '<div class="validate-red">This form has expired. Please submit it again.</div>'; } ?>
		<form method="post">
		  <div class="field">
			<input name="username" type="text" placeholder="Email or Username" />
			<?php if(isset($errors['username1'])) { echo $errors['username1']; } ?>
			<?php if(isset($errors['username2'])) { echo $errors['username2']; } ?>
		  </div>
		  <div class="button">
			<input name="csrf_token" type="hidden" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button name="submit" type="submit" class="button">Recover Login</button>
		  </div>
		</form>
	  </div>
	</div>
	</body>
	</html>
<?php } ?>