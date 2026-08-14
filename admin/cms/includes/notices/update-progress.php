<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 4));
}

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

$progress_log_file = $_SESSION['current_update_log'] ?? NULL;

if(empty($progress_log_file) || !file_exists($progress_log_file))
{
	echo json_encode(['progress' => 0, 'step_name' => 'No update in progress', 'log_exists' => false]);
	exit;
}

$response = ['progress' => 0, 'step_name' => 'Preparing update...', 'log_exists' => file_exists($progress_log_file)];

if(file_exists($progress_log_file))
{
	$log = file_get_contents($progress_log_file);
	$response['log_exists'] = true;

	$checkpoints = array(
		'Files extracted to staging folder.' => 0,
		'Installing/updating database tables, columns, and indexes...' => 5,
		'Updating admin fields...' => 10,
		'Updating database table IDs...' => 15,
		'Updating admin pages...' => 20,
		'Updating admin menus...' => 25,
		'Updating admin menu items...' => 30,
		'Updating admin field lists...' => 35,
		'Updating admin field values...' => 40,
		'Updating admin field sections...' => 45,
		'Updating website template files...' => 50,
		'Updating website menus...' => 55,
		'Updating website menu items...' => 60,
		'Updating website pages URLs...' => 65,
		'Updating website pages...' => 70,
		'Updating site settings for each site...' => 75,
		'Updating site security for each site...' => 80,
		'Updating blocking spam for each site...' => 85,
		'Updating assigned fields for each user...' => 90,
		'Copying update files from staging folder to live directory...' => 95,
		'Software update process completed successfully.' => 100
	);

	foreach(array_reverse($checkpoints, true) as $pattern => $percent)
	{
		if(strpos($log, $pattern) !== false)
		{
			$response['progress'] = $percent;
			$response['step_name'] = $pattern;
			break;
		}
	}
}

header('Content-Type: application/json');
echo json_encode($response);