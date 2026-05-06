<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('cookie-notice-banner.php', $data_array['active_template_includes']) && $display_cookie_notice == 'Yes') 
{
	//  START CAUTION!
	//  IF YOU MODIFY THE "<!-- Start Cookie Notice Banner -->" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED.
	echo '<!-- Start Cookie Notice Banner -->'; //END CAUTION!
	if(!isset($_COOKIE['cookie_notice'])) 
	{
	?>
		<script nonce="<?php echo NONCE; ?>">
        //Get cookie string and convert to js array to define cookie.
		var cookies = document.cookie
		.split(';')
		.map(cookie => cookie.split('='))
		.reduce((accumulator, [key, value]) =>
		({ ...accumulator, [key.trim()]: decodeURIComponent(value) }),
		{});
		
		//When visitor closes the cookie notice banner, set cookie so it doesn't display again.
		$(document).ready(function()
		{
			$(".closeCookieBanner").click(function()
			{
				$(".cookies-banner").hide();
				
				if(typeof cookies.cookie_notice != 'undefined')
				{
					//update expired time if cookie exist for one year.
					var now = new Date();
					var time = now.getTime();
					var expireTime = time + 31500000000;
					now.setTime(expireTime);
					document.cookie = 'cookie_notice=close;expires='+now.toUTCString()+';path=/;SameSite=Lax';
				}
				else
				{
					//Set cookie if does not exist for one year.
					var now = new Date();
					var time = now.getTime();
					var expireTime = time + 31500000000;
					now.setTime(expireTime);
					document.cookie = 'cookie_notice=close;expires='+now.toUTCString()+';path=/;SameSite=Lax';
				}
				
			});
		});
        </script>
        
        <div class="cookies-banner">
             <div class="container-width">
                <div class="cookies-banner-wrapper">
                    <div class="text">This site uses cookies to deliver its services and to analyze traffic. Learn more on our <a href="<?php echo urlId($cookie_notice_url_id); ?>">Cookie Policy</a> and <a href="<?php echo urlId($privacy_notice_url_id); ?>">Privacy Policy</a>. <div class="button"><button class="closeCookieBanner" data-click="">Ok, Got it</button></div></div>
                </div>
            </div>
        </div>
	<?php } ?>
    <?php 
	//  START CAUTION!
	//  IF YOU MODIFY THE "<!-- End Cookie Notice Banner -->" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED. ?>
    <!-- End Cookie Notice Banner --><?php //END CAUTION! ?>
<?php } ?>