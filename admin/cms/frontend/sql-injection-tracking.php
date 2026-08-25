<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/sql-injection-tracking.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/sql-injection-tracking.php');
}
else
{
	//Do not flag sql injection once logged into the admin as this should be an admin user.
	if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id']))
	{
		if((!empty($_POST) || !empty($_GET)) && $sql_injection_email_me == 'Yes' && !empty($sql_injection_to_email_address))
		{
			$posted_string_raw = '';
			$posted_string = '';
			$posted_string_no_spaces = '';
			
			if(!empty($_POST))
			{
				$posted_string_raw .= json_encode($_POST);
			}
			
			if(!empty($_GET))
			{
				$posted_string_raw .= json_encode($_GET);
			}
			
			if(!empty($posted_string_raw))
			{
				$posted_string = urldecode(strtoupper($posted_string_raw));
				$posted_string_no_spaces = preg_replace('/\s+/S', "", $posted_string);
			}
			
			if(
				//Basic checks
				strpos($posted_string_no_spaces, '(SELECT') !== false
				|| strpos($posted_string_no_spaces, 'SELECT(') !== false
				|| strpos($posted_string_no_spaces, 'SELECT*') !== false
				|| strpos($posted_string_no_spaces, 'DELETEFROM') !== false
				|| strpos($posted_string_no_spaces, 'ALTERTABLE') !== false
				|| strpos($posted_string_no_spaces, 'DROPTABLE') !== false
				|| strpos($posted_string_no_spaces, '(CASEWHEN') !== false
				|| strpos($posted_string_no_spaces, 'THENNULL') !== false
				|| strpos($posted_string_no_spaces, 'NULLELSE') !== false
				|| strpos($posted_string_no_spaces, 'CAST(CHR') !== false
				|| strpos($posted_string_no_spaces, 'CAST((CHR') !== false
				|| strpos($posted_string_no_spaces, '|CHR') !== false
				|| strpos($posted_string_no_spaces, 'ASNUMERIC') !== false
				|| strpos($posted_string_no_spaces, 'END)') !== false
				|| strpos($posted_string_no_spaces, 'ISNULL') !== false
				//Heuristics - logical operators
				|| strpos($posted_string_no_spaces, 'OR1=1') !== false
				|| strpos($posted_string_no_spaces, 'AND1=1') !== false
				|| strpos($posted_string_no_spaces, 'OR\'A\'=\'A\'') !== false
				|| strpos($posted_string_no_spaces, 'AND\'A\'=\'A\'') !== false
				//Heuristics - time-based / function abuse
				|| strpos($posted_string_no_spaces, 'SLEEP(') !== false
				|| strpos($posted_string_no_spaces, 'PG_SLEEP(') !== false
				|| strpos($posted_string_no_spaces, 'BENCHMARK(') !== false
				|| strpos($posted_string_no_spaces, 'DBMS_PIPE.RECEIVE_MESSAGE(') !== false
				|| strpos($posted_string_no_spaces, 'LOAD_FILE(') !== false
				|| strpos($posted_string_no_spaces, 'CONVERT(') !== false
				|| strpos($posted_string_no_spaces, 'CHAR(') !== false
				|| strpos($posted_string_no_spaces, 'CHR(') !== false
				//Heuristics - union-based
				|| strpos($posted_string_no_spaces, 'UNIONSELECT') !== false
				|| strpos($posted_string_no_spaces, 'UNIONALLSELECT') !== false
				|| strpos($posted_string_no_spaces, 'UNIONDISTINCTSELECT') !== false
				//Heuristics - comments / statement termination
				|| strpos($posted_string_no_spaces, '--') !== false
				|| strpos($posted_string_no_spaces, '#') !== false
				|| strpos($posted_string_no_spaces, '/*') !== false
				|| strpos($posted_string_no_spaces, '*/') !== false
				|| strpos($posted_string_no_spaces, ';') !== false
				//Heuristics - version / system info
				|| strpos($posted_string_no_spaces, '@@VERSION') !== false
				|| strpos($posted_string_no_spaces, '@@VERSION_COMMENT') !== false
			   )
			{
				//Get live template directory name.
				$email_template_record = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `status` = ? LIMIT 1', [$_SESSION['site_id'], 1]);
				
				//Get Email Template.
				$subject = '';
				$message = '';
				include INSTALLATION_ROOT.'/sites/'.$_SESSION['site_id'].'/templates/'.$email_template_record['directory_folder_name'].'/email-template-possible-sql-injection-attempt.php';
				
				if(isset($warp_with_email_template) && $warp_with_email_template == 'Yes')
				{
					//Get Email Template Frame.
					include INSTALLATION_ROOT.'/sites/'.$_SESSION['site_id'].'/templates/'.$email_template_record['directory_folder_name'].'/email-template.php';
					
					$message = str_replace('[EMAIL_MESSAGE]', $message, $email_template);
				}
				
				//Send Possible SQL Injection Attempt Email.
				smtpSendEmail($sql_injection_to_email_address, $sql_injection_to_email_name, $sql_injection_email_cc, $sql_injection_email_bcc, $contact_info_smtp_email_address, $contact_info_smtp_email_name, $contact_info_smtp_email_address, $subject, $message, $contact_info_smtp_email_hostname, $contact_info_smtp_email_port, $contact_info_smtp_email_username, $contact_info_smtp_email_password, '', '');
			}
		}
	}
}