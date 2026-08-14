<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/cf-display-as/dropdownId.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/cf-display-as/dropdownId.php');
}
else
{
	if(!class_exists('dropdownIdTcfda'))
	{
		class dropdownIdTcfda
		{
			public function dropdownIdTcfda($sql_custom_fields_rows, $sql_account_columns_active, $label_names, $domain)
			{
				//Decode attribute frontend and admin names
				$custom_field_name_main = array();
				if(isset($sql_account_columns_active['custom_field_name']) && !empty($sql_account_columns_active['custom_field_name']))
				{
					$custom_field_name_main = JSON_DECODE($sql_account_columns_active['custom_field_name'] ?? '', true);
				}
				
				$custom_field_column_frontend_name = 'Not Set';
				if(isset($custom_field_name_main[$_SESSION['admin_language']]['frontend_name']) && !empty($custom_field_name_main[$_SESSION['admin_language']]['frontend_name']))
				{
					$custom_field_column_frontend_name = $custom_field_name_main[$_SESSION['admin_language']]['frontend_name'];
				}
				
				$custom_field_column_admin_name = 'Not Set';
				if(isset($custom_field_name_main[$_SESSION['admin_language']]['admin_name']) && !empty($custom_field_name_main[$_SESSION['admin_language']]['admin_name']))
				{
					$custom_field_column_admin_name = $custom_field_name_main[$_SESSION['admin_language']]['admin_name'];
				}
				
				//Decode attribute label and value names
				$custom_field_name = array();
				if(isset($sql_custom_fields_rows['custom_fields']) && !empty($sql_custom_fields_rows['custom_fields']))
				{
					$custom_field_name = JSON_DECODE($sql_custom_fields_rows['custom_fields'] ?? '', true);
				}
				
				$custom_field_option_name = 'Not Set';
				if(isset($custom_field_name[$sql_account_columns_active["column_name"]]) && !empty($custom_field_name[$sql_account_columns_active["column_name"]]) && isset($label_names['custom_fields_options'][$custom_field_name[$sql_account_columns_active["column_name"]]]['label']) && !empty($label_names['custom_fields_options'][$custom_field_name[$sql_account_columns_active["column_name"]]]['label']))
				{
					$custom_field_option_name = $label_names['custom_fields_options'][$custom_field_name[$sql_account_columns_active["column_name"]]]['label'];
				}
				
				$custom_field_url_name = 'Not Set';
				if(isset($custom_field_name[$sql_account_columns_active["column_name"]]) && !empty($custom_field_name[$sql_account_columns_active["column_name"]]) && isset($label_names['custom_fields_options'][$custom_field_name[$sql_account_columns_active["column_name"]]]['value']) && !empty($label_names['custom_fields_options'][$custom_field_name[$sql_account_columns_active["column_name"]]]['value']))
				{
					$custom_field_url_name = $label_names['custom_fields_options'][$custom_field_name[$sql_account_columns_active["column_name"]]]['value'];
				}
				
				$custom_field_value_set = '';
				if(isset($custom_field_name[$sql_account_columns_active["column_name"]]) && !empty($custom_field_name[$sql_account_columns_active["column_name"]]))
				{
					$custom_field_value_set = $custom_field_name[$sql_account_columns_active["column_name"]];
				}
				
				echo '<li class="table-cell-results"><span class="white-space-nowrap">Type: '.$sql_account_columns_active["field_type"].'</span><br>
				<span class="white-space-nowrap">Display As: '.$sql_account_columns_active["cf_display_as"].'</span><br>
				<span class="white-space-nowrap">Language: '.$_SESSION['admin_language'].'</span><br>
				<span class="white-space-nowrap">Frontend Name: '.$custom_field_column_frontend_name.'</span><br>
				<span class="white-space-nowrap">URL Name: '.$custom_field_column_admin_name.'</span><br>
				<span class="white-space-nowrap">Label: '.$custom_field_option_name.'</span><br>
				<span class="white-space-nowrap">Value: '.$custom_field_url_name.'</span><br>
				<span class="white-space-nowrap">ID: '.$custom_field_value_set.'</span></li>';
			}
		}
		
		$class_dropdownIdTcfda = new dropdownIdTcfda();
	}
	
	$class_dropdownIdTcfda->dropdownIdTcfda($sql_custom_fields_rows, $sql_account_columns_active, $label_names, $domain);
}