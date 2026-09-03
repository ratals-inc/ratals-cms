<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/form-fields-assigned.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/form-fields-assigned.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'form_fields_assigned')
	{
		$sql_record_data_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'forms', 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
		
		$db_id = $sql_record_data_rows["id"];
		$db_form_fields_ids = $sql_record_data_rows["form_fields_ids"];
		$db_forms_created_date = $sql_record_data_rows["created_date"];
		$db_forms_created_by = $sql_record_data_rows["created_by"];
		$db_forms_last_modified_date = $sql_record_data_rows["updated_date"];
		$db_forms_last_modified_by = $sql_record_data_rows["updated_by"];
		
		$form_fields_assigned_ids = array();
		
		if(!empty($sql_record_data_rows['form_fields_ids']))
		{
			if(strpos($sql_record_data_rows['form_fields_ids'], ',') !== false)
			{
				$form_fields_ids = explode(',', $sql_record_data_rows['form_fields_ids']);
				
				foreach($form_fields_ids as $form_fields_id)
				{
					$form_fields_assigned_ids[] = $form_fields_id;
				}
			}
			else
			{
				$form_fields_assigned_ids[] = $sql_record_data_rows['form_fields_ids'];
			}
		}
		
		$form_fields_assigned_array = array();
		
		if(!empty($form_fields_assigned_ids))
		{
			foreach($form_fields_assigned_ids as $form_fields_id)
			{
				$sql_get_form_fields = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [$form_fields_id]);
				
				if(!empty($sql_get_form_fields))
				{
					$form_fields_assigned_array[] = $sql_get_form_fields;
				}
			}
		}
		
		if(isset($_POST['add_from_fields']))
		{
			$post_form_fields = array();
			$post_form_fields = $_POST["form_fields"];
			
			$update_form_fields = '';
			
			if(!empty($post_form_fields))
			{
				foreach($post_form_fields as $post_attribute)
				{
					$update_form_fields .= $post_attribute.',';
				}
				$update_form_fields = trim($update_form_fields ?? '', ',');
			}
			
			$results->getUpdateRecord(__LINE__, __FILE__, 'forms', '`form_fields_ids` = ?, `updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', 'WHERE `id` = ?', [$update_form_fields, $_SESSION['user_username'], $db_id]);
			
			//Clear cache on save.
			if($_SESSION['admin_site_id_global'] == 'No')
			{
				clearSiteCache($_SESSION['site_set_for_editing']);
			}
			else
			{
				clearAllSiteCache();
			}
			
			header("Location: ".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&updated=success"); exit();
		}
	}
}