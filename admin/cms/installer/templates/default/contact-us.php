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
<script nonce="<?php echo NONCE; ?>" type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "LocalBusiness",
	"name": "<?php echo $site_name ?? ''; ?>",
	"image": "<?php echo $logo_media_url ?? ''; ?>",
	"@id": "<?php echo $data_array['pages_url'] ?? ''; ?>",
	"url": "<?php echo $domain.'/'; ?>"<?php if($contact_info_display_contact_info == 'Yes') { ?>,
	<?php if(!empty($contact_info_phone_number)) { ?>"telephone": "<?php echo $contact_info_phone_number ?? ''; ?>",
	<?php } ?>
	"address": {
		"@type": "PostalAddress",
		"streetAddress": "<?php echo $contact_info_street_address ?? ''; ?>",
		"addressLocality": "<?php echo $contact_info_city ?? ''; ?>",
		"addressRegion": "<?php echo $contact_info_state ?? ''; ?>",
		"postalCode": "<?php echo $contact_info_postal_code ?? ''; ?>",
		"addressCountry": "<?php echo $contact_info_country ?? ''; ?>"},
	"geo": {
		"@type": "GeoCoordinates",
		"latitude": <?php echo $contact_info_latitude ?? ''; ?>,
		"longitude": <?php echo $contact_info_longitude ?? ''; ?>}
	<?php 
	if(!empty($contact_info_days_of_the_week_open))
	{ 
		$contact_info_days_of_the_week_open_exploded = explode(',', $contact_info_days_of_the_week_open);
		$last_item_in_days_array = end($contact_info_days_of_the_week_open_exploded);
	?>,
	"openingHoursSpecification": [<?php 
		foreach($contact_info_days_of_the_week_open_exploded as $days_of_the_week_open_array)
		{ 
			$days_of_the_week_open_data = explode('|', $days_of_the_week_open_array);?>{"@type": "OpeningHoursSpecification", "dayOfWeek": "<?php echo $days_of_the_week_open_data[0]; ?>", "opens": "<?php echo $days_of_the_week_open_data[1]; ?>", "closes": "<?php echo $days_of_the_week_open_data[2]; ?>"}<?php if(!empty($days_of_the_week_open_data[3]) && !empty($days_of_the_week_open_data[4])) { ?>, {"@type": "OpeningHoursSpecification", "dayOfWeek": "<?php echo $days_of_the_week_open_data[0]; ?>", "opens": "<?php echo $days_of_the_week_open_data[3]; ?>", "closes": "<?php echo $days_of_the_week_open_data[4]; ?>"}<?php } ?><?php if($days_of_the_week_open_array != $last_item_in_days_array) { ?>,<?php } ?><?php } ?>]
		<?php } ?>
	<?php } ?>
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
    <div class="pages-two-column contact-us">
        <div class="pages-two-column-wrap container-width">
            <div class="content">
                <?php if(!empty($content_title)) { ?><h1 class="title"><?php echo $content_title; ?></h1><?php } ?>
                <?php include('author-and-dates-top.php'); ?>
                <?php if(!empty($top_content)) { ?><?php echo $top_content; ?><?php } ?>
                <div class="send-email"><?php echo form_id(2); ?></div>
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
                <?php include('sidebar-contact-us.php'); ?>
                <!-- End Sidebar Box -->
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