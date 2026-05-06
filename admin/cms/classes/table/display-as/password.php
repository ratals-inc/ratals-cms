<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/display-as/password.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/display-as/password.php');
}
else
{
	if(!class_exists('passwordTda'))
	{
		class passwordTda
		{
			public function passwordTda($sql_custom_fields_rows, $sql_account_columns_active)
			{
				//Hide password for security.
				$password_placeholder = '';
				if(!empty($sql_custom_fields_rows[$sql_account_columns_active["column_name"]])) { $password_placeholder = '**********'; }
				echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.$password_placeholder.'</li>';
			}
		}
		
		$class_passwordTda = new passwordTda();
	}
	
	$class_passwordTda->passwordTda($sql_custom_fields_rows, $sql_account_columns_active);
}