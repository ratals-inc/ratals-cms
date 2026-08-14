<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/assigned-type/assign_products_to_category.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/assigned-type/assign_products_to_category.php');
}
else
{
	if(!class_exists('assign_products_to_category_tat'))
	{
		class assign_products_to_category_tat
		{
			public function assign_products_to_category_tat($sql_custom_fields_rows, $sql_account_columns_active)
			{
				if(!isset($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]) && $sql_account_columns_active['column_name'] != 'url_status')
				{
					$assign_products_table_content = ''; 
					if(!isset($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
					{
						$assign_products_table_content = 'N/A';
					}
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$assign_products_table_content.'</li>';
				}
				elseif($sql_account_columns_active['column_name'] == 'url_status' || $sql_account_columns_active['column_name'] == 'status')
				{
					if(isset($sql_custom_fields_rows['url_status']) && $sql_custom_fields_rows['url_status'] == 1) 
					{ 
						$status = "Enabled"; 
						$status_code = $sql_custom_fields_rows['url_status'];
					} 
					elseif(isset($sql_custom_fields_rows['status']) && $sql_custom_fields_rows['status'] == 1)
					{ 
						$status = "Enabled";
						$status_code = $sql_custom_fields_rows['status'];
					}
					elseif(isset($sql_custom_fields_rows['url_status']) && $sql_custom_fields_rows['url_status'] == 2)
					{ 
						$status = "Disabled"; 
						$status_code = $sql_custom_fields_rows['url_status'];
					}
					elseif(isset($sql_custom_fields_rows['status']) && $sql_custom_fields_rows['status'] == 2)
					{ 
						$status = "Disabled"; 
						$status_code = $sql_custom_fields_rows['status'];
					}
					
					if($sql_custom_fields_rows['product_type'] == 'Product: Inventory Items')
					{
						$assigned_type = 1;
					}
					elseif($sql_custom_fields_rows['product_type'] == 'Product: Sub Products')
					{
						$assigned_type = 3;
					}
					else
					{
						$assigned_type = 2;
					}
					echo '<li class="table-cell-results table-status"><div class="status changeActiveProdInven" data-click="'.$sql_custom_fields_rows['id'].','.$status_code.','.$assigned_type.'">'.$status.'</div></li>';
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
		
		$class_assign_products_to_category_tat = new assign_products_to_category_tat();
	}
	
	$class_assign_products_to_category_tat->assign_products_to_category_tat($sql_custom_fields_rows, $sql_account_columns_active);
}