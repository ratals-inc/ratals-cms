<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/smtp-send-email.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/smtp-send-email.php');
}
else
{
	//SMTP Send Emails.
	if(!function_exists('smtpSendEmail'))
	{
		function smtpSendEmail($email_to, $email_to_name, $email_cc, $email_bcc, $email_from_address, $email_from_name, $email_reply_to, $email_subject, $email_message, $smtp_hostname, $smtp_port, $smtp_username, $smtp_password, $admin_user_email_signature, $domain_only)
		{
			$domain_only = $_SESSION['domain_only'] ?? $domain_only;
			
			if(!empty($admin_user_email_signature))
			{
				$email_message = $email_message.$admin_user_email_signature;
			}
			
			$email_to = trim(str_replace(' ', '', $email_to ?? ''), ';');
			$email_cc = trim(str_replace(' ', '', $email_cc ?? ''), ';');
			$email_bcc = trim(str_replace(' ', '', $email_bcc ?? ''), ';');
			
			//Attempt SMTP if SMTP server information is configured.
			if(!empty($smtp_hostname) && !empty($smtp_port))
			{
				$email_addresses = $email_to;
				
				//Add CC's in.
				if(!empty($email_cc))
				{
					$email_addresses = $email_addresses.';'.$email_cc;
				}
				
				//Add BCC's in.
				if(!empty($email_bcc))
				{
					$email_addresses = $email_addresses.';'.$email_bcc;
				}
				
				$email_addresses_array = explode(';', $email_addresses);
				
				$stream_context = stream_context_create();
				stream_context_set_option($stream_context, 'ssl', 'verify_peer', false);
				stream_context_set_option($stream_context, 'ssl', 'verify_peer_name', false);
				
				$mail_socket = @stream_socket_client('tcp://'.$smtp_hostname.':'.$smtp_port, $error, $error_string, 3, STREAM_CLIENT_CONNECT, $stream_context);
				
				if($mail_socket)
				{
					//Set a read/write timeout so fread() doesn't hang forever.
					stream_set_timeout($mail_socket, 10);
					
					fread($mail_socket, 8192);
					fwrite($mail_socket, "EHLO ".$domain_only."\r\n");
					fread($mail_socket, 8192);
					
					//Connect with authentication if username and password filled in.
					if(!empty($smtp_username) && !empty($smtp_password))
					{
						fwrite($mail_socket, "STARTTLS\r\n");
						fread($mail_socket, 8192);
						
						stream_socket_enable_crypto($mail_socket, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);
						
						fwrite($mail_socket, "EHLO ".$domain_only."\r\n");
						fread($mail_socket, 8192);
						
						fwrite($mail_socket, "AUTH LOGIN\r\n");
						fread($mail_socket, 8192);
						
						fwrite($mail_socket, base64_encode($smtp_username)."\r\n");
						fread($mail_socket, 8192);
						
						fwrite($mail_socket, base64_encode($smtp_password)."\r\n");
						fread($mail_socket, 8192);
					}
					
					fwrite($mail_socket, "MAIL FROM:<".$email_from_address.">\r\n");
					fread($mail_socket, 8192);
					
					//Send the To addresses, Cc addresses, and Bcc addresses.
					foreach($email_addresses_array as $email_address_to)
					{
						if(!empty($email_address_to))
						{
							fwrite($mail_socket, "RCPT TO: <".$email_address_to.">\r\n");
							fread($mail_socket, 8192);
						}
					}
					
					fwrite($mail_socket, "DATA\r\n");
					fread($mail_socket, 8192);
					
					//This creates the email and displays what email addresses go in the TO and CC. The BCC variable is not included here as we don't want BCC's to display as being sent to in the email. However some email clients, like Gmail, require the Bcc to be present in the header as empty to be delivered.
					fwrite(
						$mail_socket,
						"From: ".$email_from_name." <".$email_from_address.">\r\n".
						"To: ".$email_to_name." <".$email_to.">\r\n".
						"Cc: ".$email_cc."\r\n".
						"Bcc: \r\n".
						"Reply-To: ".$email_reply_to."\r\n".
						"Return-Path: ".$email_reply_to."\r\n".
						"Content-Type: text/html; charset=UTF-8\r\n".
						"MIME-Version: 1.0\r\n".
						"Subject: ".$email_subject."\r\n\r\n".
						$email_message."\r\n.\r\n"
					);
					
					$email_send_response = fread($mail_socket, 8192);
					
					fwrite($mail_socket, "QUIT\r\n");
					fread($mail_socket, 8192);
					
					fclose($mail_socket);
					
					if(substr($email_send_response, 0, 3) == '250')
					{
						return true;
					}
				}
			}
			
			//Fallback to PHP mail() if SMTP is not configured or SMTP failed.
			if(function_exists('mail'))
			{
				$mail_headers = array();
				
				if(!empty($email_from_address))
				{
					if(!empty($email_from_name))
					{
						$mail_headers[] = 'From: '.$email_from_name.' <'.$email_from_address.'>';
					}
					else
					{
						$mail_headers[] = 'From: '.$email_from_address;
					}
				}
				
				if(!empty($email_reply_to))
				{
					$mail_headers[] = 'Reply-To: '.$email_reply_to;
				}
				
				if(!empty($email_cc))
				{
					$mail_headers[] = 'Cc: '.str_replace(';', ',', $email_cc);
				}
				
				if(!empty($email_bcc))
				{
					$mail_headers[] = 'Bcc: '.str_replace(';', ',', $email_bcc);
				}
				
				$mail_headers[] = 'MIME-Version: 1.0';
				$mail_headers[] = 'Content-Type: text/html; charset=UTF-8';
				
				//PHP mail() uses commas between multiple email addresses.
				$mail_to = str_replace(';', ',', $email_to);
				
				if(mail($mail_to, $email_subject, $email_message, implode("\r\n", $mail_headers)))
				{
					return true;
				}
			}
			
			return false;
		}
	}
}