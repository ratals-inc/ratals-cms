<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/column-names/url_extension.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/column-names/url_extension.php');
}
else
{
	if(!class_exists('url_extension_tcn'))
	{
		class url_extension_tcn
		{
			public function url_extension_tcn($sql_custom_fields_rows, $sql_account_columns_active, $sites)
			
			{
				//URL Extensions
				if(!empty($sql_custom_fields_rows["url_extension"]))
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$sql_custom_fields_rows["url_extension"].'</li>';
				}
				else
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">Global Setting: '.$sites["global_url_extension"].'</li>';
				}
			}
		}
		
		$class_url_extension_tcn = new url_extension_tcn();
	}
	
	$class_url_extension_tcn->url_extension_tcn($sql_custom_fields_rows, $sql_account_columns_active, $sites);
}