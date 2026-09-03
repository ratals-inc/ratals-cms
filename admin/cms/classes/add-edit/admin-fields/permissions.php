<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/permissions.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/permissions.php');
}
else
{
	if(!class_exists('permissionsAeaf'))
	{
		class permissionsAeaf
		{
			public function permissionsAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
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
				<script nonce="'.NONCE.'">
				$(document).ready(function () 
				{
					$("#CheckAllColumns").click(function () 
					{
						$(".CheckAllColumns").prop("checked", $(this).prop("checked"));
					});
				});
				</script>
				';
				
				echo '<div class="check-box-list padding-margin-background-font">
				<label>
				<input name="CheckAllColumns" id="CheckAllColumns" type="checkbox" value="" />Select All</label>
				</div>';
				
				//Get admin_pages table and list all pages so admin user can create a permissions set.
				$admin_pages_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_pages', 'ORDER BY `url` ASC', []);
		
				if(!empty($admin_pages_values))
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
					
					foreach($admin_pages_values as $admin_pages_value)
					{
						$selected_item = '';
		
						$admin_field_id = $admin_pages_value["id"];
						$admin_field_label = $admin_pages_value["url"];
						
						if(in_array($admin_field_id, $field_value_array))
						{
							$selected_item = " checked";
						}
						
						echo '<div class="check-box-list">
						<label>
						<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].'][]' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'" class="CheckAllColumns" type="checkbox" value="'.htmlspecialchars($admin_field_id ?? '').'"'.$selected_item.' />
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
		
		$class_permissionsAeaf = new permissionsAeaf();
	}
	
	$class_permissionsAeaf->permissionsAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}