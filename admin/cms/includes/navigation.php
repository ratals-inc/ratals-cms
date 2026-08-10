<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/navigation.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/navigation.php');
}
else
{
?>
    <div class="left">
      <div class="header">
      <div class="logo">ADMIN</div>
      <div class="navigation-toggle"><i><svg viewBox="0 0 512 512"><path d="m5 441a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23m0-183a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23m0-183a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23"></path></svg></i></div>
      </div>
      <div class="editing">
      <?php 
      $_SESSION['menu_items_parents'] = array();
      unset($_SESSION['menu_items_permissions_approved']);
      unset($_SESSION['menu_items_all']);
      $parent_menu_item_ids = array();
      
      unset($_SESSION['admin_menu_items_main']);
      unset($_SESSION['admin_menu_items_pages_row']);
      
      $_SESSION['admin_menu_all_admin_pages'] = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_pages', '', [], 'system_code');
      
      $all_sites_in_account_rows = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'sites', '', [], 'id');
      
      if(!empty($all_sites_in_account_rows)) 
      {
          echo '<script nonce="'.NONCE.'">
          $(document).ready(function()
            {
                $(".change_site").change(function()
                {
                    this.form.submit();
                });
            });
          </script>
          <form method="post">
          <select name="change_site" class="change_site">';
		  
          foreach($all_sites_in_account_rows as $all_sites_in_account_row) 
          {
              if(empty($_SESSION['user_site_permissions_id']) || (!empty($_SESSION['user_site_permissions_id']) && strpos($_SESSION['user_site_permissions_id'], ','.$all_sites_in_account_row["id"].',') !== false))
              {
                  echo '<option value="'.$all_sites_in_account_row["id"].'"'.(($_SESSION["site_set_for_editing"] == $all_sites_in_account_row["id"]) ? ' selected' : '').'>'.$all_sites_in_account_row["domain"].'</option>';
              }
          }
          echo '</select>
          </form>';
      }
      ?>
      <div class="visit-frontend"><a href="<?php echo $view_frontend_of_site; ?>" target="_blank">View Frontend of Site</a></div>
      </div>
      <div class="navigation">
      <script nonce="<?php echo NONCE; ?>">
        $(document).ready(function()
        {
            $(".toggleMenu").click(function()
            {
                id = $(this).attr('data-click');
                $(".menuItem"+id).slideToggle();
                $(".menuArrow"+id).toggleClass("toggle-arrow");
            });
        });
      </script>
      <ul>
      <?php
      //Start get parent menu items that do no have admin_pages links assigned to them. This is so the menu function with parent toggles.
      function parentMenuItemIds($main_menu_id, $menu_item_id_holder)
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
      $parent_menu_item_ids = parentMenuItemIds(1, '0');
      //echo '<pre>'; print_r($parent_menu_item_ids); echo '</pre>';
      //End get parent menu items that do no have admin_pages links assigned to them. This is so the menu function with parent toggles.
      
      //Start put menu data into an array.
      function adminNavMenu($main_menu_id, $parent_menu_item_id = 0) 
      {
          $menuArray = array();
          global $site_id, $home_page, $domain, $parent_menu_item_ids;
          
          if($parent_menu_item_id == 0)
          {
              $menu_items_main = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_menu_items', 'WHERE `parent_id` = ? AND `admin_menus_id` = ? AND `status` = ? ORDER BY `sort` ASC', [0, $main_menu_id, 1]);
          } 
          else
          {
              if(!isset($_SESSION['admin_menu_items_main']))
              {
                  $_SESSION['admin_menu_items_main'] = $_SESSION['results']->getSelectMultipleRecordsKeyNameArray(__LINE__, __FILE__, '*', 'admin_menu_items', 'WHERE `admin_menus_id` = ? AND `parent_id` != ? AND `status` = ? ORDER BY `sort` ASC', [$main_menu_id, 0, 1], 'parent_id');
              }
              
              $menu_items_main = array();
              if(isset($_SESSION['admin_menu_items_main'][$parent_menu_item_id]))
              {
                  $menu_items_main = $_SESSION['admin_menu_items_main'][$parent_menu_item_id];
              }
              //echo '<pre>'; print_r($menu_items_main); echo '</pre>';
          }
          
          if(!empty($menu_items_main))
          {
              foreach($menu_items_main as $menu_items_main_rows)
              {
                  $menu_items_pages_row = array();
                  
                  if(!empty($menu_items_main_rows["admin_pages_id"]))
                  {
                      if(!isset($_SESSION['admin_menu_items_pages_row']))
                      {
                          $_SESSION['admin_menu_items_pages_row'] = $_SESSION['results']->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_pages', '', [], 'id');
                      }
                      
                      if(isset($_SESSION['admin_menu_items_pages_row'][$menu_items_main_rows["admin_pages_id"]]) && !empty($_SESSION['admin_menu_items_pages_row'][$menu_items_main_rows["admin_pages_id"]]))
                      {
                          $menu_items_pages_row = $_SESSION['admin_menu_items_pages_row'][$menu_items_main_rows["admin_pages_id"]];
                      }
                  }
                  
                  if(!empty($menu_items_pages_row) && (empty($_SESSION['user_admin_permissions_id']) || strpos($_SESSION['user_admin_permissions_set_ids'], ','.$menu_items_main_rows["admin_pages_id"].',') !== false))
                  
                  {
                      $menu_items_rows = $menu_items_main_rows + $menu_items_pages_row;
                  }
                  else
                  {
                      if(empty($_SESSION['user_admin_permissions_id']) || in_array($menu_items_main_rows['id'], $parent_menu_item_ids))
                      {
                          $menu_items_rows = $menu_items_main_rows;
                      }
                      else
                      {
                          continue;
                      }
                  }
                  
                  if($parent_menu_item_id == 0)
                  {
                      $children = adminNavMenu($main_menu_id, $menu_items_rows["id"]);
                  }
                  elseif($parent_menu_item_id != 0)
                  {
                      $children = adminNavMenu($main_menu_id, $menu_items_rows["id"]);
                  }
                  
                  if(!empty($children))
                  {
                      $menu_items_rows['children'] = $children;
                  }
                  
                  $menuArray[] = $menu_items_rows;
                  
                  //echo '<pre>'; print_r($menuArray); echo '</pre>';
              }
          }
          
          return $menuArray;
      }
      //End put menu data into an array.
      
      //Start get parent id's of URL so menu will stay open on load.  
      function getActiveBlocks($id, $menu_id, $active_blocks)
      {
          if(empty($active_blocks))
          {
              $active_block_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_menu_items', 'WHERE `admin_menus_id` = ? AND `admin_pages_id` = ? AND `status` = ?', [$menu_id, $id, 1]);
              
              if(!empty($active_block_row['id']))
              {
                  $active_blocks[] = $active_block_row['id'];
              }
              
              if(!empty($active_block_row['parent_id']))
              {
                  return getActiveBlocks($active_block_row['parent_id'], $menu_id, $active_blocks);
              }
          }
          else
          {
              $active_blocks_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_menu_items', 'WHERE `id` = ? AND `admin_menus_id` = ? AND `status` = ?', [$id, $menu_id, 1]);
              
              if(isset($active_blocks_rows['id']))
              {
                  $active_blocks[] = $active_blocks_rows['id'];
              }
              
              if(!empty($active_blocks_rows['parent_id']))
              {
                  return getActiveBlocks($active_blocks_rows['parent_id'], $menu_id, $active_blocks);
              }
          }
          return $active_blocks;
      }
      
      $active_blocks = array();
      
      $admin_url_set = substr_replace($path_url, '', 0, strlen($_SESSION['admin_directory'].'/'));
      
	  //Set admin_pages ID. If there is no $_SESSION['admin_pages_parent_code'], the current admin page id is used to keep the menu open.
	  $menu_items_parent_id_row = array();
	  $menu_items_parent_id_row['id'] = $_SESSION['admin_id'];
	  
	  //If there is a $_SESSION['admin_pages_parent_code'], its used to keep the menu open.
	  if(!empty($_SESSION['admin_pages_parent_code']))
	  {
		  $menu_items_parent_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', 'WHERE `system_code` = ? LIMIT 1', [$_SESSION['admin_pages_parent_code']]);
		  
		  if(!empty($menu_items_parent_row))
		  {
			  $menu_items_parent_id_row['id'] = $menu_items_parent_row['id'];
		  }
	  }
	  
      //End get parent id's of URL so menu will stay open on load. 
      
      //Start run through menu array and display menu.
      function menu_header($menuArray) 
      { 
          global $path_url, $active_blocks;
          
          if(!empty($menuArray)) 
          { 
              foreach($menuArray as $menu) 
              {
                  $sub_items_arrow = '';
                   
                  if(!empty($menu['children'])) 
                  {
                      $sub_items_arrow = ' <i class="desktop-sub-menu-arrow"><svg viewBox="0 0 512 512"><path d="M12 127A19 19 0 0 0 12 154L242 385A19 19 0 0 0 270 385L500 154A19 19 0 0 0 473 127L256 344 39 127A19 19 0 0 0 12 127"></path></svg></i>';
                  }
                  
                  if(!empty($_SESSION['admin_menu_all_admin_pages'][$_SESSION['admin_pages_parent_code']]['url']))
                  {
                      //$_SESSION['admin_menu_all_admin_pages'][$_SESSION['admin_pages_parent_code']]['url'] This will make it get the parent URL, that is set on $menu['url'], so they match to make menu highlight for subpages.
                      $path_url_match = trim($_SESSION['admin_directory'].'/'.$_SESSION['admin_menu_all_admin_pages'][$_SESSION['admin_pages_parent_code']]['url'], '/');
                  }
                  else
                  {
                      $path_url_match = $path_url;
                  }
                  
                  $selected_page = '';
                  if(isset($menu['url']) && $_SESSION['admin_directory']."/".$menu['url'] == $path_url_match) { $selected_page = ' class="selected-page"'; }
                  
                  $add_slash = '';
                  if(isset($menu['url']) && strpos($menu['url'], '.') === false) { $add_slash = '/'; }
                  
                  $link_target = '';
                  if(!empty($menu['link_target'])) { $link_target = ' target="'.$menu['link_target'].'"'; }
                  
                  $display_as_block = ' display-none';
                  if(in_array($menu['id'], $active_blocks)) { $display_as_block = ' display-block'; }
                  
                  echo "
                  <li".$selected_page.">"; 
                  
                  if(isset($menu['url'])) { echo '<a href="/'.$_SESSION['admin_directory'].'/'.$menu['url'].$add_slash.'"'.$selected_page.$link_target.'>'.$menu['name'].$sub_items_arrow.'</a>'; }
                  
                  else { echo '
                  <div class="toggleMenu" data-click="'.$menu['id'].'"><span class="label">'.$menu['name'].' <i class="arrow menuArrow'.$menu['id'].'"><svg viewBox="0 0 512 512"><path d="M500 385A19 19 0 0 0 500 358L270 127A19 19 0 0 0 242 127L12 358A19 19 0 0 0 39 385L256 168 473 385A19 19 0 0 0 500 385"></path></svg></i></span></div>'; }
                  
                  if(!empty($menu['children'])) 
                  {
                      echo '
                      <ul class="menuItem'.$menu['id'].$display_as_block.'">';
                      menu_header($menu['children']); 
                      echo '</ul>
                      '; 
                  } 
                  echo "</li>
                  ";
              }
          }
      }
      
      $all_menus_data = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_menus', "WHERE `menu_type` = ? AND `status` =  ?", ['Main Menu', 1]);
      
      if(!empty($all_menus_data))
      {
          foreach($all_menus_data as $all_menus)
          {
              if(isset($menu_items_parent_id_row['id']) && !empty($menu_items_parent_id_row['id']))
              {
                  //This sets the ids for menu blocks that have to stay open, display: block;, to see where you're in the menu.
                  $active_blocks = getActiveBlocks($menu_items_parent_id_row['id'], $all_menus['id'], $active_blocks);
              }
              
              menu_header(adminNavMenu($all_menus['id'])); 
          }
      }
      //End run through menu array and display menu.
      ?>
      </ul>
      </div>
      <?php
	  echo '<div class="software-version"><a href="https://www.ratals.com" target="_blank">Ratals Inc.</a><br>Software Version: '.$current_software_version.'<br>License Type: '.$current_license_type.'</div>';
	  ?>
    </div>
<?php } ?>