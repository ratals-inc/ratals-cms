<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('author-and-dates-top.php', $data_array['active_template_includes'])) { ?>
	<?php 
    $author_bio_name = '';
    $author_photo = '';
    if(isset($data_array['author_bio']['author_page_url']) && !empty($data_array['author_bio']['author_page_url']))
    {
        if(isset($data_array['author_bio']['author_media']) && !empty($data_array['author_bio']['author_media']))
        {
            $author_photo = $data_array['author_bio']['author_media'];
        }
        
        if(isset($data_array['author_bio']['author_name']) && !empty($data_array['author_bio']['author_name']))
        {
            $author_bio_name = $data_array['author_bio']['author_name'];
        }
    }
    
    $user_timezone = new DateTimeZone($_SESSION['timezone']);
    
    if(!empty($author_bio_name) || !empty($author_photo) || $data_array['display_posted_on'] == 'Yes' || $data_array['display_last_updated'] == 'Yes')
    {
    ?>
    <div class="author-info">
        <?php if(!empty($author_photo)) { ?>
        <span class="author-photo"><?php echo $author_photo; ?></span>
        <?php } ?>
        <?php if(!empty($author_bio_name)) { ?>
        <span class="author-name"><span class="author-title">Author: </span><?php echo htmlspecialchars($author_bio_name); ?></span>
        <?php } ?>
        <?php if($data_array['display_posted_on'] == 'Yes' && isset($data_array['created_date']))
        {
            $created_date = utcToUserTimeZone($data_array['created_date'], 'M. d, Y');
        ?>
        <span class="post-date"><span class="post-date-title">Posted On: </span><?php echo $created_date; ?></span>
        <?php } ?>
        <?php if($data_array['display_last_updated'] == 'Yes' && isset($data_array['updated_date']))
        {
            $updated_date = utcToUserTimeZone($data_array['updated_date'], 'M. d, Y');
        ?>
        <span class="post-date"><span class="post-date-title">Last Updated: </span><?php echo $updated_date; ?></span>
        <?php } ?>
    </div>
    <?php } ?>
<?php } ?>
