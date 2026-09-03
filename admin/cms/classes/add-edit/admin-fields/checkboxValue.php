<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/checkboxValue.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/checkboxValue.php');
}
else
{
	if(!class_exists('checkboxValueAeaf'))
	{
		class checkboxValueAeaf
		{
			public function checkboxValueAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				if(!isset($admin_field['embed_custom_field']))
				{
					$field_required = '';
					if($admin_field["required"] == 'Yes')
					{
						$field_required = ' <span class="required-asterisk">*</span>';
					}
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="small-text">'.$admin_field["notes"].'</div>
					<div class="edit-field text">
					';
					
					//Get admin_fields_lists table to check if list is dynamic or not.
					$admin_field_list = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', [$admin_field["admin_fields_lists_system_code"]]);
					
					$set_site_id_column = '';
					$set_side_id = array();
					if($_SESSION['admin_site_id_global'] == 'No')
					{
						$set_site_id_column = 'WHERE `site_id` = ? OR `site_id` = ? ';
						$set_side_id = array($_SESSION["site_set_for_editing"], 0);
					}
					
					//if dynamic, get values from connected table.
					if($admin_field_list['dynamic'] == 'Yes')
					{
						$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', $admin_field_list['dynamic_table_name'], $set_site_id_column.' ORDER BY `'.$admin_field_list['dynamic_column_label'].'` ASC', $set_side_id);
					}
					//if not dynamic, get values from admin_fields_values.
					else
					{
						$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$admin_field["admin_fields_lists_system_code"]]);
					}
		
					if(!empty($admin_field_values))
					{
						$field_value = trim($field_value ?? '', ',');
						$field_value_array = array();
						
						if(strpos($field_value ?? '', ',') !== false)
						{
							$field_value_array = explode(',', $field_value);
						}
						elseif(!empty($field_value))
						{
							$field_value_array[] = $field_value;
						}
						
						foreach($admin_field_values as $admin_field_value)
						{
							$selected_item = '';
							
							//If admin field is dynamic=no, use values from created values in admin_fields_values.
							if($admin_field_list['dynamic'] == 'No')
							{
								$admin_field_id = $admin_field_value["value"];
								$admin_field_label = $admin_field_value["label"];
								$admin_field_system_code = $admin_field_value["system_code"];
								
								if(in_array($admin_field_id, $field_value_array))
								{
									$selected_item = " checked";
								}
							}
							//If admin field is dynamic=yes, use values from table its connecting to.
							elseif($admin_field_list['dynamic'] == 'Yes' && isset($admin_field_value[$admin_field_list['dynamic_column_label']]))
							{
								$admin_field_id = $admin_field_value[$admin_field_list['dynamic_column_id']];
								$admin_field_label = $admin_field_value[$admin_field_list['dynamic_column_label']];
								$admin_field_system_code = str_replace(' ', '_', $admin_field_value[$admin_field_list['dynamic_column_id']] ?? '');
								
								if(!empty($admin_field_id) && in_array($admin_field_id, $field_value_array))
								{
									$selected_item = " checked";
								}
							}
							
							echo '<div class="check-box-list">
							<label>
							<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].'][]' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field_system_code ?? '').'" type="checkbox" value="'.htmlspecialchars($admin_field_id ?? '').'"'.$selected_item.' />
							'.htmlspecialchars($admin_field_label ?? '').' 
							</label>
							</div>';
						}
					}
					echo '
					</div>';				
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>'; 
				}
			}
		}
		
		$class_checkboxValueAeaf = new checkboxValueAeaf();
	}
	
	$class_checkboxValueAeaf->checkboxValueAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}