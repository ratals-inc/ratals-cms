<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/data-type/date.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/data-type/date.php');
}
else
{
	if(!class_exists('date_tdt'))
	{
		class date_tdt
		{
			public function date_tdt($sql_custom_fields_rows, $sql_account_columns_active, $tables_with_urls_id)
			{
				//When data_type is Date, just display the date set in the database
				$display_date  = '';
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
				{
					$display_date = submittedDate($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]);
				}
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$display_date.'</li>';
			}
		}
		
		$class_date_tdt = new date_tdt();
	}
	
	$class_date_tdt->date_tdt($sql_custom_fields_rows, $sql_account_columns_active, $tables_with_urls_id);
}