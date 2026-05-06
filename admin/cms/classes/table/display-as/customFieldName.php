<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/display-as/customFieldName.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/display-as/customFieldName.php');
}
else
{
	if(!class_exists('customFieldNameTda'))
	{
		class customFieldNameTda
		{
			public function customFieldNameTda($sql_custom_fields_rows, $sql_account_columns_active)
			{
				$custom_field_name_frontend = '';
				$custom_field_name_admin = '';
				
				if($sql_custom_fields_rows['assigned_to'] == 'custom_fields_global')
				{
					$custom_field_name_frontend = 'N/A';
					$custom_field_name_admin = 'N/A';
				}
				
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
				{
					$custom_field_name = JSON_DECODE($sql_custom_fields_rows[$sql_account_columns_active["column_name"]] ?? '', true);
					
					if(isset($custom_field_name[$sql_account_columns_active["language"]]['frontend_name']) && !empty($custom_field_name[$sql_account_columns_active["language"]]['frontend_name']))
					{
						$custom_field_name_frontend = $custom_field_name[$sql_account_columns_active["language"]]['frontend_name'];
					}
					
					if(isset($custom_field_name[$sql_account_columns_active["language"]]['admin_name']) && !empty($custom_field_name[$sql_account_columns_active["language"]]['admin_name']))
					{
						$custom_field_name_admin = $custom_field_name[$sql_account_columns_active["language"]]['admin_name'];
					}
				}
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">Frontend Name: '.htmlspecialchars($custom_field_name_frontend ?? '').'<br>URL Name: '.htmlspecialchars($custom_field_name_admin ?? '').'</li>';
			}
		}
		
		$class_customFieldNameTda = new customFieldNameTda();
	}
	
	$class_customFieldNameTda->customFieldNameTda($sql_custom_fields_rows, $sql_account_columns_active);
}