<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('header.php', $data_array['active_template_includes'])) { ?><?php 
//  START CAUTION!
//  IF YOU MODIFY THE "<!-- Start Header -->" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED. ?>
<!-- Start Header --><?php //END CAUTION! ?>
<header class="header-wrapper">
    <div class="header">
        <div class="container-width">
            <div class="header-wrap">
                <div class="logo"><a href="<?php echo urlId(0); ?>"><?php if(!empty($logo_media)) { echo $logo_media; } else { echo $site_name; } ?></a></div>
                <nav class="mobile-menu">
                    <div class="mobile-bars"><svg viewBox="0 0 512 512"><path d="m5 441a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23m0-183a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23m0-183a23 23 0 0 1 23-23h459a23 23 0 0 1 0 46h-459a23 23 0 0 1-23-23"></path></svg></div>
                    <?php if(strpos(urlId([SITE_SEARCH_PAGE]), '/?url-id-disabled-or-deleted=') === false) { ?>
                        <div class="search-button-toggle"><svg viewBox="0 0 512 512"><path d="M212.982 4.467c112.269 0 203.219 90.995 203.219 203.213 0 38.382-10.643 74.268-29.136 104.89l98.526 98.526-.343.343c10.516 10.065 16.975 24.354 16.975 40.503.006 31.2-24.849 55.598-56.017 55.591-15.92-.006-30.158-6.376-40.319-16.74l-.248.248-101.657-101.664c-27.383 13.736-58.271 21.515-91.001 21.515-112.275 0-203.206-90.982-203.206-203.213 0-112.218 90.938-203.213 203.206-203.213zm-1.232 326.519c70.172 0 127.014-56.861 127.014-127.008 0-70.134-56.842-127.008-127.014-127.008-70.172 0-127.002 56.868-127.002 127.008-.006 70.147 56.83 127.008 127.002 127.008z"></path></svg></div>
                    <?php } ?>
                    <?php if($commerce_installed) { include('header-cart-quantity-button.php'); } ?>
                </nav>
                <nav class="menu">
                    <ul class="main-menu">
                        <?php 
                        unset($_SESSION['menu_main']);
                        unset($_SESSION['menu_items_main']);
                        unset($_SESSION['menu_items_pages_row']);
                        
                        $menu_id = '[HEADER_MENU]'; 
                        if(!empty($menu_id))
                        {
                            function menu_header($menuArray) 
                            { 
                                if(!empty($menuArray)) 
                                { 
                                    foreach($menuArray as $menu) 
                                    {
                                        $sub_items_arrow = '';
                                        
                                        if(!empty($menu['children'])) 
                                        {
                                            $sub_items_arrow = ' <i class="desktop-sub-menu-arrow"><svg viewBox="0 0 512 512"><path d="M12 127A19 19 0 0 0 12 154L242 385A19 19 0 0 0 270 385L500 154A19 19 0 0 0 473 127L256 344 39 127A19 19 0 0 0 12 127"></path></svg></i>';
                                        }
                                        
                                        echo "<li>"; 
                                        $link_type = '';
                                        if(!empty($menu['menu_url_link_type'])) { $link_type = ' rel="'.$menu['menu_url_link_type'].'"'; }
										$css_class = '';
                                        if(!empty($menu['css_class_name'])) { $css_class = ' class="'.$menu['css_class_name'].'"'; }
                                        echo "<a href='".$menu['menu_url']."'".$link_type.$css_class.">" . $menu['label'] . $sub_items_arrow."</a>"; 
                                        if(!empty($menu['children'])) 
                                        {
                                            echo '<i class="mobile-sub-menu-arrow"><svg viewBox="0 0 512 512"><path d="M12 127A19 19 0 0 0 12 154L242 385A19 19 0 0 0 270 385L500 154A19 19 0 0 0 473 127L256 344 39 127A19 19 0 0 0 12 127"></path></svg></i>
                                            <ul>';
                                            menu_header($menu['children']); 
                                            echo '</ul>'; 
                                        } 
                                        echo "</li>";
                                    }
                                }
                            }
                            menu_header(navMenu($menu_id)); 
                        }
                        ?>
                        <?php if($commerce_installed) { include('header-customer-account-menu.php'); } ?>
                    </ul>
                    <?php if(strpos(urlId([SITE_SEARCH_PAGE]), '/?url-id-disabled-or-deleted=') === false) { ?>
                        <div class="search-button-toggle"><svg viewBox="0 0 512 512"><path d="M212.982 4.467c112.269 0 203.219 90.995 203.219 203.213 0 38.382-10.643 74.268-29.136 104.89l98.526 98.526-.343.343c10.516 10.065 16.975 24.354 16.975 40.503.006 31.2-24.849 55.598-56.017 55.591-15.92-.006-30.158-6.376-40.319-16.74l-.248.248-101.657-101.664c-27.383 13.736-58.271 21.515-91.001 21.515-112.275 0-203.206-90.982-203.206-203.213 0-112.218 90.938-203.213 203.206-203.213zm-1.232 326.519c70.172 0 127.014-56.861 127.014-127.008 0-70.134-56.842-127.008-127.014-127.008-70.172 0-127.002 56.868-127.002 127.008-.006 70.147 56.83 127.008 127.002 127.008z"></path></svg></div>
                    <?php } ?>
                    <?php if($commerce_installed) { include('header-cart-quantity-button.php'); } ?>
                </nav>
            </div>
        </div>
    </div>
    <?php include('header-search-bar.php'); ?>
</header><?php 
//  START CAUTION!
//  IF YOU MODIFY THE "<!-- End Header -->" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED. ?>
<!-- End Header --><?php //END CAUTION! ?>
<?php } ?>
