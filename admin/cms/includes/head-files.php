<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/head-files.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/head-files.php');
}
else
{
if(!isset($_SESSION['admin_directory'])) { $_SESSION['admin_directory'] = 'admin'; }?><meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow" />
<link rel="stylesheet" href="<?php echo INSTALLATION_URL_PATH; ?>/sites/libraries/jquery-ui-1.14.1/jquery-ui.css">
<link rel="stylesheet" href="<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/stylesheet.css">
<?php if(!empty($favicon_16px_16px)) { ?><link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favicon_16px_16px; ?>"><?php } ?>
<?php if(!empty($favicon_32px_32px)) { ?><link rel="icon" type="image/png" sizes="32x32" href="<?php echo $favicon_32px_32px; ?>"><?php } ?>
<?php if(!empty($favicon_180px_180px)) { ?><link rel="apple-touch-icon" type="image/png" sizes="180x180" href="<?php echo $favicon_180px_180px; ?>"><?php } ?>
<script src="<?php echo INSTALLATION_URL_PATH; ?>/sites/libraries/jquery-3.7.1.min.js" nonce="<?php echo NONCE; ?>"></script>
<script src="<?php echo INSTALLATION_URL_PATH; ?>/sites/libraries/jquery-ui-1.14.1/jquery-ui.js" nonce="<?php echo NONCE; ?>"></script>
<script nonce="<?php echo NONCE; ?>">
$(function(){ $(".navigation-toggle").click(function(){ $(".navigation").slideToggle(); }); });
$(function(){ $(".toggle-results").click(function(){ $(".results").slideToggle(); }); });
</script>
<?php } ?>