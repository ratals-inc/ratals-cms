<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/sub-items.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/sub-items.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'sub_items')
	{
		//Tables with urls_id
		$tables_with_urls_id = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `admin_fields_ids` LIKE ? ORDER BY `database_table_name` ASC', ['%,'.$admin_fields_urls_id.',%']);
		
		//Creat options for tables with urls_id
		$item_type_options = '';
		if(!empty($tables_with_urls_id))
		{
			foreach($tables_with_urls_id as $tables_with_urls)
			{
				$selected = '';
				if(!empty($type) && $type == $tables_with_urls['database_table_name'])
				{
					$selected = ' selected';
				}
				
				$item_type_options .= '<option value="'.$tables_with_urls['database_table_name'].'"'.$selected.'>'.ucwords($tables_with_urls['database_table_name']).'</option>';
			}
		}
		
		//Get urls status for Enabled, Disabled, etc.
		$url_status_options = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', ['urls_status'], 'value');
		
		$url_statuses = '';
		if(!empty($url_status_options))
		{
			foreach($url_status_options as $url_status_option)
			{
				$selected = '';
				if(!empty($status) && $status == $url_status_option['value'])
				{
					$selected = ' selected';
				}
				
				$url_statuses .= '<option value="'.$url_status_option['value'].'"'.$selected.'>'.ucwords($url_status_option['label']).'</option>';
			}
		}
		?>
		
		<div class="edit-wrapper">
		<?php
		if(isset($_GET["create"]) && !empty($_GET["create"]) && $_GET["create"] == "group" && empty($errors))
		{
			echo '<div class="changes-saved">Block has been successfully created.</div>';
		}
		
		if(isset($_GET["assigned"]) && !empty($_GET["assigned"]) && $_GET["assigned"] == "items" && empty($errors))
		{
			echo '<div class="changes-saved">The selected items have been assigned.</div>';
		}
		
		if(isset($_GET["updated"]) && !empty($_GET["updated"]) && $_GET["updated"] == "groups" && empty($errors))
		{
			echo '<div class="changes-saved">Block(s) have been successfully saved.</div>';
		}
		?>
		<!-- Start Edit View -->
		
			<!-- Start Create a Group -->
			<div class="edit margin-top-25px">
			  <div class="edit-label">Create a Block</div>
			  <div class="edit-field">
				<form method="POST">
				  <input name="create-group-name" type="text" placeholder="Block Name">
				  <?php if(isset($errors['create_group_name'])) { echo '<div class="edit-field-padding">'.$errors['create_group_name'].'</div>'; } ?>
				  <div class="edit-field-padding"><button name="create-group" type="submit">Create Block</button></div>
				</form>
			  </div>
			</div>
			<!-- End Create a Group -->
			
			<?php if(!empty($page_groups)) { ?>
			<div class="edit">
			  <!-- Start Select Group -->
			  <div class="edit-label">Assign Items to a Block</div>
			  <div class="edit-field">
			  <div class="edit-field-padding">
				<select name="groups" id="groups">
				  <option value="">Select Block</option>
				  <?php 
				  if(!empty($page_groups))
				  {
					  foreach($page_groups as $page_group)
					  {
						  $select_group = '';
						  if($post_group_id == $page_group["id"]) 
						  {
							  $select_group = ' selected';
						  }
						  
						  echo '<option value="'.$page_group["id"].'"'.$select_group.'>'.$page_group["name"].'</option>';
					  }
				  }
				  ?>
				</select>
			  </div>
			  <!-- End Select Group -->
			  
			  <!-- Start Search Table -->
			  
			  <?php
			  $display_as_block_none = '';
			  if(isset($_POST['item-search']) || isset($_POST['clear-search'])) { $display_as_block_none = ' display-as-block'; } else { $display_as_block_none = ' display-as-none'; } 
			  ?>
			  <div class="edit-field-padding<?php echo $display_as_block_none; ?>" id="items-toggle">
				<form method="POST" name="items" id="items">
				<input name="group-id" id="items-group-id" type="hidden" value="<?php echo $post_group_id; ?>">
				<div class="assign-item">
				  <div class="assign-item-top">
				  <div class="headline">Select URLs to Assign<div class="note"><?php if(!isset($_POST['item-search'])) { echo '<span>Note:</span> By default, your last 100 created URLs are displaying below. If you\'re looking for a URL further back, search for it. Search will not return results for inventory items. Search for the product the inventory is attached to.'; } else { echo 'Search Results: <span>'.count($search_results).'</span> - Search returns up to 100 results that match your search. If you can\'t find what you\'re looking for and there are 100 results, narrow down your search.'; } ?></div></div>
				  <div class="search-buttons"><button name="item-search" type="submit">Search</button><?php if(isset($_POST['item-search'])) { ?> <button name="clear-search" type="submit">Clear Search</button><?php } ?></div>
				  </div>
				
				<div class="assign-item-table">
				<?php
				if(isset($_POST['item-search']))
				{
					$items_counter = 0;
					
					echo '<ul class="header">
					<li class="checkbox center"></li>
					<li class="id center">ID</li>
					<li class="item-number">URL Type</li>
					<li>Status</li>
					<li>Title</li>
					<li>Flat URL</li>
					<li>Hierarchy URL</li>
					</ul>';
					
					echo '<ul class="search">
					<li></li>
					<li class="id"><input name="search_id" type="text" value="'.$search_id.'"></li>
					<li><select name="type"><option value=""></option>'.$item_type_options.'</select></li>
					<li><select name="status"><option value=""></option>'.$url_statuses.'</select></li>
					<li><input name="search_title" type="text" value="'.$title.'"></li>
					<li><input name="flat_url" type="text" value="'.$flat_url.'"></li>
					<li><input name="hierarchy_url" type="text" value="'.$hierarchy_url.'"></li>
					</ul>';
					
					if(!empty($search_results))
					{
						foreach($search_results as $search_result)
						{
							if(isset($search_result['table_name']) && $search_result['table_name'] != 'inventory')
							{
								echo '<ul class="background-color-f5f5f5">
								<li class="center">
								<label>
								<input name="items['.$items_counter.'][item_id]" type="checkbox" value="'.$search_result["urls_id"].'">
								<input name="items['.$items_counter.'][item_type]" type="hidden" value="'.$search_result["table_name"].'">
								<input name="items['.$items_counter.'][table_name]" type="hidden" value="'.$search_result["table_name"].'">
								</label>
								</li>
								<li class="center">'.$search_result["id"].'</li>
								<li>'.ucwords($search_result["table_name"]).'</li>
								<li>'.$url_status_options[$search_result["url_status"]]['label'].'</li>
								<li>'.$search_result["meta_title"].'</li>
								<li>'.$search_result["flat_url"].'</li>
								<li>'.$search_result["hierarchy_url"].'</li>
								</ul>';
							}
							else
							{
								echo '<ul>
								<li class="center">
								<label>
								<input name="items['.$items_counter.'][item_id]" type="checkbox" value="'.$search_result["parent_product_id"].'">
								<input name="items['.$items_counter.'][item_type]" type="hidden" value="inventory">
								<input name="items['.$items_counter.'][table_name]" type="hidden" value="products">
								<input name="items['.$items_counter.'][inventory_id]" type="hidden" value="'.$search_result["id"].'">
								</label>
								</li>
								<li class="center">'.$search_result["id"].'</li>
								<li>Inventory</li>
								<li>'.$url_status_options[$search_result["status"]]['label'].'</li>
								<li>'.$search_result["name"].'</li>
								<li>N/A</li>
								<li>N/A</li>
								</ul>';
							}
							$items_counter = $items_counter + 1;
						}
						echo "</div>";
					}
					else
					{
						echo '</div><div class="no-results">No Results</div>';
					}
				}
				else
				{
				   $assign_item_rows = array();
					
					$all_urls = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? ORDER BY `id` DESC LIMIT 100 ', [$_SESSION["site_set_for_editing"]]);
					
					if(!empty($all_urls))
					{
						foreach($all_urls as $all_url)
						{
							$record_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $all_url['table_name'], 'WHERE `site_id` = ? AND `urls_id` = ? LIMIT 1', [$_SESSION["site_set_for_editing"], $all_url['id']]);
							
							if(!empty($record_data))
							{
								$assign_item_rows[] = $all_url + $record_data; 
							}
						}
					}
					
					$items_counter = 0;	
					
					echo '<ul class="header">
					<li class="checkbox center"></li>
					<li class="id center">ID</li>
					<li class="item-number">URL Type</li>
					<li>Status</li>
					<li>Title</li>
					<li>Flat URL</li>
					<li>Hierarchy URL</li>
					</ul>';
		
					echo '<ul class="search">
					<li></li>
					<li class="id"><input name="search_id" type="text"></li>
					<li><select name="type"><option value=""></option>'.$item_type_options.'</select></li>
					<li><select name="status"><option value=""></option>'.$url_statuses.'</select></li>
					<li><input name="search_title" type="text"></li>
					<li><input name="flat_url" type="text"></li>
					<li><input name="hierarchy_url" type="text"></li>
					</ul>';
					
					if($assign_item_rows)
					{
						foreach($assign_item_rows as $assign_item_row)
						{
							//echo '<pre>'; print_r($assign_item_row); echo '</pre>';
							$inventory_ids = array();
							$select_inventory_placeholders = '';
							
							echo '<ul class="background-color-f5f5f5">
							<li class="center">
							<label>
							<input name="items['.$items_counter.'][item_id]" type="checkbox" value="'.$assign_item_row["urls_id"].'">
							<input name="items['.$items_counter.'][item_type]" type="hidden" value="'.$assign_item_row["table_name"].'">
							<input name="items['.$items_counter.'][table_name]" type="hidden" value="'.$assign_item_row["table_name"].'">
							</label>
							</li>
							<li class="center">'.$assign_item_row["id"].'</li>
							<li>'.ucwords($assign_item_row["table_name"]).'</li>
							<li>'.$url_status_options[$assign_item_row["url_status"]]['label'].'</li>
							<li>'.$assign_item_row["meta_title"].'</li>
							<li>'.$assign_item_row["flat_url"].'</li>
							<li>'.$assign_item_row["hierarchy_url"].'</li>
							</ul>';
							$items_counter = $items_counter + 1;
							
							if(!empty(trim($assign_item_row['inventory_assigned'] ?? '', ',')))
							{
								if(strpos(trim($assign_item_row['inventory_assigned'] ?? '', ','), ',') !== false)
								{
									$inventory_ids_exploded = explode(',', trim($assign_item_row['inventory_assigned'] ?? '', ','));
									foreach($inventory_ids_exploded as $inventory_status_ids)
									{
										$inventory_status_id = explode('|', $inventory_status_ids);
										$inventory_ids[] = $inventory_status_id[1];
										$select_inventory_placeholders .= '?,';
									}
								}
								else
								{
									$inventory_status_id = explode('|', trim($assign_item_row['inventory_assigned'] ?? '', ','));
									$inventory_ids[] = $inventory_status_id[1];
									$select_inventory_placeholders .= '?,';
								}
								
								if(!empty($inventory_ids))
								{
									
									$select_inventory_placeholders = trim($select_inventory_placeholders, ',');
									$select_inventory_ids = array_merge($inventory_ids, $inventory_ids);
									
									$sql_get_inventory_assigned_row = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'inventory', 'WHERE `id` IN ('.$select_inventory_placeholders.') ORDER BY FIELD(`id`, '.$select_inventory_placeholders.')', $select_inventory_ids, 'id');
									
									foreach($sql_get_inventory_assigned_row as $sql_get_inventory_assigned_rows)
									{
										if(!empty($sql_get_inventory_assigned_rows))
										{
											echo '<ul>
											<li class="center"><label>
											<input name="items['.$items_counter.'][item_id]" type="checkbox" value="'.$assign_item_row["urls_id"].'">
											<input name="items['.$items_counter.'][item_type]" type="hidden" value="inventory">
											<input name="items['.$items_counter.'][table_name]" type="hidden" value="products">
											<input name="items['.$items_counter.'][inventory_id]" type="hidden" value="'.$sql_get_inventory_assigned_rows["id"].'">
											</label>
											</li>
											<li class="center">'.$sql_get_inventory_assigned_rows["id"].'</li>
											<li>Inventory</li>
											<li>'.$url_status_options[$sql_get_inventory_assigned_rows["status"]]['label'].'</li>
											<li>'.$sql_get_inventory_assigned_rows["name"].'</li>
											<li>N/A</li>
											<li>N/A</li>
											</ul>';
												
											$items_counter = $items_counter + 1;
										}
									}
								}
							}
						}
						echo '</div>';
					}
					else
					{
						echo '</div><div class="no-results">No Results</div>';
					}   
				}
				?>
				</div>
				<div class="edit-field-padding display-as-none" id="items-submit">
				<button name="assign-items" type="submit">Assign Selected Items</button>
				</div>
			  </form>
			</div>
			<!-- End Search Table -->
			</div>
		</div>
		
		<form method="POST">
            <div class="edit-label">Blocks</div>
            <div class="sortGroups">
                <?php 
                //Select groups assigned to page.
                $sql_pages_groups = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'page_groups', 'WHERE `site_id` = ? AND `table_name` = ? AND `urls_id` = ? ORDER BY `sort` ASC', [$_SESSION["site_set_for_editing"], $_SESSION['admin_table_name'], trim($_GET["rid"] ?? '')]);
                
                $all_group_data = array();
                
                if(!empty($sql_pages_groups))
                {
                    foreach($sql_pages_groups as $sql_pages_groups_rows)
                    {
                        $sql_assigned_item_array = array();
                        $sub_item_array = array();
                        
                        $sql_pages_group["group_id"] = $sql_pages_groups_rows["id"];
                        $sql_pages_group["group_status"] = $sql_pages_groups_rows["status"];
                        $sql_pages_group["group_name"] = $sql_pages_groups_rows["name"];
						$sql_pages_group["group_sub_items_type"] = $sql_pages_groups_rows["sub_items_type"];
						$sql_pages_group["group_sub_items_code"] = $sql_pages_groups_rows["sub_items_code"];
                        $sql_pages_group["group_sub_items_load_template_include_file"] = $sql_pages_groups_rows["sub_items_load_template_include_file"];
                        $sql_pages_group["group_title"] = $sql_pages_groups_rows["title"];
                        $sql_pages_group["group_content"] = $sql_pages_groups_rows["content"];
                        $sql_pages_group["group_columns"] = $sql_pages_groups_rows["columns"];
                        $sql_pages_group["group_display_text_from_sub_items"] = $sql_pages_groups_rows["display_text_from_sub_items"];
                        $sql_pages_group["group_gap_between_items"] = $sql_pages_groups_rows["gap_between_items"];
                        $sql_pages_group["group_outter_css_box_styles"] = $sql_pages_groups_rows["outter_css_box_styles"];
                        $sql_pages_group["group_inner_css_box_styles"] = $sql_pages_groups_rows["inner_css_box_styles"];
                        $sql_pages_group["group_display_as_slider"] = $sql_pages_groups_rows["display_as_slider"];
                        $sql_pages_group["group_slides_in_view"] = $sql_pages_groups_rows["slides_in_view"];
                        $sql_pages_group["group_slide_all_at_once"] = $sql_pages_groups_rows["slide_all_at_once"];
                        $sql_pages_group["group_slide_minimum_width"] = $sql_pages_groups_rows["slide_minimum_width"];
                        $sql_pages_group["group_auto_slide_media"] = $sql_pages_groups_rows["auto_slide_media"];
                        $sql_pages_group["group_pause_time"] = $sql_pages_groups_rows["pause_time"];
                        $sql_pages_group["group_slide_speed"] = $sql_pages_groups_rows["slide_speed"];
                        $sql_pages_group["group_slide_margin"] = $sql_pages_groups_rows["slide_margin"];
                        $sql_pages_group["group_display_pagination"] = $sql_pages_groups_rows["display_pagination"];
                        $sql_pages_group["group_pagination_alignment"] = $sql_pages_groups_rows["pagination_alignment"];
                        $sql_pages_group["group_pagination_over_image"] = $sql_pages_groups_rows["pagination_over_image"];
                        $sql_pages_group["group_display_thumbnails"] = $sql_pages_groups_rows["display_thumbnails"];
                        $sql_pages_group["group_pagination_thumbnail_width"] = $sql_pages_groups_rows["pagination_thumbnail_width"];
                        $sql_pages_group["group_pagination_margin"] = $sql_pages_groups_rows["pagination_margin"];
                        $sql_pages_group["group_lazy_load_media"] = $sql_pages_groups_rows["lazy_load_media"];
                        
                        //Select items assigned to the group
                        $sql_assigned_item = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'assignments_sub_items', 'WHERE `site_id` = ? AND `pages_groups_id` = ? AND `parent_id` = ? ORDER BY `sort` ASC;', [$_SESSION["site_set_for_editing"], $sql_pages_groups_rows["id"], trim($_GET["rid"] ?? '')]);
                        
                        if(!empty($sql_assigned_item)) 
                        {
                            foreach($sql_assigned_item as $sql_assigned_item_rows)
                            {
                                $sql_get_page_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', $sql_assigned_item_rows["child_id_table_name"], 'WHERE `urls_id` = ? AND (`site_id` = ? OR `site_id` IS NULL) LIMIT 1', [$sql_assigned_item_rows["child_id"], $_SESSION["site_set_for_editing"]]);
                                
                                $urls_record_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ? AND `site_id` = ? AND `table_name` = ?', [$sql_get_page_data['urls_id'], $_SESSION["site_set_for_editing"], $sql_assigned_item_rows["child_id_table_name"]]);
                                
                                if(!empty($sql_get_page_data) && !empty($urls_record_data))
                                {
                                    $sql_assigned_item_array[] = array_merge($sql_assigned_item_rows, array('pages_data' => $sql_get_page_data) , array('url_record_data' => $urls_record_data));
                                }
                            }
                            
                            if(!empty($sql_assigned_item_array))
                            {
                                $sub_item_array = array("sub_items" => $sql_assigned_item_array);
                            }
                        }
                        
                        $all_group_data[] = array_merge($sql_pages_group, $sub_item_array);
                    }
                }
				
                $group_counter = 0;
                $row_counter = 0;
                if(!empty($all_group_data))
                {
                    foreach($all_group_data as $all_groups) 
                    { 
                    ?>
                        <!-- Start Group -->
                        <script nonce="<?php echo NONCE; ?>">
                        $(function(){ $(".toggle-group-<?php echo $group_counter; ?>").click(function(){ $(".toggle-group-table-<?php echo $group_counter; ?>").slideToggle(); }); });
                        $(function(){ $(".toggle-group-<?php echo $group_counter; ?>").click(function(){ $(".toggle-group-<?php echo $group_counter; ?>").toggleClass("toggle-group-arrow"); }); });
                        </script>
                        <div class="edit small-margin group_<?php echo $group_counter; ?>">
                            <div class="edit-label header">
                                <div class="group-wrapper">
                                    <div class="group-left">
                                        <div class="toggle-group toggle-group-<?php echo $group_counter; ?>" title="Open / Close Group"><i class="arrow content-arrow"><svg viewBox="0 0 512 512"><path d="M12 127A19 19 0 0 0 12 154L242 385A19 19 0 0 0 270 385L500 154A19 19 0 0 0 473 127L256 344 39 127A19 19 0 0 0 12 127"></path></svg></i></div>
                                        <div class="order-group" title="Drag & Drop Group Order"><i><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i></div>
                                        <div class="name-group">
                                            <input name="groups[<?php echo $group_counter; ?>][group_name]" type="text" value="<?php echo htmlspecialchars($all_groups["group_name"] ?? ''); ?>" placeholder="Block Name">
                                            <input name="groups[<?php echo $group_counter; ?>][group_id]" type="hidden" value="<?php echo htmlspecialchars($all_groups["group_id"] ?? ''); ?>">
                                        </div>
                                    </div>
                                    <div class="group-right">
                                        <input name="groups[<?php echo $group_counter; ?>][group_status]" id="group-status-<?php echo $group_counter; ?>" type="hidden" value="<?php echo htmlspecialchars($all_groups["group_status"] ?? ''); ?>"><?php if($all_groups["group_status"] == 1) { $display_group_status = '<span class="groupStatus" data-click="'.trim($_GET["rid"] ?? '').','.$group_counter.','.htmlspecialchars($all_groups["group_id"] ?? '').','.htmlspecialchars($all_groups["group_status"] ?? '').'"><i><svg viewBox="0 0 512 512"><path d="M507 256S413 83 256 83 5 256 5 256 99 429 256 429 507 256 507 256M41 256A409 409 0 0 1 94 192C134 151 189 115 256 115S378 151 418 192A409 409 0 0 1 471 256Q468 260 465 265C454 280 438 300 418 320 378 361 323 397 256 397S134 361 94 320A409 409 0 0 1 41 256ZM256 177A79 79 0 1 0 256 335 79 79 0 0 0 256 177M146 256A110 110 0 1 1 366 256 110 110 0 0 1 146 256"></path></svg></i></span>';}
                                        elseif($all_groups["group_status"] == 2) { $display_group_status = '<span class="groupStatus" data-click="'.trim($_GET["rid"] ?? '').','.$group_counter.','.htmlspecialchars($all_groups["group_id"] ?? '').','.htmlspecialchars($all_groups["group_status"] ?? '').'"><i><svg viewBox="0 0 512 512"><path d="M424 358C478 310 507 256 507 256s-94-173-251-173a220 220 0 0 0-88 18l24 24A189 189 0 0 1 256 115c67 0 122 37 162 77A409 409 0 0 1 471 256q-3 4-6 9c-11 15-26 35-46 55q-8 8-16 15zM360 293a110 110 0 0 0-141-141l26 26a79 79 0 0 1 89 89zm-92 41 26 26a110 110 0 0 1-141-141l26 26a79 79 0 0 0 89 89M110 176q-8 8-16 15A409 409 0 0 0 41 256l6 9c11 15 26 35 46 55C134 361 189 397 256 397c22 0 44-4 63-11l24 24A220 220 0 0 1 256 429C99 429 5 256 5 256s30-54 83-102l22 22zm324 279-377-377 22-22 377 377z"></path></svg></i></span>';}
                                        ?>
                                        <div class="status-group" id="status-group-<?php echo $group_counter; ?>" title="Enable / Disable Block">
                                            <?php echo $display_group_status; ?>
                                        </div>
                                        <div class="delete-group removeGroup" title="Delete Block" data-click="<?php echo $group_counter; ?>"><i><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i></div>
                                    </div>
                                </div>
                            </div>
                        
                            <?php 
                            $display_as_none = '';
                            if($group_name != $all_groups["group_id"]) { $display_as_none = ' display-as-none'; }
                            ?>
                            <div class="edit-field toggle-group-table toggle-group-table-<?php echo $group_counter; ?><?php echo $display_as_none; ?>">
                                <div class="content-group">
                                    <label>
                                        <div class="name">Sub Item Type</div>
                                        <select name="groups[<?php echo $group_counter; ?>][group_sub_items_type]" data-sub-item-id="<?php echo $group_counter; ?>" class="sub-item-type">
                                            <option value=""<?php if($all_groups["group_sub_items_type"] == '') { echo ' selected'; }?>>Select Sub Item Type</option>
                                            <option value="Code (html/css)"<?php if($all_groups["group_sub_items_type"] == 'Code (html/css)') { echo ' selected'; }?>>Code (html/css)</option>
                                            <option value="Include File"<?php if($all_groups["group_sub_items_type"] == 'Include File') { echo ' selected'; }?>>Include File</option>
                                            <option value="Sub Items"<?php if($all_groups["group_sub_items_type"] == 'Sub Items') { echo ' selected'; }?>>Sub Items</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="code-<?php echo $group_counter; ?>">
                                    <div class="content-group">
                                        <label>
                                            <div class="name">HTML / CSS Code</div>
                                            <textarea name="groups[<?php echo $group_counter; ?>][group_sub_items_code]" cols="" rows="20" placeholder="Add HTML and CSS code."><?php echo $all_groups["group_sub_items_code"]; ?></textarea>
                                        </label>
                                    </div>
                                </div>
                                <div class="include-file-<?php echo $group_counter; ?>">
                                    <div class="content-group">
                                        <label>
                                            <div class="name">Template Include File</div>
                                            <select name="groups[<?php echo $group_counter; ?>][group_sub_items_load_template_include_file]" class="display-with-file-include">
                                                <option value=""<?php if($all_groups["group_sub_items_load_template_include_file"] == '') { echo ' selected'; }?>>Select Include File</option>
                                                <?php 
                                                if(!empty($template_include_files))
                                                {
                                                    foreach($template_include_files as $template_include_file)
                                                    {
                                                        $selected = '';
                                                        if($all_groups["group_sub_items_load_template_include_file"] == $template_include_file['filename']) { $selected = ' selected'; }
                                                        echo '<option value="'.$template_include_file['filename'].'"'.$selected.'>Yes: '.$template_include_file['filename'].'</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                </div>
                                <div class="sub-items-file-include-<?php echo $group_counter; ?>">
                                    <div class="content-group">
                                        <label>
                                            <div class="name">&lt;/h2&gt; Title for This Block</div>
                                            <input name="groups[<?php echo $group_counter; ?>][group_title]" value="<?php echo $all_groups["group_title"]; ?>" type="text">
                                        </label>
                                        
                                        <label>
                                            <div class="name">Content or Code Above Block</div>
                                            <textarea name="groups[<?php echo $group_counter; ?>][group_content]" cols="" rows="5" placeholder="Include content and HTML, as this field displays special characters."><?php echo $all_groups["group_content"]; ?></textarea>
                                        </label>
                                        
                                        <label>
                                            <div class="name">Display Links & Text Below Sub Items</div>
                                            <select name="groups[<?php echo $group_counter; ?>][group_display_text_from_sub_items]">
                                              <option value="Yes"<?php if($all_groups["group_display_text_from_sub_items"] == '') { echo ' selected'; }?>>Yes</option>
                                              <option value="No"<?php if($all_groups["group_display_text_from_sub_items"] == 'No') { echo ' selected'; }?>>No</option>
                                            </select>
                                        </label>
                                        
                                        <label>
                                            <div class="name">Gap Between Items</div>
                                            <input name="groups[<?php echo $group_counter; ?>][group_gap_between_items]" value="<?php echo $all_groups["group_gap_between_items"]; ?>" type="text" placeholder="e.g., 5">
                                            <div class="small-text"><strong>Default code:</strong> 5</div>
                                        </label>
                                        
                                        <label>
                                            <div class="name">Outter CSS Box Styles</div>
                                            <input name="groups[<?php echo $group_counter; ?>][group_outter_css_box_styles]" value="<?php if($all_groups["group_outter_css_box_styles"] != NULL) { echo $all_groups["group_outter_css_box_styles"]; } ?>" type="text">
                                            <div class="small-text"><strong>Default code:</strong> background: repeating-linear-gradient(45deg, #ffffff, #f3f3f3 2px, #e7e7e7 2px, #ffffff 5px); border-top: 1px solid #e7e7e7; border-bottom: 1px solid #e7e7e7; margin: 50px 0px  0px 0px; padding: 50px 10px 50px 10px;</div>
                                        </label>
                                        
                                        <label>
                                            <div class="name">Inner CSS Box Styles</div>
                                            <input name="groups[<?php echo $group_counter; ?>][group_inner_css_box_styles]" value="<?php if($all_groups["group_inner_css_box_styles"] != NULL) { echo $all_groups["group_inner_css_box_styles"]; } ?>" type="text">
                                            <div class="small-text"><strong>Default code:</strong> background-color: #ffffff; padding: 10px 5px; border-radius: 10px;</div>
                                        </label>
                                        
                                        <label>
                                            <div class="name">Lazy Load Media in This Block</div>
                                            <select name="groups[<?php echo $group_counter; ?>][group_lazy_load_media]">
                                              <option value="Yes"<?php if($all_groups["group_lazy_load_media"] == 'Yes') { echo ' selected'; }?>>Yes</option>
                                              <option value="No"<?php if($all_groups["group_lazy_load_media"] == 'No') { echo ' selected'; }?>>No</option>
                                            </select>
                                        </label>
                                        
                                        <label>
                                            <div class="name">Image Fetch Priority in This Block</div>
                                            <select name="groups[<?php echo $group_counter; ?>][group_image_fetch_priority]">
                                              <option value="Yes"<?php if($all_groups["group_lazy_load_media"] == 'Yes') { echo ' selected'; }?>>FetchPriority=High</option>
                                              <option value="No"<?php if($all_groups["group_lazy_load_media"] == 'No') { echo ' selected'; }?>>FetchPriority=Auto</option>
                                            </select>
                                        </label>
                                        
                                        <?php
                                        //Get CSS grid columns
                                        $grid_column_options = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', ['css_grid_columns'], 'value');
                                        $grid_columns = '';
                                        if(!empty($grid_column_options))
                                        {
                                            foreach($grid_column_options as $grid_column_option)
                                            {
                                                $selected = '';
                                                if(!empty($all_groups["group_columns"]) && $all_groups["group_columns"] == $grid_column_option['value'])
                                                {
                                                    $selected = ' selected';
                                                }
                                                
                                                $grid_columns .= '<option value="'.$grid_column_option['value'].'"'.$selected.'>'.$grid_column_option['label'].'</option>';
                                            }
                                        }
                                        ?>
                                        <label>
                                            <div class="name">Number of Columns to Show</div>
                                            <select name="groups[<?php echo $group_counter; ?>][group_columns]">
                                              <?php echo $grid_columns; ?>
                                            </select>
                                        </label>
                                        
                                        <label>
                                            <div class="name">Display Block as Slider</div>
                                            <select name="groups[<?php echo $group_counter; ?>][group_display_as_slider]" id="display-as-slider-<?php echo $group_counter; ?>" data-slider-id="<?php echo $group_counter; ?>" class="display-as-slider">
                                              <option value="Yes"<?php if($all_groups["group_display_as_slider"] == 'Yes') { echo ' selected'; }?>>Yes</option>
                                              <option value="No"<?php if($all_groups["group_display_as_slider"] == 'No') { echo ' selected'; }?>>No</option>
                                            </select>
                                        </label>
                                        
                                        <div class="sub-items-slider sub-items-slider-<?php echo $group_counter; ?>">
                                            <div class="sub-items-slider-options">Slider Options</div>
                                            
                                            <label>
                                                <div class="name">Slides Visible at Once</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_slides_in_view]">
                                                  <option value="1"<?php if($all_groups["group_slides_in_view"] == '1') { echo ' selected'; }?>>1</option>
                                                  <option value="2"<?php if($all_groups["group_slides_in_view"] == '2') { echo ' selected'; }?>>2</option>
                                                  <option value="3"<?php if($all_groups["group_slides_in_view"] == '3') { echo ' selected'; }?>>3</option>
                                                  <option value="4"<?php if($all_groups["group_slides_in_view"] == '4') { echo ' selected'; }?>>4</option>
                                                  <option value="5"<?php if($all_groups["group_slides_in_view"] == '5') { echo ' selected'; }?>>5</option>
                                                  <option value="6"<?php if($all_groups["group_slides_in_view"] == '6') { echo ' selected'; }?>>6</option>
                                                  <option value="7"<?php if($all_groups["group_slides_in_view"] == '7') { echo ' selected'; }?>>7</option>
                                                  <option value="8"<?php if($all_groups["group_slides_in_view"] == '8') { echo ' selected'; }?>>8</option>
                                                  <option value="9"<?php if($all_groups["group_slides_in_view"] == '9') { echo ' selected'; }?>>9</option>
                                                  <option value="10"<?php if($all_groups["group_slides_in_view"] == '10') { echo ' selected'; }?>>10</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Slide All at Once</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_slide_all_at_once]">
                                                  <option value="Yes"<?php if($all_groups["group_slide_all_at_once"] == 'Yes') { echo ' selected'; }?>>Yes</option>
                                                  <option value="No"<?php if($all_groups["group_slide_all_at_once"] == 'No') { echo ' selected'; }?>>No</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Minimum Slide Width</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_slide_minimum_width]">
                                                  <option value="50"<?php if($all_groups["group_slide_minimum_width"] == '50') { echo ' selected'; }?>>50px</option>
                                                  <option value="75"<?php if($all_groups["group_slide_minimum_width"] == '75') { echo ' selected'; }?>>75px</option>
                                                  <option value="100"<?php if($all_groups["group_slide_minimum_width"] == '100') { echo ' selected'; }?>>100px</option>
                                                  <option value="125"<?php if($all_groups["group_slide_minimum_width"] == '125') { echo ' selected'; }?>>125px</option>
                                                  <option value="150"<?php if($all_groups["group_slide_minimum_width"] == '150') { echo ' selected'; }?>>150px</option>
                                                  <option value="175"<?php if($all_groups["group_slide_minimum_width"] == '175') { echo ' selected'; }?>>175px</option>
                                                  <option value="200"<?php if($all_groups["group_slide_minimum_width"] == '200') { echo ' selected'; }?>>200px</option>
                                                  <option value="225"<?php if($all_groups["group_slide_minimum_width"] == '225') { echo ' selected'; }?>>225px</option>
                                                  <option value="250"<?php if($all_groups["group_slide_minimum_width"] == '250') { echo ' selected'; }?>>250px</option>
                                                  <option value="275"<?php if($all_groups["group_slide_minimum_width"] == '275') { echo ' selected'; }?>>275px</option>
                                                  <option value="300"<?php if($all_groups["group_slide_minimum_width"] == '300') { echo ' selected'; }?>>300px</option>
                                                  <option value="325"<?php if($all_groups["group_slide_minimum_width"] == '325') { echo ' selected'; }?>>325px</option>
                                                  <option value="350"<?php if($all_groups["group_slide_minimum_width"] == '350') { echo ' selected'; }?>>350px</option>
                                                  <option value="375"<?php if($all_groups["group_slide_minimum_width"] == '375') { echo ' selected'; }?>>375px</option>
                                                  <option value="400"<?php if($all_groups["group_slide_minimum_width"] == '400') { echo ' selected'; }?>>400px</option>
                                                  <option value="425"<?php if($all_groups["group_slide_minimum_width"] == '425') { echo ' selected'; }?>>425px</option>
                                                  <option value="450"<?php if($all_groups["group_slide_minimum_width"] == '450') { echo ' selected'; }?>>450px</option>
                                                  <option value="475"<?php if($all_groups["group_slide_minimum_width"] == '475') { echo ' selected'; }?>>475px</option>
                                                  <option value="500"<?php if($all_groups["group_slide_minimum_width"] == '500') { echo ' selected'; }?>>500px</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Auto-Slide Media</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_auto_slide_media]">
                                                  <option value="Yes"<?php if($all_groups["group_auto_slide_media"] == 'Yes') { echo ' selected'; }?>>Yes</option>
                                                  <option value="No"<?php if($all_groups["group_auto_slide_media"] == 'No') { echo ' selected'; }?>>No</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Pause Time Between Auto-Slides</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_pause_time]">
                                                  <option value="1000"<?php if($all_groups["group_pause_time"] == '1000') { echo ' selected'; }?>>1 second</option>
                                                  <option value="2000"<?php if($all_groups["group_pause_time"] == '2000') { echo ' selected'; }?>>2 seconds</option>
                                                  <option value="3000"<?php if($all_groups["group_pause_time"] == '3000') { echo ' selected'; }?>>3 seconds</option>
                                                  <option value="4000"<?php if($all_groups["group_pause_time"] == '4000') { echo ' selected'; }?>>4 seconds</option>
                                                  <option value="5000"<?php if($all_groups["group_pause_time"] == '5000') { echo ' selected'; }?>>5 seconds</option>
                                                  <option value="6000"<?php if($all_groups["group_pause_time"] == '6000') { echo ' selected'; }?>>6 seconds</option>
                                                  <option value="7000"<?php if($all_groups["group_pause_time"] == '7000') { echo ' selected'; }?>>7 seconds</option>
                                                  <option value="8000"<?php if($all_groups["group_pause_time"] == '8000') { echo ' selected'; }?>>8 seconds</option>
                                                  <option value="9000"<?php if($all_groups["group_pause_time"] == '9000') { echo ' selected'; }?>>9 seconds</option>
                                                  <option value="10000"<?php if($all_groups["group_pause_time"] == '10000') { echo ' selected'; }?>>10 seconds</option>
                                                  <option value="12000"<?php if($all_groups["group_pause_time"] == '12000') { echo ' selected'; }?>>12 seconds</option>
                                                  <option value="15000"<?php if($all_groups["group_pause_time"] == '15000') { echo ' selected'; }?>>15 seconds</option>
                                                  <option value="20000"<?php if($all_groups["group_pause_time"] == '20000') { echo ' selected'; }?>>20 seconds</option>
                                                  <option value="25000"<?php if($all_groups["group_pause_time"] == '25000') { echo ' selected'; }?>>25 seconds</option>
                                                  <option value="30000"<?php if($all_groups["group_pause_time"] == '30000') { echo ' selected'; }?>>30 seconds</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Slide Transition Speed</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_slide_speed]">
                                                  <option value="100"<?php if($all_groups["group_slide_speed"] == '100') { echo ' selected'; }?>>0.1 seconds</option>
                                                  <option value="200"<?php if($all_groups["group_slide_speed"] == '200') { echo ' selected'; }?>>0.2 seconds</option>
                                                  <option value="300"<?php if($all_groups["group_slide_speed"] == '300') { echo ' selected'; }?>>0.3 seconds</option>
                                                  <option value="400"<?php if($all_groups["group_slide_speed"] == '400') { echo ' selected'; }?>>0.4 seconds</option>
                                                  <option value="500"<?php if($all_groups["group_slide_speed"] == '500') { echo ' selected'; }?>>0.5 seconds</option>
                                                  <option value="600"<?php if($all_groups["group_slide_speed"] == '600') { echo ' selected'; }?>>0.6 seconds</option>
                                                  <option value="700"<?php if($all_groups["group_slide_speed"] == '700') { echo ' selected'; }?>>0.7 seconds</option>
                                                  <option value="800"<?php if($all_groups["group_slide_speed"] == '800') { echo ' selected'; }?>>0.8 seconds</option>
                                                  <option value="900"<?php if($all_groups["group_slide_speed"] == '900') { echo ' selected'; }?>>0.9 seconds</option>
                                                  <option value="1000"<?php if($all_groups["group_slide_speed"] == '1000') { echo ' selected'; }?>>1 second</option>
                                                  <option value="2000"<?php if($all_groups["group_slide_speed"] == '2000') { echo ' selected'; }?>>2 seconds</option>
                                                  <option value="3000"<?php if($all_groups["group_slide_speed"] == '3000') { echo ' selected'; }?>>3 seconds</option>
                                                  <option value="4000"<?php if($all_groups["group_slide_speed"] == '4000') { echo ' selected'; }?>>4 seconds</option>
                                                  <option value="5000"<?php if($all_groups["group_slide_speed"] == '5000') { echo ' selected'; }?>>5 seconds</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Gap Between Items</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_slide_margin]">
                                                  <option value="0"<?php if($all_groups["group_slide_margin"] == '0') { echo ' selected'; }?>>0px</option>
                                                  <option value="1"<?php if($all_groups["group_slide_margin"] == '1') { echo ' selected'; }?>>1px</option>
                                                  <option value="2"<?php if($all_groups["group_slide_margin"] == '2') { echo ' selected'; }?>>2px</option>
                                                  <option value="3"<?php if($all_groups["group_slide_margin"] == '3') { echo ' selected'; }?>>3px</option>
                                                  <option value="4"<?php if($all_groups["group_slide_margin"] == '4') { echo ' selected'; }?>>4px</option>
                                                  <option value="5"<?php if($all_groups["group_slide_margin"] == '5') { echo ' selected'; }?>>5px</option>
                                                  <option value="6"<?php if($all_groups["group_slide_margin"] == '6') { echo ' selected'; }?>>6px</option>
                                                  <option value="7"<?php if($all_groups["group_slide_margin"] == '7') { echo ' selected'; }?>>7px</option>
                                                  <option value="8"<?php if($all_groups["group_slide_margin"] == '8') { echo ' selected'; }?>>8px</option>
                                                  <option value="9"<?php if($all_groups["group_slide_margin"] == '9') { echo ' selected'; }?>>9px</option>
                                                  <option value="10"<?php if($all_groups["group_slide_margin"] == '10') { echo ' selected'; }?>>10px</option>
                                                  <option value="15"<?php if($all_groups["group_slide_margin"] == '15') { echo ' selected'; }?>>15px</option>
                                                  <option value="20"<?php if($all_groups["group_slide_margin"] == '20') { echo ' selected'; }?>>20px</option>
                                                  <option value="30"<?php if($all_groups["group_slide_margin"] == '30') { echo ' selected'; }?>>30px</option>
                                                  <option value="50"<?php if($all_groups["group_slide_margin"] == '50') { echo ' selected'; }?>>50px</option>
                                                  <option value="70"<?php if($all_groups["group_slide_margin"] == '70') { echo ' selected'; }?>>70px</option>
                                                  <option value="100"<?php if($all_groups["group_slide_margin"] == '100') { echo ' selected'; }?>>100px</option>
                                                  <option value="125"<?php if($all_groups["group_slide_margin"] == '125') { echo ' selected'; }?>>125px</option>
                                                  <option value="150"<?php if($all_groups["group_slide_margin"] == '150') { echo ' selected'; }?>>150px</option>
                                                  <option value="200"<?php if($all_groups["group_slide_margin"] == '200') { echo ' selected'; }?>>200px</option>
                                                  <option value="250"<?php if($all_groups["group_slide_margin"] == '250') { echo ' selected'; }?>>250px</option>
                                                  <option value="300"<?php if($all_groups["group_slide_margin"] == '300') { echo ' selected'; }?>>300px</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Display Pagination Controls</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_display_pagination]">
                                                  <option value="Yes"<?php if($all_groups["group_display_pagination"] == 'Yes') { echo ' selected'; }?>>Yes</option>
                                                  <option value="No"<?php if($all_groups["group_display_pagination"] == 'No') { echo ' selected'; }?>>No</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Pagination Alignment</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_pagination_alignment]">
                                                  <option value="left"<?php if($all_groups["group_pagination_alignment"] == 'left') { echo ' selected'; }?>>Left</option>
                                                  <option value="center"<?php if($all_groups["group_pagination_alignment"] == 'center') { echo ' selected'; }?>>Center</option>
                                                  <option value="right"<?php if($all_groups["group_pagination_alignment"] == 'right') { echo ' selected'; }?>>Right</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Display Slide Image as Thumbnail</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_display_thumbnails]">
                                                  <option value="Yes"<?php if($all_groups["group_display_thumbnails"] == 'Yes') { echo ' selected'; }?>>Yes</option>
                                                  <option value="No"<?php if($all_groups["group_display_thumbnails"] == 'No') { echo ' selected'; }?>>No</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Thumbnail Width for Pagination</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_pagination_thumbnail_width]">
                                                  <option value="10"<?php if($all_groups["group_pagination_thumbnail_width"] == '10') { echo ' selected'; }?>>10px</option>
                                                  <option value="20"<?php if($all_groups["group_pagination_thumbnail_width"] == '20') { echo ' selected'; }?>>20px</option>
                                                  <option value="30"<?php if($all_groups["group_pagination_thumbnail_width"] == '30') { echo ' selected'; }?>>30px</option>
                                                  <option value="40"<?php if($all_groups["group_pagination_thumbnail_width"] == '40') { echo ' selected'; }?>>40px</option>
                                                  <option value="50"<?php if($all_groups["group_pagination_thumbnail_width"] == '50') { echo ' selected'; }?>>50px</option>
                                                  <option value="60"<?php if($all_groups["group_pagination_thumbnail_width"] == '60') { echo ' selected'; }?>>60px</option>
                                                  <option value="70"<?php if($all_groups["group_pagination_thumbnail_width"] == '70') { echo ' selected'; }?>>70px</option>
                                                  <option value="80"<?php if($all_groups["group_pagination_thumbnail_width"] == '80') { echo ' selected'; }?>>80px</option>
                                                  <option value="90"<?php if($all_groups["group_pagination_thumbnail_width"] == '90') { echo ' selected'; }?>>90px</option>
                                                  <option value="100"<?php if($all_groups["group_pagination_thumbnail_width"] == '100') { echo ' selected'; }?>>100px</option>
                                                </select>
                                            </label>
                                            
                                            <label>
                                                <div class="name">Margin Between Pagination Items</div>
                                                <select name="groups[<?php echo $group_counter; ?>][group_pagination_margin]">
                                                  <option value="0"<?php if($all_groups["group_pagination_margin"] == '0') { echo ' selected'; }?>>0px</option>
                                                  <option value="1"<?php if($all_groups["group_pagination_margin"] == '1') { echo ' selected'; }?>>1px</option>
                                                  <option value="2"<?php if($all_groups["group_pagination_margin"] == '2') { echo ' selected'; }?>>2px</option>
                                                  <option value="3"<?php if($all_groups["group_pagination_margin"] == '3') { echo ' selected'; }?>>3px</option>
                                                  <option value="5"<?php if($all_groups["group_pagination_margin"] == '5') { echo ' selected'; }?>>5px</option>
                                                  <option value="6"<?php if($all_groups["group_pagination_margin"] == '6') { echo ' selected'; }?>>6px</option>
                                                  <option value="7"<?php if($all_groups["group_pagination_margin"] == '7') { echo ' selected'; }?>>7px</option>
                                                  <option value="8"<?php if($all_groups["group_pagination_margin"] == '8') { echo ' selected'; }?>>8px</option>
                                                  <option value="9"<?php if($all_groups["group_pagination_margin"] == '9') { echo ' selected'; }?>>9px</option>
                                                  <option value="10"<?php if($all_groups["group_pagination_margin"] == '10') { echo ' selected'; }?>>10px</option>
                                                  <option value="15"<?php if($all_groups["group_pagination_margin"] == '15') { echo ' selected'; }?>>15px</option>
                                                  <option value="20"<?php if($all_groups["group_pagination_margin"] == '20') { echo ' selected'; }?>>20px</option>
                                                  <option value="30"<?php if($all_groups["group_pagination_margin"] == '30') { echo ' selected'; }?>>30px</option>
                                                  <option value="50"<?php if($all_groups["group_pagination_margin"] == '50') { echo ' selected'; }?>>50px</option>
                                                </select>  
                                            </label>           
                                        </div>
                                    </div>
                                    
                                    <!-- Start Table -->
                                    <div class="sub-categories-overflow">
                                        <div class="sub-categories-table">
                                        <ul class="sub-categories-row header">
                                        <li class="sub-categories-cell header center">Order</li>
                                        <li class="sub-categories-cell header center">Status</li>
                                        <li class="sub-categories-cell header center">Type</li>
                                        <li class="sub-categories-cell header center">ID</li>
                                        <li class="sub-categories-cell header">Title</li>
                                        <li class="sub-categories-cell header center">Remove</li>
                                        </ul>
                                            <div class="sortCategories">
                                                <?php 
                                                if(!empty($all_groups["sub_items"]))
                                                {
                                                    foreach($all_groups["sub_items"] as $items_assigned) 
                                                    {
                                                        //echo '<pre>'; print_r($items_assigned); echo '</pre>'; 
                                                        $frontend_url = '';
                                                        
                                                        if($items_assigned['pages_data']['urls_id'] != $home_page)
                                                        {
                                                            if($sites["url_structure"] == 'Hierarchy')
                                                            {
                                                                $url_name = $items_assigned['url_record_data']['hierarchy_url'];
                                                            }
                                                            else
                                                            {
                                                                $url_name = $items_assigned['url_record_data']['flat_url'];
                                                            }
                                                            
                                                            if(!empty($items_assigned['url_record_data']['url_extension']))
                                                            {
                                                                $end_url_with = $items_assigned['url_record_data']['url_extension'];
                                                            }
                                                            else
                                                            {
                                                                $end_url_with = $sites["global_url_extension"];
                                                            }
                                                            
                                                            if(!empty($items_assigned['url_record_data']['custom_link']))
                                                            {
                                                                $frontend_url = $items_assigned['url_record_data']['custom_link'];
                                                            }
                                                            else
                                                            {
                                                                $frontend_url = $domain.'/'.$url_name.$end_url_with;
                                                            }
                                                        }
                                                        else
                                                        {
                                                             $frontend_url = $domain.'/';
                                                        }
                                                        
                                                        $item_name = '';
                                                        if($items_assigned['type'] == 'inventory')
                                                        {
                                                            //Get inventory data
                                                            $sql_get_inventory_data_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `id` = ?', [$items_assigned['inventory_id']]);
                                                            
                                                            $item_name = $sql_get_inventory_data_rows["name"];
                                                        }
                                                        else
                                                        {
                                                            $item_name = $items_assigned['url_record_data']["meta_title"];
                                                        }
                                        
                                                        $item_status = '';
                                                        $item_type = '';
                                                        $view_in_admin_url = '';
                                                        
                                                        if($items_assigned["status"] == "1") { $item_status = "Enabled"; }
                                                        elseif($items_assigned["status"] == "2") { $item_status = "Disabled"; }
                                                        elseif($items_assigned["status"] == "3") { $item_status = "Draft"; }
                                                        elseif($items_assigned["status"] == "4") { $item_status = "Scheduled"; }
                                                        
                                                        $admin_edit_url = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', 'WHERE `table_name` = ? AND `type` = ?', [$items_assigned["child_id_table_name"], 'edit']);
                                                        
                                                        $admin_edit_page = 'Can\'t find admin edit URL.';
                                                        
                                                        if($items_assigned["type"] == "inventory") 
                                                        {
                                                            $item_type = "Inventory"; 
                                                            $view_in_admin_url = '<a href="/'.$_SESSION['admin_directory'].'/purchasing/inventory/edit/?rid='.$items_assigned["inventory_id"].'" target="_blank" title="View item in admin">'.$items_assigned["inventory_id"].'</a>';
                                                        }
                                                        elseif(isset($admin_edit_url['url']))
                                                        {
                                                            $item_type = ucwords(str_replace('_', ' ', $items_assigned["type"] ?? '')); 
                                                            $view_in_admin_url = '<a href="/'.$_SESSION['admin_directory'].'/'.$admin_edit_url['url'].'/?rid='.$items_assigned["child_id"].'" target="_blank" title="View item in admin">'.$items_assigned["child_id"].'</a>';
                                                        }
                                                        ?>
                                                            <ul class="sub-categories-row row_<?php echo $row_counter; ?>">
                                                            <li class="sub-categories-cell order center order-row"><i><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i></li>
                                                            <li class="sub-categories-cell status center status_<?php echo $row_counter; ?>"><span class="changeActive" data-click="<?php echo trim($_GET["rid"] ?? ''); ?>,<?php echo $row_counter; ?>,<?php echo $items_assigned["id"]; ?>,<?php echo $items_assigned["status"]; ?>"><?php echo $item_status; ?></span></li>
                                                            <li class="sub-categories-cell type center"><?php echo $item_type; ?></li>
                                                            <li class="sub-categories-cell id center"><?php echo $view_in_admin_url; ?></li>
                                                            <li class="sub-categories-cell"><a href="<?php echo $frontend_url; ?>" target="_blank" title="View item on frontend"><?php echo $item_name; ?></a>
                                                            </li>
                                                            <li class="sub-categories-cell status center"><span class="removeRow" data-click="<?php echo $row_counter; ?>">Remove</span></li>
                                                            <input name="groups[<?php echo $group_counter; ?>][items][<?php echo $row_counter; ?>][item_status]" type="hidden" value="<?php echo $items_assigned["status"]; ?>" id="item_status_<?php echo $row_counter; ?>">
                                                            <input name="groups[<?php echo $group_counter; ?>][items][<?php echo $row_counter; ?>][item_id]" type="hidden" value="<?php echo $items_assigned['pages_data']['urls_id']; ?>">
                                                            <input name="groups[<?php echo $group_counter; ?>][items][<?php echo $row_counter; ?>][item_type]" type="hidden" value="<?php echo $items_assigned["type"]; ?>">
                                                            <input name="groups[<?php echo $group_counter; ?>][items][<?php echo $row_counter; ?>][table_name]" type="hidden" value="<?php echo $items_assigned["child_id_table_name"]; ?>">
                                                            <?php if($items_assigned["type"] == 'inventory') { ?><input name="groups[<?php echo $group_counter; ?>][items][<?php echo $row_counter; ?>][inventory_id]" type="hidden" value="<?php echo $items_assigned["inventory_id"]; ?>"><?php } ?>
                                                            </ul>
                                                        <?php
                                                        $row_counter = $row_counter + 1;
                                                    } 
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Table -->
                                </div>
                            </div>
                        </div>
                        <!-- End Group -->
                    <?php 
                    $group_counter = $group_counter + 1;
                    }
                }
                ?>
            </div>
            <div class="button-left"><button type="submit" name="save-groups">Save Blocks</button></div>
		</form>
		
		<div class="edit">
		<div class="edit-label">Updated Date</div>
		<div class="edit-field text"><?php echo utcToUserTimeZone($record_data_row["updated_date"], 'F d, Y - g:i:s A'); ?>
		</div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Updated By</div>
		<div class="edit-field text"><?php echo $record_data_row["updated_by"]; ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Created Date</div>
		<div class="edit-field text"><?php echo utcToUserTimeZone($record_data_row["created_date"], 'F d, Y - g:i:s A'); ?>
		</div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Created By</div>
		<div class="edit-field text"><?php echo $record_data_row["created_by"]; ?></div>
		</div>
		<?php 
		}
		?>
		
		
		</div>
	<?php } ?>
<?php } ?>