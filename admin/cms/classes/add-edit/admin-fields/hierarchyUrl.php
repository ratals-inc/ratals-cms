<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/hierarchyUrl.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/hierarchyUrl.php');
}
else
{
	if(!class_exists('hierarchyUrlAeaf'))
	{
		class hierarchyUrlAeaf
		{
			public function hierarchyUrlAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $current_values, $sites, $domain)
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
				
				$hierarchy_url = '';
				$hierarchy_url_path = '';
				if(isset($field_value))
				{
					$hierarchy_url_array = explode("/", $field_value);
					$hierarchy_url = end($hierarchy_url_array);
					
					$hierarchy_url_path = implode('/', explode('/', $field_value, -1)).'/';
					if($hierarchy_url_path == '/')
					{
						$hierarchy_url_path = '';
					}
				}
				
				echo '
				<div class="edit'.$class_for_urls_table_fields.' '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">';
				//This code gets sub categories when creating a Hierarchy URL. The else statement below getd the default top level category that displays.
				$path_level = '';
				
				if(isset($post_values['urls']['path_level']))
				{
					$path_level = $post_values['urls']['path_level'];
				}
				elseif(isset($current_values['urls']['path_level']))
				{
					$path_level = $current_values['urls']['path_level']; 
				}
				
				if(empty(trim($_GET["rid"] ?? ''))) { $_GET["rid"] = '0'; }
				
				if(!empty($path_level))
				{
					$path_level_ids = explode("/", trim($path_level, '/'));
					
					$dropdown_counter = 1;
					
					foreach($path_level_ids as $path_level_id)
					{
						if($dropdown_counter == 1)
						{
							//Display top level categories
							$sql_top_level_categories = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `path_level` = ? AND `id` != ? ORDER BY `meta_title` ASC', [$_SESSION["site_set_for_editing"], '0', trim($_GET["rid"] ?? '')]);
							
							if(!empty($sql_top_level_categories))
							{
								echo '<select name="urls[path_level][]" class="pathLevelUrls" data-click="'.trim($_GET["rid"] ?? '').'">
								<option value="">Select URL</option>';
								foreach($sql_top_level_categories as $sql_top_level_categories_row)
								{
									$select_option = '';
									if($path_level_ids[0] == $sql_top_level_categories_row["id"]) { $select_option = " selected"; }
									echo '<option value="'.$sql_top_level_categories_row["id"].'"'.$select_option.'>'.$sql_top_level_categories_row["meta_title"].'</option>';
								}
								echo '</select>';
							}
							$dropdown_counter++;
						}
						else
						{
							//Open container for ajax responce
							if($dropdown_counter == 2) { echo '<div id="display-sub-categories">'; }
							
							//Display sub categories selected
							$sql_top_level_categories = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `id` = ? AND `path_level` != ? ORDER BY `meta_title` ASC', [$_SESSION["site_set_for_editing"], $path_level_id, '0']);
							
							if(!empty($sql_top_level_categories))
							{
								foreach($sql_top_level_categories as $sql_top_level_categories_row)
								{
									$sql_sub_categories = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `path_level` = ? ORDER BY `meta_title` ASC', [$_SESSION["site_set_for_editing"], $sql_top_level_categories_row["path_level"]]);
									
									if(!empty($sql_sub_categories))
									{
										echo '<div class="edit-field-padding">
										<select name="urls[path_level][]" class="pathLevelUrls" data-click="'.trim($_GET["rid"] ?? '').'">
										<option value="">Select URL</option>';
										foreach($sql_sub_categories as $sql_sub_categories_row)
										{
											$select_option = '';
											if($path_level_id == $sql_sub_categories_row["id"]) { $select_option = " selected"; }
											echo '<option value="'.$sql_sub_categories_row["id"].'"'.$select_option.'>'.$sql_sub_categories_row["meta_title"].'</option>';
										}
										echo '</select>
										</div>';
									}
								}
							}
							
							//Display sub categories NOT selected if any
							if(count($path_level_ids) == $dropdown_counter) 
							{
								$sql_sub_categories = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `path_level` = ? AND `id` != ? ORDER BY `meta_title` ASC', [$_SESSION["site_set_for_editing"], $path_level, trim($_GET["rid"] ?? '')]);
								
								if(!empty($sql_sub_categories))
								{
									echo '<div class="edit-field-padding">
									<select name="urls[path_level][]" class="pathLevelUrls" data-click="'.trim($_GET["rid"] ?? '').'">
									<option value="">Select URL</option>';
									foreach($sql_sub_categories as $sql_sub_categories_row)
									{
										echo '<option value="'.$sql_sub_categories_row["id"].'">'.$sql_sub_categories_row["meta_title"].'</option>';
									}
									echo '</select>
									</div>';
								}
								//Close container for ajax reposnce
								echo '</div>'; 
							}
							$dropdown_counter++;
						}//end counter if statement
					}//end of foreach
					
					if($dropdown_counter == 2) { echo '<div id="display-sub-categories">'; }
					if($dropdown_counter == 2) 
					{
						//Display sub categories NOT selected if any
						
						$sql_sub_categories = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `path_level` = ? AND `id` != ? ORDER BY `meta_title` ASC', [$_SESSION["site_set_for_editing"], $path_level, trim($_GET["rid"] ?? '')]);
						
						if(!empty($sql_sub_categories))
						{
							echo '<div class="edit-field-padding">
							<select name="urls[path_level][]" class="pathLevelUrls" data-click="'.trim($_GET["rid"] ?? '').'">
							<option value="">Select URL</option>';
							foreach($sql_sub_categories as $sql_sub_categories_row)
							{
								echo '<option value="'.$sql_sub_categories_row["id"].'">'.$sql_sub_categories_row["meta_title"].'</option>';
							}
							echo '</select>
							</div>';
						}
					}
					if($dropdown_counter == 2) { echo '</div>'; }
				}
				else 
				{
					$sql_top_level_categories = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `path_level` = ? AND `id` != ? ORDER BY `meta_title` ASC', [$_SESSION["site_set_for_editing"], '0', trim($_GET["rid"] ?? '')]);
					
					if(!empty($sql_top_level_categories))
					{
						echo '<select name="urls[path_level][]" class="pathLevelUrls" data-click="'.trim($_GET["rid"] ?? '').'">
						<option value="">Select URL</option>';
						foreach($sql_top_level_categories as $sql_top_level_categories_row)
						{
							echo '<option value="'.$sql_top_level_categories_row["id"].'">'.$sql_top_level_categories_row["meta_title"].'</option>';
						}
						echo '</select>';
					}
					echo '<div id="display-sub-categories"></div>';
				}
				
				
				
				
				
				echo '<div class="edit-field-padding">
				<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($hierarchy_url ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				</div>
				<div class="edit-field-padding edit-field-url"><a href="'.$_SESSION['view_frontend_of_site'].'/'.$hierarchy_url_path.$hierarchy_url.$display_url_extension.'" target="_blank" id="href_hierarchy_url">'.$_SESSION['view_frontend_of_site'].'/<span id="display-hierarchy-url">'.$hierarchy_url_path.'</span><span id="display-url-name">'.$hierarchy_url.'</span><span id="display_url_extension">'.$display_url_extension.'</span></a></div>
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'; 
			}
		}
		
		$class_hierarchyUrlAeaf = new hierarchyUrlAeaf();
	}
	
	$class_hierarchyUrlAeaf->hierarchyUrlAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $current_values, $sites, $domain);
}