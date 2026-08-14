<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/display-as/priceAsText.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/display-as/priceAsText.php');
}
else
{
	if(!class_exists('priceAsTextTda'))
	{
		class priceAsTextTda
		{
			public function priceAsTextTda($sql_custom_fields_rows, $sql_account_columns_active)
			{
				//update prices to show in currency format
				$table_price = ''; 
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]]))
				{
					$currency_zeros_after_separator = $_SESSION['currency_zeros_after_separator'];
					if($sql_account_columns_active["financial_field"] == 'Yes')
					{
						$currency_zeros_after_separator = 6;
					}
					
					$table_price = number_format((float)$sql_custom_fields_rows[$sql_account_columns_active["column_name"]], $currency_zeros_after_separator, $_SESSION['currency_fractional_separator'], '');
				}
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$table_price.'</li>';
			}
		}
		
		$class_priceAsTextTda = new priceAsTextTda();
	}
	
	$class_priceAsTextTda->priceAsTextTda($sql_custom_fields_rows, $sql_account_columns_active);
}