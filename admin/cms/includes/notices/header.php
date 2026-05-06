<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$display_message = '';

if($_SESSION['user_allow_software_update_messages'] == 'Yes')
{
	$sql_get_messages = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'notices', 'WHERE `status` = ? ORDER BY `id` DESC', [1]);
}
else
{
	$sql_get_messages = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'notices', 'WHERE `status` = ? AND `notice_update_software` = ? ORDER BY `id` DESC', [1, 'No']);
}

$software_update_message = 'No';

if(!empty($sql_get_messages) && $_SESSION['user_allow_software_update_messages'] == 'Yes')
{
	foreach($sql_get_messages as $check_for_software_update_message)
	{
		if($check_for_software_update_message['notice_update_software'] == 'Yes')
		{
			$software_update_message = 'Yes';
			break;
		}
	}
}

//Get mysql version
$my_sql_version = $results->getRawQuery(__LINE__, __FILE__, 'select version()', []);