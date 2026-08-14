<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/lead.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/lead.php');
}
else
{
	if(!class_exists('lead_tcn'))
	{
		class lead_tcn
		{
			public function lead_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				//Display form lead correctly
				$lead_content = ''; 
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
				{
					$lead_content = htmlspecialchars($sql_custom_fields_rows[$sql_account_columns_active["column_name"]] ?? '');
					$lead_content = str_replace('&lt;br&gt;', '<br>', $lead_content);
					$lead_content = str_replace('&lt;strong&gt;', '', $lead_content);
					$lead_content = str_replace('&lt;/strong&gt;', '', $lead_content);
				}
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.substr($lead_content, 0, 250).'</li>';
			}
		}
		
		$class_lead_tcn = new lead_tcn();
	}
	
	$class_lead_tcn->lead_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}