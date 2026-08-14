<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/linkType.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/linkType.php');
}
else
{
	if(!class_exists('linkTypeAeaf'))
	{
		class linkTypeAeaf
		{
			public function linkTypeAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				//Set URL styles for gray box if table is for urls.
				$class_for_urls_table_fields = '';
				
				if($table_name == 'urls')
				{
					//If URLs, add class to for gray / padding.
					$class_for_urls_table_fields = ' url';
				}
				
				echo '
				<div class="edit'.$class_for_urls_table_fields.' '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">
				<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<option value=""></option>
				';
				
				//get values from admin_fields_values table.
				$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$admin_field["admin_fields_lists_system_code"]]);
				
				if(!empty($admin_field_values))
				{
					foreach($admin_field_values as $admin_field_value)
					{
						$selected_item = '';
						
						$admin_field_id = $admin_field_value["value"];
						$admin_field_label = $admin_field_value["label"];
						
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
		}
		
		$class_linkTypeAeaf = new linkTypeAeaf();
	}
	
	$class_linkTypeAeaf->linkTypeAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}