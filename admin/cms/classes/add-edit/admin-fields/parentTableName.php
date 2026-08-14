<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/parentTableName.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/parentTableName.php');
}
else
{
	if(!class_exists('parentTableNameAeaf'))
	{
		class parentTableNameAeaf
		{
			public function parentTableNameAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">
				<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<option value=""></option>
				';
				
				//Get all the table names of the site.
				$all_table_names = $_SESSION['results_schema']->getSchemaSelectMultipleRecords(__LINE__, __FILE__, '*', 'tables', 'WHERE `table_schema` = ? ORDER BY `table_name` ASC', [$_SESSION['site_db_name']]);
				
				if(!empty($all_table_names))
				{
					foreach($all_table_names as $all_table_name)
					{
						//Check if site_id column exist on the table to be able to create an admin page.
						$table_column_names = $_SESSION['results_schema']->getSchemaSelectSingleRecord(__LINE__, __FILE__, '*', 'columns', 'WHERE `table_schema` = ? AND `table_name` = ? AND `COLUMN_NAME` = ? ORDER BY `columns`.`ORDINAL_POSITION` ASC', [$_SESSION['site_db_name'], $all_table_name['TABLE_NAME'], 'site_id']);
						
						if(!empty($table_column_names) || $all_table_name['TABLE_NAME'] == 'sites')
						{
							$selected_item = '';
							
							if(isset($all_table_name['TABLE_NAME']))
							{
								$admin_field_id = $all_table_name['TABLE_NAME'];
								$admin_field_label = $all_table_name['TABLE_NAME'];
								
								if(!empty($all_table_name['TABLE_NAME']) && $field_value == $all_table_name['TABLE_NAME'])
								{
									$selected_item = " selected";
								}
							}
							
							echo '<option value="'.htmlspecialchars($admin_field_id ?? '').'"'.$selected_item.'>'.htmlspecialchars($admin_field_label ?? '').'</option>';
						}
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
		
		$class_parentTableNameAeaf = new parentTableNameAeaf();
	}
	
	$class_parentTableNameAeaf->parentTableNameAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}