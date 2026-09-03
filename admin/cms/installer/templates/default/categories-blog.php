<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//echo '<pre>'; print_r($data_array); echo '</pre>';
include_once('sites/functions.php');
?>
<!DOCTYPE html>
<html lang="<?php echo $_SESSION['site_language']; ?>">
<head>
<?php if(!empty($full_meta_title)) { ?>
<title><?php echo $full_meta_title?></title>
<?php } ?>
<?php if(!empty($meta_description)) { ?>
<meta name="description" content="<?php echo $meta_description; ?>">
<?php } ?>
<?php if(!empty($meta_keywords)) { ?>
<meta name="keywords" content="<?php echo $meta_keywords; ?>">
<?php } ?>
<?php if(!empty($canonical_url)) 
{ 
	$pagination_page_number = '';
	if($number_of_paginated_pages > 1 && $page_number > 1)
	{
		$pagination_page_number = '?page='.$page_number;
	}
?>
<link rel="canonical" href="<?php echo $canonical_url.$pagination_page_number; ?>">
<?php } ?>
<?php if(!empty($meta_robots)) { ?>
<meta name="robots" content="<?php echo $meta_robots; ?>">
<?php } ?>
<?php if(!empty($pages_data['hreflang'])) { echo $pages_data['hreflang'].'
'; } ?>
<meta property="og:type" content="website">
<?php if(!empty($full_meta_title)) { ?>
<meta property="og:title" content="<?php echo $full_meta_title; ?>">
<?php } ?>
<?php if(!empty($meta_description)) { ?>
<meta property="og:description" content="<?php echo $meta_description; ?>">
<?php } ?>
<?php if(!empty($data_array['pages_url'])) { ?>
<meta property="og:url" content="<?php echo $data_array['pages_url']; ?>">
<?php } ?>
<?php if(!empty($site_name)) { ?>
<meta property="og:site_name" content="<?php echo $site_name; ?>">
<?php } ?>
<?php if(!empty($data_array['media_data'][0]['full_url'])) { ?>
<meta property="og:image" content="<?php echo $data_array['media_data'][0]['full_url']; ?>">
<?php } ?>
<?php if(!empty($data_array['media_data'][0]['media_tag'])) { ?>
<meta property="og:image:alt" content="<?php echo $data_array['media_data'][0]['media_tag']; ?>">
<?php } ?>
<?php include('head-files.php'); ?>
</head>

<body><?php 
//  START CAUTION!
//  IF YOU MODIFY THE "EDIT IN ADMIN" CODE BELOW, MAKE SURE TO UPDATE THE CODE IN /INDEX.PHP FILE TO PREVENT IT FROM BEING CACHED. ?>
<!-- Start Edit Admin Page -->
<?php
if(isset($_SESSION['user_id'])) 
{
	echo '<div class="edit-page"><a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/website/'.$pages_data['table_name'].'/edit/?rid='.$id.'" target="_blank">Edit in Admin</a></div>';
}
?>
<!-- End Edit Admin Page --><?php //END CAUTION! ?>
<?php if($commerce_installed) { include('header-free-shipping.php'); } ?>
<?php include('header.php'); ?>
<?php if($commerce_installed) { include('header-customer-account-control.php'); } ?>
<!-- Start Breadcrumbs -->
<nav class="breadcrumbs">
    <div class="breadcrumbs-wrap container-width">
        <?php include('breadcrumbs.php'); ?>
    </div>
</nav>
<!-- End Breadcrumbs -->
<main>
    <!-- Start Blog -->
    <div class="blog">
        <div class="blog-wrap container-width">
            <!-- Start Posts -->
            <div class="posts">
                <?php 
                if(!empty($data_array['content_title']) || !empty($data_array['top_content'])) 
                { 
                ?>
                <div class="content-top">
                    <?php if(!empty($data_array['content_title'])) { ?><h1 class="title"><?php echo $content_title; ?></h1><?php } ?>
                    <?php include('author-and-dates-top.php'); ?>
                    <?php if(!empty($data_array['top_content'])) { ?><div class="top-content"><?php echo $top_content; ?></div><?php } ?>
                </div>
                <?php } ?>
                <?php 
                if(!empty($data_array['posts'])) 
                { 
                ?>
                <!-- Start Design Blocks -->
                <div class="categories-store-design-blocks-style">
                    <div class="design-blocks design-blocks-bottom-padding">
                        <div class="design-blocks-wrap container-width">
                            <ul class="grid-<?php echo empty($data_array['grid_columns']) ? '2' : $data_array['grid_columns']; ?>">
                                <?php 
                                foreach($data_array['posts'] as $posts)
                                {
                                ?>
                                    <!-- Start Post Archive -->
                                    <li>
                                        <?php if(isset($posts['media_data'][0]) && !empty($posts['media_data'][0])) 
                                        {
                                        ?>
                                        <div class="img"><a href="<?php echo $posts['final_url']; ?>"><?php echo $posts['media_data'][0]; ?></a></div>
                                        <?php } ?>
                                        <div class="text-post-archive">
                                            <div class="author-info">
                                                <?php if(isset($posts['author_bio']['author_media']) && !empty($posts['author_bio']['author_media'])) 
                                                {
                                                ?>
                                                <span class="author-photo"><?php echo $posts['author_bio']['author_media']; ?></span>
                                                <?php } ?>
                                                <?php if(isset($posts['author_bio']['author_name']) && !empty($posts['author_bio']['author_name'])) { ?>
                                                <span class="author-name"><span class="author-title">Author: </span><?php echo $posts['author_bio']['author_name']; ?></span>
                                                <?php } ?>
                                                <?php if($posts['display_posted_on'] == 'Yes' && isset($posts['created_date']))
                                                {
                                                    $created_date = utcToUserTimeZone($posts['created_date'], 'M. d, Y');
                                                ?>
                                                <span class="post-date"><span class="post-date-title">Posted On: </span><?php echo $created_date; ?></span>
                                                <?php } ?>
                                                <?php if(!empty($posts['posted_in_category'])) { ?>
                                                <span class="posted-category"><span class="posted-category-title">Posted In: </span><a href="<?php echo $posts['posted_in_category']['url']; ?>"><?php echo $posts['posted_in_category']['meta_title']; ?></a></span>
                                                <?php } ?>
                                            </div>
                                            <h2 class="title"><a href="<?php echo $posts['final_url']; ?>"><?php echo $posts['meta_title']; ?></a></h2>
                                        </div>
                                    </li>
                                    <!-- End Post Archive -->
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- End Design Blocks -->
                <?php } ?>
                <!-- Start Pagination -->
                <?php 
                if($number_of_paginated_pages > 1) 
                {
                    if(!empty($page_number) && $page_number != 2) { $prev_pagination = '?page='.($page_number - 1); } else { $prev_pagination = ''; }
                    if(!empty($page_number)) { $next_pagination = '?page='.($page_number + 1); } else { $next_pagination = '?page=2'; } 
                    
                    //This controls how pagination works. If there are mroe than 5 pages start showing 1..., etc.
                    $greater_than = 5;
                ?>
                    <div class="pagination">
                        <div class="pagination-wrap container-width">
                            <ul>
                                <?php if($page_number > 1) { ?>
                                <li><a href="<?php echo $data_array['pages_url'].$prev_pagination; ?>"><span><svg viewBox="0 0 512 512"><path d="M385 12A19 19 0 0 0 358 12L127 242A19 19 0 0 0 127 270L358 500A19 19 0 0 0 385 473L168 256 385 39A19 19 0 0 0 385 12"></path></svg></span> Prev</a></li>
                                <?php } ?>
                                <?php if($page_number > 3 && $number_of_paginated_pages > $greater_than) { ?>
                                <li><a href="<?php echo $data_array['pages_url']; ?>">1</a> ... </li>
                                <?php } ?>
                                <?php 
                                if($page_number == 1 && $number_of_paginated_pages > $greater_than)
                                {
                                    $number_of_paginated_pages_first = $page_number;
                                    $number_of_paginated_pages_last = $page_number + 4;
                                }
                                elseif($page_number == 2 && $number_of_paginated_pages > $greater_than)
                                {
                                    $number_of_paginated_pages_first = $page_number - 1;
                                    $number_of_paginated_pages_last = $page_number + 3;
                                }
                                
                                elseif($page_number == $number_of_paginated_pages - 1 && $number_of_paginated_pages > $greater_than)
                                {
                                    $number_of_paginated_pages_first = $number_of_paginated_pages - 4;
                                    $number_of_paginated_pages_last = $page_number + 1;
                                }
                                elseif($page_number == $number_of_paginated_pages && $number_of_paginated_pages > $greater_than)
                                {
                                    $number_of_paginated_pages_first = $number_of_paginated_pages - 5;
                                    $number_of_paginated_pages_last = $page_number;
                                }
                                elseif($number_of_paginated_pages > 3 && $number_of_paginated_pages > $greater_than)
                                {
                                    $number_of_paginated_pages_first = $page_number - 2;
                                    $number_of_paginated_pages_last = $page_number + 2;
                                }
                                else
                                {
                                    $number_of_paginated_pages_first = 1;
                                    $number_of_paginated_pages_last = $number_of_paginated_pages;
                                }
                                
                                for($i = $number_of_paginated_pages_first; $i <= $number_of_paginated_pages_last; $i++)
                                {
                                    $pagination_counter = $i;  
                                    $active_class = '';
                                    if($pagination_counter == $page_number || ($pagination_counter == 1 && empty($page_number))) { $active_class = ' class="active"'; }
                                    if($pagination_counter > 1) { $dynamic_page_counter = '?page='.$pagination_counter; } else { $dynamic_page_counter = ''; }
                                    echo '<li><a href="'.$data_array['pages_url'].$dynamic_page_counter.'"'.$active_class.'>'.$pagination_counter.'</a></li> '; 
                                }
                                ?>
                                <?php if($page_number < $number_of_paginated_pages - 2 && $number_of_paginated_pages > $greater_than) { ?>
                                <li> ... <a href="<?php echo $data_array['pages_url'].'?page='.$number_of_paginated_pages; ?>"><?php echo $number_of_paginated_pages ?></a></li>
                                <?php } ?>
                                <?php if($page_number < $number_of_paginated_pages) { ?>
                                <li><a href="<?php echo $data_array['pages_url'].$next_pagination; ?>">Next <span><svg viewBox="0 0 512 512"><path d="M127 500A19 19 0 0 0 154 500L385 270A19 19 0 0 0 385 242L154 12A19 19 0 0 0 127 39L344 256 127 473A19 19 0 0 0 127 500"></path></svg></span></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
                <!-- End Pagination -->
                <?php if(isset($data_array['author_bio']['author_page_url']) && !empty($data_array['author_bio']['author_page_url'])) { ?>
                <!-- Stat Author Bio -->
                <div class="short-bio-wrapper padding-top">
                    <div class="short-bio container-width">
                        <?php
                        if(isset($data_array['author_bio']['author_media']) && !empty($data_array['author_bio']['author_media']))
                        {
                            echo '<div class="photo">'.$data_array['author_bio']['author_media'].'</div>';
                        }
                        ?>
                        <div>
                        <div class="intro">About the Author</div>
                        <div class="author-name"><a href="<?php echo $data_array['author_bio']['author_page_url']; ?>"><?php echo $data_array['author_bio']['author_name']; ?></a></div>
                        <?php echo $data_array['author_bio']['author_description']; ?>
                        </div>
                    </div>
                </div>
                <!-- End Author Bio -->
                <?php } ?>
            </div>
            <!-- End Posts -->
            <!-- Start Sidebar -->
            <div class="sidebar">
                <!-- Start Sidebar Box -->
                <?php include('sidebar-about.php'); ?>
                <!-- End Sidebar Box -->
                <!-- Start Sidebar Box -->
                <?php include('sidebar-blog-categories.php'); ?>
                <!-- End Sidebar Box -->
                <!-- Start Sidebar Box -->
                <?php include('sidebar-table-of-contents.php'); ?>
                <!-- End Sidebar Box -->
            </div>
            <!-- End Sidebar -->
        </div>
    </div>
    <!-- End Blog -->
    <?php if(!empty($data_array['design_blocks'])) { ?>
        <!-- Start Design Blocks -->
        <div class="pages-design-blocks-style">
            <?php include('design-blocks.php'); ?>
        </div>
        <!-- End Design Blocks -->
    <?php } ?>
    <?php if(!empty($bottom_content)) { ?>
        <!-- Start Bottom Content -->
        <div class="bottom-content">
            <div class="bottom-content-wrap container-width"><?php echo $bottom_content; ?></div>
        </div>
        <!-- End Bottom Content -->
    <?php } ?>
</main>
<?php include('footer.php'); ?>
<?php include('cookie-notice-banner.php'); ?>
<?php if($commerce_installed) { include('affiliate.php'); } ?>
<?php include('analytics.php'); ?>
</body>
</html>