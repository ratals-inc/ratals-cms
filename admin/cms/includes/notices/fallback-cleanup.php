<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	//This cleanup file runs from the live admin directory, outside temp_extract.
	define('INSTALLATION_ROOT', dirname(__DIR__, 4));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

//Only allow cleanup requests started by update.php.
if(!isset($_POST['type'], $_POST['cleanup_token']) || $_POST['type'] !== 'updateCleanup')
{
	http_response_code(403);
	exit('Forbidden');
}

$cleanup_token_file = INSTALLATION_ROOT.'/update-cleanup-token.txt';
$cleanup_started_file = INSTALLATION_ROOT.'/update-cleanup-started.txt';

if(!file_exists($cleanup_token_file) || trim(file_get_contents($cleanup_token_file)) !== $_POST['cleanup_token'])
{
	http_response_code(403);
	exit('Forbidden');
}

//Delete the token immediately after verification so it cannot be reused.
@unlink($cleanup_token_file);

//Mark cleanup as started so update.php knows this request was successfully launched.
file_put_contents($cleanup_started_file, '');

//Allow this request to continue after update.php disconnects.
ignore_user_abort(true);
set_time_limit(30);

function removeUpdateTempDirectory($directory_path)
{
	if(!is_dir($directory_path))
	{
		return true;
	}
	
	$directory_contents = scandir($directory_path);
	
	if($directory_contents === false)
	{
		return false;
	}
	
	$cleanup_successful = true;
	
	foreach($directory_contents as $item)
	{
		if($item !== "." && $item !== "..")
		{
			$item_path = $directory_path.'/'.$item;
			
			if(is_dir($item_path))
			{
				if(removeUpdateTempDirectory($item_path) === false)
				{
					$cleanup_successful = false;
				}
			}
			else
			{
				if(file_exists($item_path) && !@unlink($item_path))
				{
					$cleanup_successful = false;
				}
			}
		}
	}
	
	if($cleanup_successful === true && is_dir($directory_path))
	{
		if(!@rmdir($directory_path))
		{
			$cleanup_successful = false;
		}
	}
	
	return $cleanup_successful;
}

$temp_extract_dir = INSTALLATION_ROOT.'/admin/temp_extract';

//Keep trying while update.php finishes and the operating system releases its files.
for($i = 0; $i < 40; $i++)
{
	clearstatcache();
	
	if(!is_dir($temp_extract_dir))
	{
		exit;
	}
	
	if(removeUpdateTempDirectory($temp_extract_dir) === true)
	{
		$log_file = INSTALLATION_ROOT.'/storage/logs/software-update.txt';
		
		date_default_timezone_set($_SESSION['timezone'] ?? 'UTC');
		$timestamp = date('M. d, Y - h:i:s A');
		$formatted_message = '['.$timestamp.'] Temporary update files successfully removed using fallback cleanup. This typically occurs on Windows systems when temporary update directories cannot be removed until the update process has finished. The fallback cleanup successfully removed the remaining directories.'.PHP_EOL.PHP_EOL;
		
		file_put_contents($log_file, $formatted_message, FILE_APPEND | LOCK_EX);
		
		exit;
	}
	
	//Wait 250ms before trying again.
	usleep(250000);
}

error_log('Ratals update fallback cleanup could not completely remove: '.$temp_extract_dir);