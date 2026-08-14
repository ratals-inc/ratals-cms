<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/display-as/dropdownValue.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/display-as/dropdownValue.php');
}
else
{
	if(!class_exists('dropdownValueTda'))
	{
		class dropdownValueTda
		{
			public function dropdownValueTda($sql_custom_fields_rows, $sql_account_columns_active, $dynamic_options_list, $label_names)
			{
				if($sql_account_columns_active['admin_fields_lists_system_code'] && (!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]) || is_numeric($sql_custom_fields_rows[$sql_account_columns_active["column_name"]])))
				{
					$option_label = ''; 
					if(!empty($dynamic_options_list))
					{
						if($dynamic_options_list[$sql_account_columns_active['admin_fields_lists_system_code']]['dynamic'] == 'Yes')
						{
							if(isset($label_names[$dynamic_options_list[$sql_account_columns_active['admin_fields_lists_system_code']]['dynamic_table_name'].'_'.$dynamic_options_list[$sql_account_columns_active['admin_fields_lists_system_code']]['system_code']][$sql_custom_fields_rows[$sql_account_columns_active["column_name"]]][$dynamic_options_list[$sql_account_columns_active['admin_fields_lists_system_code']]["dynamic_column_label"]]))
							{
								$option_label .= $label_names[$dynamic_options_list[$sql_account_columns_active['admin_fields_lists_system_code']]['dynamic_table_name'].'_'.$dynamic_options_list[$sql_account_columns_active['admin_fields_lists_system_code']]['system_code']][$sql_custom_fields_rows[$sql_account_columns_active["column_name"]]][$dynamic_options_list[$sql_account_columns_active['admin_fields_lists_system_code']]["dynamic_column_label"]];
							}
						}
						//Dropdown means, when using ids, this looks up the row with the id.
						elseif($sql_account_columns_active["display_as"] == "dropdownId" && isset($label_names['admin_fields_values'][$sql_custom_fields_rows[$sql_account_columns_active["column_name"]]]['label']))
						{
								$option_label = $label_names['admin_fields_values'][$sql_custom_fields_rows[$sql_account_columns_active["column_name"]]]['label'];
								
						}
						elseif(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
						{
							$option_label = $sql_custom_fields_rows[$sql_account_columns_active["column_name"]];
						}
					}
					
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$option_label.'</li>';
				}
				else
				{
					$else_table_content = ''; 
					if(!empty(substr($sql_custom_fields_rows[$sql_account_columns_active["column_name"]] ?? '', 0, 100)))
					{
						$else_table_content = substr($sql_custom_fields_rows[$sql_account_columns_active["column_name"]] ?? '', 0, 100);
					}
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.htmlspecialchars($else_table_content ?? '').'</li>';
				}
			}
		}
		
		$class_dropdownValueTda = new dropdownValueTda();
	}
	
	$class_dropdownValueTda->dropdownValueTda($sql_custom_fields_rows, $sql_account_columns_active, $dynamic_options_list, $label_names);
}