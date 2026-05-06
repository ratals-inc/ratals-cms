<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/daysOfTheWeekOpen.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/daysOfTheWeekOpen.php');
}
else
{
	if(!class_exists('daysOfTheWeekOpenAeaf'))
	{
		class daysOfTheWeekOpenAeaf
		{
			public function daysOfTheWeekOpenAeaf($table_name, $admin_field, &$field_value, &$errors, &$post_values)
			{
				if(!empty($field_value))
				{
					$days_open_data_main = array();
					$days_open_data_main = explode(',', $field_value);
					foreach($days_open_data_main as $days_open_data_each)
					{
						$days_open_array = explode('|', $days_open_data_each);
						$days_open_data_array[$days_open_array[0]] = $days_open_array;
					}
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">';
				
				echo '<table class="day-open-table">
				  <tr class="header">
					<td>Days</td>
					<td>Open Time</td>
					<td>Close Close</td>
					<td>Open Time</td>
					<td>Close Close</td>
				  </tr>';
				  
				  $days = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
				  
				  foreach($days as $key => $value) 
				  {
					  $open_1 = '';
					  if(isset($days_open_data_array[$value][1]) && !empty($days_open_data_array[$value][1])) { $open_1 = $days_open_data_array[$value][1]; }
					  
					  $close_1 = '';
					  if(isset($days_open_data_array[$value][2]) && !empty($days_open_data_array[$value][2])) { $close_1 = $days_open_data_array[$value][2]; }
					  
					  $open_2 = '';
					  if(isset($days_open_data_array[$value][3]) && !empty($days_open_data_array[$value][3])) { $open_2 = $days_open_data_array[$value][3]; }
					  
					  $close_2 = '';
					  if(isset($days_open_data_array[$value][4]) && !empty($days_open_data_array[$value][4])) { $close_2 = $days_open_data_array[$value][4]; }
					  
					  echo '<tr>
						<td><span class="width-display-inline-block">'.$value.'</span><input name="site_contact_info[day]['.$key.']" type="hidden" value="'.$value.'"></td>
						<td><input name="site_contact_info[open_time_1]['.$key.']" type="text" value="'.$open_1.'"></td>
						<td><input name="site_contact_info[close_time_1]['.$key.']" type="text" value="'.$close_1.'"></td>
						<td><input name="site_contact_info[open_time_2]['.$key.']" type="text" value="'.$open_2.'"></td>
						<td><input name="site_contact_info[close_time_2]['.$key.']" type="text" value="'.$close_2.'"></td>
					  </tr>';
				 }
				
				echo '</table>
				
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'; 
			}
		}
		
		$class_daysOfTheWeekOpenAeaf = new daysOfTheWeekOpenAeaf();
	}
	
	$class_daysOfTheWeekOpenAeaf->daysOfTheWeekOpenAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}