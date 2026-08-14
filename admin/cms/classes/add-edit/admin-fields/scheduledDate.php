<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/scheduledDate.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/scheduledDate.php');
}
else
{
	if(!class_exists('scheduledDateAeaf'))
	{
		class scheduledDateAeaf
		{
			public function scheduledDateAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				echo '<script nonce="'.NONCE.'">
					  //Start show datepicker
					  $(function() 
					  {
						  $( "#datepicker_'.$_SESSION['date_counter'].'_scheduled" ).datepicker({dateFormat: "yy-mm-dd"});
					  });
					  //End show datepicker
					  </script>';
				echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field['name'] ?? '').'</div>
				<div class="edit-field">';
				echo '<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" id="datepicker_'.$_SESSION['date_counter'].'_scheduled" placeholder="Date" value="'.htmlspecialchars($field_value ?? '').'" autocomplete="off">';
				echo '<div class="small-text">'.$admin_field["notes"].'</div>';
				echo '</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
				$_SESSION['date_counter'] ++;
			}
		}
		
		$class_scheduledDateAeaf = new scheduledDateAeaf();
	}
	
	$class_scheduledDateAeaf->scheduledDateAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}