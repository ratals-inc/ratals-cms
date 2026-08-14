<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/url_status.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/url_status.php');
}
else
{
	if(!class_exists('url_status_tcn'))
	{
		class url_status_tcn
		{
			public function url_status_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				if($sql_custom_fields_rows['url_status'] == '1') 
				{ 
					$status = "Enabled"; 
				} 
				elseif($sql_custom_fields_rows['url_status'] == '3')
				{ 
					$status = "Draft"; 
				}
				elseif($sql_custom_fields_rows['url_status'] == '4')
				{ 
					$status = "Scheduled"; 
				}
				else
				{
					//$sql_custom_fields_rows['url_status'] == '2'
					$status = "Disabled";
				}
				echo '<li class="table-cell-results table-status"><div class="status changeActive" data-click="'.$sql_custom_fields_rows['id'].','.$sql_custom_fields_rows['url_status'].',urls">'.$status.'</div></li>';
			}
		}
		
		$class_url_status_tcn = new url_status_tcn();
	}
	
	$class_url_status_tcn->url_status_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}