<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$warp_with_email_template = 'Yes';

$subject = "Password Reset - ".$site_name;

$url = $domain.INSTALLATION_URL_PATH."/".$_SESSION['admin_directory']."/reset-password.php?selector=".$selector."&validator=".bin2hex($token);
$message = "<p>We've received a password reset request. The link to reset your password is below. If you did not make this request, you can ignore this email.</p><p>Here is your password reset link: ";
$message .= '<a href="'.$url.'" target="_blank" style="word-break: break-all;">'.$url.'</a></p>';
$message .= "<p>Thanks,<br>".$site_name." Team</p>";
?>

