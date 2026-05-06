<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/parentId.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/parentId.php');
}
else
{
	if(!class_exists('parentIdAeaf'))
	{
		class parentIdAeaf
		{
			public function parentIdAeaf($table_name, $admin_field, $field_value)
			{
				if($_SESSION['admin_type'] == 'add')
				{
					if(isset($_GET["sub-rid"]) && is_numeric(trim($_GET["sub-rid"] ?? '')))
					{
						echo '
						<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars(trim($_GET["sub-rid"] ?? '')).'">';
					}
					else
					{
						echo '
						<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="0">';
					}
				}
				else
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
			}
		}
		
		$class_parentIdAeaf = new parentIdAeaf();
	}
	
	$class_parentIdAeaf->parentIdAeaf($table_name, $admin_field, $field_value);
}
