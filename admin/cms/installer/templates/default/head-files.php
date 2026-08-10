<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('head-files.php', $data_array['active_template_includes'])) { ?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="preload" href="[FILE_PATH]stylesheet.css" as="style">
<link rel="stylesheet" href="[FILE_PATH]stylesheet.css">
<link rel="preload" href="/sites/libraries/jquery-3.7.1.min.js" as="script" nonce="<?php echo NONCE; ?>">
<script src="/sites/libraries/jquery-3.7.1.min.js" nonce="<?php echo NONCE; ?>"></script>
<script async src="[FILE_PATH]scripts.js" nonce="<?php echo NONCE; ?>"></script>
<?php if(!empty($favicon_16px_16px)) { ?><link rel="icon" type="image/png" sizes="16x16" href="<?php echo $favicon_16px_16px; ?>"><?php } ?>
<?php if(!empty($favicon_32px_32px)) { ?><link rel="icon" type="image/png" sizes="32x32" href="<?php echo $favicon_32px_32px; ?>"><?php } ?>
<?php if(!empty($favicon_180px_180px)) { ?><link rel="apple-touch-icon" type="image/png" sizes="180x180" href="<?php echo $favicon_180px_180px; ?>"><?php } ?>
<?php if(isset($cart_cookie) && !empty($cart_cookie)) { ?>
<script nonce="<?php echo NONCE; ?>">
//Get cookie string and convert to JS array.
var cookies = document.cookie
.split(';')
.map(cookie => cookie.split('='))
.reduce((accumulator, [key, value]) =>
({ ...accumulator, [key.trim()]: decodeURIComponent(value) }),
{});

var now = new Date();
var time = now.getTime();
var expireTime = time + 31500000000;
now.setTime(expireTime);

if(typeof cookies.cart != 'undefined')
{
	//Update expiration time for one year if the cookie already exists.
	document.cookie = 'cart='+cookies.cart+';expires='+now.toUTCString()+';path=/;SameSite=Lax';
}
else
{
	//Set cookie for one year if it does not exist.
	<?php 
	//  START CAUTION!
	//  IF YOU MODIFY THE "CART COOKIE" CODE BELOW, MAKE SURE TO UPDATE THE CODE IN /INDEX.PHP FILE TO PREVENT IT FROM BEING CACHED.
	?>
	document.cookie = 'cart=<?php echo $cart_cookie; ?>;expires='+now.toUTCString()+';path=/;SameSite=Lax';
	<?php //  END CAUTION! ?>
}
</script>
<?php } ?>
<?php } ?>
