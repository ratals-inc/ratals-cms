<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/data-type/datetime.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/data-type/datetime.php');
}
else
{
	if(!class_exists('datetime_tdt'))
	{
		class datetime_tdt
		{
			public function datetime_tdt($sql_custom_fields_rows, $sql_account_columns_active)
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
		
		$class_datetime_tdt = new datetime_tdt();
	}
	
	$class_datetime_tdt->datetime_tdt($sql_custom_fields_rows, $sql_account_columns_active);
}