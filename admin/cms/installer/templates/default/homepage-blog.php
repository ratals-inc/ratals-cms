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
<script nonce="<?php echo NONCE; ?>" type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "Corporation",
	"name": "<?php echo $site_name ?? ''; ?>",
	"url": "<?php echo $domain.INSTALLATION_URL_PATH.'/'; ?>",
	"logo": "<?php echo $logo_media_url ?? ''; ?>"<?php if($contact_info_display_contact_info == 'Yes') { ?>,
	"contactPoint": {
		"@type": "ContactPoint",
		"telephone": "<?php echo $contact_info_phone_number ?? ''; ?>",
		"contactType": "customer service",
		"areaServed": "<?php echo $contact_info_area_served ?? ''; ?>",
		"availableLanguage": "<?php echo $contact_info_available_language ?? ''; ?>"
	},
	"sameAs": [<?php echo $contact_info_other_business_urls ?? ''; ?>]<?php } ?>
}
</script>
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
<main>
	<?php include('slider.php'); ?>
    <!-- Start Blog -->
    <div class="blog">
        <div class="blog-homepage">
            <div class="blog-wrap container-width">
                <!-- Start Posts -->
                <div class="posts">
                    <?php if(!empty($data_array['content_title']) || !empty($data_array['top_content'])) { ?>
                    <div class="content-top">
                        <?php if(!empty($data_array['content_title'])) { ?><h1 class="title"><?php echo $content_title; ?></h1><?php } ?>
                        <?php include('author-and-dates-top.php'); ?>
                        <?php if(!empty($data_array['top_content'])) { ?><div class="top-content"><?php echo $top_content; ?></div><?php } ?>
                    </div>
                    <?php } ?>
                    <?php if(!empty($data_array['posts'])) { ?>
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
                                            <?php 
                                            if(isset($posts['media_data'][0]) && !empty($posts['media_data'][0])) 
                                            {
                                            ?>
                                                <div class="img"><a href="<?php echo $posts['final_url']; ?>"><?php echo $posts['media_data'][0]; ?></a></div>
                                            <?php } ?>
                                            <div class="text-post-archive">
                                                <div class="author-info">
                                                    <?php 
                                                    if(isset($posts['author_bio']['author_media']) && !empty($posts['author_bio']['author_media'])) 
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
                                                <div class="short-content">
                                                    <?php 
                                                    $top_content_strip_tags = strip_tags($posts['top_content']);
                                                    echo preg_replace('/mediaId(.*?);/', '', substr($top_content_strip_tags, 0, 300)); ?>
                                                    <?php if(strlen($top_content_strip_tags) > 299) { ?>
                                                    ... <a href="<?php echo $posts['final_url']; ?>">Keep Reading</a>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        </li>
                                        <!-- End Post Archive -->
                                    <?php 
                                    } 
                                    ?>
                                </ul>
                            </div>
                            <?php if(!empty($data_array['posts'])) { ?>
                                <div class="view-more-posts"><a href="<?php echo urlId([BLOG_PAGE]); ?>">View More Posts <i><svg viewBox="0 0 512 512"><path d="M127 500A19 19 0 0 0 154 500L385 270A19 19 0 0 0 385 242L154 12A19 19 0 0 0 127 39L344 256 127 473A19 19 0 0 0 127 500"></path></svg></i></a></div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- End Design Blocks -->
                    <?php } ?>
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