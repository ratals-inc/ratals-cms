<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('sidebar-blog-categories.php', $data_array['active_template_includes']) && !empty($data_array['sidebar_blog_categories'])) { ?>
<div class="box">
    <div class="box-inner">
        <div class="title">Blog Categories</div>
        <ul>
            <?php foreach($data_array['sidebar_blog_categories'] as $blog_categories) { ?>
            <li><i class="arrow"><svg viewBox="0 0 512 512"><path d="M127 500A19 19 0 0 0 154 500L385 270A19 19 0 0 0 385 242L154 12A19 19 0 0 0 127 39L344 256 127 473A19 19 0 0 0 127 500"></path></svg></i> <a href="<?php echo $blog_categories['url']?>"><?php echo $blog_categories['meta_title']?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
<?php } ?>
