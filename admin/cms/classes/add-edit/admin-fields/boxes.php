<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/boxes.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/boxes.php');
}
else
{
	if(!class_exists('boxesAeaf'))
	{
		class boxesAeaf
		{
			public function boxesAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $current_values)
			{
				//Set URL styles for gray box if table is for urls.
				$last_class_for_urls_table_fields = '';
				
				if($table_name == 'urls' && $admin_field["column_name"] == 'hreflang_url_id')
				{
					$last_class_for_urls_table_fields .= ' url';
				}
				
				$url_name_class = '';
				if($admin_field["url_name"] != 'url')
				{
					$url_name_class = ' '.htmlspecialchars($admin_field["url_name"] ?? '');
				}
				
				if(isset($admin_field['custom_field_name']))
				{
					$custom_field_name = JSON_DECODE($admin_field['custom_field_name'] ?? '', true);
					
					$admin_field['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
				}
				
				if(isset($admin_field["admin_fields_lists_system_code"]))
				{
					$admin_field_list = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', [$admin_field["admin_fields_lists_system_code"]]);
					
					//If swap fields is set on an admin fields list, get the jquery to swap the fields.
					if(!empty($admin_field_list['swap_admin_field']))
					{
						$current_or_post_value = array();
						
						if(isset($post_values[$table_name]) && !empty($post_values[$table_name]))
						{
							$current_or_post_value = $post_values[$table_name];
						}
						elseif(isset($current_values[$table_name]) && !empty($current_values[$table_name]))
						{
							$current_or_post_value = $current_values[$table_name];
						}
						
						$jquery_swap = adminFieldsListsSwap($admin_field_list['id'], $field_value, $current_or_post_value, htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? ''), htmlspecialchars($table_name ?? ''));
						echo $jquery_swap['swap_jquery'];
					}
				}
				
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				echo '
				<div class="edit'.$last_class_for_urls_table_fields.''.$url_name_class.'">
				<div class="edit-label">'.((isset($admin_field["name"])) ? htmlspecialchars($admin_field["name"] ?? '') : htmlspecialchars($admin_field["frontend_name"] ?? '')).$field_required.'</div>			
				<div class="edit-field">
				<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<option value=""></option>
				';
				
				$set_site_id_column = '';
				$set_side_id = array();
				if($_SESSION['admin_site_id_global'] == 'No')
				{
					$set_site_id_column = 'WHERE `site_id` = ? OR `site_id` = ? ';
					$set_side_id = array($_SESSION["site_set_for_editing"], 0);
				}
				
				$admin_field_values = array();
				//if dynamic, get values from connected table.
				if(isset($admin_field_list['dynamic']) && $admin_field_list['dynamic'] == 'Yes')
				{					
					$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', $admin_field_list['dynamic_table_name'], $set_site_id_column.' ORDER BY `'.$admin_field_list['dynamic_column_label'].'` ASC', $set_side_id);
				}
				//if not dynamic, get values from admin_fields_values.
				elseif(isset($admin_field_list['dynamic']) && $admin_field_list['dynamic'] == 'No')
				{
					$admin_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$admin_field["admin_fields_lists_system_code"]]);
				}
				//if custom_fields, get values from custom_fields_options table. Using ['embed_custom_field'] as its unquie to custom_fields. This tell that it is custom_fields and get custom_fields_options.
				elseif(isset($admin_field['embed_custom_field']))
				{
					$admin_field_values_data = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields_options', 'WHERE `custom_fields_id` = '.$admin_field["id"].' ORDER BY `sort` ASC', []);
					
					$admin_field_values = array();
					
					if(!empty($admin_field_values_data))
						{
							foreach($admin_field_values_data as $admin_field_value)
							{
								$option_data = JSON_DECODE($admin_field_value['option_data'] ?? '', true);
								
								$admin_field_value['label'] = $option_data[$_SESSION['admin_language']]['label'] ?? '';
								$admin_field_value['value'] = $option_data[$_SESSION['admin_language']]['value'] ?? '';
								
								$admin_field_values[] = $admin_field_value;
							}
						}
				}
				
				if(!empty($admin_field_values))
				{
					foreach($admin_field_values as $admin_field_value)
					{
						$selected_item = '';
						
						//If admin field is dynamic=yes, use values from table its connecting to.
						if(isset($admin_field_list['dynamic']) && $admin_field_list['dynamic'] == 'Yes' && isset($admin_field_value[$admin_field_list['dynamic_column_id']]) && isset($admin_field_value[$admin_field_list['dynamic_column_label']]))
						{
							$admin_field_id = $admin_field_value[$admin_field_list['dynamic_column_id']];
							$admin_field_label = $admin_field_value[$admin_field_list['dynamic_column_label']];
							
							if(!empty($admin_field_value[$admin_field_list['dynamic_column_id']]) && $field_value == $admin_field_value[$admin_field_list['dynamic_column_id']])
							{
								$selected_item = " selected";
							}
						}
						//If admin field is dynamic=no, use ids as values from admin_fields_values table. If custom_fields, use custom_fields_options row id as value.
						else
						{
							$admin_field_id = $admin_field_value["id"];
							$admin_field_label = $admin_field_value["label"];
							
							if($field_value == $admin_field_id)
							{
								$selected_item = " selected";
							}
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
		
		$class_boxesAeaf = new boxesAeaf();
	}
	
	$class_boxesAeaf->boxesAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $current_values);
}