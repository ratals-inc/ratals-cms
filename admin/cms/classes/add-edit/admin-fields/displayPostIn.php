<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/displayPostIn.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/displayPostIn.php');
}
else
{
	if(!class_exists('displayPostInAeaf'))
	{
		class displayPostInAeaf
		{
			public function displayPostInAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				//Get all blog categories and what this posts is displaying in.
				//Get active template for site.
				$active_template_data = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'templates', 'WHERE `site_id` = ? AND `status` = ?', [$_SESSION["site_set_for_editing"], 1]);
				
				$blog_category_templates = array();
				if(!empty($active_template_data))
				{
					//Get active template files that are a Category > Blog.
					$blog_category_templates = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'template_files', 'WHERE `templates_id` = ? AND `site_id` = ? AND `assigned_type` = ?', [$active_template_data['id'], $_SESSION["site_set_for_editing"], 'Category > Blog']);
				}
				
				$blog_template_file_categories = array();
				if(!empty($blog_category_templates))
				{
					foreach($blog_category_templates as $blog_category_template)
					{
						//Get categories using a Category > Blog template.
						$blog_template_file_category = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `template` = ? ORDER BY `content_title` ASC', [$blog_category_template['id']]);
						
						if(!empty($blog_template_file_category))
						{
							$blog_template_file_categories = array_merge($blog_template_file_categories, $blog_template_file_category);
						}
					}
				}
				
				//Get blog categories that this post is displaying in.
				$posts_assigned_to = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'assignments_posts', 'WHERE `site_id` = ? AND `child_id` = ?', [$_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? '')]);
				
				
				$posts_assigned_to_array = array();
				if(empty($_POST) && !empty($posts_assigned_to))
				{
					foreach($posts_assigned_to as $posts_assigned)
					{
						$posts_assigned_to_array[] = $posts_assigned['parent_id'];
					}
				}
				elseif(!empty($_POST) && !empty($field_value))
				{
					$field_value = trim($field_value ?? '', ',');
					
					if(strpos($field_value, ',') !== false)
					{
						$posts_assigned_to_array = explode(',', $field_value);
					}
					else
					{
						$posts_assigned_to_array[] = $field_value;
					}
					
					foreach($posts_assigned_to as $posts_assigned)
					{
						$posts_assigned_to_array[] = $posts_assigned['parent_id'];
					}
				}
				
				echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">
					<div class="header-text margin-bottom-13">
					<div class="text">'.htmlspecialchars($admin_field["name"] ?? '').'
					<div class="section-notes">URLs are determined by the Flat and Hierarchy URLs set above and by the URL structure selected under Website > Site Settings > URL Settings. This ensures each record has one canonical URL to help prevent duplicate content issues. The categories selected here simply control which categories this post will display in.</div>
					</div>
					</div>
					</div>
					<div class="edit-field">
					';
					
				if(!empty($blog_template_file_categories))
				{
					foreach($blog_template_file_categories as $blog_category)
					{
						$checked_item = '';
						
						$admin_field_id = $blog_category['id'];
						$admin_field_label = $blog_category['content_title'];
						
						if(in_array($blog_category['id'], $posts_assigned_to_array))
						{
							$checked_item = " checked";
						}
						  
						echo '<div class="check-box-list"><label class="cursor-pointer"><input 
						name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].'][]' ?? '').'" 
						type="checkbox" 
						value="'.htmlspecialchars($admin_field_id ?? '').'" '.$checked_item.' 
						id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'" 
						class="width-cursor;" 
						/> '.htmlspecialchars($admin_field_label ?? '').'</label></div>';
					}
					
					
				} 
				else
				{
					echo '<div class="check-box-list">To display this post in a category, create a category using a template that has an "Assigned Type" of a Category > Blog. This will make it so you can assign this post to a category.</div>';
				}
				
				echo '
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';				
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>';
			}
		}
		
		$class_displayPostInAeaf = new displayPostInAeaf();
	}
	
	$class_displayPostInAeaf->displayPostInAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}