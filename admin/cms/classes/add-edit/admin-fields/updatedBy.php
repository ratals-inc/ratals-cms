<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/updatedBy.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/updatedBy.php');
}
else
{
	if(!class_exists('updatedByAeaf'))
	{
		class updatedByAeaf
		{
			public function updatedByAeaf($table_name, $admin_field, $field_value)
			{
				if($_SESSION['admin_type'] == 'edit')
				{
					echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
					<div class="edit-field text">';
					echo $field_value;
					echo '<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
					echo '<div class="small-text">'.$admin_field["notes"].'</div>';
					echo '</div>
					</div>';
				}
			}
		}
		
		$class_updatedByAeaf = new updatedByAeaf();
	}
	
	$class_updatedByAeaf->updatedByAeaf($table_name, $admin_field, $field_value);
}