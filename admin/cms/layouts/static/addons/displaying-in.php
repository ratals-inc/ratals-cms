<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/displaying-in.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/displaying-in.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'displaying_in')
	{
		if(isset($_GET["remove"]) && $_GET["remove"] == "success" && empty($errors))
		{
			echo '<div class="changes-saved">Removed successfully.</div>';
		} 
		?>
		<div class="edit-wrapper">
		<!-- Start Edit View -->
		<div class="edit margin-top-25px">
		<div class="edit-label">These are the locations where "<strong><?php echo $sql_record_data_rows['urls_record_data']["meta_title"]; ?></strong>" is linked throughout your website. This report includes product-category assignments, sub-item relationships, and contextual links embedded in page content using the <code>urlId();</code> function. Site-wide navigation elements such as menus, headers, footers, and sliders are intentionally excluded. Use this report to evaluate how well this URL is connected to other content across your website.</div>
		<div class="edit-field">
		<!-- Start Displaying In Table -->
		<div class="table-overfollow fixed-scrollbar">
		<div class="displaying-in-table">
        <ul class="displaying-in-table-row header">
            <li class="displaying-in-table-cell header center">Status</li>
            <li class="displaying-in-table-cell header center">Source ID</li>
            <li class="displaying-in-table-cell header center">Source Type</li>
            <li class="displaying-in-table-cell header center">Link Type</li>
            <li class="displaying-in-table-cell header">Linked From</li>
            <li class="displaying-in-table-cell header center">Action</li>
        </ul>
		<?php   
		if(!empty($assignments_rows))
		{
			$counter = 0;
			foreach($assignments_rows as $assignments_row)
			{
				$assignment_table = '';
				if($assignments_row['assignment_table_name'] == 'products')
				{
					$assignment_table = 1;
				}
				elseif($assignments_row['assignment_table_name'] == 'design_blocks')
				{
					$assignment_table = 2;
				}
				
				if($assignments_row["status"] == 1)
				{
					$status = '<span class="displayingInStatus" data-click="'.$assignments_row["id"].','.$assignments_row["status"].','.$counter.','.$assignment_table.'">Enabled</span>';
				} 
				elseif($assignments_row["status"] == 2)
				{
					$status = '<span class="displayingInStatus" data-click="'.$assignments_row["id"].','.$assignments_row["status"].','.$counter.','.$assignment_table.'">Disabled</span>';
				}
				elseif($assignments_row["status"] == 'N/A')
				{
					$status = 'N/A';
				}
				
				$get_url_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ?', [$assignments_row["parent_id"]]);
				
				$admin_edit_url = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', 'WHERE `table_name` = ? AND `type` = ?', [$get_url_data["table_name"], 'edit']);
				
				$admin_edit_page = 'Can\'t find admin edit URL.';
				if(isset($admin_edit_url['url']))
				{
					$admin_edit_page = '<a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/'.$admin_edit_url['url'].'/?rid='.$assignments_row['parent_id'].'" target="_blank">'.$assignments_row['parent_id'].'</a>';
				}
				
				$edit_inventory = '';
				if($assignments_row["type"] == 'inventory') { $edit_inventory = ' - ID: <a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/purchasing/inventory/edit/?rid='.$assignments_row['inventory_id'].'" target="_blank">'.$assignments_row['inventory_id'].'</a>'; }
			
				if(empty($get_url_data["custom_link"])) 
				{
					if(!empty($get_url_data["url_extension"])) { $end_url_with = $get_url_data["url_extension"]; } else { $end_url_with = $sites["global_url_extension"]; }
					if($sites["url_structure"] == 'Hierarchy') { $final_url = $domain.INSTALLATION_URL_PATH."/".$get_url_data["hierarchy_url"].$end_url_with; } 
					elseif($sites["url_structure"] == 'Flat') { $final_url = $domain.INSTALLATION_URL_PATH."/".$get_url_data["flat_url"].$end_url_with; }
				}
				else
				{
					$final_url = $get_url_data["custom_link"];
				}
				
				if($home_page == $get_url_data["id"] && empty($get_url_data["custom_link"]))
				{
					$final_url = $domain.INSTALLATION_URL_PATH."/";
				}
				
				$displaying_as_type = $assignments_row['type'];
				if($assignments_row['assignment_table_name'] == 'products')
				{
					$displaying_as_type = 'Product List';
				}
				elseif($assignments_row['assignment_table_name'] == 'design_blocks')
				{
					$displaying_as_type = 'Sub Item';
				}
				elseif($assignments_row['assignment_table_name'] == 'Contextual Link')
				{
					$displaying_as_type = 'Contextual Link';
				}
				?>
				<ul class="displaying-in-table-row remove_<?php echo $counter; ?>">
				<li class="displaying-in-table-cell center status status_<?php echo $counter; ?>"><?php echo $status; ?></li>
				<li class="displaying-in-table-cell center status"><?php echo $admin_edit_page; ?></li>
				<li class="displaying-in-table-cell center status"><?php echo ucwords(str_replace('_', ' ', $get_url_data["table_name"] ?? '')); ?></li>
				<li class="displaying-in-table-cell center displaying-as"><?php echo $displaying_as_type; echo $edit_inventory; ?></li>
				<li class="displaying-in-table-cell"><a href="<?php echo $final_url; ?>" target="_blank"><?php echo $final_url; ?></a></li>
                <?php if($assignments_row["type"] == 'Contextual Link') { ?>
                <li class="displaying-in-table-cell center status">N/A</li>
                <?php } else { ?>
				<li class="displaying-in-table-cell center status"><span class="displayingInRemove" data-click="<?php echo $assignments_row["id"] ?>,<?php echo $assignments_row["parent_id"]; ?>,<?php echo $assignment_table; ?>">Remove</span></li>
                <?php } ?>
				</ul>
				<?php
				$counter = $counter + 1;
			}
			echo "</div>";
		}
		else 
		{
			echo '</div><div class="table-no-results">No Results</div>';
		}
		?>
		</div>
		<!-- End Displaying In Table -->
		</div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Updated Date</div>
		<div class="edit-field text"><?php echo utcToUserTimeZone($sql_record_data_rows["updated_date"], 'F d, Y - g:i:s A'); ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Updated By</div>
		<div class="edit-field text"><?php echo $sql_record_data_rows["updated_by"]; ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Created Date</div>
		<div class="edit-field text"><?php echo utcToUserTimeZone($sql_record_data_rows["created_date"], 'F d, Y - g:i:s A'); ?></div>
		</div>
		
		<div class="edit">
		<div class="edit-label">Created By</div>
		<div class="edit-field text"><?php echo $sql_record_data_rows["created_by"]; ?></div>
		</div>
		
		</div>
	<?php } ?>
<?php } ?>