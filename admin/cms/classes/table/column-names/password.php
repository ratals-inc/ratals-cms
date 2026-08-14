<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/password.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/password.php');
}
else
{
	if(!class_exists('password_tcn'))
	{
		class password_tcn
		{
			public function password_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				//Hide password for security.
				$password_placeholder = '';
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]])) { $password_placeholder = '**********'; }
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$password_placeholder.'</li>';
			}
		}
		
		$class_password_tcn = new password_tcn();
	}
	
	$class_password_tcn->password_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}