<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/redirects.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/addons/redirects.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'redirects')
	{
	?>
		<div class="edit-wrapper">
		<?php 
		//Check for flat url changes
		if($_SESSION["old_flat_url"] != $_SESSION["new_flat_url"])
		{
			  $hierarchy["id"] = trim($_GET["rid"] ?? '');
			  $hierarchy["old_url"] = $_SESSION["old_flat_url"];
			  $hierarchy["new_url"] = $_SESSION["new_flat_url"];
			  $hierarchy["db_column"] = "flat_url";
			  $url_changes[] = $hierarchy;
		}
		
		//Check for hierarchy url changes
		if($_SESSION["old_hierarchy_url"] != $_SESSION["new_hierarchy_url"])
		{
			$sql_get_matching_hierarchy_urls = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND (`hierarchy_url` = ? OR `hierarchy_url` LIKE ?)', [$_SESSION["site_set_for_editing"], $_SESSION["old_hierarchy_url"], $_SESSION["old_hierarchy_url"].'/%' ]);
			
			if(!empty($sql_get_matching_hierarchy_urls))
			{
				foreach($sql_get_matching_hierarchy_urls as $sql_get_matching_hierarchy_urls_rows)
				{
					$hierarchy["id"] = $sql_get_matching_hierarchy_urls_rows["id"];
					$hierarchy["old_url"] =  $sql_get_matching_hierarchy_urls_rows["hierarchy_url"];
					$hierarchy["new_url"] = str_replace("/".$_SESSION["old_hierarchy_url"]."/", "/".$_SESSION["new_hierarchy_url"]."/", "/".$sql_get_matching_hierarchy_urls_rows["hierarchy_url"]."/");
					$hierarchy["new_url"] = trim($hierarchy["new_url"], "/");
					$hierarchy["db_column"] = "hierarchy_url";
					
					$url_changes[] = $hierarchy;
				}
			}
		}
		
		//Check for URL conflicts
		$url_conflicts = array();
		if(!empty($url_changes))
		{
			foreach($url_changes as $url_changes_conflicts)
			{
				$sql_valid_url_available_check = $results->getSelectCountRecords(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `'.$url_changes_conflicts["db_column"].'` = ?', [$_SESSION["site_set_for_editing"], $url_changes_conflicts["new_url"]]);
				
				if($sql_valid_url_available_check > 0)
				{
					$url_conflicts[] = $url_changes_conflicts["new_url"];
				}
			}
		}
		
		//Check for redirect conflicts
		$conflicts = array();
		if(!empty($url_changes))
		{
			foreach($url_changes as $url_changes_rows)
			{
				$sql_redirect_conflict = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'redirects', 'WHERE `site_id` = ? AND `old_url` = ?', [$_SESSION["site_set_for_editing"], trim($url_changes_rows["new_url"], "/")]);
				
				if(!empty($sql_redirect_conflict))
				{
					foreach($sql_redirect_conflict as $sql_redirect_conflict_rows)
					{
						$conflicts[] = $sql_redirect_conflict_rows["old_url"];
					}
				}
			}
		}	
		?>
		
		<form method="post">
		<?php
		if(!empty($url_conflicts))
		{
			$url_conflict_array = array();
			
			foreach($url_conflicts as $url_conflict)
			{
				if(!in_array($url_conflict, $url_conflict_array))
				{
					$url_conflict_array[] = $url_conflict;
				}
			}
			
			if(count($url_conflict_array) > 1) { $add_s = "URL's conflict"; } else  { $add_s = "URL conflicts"; }
			
			echo '<div class="redirect-top-text"><strong>'.count($url_conflict_array).'</strong> '.$add_s.' with other existing URLs. You cannot have the same URL on 2 different documents. Below is a list of URLs that already exists on another document that you\'re trying to use again.</div>';
			echo '<div class="redirect-table-overflow">
			<div class="redirect-table">';
			echo '<div class="redirect-table-row header">';
			echo '<div class="redirect-table-cell redirect-type">Conflicting URLs</div>';
			echo '</div>';
			
			foreach($url_conflict_array as $url_conflicting)
			{
				echo '<div class="redirect-table-row">';
				echo '<div class="redirect-table-cell">'.$url_conflicting.'</div>';
				echo '</div>';
			}
			
			echo '</div></div>';
		}
		elseif(!empty($conflicts))
		{
			echo '<div class="redirect-top-text">Your new URL conflicts with <strong>'.count($conflicts).'</strong> existing redirects. These existing rediredts must be deleted to change to your new URL. Please delete the below redirects and try changing your URL again.</div>
			<div class="redirect_urls-buttons-wrapper">
			<div class="redirect_urls-buttons"><button type="submit" name="cancel">Cancel URL Changes</button></div>
			<div class="redirect_urls-buttons"><button type="submit" name="delete_conflicting_redirects">Delete Below Conflicting Redirects</button></div>
			</div>';
			echo '<div class="redirect-table-overflow">
			<div class="redirect-table">';
			echo '<div class="redirect-table-row header">';
			echo '<div class="redirect-table-cell redirect-type">Redirect Type</div>';
			echo '<div class="redirect-table-cell width-urls">Old URL</div>';
			echo '<div class="redirect-table-cell width-urls">New URL</div>';
			echo '</div>';
			
			$array_counter = 1;
			foreach($conflicts as $conflict)
			{
				$sql_conflicting_redirects = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'redirects', 'WHERE `site_id` = ? AND `old_url` = ?', [$_SESSION["site_set_for_editing"], $conflict]);
				
				if(!empty($sql_conflicting_redirects))
				{
					foreach($sql_conflicting_redirects as $sql_conflicting_redirects_rows)
					{
						echo '<div class="redirect-table-row">';
						echo '<div class="redirect-table-cell">'.$sql_conflicting_redirects_rows["redirect_type"].'</div>';
						echo '<div class="redirect-table-cell">'.$sql_conflicting_redirects_rows["old_url"].'
						<input name="conflicting_redirects['.$array_counter.']" type="hidden" value="'.$sql_conflicting_redirects_rows["old_url"].'">
						</div>';
						echo '<div class="redirect-table-cell">'.$sql_conflicting_redirects_rows["new_url"].'</div>';
						echo '</div>';
						$array_counter = $array_counter + 1;
					}
				}
			}
			echo '</div>
			</div>';
		}
		else 
		{
			if(count($url_changes) > 1) { $add_s = "s"; } else  { $add_s = ""; }
			
			echo '<div class="redirect-top-text">Changing this URL will affect <strong>'.count($url_changes).'</strong> URL'.$add_s.' below. When changing URLs it is highly recomended to redirect the Old URLs to the New URLs so search engines can easily locate where the pages have moved to. Would you like to redirect the Old URLs to the New URLs? Note: This will only create redirects for '.$url_structure_set.' URLs as that is what your site loads on.</div>
			<div class="redirect_urls-buttons-wrapper">
			<div class="redirect_urls-buttons"><button type="submit" name="cancel">Cancel URL Change'.$add_s.'</button></div>
			<div class="redirect_urls-buttons"><button type="submit" name="change_urls_redirect">Change URL'.$add_s.' & Create Redirect'.$add_s.'</button></div>
			<div class="redirect_urls-buttons"><button type="submit" name="change_urls">Change URL'.$add_s.' with No Redirect'.$add_s.'</button></div>
			</div>'; 
			echo '<div class="redirect-table-overflow">
			<div class="redirect-table">';
			echo '<div class="redirect-table-row header">';
			echo '<div class="redirect-table-cell redirect-type">Redirect Type</div>';
			echo '<div class="redirect-table-cell width-urls">Old URL</div>';
			echo '<div class="redirect-table-cell width-urls">New URL</div>';
			echo '<div class="redirect-table-cell url-type">URL Type</div>';
			echo '</div>';
			
			$array_counter = 1;
			foreach($url_changes as $display_url_changes)
			{
				if($display_url_changes["db_column"] == "hierarchy_url") { $url_type = "hierarchy";  $display_url_type = "Hierarchy URL";} else { $url_type = "flat"; $display_url_type = "Flat URL"; } 
				echo '<div class="redirect-table-row">';
				
				if($url_type == $url_structure_set) { $redirect_dropdown = '<select name="redirects['.$array_counter.'][redirect_type]"><option value="301" selected>301 Permanent Redirect</option><option value="302">302 Temporary Redirect</option><option value="404">Do Not Redirect</option></select>'; } else { $redirect_dropdown = '<span>Redirect will not be created as your site loads on '.$url_structure_set.' URLs.</spa>'; }
				
				echo '<div class="redirect-table-cell">'.$redirect_dropdown.'</div>';
				echo '<div class="redirect-table-cell"><input type="text" name="redirects['.$array_counter.'][old_url]" value="'.$display_url_changes["old_url"].'"></div>';
				echo '<div class="redirect-table-cell"><input type="text" name="redirects['.$array_counter.'][new_url]" value="'.$display_url_changes["new_url"].'"></div>';
				echo '<div class="redirect-table-cell">'.$display_url_type.'</div>';
				echo '<input name="redirects['.$array_counter.'][url_type]" type="hidden" value="'.$url_type.'">';
				echo '<input name="redirects['.$array_counter.'][id]" type="hidden" value="'.$display_url_changes["id"].'">';
				echo '</div>';
				
				$array_counter = $array_counter + 1;
			}
			echo '</div>
			</div>';
		}
		?>
		</div>
		</form>
	<?php } ?>
<?php } ?>