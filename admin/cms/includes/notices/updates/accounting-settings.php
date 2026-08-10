<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//UPDATE ACCOUNTING_SETTING DEFAULTS ON ACCOUNT UPGRADE TO ERP.
try
{
	if(in_array('accounting_settings', $existing_database_tables))
	{
		$accounting_settings_to_update = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'accounting_settings', 'LIMIT 1', []);
		
		if(!empty($accounting_settings_to_update))
		{
			if(isset($accounting_settings_to_update['id']) && !empty($accounting_settings_to_update['id']) && isset($accounting_settings_to_update['erp_mode']) && empty($accounting_settings_to_update['erp_mode']) && isset($accounting_settings_to_update['cost_of_goods_sold_method']) && empty($accounting_settings_to_update['cost_of_goods_sold_method']) && isset($accounting_settings_to_update['landed_cost_method']) && empty($accounting_settings_to_update['landed_cost_method']))
			{
				$update_columns = '`erp_mode` = ?, `cost_of_goods_sold_method` = ?, `landed_cost_method` = ?';
				$update_where_clause = 'WHERE `id` = ?';
				$fields_to_update = array('Disabled', 'FIFO', 'Cost', $accounting_settings_to_update['id']);
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'accounting_settings', $update_columns, $update_where_clause, $fields_to_update);
			}
		}
	}
	
	writeToInstallLog('Completed accounting settings update check.');
}
catch(\Throwable $e)
{
	writeToInstallLog('Failed accounting settings update: '.$e->getMessage());
}