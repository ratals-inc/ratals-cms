<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/ajax/design-blocks.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/ajax/design-blocks.php');
}
else
{
	//Enable/Disable Design Blocks.
	if(($_POST['type'] ?? '') == 'groupStatus')
	{
		$editing = htmlspecialchars($_POST['editing'] ?? '');
		$group_id_count = htmlspecialchars($_POST['group_id_count'] ?? '');
		$group_id = htmlspecialchars($_POST['group_id'] ?? '');
		$value = htmlspecialchars($_POST['value'] ?? '');
		
		$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`updated_by` = ?,`updated_date` = UTC_TIMESTAMP()', 'WHERE `id` = ? AND `site_id` = ?', [$_SESSION['user_username'], $_POST['editing'], $_SESSION["site_set_for_editing"]]);
		
		$results->getUpdateRecord(__LINE__, __FILE__, 'design_blocks', '`status` = ?', 'WHERE `site_id` = ? AND `urls_id` = ? AND `id` = ?', [$_POST['value'], $_SESSION["site_set_for_editing"], $_POST['editing'], $_POST['group_id']]);
		
		if($value == 1)
		{
			echo '<span class="groupStatus" data-click="'.$editing.','.$group_id_count.','.$group_id.','.$value.'"><i><svg viewBox="0 0 512 512"><path d="M507 256S413 83 256 83 5 256 5 256 99 429 256 429 507 256 507 256M41 256A409 409 0 0 1 94 192C134 151 189 115 256 115S378 151 418 192A409 409 0 0 1 471 256Q468 260 465 265C454 280 438 300 418 320 378 361 323 397 256 397S134 361 94 320A409 409 0 0 1 41 256ZM256 177A79 79 0 1 0 256 335 79 79 0 0 0 256 177M146 256A110 110 0 1 1 366 256 110 110 0 0 1 146 256"></path></svg></i></span>';
		}
		elseif($value == 2)
		{
			echo '<span class="groupStatus" data-click="'.$editing.','.$group_id_count.','.$group_id.','.$value.'"><i><svg viewBox="0 0 512 512"><path d="M424 358C478 310 507 256 507 256s-94-173-251-173a220 220 0 0 0-88 18l24 24A189 189 0 0 1 256 115c67 0 122 37 162 77A409 409 0 0 1 471 256q-3 4-6 9c-11 15-26 35-46 55q-8 8-16 15zM360 293a110 110 0 0 0-141-141l26 26a79 79 0 0 1 89 89zm-92 41 26 26a110 110 0 0 1-141-141l26 26a79 79 0 0 0 89 89M110 176q-8 8-16 15A409 409 0 0 0 41 256l6 9c11 15 26 35 46 55C134 361 189 397 256 397c22 0 44-4 63-11l24 24A220 220 0 0 1 256 429C99 429 5 256 5 256s30-54 83-102l22 22zm324 279-377-377 22-22 377 377z"></path></svg></i></span>';
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
		
		exit;
	}
	
	//Enable/Disable Design Block items.
	if(($_POST['type'] ?? '') == 'changeActive')
	{
		$id = htmlspecialchars($_POST['id'] ?? '');
		$editing = htmlspecialchars($_POST['editing'] ?? '');
		$field = htmlspecialchars($_POST['field'] ?? '');
		$value = htmlspecialchars($_POST['value'] ?? '');
		
		$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`updated_by` = ?,`updated_date` = UTC_TIMESTAMP()', 'WHERE `id` = ? AND `site_id` = ?', [$_SESSION['user_username'], $_POST['id'], $_SESSION["site_set_for_editing"]]);
		
		$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_design_blocks', '`status` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$_POST['value'], $_POST['field'], $_SESSION["site_set_for_editing"]]);
		
		if($value == 1)
		{
			echo '<span class="changeActive" data-click="'.$id.','.$editing.','.$field.','.$value.'">Enabled</span>';
		}
		elseif($value == 2)
		{
			echo '<span class="changeActive" data-click="'.$id.','.$editing.','.$field.','.$value.'">Disabled</span>';
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
		
		exit;
	}
	
	//Search for items to assign to a Design Block.
	if(($_POST['type'] ?? '') == 'designBlockItemSearch')
	{
		$group_counter = intval($_POST['group_counter'] ?? 0);
		
		$search_id = trim($_POST['search_id'] ?? '');
		$item_type = trim($_POST['item_type'] ?? '');
		$status = trim($_POST['status'] ?? '');
		$search_title = trim($_POST['search_title'] ?? '');
		$flat_url = trim($_POST['flat_url'] ?? '');
		$hierarchy_url = trim($_POST['hierarchy_url'] ?? '');
		
		$sql_search_query = '';
		$sql_search_query_parameters = array();
		
		$sql_search_query_parameters[] = $_SESSION["site_set_for_editing"];
		
		//Search by URL ID.
		if(!empty($search_id))
		{
			$sql_search_query .= ' AND `id` = ?';
			$sql_search_query_parameters[] = $search_id;
		}
		
		//Search by URL type.
		if(!empty($item_type))
		{
			$sql_search_query .= ' AND `table_name` = ?';
			$sql_search_query_parameters[] = $item_type;
		}
		
		//Search by URL status.
		if(!empty($status))
		{
			$sql_search_query .= ' AND `url_status` = ?';
			$sql_search_query_parameters[] = $status;
		}
		
		//Search by title.
		if(!empty($search_title))
		{
			$sql_search_query .= ' AND `meta_title` LIKE ?';
			$sql_search_query_parameters[] = '%'.$search_title.'%';
		}
		
		//Search by flat URL.
		if(!empty($flat_url))
		{
			$sql_search_query .= ' AND `flat_url` LIKE ?';
			$sql_search_query_parameters[] = '%'.$flat_url.'%';
		}
		
		//Search by hierarchy URL.
		if(!empty($hierarchy_url))
		{
			$sql_search_query .= ' AND `hierarchy_url` LIKE ?';
			$sql_search_query_parameters[] = '%'.$hierarchy_url.'%';
		}
		
		//Get matching URLs.
		//If all search fields are empty, this returns the 100 most recent URLs.
		$all_urls = $results->getSelectMultipleRecords(
			__LINE__,
			__FILE__,
			'*',
			'urls',
			'WHERE `site_id` = ?'.$sql_search_query.' ORDER BY `id` DESC LIMIT 100',
			$sql_search_query_parameters
		);
		
		//Get URL status labels.
		$url_status_options = $results->getSelectMultipleRecordsKeyName(
			__LINE__,
			__FILE__,
			'*',
			'admin_fields_values',
			'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC',
			['urls_status'],
			'value'
		);
		
		$new_item_counter = 0;
		$results_found = false;
		
		if(!empty($all_urls))
		{
			foreach($all_urls as $all_url)
			{
				//Get the record connected to this URL.
				$record_data = $results->getSelectSingleRecord(
					__LINE__,
					__FILE__,
					'*',
					$all_url['table_name'],
					'WHERE `site_id` = ? AND `urls_id` = ? LIMIT 1',
					[
						$_SESSION["site_set_for_editing"],
						$all_url['id']
					]
				);
				
				if(empty($record_data))
				{
					continue;
				}
				
				$assign_item_row = $all_url + $record_data;
				
				$url_status_label = $assign_item_row["url_status"];
				
				if(isset($url_status_options[$assign_item_row["url_status"]]['label']))
				{
					$url_status_label = $url_status_options[$assign_item_row["url_status"]]['label'];
				}
				
				$results_found = true;
				
				?>
				<ul class="design-block-assign-row">
					<li class="center">
						<label>
							<input name="groups[<?php echo $group_counter; ?>][new_items][<?php echo $new_item_counter; ?>][item_id]" type="checkbox" value="<?php echo htmlspecialchars($assign_item_row["urls_id"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
							<input name="groups[<?php echo $group_counter; ?>][new_items][<?php echo $new_item_counter; ?>][item_type]" type="hidden" value="<?php echo htmlspecialchars($assign_item_row["table_name"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
							<input name="groups[<?php echo $group_counter; ?>][new_items][<?php echo $new_item_counter; ?>][table_name]" type="hidden" value="<?php echo htmlspecialchars($assign_item_row["table_name"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
						</label>
					</li>
					<li class="center"><?php echo htmlspecialchars($assign_item_row["id"] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
					<li><?php echo htmlspecialchars(ucwords($assign_item_row["table_name"] ?? ''), ENT_QUOTES, 'UTF-8'); ?></li>
					<li><?php echo htmlspecialchars($url_status_label ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
					<li><?php echo htmlspecialchars($assign_item_row["meta_title"] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
					<li><?php echo htmlspecialchars($assign_item_row["flat_url"] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
					<li><?php echo htmlspecialchars($assign_item_row["hierarchy_url"] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
				</ul>
				<?php
				
				$new_item_counter = $new_item_counter + 1;
				
				//If this product has inventory items, display those beneath the product.
				if(!empty(trim($assign_item_row['inventory_assigned'] ?? '', ',')))
				{
					$inventory_ids = array();
					$select_inventory_placeholders = '';
					
					if(strpos(trim($assign_item_row['inventory_assigned'] ?? '', ','), ',') !== false)
					{
						$inventory_ids_exploded = explode(',', trim($assign_item_row['inventory_assigned'] ?? '', ','));
						
						foreach($inventory_ids_exploded as $inventory_status_ids)
						{
							$inventory_status_id = explode('|', $inventory_status_ids);
							
							if(isset($inventory_status_id[1]) && !empty($inventory_status_id[1]))
							{
								$inventory_ids[] = $inventory_status_id[1];
								$select_inventory_placeholders .= '?,';
							}
						}
					}
					else
					{
						$inventory_status_id = explode('|', trim($assign_item_row['inventory_assigned'] ?? '', ','));
						
						if(isset($inventory_status_id[1]) && !empty($inventory_status_id[1]))
						{
							$inventory_ids[] = $inventory_status_id[1];
							$select_inventory_placeholders .= '?,';
						}
					}
					
					if(!empty($inventory_ids))
					{
						$select_inventory_placeholders = trim($select_inventory_placeholders, ',');
						$select_inventory_ids = array_merge($inventory_ids, $inventory_ids);
						
						$sql_get_inventory_assigned_rows = $results->getSelectMultipleRecordsKeyName(
							__LINE__,
							__FILE__,
							'*',
							'inventory',
							'WHERE `id` IN ('.$select_inventory_placeholders.') ORDER BY FIELD(`id`, '.$select_inventory_placeholders.')',
							$select_inventory_ids,
							'id'
						);
						
						if(!empty($sql_get_inventory_assigned_rows))
						{
							foreach($sql_get_inventory_assigned_rows as $sql_get_inventory_assigned_row)
							{
								if(empty($sql_get_inventory_assigned_row))
								{
									continue;
								}
								
								$inventory_status_label = $sql_get_inventory_assigned_row["status"];
								
								if(isset($url_status_options[$sql_get_inventory_assigned_row["status"]]['label']))
								{
									$inventory_status_label = $url_status_options[$sql_get_inventory_assigned_row["status"]]['label'];
								}
								
								?>
								<ul class="design-block-assign-row">
									<li class="center">
										<label>
											<input name="groups[<?php echo $group_counter; ?>][new_items][<?php echo $new_item_counter; ?>][item_id]" type="checkbox" value="<?php echo htmlspecialchars($assign_item_row["urls_id"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
											<input name="groups[<?php echo $group_counter; ?>][new_items][<?php echo $new_item_counter; ?>][item_type]" type="hidden" value="inventory">
											<input name="groups[<?php echo $group_counter; ?>][new_items][<?php echo $new_item_counter; ?>][table_name]" type="hidden" value="products">
											<input name="groups[<?php echo $group_counter; ?>][new_items][<?php echo $new_item_counter; ?>][inventory_id]" type="hidden" value="<?php echo htmlspecialchars($sql_get_inventory_assigned_row["id"] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
										</label>
									</li>
									<li class="center"><?php echo htmlspecialchars($sql_get_inventory_assigned_row["id"] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
									<li>Inventory</li>
									<li><?php echo htmlspecialchars($inventory_status_label ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
									<li><?php echo htmlspecialchars($sql_get_inventory_assigned_row["name"] ?? '', ENT_QUOTES, 'UTF-8'); ?></li>
									<li>N/A</li>
									<li>N/A</li>
								</ul>
								<?php
								
								$new_item_counter = $new_item_counter + 1;
							}
						}
					}
				}
			}
		}
		
		if($results_found === false)
		{
			echo '<div class="no-results">No Items Found</div>';
		}
		
		exit;
	}
}