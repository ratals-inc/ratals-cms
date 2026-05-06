<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/timezone.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/timezone.php');
}
else
{
	if(!class_exists('timezoneAeaf'))
	{
		class timezoneAeaf
		{
			public function timezoneAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				//Get all timezones from php library and put in array.
				$php_timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
				$timezone_rows = array();
				if(!empty($php_timezones))
				{
					foreach($php_timezones as $timezone)
					{
						$current_time = '';
						date_default_timezone_set($timezone);
						$current_time_24 = date('H:i');
						$current_time = date('g:i A');
						$timezones_row = array('time_24' => $current_time_24, 'time' => $current_time, 'timezone' => $timezone);
						$timezone_rows[] = $timezones_row;
					}
					sort($timezone_rows);
					
					//Set timezone default back to site_settings after looping though all timezones and changing it many times to build array above.
					date_default_timezone_set($_SESSION['timezone']);
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">
				<select name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<option value=""></option>
				';
		
				if(!empty($timezone_rows))
				{
					foreach($timezone_rows as $admin_field_value)
					{
						$selected_item = '';
						
						if(isset($admin_field_value["timezone"]))
						{
							$admin_field_id = $admin_field_value["timezone"];
							$admin_field_label = $admin_field_value["time"];
							
							if($field_value == $admin_field_value["timezone"])
							{
								$selected_item = " selected";
							}
						}
						
						echo '<option value="'.htmlspecialchars($admin_field_id ?? '').'"'.$selected_item.'>'.htmlspecialchars($admin_field_label.' - '.$admin_field_id ?? '').'</option>';
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
		
		$class_timezoneAeaf = new timezoneAeaf();
	}
	
	$class_timezoneAeaf->timezoneAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}