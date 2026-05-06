<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/display-as/optionData.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/display-as/optionData.php');
}
else
{
	if(!class_exists('optionDataTda'))
	{
		class optionDataTda
		{
			public function optionDataTda($sql_custom_fields_rows, $sql_account_columns_active)
			{
				$option_data_label = ''; 
				$option_data_value = ''; 
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
				{
					$option_data = JSON_DECODE($sql_custom_fields_rows[$sql_account_columns_active["column_name"]] ?? '', true);
					
					if(isset($option_data[$sql_account_columns_active["language"]]['label']) && !empty($option_data[$sql_account_columns_active["language"]]['label']))
					{
						$option_data_label = $option_data[$sql_account_columns_active["language"]]['label'];
					}
					
					if(isset($option_data[$sql_account_columns_active["language"]]['value']) && !empty($option_data[$sql_account_columns_active["language"]]['value']))
					{
						$option_data_value = $option_data[$sql_account_columns_active["language"]]['value'];
					}
				}
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">Label: '.htmlspecialchars($option_data_label ?? '').'<br>Value: '.htmlspecialchars($option_data_value ?? '').'</li>';
			}
		}
		
		$class_optionDataTda = new optionDataTda();
	}
	
	$class_optionDataTda->optionDataTda($sql_custom_fields_rows, $sql_account_columns_active);
}