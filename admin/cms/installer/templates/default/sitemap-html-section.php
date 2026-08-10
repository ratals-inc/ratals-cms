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
<title><?php echo $table_name_text; ?> Sitemap<?php if($add_site_name_to_title_tag == 'Yes') { echo ' '; echo $separate_site_name_in_title_tag_with ?? ''; echo ' '; echo $site_name ?? ''; } ?></title>
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
		$pagination_page_number = '?page='.$page_number.'&section='.$table_name;
	}
	else
	{
		$pagination_page_number = '?section='.$table_name;
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
<meta property="og:title" content="<?php echo $table_name_text; ?> Sitemap<?php if($add_site_name_to_title_tag == 'Yes') { echo ' '; echo $separate_site_name_in_title_tag_with ?? ''; echo ' '; echo $site_name ?? ''; } ?>">
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
	echo '<div class="edit-page"><a href="/'.$_SESSION['admin_directory'].'/website/'.$pages_data['table_name'].'/edit/?rid='.$id.'" target="_blank">Edit in Admin</a></div>';
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
    <!-- Start One Column Layout -->
    <div class="pages-one-column">
        <div class="pages-one-column-wrap container-width">
            <div class="content">
                <?php if(!empty($table_name_text)) { ?><h1 class="title"><?php echo $table_name_text; ?> Sitemap</h1><?php } ?>
                <?php include('author-and-dates-top.php'); ?>
                <?php if(!empty($top_content)) { ?><?php echo $top_content; ?><?php } ?>
                <div class="sitemap section">
                    <?php 
                    if(!empty($data_array['html_data_'.$table_name]))
                    {
                        echo '<ul class="sitemap-categories">';
                        
                        foreach($data_array['html_data_'.$table_name] as $html_sitemap)
                        {
                            echo '<li><a href="'.$html_sitemap['url'].'">'.$html_sitemap['meta_title'].'</a></li>';
                        }
                        
                        echo '</ul>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <!-- End One Column Layout -->
    <!-- Start Pagination -->
    <?php 
    if($number_of_paginated_pages > 1) 
    {
        $section = $_GET['section'];
        if(!empty($page_number) && $page_number != 2) { $prev_pagination = '?page='.($page_number - 1).'&section='.$section; } else { $prev_pagination = '?section='.$section; }
        if(!empty($page_number)) { $next_pagination = '?page='.($page_number + 1).'&section='.$section; } else { $next_pagination = '?page=2'.'&section='.$section; } 
        
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
                <li><a href="<?php echo $data_array['pages_url'].'?section='.$section; ?>">1</a> ... </li>
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
                    if($pagination_counter > 1) { $dynamic_page_counter = '?page='.$pagination_counter.'&section='.$section; } else { $dynamic_page_counter = '?section='.$section; }
                    echo '<li><a href="'.$data_array['pages_url'].$dynamic_page_counter.'"'.$active_class.'>'.$pagination_counter.'</a></li>'; 
                }
                ?>
                <?php if($page_number < $number_of_paginated_pages - 2 && $number_of_paginated_pages > $greater_than) { ?>
                    <li> ... <a href="<?php echo $data_array['pages_url'].'?page='.$number_of_paginated_pages.'&section='.$section; ?>"><?php echo $number_of_paginated_pages ?></a></li>
                <?php } ?>
                <?php if($page_number < $number_of_paginated_pages) { ?>
                    <li><a href="<?php echo $data_array['pages_url'].$next_pagination; ?>">Next <span><svg viewBox="0 0 512 512"><path d="M127 500A19 19 0 0 0 154 500L385 270A19 19 0 0 0 385 242L154 12A19 19 0 0 0 127 39L344 256 127 473A19 19 0 0 0 127 500"></path></svg></span></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
    <!-- End Pagination -->
    <?php if(!empty($data_array['sub_items'])) { ?>
        <!-- Start Sub Items -->
        <div class="pages-sub-items-style">
            <?php include('sub-items.php'); ?>
        </div>
        <!-- End Sub Items -->
    <?php } ?>
    <?php if(!empty($bottom_content)) { ?>
        <!-- Start Bottom Content -->
        <div class="bottom-content">
            <div class="bottom-content-wrap container-width"><?php echo $bottom_content; ?></div>
        </div>
        <!-- End Bottom Content -->
    <?php } ?>
    <?php if(isset($data_array['author_bio']['author_page_url']) && !empty($data_array['author_bio']['author_page_url'])) { ?>
    <!-- Stat Author Bio -->
    <div class="short-bio-wrapper padding-sides padding-bottom">
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
</main>
<?php include('footer.php'); ?>
<?php include('cookie-notice-banner.php'); ?>
<?php if($commerce_installed) { include('affiliate.php'); } ?>
<?php include('analytics.php'); ?>
</body>
</html>