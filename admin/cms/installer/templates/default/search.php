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
	$search_results_page_number = '';
	if($search_results_number_of_paginated_pages > 1 && $page_number > 1)
	{
		$search_results_page_number = '?search='.$search_term.'&page='.$page_number;
	}
	else
	{
		$search_results_page_number = '?search='.$search_term;
	}
?>
<link rel="canonical" href="<?php echo $canonical_url.$search_results_page_number; ?>">
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
<nav class="results">
    <div class="results-wrap container-width">
        <div class="left">
            <div class="result-numbers"><?php echo $search_results_start_count; ?> - <?php echo $search_results_end_count; ?> of <?php echo count($search_results); ?> Results</div>
            <div class="result-breadcrumbs">
                <?php include('breadcrumbs.php'); ?>
            </div>
        </div>
    </div>
</nav>
<!-- End Breadcrumbs -->
<main>
    <!-- Start One Column Layout -->
    <div class="pages-one-column search">
        <div class="pages-one-column-wrap container-width">
            <div class="content">
                <?php if(!empty($content_title)) { ?><h1 class="title"><?php echo $content_title; ?></h1><?php } ?>
                <?php include('author-and-dates-top.php'); ?>
                <?php if(!empty($top_content)) { echo $top_content;  } ?>
            </div>
            <?php
            if(!empty($search_results))
            {
                echo '<div class="design-blocks design-blocks-bottom-padding padding-0px">
                <div class="design-blocks-wrap container-width">';
                echo '<ul class="grid-'.$grid_columns.'">';
                $lazy_load_item_counter = 0;
                $fetch_priority_high_counter = 0;
                $items_done = 0;
                
                //$lazy_load_media_row variable is set in load-sites/site-settings.php
                if($lazy_load_media_row == 1)
                {
                    $lazy_load_item_counter = $lazy_load_media_row;
                }
                if($lazy_load_media_row > 1)
                {
                    $lazy_load_item_counter = ($grid_columns * ($lazy_load_media_row - 1)) + 1;
                }
                
                if(empty($lazy_load_media_row) || $lazy_load_media_row == 0 || $lazy_load_media_row == 1)
                {
                    $fetch_priority_high_counter = ($grid_columns * 1);
                }
                elseif($lazy_load_media_row == 2)
                {
                    $fetch_priority_high_counter = ($grid_columns * 2);
                }
                elseif($lazy_load_media_row > 2)
                {
                    $fetch_priority_high_counter = ($grid_columns * 3);
                }
                
                foreach($search_results_with_offset as $search_result)
                {
                    $lazy_load_item = 'lazyLoadNo';
                    $items_done++;
                    
                    if($lazy_load_item_counter > 0)
                    {
                        if($items_done >= $lazy_load_item_counter)
                        {
                            $lazy_load_item = 'lazyLoadYes';
                        }
                    }
                    
                    $fetch_priority_sub_products = 'fetchPriorityAuto';
                    
                    if($fetch_priority_high_counter > 0)
                    {
                        if($items_done <= $fetch_priority_high_counter)
                        {
                            $fetch_priority_sub_products = 'fetchPriorityHigh';
                        }
                    }
                    
                    $search_link_and_media = '';
                    if(isset($search_result['media_data'][0]) && !empty($search_result['media_data'][0]))
                    {
                        $search_link_and_media = mediaId($search_result['media_data'][0]['id'], $lazy_load_item, $fetch_priority_sub_products, '', '');
                        $search_link_and_media = '<div class="img"><a href="'.$search_result["url_data"].'">'.$search_link_and_media.'</a></div>';
                    }
                
                    $search_meta_title = '';
                    if(isset($search_result["meta_title"]) && !empty($search_result["meta_title"]))
                    {
                        $search_meta_title = '<h2 class="title"><a href="'.$search_result["url_data"].'">'.$search_result["meta_title"].'</a></h2>';
                    }
                
                    if($search_result['table_name'] == 'products')
                    {
                        //If sub product, add starting at above price.
                        $starting_at = '';
                        if($search_result['product_type'] == 'Sub Products')
                        {
                            $starting_at = '<div class="class="font-size-12px"">Starting at</div>';
                        }
                    
                        //Format product price.
                        $price = '';
                        if(!empty($search_result["save"]))
                        {
                            $lowest_price = currencyFormatWithSymbol($search_result["price"]);
                            
                            $was_price = currencyFormatWithSymbol($search_result["products_price"]);
                            
                            $save = currencyFormatWithSymbol($search_result["save"]);
                            
                            $price = '<div class="prices">'.$starting_at.'<span class="price">'.$lowest_price.'</span><span class="old-price">Was: <span class="was-price">'.$was_price.'</span> / <span class="save">Save: '.$save.'</span></span></div>';
                        }
                        elseif(!empty($search_result["price"]))
                        {
                            $lowest_price = currencyFormatWithSymbol($search_result["price"]);
                            
                            $price = '<div class="prices">'.$starting_at.'<span class="price">'.$lowest_price.'</span></div>';
                        }
                    
                        //Get product review stars/score.
                        $search_review_score = '';
                        if(!empty($search_result["review_score"]))
                        {
                            $search_review_score = getReviewStars($search_result["review_score"]);
                            $search_review_score = '<div class="review-score">'.$search_review_score.'<span class="score">('.$search_result["review_score"].' out of 5)</span></div>';
                        }
                    
                        //Get inventory ships within.
                        $ships_within = '';
                        if(isset($search_result['inventory']['ships_within']) && !empty($search_result['inventory']['ships_within']))
                        {
                            if($search_result['inventory']['ships_within'] > 1)
                            {
                                $ships_within = '<div class="ships"><i class="ships-truck"><svg viewBox="0 0 512 512"><path d="M5 131A47 47 0 0 1 52 84H334A47 47 0 0 1 381 131V178H413A47 47 0 0 1 450 195L497 253A47 47 0 0 1 507 283V350A47 47 0 0 1 460 397H444A63 63 0 1 1 319 397H162A63 63 0 1 1 37 394 47 47 0 0 1 5 350ZM46 364A63 63 0 0 1 154 366H327A63 63 0 0 1 350 343V131A16 16 0 0 0 334 115H52A16 16 0 0 0 36 131V350A16 16 0 0 0 46 364M381 334A63 63 0 0 1 436 366H460A16 16 0 0 0 476 350V283A16 16 0 0 0 472 273L426 215A16 16 0 0 0 413 209H381ZM99 366A31 31 0 1 0 99 428 31 31 0 0 0 99 366M381 366A31 31 0 1 0 381 428 31 31 0 0 0 381 366"></path></svg></i>Ships Within '.$search_result['inventory']['ships_within'].' Days</div>';
                            }
                            elseif($search_result['inventory']['ships_within'] == 1)
                            {
                                $ships_within = '<div class="ships"><i class="ships-truck"><svg viewBox="0 0 512 512"><path d="M5 131A47 47 0 0 1 52 84H334A47 47 0 0 1 381 131V178H413A47 47 0 0 1 450 195L497 253A47 47 0 0 1 507 283V350A47 47 0 0 1 460 397H444A63 63 0 1 1 319 397H162A63 63 0 1 1 37 394 47 47 0 0 1 5 350ZM46 364A63 63 0 0 1 154 366H327A63 63 0 0 1 350 343V131A16 16 0 0 0 334 115H52A16 16 0 0 0 36 131V350A16 16 0 0 0 46 364M381 334A63 63 0 0 1 436 366H460A16 16 0 0 0 476 350V283A16 16 0 0 0 472 273L426 215A16 16 0 0 0 413 209H381ZM99 366A31 31 0 1 0 99 428 31 31 0 0 0 99 366M381 366A31 31 0 1 0 381 428 31 31 0 0 0 381 366"></path></svg></i>Ships Within 1 Day</div>';
                            } 
                        }
                    
                        echo '<!-- Start Product -->
                        <li>
                        '.$search_link_and_media.'
                        <div class="text-product">
                        '.$search_meta_title.'
                        '.$search_review_score.'
                        '.$price.'
                        '.$ships_within.'
                        </div>
                        </li>
                        <!-- End Product -->';
                    }
                    else
                    {
                        echo '<!-- Start Result -->
                        <li>
                        '.$search_link_and_media.'
                        <div class="text">
                        '.$search_meta_title.'
                        </div>
                        </li>
                        <!-- End Result -->';
                    }
                }
                echo '</ul>';
                echo '</div>
                </div>';
            }
            elseif(empty($search_results) && !empty($search_term))
            {
                echo '<!-- Start No Results -->
                <div class="no-results">No results. Please try a different search term.</div>
                <!-- End No Results -->';
            }
            elseif(empty($search_term) && isset($_GET['search']))
            {
                echo '<!-- Start Enter Search Term -->
                <div class="no-results">Please enter a search term.</div>
                <!-- End Enter Search Term -->';
            }
            ?>
        </div>
    </div>
    <!-- End One Column Layout -->
    <!-- Start Pagination -->
    <?php 
    if($search_results_number_of_paginated_pages > 1) 
    {
        if(!empty($page_number) && $page_number != 2) { $prev_pagination = '&page='.($page_number - 1); } else { $prev_pagination = ''; }
        if(!empty($page_number)) { $next_pagination = '&page='.($page_number + 1); } else { $next_pagination = '&page=2'; }
        
        //This controls how pagination works. If there are mroe than 5 pages start showing 1..., etc.
        $greater_than = 5;
    ?>
        <div class="pagination">
            <div class="pagination-wrap container-width">
                <ul>
                    <?php if($page_number > 1) { ?>
                    <li><a href="<?php echo $data_array['pages_url'].'?search='.$search_term.$prev_pagination; ?>"><span><svg viewBox="0 0 512 512"><path d="M385 12A19 19 0 0 0 358 12L127 242A19 19 0 0 0 127 270L358 500A19 19 0 0 0 385 473L168 256 385 39A19 19 0 0 0 385 12"></path></svg></span> Prev</a></li>
                    <?php } ?>
                    <?php if($page_number > 3 && $search_results_number_of_paginated_pages > $greater_than) { ?>
                    <li><a href="<?php echo $data_array['pages_url'].'?search='.$search_term; ?>">1</a> ... </li>
                    <?php } ?>
                    <?php 
                    if($page_number == 1 && $search_results_number_of_paginated_pages > $greater_than)
                    {
                        $number_of_paginated_pages_first = $page_number;
                        $number_of_paginated_pages_last = $page_number + 4;
                    }
                    elseif($page_number == 2 && $search_results_number_of_paginated_pages > $greater_than)
                    {
                        $number_of_paginated_pages_first = $page_number - 1;
                        $number_of_paginated_pages_last = $page_number + 3;
                    }
                    
                    elseif($page_number == $search_results_number_of_paginated_pages - 1 && $search_results_number_of_paginated_pages > $greater_than)
                    {
                        $number_of_paginated_pages_first = $search_results_number_of_paginated_pages - 4;
                        $number_of_paginated_pages_last = $page_number + 1;
                    }
                    elseif($page_number == $search_results_number_of_paginated_pages && $search_results_number_of_paginated_pages > $greater_than)
                    {
                        $number_of_paginated_pages_first = $search_results_number_of_paginated_pages - 5;
                        $number_of_paginated_pages_last = $page_number;
                    }
                    elseif($search_results_number_of_paginated_pages > 3 && $search_results_number_of_paginated_pages > $greater_than)
                    {
                        $number_of_paginated_pages_first = $page_number - 2;
                        $number_of_paginated_pages_last = $page_number + 2;
                    }
                    else
                    {
                        $number_of_paginated_pages_first = 1;
                        $number_of_paginated_pages_last = $search_results_number_of_paginated_pages;
                    }
                    
                    for($i = $number_of_paginated_pages_first; $i <= $number_of_paginated_pages_last; $i++) 
                    {
                        $pagination_counter = $i;  
                        $active_class = '';
                        if($pagination_counter == $page_number || ($pagination_counter == 1 && empty($page_number)))
                        {
                            $active_class = ' class="active"';
                        }
                        
                        if($pagination_counter > 1)
                        {
                            $dynamic_page_counter = '&page='.$pagination_counter;
                        }
                        else
                        {
                            $dynamic_page_counter = '';
                        }
                        echo '<li><a href="'.$data_array['pages_url'].'?search='.$search_term.$dynamic_page_counter.'"'.$active_class.'>'.$pagination_counter.'</a></li> '; 
                    }
                    ?>
                    <?php if($page_number < $search_results_number_of_paginated_pages - 2 && $search_results_number_of_paginated_pages > $greater_than) { ?>
                    <li> ... <a href="<?php echo $data_array['pages_url'].'?search='.$search_term.'&page='.$search_results_number_of_paginated_pages; ?>"><?php echo $search_results_number_of_paginated_pages ?></a></li>
                    <?php } ?>
                    <?php if($page_number < $search_results_number_of_paginated_pages) { ?>
                    <li><a href="<?php echo $data_array['pages_url'].'?search='.$search_term.$next_pagination; ?>">Next <span><svg viewBox="0 0 512 512"><path d="M127 500A19 19 0 0 0 154 500L385 270A19 19 0 0 0 385 242L154 12A19 19 0 0 0 127 39L344 256 127 473A19 19 0 0 0 127 500"></path></svg></span></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    <?php
    }
    ?>
    <!-- End Pagination -->
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