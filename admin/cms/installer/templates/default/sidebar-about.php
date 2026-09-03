<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('sidebar-about.php', $data_array['active_template_includes'])) { ?>
<div class="box">
    <div class="box-inner">
        <?php 
		$image_data = customField('[COMPANY_IMAGE]', $rid);
		if(!empty($image_data))
		{
			$media_output = mediaId($image_data[0], 'No', 'Yes', '', $image_data[1]);
			echo '<div class="img">'.$media_output.'</div>';
		}
		?>
        <div class="title"><?php echo customField('[COMPANY_TITLE]', $rid); ?></div>
        <div class="text"> <?php echo customField('[COMPANY_TEXT]', $rid); ?> </div>
        <div class="button"><a href="<?php echo urlId([ABOUT_US_PAGE]); ?>">Read More About Us</a></div>
    </div>
</div>
<?php } ?>
