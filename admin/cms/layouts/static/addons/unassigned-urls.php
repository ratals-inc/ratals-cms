<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/unassigned-urls.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/unassigned-urls.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'unassigned_categories' || $_SESSION['admin_assigned_type'] == 'unassigned_pages' || $_SESSION['admin_assigned_type'] == 'unassigned_posts' || $_SESSION['admin_assigned_type'] == 'unassigned_products')
	{
		$sql_get_assignments_posts_check = 0;
		$sql_get_assignments_products_check = 0;
		$sql_get_assignments_sub_items_check = 0;
		$sql_get_menu_items_check = 0;
		$sql_get_sliders_items_check = 0;
		
		$unassigned_ids = array();
		
		$sql_get_all_pages = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` != ? AND `site_id` = ? AND `table_name` = ?', [$home_page, $_SESSION["site_set_for_editing"], $_SESSION['admin_table_name']]);
		
		if(!empty($sql_get_all_pages))
		{
			foreach($sql_get_all_pages as $sql_get_all_pages_rows)
			{
				if($_SESSION['admin_table_name'] == 'posts')
				{
					$sql_get_assignments_posts_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'assignments_posts', 'WHERE `child_id` = ? AND `site_id` = ?', [$sql_get_all_pages_rows["id"], $_SESSION["site_set_for_editing"]]);
				}
				
				$sql_get_assignments_products_check = 0;
				if($commerce_installed)
				{
					$sql_get_assignments_products_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'assignments_products', 'WHERE `child_id` = ? AND `site_id` = ? AND `status` = ?', [$sql_get_all_pages_rows["id"], $_SESSION["site_set_for_editing"], '1']);
				}
				
				$sql_get_assignments_sub_items_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'assignments_sub_items', 'WHERE `child_id` = ? AND `site_id` = ? AND `status` = ?', [$sql_get_all_pages_rows["id"], $_SESSION["site_set_for_editing"], '1']);
				
				$sql_get_menus = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'menus', 'WHERE `site_id` = ? AND `status` = ?', [$_SESSION["site_set_for_editing"], '1']);
				$sql_get_menu_items_check = 0;
				if(!empty($sql_get_menus))
				{
					foreach($sql_get_menus as $sql_get_menus_rows)
					{
						$sql_get_menu_items_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'menu_items', 'WHERE `site_id` = ? AND `status` = ? AND `menus_id` = ? AND `links_to` = ?', [$_SESSION["site_set_for_editing"], '1', $sql_get_menus_rows["id"], $sql_get_all_pages_rows["id"]]);
						
						if($sql_get_menu_items_check > 0)
						{
							break;
						}
					}
				}
				
				$sql_get_sliders = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'sliders', 'WHERE `site_id` = ? AND `status` = ?', [$_SESSION["site_set_for_editing"], '1']);
				if(!empty($sql_get_sliders))
				{
					foreach($sql_get_sliders as $sql_get_sliders_rows)
					{
						$sql_get_sliders_items_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'slider_items', 'WHERE `site_id` = ? AND `status` = ? AND `sliders_id` = ? AND `links_to` = ?', [$_SESSION["site_set_for_editing"], '1', $sql_get_sliders_rows["id"], $sql_get_all_pages_rows["id"]]);
						
						if($sql_get_sliders_items_check > 0)
						{
							break;
						}
					}
				}
				
				if($sql_get_assignments_posts_check == 0 && $sql_get_assignments_products_check == 0 && $sql_get_assignments_sub_items_check == 0 && $sql_get_menu_items_check == 0 && $sql_get_sliders_items_check == 0)
				{
					$unassigned_ids[] = $sql_get_all_pages_rows["id"];
				}
			}
		}
		//echo "<pre>"; print_r($unassigned_ids); echo "</pre>";
		?>
		<!-- Start Edit View -->
		<div class="edit-wrapper">
		<!-- Start Edit View -->
		<div class="edit">
		<?php 
		$category_message = '';
		if($_SESSION['admin_table_name'] == 'posts')
		{
			$category_message = 'blog post category, ';
		}
		elseif($_SESSION['admin_table_name'] == 'products')
		{
			$category_message = 'product category, ';
		}
		?>
		<div class="edit-label">These are the <?php echo str_replace('_', ' ', $_SESSION['admin_table_name'] ?? ''); ?> that are not assigned to a <?php echo $category_message; ?>menu, slider, or sub item. In other words, there is nothing linking to these <?php echo $_SESSION['admin_table_name']; ?> for visitors or search engines to find them.</div>
		<div class="edit-field">
		<!-- Start Unassigned In Table -->
		<div class="table-overfollow fixed-scrollbar">
		<div class="unassigned-table">
		<ul class="unassigned-table-row header">
		<li class="unassigned-table-cell header center">Status</li>
		<li class="unassigned-table-cell header center">ID</li>
		<li class="unassigned-table-cell header">URL</li>
		</ul>
		<?php
		if(!empty($unassigned_ids))
		{
			$counter = 0;
			foreach($unassigned_ids as $assignments_row)
			{
				$url_record_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE table_name = ? AND `id` = ?', [$_SESSION['admin_table_name'] ,$assignments_row]);
				
				if($url_record_data["url_status"] == 1) { $status = '<span class="unassignedStatus" data-click="'.$assignments_row.','.$url_record_data["id"].','.$url_record_data["url_status"].'">Enabled</span>'; } 
				elseif($url_record_data["url_status"] == 2) { $status = '<span class="unassignedStatus" data-click="'.$assignments_row.','.$url_record_data["id"].','.$url_record_data["url_status"].'">Disabled</span>'; }
				elseif($url_record_data["url_status"] == 3) { $status = '<span class="unassignedStatus" data-click="'.$assignments_row.','.$url_record_data["id"].','.$url_record_data["url_status"].'">Draft</span>'; }
				elseif($url_record_data["url_status"] == 4) { $status = '<span class="unassignedStatus" data-click="'.$assignments_row.','.$url_record_data["id"].','.$url_record_data["url_status"].'">Scheduled</span>'; }
				$admin_edit_page = '/'.$_SESSION['admin_edit_url'].'/?rid=';
				
				if(empty($url_record_data["custom_link"])) 
				{
					if(!empty($url_record_data["url_extension"]))
					{
						$end_url_with = $url_record_data["url_extension"]; } else { $end_url_with = $sites_end_urls_with;
					}
					
					if($url_structure == 'Hierarchy')
					{
						$final_url = $view_frontend_of_site."/".$url_record_data["hierarchy_url"].$end_url_with;
					}
					elseif($url_structure == 'Flat')
					{
						$final_url = $view_frontend_of_site."/".$url_record_data["flat_url"].$end_url_with;
					}
				}
				else
				{
					$final_url = $url_record_data["custom_link"];
				}
				if($home_page == $url_record_data["id"] && empty($url_record_data["custom_link"]))
				{
					$final_url = $view_frontend_of_site."/";
				} 
				?>
				<ul class="unassigned-table-row">
				<li class="unassigned-table-cell center status status_id_<?php echo $url_record_data["record_id"]; ?>"><?php echo $status; ?></li>
				<li class="unassigned-table-cell center status"><a href="<?php echo $admin_edit_page.$assignments_row; ?>" target="_blank"><?php echo $assignments_row; ?></a></li>
				<li class="unassigned-table-cell"><a href="<?php echo $final_url; ?>" target="_blank"><?php echo $final_url; ?></a></li>
				</ul>
			<?php
			}
		echo "</div>";
		}
		else 
		{
		echo '</div><div class="table-no-results">No Results</div>';
		}
		?>
		</div>
		<!-- End Unassigned In Table -->
		</div>
		</div>
		</div>
		<!-- End Edit View -->
		</div>
	<?php } ?>
<?php } ?>