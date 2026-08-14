<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/sub-navigation.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/sub-navigation.php');
}
else
{
	$sub_menus = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_menus', "WHERE `menu_type` = 'Sub Menu' AND `menu_locations` LIKE ? AND `status` = ?", ['%,'.$_SESSION['admin_id'].',%', 1]);
	
	$_SESSION['menu_items_parents'] = array();
	unset($_SESSION['menu_items_permissions_approved']);
	unset($_SESSION['menu_items_all']);
	$parent_sub_menu_item_ids = array();
	
	//Start get parent menu items that do no have admin_pages links assigned to them. This is so the menu function with parent toggles.
	function parentSubMenuItemIds($main_menu_id, $menu_item_id_holder)
	{
		if(!empty($_SESSION['user_admin_permissions_id']) && !empty($_SESSION['user_admin_permissions_set_ids']))
		{
			if(!isset($_SESSION['menu_items_all']) || !isset($_SESSION['menu_items_permissions_approved']))
			{
				$_SESSION['menu_items_all'] = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_menu_items', 'WHERE `admin_menus_id` = ? AND `status` = ?', [$main_menu_id, 1], 'id');
				
				$_SESSION['menu_items_permissions_approved'] = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_menu_items', 'WHERE `admin_menus_id` = ? AND `status` = ? AND `admin_pages_id` IN ('.trim($_SESSION['user_admin_permissions_set_ids'], ',').')', [$main_menu_id, 1], 'id');
			}
			
			if(!empty($_SESSION['menu_items_permissions_approved']))
			{
				foreach($_SESSION['menu_items_permissions_approved'] as $approved_admin_urls)
				{
					if(!array_key_exists($approved_admin_urls['id'], $_SESSION['menu_items_parents']))
					{
						$_SESSION['menu_items_parents'][$approved_admin_urls['id']] = $approved_admin_urls['id'];
					}
					elseif(!empty($menu_item_id_holder))
					{
						$_SESSION['menu_items_parents'][$_SESSION['menu_items_all'][$menu_item_id_holder]['id']] = $_SESSION['menu_items_all'][$menu_item_id_holder]['id'];
					}
					
					if(!empty($approved_admin_urls['parent_id']) && !array_key_exists($approved_admin_urls['parent_id'], $_SESSION['menu_items_parents']))
					{
						parentMenuItemIds(1, $approved_admin_urls['parent_id']);  
					}
					elseif(!empty($menu_item_id_holder) && !array_key_exists($_SESSION['menu_items_all'][$menu_item_id_holder]['parent_id'], $_SESSION['menu_items_parents']))
					{
						parentMenuItemIds(1, $_SESSION['menu_items_all'][$menu_item_id_holder]['parent_id']);  
					}
				}
			}
		}
		
		return $_SESSION['menu_items_parents'];
	}
	
	if(!empty($sub_menus))
	{
		foreach($sub_menus as $sub_menu_record)
		{
			$parent_sub_menu_item_ids_results = parentSubMenuItemIds($sub_menu_record['id'], '0'); 
			
			if(!empty($parent_sub_menu_item_ids_results))
			{
				$parent_sub_menu_item_ids = array_merge($parent_sub_menu_item_ids, $parent_sub_menu_item_ids_results);
			}
		}
	}
	//echo '<pre>'; print_r($parent_sub_menu_item_ids); echo '</pre>';
	//End get parent menu items that do no have admin_pages links assigned to them. This is so the menu function with parent toggles.
	
	//Create Menu array to display sub menus.
	function adminSubMenu($menu_id, $parent_menu_item_id = 0) 
	{
		global $parent_sub_menu_item_ids;
		
		$menuArray = array();
		
		if(!empty($menu_id))
		{
			if($parent_menu_item_id == 0)
			{
				$sub_menu_items = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_menu_items', "WHERE `admin_menus_id` = ? AND `parent_id` = ? AND `status` = ? ORDER BY `sort` ASC", [$menu_id, 0, 1]);
			} 
			else
			{
				$sub_menu_items = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_menu_items', "WHERE `admin_menus_id` = ? AND `parent_id` = ? AND `status` = ? ORDER BY `sort` ASC", [$menu_id, $parent_menu_item_id, 1]);
			}
			
			if(!empty($sub_menu_items))
			{
				foreach($sub_menu_items as $sub_menu_item)
				{
					$sub_menu_item_page = 0;
					
					if(is_numeric($sub_menu_item["admin_pages_id"]))
					{
						$sub_menu_item_page = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', "WHERE `id` = ? LIMIT 1", [$sub_menu_item["admin_pages_id"]]);
					}
					
					if(!empty($sub_menu_item_page) && (empty($_SESSION['user_admin_permissions_id']) || strpos($_SESSION['user_admin_permissions_set_ids'], ','.$sub_menu_item["admin_pages_id"].',') !== false))
					{
						$menu_items_rows = array_merge($sub_menu_item, array('admin_page' => $sub_menu_item_page));
					}
					else
					{
						if(empty($_SESSION['user_admin_permissions_id']) || in_array($sub_menu_item['id'], $parent_sub_menu_item_ids))
						{
							$menu_items_rows = $sub_menu_item;
						}
						else
						{
							continue;
						}
					}
					
					if($parent_menu_item_id == 0)
					{
						$children = adminSubMenu($menu_id, $menu_items_rows["id"]);
					}
					elseif($parent_menu_item_id != 0)
					{
						$children = adminSubMenu($menu_id, $menu_items_rows["id"]);
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
	
	//Create menu into html list.
	function subMenu($menuArray, $sub_menu_results) 
	{
		if(!empty($menuArray)) 
		{ 
			global $path_url;
			
			foreach($menuArray as $menu) 
			{
				$link_parameters = '';
				if($menu['link_parameters'] == 'Yes')
				{
					if(!empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET["sub-rid"] ?? '')))
					{
						$link_parameters = '?rid='.trim($_GET["rid"] ?? '').'&sub-rid='.trim($_GET["sub-rid"] ?? '');
					}
					elseif(!empty(trim($_GET["sub-rid"] ?? '')))
					{
						$link_parameters = '?sub-rid='.trim($_GET["sub-rid"] ?? '');
					}
					elseif(!empty(trim($_GET['sub-page-rid'] ?? '')))
					{
						$link_parameters = '?rid='.trim($_GET['sub-page-rid'] ?? '');
					}
					elseif(!empty(trim($_GET["rid"] ?? '')))
					{
						$link_parameters = '?rid='.trim($_GET["rid"] ?? '');
					}
				}
				
				$sub_items_arrow = '';
				
				if(!empty($menu['children'])) 
				{
					$sub_items_arrow = ' <i class="desktop-sub-menu-arrow"><svg viewBox="0 0 512 512"><path d="M12 127A19 19 0 0 0 12 154L242 385A19 19 0 0 0 270 385L500 154A19 19 0 0 0 473 127L256 344 39 127A19 19 0 0 0 12 127"></path></svg></i>';
				}
				
				$link_target = '';
				if(!empty($menu['link_target']))
				{
					$link_target = ' target="'.$menu['link_target'].'"';
				}
				
				$selected_page = '';
				if(isset($menu['admin_page']['url']) && $_SESSION['admin_directory']."/".$menu['admin_page']['url'] == $path_url) { $selected_page = ' class="sub-menu-active"'; }
				elseif(isset($menu['admin_page']['url']) && $_SESSION['admin_directory']."/".$menu['admin_page']['url'].'/edit' == $path_url) { $selected_page = ' class="sub-menu-active"'; }
				//for here: admin5/accounting/accounts_payable/bills/inventory - keeps sub menu button selected
				elseif(isset($menu['admin_page']['url']) && $_SESSION['admin_directory']."/".$menu['admin_page']['url'].'/inventory' == $path_url) { $selected_page = ' class="sub-menu-active"'; }
				//for here: admin5/accounting/accounts_payable/bills/inventory/edit - keeps sub menu button selected
				elseif(isset($menu['admin_page']['url']) && $_SESSION['admin_directory']."/".$menu['admin_page']['url'].'/inventory/edit' == $path_url) { $selected_page = ' class="sub-menu-active"'; }
				
				$sub_menu_results .= '<li>'; 
				if(isset($menu['admin_page']['url']) && !empty($menu['admin_page']['url']))
				{
					$sub_menu_results .= '<a href="/'.$_SESSION['admin_directory'].'/'.$menu['admin_page']['url'].'/'.$link_parameters.'"'.$selected_page.$link_target.'>'.$menu['name'].$sub_items_arrow.'</a>'; 
				}
				else
				{
					$sub_menu_results .= '<div class="sub-navigation-no-link">'.$menu['name'].$sub_items_arrow.'</div>'; 
				}
				
				if(!empty($menu['children'])) 
				{
					$sub_menu_results .= '<i class="mobile-sub-menu-arrow toggleSubMenu" data-click="'.$menu['id'].'"><svg viewBox="0 0 512 512"><path d="M12 127A19 19 0 0 0 12 154L242 385A19 19 0 0 0 270 385L500 154A19 19 0 0 0 473 127L256 344 39 127A19 19 0 0 0 12 127"></path></svg></i>
					<ul class="subMenuItem'.$menu['id'].'">';
					$sub_menu_results = subMenu($menu['children'], $sub_menu_results); 
					$sub_menu_results .= '</ul>'; 
				} 
				$sub_menu_results .= "</li>";
			}
		}
		return $sub_menu_results;
	}
	?>
	
	<?php
	$sub_menus_data = '';
	
	if(!empty($sub_menus))
	{
		foreach($sub_menus as $sub_menu_record)
		{
			$sub_menus_data .= subMenu(adminSubMenu($sub_menu_record['id']), $sub_menus_data); 
		}
	}
	
	$sub_menu = '';
	if(!empty($sub_menus_data)) 
	{
		$sub_menu = '<script nonce="'.NONCE.'">
		$(function(){ $(".sub-navigation-toggle").click(function(){ $(".sub-navigation").slideToggle(); }); });
		
	$(document).ready(function()
	{
		$(".toggleSubMenu").click(function()
		{
			var id = $(this).attr("data-click");
			$(".subMenuItem"+id).slideToggle();
		});
	});
		
		//If .sub-menu ul ul are open in mobile view, remove display: block on window reize if window gets larger than 1175
		$(window).resize(function() 
		{
			if($(window).width() > 1174)
			{
				$(".sub-menu ul ul").css({"display" : ""})
			}
		})
		</script>
		
		<div class="sub-menu">
		  <div class="sub-navigation-toggle">
			<div class="sub-left">Sub Menu</div>
			<div class="sub-right"><i class="text-align-right"><svg viewBox="0 0 512 512"><path d="m5 441a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23m0-183a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23m0-183a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23"></path></svg></i></div>
			<div class="sub-clear"></div>
		  </div>
		  <ul class="sub-navigation">
			'.$sub_menus_data.'
		  </ul>
		</div>';
	}
}