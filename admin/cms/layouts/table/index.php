<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/index.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/index.php');
}
else
{
	include_once('code.php');
	?><!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php if(!empty($head_title_name)) { echo $head_title_name.' '; } echo $_SESSION['admin_title']; ?></title>
	<?php include_once(INSTALLATION_ROOT.'/admin/cms/includes/head-files.php'); ?>
	<?php
	//Auto loader - scripts
	$types_to_load = array();
	
	if(is_dir(INSTALLATION_ROOT.'/admin/cms/layouts/table/scripts')) 
	{
		$types_to_load[] = 'cms'; 
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/commerce/layouts/table/scripts')) 
	{
		$types_to_load[] = 'commerce';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/erp/layouts/table/scripts'))
	{
		$types_to_load[] = 'erp';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/ai/layouts/table/scripts')) 
	{
		$types_to_load[] = 'ai';
	}
	
	foreach($types_to_load as $type_to_load)
	{
		$existing_files = array();
		$directory_path = '/admin/'.$type_to_load.'/layouts/table/scripts';
		$auto_loader_path = INSTALLATION_ROOT.$directory_path;
		$auto_loader_files = array_diff(scandir($auto_loader_path), array('.', '..'));
		if(!empty($auto_loader_files))
		{
			foreach($auto_loader_files as $auto_loader_file)
			{
				$existing_files[] = $auto_loader_file;
				
				if(file_exists(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file))
				{
					include_once(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file);
				}
				else
				{
					include_once(INSTALLATION_ROOT.$directory_path.'/'.$auto_loader_file);
				}
			}
		}
	}
	?>
	</head>
	
	<body>
	<!-- Start Pending Ajax Overlay -->
	<style nonce="<?php echo NONCE; ?>">.pending-ajax { display: none; }</style>
	<div class="pending-ajax">
	  <div class="pending-ajax-outer-container">
		<div class="pending-ajax-inner-container">
		  <span>Updating...</span>
		</div>
	  </div>
	</div>
	<!-- End Pending Ajax Overlay -->
	<!-- Start Left Column -->
	<?php
    include_once(INSTALLATION_ROOT.'/admin/cms/includes/navigation.php'); 
	?>
	<!-- End Left Column -->
	
	<!-- Start Right Column -->
	<div class="right">
    <!-- Start Notices -->
    <?php include(INSTALLATION_ROOT.'/admin/cms/includes/notices/index.php'); ?>
    <!-- End Notices -->
    <?php if($level >= $_SESSION['admin_page_level']) { ?>
	<?php 
    if(isset($_GET["edited"]) && $_GET["edited"] == "success")
    {
        echo '<div class="changes-saved">Edited successfully.</div>';
    }
    
    if(isset($_GET["created"]) && $_GET["created"] == "success")
    {
        echo '<div class="changes-saved">Created successfully.</div>';
    }
    
    if(isset($_GET["updated"]) && $_GET["updated"] == "success")
    {
        echo '<div class="changes-saved">Updated successfully.</div>';
    }
    
    if(isset($_GET["posted"]) && $_GET["posted"] == "success")
    {
        echo '<div class="changes-saved">Posted successfully.</div>';
    }
    ?>
    <div id="response"></div>
    <?php
    //Auto loader - addons
	$types_to_load = array();
	
	if(is_dir(INSTALLATION_ROOT.'/admin/cms/layouts/table/addons')) 
	{
		$types_to_load[] = 'cms';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/commerce/layouts/table/addons')) 
	{
		$types_to_load[] = 'commerce';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/erp/layouts/table/addons'))
	{
		$types_to_load[] = 'erp';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/ai/layouts/table/addons')) 
	{
		$types_to_load[] = 'ai';
	}
	
	foreach($types_to_load as $type_to_load)
	{
		$existing_files = array();
		$directory_path = '/admin/'.$type_to_load.'/layouts/table/addons';
		$auto_loader_path = INSTALLATION_ROOT.$directory_path;
		$auto_loader_files = array_diff(scandir($auto_loader_path), array('.', '..'));
		if(!empty($auto_loader_files))
		{
			foreach($auto_loader_files as $auto_loader_file)
			{
				$existing_files[] = $auto_loader_file;
				
				if(file_exists(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file))
				{
					include_once(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file);
				}
				else
				{
					include_once(INSTALLATION_ROOT.$directory_path.'/'.$auto_loader_file);
				}
			}
		}
	}
    ?>
    <!-- Start Header -->
    <div class="header-text">
    <div class="text"><?php if(!empty($head_title_name)) { echo $head_title_name.' '; } echo $_SESSION['admin_title']; ?> 
    <?php if($_SESSION['admin_assigned_type'] == 'assigned_inventory') { echo '<div class="note">Search inventory below to assign them to this product.</div>'; } ?>
    <?php if($_SESSION['admin_assigned_type'] == 'sub_products_assigned') { echo '<div class="note">Search sub products below to assign them to this product.</div>'; } ?>
    </div>
    <div class="header-right">
    <?php if(!empty($_SESSION['admin_help_video_url'])) { ?><a href="<?php echo $_SESSION['admin_help_video_url']; ?>" target="_blank"><div class="header-video"><i><svg viewBox="0 0 512 512"><path d="M4 162a63 63 0 0 1 63-63h236a63 63 0 0 1 62 55l98-44A31 31 0 0 1 508 139v235a31 31 0 0 1-44 29l-98-44A63 63 0 0 1 303 413H67a63 63 0 0 1-63-63z"></path></svg></i> Tutorial</div></a><?php } ?>
    <div class="toggle-results">Results</div>
    </div>
    </div>
    <!-- End Header -->
    
    <?php 
    //Display "Add" for sub menu items
    if($_SESSION['admin_sub_page'] == "Yes" && !empty($_SESSION['admin_sub_items_add_url']) && $_SESSION['admin_sub_items_add_url'] != INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/')
    { 
        echo '<div class="sub-menu"><ul>';
		$admin_title_no_span = preg_replace('/<span\b[^>]*>.*$/is', '', $_SESSION['admin_title']);
        echo '<li><a href="'.$_SESSION['admin_sub_items_add_url_with_rid'].'">Add '.rtrim($admin_title_no_span, 's').'</a></li>';
        echo '</ul></div>';
    }
    ?>
    <?php 
    if($path_url != INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/categories/assigned-products' 
       && $path_url != INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/products/assigned-inventory' 
       && $path_url != INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/products/assigned-sub-products'
       && $path_url != INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/accounting/accounts_payable/bills/inventory')
    {
        include_once INSTALLATION_ROOT.'/admin/cms/includes/sub-navigation.php';
        if(!empty($sub_menu)) { echo $sub_menu; }
    }
    ?>
    
    <!-- Start Results -->
    <div class="results-wrapper<?php if($_SESSION['admin_sub_page'] == "Yes") { echo ' margin-top-10px'; } ?>">
    <div class="results">
    <ul>
    <li>
    <span>Results:</span> <?php if($limit_offset == 0 && $sql_custom_fields_count > 0) { echo "1"; } elseif($limit_offset == 0 && $sql_custom_fields_count == 0) { echo "0"; } else { echo $limit_offset + 1; } ?> - <?php if($results_per_page > $sql_custom_fields_sorted_count) { echo $limit_offset + $sql_custom_fields_sorted_count; } else { echo $limit_offset + $results_per_page; } ?> of <?php echo number_format($sql_custom_fields_count, '0','',','); ?></li><li><span>Page:</span> <?php if($sql_custom_fields_count > $results_per_page) { ?><?php if($prev_page_number != 0) { ?><a href="?page-number=<?php echo $prev_page_number.$url_sorting.$results_per_page_set.htmlentities($url_search_fileds_string); ?>"><i><svg viewBox="0 0 512 512"><path d="m445 35a32 32 0 0 1 32 32v379a32 32 0 0 1-32 32h-379a32 32 0 0 1-32-32v-379a32 32 0 0 1 32-32zm-379-32a63 63 0 0 0-63 63v379a63 63 0 0 0 63 63h379a63 63 0 0 0 63-63v-379a63 63 0 0 0-63-63zm259 393a16 16 0 0 0 9-14v-252a16 16 0 0 0-26-12l-142 126a16 16 0 0 0 0 24l142 126a16 16 0 0 0 17 3"></path></svg></i></a><?php } ?> 
    <form method="get" class="display-inline-block">
    <input type="text" name="page-number" value="<?php echo $curent_page_number; ?>"> 
    <?php if(!empty($_GET['results-per-page'])) { ?><input type="hidden" name="results-per-page" value="<?php echo $results_per_page ; ?>"><?php } ?>
    <?php if(!empty($url_search_fileds_array))
    {
        foreach($url_search_fileds_array as $clean_search_url_items)
        {
            $clean_search_url_items_exploded = explode("=", $clean_search_url_items); ?>
            <input type="hidden" name="<?php echo  $clean_search_url_items_exploded[0]; ?>" value="<?php echo urldecode(htmlentities($clean_search_url_items_exploded[1])); ?>">
        <?php
        }
    }
    ?>
    <!--<button type="submit" class="results-go-button">GO</button>--> 
    </form>
    <?php if($sql_custom_fields_next_page_count > 0) { ?><a href="?page-number=<?php echo $next_page_number.$url_sorting.$results_per_page_set.htmlentities($url_search_fileds_string); ?>"><i><svg viewBox="0 0 512 512"><path d="m67 477a32 32 0 0 1-32-32l0-379a32 32 0 0 1 32-32l379 0a32 32 0 0 1 32 32l0 379a32 32 0 0 1-32 32zm379 32a63 63 0 0 0 63-63l0-379a63 63 0 0 0-63-63l-379 0a63 63 0 0 0-63 63l0 379a63 63 0 0 0 63 63zm-259-393a16 16 0 0 0-9 14l0 252a16 16 0 0 0 26 12l142-126a16 16 0 0 0 0-24l-142-126a16 16 0 0 0-17-3"></path></svg></i></a></a><?php } ?> of <?php echo $number_of_result_pages; ?><?php } elseif($sql_custom_fields_sorted_count == 0) { echo "0 of 0"; } else { echo "1 of 1";} ?>
    </li>
    <li>
    <span>Per Page:</span> 
    <?php
    if(!empty($_GET['results-per-page'])) { $results_per_page = $_GET['results-per-page']; } else { $results_per_page = ''; } 
    ?>
    <form method="get" class="display-inline-block">
    <select name="results-per-page" class="results-per-page">
    <option value="10"<?php if($results_per_page == 10) { echo " selected"; } ?>>10</option>
    <option value="25"<?php if($results_per_page == 25) { echo " selected"; } ?>>25</option>
    <option value="50"<?php if($results_per_page == 50) { echo " selected"; } ?>>50</option>
    <option value="100"<?php if($results_per_page == 100) { echo " selected"; } ?>>100</option>
    <option value="200"<?php if($results_per_page == 200) { echo " selected"; } ?>>200</option>
    <option value="500"<?php if($results_per_page == 500) { echo " selected"; } ?>>500</option>
    <option value="1000"<?php if($results_per_page == 1000) { echo " selected"; } ?>>1000</option>
    </select>
    <?php if(!empty($url_search_fileds_array))
    {
        foreach($url_search_fileds_array as $clean_search_url_items)
        {
            $clean_search_url_items_exploded = explode("=", $clean_search_url_items); ?>
            <input type="hidden" name="<?php echo  $clean_search_url_items_exploded[0]; ?>" value="<?php echo urldecode(htmlentities($clean_search_url_items_exploded[1])); ?>">
        <?php
        }
    }
    ?>
    </form>
    </li>
    <?php if($_SESSION['record_has_url'] == 'Yes' || $_SESSION['admin_table_name'] == "urls") { ?>
    <li>
    <span>Viewing:</span> <select name="layout_type" id="layout_type"><option value="1"<?php if(!isset($_GET['layout'])) { echo ' selected'; }?>>All</option><option value="2"<?php if(isset($_GET['layout']) && $_GET['layout'] == 'hierarchy') { echo ' selected';}?>>Hierarchy Layout</option></select>
    </li>
    <?php } ?>
    <?php 
    $good_leads = '';
    $junk_leads = '';
    if($_SESSION['admin_class'] == "good-leads") { $good_leads = ' class="leads"'; }
    elseif($_SESSION['admin_class'] == "junk-leads") { $junk_leads = ' class="leads"'; }
    ?>
    <?php if($_SESSION['admin_class'] == "good-leads" || $_SESSION['admin_class'] == "junk-leads") { ?>
    <li><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/customers/leads/"<?php echo $good_leads; ?>>Good Leads</a></li>
    <li><a href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/customers/junk-leads/"<?php echo $junk_leads; ?>>Junk Leads</a></li>
    <?php } ?>
    </ul>
    </div>
    <div class="results-buttons">
    <label for="search" tabindex="0">Search</label>
    <?php 
    if(!empty($url_sorting_query) && 
    empty(trim($_GET["rid"] ?? '')) && 
    empty(trim($_GET["sub-rid"] ?? '')) && 
    $url_sorting_query != "add=success" && 
    $url_sorting_query != 'rid='.trim($_GET["rid"] ?? '').'&add=success' && 
    $url_sorting_query != "created=success" && 
    $url_sorting_query != 'rid='.trim($_GET["rid"] ?? '').'&created=success' && 
    $url_sorting_query != "edited=success" &&
    $url_sorting_query != 'rid='.trim($_GET["rid"] ?? '').'&edited=success'
    )
    {
    ?>
    <a href="<?php echo $_SESSION['admin_url']."/"; ?>"><button>Clear Search</button></a>
    <?php
    }
    elseif
    (!empty($url_sorting_query) && 
    $url_sorting_query != 'rid='.trim($_GET["rid"] ?? '') && 
    $url_sorting_query != 'rid='.trim($_GET["rid"] ?? '').'&sub-rid='.trim($_GET["sub-rid"] ?? '') && 
    $url_sorting_query != 'sub-rid='.trim($_GET["sub-rid"] ?? '').'&rid='.trim($_GET["rid"] ?? '') && 
    $url_sorting_query != "add=success" && 
    $url_sorting_query != 'rid='.trim($_GET["rid"] ?? '').'&add=success' && 
    $url_sorting_query != "created=success" && 
    $url_sorting_query != 'rid='.trim($_GET["rid"] ?? '').'&created=success' && 
    $url_sorting_query != "edited=success" &&
    $url_sorting_query != 'rid='.trim($_GET["rid"] ?? '').'&edited=success'
    )
    {
    ?> 
    <a href="<?php echo $_SESSION['admin_url_with_rid']; ?>"><button>Clear Search</button></a>
    <?php } ?>
    <button class="show-columns">Columns</button>
    <?php if($_SESSION['admin_class'] == "junk-leads") { ?>
    <button class="activateLeads" data-click="">Activate Selected</button>
    <?php } ?>
    <?php if($_SESSION['admin_assigned_type'] != "assign_products_to_category" && $_SESSION['admin_assigned_type'] != "assign_inventory_to_category" && $_SESSION['admin_js_name'] != "hide-delete-option") { ?>
    <button class="<?php echo $_SESSION['admin_js_name']; ?>" data-click="<?php echo $_SESSION['admin_table_name']; ?>">Deleted Selected</button>
    <?php } ?>
    </div>
    </div>
    <!-- End Results -->
    
    <!-- Start Table View -->
    <form method="get">
    <?php if(!empty($url_sorting)) { ?><input type="hidden" name="<?php echo $url_sorting_name; ?>" value="<?php echo $url_sorting_value; ?>"><?php } ?>
    <?php if(!empty($_GET['results-per-page'])) { ?><input type="hidden" name="results-per-page" value="<?php echo $results_per_page ; ?>"><?php } ?>
    <div class="table-overfollow fixed-scrollbar">
    <div class="table">
    
    <!-- Start Table Header Row -->
    <ul class="table-row-header">
    
    <?php 
    if($_SESSION['admin_js_name'] == "hide-delete-option") { echo ''; }
    elseif($_SESSION['admin_assigned_type'] == 'assigned_inventory' || $_SESSION['admin_assigned_type'] == 'sub_products_assigned' || $_SESSION['admin_assigned_type'] == 'assign_products_to_category' || $_SESSION['admin_assigned_type'] == 'assign_inventory_to_category') { echo '<li class="table-cell-header table-checkbox">Assign</li>'; } 
    elseif($_SESSION['admin_assigned_type'] == 'purchase_order_add_inventory') { echo '<li class="table-cell-header table-checkbox">Add to PO</li>'; } 
    elseif($_SESSION['admin_assigned_type'] == 'bills_add_inventory') { echo '<li class="table-cell-header table-checkbox">Add to PO</li>'; } 
    else { echo '<li class="table-cell-header table-checkbox"><input id="deleteAll" type="checkbox"></li>'; } 
    ?>
    <?php if($_SESSION['admin_sort_or_dragdrop'] == "dragdrop") { echo '<li class="table-cell-header table-sort">Order</li>'; } ?>
    <?php if($_SESSION['admin_assigned_type'] == 'assign_products_to_category') { echo '<li class="table-cell-header table-edit">Assign Inventory</li>'; } ?>
    <li class="table-cell-header table-edit">Edit</li>
    
    <?php
    if(!empty($sql_account_columns_array))
    {
        foreach($sql_account_columns_array as $sql_account_columns_active) 
        {
            if($sql_account_columns_active["default_or_custom"] == "default")
            {  
                if(strpos("?".$url_sorting_query, "?".$sql_account_columns_active["url_name"]."=ascend") !== false) { $sorting_arrow = '<i><svg viewBox="0 0 512 512"><path d="m231 17-154 177c-19 21-3 53 25 53l308 0a32 32 0 0 0 25-53l-154-177a32 32 0 0 0-48 0z"></path></svg></i>'; }
                elseif(strpos("?".$url_sorting_query, "?".$sql_account_columns_active["url_name"]."=descend") !== false) { $sorting_arrow = '<i><svg viewBox="0 0 512 512"><path d="m269 496 154-177c19-21 3-53-25-53l-308 0a32 32 0 0 0-25 53l154 177a32 32 0 0 0 48 0z"></path></svg></i>'; }
                else { $sorting_arrow = '<i><svg viewBox="0 0 512 512"><path d="m231 17-154 177c-19 21-3 53 25 53l308 0a32 32 0 0 0 25-53l-154-177a32 32 0 0 0-48 0zm51 479 154-177c19-21 3-53-25-53l-308 0a32 32 0 0 0-25 53l154 177a32 32 0 0 0 48 0z"></path></svg></i>'; }
                
                if($_SESSION['admin_sort_or_dragdrop'] == "dragdrop")
                { 
                    echo '<li class="table-cell-header no-sorting '.$sql_account_columns_active["css_class"].'">'.$sql_account_columns_active["name"].'</li>';
                }
                else
                { 
                    echo '<li class="table-cell-header '.$sql_account_columns_active["css_class"].'"><a href="?'.$sql_account_columns_active["url_name"].'='.$next_sorting_type.$results_per_page_set.htmlentities($url_search_fileds_string).'">'.$sql_account_columns_active["name"].' '.$sorting_arrow.'</a></li>';
                }
            }
            elseif($sql_account_columns_active["default_or_custom"] == "custom")
            {
                if(strpos("?".$url_sorting_query, "?".$sql_account_columns_active["url_name"]."=ascend") !== false) { $sorting_arrow = '<i><svg viewBox="0 0 512 512"><path d="m231 17-154 177c-19 21-3 53 25 53l308 0a32 32 0 0 0 25-53l-154-177a32 32 0 0 0-48 0z"></path></svg></i>'; }
                elseif(strpos("?".$url_sorting_query, "?".$sql_account_columns_active["url_name"]."=descend") !== false) { $sorting_arrow = '<i><svg viewBox="0 0 512 512"><path d="m269 496 154-177c19-21 3-53-25-53l-308 0a32 32 0 0 0-25 53l154 177a32 32 0 0 0 48 0z"></path></svg></i>'; }
                else { $sorting_arrow = '<i><svg viewBox="0 0 512 512"><path d="m231 17-154 177c-19 21-3 53 25 53l308 0a32 32 0 0 0 25-53l-154-177a32 32 0 0 0-48 0zm51 479 154-177c19-21 3-53-25-53l-308 0a32 32 0 0 0-25 53l154 177a32 32 0 0 0 48 0z"></path></svg></i>'; }
                
                if(empty($sql_account_columns_active["frontend_name"]))
                { 
                    echo '<li class="table-cell-header attribute-not-set">Not Set</li>';
                }
                elseif($_SESSION['admin_sort_or_dragdrop'] == "dragdrop")
                { 
                    echo '<li class="table-cell-header no-sorting">'.$sql_account_columns_active["frontend_name"].'</li>';
                }
                else
                { 
                    echo '<li class="table-cell-header"><a href="?'.$sql_account_columns_active["url_name"].'='.$next_sorting_type.$results_per_page_set.htmlentities($url_search_fileds_string).'">'.$sql_account_columns_active["frontend_name"].' '.$sorting_arrow.'</a></li>';
                }
            }
        }
    }
    ?>
    </ul>
    <!-- End Table Header Row -->
    
    <!-- Start Table Search Row -->
    <ul class="table-row-search">
    <?php if($_SESSION['admin_js_name'] != "hide-delete-option") { echo '<li class="table-cell-search"></li>'; }?>
    <?php if($_SESSION['admin_sort_or_dragdrop'] == "dragdrop") { echo '<li class="table-cell-search"></li>'; } ?>
    <?php if($_SESSION['admin_assigned_type'] == 'assign_products_to_category') { echo '<li class="table-cell-search"></li>'; } ?>
    <li class="table-cell-search"></li>
    <?php
    if(!empty($sql_account_columns_array))
    {
        $search_counter = 0;
        foreach($sql_account_columns_array as $sql_account_columns_search_fileds) 
        {
            include('search-fields.php');
            if(!empty($sql_account_columns_search_fileds["css_class"])) { $css_class_name = $sql_account_columns_search_fileds["css_class"]; } else { $css_class_name = ''; }
            echo '<li class="table-cell-search '.$css_class_name.'">'.$search_type.'</li>';
        }
    }
    ?>
    </ul>
    <!-- End Table Search Row -->
    
    <!-- Start Table Results Row -->
    <?php
    if(!empty($sql_custom_fields)) 
    {
        //Get distinct url tables for edit url.	
        $tables_with_urls_id = $results->getSelectLeftJoinMultipleRecordsKeyName(__LINE__, __FILE__, 'DISTINCT `database_tables`.`admin_fields_ids`, `admin_pages`.`table_name`, `admin_pages`.`type`, `admin_pages`.`url`', 'admin_pages', '`database_tables` ON `admin_pages`.`table_name` = `database_tables`.`database_table_name`', 'WHERE `database_tables`.`admin_fields_ids` LIKE ? AND `admin_pages`.`type` = ? ORDER BY `admin_pages`.`table_name` ASC', ['%,'.$admin_fields_urls_id.',%', 'edit'], 'table_name');
        
        echo '<div class="table-row-results-group" id="sortrows">';
        foreach($sql_custom_fields as $sql_custom_fields_rows) 
        {
            echo '<ul class="table-row-results" id="sortorder_'.$sql_custom_fields_rows['id'].'">';
            
            if($_SESSION['admin_assigned_type'] == 'assign_products_to_category' && isset($sql_custom_fields_rows['inventory_assigned']) && !empty($sql_custom_fields_rows['inventory_assigned']))
            {
                if(strpos(trim($sql_custom_fields_rows['inventory_assigned'], ','), ',') !== false)
                {
                    $product_id_set = $sql_custom_fields_rows['id'];
                    $inventory_assigned_to_products = explode(',', trim($sql_custom_fields_rows['inventory_assigned'], ','));
                    $total_inventory_assigned = count($inventory_assigned_to_products);
                    
                    foreach($inventory_assigned_to_products as $inventory_assigned_to_product)
                    {
                        $inventory_assigned_to_products = explode('|', $inventory_assigned_to_product);
                        
                        if($inventory_assigned_to_products[0] == 1)
                        {
                            $inventory_id_set = $inventory_assigned_to_products[1];
                            break;
                        }
                    }
                }
                else
                {
                    $product_id_set = $sql_custom_fields_rows['id'];
                    $inventory_assigned_to_products = explode('|', trim($sql_custom_fields_rows['inventory_assigned'], ','));
                    $total_inventory_assigned = 1;
                    
                    $inventory_id_set = 0;
                    if($inventory_assigned_to_products[0] == 1)
                    {
                        $inventory_id_set = $inventory_assigned_to_products[1];
                    }
                }
            }
            
            elseif($_SESSION['admin_assigned_type'] == 'assign_inventory_to_category')
            {
                $product_id_set = trim($_GET["sub-rid"] ?? '');
                $inventory_id_set = $sql_custom_fields_rows['id'];
                $total_inventory_assigned = 0;
            }
            else { $product_id_set = $sql_custom_fields_rows['id']; $inventory_id_set = 0; $total_inventory_assigned = 0; }
            
            if(!empty($sql_custom_fields_rows['product_type']) && $sql_custom_fields_rows['product_type'] == 'Inventory Items')
            {
                $type_set_on_assignments = 1;
            }
            elseif(!empty($_SESSION['admin_assigned_type']) && $_SESSION['admin_assigned_type'] == 'assign_inventory_to_category')
            {
                $type_set_on_assignments = 2;
            }
            elseif(!empty($sql_custom_fields_rows['product_type']) && $sql_custom_fields_rows['product_type'] == 'Sub Products')
            {
                $type_set_on_assignments = 3;
            }
            elseif(!empty($sql_custom_fields_rows['product_type']) && $sql_custom_fields_rows['product_type'] == 'Lead Form')
            {
                $type_set_on_assignments = 4;
            }
            
            if($_SESSION['admin_assigned_type'] == 'assigned_inventory' && !in_array($sql_custom_fields_rows['id'], $inventory_assigned_ids)) 
            {
                echo '<li class="table-cell-results table-checkbox">
                <button type="button" class="assignInventory" data-click="'.trim($_GET["rid"] ?? '').','.$sql_custom_fields_rows['id'].'">Assign</button>
                </li>';
            } 
            elseif($_SESSION['admin_assigned_type'] == 'assigned_inventory' && in_array($sql_custom_fields_rows['id'], $inventory_assigned_ids)) 
            {
                echo '<li class="table-cell-results table-checkbox">Assigned</li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'sub_products_assigned' && !in_array($sql_custom_fields_rows['id'], $inventory_ids_assigned)) 
            {
                echo '<li class="table-cell-results table-checkbox">
                <button type="button" class="assignSubProducts" data-click="'.trim($_GET["rid"] ?? '').','.$sql_custom_fields_rows['id'].'">Assign</button>
                </li>';
            } 
            elseif($_SESSION['admin_assigned_type'] == 'sub_products_assigned' && in_array($sql_custom_fields_rows['id'], $inventory_ids_assigned)) 
            {
                echo '<li class="table-cell-results table-checkbox">Assigned</li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'assign_products_to_category' && $sql_custom_fields_rows['product_type'] == 'Inventory Items' && !in_array($product_id_set.'|'.$inventory_id_set.'|'.$type_set_on_assignments, $products_ids_assigned)) 
            {
                echo '<li class="table-cell-results table-checkbox">
                <button type="button" class="assignProductToCategory" data-click="'.trim($_GET["rid"] ?? '').','.$sql_custom_fields_rows['id'].','.$inventory_id_set.',1">Assign</button>
                </li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'assign_inventory_to_category' && !in_array($product_id_set.'|'.$inventory_id_set.'|'.$type_set_on_assignments, $products_ids_assigned)) 
            {
                echo '<li class="table-cell-results table-checkbox"><button type="button" class="assignProductToCategory" data-click="'.trim($_GET["rid"] ?? '').','.trim($_GET["sub-rid"] ?? '').','.$sql_custom_fields_rows['id'].',2">Assign</button>
                </li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'assign_products_to_category' && $sql_custom_fields_rows['product_type'] == 'Sub Products' && !in_array($product_id_set.'|'.$inventory_id_set.'|'.$type_set_on_assignments, $products_ids_assigned)) 
            {
                echo '<li class="table-cell-results table-checkbox">
                <button type="button" class="assignProductToCategory" data-click="'.trim($_GET["rid"] ?? '').','.$sql_custom_fields_rows['id'].','.$inventory_id_set.',3">Assign</button>
                </li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'assign_products_to_category' && $sql_custom_fields_rows['product_type'] == 'Lead Form' && !in_array($product_id_set.'|'.$inventory_id_set.'|'.$type_set_on_assignments, $products_ids_assigned)) 
            {
                echo '<li class="table-cell-results table-checkbox">
                <button type="button" class="assignProductToCategory" data-click="'.trim($_GET["rid"] ?? '').','.$sql_custom_fields_rows['id'].','.$inventory_id_set.',4">Assign</button>
                </li>';
            }
            elseif(($_SESSION['admin_assigned_type'] == 'assign_products_to_category' || $_SESSION['admin_assigned_type'] == 'assign_inventory_to_category') && in_array($product_id_set.'|'.$inventory_id_set.'|'.$type_set_on_assignments, $products_ids_assigned)) 
            {
                echo '<li class="table-cell-results table-checkbox">Assigned</li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'purchase_order_add_inventory' && !in_array($sql_custom_fields_rows['id'], $inventory_ids_added_to_po)) 
            {
                echo '<li class="table-cell-results table-checkbox">
                <button type="button" class="assignInventoryToPo" data-click="'.$sql_custom_fields_rows['id'].','.trim($_GET["rid"] ?? '').'">Add to PO</button>
                </li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'purchase_order_add_inventory' && in_array($sql_custom_fields_rows['id'], $inventory_ids_added_to_po)) 
            {
                echo '<li class="table-cell-results table-checkbox">Added</li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'bills_add_inventory' && !in_array($sql_custom_fields_rows['id'], $inventory_ids_added_to_bills)) 
            {
                echo '<li class="table-cell-results table-checkbox">
                <button type="button" class="assignInventoryToBills" data-click="'.$sql_custom_fields_rows['id'].','.trim($_GET["rid"] ?? '').'">Add to Bill</button>
                </li>';
            }
            elseif($_SESSION['admin_assigned_type'] == 'bills_add_inventory' && in_array($sql_custom_fields_rows['id'], $inventory_ids_added_to_bills)) 
            {
                echo '<li class="table-cell-results table-checkbox">Added</li>';
            }
            elseif($_SESSION['admin_js_name'] == "hide-delete-option")
            {
                echo '';
            }
            else
            {
                echo '<li class="table-cell-results table-checkbox"><input type="checkbox" id="delete" value="'.$sql_custom_fields_rows['id'].'" class="deleteCheckBox"></li>';
            } 
            
            if($_SESSION['admin_sort_or_dragdrop'] == 'dragdrop')
            {
                echo '<li class="table-cell-results table-sort table-row-sort"><i><svg viewBox="0 0 512 512"><path d="m245 7a16 16 0 0 1 22 0l63 63a16 16 0 0 1-22 22l-36-36v120a16 16 0 0 1-32 0v-120l-36 36a16 16 0 1 1-22-22zm11 312a16 16 0 0 1 16 16v120l36-36a16 16 0 0 1 22 22l-63 63a16 16 0 0 1-22 0l-63-63a16 16 0 0 1 22-22l36 36v-120a16 16 0 0 1 16-16m-249-52a16 16 0 0 1 0-22l63-63a16 16 0 1 1 22 22l-36 36h120a16 16 0 0 1 0 32h-120l36 36a16 16 0 0 1-22 22zm312-11a16 16 0 0 1 16-16h120l-36-36a16 16 0 0 1 22-22l63 63a16 16 0 0 1 0 22l-63 63a16 16 0 0 1-22-22l36-36h-120a16 16 0 0 1-16-16"></path></svg></i></li>';
            }
            
            if($_SESSION['admin_assigned_type'] == 'assign_products_to_category')
            {
                $add_all_inventory_items = '<br><span class="assign-all-inventory-items assignInventoryToCategory" data-click="'.trim($_GET["rid"] ?? '').','.$sql_custom_fields_rows['id'].','.$inventory_id_set.',2">Assign All '.$total_inventory_assigned.' Inventory Items</span>';
                
                echo '<li class="table-cell-results"><a class="button" href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/categories/assign-products-to-category/inventory/?rid='.trim($_GET["rid"] ?? '').'&sub-rid='.$sql_custom_fields_rows['id'].'">Assign Inventory Items</a>'.$add_all_inventory_items.'</li>';
            }
            
            //When the admin page for website/urls is loaded, the "Edit link" links to each url page type such as products, pages, post, etc. 
            //Example: website/pages/edit/?rid=1&display-url=yes
            if($_SESSION['admin_table_name'] == "urls" && isset($tables_with_urls_id[$sql_custom_fields_rows['table_name']]['url'])&& !empty($tables_with_urls_id[$sql_custom_fields_rows['table_name']]['url']))
            {
                $edit_url = INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/'.$tables_with_urls_id[$sql_custom_fields_rows['table_name']]['url'].'/?rid='.$sql_custom_fields_rows['id'].'&display-url=yes';
            }
            elseif($_SESSION['admin_sub_page'] == "Yes" && !empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET["sub-rid"] ?? '')))
            {
                $edit_url = $_SESSION['admin_sub_items_edit_url']."/?sub-page-rid=".trim($_GET["rid"] ?? '')."&sub-rid=".trim($_GET["sub-rid"] ?? '')."&rid=".$sql_custom_fields_rows['id'];
            }
            //customers/customer-accounts/addresses
            //customers/customer-accounts/license-keys
            elseif($_SESSION['admin_sub_page'] == "Yes" && !empty(trim($_GET["rid"] ?? '')))
            {
                $edit_url = $_SESSION['admin_sub_items_edit_url']."/?sub-page-rid=".trim($_GET["rid"] ?? '')."&rid=".$sql_custom_fields_rows['id'];
            }
            elseif(!empty($_SESSION['admin_edit_url']))
            {
                $edit_url = $_SESSION['admin_edit_url']."/?rid=".$sql_custom_fields_rows['id'];
            }
            else
            {
                $edit_url = "edit/?rid=".$sql_custom_fields_rows['id'];
            }
            echo '<li class="table-cell-results table-edit"><a href="'.$edit_url.'">Edit</a></li>';
            
            if(!empty($sql_account_columns_array))
            {
                foreach($sql_account_columns_array as $sql_account_columns_active) 
                {
                    //echo '<pre>'; print_r($sql_account_columns_active); echo '</pre>';
                    if($sql_account_columns_active["default_or_custom"] == "default")
                    {
                        $db_column_name = $sql_account_columns_active['column_name'];
                        $db_display_as = $sql_account_columns_active["display_as"];
                        $db_assigned_type = $_SESSION['admin_assigned_type'];
                        $db_data_type = $sql_account_columns_active["data_type"];
                        
                        //column_names run first as they are a higher level rule. If you create a file for a column name, it will process any data you pass through that admin field column.
                        //If you want to customize an existing /classes/table/column-names/ file, copy the file to the folder of /hooks/classes/table/column-names/. This allows you to edit existing files that software updates will not override.
                        if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/'.$db_column_name.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/'.$db_column_name.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/column-names/'.$db_column_name.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/column-names/'.$db_column_name.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/column-names/'.$db_column_name.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/column-names/'.$db_column_name.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/column-names/'.$db_column_name.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/column-names/'.$db_column_name.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/cms/classes/table/column-names/'.$db_column_name.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/cms/classes/table/column-names/'.$db_column_name.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/commerce/classes/table/column-names/'.$db_column_name.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/commerce/classes/table/column-names/'.$db_column_name.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/erp/classes/table/column-names/'.$db_column_name.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/erp/classes/table/column-names/'.$db_column_name.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/admin/ai/classes/table/column-names/'.$db_column_name.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/ai/classes/table/column-names/'.$db_column_name.'.php');
                        }
                        //display_as runs second as its a lower level rule. If you set an admin_field / column to display_as singleMedia, all column_names will run through the singleMedia display_as. This allows you to have multiple column_names that can run through the same display_as name.
                        //If you want to customize an existing /classes/table/display-as/ file, copy the file to the folder of /hooks/classes/table/display-as/. This allows you to edit existing files that software updates will not override.
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/display-as/'.$db_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/display-as/'.$db_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/display-as/'.$db_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/display-as/'.$db_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/display-as/'.$db_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/display-as/'.$db_display_as.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/display-as/'.$db_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/display-as/'.$db_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/cms/classes/table/display-as/'.$db_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/cms/classes/table/display-as/'.$db_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/commerce/classes/table/display-as/'.$db_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/commerce/classes/table/display-as/'.$db_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/erp/classes/table/display-as/'.$db_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/erp/classes/table/display-as/'.$db_display_as.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/admin/ai/classes/table/display-as/'.$db_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/ai/classes/table/display-as/'.$db_display_as.'.php');
                        }
                        //assigned_type runs third as its a lower level rule. If you set an admin_page to an assigned_type, you can create condition in this file to do specific things for that assigned type.
                        //If you want to customize an existing /classes/table/assigned-type/ file, copy the file to the folder of /hooks/classes/table/assigned-type/. This allows you to edit existing files that software updates will not override.
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/assigned-type/'.$db_assigned_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/assigned-type/'.$db_assigned_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/assigned-type/'.$db_assigned_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/assigned-type/'.$db_assigned_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/assigned-type/'.$db_assigned_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/assigned-type/'.$db_assigned_type.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/assigned-type/'.$db_assigned_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/assigned-type/'.$db_assigned_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/cms/classes/table/assigned-type/'.$db_assigned_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/cms/classes/table/assigned-type/'.$db_assigned_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/commerce/classes/table/assigned-type/'.$db_assigned_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/commerce/classes/table/assigned-type/'.$db_assigned_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/erp/classes/table/assigned-type/'.$db_assigned_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/erp/classes/table/assigned-type/'.$db_assigned_type.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/admin/ai/classes/table/assigned-type/'.$db_assigned_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/ai/classes/table/assigned-type/'.$db_assigned_type.'.php');
                        }
                        //assigned_type runs third as its a lower level rule. If you set an admin_page to an assigned_type, you can create condition in this file to do specific things for that assigned type.
                        //If you want to customize an existing /classes/table/data-type/ file, copy the file to the folder of /hooks/classes/table/data-type/. This allows you to edit existing files that software updates will not override.
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/data-type/'.$db_data_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/data-type/'.$db_data_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/data-type/'.$db_data_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/data-type/'.$db_data_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/data-type/'.$db_data_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/data-type/'.$db_data_type.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/data-type/'.$db_data_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/data-type/'.$db_data_type.'.php');
                        }
						
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/cms/classes/table/data-type/'.$db_data_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/cms/classes/table/data-type/'.$db_data_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/commerce/classes/table/data-type/'.$db_data_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/commerce/classes/table/data-type/'.$db_data_type.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/erp/classes/table/data-type/'.$db_data_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/erp/classes/table/data-type/'.$db_data_type.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/admin/ai/classes/table/data-type/'.$db_data_type.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/ai/classes/table/data-type/'.$db_data_type.'.php');
                        }
                        //else, if none of above true, display data from database as its saved.
                        else 
                        {
                            $else_table_content = substr($sql_custom_fields_rows[$sql_account_columns_active["column_name"]] ?? '', 0, 100);
                            
                            echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.str_replace(['&amp;#8593;', '&amp;#8595;'], ['&#8593;', '&#8595;'], htmlspecialchars($else_table_content ?? '')).'</li>';
                        }	
                    }
                    //Start Custom Fields
                    elseif($sql_account_columns_active["default_or_custom"] == "custom")
                    {
                        $db_cf_display_as = $sql_account_columns_active["cf_display_as"];
                        
                        //cf-display-as runs first as they are a higher level rule as a custom field. If you create a file for a cf-display-as, it will process any data you pass through that admin cf-display-as.
                        //If you want to customize an existing /classes/table/cf-display-as/ file, copy the file to the folder of /hooks/classes/table/cf-display-as/. This allows you to edit existing files that software updates will not override.
                        if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/cf-display-as/'.$db_cf_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/cf-display-as/'.$db_cf_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/cf-display-as/'.$db_cf_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/commerce/classes/table/cf-display-as/'.$db_cf_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/cf-display-as/'.$db_cf_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/erp/classes/table/cf-display-as/'.$db_cf_display_as.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/cf-display-as/'.$db_cf_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/hooks/admin/ai/classes/table/cf-display-as/'.$db_cf_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/cms/classes/table/cf-display-as/'.$db_cf_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/cms/classes/table/cf-display-as/'.$db_cf_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/commerce/classes/table/cf-display-as/'.$db_cf_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/commerce/classes/table/cf-display-as/'.$db_cf_display_as.'.php');
                        }
                        elseif(file_exists(INSTALLATION_ROOT.'/admin/erp/classes/table/cf-display-as/'.$db_cf_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/erp/classes/table/cf-display-as/'.$db_cf_display_as.'.php');
                        }
						elseif(file_exists(INSTALLATION_ROOT.'/admin/ai/classes/table/cf-display-as/'.$db_cf_display_as.'.php'))
                        {
                            include(INSTALLATION_ROOT.'/admin/ai/classes/table/cf-display-as/'.$db_cf_display_as.'.php');
                        }
                        elseif(!empty($sql_custom_fields_rows['custom_fields']))
                        {
                            $custom_field_array = JSON_DECODE($sql_custom_fields_rows['custom_fields'] ?? '', true);
                            
                            $custom_field_value = '';
                            
                            if(isset($custom_field_array[$sql_account_columns_active["column_name"]]))
                            {
                                $custom_field_value = $custom_field_array[$sql_account_columns_active["column_name"]];
                            }
                            
                            echo '<li class="table-cell-results">'.htmlspecialchars(substr($custom_field_value ?? '', 0, 100)).'</li>';
                        }
                        else
                        {
                            echo '<li class="table-cell-results"></li>';
                        }
                    }
                }
            }
            echo '</ul>'; 
        }
        echo '</div>';
    }
    ?>
    <!-- End Table Results Row -->
    </div>
    </div>
    
    <?php if($sql_custom_fields_sorted_count == 0) { ?>
    <div class="table-no-results">No Results</div>
    <?php } ?>
    <?php 
    if(!empty(trim($_GET["rid"] ?? '')) && !empty(trim($_GET["sub-rid"] ?? ''))) { ?>
    <input type="hidden" name="rid" value="<?php echo trim($_GET["rid"] ?? ''); ?>">
    <input type="hidden" name="sub-rid" value="<?php echo trim($_GET["sub-rid"] ?? ''); ?>">
    <?php } elseif(!empty(trim($_GET["rid"] ?? ''))) { ?>
    <input type="hidden" name="rid" value="<?php echo trim($_GET["rid"] ?? ''); ?>">
    <?php } ?>
    <input type="submit" id="search" class="display-as-none" />
    </form>
    <!-- End Table View -->
	<?php 
	} 
	else
	{
	?>
		<!-- Start Header -->
        <div class="header-text">
        <div class="text"><?php if(!empty($head_title_name)) { echo $head_title_name.' '; } echo $_SESSION['admin_title']; ?></div>
        </div>
        <!-- End Header -->
	<?php
	echo $account_message;
    }
	?>
    </div>
    <!-- End Right Column -->
    
    <!-- Start Popup for Table Columns -->
    <?php
    $urls_fields = '';
    if(!empty($_SESSION['admin_fields_urls_id']) && strpos($_SESSION['admin_fields_ids'] ?? '', ','.$_SESSION['admin_fields_urls_id'].',') !== false)
    {
        $urls_fields = $_SESSION['admin_urls_column_ids'].',';
    }
    
    //When an admin user is on the orders page in admin, include the orders_ship_to database columns so they can search orders based on the customers info.
    $orders_ship_to_admin_field_ids = '';
    if($_SESSION['admin_table_name'] == 'orders')
    {
        $database_table_orders_ship_to = $results->getSelectSingleRecord(__LINE__, __FILE__, 'admin_fields_ids', 'database_tables', 'WHERE `database_table_name` = ?', ['orders_ship_to']);
        
        if(!empty($database_table_orders_ship_to['admin_fields_ids']))
        {
            $orders_ship_to_admin_field_ids = trim($database_table_orders_ship_to['admin_fields_ids'], ',').',';
        }
    }
    
    $sql_account_columns_default = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` IN ('.$urls_fields.$orders_ship_to_admin_field_ids.$_SESSION['admin_fields_ids'].') ORDER BY FIELD(`id`, '.$urls_fields.$orders_ship_to_admin_field_ids.$_SESSION['admin_fields_ids'].')', []);
    
    if(!empty($sql_account_columns_default))
    {
        foreach($sql_account_columns_default as $sql_account_columns_default_rows) 
        {
            //Get option data for custom fields options / Languages
            if($sql_account_columns_default_rows['column_name'] == 'option_data')
            {
                $sql_account_columns_default_array[] = array('name' => 'Label & Value (All Languages)', "default_or_custom" => "default") + $sql_account_columns_default_rows;
            }
            elseif($sql_account_columns_default_rows['column_name'] == 'custom_field_name')
            {
                $sql_account_columns_default_array[] = array('name' => 'Frontend & Admin Name (All Languages)', "default_or_custom" => "default") + $sql_account_columns_default_rows;
            }
            else
            {
                $sql_account_columns_default_array[] = $sql_account_columns_default_rows + array("default_or_custom" => "default");
            }
        }
    }
    
    if($_SESSION['admin_assigned_type'] == "assigned_inventory")
    {
        //Get custom fields for inventory if assigning inventory to products
        $sql_account_columns_custom = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `assigned_to` = "inventory" '.$site_id_no_joined_query.' ORDER BY `column_name` ASC', $site_id_value);
        
        if(!empty($sql_account_columns_custom)) 
        {
            foreach($sql_account_columns_custom as $sql_account_columns_custom_rows) 
            {
                $custom_field_name = JSON_DECODE($sql_account_columns_custom_rows['custom_field_name'] ?? '', true);
                
                $sql_account_columns_custom_rows['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
                $sql_account_columns_custom_rows['admin_name'] = $custom_field_name[$_SESSION['admin_language']]['admin_name'] ?? '';
                
                $sql_account_columns_default_array[] = $sql_account_columns_custom_rows + array("default_or_custom" => "custom");
            }
        }
    }
    elseif($_SESSION['admin_assigned_type'] == "assign_products_to_category" || $_SESSION['admin_assigned_type'] == "assign_inventory_to_category")
    {
        //Get custom fields for products, sub products and inventory if assigning to a category
        $sql_account_columns_custom = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `assigned_to` = ? AND `field_type` != ? AND (`site_id` = ? OR `site_id` = ?) ORDER BY `column_name` ASC', ['Products', 'Product Option', $_SESSION["site_set_for_editing"], '0']);
        
        if(!empty($sql_account_columns_custom)) 
        {
            foreach($sql_account_columns_custom as $sql_account_columns_custom_rows) 
            {
                $custom_field_name = JSON_DECODE($sql_account_columns_custom_rows['custom_field_name'] ?? '', true);
                
                $sql_account_columns_custom_rows['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
                $sql_account_columns_custom_rows['admin_name'] = $custom_field_name[$_SESSION['admin_language']]['admin_name'] ?? '';
                
                $sql_account_columns_default_array[] = $sql_account_columns_custom_rows + array("default_or_custom" => "custom");
            }
        }
    }
    else
    {
        $sql_account_columns_custom = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `assigned_to` = ? AND `field_type` != ? AND (`site_id` = ? OR `site_id` = ?) ORDER BY `column_name` ASC', [$_SESSION['admin_table_name'], 'Product Option', $_SESSION["site_set_for_editing"], '0']);
        
        if(!empty($sql_account_columns_custom)) 
        {
            foreach($sql_account_columns_custom as $sql_account_columns_custom_rows) 
            {
                $custom_field_name = JSON_DECODE($sql_account_columns_custom_rows['custom_field_name'] ?? '', true);
                
                $sql_account_columns_custom_rows['frontend_name'] = $custom_field_name[$_SESSION['admin_language']]['frontend_name'] ?? '';
                $sql_account_columns_custom_rows['admin_name'] = $custom_field_name[$_SESSION['admin_language']]['admin_name'] ?? '';
                
                $sql_account_columns_default_array[] = $sql_account_columns_custom_rows + array("default_or_custom" => "custom");
            }
        }
    }
    ?>
    <div class="popup admin-width">
    <div class="columns">
    <div class="header">
    <div class="header-top">
    <div class="headline">Column Chooser
    <div class="display-font-size-margin-top">
    <div class="float-left"><input type="checkbox" id="CheckAllColumns" class="height-width-margin"></div>
    <label for="CheckAllColumns" class="padding-left-font-weight">Select All</label>
    </div>
    </div>
    <div class="close hide-columns"><i><svg viewBox="0 0 512 512"><path d="M506 256A250 250 0 1 1 6 256 250 250 0 0 1 506 256M173 151A16 16 0 1 0 151 173L234 256 151 339A16 16 0 0 0 173 361L256 278 339 361A16 16 0 0 0 361 339L278 256 361 173A16 16 0 0 0 339 151L256 234Z"></path></svg></i></div>
    </div>
    </div>
    <form method="post">
    <ul class="column-list">
    <?php 
    //Assigned default columns.
    if(!empty($sql_account_columns_assigned_array))
    {
        foreach($sql_account_columns_assigned_array as $assigned_columns) 
        {  
            foreach ($sql_account_columns_default_array as $default_columns) 
            {
                if($default_columns["default_or_custom"] == "default" && $assigned_columns["default_or_custom"]  == "default")
                {
    
                    if($assigned_columns["field_id"] == $default_columns["id"])
                    {
                        echo '<li id="columnid_'.$default_columns["id"].'">
                        <div class="sort-columns">
                        <div class="column-box">
                        <div class="column-box-left"><input name="column[]" type="checkbox" class="CheckAllColumns" value="'.$default_columns["id"].'-default" checked></div>
                        <div class="column-box-right">
                        <div class="column-box-name">'.htmlspecialchars($default_columns["name"] ?? '').'</div>
                        <div class="column-box-type">Default Column</div>
                        </div>
                        </div>
                        </div>
                        </li>';
                    }
                } 
                elseif($default_columns["default_or_custom"] == "custom" && $assigned_columns["default_or_custom"]  == "custom")
                {
                    if($assigned_columns["field_id"] == $default_columns["id"])
                    {
                        if(empty($default_columns["frontend_name"]))
                        {
                            $frontend_name_data = 'Not Set';
                        }
                        else
                        {
                            $frontend_name_data = htmlspecialchars($default_columns["frontend_name"] ?? '');
                        }
                        
                        echo '<li id="columnid_'.$default_columns["id"].'">
                        <div class="sort-columns">
                        <div class="column-box">
                        <div class="column-box-left"><input name="column[]" type="checkbox" class="CheckAllColumns" value="'.$default_columns["id"].'-custom" checked></div>
                        <div class="column-box-right">
                        <div class="column-box-name">'.$frontend_name_data.'</div>
                        <div class="column-box-type">Custom Column</div>
                        </div>
                        </div>
                        </div>
                        </li>';
                    }
                }
            }
        }
    }
    //Not assigned default columns.
    if(!empty($sql_account_columns_default_array))
    {
        foreach ($sql_account_columns_default_array as $default_columns) 
        {
            if($default_columns["default_or_custom"] == "default")
            {
                if(!in_array($default_columns["id"], $sql_account_columns_default_assigned_id))
                {
                    echo '<li id="columnid_'.$default_columns["id"].'">
                    <div class="sort-columns">
                    <div class="column-box">
                    <div class="column-box-left"><input name="column[]" type="checkbox" class="CheckAllColumns" value="'.$default_columns["id"].'-default"></div>
                    <div class="column-box-right">
                    <div class="column-box-name">'.htmlspecialchars($default_columns["name"] ?? '').'</div>
                    <div class="column-box-type">Default Column</div>
                    </div>
                    </div>
                    </div>
                    </li>';
                }
            }
            elseif($default_columns["default_or_custom"] == "custom")
            {
                if(empty($default_columns["frontend_name"]))
                {
                    $frontend_name_data = 'Not Set';
                }
                else
                {
                    $frontend_name_data = htmlspecialchars($default_columns["frontend_name"] ?? '');
                }
                
                if(!in_array($default_columns["id"], $sql_account_columns_custom_assigned_id))
                {
                    echo '<li id="columnid_'.$default_columns["id"].'">
                    <div class="sort-columns">
                    <div class="column-box">
                    <div class="column-box-left"><input name="column[]" type="checkbox" class="CheckAllColumns" value="'.$default_columns["id"].'-custom"></div>
                    <div class="column-box-right">
                    <div class="column-box-name">'.$frontend_name_data.'</div>
                    <div class="column-box-type">Custom Column</div>
                    </div>
                    </div>
                    </div>
                    </li>';
                }
            }
        }
    }
    ?>
    </ul>
    <div class="popup-button"><button type="submit" name="save">Save</button></div>
    </form>
    </div>
    </div>
    <!-- End Popup for Table Columns -->
	</body>
	</html>
<?php } ?>