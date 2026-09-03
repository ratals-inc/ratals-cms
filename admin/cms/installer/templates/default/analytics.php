<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('analytics.php', $data_array['active_template_includes'])) 
{
	//  START CAUTION!
	//  IF YOU MODIFY THE "<!-- Start Analytics -->" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED.
	echo '<!-- Start Analytics -->'; //END CAUTION! ?>
	<?php if(!isset($_COOKIE['analytics']) && $collect_analytics_data == 'Yes') { ?>
		<script nonce="<?php echo NONCE; ?>">
        //Set cookie if does not exist for one year.
        var now = new Date();
        var time = now.getTime();
        var expireTime = time + 31500000000;
        now.setTime(expireTime);
        document.cookie = 'analytics=<?php echo $_SESSION['analytics_cookie_id']; ?>;expires='+now.toUTCString()+';path=/;SameSite=Lax';
        </script>
    <?php } ?>
    <?php if($collect_analytics_data == 'Yes') { ?>
    <script nonce="<?php echo NONCE; ?>">
	//Analytics Event
	$(document).ready(function()
	{
		$(document).on('click', '.event', function()
		{
			var dataValues = $(this).attr('data');
			if(dataValues && dataValues.trim() !== '')
			{
				var dataArray = dataValues.split(',');
				
				var eventName = dataArray[0];
				var eventValue = dataArray[1];
				
				var post = 'event_name=' + eventName + '&event_value=' + eventValue; 
				$.post("<?php echo INSTALLATION_URL_PATH; ?>/sites/event.php", post, function(theResponse){ /*alert(theResponse);*/ });
			}
		});
	});
    </script>
    <?php } ?>
    <?php if(!empty($google_analytics_tag_id)) { ?>
        <!-- Google tag (gtag.js) -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo $google_analytics_tag_id; ?>" nonce="<?php echo NONCE; ?>"></script>
        <script nonce="<?php echo NONCE; ?>">
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            
            gtag('config', '<?php echo $google_analytics_tag_id; ?>');
        </script>
    <?php } ?>
    <?php if(!empty($microsoft_advertising_tag_id)) { ?>
		<script nonce="<?php echo NONCE; ?>">
        (function(w,d,t,r,u) {
            var f,n,i;
            w[u]=w[u]||[],f=function() {
              var o={ti:"<?php echo $microsoft_advertising_tag_id; ?>"};
              o.q=w[u],w[u]=new UET(o),w[u].push("pageLoad")
            },n=d.createElement(t),n.src=r,n.async=1,n.onload=n.onreadystatechange=function() {
              var s=this.readyState;
              s&&s!=="loaded"&&s!=="complete"||(f(),n.onload=n.onreadystatechange=null)
            },i=d.getElementsByTagName(t)[0],i.parentNode.insertBefore(n,i)
          })(window,document,"script","//bat.bing.com/bat.js","uetq");
        </script>
    <?php } ?>
	<?php 
	//  START CAUTION!
	//  IF YOU MODIFY THE "<!-- End Analytics -->" TAG BELOW, MAKE SURE TO UPDATE THE NEW TAG NAME IN /INDEX.PHP FILE TO PREVENT THE HEADER FROM BEING CACHED. ?>
    <!-- End Analytics --><?php //END CAUTION! ?>
<?php } ?>