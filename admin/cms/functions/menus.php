<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/menus.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/menus.php');
}
else
{
	//Build Menus for MENUS and MENU_ITEMS tables
	//$menu_id = 1;
	if(!function_exists('navMenu'))
	{
		function navMenu($menu_id, $parent_menu_item_id = 0) 
		{
			$menuArray = array();
			global $site_id, $home_page, $domain, $url_structure, $sites_end_urls_with, $record_id, $page_type;
			
			if(!isset($_SESSION['menu_main']))
			{
				$_SESSION['menu_main'] = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'menus', 'WHERE `site_id` = ? AND `status` = ?', [$site_id, 1], 'id');
			}
			$sql_menus_main_rows = array();
			if(isset($_SESSION['menu_main'][$menu_id]))
			{
				$sql_menus_main_rows = $_SESSION['menu_main'][$menu_id];
			}
			
			if(!empty($sql_menus_main_rows))
			{
				if($parent_menu_item_id == 0)
				{
					$sql_menu_items_main = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'menu_items', 'WHERE `menus_id` = ? AND `site_id` = ? AND `parent_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$menu_id, $site_id, 0, 1]);
				}
				else
				{ 
					if(!isset($_SESSION['menu_items_main']))
					{
						$_SESSION['menu_items_main'] = $_SESSION['results']->getSelectMultipleRecordsKeyNameArray(__LINE__, __FILE__, '*', 'menu_items', 'WHERE `menus_id` = ? AND `site_id` = ? AND `status` = ? AND `parent_id` != ? ORDER BY `sort` ASC', [$menu_id, $site_id, 1, 0], 'parent_id');
					}
					
					$sql_menu_items_main = array();
					if(isset($_SESSION['menu_items_main'][$parent_menu_item_id]))
					{
						$sql_menu_items_main = $_SESSION['menu_items_main'][$parent_menu_item_id];
					}
				}
				
				if(!empty($sql_menu_items_main))
				{
					foreach($sql_menu_items_main as $menu_items_rows)
					{
						$sql_menu_items_pages_rows = array();
						
						if(is_numeric($menu_items_rows["links_to"]))
						{
							if($menu_items_rows["links_to"] == 0)
							{
								$menu_items_rows["links_to"] = $home_page;
							}
							
							if(!isset($_SESSION['menu_items_pages_row']))
							{
								//Get all url ids that this site links to.
								$all_menu_url_ids = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, 'links_to', 'menu_items', 'WHERE `site_id` = ? AND `status` = ?', [$site_id, 1]);
								
								$menu_url_ids = '';
								$menu_url_placements = '';
								
								if(!empty($all_menu_url_ids))
								{
									$unique_menu_url_ids = array();
									
									foreach($all_menu_url_ids as $all_menu_url_id)
									{
										if(is_numeric($all_menu_url_id['links_to']) && !in_array($all_menu_url_id['links_to'], $unique_menu_url_ids))
										{
											if($all_menu_url_id['links_to'] == 0)
											{
												$all_menu_url_id['links_to'] = $home_page;
											}
											
											$menu_url_ids .= $all_menu_url_id['links_to'].',';
											
											$unique_menu_url_ids[] = $all_menu_url_id['links_to'];
										}
									}
								}
								
								$menu_url_ids = trim($menu_url_ids, ',');
								
								$_SESSION['menu_items_pages_row'] = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` IN('.$menu_url_ids.') AND `site_id` = ? AND `url_status` = ?', [$site_id, 1], 'id');
							}
							
							//Set page URL data for what menu is linking to.
							if(isset($_SESSION['menu_items_pages_row'][$menu_items_rows["links_to"]]))
							{
								$sql_menu_items_pages_rows = $_SESSION['menu_items_pages_row'][$menu_items_rows["links_to"]];
							}
						}
						
						$menu_items_rows["menu_url"] = '';
						$menu_items_rows["menu_url_link_type"] = '';
						
						if(empty($menu_items_rows["custom_link"]) && !empty($sql_menu_items_pages_rows) && empty($sql_menu_items_pages_rows["custom_link"]))
						{
							if(!empty($sql_menu_items_pages_rows["url_extension"]))
							{
								$menu_end_url_with = $sql_menu_items_pages_rows["url_extension"];
							}
							else
							{
								$menu_end_url_with = $sites_end_urls_with;
							}
							
							if($url_structure == 'Hierarchy')
							{
								$menu_items_rows["menu_url"] = $domain."/".$sql_menu_items_pages_rows["hierarchy_url"].$menu_end_url_with;
								
								if(!empty($menu_items_rows["link_type"]))
								{
									$menu_items_rows["menu_url_link_type"] = $menu_items_rows["link_type"];
								}
								else
								{
									$menu_items_rows["menu_url_link_type"] = $sql_menu_items_pages_rows["link_type"];
								}
							} 
							elseif($url_structure == 'Flat')
							{
								$menu_items_rows["menu_url"] = $domain."/".$sql_menu_items_pages_rows["flat_url"].$menu_end_url_with;
								
								if(!empty($menu_items_rows["link_type"]))
								{
									$menu_items_rows["menu_url_link_type"] = $menu_items_rows["link_type"];
								}
								else
								{
									$menu_items_rows["menu_url_link_type"] = $sql_menu_items_pages_rows["link_type"];
								}
							} 
							
							if($home_page == $menu_items_rows["links_to"])
							{
								$menu_items_rows["menu_url"] = $domain."/";
								
								if(!empty($menu_items_rows["link_type"]))
								{
									$menu_items_rows["menu_url_link_type"] = $menu_items_rows["link_type"];
								}
								else
								{
									$menu_items_rows["menu_url_link_type"] = $sql_menu_items_pages_rows["link_type"];
								}
							}
						}
						
						elseif(!empty($menu_items_rows["custom_link"]))
						{
							$menu_items_rows["menu_url"] = $menu_items_rows["custom_link"];
							
							if(!empty($menu_items_rows["link_type"]))
							{
								$menu_items_rows["menu_url_link_type"] = $menu_items_rows["link_type"];
							}
							elseif(isset($sql_menu_items_pages_rows["link_type"]))
							{
								$menu_items_rows["menu_url_link_type"] = $sql_menu_items_pages_rows["link_type"];
							}
							else
							{
								$menu_items_rows["menu_url_link_type"] = '';
							}
						}
						
						elseif(isset($sql_menu_items_pages_rows["custom_link"]) && !empty($sql_menu_items_pages_rows["custom_link"]))
						{
							$menu_items_rows["menu_url"] = $sql_menu_items_pages_rows["custom_link"];
							
							if(!empty($menu_items_rows["link_type"]))
							{
								$menu_items_rows["menu_url_link_type"] = $menu_items_rows["link_type"];
							}
							else
							{
								$menu_items_rows["menu_url_link_type"] = $sql_menu_items_pages_rows["link_type"];
							}
						}
						
						if(empty($menu_items_rows["menu_url"]))
						{
							//If the menu item has no url, don't add to the menuArray and skip to next menu item.
							continue;
						}
						
						if($parent_menu_item_id == 0)
						{
							$children = navMenu($menu_id, $menu_items_rows["id"]);
						}
						elseif($parent_menu_item_id != 0)
						{
							$children = navMenu($menu_id, $menu_items_rows["id"]);
						}
						
						if(!empty($children))
						{
							$menu_items_rows['children'] = $children;
						}
						
						$menuArray[] = $menu_items_rows;
					}
				}
				
				return $menuArray;
			}
		}
	}
	//$menu = navMenu(1);
	//echo "<pre>"; print_r($menu); echo "</pre>";
}