<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/createdDate.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/createdDate.php');
}
else
{
	if(!class_exists('createdDateAeaf'))
	{
		class createdDateAeaf
		{
			public function createdDateAeaf($table_name, $admin_field, $field_value)
			{
				if($_SESSION['admin_type'] == 'edit')
				{
					$show_date = '';
					if(!empty($field_value))
					{
						$show_date = utcToUserTimeZone($field_value, 'F d, Y - g:i:s A');
					}
					
					$field_required = '';
					if($admin_field["required"] == 'Yes')
					{
						$field_required = ' <span class="required-asterisk">*</span>';
					}
					
					echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="edit-field text">';
					echo $show_date;
					echo '<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
					echo '<div class="small-text">'.$admin_field["notes"].'</div>';
					echo '</div>
					</div>';
				}
			}
		}
		
		$class_createdDateAeaf = new createdDateAeaf();
	}
	
	$class_createdDateAeaf->createdDateAeaf($table_name, $admin_field, $field_value);
}