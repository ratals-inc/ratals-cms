<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/ajax/template-files.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/ajax/template-files.php');
}
else
{
	header('Content-Type: application/json; charset=utf-8');
	
	if(!isset($_POST['template_file_id']) || !is_numeric($_POST['template_file_id']))
	{
		echo json_encode([
			'success' => false,
			'message' => 'A valid Template File ID is required.'
		]);
		exit();
	}
	
	$template_file_id = (int) $_POST['template_file_id'];
	$site_id = (int) ($_SESSION['site_set_for_editing'] ?? 0);
	
	if(empty($site_id))
	{
		echo json_encode([
			'success' => false,
			'message' => 'A valid Site ID could not be found.'
		]);
		exit();
	}
	
	//Get the template file being copied.
	$template_file_record = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'template_files', 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$template_file_id, $site_id]);
	
	if(empty($template_file_record))
	{
		echo json_encode([
			'success' => false,
			'message' => 'The template file could not be found.'
		]);
		exit();
	}
	
	$template_id = (int) $template_file_record['templates_id'];
	
	//Get the template so we know its directory.
	$template_record = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `id` = ? AND `site_id` = ? LIMIT 1', [$template_id, $site_id]);
	
	if(empty($template_record))
	{
		echo json_encode([
			'success' => false,
			'message' => 'The template associated with this file could not be found.'
		]);
		exit();
	}
	
	if(empty($template_record['directory_folder_name']))
	{
		echo json_encode([
			'success' => false,
			'message' => 'The template directory could not be determined.'
		]);
		exit();
	}
	
	//Set the template directory.
	$template_directory = INSTALLATION_ROOT.'/sites/'.$site_id.'/templates/'.$template_record['directory_folder_name'].'/';
	
	//Set the existing template file path.
	$existing_filename = $template_file_record['filename'];
	$existing_file_path = $template_directory.$existing_filename;
	
	if(!is_file($existing_file_path))
	{
		echo json_encode([
			'success' => false,
			'message' => 'The template file could not be found on the server to copy it.'
		]);
		exit();
	}
	
	//Get the filename and extension separately.
	$filename_extension = pathinfo($existing_filename, PATHINFO_EXTENSION);
	$filename_without_extension = pathinfo($existing_filename, PATHINFO_FILENAME);
	
	//If they copy an existing copy, keep using the original base name.
	//Example: pages-two-column-copy-2.php becomes pages-two-column.
	$filename_without_extension = preg_replace('/-copy-\d+$/i', '', $filename_without_extension);
	
	//Do the same for the display name.
	$base_name = trim($template_file_record['name']);
	$base_name = preg_replace('/\s*-\s*Copy\s+\d+$/i', '', $base_name);
	
	//Find the first available copy number in both the DB and filesystem.
	$copy_number = 1;
	$new_filename = '';
	$new_name = '';
	$new_file_path = '';
	
	while(true)
	{
		$new_filename = $filename_without_extension.'-copy-'.$copy_number;
	
		if(!empty($filename_extension))
		{
			$new_filename .= '.'.$filename_extension;
		}
	
		$new_name = $base_name.' - Copy '.$copy_number;
		$new_file_path = $template_directory.$new_filename;
	
		//Check if this filename already exists in the database.
		$database_file_exists = $_SESSION['results']->getSelectCountRecords(__LINE__, __FILE__, '*', 'template_files', 'WHERE `site_id` = ? AND `templates_id` = ? AND `filename` = ?', [$site_id, $template_id, $new_filename]);
	
		//The name must be available in both the database and filesystem.
		if(empty($database_file_exists) && !file_exists($new_file_path))
		{
			break;
		}
	
		$copy_number++;
	}
	
	//Copy the physical template file first.
	if(!copy($existing_file_path, $new_file_path))
	{
		echo json_encode([
			'success' => false,
			'message' => 'The template file could not be copied on the server.'
		]);
		exit();
	}
	
	//Use the current logged-in user's name.
	//If $first_last_name is already created by your AJAX bootstrap, this uses it.
	$current_user_name = $first_last_name ?? ($_SESSION['first_last_name'] ?? 'System');
	
	//Create the new template_files database record.
	$column_names = '`site_id`, `templates_id`, `status`, `name`, `filename`, `php_array`, `file_code`, `template_type`, `assigned_type`, `default_file`, `copy_template_file`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
	$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';
	$parameters = [$site_id, $template_id, $template_file_record['status'], $new_name, $new_filename, $template_file_record['php_array'], $template_file_record['file_code'], $template_file_record['template_type'], $template_file_record['assigned_type'], 'No', $template_file_record['copy_template_file'], $template_file_record['custom_fields'], $current_user_name, $current_user_name];
	
	try
	{
		$inserted_id = $_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'template_files', $column_names, $placeholders, $parameters);
	}
	catch(Throwable $e)
	{
		error_log('Template file copy database insert failed: '.$e->getMessage());
		$inserted_id = 0;
	}
	
	//If the DB insert failed, remove the physical copy so we do not leave an orphaned file.
	if(empty($inserted_id))
	{
		if(file_exists($new_file_path))
		{
			if(!unlink($new_file_path))
			{
				error_log('Ratals could not remove copied template file after failed database insert: '.$new_file_path);
			}
		}
	
		echo json_encode([
			'success' => false,
			'message' => 'The template file was copied, but the new database record could not be created.'
		]);
		exit();
	}
	
	//Update the Template File count on the parent template.
	$template_file_count = $_SESSION['results']->getSelectCountRecords(__LINE__, __FILE__, '*', 'template_files', 'WHERE `site_id` = ? AND `templates_id` = ?', [$site_id, $template_id]);
	
	$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, 'templates', '`sub_items` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$template_file_count, $template_id, $site_id]);
	
	//Return the new Edit URL to JavaScript.
	echo json_encode([
		'success' => true,
		'template_id' => $template_id,
		'template_file_id' => $inserted_id,
		'name' => $new_name,
		'filename' => $new_filename,
		'redirect' => '/'.$_SESSION['admin_directory'].'/website/template-files/edit/?sub-page-rid='.$template_id.'&rid='.$inserted_id.'&copied=success'
	]);
	
	exit();
}