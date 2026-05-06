<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/smtp-send-email.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/smtp-send-email.php');
}
else
{
	//SMTP Send Emails.
	if(!function_exists('smtpSendEmail'))
	{
		function smtpSendEmail($email_to, $email_to_name, $email_cc, $email_bcc, $email_from, $email_from_name, $email_reply_to, $email_subject, $email_message, $email_server_url, $email_server_port, $email_username, $password, $email_signiture, $domain_only = '')
		{
			$domain_only = $_SESSION['domain_only'] ?? $domain_only;
			
			if(!empty($email_signiture))
			{
				$email_message = $email_message.$email_signiture;
			}
			
			$email_to = trim(str_replace(' ', '', $email_to ?? ''), ';');
			$email_cc = trim(str_replace(' ', '', $email_cc ?? ''), ';');
			$email_bcc = trim(str_replace(' ', '', $email_bcc ?? ''), ';');
			
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
			
			$mail_socket = @stream_socket_client('tcp://'.$email_server_url.':'.$email_server_port, $error, $error_string, 3, STREAM_CLIENT_CONNECT, $stream_context);
			
			if($mail_socket)
			{
				//Set a read/write timeout so fread() doesn't hang forever
				stream_set_timeout($mail_socket, 10);
				
				fread($mail_socket, 8192);
				fwrite($mail_socket, "EHLO ".$domain_only."\r\n");
				fread($mail_socket, 8192);
				
				//Connect with authentication if username and password filled in.
				if(!empty($email_username) && !empty($password))
				{
					fwrite($mail_socket, "STARTTLS\r\n");
					fread($mail_socket, 8192);
					
					stream_socket_enable_crypto($mail_socket, true, STREAM_CRYPTO_METHOD_SSLv23_CLIENT);
					
					fwrite($mail_socket, "EHLO ".$domain_only."\r\n");
					fread($mail_socket, 8192);
					
					fwrite($mail_socket, "AUTH LOGIN\r\n");
					fread($mail_socket, 8192);
					
					fwrite($mail_socket, base64_encode($email_username)."\r\n");
					fread($mail_socket, 8192);
					
					fwrite($mail_socket, base64_encode($password)."\r\n");
					fread($mail_socket, 8192);
				}
				
				fwrite($mail_socket, "MAIL FROM: ".$email_from_name." <".$email_from.">\r\n");
				fread($mail_socket, 8192);
				
				//This send the To addresses, Cc addresses, and Bcc addresses.
				foreach($email_addresses_array as $email_address_to)
				{
					fwrite($mail_socket, "RCPT TO: <".$email_address_to.">\r\n");
					fread($mail_socket, 8192);
					
				}
				
				fwrite($mail_socket, "DATA\r\n");
				fread($mail_socket, 8192);
				
				//This creates the email and displays what email addresses go in the TO and CC. The BCC variable is not included here as we don't want BCC's to display as being sent to in the email. However some email clients, like Gmail, require the Bcc to be present in the header as empty to be delivered.
				fwrite($mail_socket, "From: ".$email_from."\r\n"."To: ".$email_to_name." <".$email_to.">\r\n"."Cc: ".$email_cc."\r\n"."Bcc: \r\n"."Reply-To: ".$email_reply_to."\r\n"."Return-Path: ".$email_reply_to."\r\n"."Content-Type: text/html; charset=UTF-8\r\n"."MIME-Version: 1.0\r\n"."Subject: ".$email_subject."\r\n\r\n".$email_message."\r\n.\r\n");
				fread($mail_socket, 8192);
				
				fwrite($mail_socket, "QUIT \r\n");
				fread($mail_socket, 8192);
				
				fclose($mail_socket);
			}
		}
	}
}