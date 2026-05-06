<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-form-field-options.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/ajax/delete-form-field-options.php');
}
else
{
	//Delete Forms Field Options
	if(isset($_POST['deleteRow']) && !empty($_POST['deleteRow']) && $_POST['type'] == 'delete-form-field-options')
	{
		foreach($_POST['deleteRow'] as $delete_form_field_options_row_id)
		{
			$sql_get_form_field_option_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_values', 'WHERE `id` = ? LIMIT 1', [$delete_form_field_options_row_id]);
			
			if(!empty($sql_get_form_field_option_data))
			{
				$results->getDeleteRecord(__LINE__, __FILE__, 'form_values', 'WHERE `id` = ?', [$sql_get_form_field_option_data['id']]);
				
				//Get form options count
				$sql_form_option_count = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ?', [$sql_get_form_field_option_data['form_fields_id']]);
				
				//Update form options count
				$results->getUpdateRecord(__LINE__, __FILE__, 'form_fields', '`sub_items` = ?, `updated_date` = UTC_TIMESTAMP(),`updated_by` = ?', 'WHERE `id` = ?', [$sql_form_option_count, $_SESSION['user_first_last_name'], $sql_get_form_field_option_data['form_fields_id']]);
			}
		}
		
		echo "1";
		exit;
	}
}