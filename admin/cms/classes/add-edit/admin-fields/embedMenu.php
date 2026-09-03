<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/embedMenu.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/embedMenu.php');
}
else
{
	if(!class_exists('embedMenuAeaf'))
	{
		class embedMenuAeaf
		{
			public function embedMenuAeaf($table_name, $admin_field, $field_value, $current_values)
			{
				if($_SESSION['admin_type'] == 'edit')
				{
					//Create a randon string to add to function name in case function name is used twice on the same page.
					$random_string = '0123456789abcdefghijklmnopqrstuvwxyz';
					$random_string = substr(str_shuffle($random_string), 0, 4);
					
					$field_required = '';
					if($admin_field["required"] == 'Yes')
					{
						$field_required = ' <span class="required-asterisk">*</span>';
					}
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="edit-field text embed">';
					?>
					&lt;?php unset($_SESSION['menu_main']); unset($_SESSION['menu_items_main']); unset($_SESSION['menu_items_pages_row']); echo '&lt;ul&gt;'; $menu_id = <?php echo trim($_GET["rid"] ?? ''); ?>; function menu_<?php echo $random_string; ?>_<?php echo trim($_GET["rid"] ?? ''); ?>_<?php echo str_replace(" ", "_", strtolower($current_values[$table_name]['name'])); ?>($menuArray, $url) { if(!empty($menuArray)) { foreach($menuArray as $menu) { echo '&lt;li&gt;'; $css_class = ''; if(!empty($menu['css_class_name'])) { $css_class = $menu['css_class_name'].' '; } $active_link = ''; if(strpos($url, $menu['menu_url']) !== false) { $active_link = ' class="'.$css_class.'active"'; } elseif(!empty($css_class)) { $active_link = ' class="'.trim($css_class ?? '').'"'; } $link_type = ''; if(!empty($menu['menu_url_link_type'])) { $link_type = ' rel="'.$menu['menu_url_link_type'].'"'; } $icon = ''; if(!empty($menu['svg_path'])) { $icon = $menu['svg_path']; } echo '&lt;a href="'.$menu['menu_url'].'"'.$active_link.$link_type.'&gt;'.$icon.$menu['label'].'&lt;/a&gt;'; if(!empty($menu['children'])) { echo '&lt;ul&gt;'; menu_<?php echo $random_string; ?>_<?php echo trim($_GET["rid"] ?? ''); ?>_<?php echo str_replace(" ", "_", strtolower($current_values[$table_name]['name'])); ?>($menu['children'], $url); echo '&lt;/ul&gt;'; } echo '&lt;/li&gt;'; } } } menu_<?php echo $random_string; ?>_<?php echo trim($_GET["rid"] ?? ''); ?>_<?php echo str_replace(" ", "_", strtolower($current_values[$table_name]['name'])); ?>(navMenu($menu_id), $url); echo '&lt;/ul&gt;'; ?&gt;
					<?php 
					echo '</div>
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
				else
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
			}
		}
		
		$class_embedMenuAeaf = new embedMenuAeaf();
	}
	
	$class_embedMenuAeaf->embedMenuAeaf($table_name, $admin_field, $field_value, $current_values);
}