<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/canonicalUrl.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/canonicalUrl.php');
}
else
{
	if(!class_exists('canonicalUrlAeaf'))
	{
		class canonicalUrlAeaf
		{
			public function canonicalUrlAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $sites)
			{
				//Set URL styles for gray box if table is for urls.
				$class_for_urls_table_fields = '';
				
				if($table_name == 'urls')
				{
					//If URLs, add class to for gray / padding.
					$class_for_urls_table_fields = ' url';
				}
				
				if($sites["auto_generate_canonical_url"] == 'Yes') { $auto_gen_canonical = 'Yes'; } else { $auto_gen_canonical = 'No'; }
				echo '
				<div class="edit'.$class_for_urls_table_fields.' '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">';
				echo '
				<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<div class="small-text">Global Setting - Auto Generate Canonical URLs: '.$auto_gen_canonical.'</div>
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'; 
			}
		}
		
		$class_canonicalUrlAeaf = new canonicalUrlAeaf();
	}
	
	$class_canonicalUrlAeaf->canonicalUrlAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $sites);
}