<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/links_to.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/links_to.php');
}
else
{
	if(!class_exists('links_to_tcn'))
	{
		class links_to_tcn
		{
			public function links_to_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				//Links To
				$links_to_data = '';
				if(!empty($sql_custom_fields_rows['links_to'])) 
				{
					$links_to_data = '<a href="'.$sql_custom_fields_rows['links_to_url'].'" target="_blank">ID: '.$sql_custom_fields_rows['links_to'].' - '.$sql_custom_fields_rows['meta_title'].'</a>';
				}
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$links_to_data.'</li>';
			}
		}
		
		$class_links_to_tcn = new links_to_tcn();
	}
	
	$class_links_to_tcn->links_to_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}