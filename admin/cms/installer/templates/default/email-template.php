<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$email_context = [
    'site_name' => $_SESSION['site_name'] ?? $site_name ?? '',
    'domain' => $_SESSION['domain'] ?? $domain ?? '',
    'logo_media' => $_SESSION['logo_media'] ?? $logo_media ?? '',
    'contact_info_display' => $_SESSION['contact_info_display_contact_info'] ?? $contact_info_display_contact_info ?? 'No',
    'contact_info_phone_number' => $_SESSION['contact_info_phone_number'] ?? $contact_info_phone_number ?? '',
    'contact_info_smtp_email_address' => $_SESSION['contact_info_smtp_email_address'] ?? $contact_info_smtp_email_address ?? '',
    'contact_info_street_address' => $_SESSION['contact_info_street_address'] ?? $contact_info_street_address ?? '',
    'contact_info_city' => $_SESSION['contact_info_city'] ?? $contact_info_city ?? '',
    'contact_info_state' => $_SESSION['contact_info_state'] ?? $contact_info_state ?? '',
    'contact_info_postal_code' => $_SESSION['contact_info_postal_code'] ?? $contact_info_postal_code ?? '',
    'contact_info_country' => $_SESSION['contact_info_country'] ?? $contact_info_country ?? ''
];

$email_logo_site_name = $email_context['site_name'];
if(!empty($email_context['logo_media']))
{
	$email_logo_site_name = str_replace('>', ' style="max-width: 200px; height: auto;">', $email_context['logo_media']);
}

$email_contact_info_phone_number = '';
$email_contact_info_address = '';
if($email_context['contact_info_display'] == 'Yes')
{
	if(!empty($email_context['contact_info_phone_number']))
	{
		$email_contact_info_phone_number = '<a href="tel:'.$email_context['contact_info_phone_number'].'" target="_blank" style="color: #ffffff; text-decoration: none; cursor: default;">'.$email_context['contact_info_phone_number'].'</a><br>';
	}
	
	$email_contact_info_address = '<br>'.$email_context['contact_info_street_address'].', '.$email_context['contact_info_city'].', '.$email_context['contact_info_state'].' '.$email_context['contact_info_postal_code'].', '.$email_context['contact_info_country'];
}

$email_template = '<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>'.$subject.'</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
#outlook a { padding:0; }
.ExternalClass { width:100%; } 
.ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div { line-height: 100%; }
table td { border-collapse: collapse; }
</style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" bgcolor="#ffffff" style="width: 100% !important; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; margin: 0; padding: 0; margin: 0px; padding: 0px; background-color: #ffffff; font-family: sans-serif;">
<table bgcolor="#f1f1f1" width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="font-size: 1.3em;">
	<tr>
		<td>
			<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td valign="top" style="padding:10px;">
						<!-- Start Header -->
						<table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#202020">
							<tr>
								<td align="center">
									<b><a href="'.$email_context['domain'].'" target="_blank" style="font-size: 32px; color: #ffffff; text-decoration: none;">'.$email_logo_site_name.'</a></b><br>
									'.$email_contact_info_phone_number.'
									<a href="mailto:'.$email_context['contact_info_smtp_email_address'].'" target="_blank" style="color: #ffffff; text-decoration: none; display: inline-block; margin-top: 4px;">'.$email_context['contact_info_smtp_email_address'].'</a><br>
								</td>
							</tr>
						</table>
						<!-- End Header -->
						<!-- Start Message -->
						<table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#ffffff" style="table-layout: fixed;">
							<tr>
								<td>
									[EMAIL_MESSAGE]
								</td>
							</tr>
						</table>
						<!-- End Message -->
						<!-- Start Footer -->
						<table width="100%" border="0" cellpadding="10" cellspacing="0" bgcolor="#ffffff">
							<tr>
								<td align="center" style="font-size: 0.9em;">
									<p>This email was sent by:<br><a href="'.$email_context['domain'].'" target="_blank" style="color: #6f9df3;">'.$email_context['site_name'].'</a>'.$email_contact_info_address.'</p>
									<p>&copy; '.date("Y").' '.$email_context['site_name'].'. All Rights Reserved.</p>
									<p><a href="'.urlId([CONTACT_US_PAGE], $results ?? NULL).'" target="_blank" style="color: #6f9df3;">Contact Us</a> | <a href="'.urlId([PRIVACY_POLICY_PAGE], $results ?? NULL).'" target="_blank" style="color: #6f9df3;">Privacy Policy</a></p>
								</td>
							</tr>
						</table>
						<!-- End Footer -->
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>';
?>