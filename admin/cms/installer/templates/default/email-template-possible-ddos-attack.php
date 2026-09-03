<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$warp_with_email_template = 'Yes';

if($auto_blocked_ip_email_me == 'Email Me')
{
	$subject = "Possible DDOS Attack - Visitor Hit ".$max_pageviews_block. " Pageviews Within ".$time_period_block." Minutes";
	
	$message = '<p>The IP Address of "'.$_SERVER['REMOTE_ADDR'].'", visiting '.$site_name.', has hit '.$max_pageviews_block. ' Pageviews Within '.$time_period_block.' Minutes.</p>
	<p>Here are a few resources for checking if you should block this IP Address:</p>
	<p><a href="https://developers.google.com/static/crawling/ipranges/common-crawlers.json" target="_blank">Googlebot IP\'s</a><br><a href="https://developers.google.com/static/crawling/ipranges/special-crawlers.json" target="_blank">Google AdsBot IP\'s</a><br><a href="https://www.bing.com/toolbox/bingbot.json" target="_blank">Bing IP\'s</a>.</p>
	<p>You can also try to see who the IP Address belongs to by searching for "IP Whois Lookup" in a search engine.</p>
	<p>You can control what is being blocked here: <a href="'.$domain.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/security/site-security.php?id='.$site_id.'" target="_blank" style="word-break: break-all;">'.$domain.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/security/site-security.php?id='.$site_id.'</a></p>';
}
elseif($auto_blocked_ip_email_me == 'Email Me and Block IP')
{
	$subject = "Blocked IP Address - Possible DDOS Attack - Visitor Hit ".$max_pageviews_block. " Pageviews Within ".$time_period_block." Minutes";
	
	$message = '<p>The IP Address of "'.$_SERVER['REMOTE_ADDR'].'", visiting '.$site_name.', has hit '.$max_pageviews_block. ' Pageviews Within '.$time_period_block.' Minutes.</p>
	<p>You should make sure the IP Address that was blocked is not a search engine you want to be ranking in.</p>
	<p>Here are a few resources for checking:<br><br><a href="https://developers.google.com/static/crawling/ipranges/common-crawlers.json" target="_blank">Googlebot IP\'s</a><br><a href="https://developers.google.com/static/crawling/ipranges/special-crawlers.json" target="_blank">Google AdsBot IP\'s</a><br><a href="https://www.bing.com/toolbox/bingbot.json" target="_blank">Bing IP\'s</a>.</p>
	<p>You can also try to see who the IP Address belongs to by searching for "IP Whois Lookup" in a search engine.</p>
	<p>You can control what is being blocked here: <a href="'.$domain.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/security/site-security.php?id='.$site_id.'" target="_blank" style="word-break: break-all;">'.$domain.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/security/site-security.php?id='.$site_id.'</a></p>';
}
?>