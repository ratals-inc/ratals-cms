<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/column-names/canonical_url.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/column-names/canonical_url.php');
}
else
{
	if(!class_exists('canonical_url_tcn'))
	{
		class canonical_url_tcn
		{
			public function canonical_url_tcn($sql_custom_fields_rows, $sql_account_columns_active, $sites)
			{
				//Canonical URL's
				if(!empty($sql_custom_fields_rows["canonical_url"]))
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$sql_custom_fields_rows["canonical_url"].'</li>';
				}
				elseif($sites["auto_generate_canonical_url"] == 'Yes')
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">Global Setting: Auto Generate</li>';
				}
				else
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">Global Setting: Don\'t Auto Generate</li>';
				}
			}
		}
		
		$class_canonical_url_tcn = new canonical_url_tcn();
	}
	
	$class_canonical_url_tcn->canonical_url_tcn($sql_custom_fields_rows, $sql_account_columns_active, $sites);
}