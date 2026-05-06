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
<?php if(!empty($canonical_url)) { ?>
<link rel="canonical" href="<?php echo $canonical_url; ?>">
<?php } ?>
<?php if(!empty($meta_robots)) { ?>
<meta name="robots" content="<?php echo $meta_robots; ?>">
<?php } ?>
<?php if(!empty($pages_data['hreflang'])) { echo $pages_data['hreflang'].'
'; } ?>
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
                <?php if(!empty($content_title)) { ?><h1 class="title"><?php echo $content_title; ?></h1><?php } ?>
                <?php include('author-and-dates-top.php'); ?>
                <?php if(!empty($top_content)) { ?><?php echo $top_content; ?><?php } ?>
            </div>
        </div>
    </div>
    <!-- End One Column Layout -->
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