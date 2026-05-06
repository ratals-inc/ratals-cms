<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/addons/displaying-in.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/addons/displaying-in.php');
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
		<div class="edit-label">These are the locations that "<?php echo $sql_record_data_rows['urls_record_data']["meta_title"]; ?>" is displaying in.</div>
		<div class="edit-field">
		<!-- Start Displaying In Table -->
		<div class="table-overfollow fixed-scrollbar">
		<div class="displaying-in-table">
		<ul class="displaying-in-table-row header">
			<li class="displaying-in-table-cell header center">Status</li>
			<li class="displaying-in-table-cell header center">ID</li>
			<li class="displaying-in-table-cell header center">Page Type</li>
			<li class="displaying-in-table-cell header center">Displaying As</li>
			<li class="displaying-in-table-cell header">Displaying In</li>
			<li class="displaying-in-table-cell header center">Remove</li>
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
				elseif($assignments_row['assignment_table_name'] == 'sub_items')
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
					$admin_edit_page = '<a href="/'.$_SESSION['admin_directory'].'/'.$admin_edit_url['url'].'/?rid='.$assignments_row['parent_id'].'" target="_blank">'.$assignments_row['parent_id'].'</a>';
				}
				
				$edit_inventory = '';
				if($assignments_row["type"] == 'inventory') { $edit_inventory = ' - ID: <a href="/'.$_SESSION['admin_directory'].'/purchasing/inventory/edit/?rid='.$assignments_row['inventory_id'].'" target="_blank">'.$assignments_row['inventory_id'].'</a>'; }
			
				if(empty($get_url_data["custom_link"])) 
				{
					if(!empty($get_url_data["url_extension"])) { $end_url_with = $get_url_data["url_extension"]; } else { $end_url_with = $sites["global_url_extension"]; }
					if($sites["url_structure"] == 'Hierarchy') { $final_url = $domain."/".$get_url_data["hierarchy_url"].$end_url_with; } 
					elseif($sites["url_structure"] == 'Flat') { $final_url = $domain."/".$get_url_data["flat_url"].$end_url_with; }
				}
				else
				{
					$final_url = $get_url_data["custom_link"];
				}
				
				if($home_page == $get_url_data["id"] && empty($get_url_data["custom_link"]))
				{
					$final_url = $domain."/";
				} 
				?>
				<ul class="displaying-in-table-row remove_<?php echo $counter; ?>">
				<li class="displaying-in-table-cell center status status_<?php echo $counter; ?>"><?php echo $status; ?></li>
				<li class="displaying-in-table-cell center status"><?php echo $admin_edit_page; ?></li>
				<li class="displaying-in-table-cell center status"><?php echo ucwords(str_replace('_', ' ', $get_url_data["table_name"] ?? '')); ?></li>
				<li class="displaying-in-table-cell center displaying-as"><?php echo ucwords(str_replace('_', ' ', $assignments_row["type"] ?? '')); echo $edit_inventory; ?></li>
				<li class="displaying-in-table-cell"><a href="<?php echo $final_url; ?>" target="_blank"><?php echo $final_url; ?></a></li>
				<li class="displaying-in-table-cell center status"><span class="displayingInRemove" data-click="<?php echo $assignments_row["id"] ?>,<?php echo $assignments_row["parent_id"]; ?>,<?php echo $assignment_table; ?>">Remove</span></li>
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