<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 4));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(isset($_POST['noticeId']) && !empty($_POST['noticeId']) && isset($_POST['versionNumber']) && !empty($_POST['versionNumber']) && isset($_POST['upgradeTo']) && isset($_POST['type']) && $_POST['type'] == 'updateSoftwareNow')
{
	if($_SESSION['user_allow_software_update_messages'] != 'Yes')
	{
		http_response_code(403);
		die('Forbidden');
	}
	
	function removeInstallDirectoryStartUpdate($directory_path)
	{
		if(is_dir($directory_path))
		{
			$directory_contents = scandir($directory_path);
			foreach($directory_contents as $item)
			{
				if($item !== "." && $item !== "..")
				{
					$item_path = $directory_path.'/'.$item;
					if(is_dir($item_path))
					{
						removeInstallDirectoryStartUpdate($item_path);
					}
					else
					{
						unlink($item_path);
					}
				}
			}
			rmdir($directory_path);
		}
	}
	
	//Make sure zip extension is enabled in php so zip the file can be unziped to update.
	if(!extension_loaded('zip'))
	{
		echo json_encode(['status' => 'error', 'message' => 'PHP ZIP extension not enabled. Please enable it before running the update. See https://www.ratals.com/tutorials/installation/enable-php-zip-extension/']);
		exit;
	}
	
	//Make sure allow_url_fopen extension is enabled in php so we can open the zip file.
	if(!ini_get('allow_url_fopen'))
	{
		echo json_encode(['status' => 'error', 'message' => 'allow_url_fopen is disabled. This must be enabled to download the update file.']);
		exit;
	}
	
	//Set save path
	$file_name = 'ratals-core';
	$save_path = rtrim(INSTALLATION_ROOT, '/').'/'.$file_name.'.zip';
	
	//Create zip file on server to download to.
	$save_file = fopen($save_path, 'w');
	if(!$save_file)
	{
		if(file_exists($save_path))
		{
			unlink($save_path);
		}
		
		echo json_encode(['status' => 'error', 'message' => 'Failed to open saved zip file for writing.']);
		exit;
	}
	
	//Ratals API Endpoint
	$api_endpoint = 'https://www.ratals.com/api/download/index.php';
	
	//Get license key
	$license_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'license', 'WHERE `site_id` = ? LIMIT 1', [0]);
	$license_key = $license_data['license_key'] ?? '';
	
	//Send license data
	$api_data = [
		"authentication" => [
			"domain" => $_SERVER['HTTP_HOST'] ?? '',
			"ip" => $_SERVER['REMOTE_ADDR'] ?? '',
			"file_name" => $file_name,
			"license_key" => $license_key
		]
	];
	
	$api_json = json_encode($api_data, JSON_UNESCAPED_SLASHES);
	
	//Stream directly from curl into the file
	$curl = curl_init($api_endpoint);
	curl_setopt_array($curl, [
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $api_json,
		CURLOPT_FILE => $save_file, //<== stream directly into file
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTPHEADER => [
			"Content-Type: application/json"
		],
		CURLOPT_FOLLOWLOCATION => true
	]);
	
	$success = curl_exec($curl);
	$error = curl_error($curl);
	$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	curl_close($curl);
	fclose($save_file);
	
	//Check if we get a 200 header response code for successful download and if downloaded file is empty.
	if(!$success || $http_code !== 200)
	{
		if(file_exists($save_path))
		{
			unlink($save_path);
		}
		
		echo json_encode(['status' => 'error', 'message' => 'Failed to download update. HTTP code: '.$http_code.' Error: '.$error]);
		exit;
	}
	
	//Check if downloaded file is empty/
	if(filesize($save_path) === 0)
	{
		if(file_exists($save_path))
		{
			unlink($save_path);
		}
		
		echo json_encode(['status'=>'error','message'=>'Failed to download update.']);
		exit;
	}
	
	//Extract to temp_extract folder
	$temp_extract_dir = INSTALLATION_ROOT.'/admin/temp_extract';
	
	//Delete files that might be in an old temp_extract directory.
	if(is_dir($temp_extract_dir))
	{
		removeInstallDirectoryStartUpdate($temp_extract_dir);
	}
	
	//Create temp folder if it doesn't exist
	if(!is_dir($temp_extract_dir))
	{
		//Try permission 0755 first
		if(mkdir($temp_extract_dir, 0755, true))
		{
			chmod($temp_extract_dir, 0755);
		}
		else
		{
			//If 0755 fails, fallback to 0775 for group permission
			if(mkdir($temp_extract_dir, 0775, true))
			{
				chmod($temp_extract_dir, 0775);
			}
			else
			{
				echo json_encode(['status' => 'error', 'message' => 'Failed to create temp directory: '.$temp_extract_dir]);
				exit;
			}
		}
	}
	
	//Open ZIP file
	$zip = new ZipArchive();
	if($zip->open($save_path) !== TRUE)
	{
		if(file_exists($save_path))
		{
			unlink($save_path);
		}
		
		echo json_encode(['status' => 'error', 'message' => 'Failed to open ZIP file: '.$save_path]);
		exit;
	}
	
	//Extract ZIP to temp folder
	if(!$zip->extractTo($temp_extract_dir))
	{
		$zip->close();
		
		if(file_exists($save_path))
		{
			unlink($save_path);
		}
		
		echo json_encode(['status' => 'error', 'message' => 'Failed to extract ZIP file to temp directory: '.$temp_extract_dir]);
		exit;
	}
	
	$zip->close();
	
	//Delete ZIP file after extraction
	if(!unlink($save_path))
	{
		echo json_encode(['status' => 'error', 'message' => 'Failed to delete downloaded ZIP file: '.$save_path]);
		exit;
	}
	
	//Create file if it doesn't exist
	$timestamp = time();
	$progress_log_file = INSTALLATION_ROOT.'/progress-software-update-log-'.$timestamp.'.txt';
	file_put_contents($progress_log_file, '');
	
	//Pass this filename to update.php
	$_SESSION['current_update_log'] = $progress_log_file;
	
	//Build URL for the actual updater
	$update_url = $domain.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/temp_extract/admin/cms/includes/notices/update.php';
	
	//Token file
	$update_token = bin2hex(random_bytes(32));
	$token_file = INSTALLATION_ROOT.'/admin/temp_extract/update-token-'.$timestamp.'.txt';
	file_put_contents($token_file, $update_token);
	
	//Pass same POST data to update.php
	$post_fields = [
					'noticeId' => $_POST['noticeId'], 
					'versionNumber' => $_POST['versionNumber'], 
					'upgradeTo' => $_POST['upgradeTo'],
					'current_update_log' => $progress_log_file, 
					'token_file' => $token_file, 
					'update_token' => $update_token, 
					'type' => 'updateSoftwareNow'];
	
	//Release session lock before async call
	session_write_close();
	
	//Remove started-file.txt to avoid stale file starts.
	$started_file = INSTALLATION_ROOT.'/update-started.txt';
	if(file_exists($started_file))
	{
		unlink($started_file);
	}
	
	//Fire background HTTP request.
	//Try starting up to 5 times to make sure it starts.
	$started = false;
	for($i = 0; $i < 5; $i++)
	{
		$ch = curl_init($update_url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post_fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 2);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 500);
		curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
	
		curl_exec($ch);
		curl_close($ch);
		
		//Make sure to clear the cache to check for current file.
		clearstatcache(true, $started_file);
		
		//Check every 100ms for 2 seconds if started-file.txt was created to know it started.
		for($j = 0; $j < 20; $j++)
		{
			if(file_exists($started_file))
			{
				$started = true;
				break 2;
			}
			usleep(100000); //100ms
		}
	}
	
	if(!$started)
	{
		echo json_encode(['status'=>'error','message'=>'Update failed to start']);
		exit;
	}
	
	//Mark update notice as read if the admin user updates the software
	$results->getUpdateRecord(__LINE__, __FILE__, 'notices', '`status` = ?', 'WHERE `id` = ?', [2, $_POST['noticeId']]);
	
	//Respond immediately to the user
	echo json_encode(['status' => 'started', 'message' => 'Update started in the background. Click OK to continue working.']);
	
	exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);