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
    <!-- Start Two Column Layout -->
    <div class="pages-two-column">
        <div class="pages-two-column-wrap container-width">
            <div class="content">
                <?php if(!isset($_GET['update-admin-url']) && !empty($content_title)) { ?><h1 class="title"><?php echo $content_title; ?></h1><?php } ?>
                <?php include('author-and-dates-top.php'); ?>
                <?php 
                if(isset($_GET['update-admin-url']) && !empty($_GET['update-admin-url'])) 
                {
                    echo "<h2><strong>ATTENTION: ADMIN URL UPDATED</strong></h2>
					<p>You have successfully updated your admin URL. This message is visible to you because your current URL contains the 'update-admin-url' flag; <strong>regular visitors cannot see this.</strong></p>
					<p>Depending on your server type (Apache or Nginx), you may need to take an extra step for the new URL to become active.</p>
					<div>
						<strong>Step 1: For Apache or LiteSpeed Users (Most Shared Hosting)</strong>
						<p>Changes to <code>.htaccess</code> are usually instant, but server-level caching can sometimes cause a 30–60 second delay.</p>
						<ol>
							<li><strong>Wait 10–20 seconds</strong> and <strong>refresh</strong> this page.</li>
							<li>If it still fails, try opening the new URL in an <strong>Incognito/Private window</strong> to bypass your browser's cache.</li>
						</ol>
					</div>
					<div>
						<strong>Step 2: For Nginx Users (VPS or Dedicated Servers)</strong>
						<p>Nginx does not recognize <code>.htaccess</code> changes. You <strong>must</strong> manually update your server configuration file with the new path: <code>".htmlspecialchars($_GET['update-admin-url'])."</code>.</p>
						<ol>
							<li>Update your <code>server {}</code> block in your Nginx config file.</li>
							<li>Run the command: <code>sudo nginx -t && sudo systemctl reload nginx</code></li>
							<li>For detailed help, see the <strong><a href='https://www.ratals.com/tutorials/installation/setting-up-ratals-on-nginx/' target='_blank'>Nginx Configuration & Troubleshooting Guide</a></strong>.</li>
						</ol>
					</div>
					<p><strong>General Troubleshooting:</strong></p>
					<ul>
						<li>Verify that your <code>.htaccess</code> files (in the root and /admin/ folders) reflect the new path: <strong>".htmlspecialchars($_GET['update-admin-url'])."</strong>.</li>
						<li>If using <strong>Cloudflare</strong>, you may need to 'Purge Cache' in your Cloudflare dashboard.</li>
						<li>If the issue persists for more than 5 minutes on a managed host, contact support and ask them to <strong>clear the server-level web cache.</strong></li>
					</ul>"; 
                }
                elseif(!empty($top_content)) 
                { 
                ?>
                    <?php echo $top_content; ?>
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
        </div>
    </div>
    <!-- End Two Column Layout -->
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
</main>
<?php include('footer.php'); ?>
<?php include('cookie-notice-banner.php'); ?>
<?php if($commerce_installed) { include('affiliate.php'); } ?>
<?php include('analytics.php'); ?>
</body>
</html>