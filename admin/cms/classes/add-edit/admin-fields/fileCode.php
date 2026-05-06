<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/fileCode.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/fileCode.php');
}
else
{
	if(!class_exists('fileCodeAeaf'))
	{
		class fileCodeAeaf
		{
			public function fileCodeAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $current_values)
			{
				$filename_editing = '';
				if($_SESSION['admin_type'] == 'edit')
				{
					$active_template = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `id` = ? AND site_id = ?', [trim($_GET['sub-page-rid'] ?? ''), $_SESSION["site_set_for_editing"]]);
					
					$filename_editing = ' for /sites/'.$_SESSION["site_set_for_editing"].'/templates/'.$active_template['directory_folder_name'].'/'.$current_values[$table_name]['filename'];
				}
				
				$code_in_file = '';
				if(isset($post_values[$table_name]['file_code']))
				{
					$code_in_file = $post_values[$table_name]['file_code']; 
				}
				elseif(isset($active_template['directory_folder_name']) && !empty($active_template['directory_folder_name']) && !empty($current_values[$table_name]['filename']) && file_exists($_SERVER['DOCUMENT_ROOT']."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$current_values[$table_name]['filename']))
				{
					$code_in_file = file_get_contents($_SERVER['DOCUMENT_ROOT']."/sites/".$_SESSION["site_set_for_editing"]."/templates/".$active_template['directory_folder_name']."/".$current_values[$table_name]['filename']); 
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$filename_editing.'</div>
				<div class="edit-field">';
				include_once $_SERVER['DOCUMENT_ROOT'].'/admin/cms/includes/code-editor/code-editor.php';
				echo '<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'; 
			}
		}
		
		$class_fileCodeAeaf = new fileCodeAeaf();
	}
	
	$class_fileCodeAeaf->fileCodeAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $current_values);
}