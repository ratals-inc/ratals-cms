<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/lead.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/lead.php');
}
else
{
	if(!class_exists('leadAeaf'))
	{
		class leadAeaf
		{
			public function leadAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$lead_content = '';
				$lead_rows = explode('<br>', $field_value ?? '');
				foreach($lead_rows as $lead_row)
				{
					$lead_row = trim($lead_row);
					
					if($lead_row == '')
					{
						continue;
					}
					
					$lead_parts = explode(':', $lead_row, 2);
					$lead_label = trim($lead_parts[0]);
					$lead_value = isset($lead_parts[1]) ? trim($lead_parts[1]) : '';
					
					$lead_content .= '
					<div class="lead-submitted-field">
						<div class="lead-submitted-label">'.htmlspecialchars($lead_label).'</div>
						<div class="lead-submitted-value">'.$lead_value.'</div>
					</div>';
				}
				
				echo '				
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<!--<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>-->
					<div class="edit-field">
						'.$lead_content.'
						<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
						<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]]))
				{
					echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>';
				}
				echo '</div>';
			}
		}
		
		$class_leadAeaf = new leadAeaf();
	}
	
	$class_leadAeaf->leadAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}