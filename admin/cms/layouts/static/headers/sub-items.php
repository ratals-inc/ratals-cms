<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/sub-items.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/sub-items.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'sub_items')
	{
		$group_name = '';
		if(isset($_GET["group"])) { $group_name = trim($_GET["group"] ?? ''); }
		
		$post_group_id = '';
		if(isset($_POST["group-id"])) { $post_group_id = trim($_POST["group-id"] ?? ''); }
		
		$search_results = array();
		$search_id = '';
		$type = '';
		$url_status = '';
		$title = '';
		$flat_url = '';
		$hierarchy_url = '';
		
		$record_data_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $_SESSION['admin_table_name'], 'WHERE `urls_id` = ? AND `site_id` = ?', [trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
		if(empty($record_data_row)) { header("Location: ".$_SESSION['admin_url_no_records']); exit(); }
		
		$page_groups = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'page_groups', 'WHERE `site_id` = ? AND `table_name` = ? AND `urls_id` = ? ORDER BY `name` ASC', [$_SESSION["site_set_for_editing"], $_SESSION['admin_table_name'], trim($_GET["rid"] ?? '')]);
		
		$template_include_files = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'template_files', 'WHERE `site_id` = ? AND `templates_id` = ? AND `template_type` = ? ORDER BY `filename` ASC', [$_SESSION["site_set_for_editing"], 1, 'includes']);
		
		//Create a Group
		$errors = array();
		if(isset($_POST['create-group']))
		{
			//Update record with updated timestamp.
			$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', 'WHERE `urls_id` = ? AND `site_id` = ?', [$_SESSION['user_first_last_name'], trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
			
			$create_group_name = trim($_POST["create-group-name"] ?? '');
			if(empty(trim($create_group_name)))
			{
				$errors['create_group_name'] = '<div class="validation">Please enter a group name.</div>';
			}
			if(empty($errors))
			{
				//Insert new sub items group.
				$results->getInsertRecord(__LINE__, __FILE__, 'page_groups', '`site_id`, `table_name`, `urls_id`, `status`, `name`, `sub_items_type`, `sub_items_code`, `sub_items_load_template_include_file`, `title`, `content`, `columns`, `display_text_from_sub_items`, `gap_between_items`, `outter_css_box_styles`, `inner_css_box_styles`, `lazy_load_media`, `display_as_slider`, `slides_in_view`, `slide_all_at_once`, `slide_minimum_width`, `auto_slide_media`, `pause_time`, `slide_speed`, `slide_margin`, `display_pagination`, `pagination_alignment`, `pagination_over_image`, `display_thumbnails`, `pagination_thumbnail_width`, `pagination_margin`, `sort`', '?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?', [$_SESSION["site_set_for_editing"], $_SESSION['admin_table_name'], trim($_GET["rid"] ?? ''), 1, $create_group_name, '', '', 'No', '', '', '5', 'Yes', 0, '', '', 'No', 'No', 5, 'Yes', 200, 'No', 8000, 500, 3, 'Yes', 'center', 'No', 'No', 40, 5, '0']);
				
				//Clear cache on save.
				if($_SESSION['admin_site_id_global'] == 'No')
				{
					clearSiteCache($_SESSION['site_set_for_editing']);
				}
				else
				{
					clearAllSiteCache();
				}
				
				header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&create=group"); exit();
			}
		}
		
		//Save a group
		if(isset($_POST['save-groups'])) 
		{
			$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', 'WHERE `urls_id` = ? AND `site_id` = ?', [$_SESSION['user_first_last_name'], trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
			
			//Delete assignment sub items assigned to parent id.
			$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_sub_items', 'WHERE `site_id` = ? AND `parent_id` = ?', [$_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? '')]);
			
			$group_order_counter = 1;
			$item_order_counter = 1;
			
			$group_ids_submitted = array();
			
			if(isset($_POST["groups"]))
			{
				foreach($_POST["groups"] as $insert_groups)
				{
					//Update group data.
					$results->getUpdateRecord(__LINE__, __FILE__, 'page_groups', '`status` = ?, `name` = ?, `sub_items_type` = ?, `sub_items_code` = ?, `sub_items_load_template_include_file` = ?, `title` = ?, `content` = ?, `columns` = ?, `display_text_from_sub_items` = ?, `gap_between_items` = ?, `outter_css_box_styles` = ?, `inner_css_box_styles` = ?, `display_as_slider` = ?, `slides_in_view` = ?, `slide_all_at_once` = ?, `slide_minimum_width` = ?, `auto_slide_media` = ?, `pause_time` = ?, `slide_speed` = ?, `slide_margin` = ?, `display_pagination` = ?, `pagination_alignment` = ?, `pagination_over_image` = ?, `display_thumbnails` = ?, `pagination_thumbnail_width` = ?, `pagination_margin` = ?, `lazy_load_media` = ?, `fetch_priority` = ?, `sort` = ?', 'WHERE `site_id` = ? AND `id` = ?', [$insert_groups["group_status"], $insert_groups["group_name"], $insert_groups["group_sub_items_type"], $insert_groups["group_sub_items_code"], $insert_groups["group_sub_items_load_template_include_file"], $insert_groups["group_title"], $insert_groups["group_content"], $insert_groups["group_columns"], $insert_groups["group_display_text_from_sub_items"],  $insert_groups["group_gap_between_items"],  $insert_groups["group_outter_css_box_styles"], $insert_groups["group_inner_css_box_styles"], $insert_groups["group_display_as_slider"], $insert_groups["group_slides_in_view"], $insert_groups["group_slide_all_at_once"], $insert_groups["group_slide_minimum_width"], $insert_groups["group_auto_slide_media"], $insert_groups["group_pause_time"], $insert_groups["group_slide_speed"], $insert_groups["group_slide_margin"], $insert_groups["group_display_pagination"], $insert_groups["group_pagination_alignment"], 'No', $insert_groups["group_display_thumbnails"], $insert_groups["group_pagination_thumbnail_width"], $insert_groups["group_pagination_margin"], $insert_groups["group_lazy_load_media"], $insert_groups["group_image_fetch_priority"], $group_order_counter, $_SESSION["site_set_for_editing"], $insert_groups["group_id"]]);
					
					$group_ids_submitted[] = $insert_groups['group_id'];
					
					if(!empty($insert_groups["items"]))
					{
						//Insert each item in the group.
						assignmentsInsertRecord('assignments_sub_items', $insert_groups['group_id'], trim($_GET["rid"] ?? ''), $insert_groups['items']);
					}
					$group_order_counter = $group_order_counter + 1;
				}
			}
			
			if(!empty($page_groups))
			{
				foreach($page_groups as $page_group)
				{
					if(!in_array($page_group['id'],$group_ids_submitted))
					{
						//Delete groups that have been removed.
						$results->getDeleteRecord(__LINE__, __FILE__, 'page_groups', 'WHERE `site_id` = ? AND `urls_id` = ? AND `id` = ?', [$_SESSION["site_set_for_editing"], trim($_GET["rid"] ?? ''), $page_group['id']]);
					}
				}
			}
			
			//Clear cache on save.
			if($_SESSION['admin_site_id_global'] == 'No')
			{
				clearSiteCache($_SESSION['site_set_for_editing']);
			}
			else
			{
				clearAllSiteCache();
			}
			
			header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&updated=groups"); exit();
		}
		
		//Assign Items
		if(isset($_POST['assign-items'])) 
		{
			$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`updated_by` = ?, `updated_date` = UTC_TIMESTAMP()', 'WHERE `urls_id` = ? AND `site_id` = ?', [$_SESSION['user_first_last_name'], trim($_GET["rid"] ?? ''), $_SESSION["site_set_for_editing"]]);
			
			if(isset($_POST['items']))
			{
				assignmentsInsertRecord('assignments_sub_items', $post_group_id, trim($_GET["rid"] ?? ''), $_POST['items']);
				
				//Clear cache on save.
				if($_SESSION['admin_site_id_global'] == 'No')
				{
					clearSiteCache($_SESSION['site_set_for_editing']);
				}
				else
				{
					clearAllSiteCache();
				}
				
				header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&group=".$post_group_id."&assigned=items"); exit();
			}
		}
		
		//Search items.
		if(isset($_POST['item-search'])) 
		{
			if(isset($_POST["search_id"])) { $search_id = trim($_POST["search_id"] ?? ''); }
			if(isset($_POST["type"])) { $type = trim($_POST["type"] ?? ''); }
			if(isset($_POST["status"])) { $status = trim($_POST["status"] ?? ''); }
			if(isset($_POST["search_title"])) { $title = trim($_POST["search_title"] ?? ''); }
			if(isset($_POST["flat_url"])) { $flat_url = trim($_POST["flat_url"] ?? '');  }
			if(isset($_POST["hierarchy_url"])) { $hierarchy_url = trim($_POST["hierarchy_url"] ?? ''); }
			
			$sql_product_search_query = '';
			$sql_product_search_query_parameters = array();
			
			$sql_product_search_query_parameters[] = $_SESSION["site_set_for_editing"];
			if(!empty($search_id)) { $sql_product_search_query .= " AND `id` = ?"; $sql_product_search_query_parameters[] = $search_id; }
			if(!empty($type)) { $sql_product_search_query .= " AND `table_name` = ?"; $sql_product_search_query_parameters[] = $type; }
			if(!empty($status)) { $sql_product_search_query .= " AND `url_status` = ?"; $sql_product_search_query_parameters[] = $status; }
			if(!empty($title)) { $sql_product_search_query .= " AND `meta_title` LIKE ?"; $sql_product_search_query_parameters[] = '%'.$title.'%'; }
			if(!empty($flat_url)) { $sql_product_search_query .= " AND `flat_url` LIKE ?"; $sql_product_search_query_parameters[] = '%'.$flat_url.'%'; }
			if(!empty($hierarchy_url)) { $sql_product_search_query .= " AND `hierarchy_url` LIKE ?"; $sql_product_search_query_parameters[] = '%'.$hierarchy_url.'%'; }
			
			$all_urls = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ?'.$sql_product_search_query.' ORDER BY `id` DESC LIMIT 100 ', $sql_product_search_query_parameters);
			
			if(!empty($all_urls))
			{
				foreach($all_urls as $all_url)
				{
					$record_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $all_url['table_name'], 'WHERE `site_id` = ? AND `urls_id` = ? LIMIT 1', [$_SESSION["site_set_for_editing"], $all_url['id']]);
					
					$search_results[] = $all_url + $record_data;
					
					//If a product is being searched, get inventory attached to it.
					if(isset($record_data['product_type']) && $record_data['product_type'] == 'Inventory Items')
					{
						if(!empty(trim($record_data['inventory_assigned'] ?? '', ',')))
						{
							$inventory_assigned_ids = array();
							$select_inventory_placeholders = '';
							
							if(strpos(trim($record_data['inventory_assigned'] ?? '', ','), ',') !== false)
							{
								$inventory_assigned_exploded = explode(',', trim($record_data['inventory_assigned'] ?? '', ','));
								foreach($inventory_assigned_exploded as $inventory_assigned_array)
								{
									$inventory_assigned_id = explode('|', $inventory_assigned_array);
									$inventory_assigned_ids[] = $inventory_assigned_id[1];
									$select_inventory_placeholders .= '?,';
								}
							}
							else
							{
								$inventory_assigned_id = explode('|', trim($record_data['inventory_assigned'], ','));
								$inventory_assigned_ids[] = $inventory_assigned_id[1];
								$select_inventory_placeholders .= '?,';
							}
							
							$sql_get_searched_inventory_rows = array();
							if(!empty($inventory_assigned_ids))
							{
								$select_inventory_placeholders = trim($select_inventory_placeholders, ',');
								$select_inventory_ids = array_merge($inventory_assigned_ids, $inventory_assigned_ids);
								
								$sql_get_searched_inventory_rows = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `id` IN ('.$select_inventory_placeholders.') ORDER BY FIELD(`id`, '.$select_inventory_placeholders.')', $select_inventory_ids, 'id');
							}
							
							if(!empty($sql_get_searched_inventory_rows))
							{
								foreach($sql_get_searched_inventory_rows as $sql_get_searched_inventory_row)
								{
									$search_results[] = $sql_get_searched_inventory_row + array('parent_product_id' => $record_data['urls_id']);
								}
							}
						}
					}
				}
			}
		}
		
		//Clear search
		if(isset($_POST['clear-search'])) { $search_results = array(); }
	}
}