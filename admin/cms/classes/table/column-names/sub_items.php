<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/sub_items.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/sub_items.php');
}
else
{
	if(!class_exists('sub_items_tcn'))
	{
		class sub_items_tcn
		{
			public function sub_items_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				static $sub_items_labels;
				
				if(!isset($sub_items_labels))
				{
					$sub_items_labels = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ?', ['sub_items_labels'], 'value');
				}
				
				$sub_items_label = 'Sub Items';
				if(isset($sub_items_labels[$_SESSION['admin_table_name']]['label']) && !empty($sub_items_labels[$_SESSION['admin_table_name']]['label']))
				{
					$sub_items_label = $sub_items_labels[$_SESSION['admin_table_name']]['label'];
				}
				
				//display sub item data
				$table_menu_items = '0'; 
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
				{							
					$table_menu_items = $sql_custom_fields_rows[$sql_account_columns_active["column_name"]];
				}
				
				if($_SESSION['record_has_url'] == 'Yes' && isset($sql_custom_fields_rows['record_id']) && !empty($sql_custom_fields_rows['record_id']))
				{
					$sub_items = 'rid='.$sql_custom_fields_rows['record_id'];
				}
				elseif($_SESSION['admin_sub_page'] == 'Yes')
				{
					$sub_items = 'rid='.trim($_GET["rid"] ?? '').'&sub-rid='.$sql_custom_fields_rows['id'];
				}
				else
				{
					$sub_items = 'rid='.$sql_custom_fields_rows['id'];
				}
				
				if(isset($sql_custom_fields_rows['display_as']) && ($sql_custom_fields_rows['display_as'] == 'textfield' || $sql_custom_fields_rows['display_as'] == 'textareaWithEditor' || $sql_custom_fields_rows['display_as'] == 'multipleMedia' || $sql_custom_fields_rows['display_as'] == 'singleMedia' || $sql_custom_fields_rows['display_as'] == 'textarea'))
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">N/A</li>';
				}
				elseif(isset($sql_custom_fields_rows['form_field_type']) && ($sql_custom_fields_rows['form_field_type'] == 'Textfield' || $sql_custom_fields_rows['form_field_type'] == 'Textarea'))
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">N/A</li>';
				}
				else
				{
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><a href="/'.$_SESSION['admin_sub_items_url'].'/?'.$sub_items.'">'.$table_menu_items.' '.htmlspecialchars($sub_items_label ?? '').'</a></li>';
				}
			}
		}
		
		$class_sub_items_tcn = new sub_items_tcn();
	}
	
	$class_sub_items_tcn->sub_items_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}