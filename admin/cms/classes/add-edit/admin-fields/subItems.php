<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/subItems.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/subItems.php');
}
else
{
	if(!class_exists('subItemsAeaf'))
	{
		class subItemsAeaf
		{
			public function subItemsAeaf($table_name, $admin_field, $field_value, $current_values, &$errors, &$post_values)
			{
				if(empty($field_value) || $field_value == NULL)
				{
					if($table_name == 'custom_fields' && isset($current_values[$table_name]['display_as']) && ($current_values[$table_name]['display_as'] == 'textfield' || $current_values[$table_name]['display_as'] == 'textareaWithEditor' || $current_values[$table_name]['display_as'] == 'multipleMedia' || $current_values[$table_name]['display_as'] == 'singleMedia' || $current_values[$table_name]['display_as'] == 'textarea'))
					{
						echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
						<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
						<div class="edit-field text">
						N/A
						<div class="small-text">'.$admin_field["notes"].'</div>
						</div>';
						if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
						echo '</div>'; 
					}
					elseif($_SESSION['admin_type'] == 'edit')
					{
						if(!empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET['sub-page-rid'] ?? '')))
						{
							$items_edit_url = 'rid='.trim($_GET['sub-page-rid'] ?? '').'&sub-rid='.trim($_GET["rid"] ?? '');
						}
						elseif($_SESSION['record_has_url'] == 'Yes' && isset($current_values['urls']['record_id']) && !empty($current_values['urls']['record_id']))
						{
							$items_edit_url = 'rid='.$current_values['urls']['record_id'];
						}
						else
						{
							$items_edit_url = 'rid='.trim($_GET["rid"] ?? '');
						}
						
						echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
						<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
						<div class="edit-field text">
						<a href="/'.$_SESSION['admin_sub_items_edit_url'].'/?'.$items_edit_url.'">Edit Sub Items (0)</a>
						<div class="small-text">'.$admin_field["notes"].'</div>
						</div>';
						if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
						echo '</div>'; 
					}
					
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="0">';
				}
				else
				{
					if(!empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET['sub-page-rid'] ?? '')))
					{
						$items_edit_url = 'rid='.trim($_GET['sub-page-rid'] ?? '').'&sub-rid='.trim($_GET["rid"] ?? '');
					}
					elseif($_SESSION['record_has_url'] == 'Yes' && isset($current_values['urls']['record_id']) && !empty($current_values['urls']['record_id']))
					{
						$items_edit_url = 'rid='.$current_values['urls']['record_id'];
					}
					else
					{
						$items_edit_url = 'rid='.trim($_GET["rid"] ?? '');
					}
					
					echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
					<div class="edit-field text">
					<a href="/'.$_SESSION['admin_sub_items_edit_url'].'/?'.$items_edit_url.'">Edit Sub Items ('.htmlspecialchars($field_value ?? '').')</a>
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>'; 
					
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
			}
		}
		
		$class_subItemsAeaf = new subItemsAeaf();
	}
	
	$class_subItemsAeaf->subItemsAeaf($table_name, $admin_field, $field_value, $current_values, $errors, $post_values);
}