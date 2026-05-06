<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/reset-password.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/reset-password.php');
}
else
{
	$post_password = trim($_POST['password'] ?? '');
	$post_confirm_password = trim($_POST['confirm_password'] ?? '');
	$selector = trim($_GET['selector'] ?? '');
	$validator = trim($_GET['validator'] ?? '');
	
	if(isset($_POST['submit'])) 
	{
		//csrf_token validation.
		if($_SERVER['REQUEST_METHOD'] === 'POST' && (empty($_SESSION['csrf_token']) || empty($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])))
		{
			//unset after fail so it will regenerate and create a new secure token for next post.
			unset($_SESSION['csrf_token']);
			csrfFormSubmitToken(); //Set new token for page reload after post.
			header("Location: ".urlId($record_id).'?security=failed&selector='.$selector.'&validator='.$validator);
			exit;
		}
		elseif($_SERVER['REQUEST_METHOD'] === 'POST')
		{
			//unset after pass so it will regenerate and create a new secure token for next post.
			unset($_SESSION['csrf_token']);
			csrfFormSubmitToken(); //Set new token for page reload after post.
		}
		
		$errors = array();
		
		if(empty($selector) || empty($validator))
		{
			$errors['tokens1'] = '<div class="validate-red">Please request a new link to reset your password.</div>';
		}
		
		$password_validation = passwordValidation($post_password);
		
		if(empty($post_password))
		{
			$errors['password1'] = '<div class="validate-red-field">Password cannot be empty.</div>';
		}
		elseif(strlen($post_password) < 10)
		{
			$errors['password1'] = '<div class="validate-red-field">Password must be at least 10 characters long.</div>';
		}
		elseif($password_validation['sepcial_character_in_password'] == 'No')
		{
			$errors['password1'] = '<div class="validate-red-field">Password must have at least 1 special character.</div>';
		}
		elseif($password_validation['letter_in_password'] == 'No')
		{
			$errors['password1'] = '<div class="validate-red-field">Password must have at least 1 letter.</div>';
		}
		elseif($password_validation['number_in_password'] == 'No')
		{
			$errors['password1'] = '<div class="validate-red-field">Password must have at least 1 number.</div>';
		}
		
		if(empty($post_confirm_password))
		{
			$errors['confirm_password1'] = '<div class="validate-red-field">Confirm password cannot be empty.</div>';
		}
		
		if($post_password != $post_confirm_password)
		{
			$errors['match_password1'] = '<div class="validate-red-field">Password and confirm password did not match.</div>';
		}
		
		if(!empty($selector) && !empty($validator) && ctype_xdigit($selector) !== false && ctype_xdigit($validator) !== false && strlen($validator) % 2 == 0)
		{
			$current_time = date('U');
			
			$row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'password_reset_tokens', 'WHERE `reset_selector` = ? AND `reset_expires` >= ?', [$selector, $current_time]);
			
			if(!empty($row))
			{
				if($row['user_email'] == $post_password)
				{
					$errors['password1'] = '<div class="validate-red-field">Username/Email Address and Password cannot be the same.</div>';
				}
				
				$tokenbin = hex2bin($validator);
				$check_hash_token = password_verify($tokenbin, $row['reset_token']);
				
				if($check_hash_token === false)
				{
					$errors['tokens1'] = '<div class="validate-red">Please request a new link to reset your password.</div>';
				}
			}
			else
			{
				$errors['tokens1'] = '<div class="validate-red">Please request a new link to reset your password.</div>';
			}
		}
		else
		{
			$errors['tokens1'] = '<div class="validate-red">Please request a new link to reset your password.</div>';
		}
		
		if(count($errors) == 0 && $check_hash_token === true) 
		{
			$post_hash_password = hash("sha512", trim($post_password ?? ''));
			
			$results->getUpdateRecord(__LINE__, __FILE__, 'users', '`password` = ?', 'WHERE `email` = ?', [$post_hash_password, $row['user_email']]);
			
			$results->getDeleteRecord(__LINE__, __FILE__, 'password_reset_tokens', 'WHERE `user_email` = ?', [$row['user_email']]);
			
			header("Location: /".$_SESSION['admin_directory']."/?password-updated=success"); exit();
		}
	}
	?>
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Reset Password - <?php echo $site_name; ?></title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<?php include_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/head-files.php');?>
	</head>
	<body>
	<div class="box-wrapper">
	  <div class="box">
		<div class="headline">RESET PASSWORD</div>
        <?php if(isset($_GET['security']) && $_GET['security'] == 'failed') { echo '<div class="validate-red">This form has expired. Please submit it again.</div>'; } ?>
		<?php if(isset($errors['tokens1'])) { echo $errors['tokens1']; } ?>
		<form method="post">
		  <div class="field password-requirement-wrap">
			<span>Password Requirements</span>
			<ul class="password-requirements">
			  <li>At least 10 characters long</li>
			  <li>At least one special character (e.g., `~!@#$%^&*()-_+=[{]}\|;:'",.?/)</li>
			  <li>At least one letter (anywhere from A to Z)</li>
			  <li>At least one number (from 0 to 9)</li>
			</ul>
		  </div>
		  <div class="field">
			<input name="password" type="password" placeholder="New Password" />
			<?php if(isset($errors['password1'])) { echo $errors['password1']; } ?>
		  </div>
		  <div class="field">
			<input name="confirm_password" type="password" placeholder="Confirm New Password" />
			<?php if(isset($errors['confirm_password1'])) { echo $errors['confirm_password1']; } ?>
			<?php if(isset($errors['match_password1'])) { echo $errors['match_password1']; } ?>
		  </div>
		  <div class="button">
			<input name="csrf_token" type="hidden" value="<?php echo $_SESSION['csrf_token']; ?>">
            <button name="submit" type="submit" class="button">Reset Password</button>
		  </div>
		</form>
	  </div>
	</div>
	</body>
	</html>
<?php } ?>