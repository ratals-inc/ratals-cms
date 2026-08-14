<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/meta_robots.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/meta_robots.php');
}
else
{
	if(!class_exists('meta_robots_tcn'))
	{
		class meta_robots_tcn
		{
			public function meta_robots_tcn($sql_custom_fields_rows, $sql_account_columns_active, $search_engines_settings)
			{
				//Meta Robots
				if(!empty($sql_custom_fields_rows["meta_robots"]))
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$sql_custom_fields_rows["meta_robots"].'</li>';
				}
				else
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">Global Setting: '.$search_engines_settings["meta_robots"].'</li>';
				}
			}
		}
		
		$class_meta_robots_tcn = new meta_robots_tcn();
	}
	
	$class_meta_robots_tcn->meta_robots_tcn($sql_custom_fields_rows, $sql_account_columns_active, $search_engines_settings);
}