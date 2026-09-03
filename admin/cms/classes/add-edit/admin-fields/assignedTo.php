<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/assignedTo.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/assignedTo.php');
}
else
{
	if(!class_exists('assignedToAeaf'))
	{
		class assignedToAeaf
		{
			public function assignedToAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				if($_SESSION['admin_type'] == 'add')
				{
					echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="edit-field">
					<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">';
					
					//Get custom_field column id and check admin pages for the id in admin_fields_ids. This tells us which tables have a custom_filed column.
					$admin_fields_id = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `column_name` = ?', ['custom_fields']);
					
					$tables_with_custon_fields = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `admin_fields_ids` LIKE ? ORDER BY `database_table_name` ASC', ['%,'.$admin_fields_id['id'].',%']);
					
					if(!empty($tables_with_custon_fields))
					{
						$single_value = array();
						
						foreach($tables_with_custon_fields as $tables_with_custon_field)
						{
							if(!in_array($tables_with_custon_field['database_table_name'], $single_value))
							{
								$selected = '';
								if($field_value == $tables_with_custon_field['database_table_name']) { $selected = " selected"; }
								echo '<option value="'.htmlspecialchars($tables_with_custon_field['database_table_name'] ?? '').'"'.$selected.'>'.htmlspecialchars($tables_with_custon_field['database_table_name'] ?? '').'</option>';
								$single_value[] = $tables_with_custon_field['database_table_name'];
							}
						}
					}
					echo '</select>
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>';
				}
				else
				{
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="edit-field text">
					'.htmlspecialchars($field_value ?? '').'
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>';
				}
			}
		}
		
		$class_assignedToAeaf = new assignedToAeaf();
	}
	
	$class_assignedToAeaf->assignedToAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}