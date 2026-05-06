<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('footer.php', $data_array['active_template_includes'])) { ?>
<!-- Start Footer -->
<footer class="footer">
    <div class="footer-wrap container-width">
        <?php unset($_SESSION['menu_main']); unset($_SESSION['menu_items_main']); unset($_SESSION['menu_items_pages_row']); $menu_id = '[CONNECT_ON_SOCIAL]'; function menu_connect_on_social($menuArray) { if(!empty($menuArray)) { 
		?>
        <div class="contact">
            <div class="title"><?php if(isset($_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name']) && !empty($_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name'])) { echo $_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name']; } else { echo 'Menu'; } ?></div>
            <div class="text">Lets Get in Touch</div>
            <div class="sub-text">If you would like to email us, <a href="<?php echo urlId([CONTACT_US_PAGE]); ?>">you can do so here</a>. Otherwise, connect with us on our social media channels here:</div>
            <div class="social">
                <ul>
                    <?php foreach ($menuArray as $menu) { echo "<li title='".$menu['label']."' >"; $link_type = ''; if(!empty($menu['menu_url_link_type'])) { $link_type = ' rel="'.$menu['menu_url_link_type'].'"'; } echo "<a href='".$menu['menu_url']."'".$link_type." target='_blank' aria-label='".$menu['label']."'>".$menu['svg_path']."</a>"; if( ! empty($menu['children'])) { echo "<ul>"; menu_connect_on_social($menu['children']); echo "</ul>"; } echo "</li>"; } ?>
                </ul>
            </div>
        </div>
        <?php } } menu_connect_on_social(navMenu($menu_id)); ?>
        <?php unset($_SESSION['menu_main']); unset($_SESSION['menu_items_main']); unset($_SESSION['menu_items_pages_row']); $menu_id = '[FOOTER_CATEGORIES]'; function menu_footer_categories($menuArray) { if(!empty($menuArray)) { ?>
        <div class="company">
            <div class="title"><?php if(isset($_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name']) && !empty($_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name'])) { echo $_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name']; } else { echo 'Menu'; } ?></div>
            <ul>
                <?php foreach ($menuArray as $menu) { echo "<li>"; $link_type = ''; if(!empty($menu['menu_url_link_type'])) { $link_type = ' rel="'.$menu['menu_url_link_type'].'"'; } echo "<a href='".$menu['menu_url']."'".$link_type.">".$menu['label']."</a>"; if( ! empty($menu['children'])) { echo '<span><svg viewBox="0 0 512 512"><path d="M12 127A19 19 0 0 0 12 154L242 385A19 19 0 0 0 270 385L500 154A19 19 0 0 0 473 127L256 344 39 127A19 19 0 0 0 12 127"></path></svg></span><ul>'; menu_footer_categories($menu['children']); echo "</ul>"; } echo "</li>"; } ?>
            </ul>
        </div>
		<?php } } menu_footer_categories(navMenu($menu_id)); ?>
        <?php unset($_SESSION['menu_main']); unset($_SESSION['menu_items_main']); unset($_SESSION['menu_items_pages_row']); $menu_id = '[FOOTER_BOTTOM]'; function menu_footer_bottom($menuArray) { if(!empty($menuArray)) { ?>
        <div class="site-nav">
            <div class="title"><?php if(isset($_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name']) && !empty($_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name'])) { echo $_SESSION['menu_main'][$menuArray[0]['menus_id']]['frontend_name']; } else { echo 'Menu'; } ?></div>
            <ul>
                <?php foreach ($menuArray as $menu) { echo "<li>"; $link_type = ''; if(!empty($menu['menu_url_link_type'])) { $link_type = ' rel="'.$menu['menu_url_link_type'].'"'; } echo "<a href='".$menu['menu_url']."'".$link_type.">".$menu['label']."</a>"; if( ! empty($menu['children'])) { echo "<ul>"; menu_footer_bottom($menu['children']); echo "</ul>"; } echo "</li>"; } ?>
            </ul>
        </div>
        <?php } } menu_footer_bottom(navMenu($menu_id)); ?>
        <?php if($contact_info_display_contact_info == 'Yes') { ?>
        <div class="headquarters">
            <div class="title">HEADQUARTERS</div>
            <div class="street-address"><?php echo $contact_info_street_address; ?></div>
            <div class="city-state-zip"><?php echo $contact_info_city.', '.$contact_info_state.' '.$contact_info_postal_code; ?></div>
            <div class="hours"><?php echo $contact_info_hours; ?></div>
            <?php if(!empty($contact_info_phone_number)) { ?><div class="phone-number">Phone: <a href="tel:<?php echo $contact_info_phone_number; ?>"><?php echo $contact_info_phone_number; ?></a></div><?php } ?>
        </div>
        <?php } ?>
    </div>
    <div class="footer-bottom container-width">
        <div class="copy">© <?php echo date("Y"); ?> <?php echo $site_name; ?> - All Right Reserved - Powered by <a href="https://www.ratals.com/" target="_blank">Ratals</a></div><?php 
		//  START CAUTION!
		//  IF YOU MODIFY THE "<!-- Start Footer Cart ID -->" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED. ?>
        <!-- Start Footer Cart ID --><?php //END CAUTION! ?>
		<?php if(isset($sql_get_cart_id_row['id']) && !empty($sql_get_cart_id_row['id'])) { ?>
        <div class="cart-id">Cart ID: <?php echo $sql_get_cart_id_row['id']; ?></div>
        <?php } ?><?php 
		//  START CAUTION!
		//  IF YOU MODIFY THE "<!-- Start Footer Cart ID -->" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED. ?>
        <!-- End Footer Cart ID --><?php //END CAUTION! ?>
    </div>
</footer>
<!-- End Footer -->
<?php } ?>
