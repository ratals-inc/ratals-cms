<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/flatUrl.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/flatUrl.php');
}
else
{
	if(!class_exists('flatUrlAeaf'))
	{
		class flatUrlAeaf
		{
			public function flatUrlAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $current_values, $sites, $domain, $display_url_data)
			{
				//Set URL styles for gray box if table is for urls.
				$class_for_urls_table_fields = '';
				
				if($table_name == 'urls')
				{
					//If URLs, add class to for gray / padding.
					$class_for_urls_table_fields = ' url';
				}
				
				$url_extension = '';
				if(!empty($current_values['urls']['url_extension']))
				{
					$url_extension = $current_values['urls']['url_extension'];
					$display_url_extension = $current_values['urls']['url_extension'];
				}
				else
				{
					$display_url_extension = $sites["global_url_extension"];
				}
				
				if($table_name == 'urls')
				{
					//Keep url data open if creating a page or if display-url=yes in url.
					if($_SESSION['admin_type'] == 'add' || (isset($_GET['display-url']) && $_GET['display-url'] == 'yes'))
					{
						$display_url_data = '';
					}
					
					//Open URLs gray backgound area.
					echo '<div id="url-data" class="url-data'.$display_url_data.'">';
				}
				
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				echo '
				<div class="edit'.$class_for_urls_table_fields.' '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
				<div class="edit-field">
				<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<div class="edit-field-padding edit-field-url"><a href="'.$_SESSION['view_frontend_of_site'].INSTALLATION_URL_PATH.'/'.$field_value.$display_url_extension.'" target="_blank" id="href_flat_url">'.$_SESSION['view_frontend_of_site'].INSTALLATION_URL_PATH.'/<span id="display-flat-url">'.$field_value.'</span><span id="display_flat_url_extension">'.$display_url_extension.'</span></a></div>
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'; 
			}
		}
		
		$class_flatUrlAeaf = new flatUrlAeaf();
	}
	
	$class_flatUrlAeaf->flatUrlAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $current_values, $sites, $domain, $display_url_data);
}