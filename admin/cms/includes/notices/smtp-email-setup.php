<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$left_to_fill_in = array();

if(empty($contact_info_smtp_email_name))
{
	$left_to_fill_in[] = 'SMTP Email Name';
}

if(empty($contact_info_smtp_email_address))
{
	$left_to_fill_in[] = 'SMTP Email Address';
}

if(empty($contact_info_smtp_email_hostname))
{
	$left_to_fill_in[] = 'SMTP Email Hostname';
}

if(empty($contact_info_smtp_email_port))
{
	$left_to_fill_in[] = 'SMTP Email Port';
}

if(!empty($left_to_fill_in))
{
	$display_message .= '<div class="setup-message"><strong>Email Delivery Setup Incomplete:</strong> Outgoing SMTP email delivery has not been fully configured. Ratals will attempt to send email using your server\'s PHP mail function, but delivery may be less reliable and messages may be sent to spam or junk folders. To complete SMTP setup, enter the required email delivery settings under <a href="/'.$_SESSION['admin_directory'].'/website/site-settings/contact-information/">Site Contact Information</a>. If your email server requires authentication, also enter the SMTP Email Username and SMTP Email Password. This message will automatically disappear once the required settings are complete.<br><br><strong>Still needs to be completed:</strong> '.implode(', ', $left_to_fill_in).'.</div>';
}