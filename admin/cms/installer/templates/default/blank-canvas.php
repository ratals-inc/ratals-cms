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
	"@type": "Corporation",
	"name": "<?php echo $site_name ?? ''; ?>",
	"url": "<?php echo $domain.'/'; ?>",
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
	echo '<div class="edit-page"><a href="/'.$_SESSION['admin_directory'].'/website/'.$pages_data['table_name'].'/edit/?rid='.$id.'" target="_blank">Edit in Admin</a></div>';
}
?>
<!-- End Edit Admin Page --><?php //END CAUTION! ?>
<?php if(!empty($data_array['sub_items'])) { ?>
    <!-- Start Sub Items -->
    <div class="pages-sub-items-style">
        <?php include('sub-items.php'); ?>
    </div>
    <!-- End Sub Items -->
<?php } ?>
<?php include('cookie-notice-banner.php'); ?>
<?php if($commerce_installed) { include('affiliate.php'); } ?>
<?php include('analytics.php'); ?>
</body>
</html>