<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/data-type/timestamp.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/data-type/timestamp.php');
}
else
{
	if(!class_exists('timestamp_tdt'))
	{
		class timestamp_tdt
		{
			public function timestamp_tdt($sql_custom_fields_rows, $sql_account_columns_active, $tables_with_urls_id)
			{
				//When data_type is DateTime or TimeStamp, adjust the value/date with the timezone set in admin.
				$display_date  = '';
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
				{
					$display_date = utcToUserTimeZone($sql_custom_fields_rows[$sql_account_columns_active["column_name"]], 'F d, Y');
				}
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$display_date.'</li>';
			}
		}
		
		$class_timestamp_tdt = new timestamp_tdt();
	}
	
	$class_timestamp_tdt->timestamp_tdt($sql_custom_fields_rows, $sql_account_columns_active, $tables_with_urls_id);
}