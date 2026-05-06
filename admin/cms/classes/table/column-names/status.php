<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/column-names/status.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/column-names/status.php');
}
else
{
	if(!class_exists('status_tcn'))
	{
		class status_tcn
		{
			public function status_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				if($sql_custom_fields_rows['status'] == 1) 
				{ 
					$status = "Enabled"; 
				}
				else
				{ 
					$status = "Disabled"; 
				}
				echo '<li class="table-cell-results table-status"><div class="status changeActive" data-click="'.$sql_custom_fields_rows['id'].','.$sql_custom_fields_rows['status'].','.$_SESSION['admin_table_name'].'">'.$status.'</div></li>';
			}
		}
		
		$class_status_tcn = new status_tcn();
	}
	
	$class_status_tcn->status_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}