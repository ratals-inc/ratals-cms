<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$warp_with_email_template = 'Yes';

$subject = "Max Failed Login Attempts - ".$site_name;

$url_failed_logins = $domain.INSTALLATION_URL_PATH."/".$_SESSION['admin_directory']."/security/failed-logins/";
$url_empty_failed_logins = $domain.INSTALLATION_URL_PATH."/".$_SESSION['admin_directory']."/security/site-security/";

$message = "<p>A visitor to ".$site_name." has had ".($number_of_failed_login_attempts + 1)." failed login attempts in the past 60 minutes from IP Address ".$_SERVER['REMOTE_ADDR'].".</p><p>To view the failed login attempts in the admin area, go here: ";
$message .= '<a href="'.$url_failed_logins.'" target="_blank" style="word-break: break-all;">'.$url_failed_logins.'</a></p>';
$message .= '<p><strong>Important:</strong> We delete failed login attempts when the IP address can start logging in again. If you see no attempts made in the admin area, it might be because they have already been deleted as it happened over an hour ago. We delete these failed login attempts so the database table doesn\'t become too large.</p><p>Also, every once and a while you should empty the failed login attempts log field in the admin area. You can do that here: ';
$message .= '<a href="'.$url_empty_failed_logins.'" target="_blank" style="word-break: break-all;">'.$url_empty_failed_logins.'</a></p>';
$message .= "<p>Thanks,<br>".$site_name." Team</p>";
?>

