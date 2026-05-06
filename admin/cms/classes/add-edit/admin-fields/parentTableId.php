<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/parentTableId.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/parentTableId.php');
}
else
{
	if(!class_exists('parentTableIdAeaf'))
	{
		class parentTableIdAeaf
		{
			public function parentTableIdAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				//If admin_fields page in admin for $admin_field['column_name'] == "admin_fields_lists_system_code" display it as a dropdown with all list names.
				if($_SESSION['admin_table_name'] == "admin_fields" && $admin_field['column_name'] == "admin_fields_lists_system_code")
				{
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
					<div class="edit-field">
					<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
					<option value="0"></option>
					';
					
					//get values from admin_fields_values table.
					$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_lists', 'ORDER BY `name` ASC', []);
					
					if(!empty($admin_field_values))
					{
						foreach($admin_field_values as $admin_field_value)
						{
							$selected_item = '';
							
							$admin_field_id = $admin_field_value["id"];
							$admin_field_label = $admin_field_value["name"];
							
							if($field_value == $admin_field_id)
							{
								$selected_item = " selected";
							}
							
							echo '<option value="'.htmlspecialchars($admin_field_id ?? '').'"'.$selected_item.'>'.htmlspecialchars($admin_field_label ?? '').'</option>';
						}
					}
					echo '
					</select>
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';				
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>'; 
				}
				//This gets the parent table name with _id if it is a sub_page like: admin_menus_id, custom_fields_id.
				elseif(!empty($_SESSION['admin_table_link_column']) && !empty($_SESSION['admin_parent_table_name']) && $admin_field['column_name'] == $_SESSION['admin_table_link_column'])
				{
					if($_SESSION['admin_type'] == 'add')
					{
						echo '
						<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars(trim($_GET["rid"] ?? '')).'">';
					}
					else
					{
						echo '
						<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
					}
				}
			}
		}
		
		$class_parentTableIdAeaf = new parentTableIdAeaf();
	}
	
	$class_parentTableIdAeaf->parentTableIdAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}