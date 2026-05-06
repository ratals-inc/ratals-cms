<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/get-fields.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/get-fields.php');
}
else
{
	//Declare $current_values for add page as get-values.php does not load in add admin page.
	$current_values = array();
	
	//Query db for all admin_fields associated with the record id set in url.
	$admin_fields_id = explode(',', $_SESSION['admin_fields_ids']);
	$admin_fields_ids = array_merge($admin_fields_id, $admin_fields_id);
	$clause = implode(',', array_fill(0, count($admin_fields_id), '?'));
	$all_admin_fields = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` IN ('.$clause.') ORDER BY FIELD(`id`, '.$clause.')', $admin_fields_ids, 'column_name');
	
	$admin_fields = array();
	if(!empty($all_admin_fields))
	{
		$admin_fields[$_SESSION['admin_table_name']] = $all_admin_fields;
	}
	
	//If admin_pages has a url_id, get the url data from urls table.
	$admin_fields_has_url = 'No';
	if(isset($admin_fields[$_SESSION['admin_table_name']]) && array_key_exists('urls_id', $admin_fields[$_SESSION['admin_table_name']]))
	{	
		$admin_fields_has_url = 'Yes';
		$admin_fields['urls'] = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` IN ('.$_SESSION['admin_urls_column_placeholders'].') ORDER BY FIELD(`id`, '.$_SESSION['admin_urls_column_placeholders'].')', $_SESSION['admin_urls_column_ids_doubled'], 'column_name');
		
		//flip order so URLs display first.
		$admin_fields = array_reverse($admin_fields);
	}
	//echo '<pre>'; print_r($admin_fields); echo '</pre>';
}