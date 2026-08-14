<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/path_level.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/path_level.php');
}
else
{
	if(!class_exists('path_level_tcn'))
	{
		class path_level_tcn
		{
			public function path_level_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				//Display sub categories to a category
				if(!empty($sql_custom_fields_rows['path_level']))
				{
					$new_path_level_link = $sql_custom_fields_rows['path_level'].$sql_custom_fields_rows['id'].'/';
					
					$sql_get_sub_categories_count = $_SESSION['results']->getSelectCountRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `path_level` = ?', [$_SESSION["site_set_for_editing"], $new_path_level_link]);
				}
				else
				{
					$new_path_level_link = $sql_custom_fields_rows['id'].'/';
					
					$sql_get_sub_categories_count = $_SESSION['results']->getSelectCountRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `path_level` = ?', [$_SESSION["site_set_for_editing"], $new_path_level_link]);
				}
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><a href="/'.$_SESSION['admin_sub_items_url'].'/?layout=hierarchy&path-ids='.$new_path_level_link.'">'.$sql_get_sub_categories_count.' URL Sub Item(s)</a></li>';
			}
		}
		
		$class_path_level_tcn = new path_level_tcn();
	}
	
	$class_path_level_tcn->path_level_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}