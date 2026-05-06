<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('sidebar-contact-us.php', $data_array['active_template_includes'])) { 
if($contact_info_display_contact_info == 'Yes') { ?>
<div class="box">
    <div class="box-inner">
        <?php if(!empty($contact_info_company_media)) { ?>
        <div class="img"><?php echo $contact_info_company_media; ?></div>
        <?php } ?>
        <?php if($contact_info_display_contact_info == 'Yes') { ?>
        <div class="title"><?php echo $site_name; ?></div>
        <div class="text">
            <div class="street-address"><?php echo $contact_info_street_address; ?></div>
            <div class="city"><?php echo $contact_info_city.', '.$contact_info_state.' '.$contact_info_postal_code; ?></div>
            <div class="hours"><?php echo $contact_info_hours; ?></div>
            <?php if(!empty($contact_info_phone_number)) { ?><div class="phone">Phone: <a href="tel:<?php echo $contact_info_phone_number; ?>"><?php echo $contact_info_phone_number; ?></a></div><?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
<?php } ?>
<?php } ?>
