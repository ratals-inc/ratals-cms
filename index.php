<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', __DIR__);
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

require_once(INSTALLATION_ROOT.'/core/session-check-frontend.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/index.php'))
{
	include(INSTALLATION_ROOT.'/hooks/index.php');
}
else
{
	//Make sure server software allows .htaccess rules to run.
	require_once(INSTALLATION_ROOT.'/core/server-software.php');
	
	//Set no cache for when admin user logged in.
	header('Cache-Control: no-cache');
	
	//If site is in maintenance mode, serve a HTTP 503 and stop the site from loading. Display message that site is in maintenance.
	if($site_maintenance_mode == 'Yes')
	{
		header("HTTP/2 503 Service Unavailable");
		echo '<div class="under-maintenance">Our site is currently under maintenance. Please come back shortly.</div>';
		die;
	}
	
	//Start - Pages that should never be cached. If the user clears these values in "admin > settings > site settings", these pages will be automatically added from here to prevent caching.
	$path_urls_to_never_cache = array('account', 'addresses', 'account/addresses', 'add-address', 'account/addresses/add-address', 'edit-address', 'account/addresses/edit-address', 'affiliate', 'account/affiliate', 'cards-on-file', 'account/cards-on-file', 'add-card', 'account/cards-on-file/add-card', 'edit-card', 'account/cards-on-file/edit-card', 'invoice', 'account/invoice', 'license-keys', 'account/license-keys', 'orders', 'account/orders', 'order-details', 'account/orders/order-details', 'profile', 'account/profile', 'receipt', 'account/receipt', 'subscriptions', 'account/subscriptions', 'cancel-order', 'cart', 'checkout', 'order-confirmation', 'reset-password', 'robots');
	$pages_not_to_cache = str_replace(' ', '', $pages_not_to_cache ?? '');
	$pages_not_to_cache = trim($pages_not_to_cache ?? '', ',');
	if(!empty($pages_not_to_cache))
	{
		$path_urls_to_never_cache = array_merge($path_urls_to_never_cache, explode(',', $pages_not_to_cache));
	}
	
	//If installed in a subdirectory, make sure the cache check accounts for it too.
	$cache_path_url = $path_url;
	if(!empty(INSTALLATION_URL_PATH))
	{
		$installation_path_url = trim(INSTALLATION_URL_PATH, '/');
	
		if(strpos($cache_path_url, $installation_path_url.'/') === 0)
		{
			$cache_path_url = substr($cache_path_url, strlen($installation_path_url) + 1);
		}
		elseif($cache_path_url == $installation_path_url)
		{
			$cache_path_url = '';
		}
	}
	
	//Start - check if cached page exist and if page should be cached.
	if(!isset($_SESSION['user_id']) && $load_pages_with_cached_results == 'Yes' && empty($_POST) && !in_array($path_url, $path_urls_to_never_cache) && !in_array($cache_path_url, $path_urls_to_never_cache) && strpos($url, '?') === false && strpos($url, '#') === false)
	{
		if(!empty($path_url))
		{
			$cache_file_path = trim($path_url, '/');
		}
		else
		{
			$cache_file_path = 'index';
		}
		
		//Set cache file path to see if it exist to load it.
		$cached_file_path = INSTALLATION_ROOT.'/storage/cache/'.$site_id.'/'.$active_template_path.'/'.$cache_file_path.'.php';
		
		if(empty($seconds_between_cache_refreshing))
		{
			$seconds_between_cache_refreshing = 14400;
		}
	
		//Load page from static cache file if it exist and has not expired.
		if(file_exists($cached_file_path) && time() - $seconds_between_cache_refreshing < filemtime($cached_file_path))
		{
			include INSTALLATION_ROOT."/admin/cms/frontend/redirects.php"; //This checks for redirects for the current URL being requested.
			include INSTALLATION_ROOT."/admin/cms/frontend/pages-data.php"; //This gets the data for the page being requested.
			include INSTALLATION_ROOT."/admin/cms/frontend/final-url.php"; //This creates the final URL that the page should be loading on.
			include INSTALLATION_ROOT."/admin/cms/frontend/redirect-to-final-url.php"; //This redirects the url to the correct one if the final url is different from what is being requested.
			if($commerce_installed)
			{
				include INSTALLATION_ROOT."/admin/commerce/frontend/affiliate.php"; //This sets the cookie for an affiliate id if source=affailite_id is set in the url.
				include INSTALLATION_ROOT."/admin/commerce/frontend/cart-cookie.php"; //This checks to see if visitor has a cart cookie set. if not, set one.
				include INSTALLATION_ROOT."/admin/commerce/frontend/update-sub-products-data.php"; //This updates sub_products/collection products so prices load correct in categories.
			}
			include INSTALLATION_ROOT."/admin/cms/frontend/analytics.php"; //This runs analytics data.
			
			include($cached_file_path);
			
			//Start - display sql queries.
			if($_SESSION['display_sql_queries'] == 'Yes' && !empty($_SESSION['all_queries']))
			{
				rsort($_SESSION['all_queries']);
				$sql_time = 0.00000000;
				foreach($_SESSION['all_queries'] as $all_queries)
				{
					$sql_time = $sql_time + $all_queries['MS_Time'];
				}
				
				echo 'Time to load all MySQL queries: '.$sql_time.' ms';
				echo '<pre>'; print_r($_SESSION['all_queries']); echo '</pre>';
			}
			//End - display sql queries.
			
			exit();
		}
		
		//Start output buffer.
		ob_start();
	}
	//End - check if cached page exist and if page should be cached.
	
	//$sites happens in /config.php
	if(!empty($sites))
	{
		//If URL was not cached above, then run all queries the template needs and cache the page if cache enabled.
		include INSTALLATION_ROOT."/admin/cms/frontend/redirects.php"; //This checks for redirects for the current URL being requested.
		include INSTALLATION_ROOT."/admin/cms/frontend/pages-data.php"; //This gets the data for the page being requested.
		include INSTALLATION_ROOT."/admin/cms/frontend/final-url.php"; //This creates the final URL that the page should be loading on.
		include INSTALLATION_ROOT."/admin/cms/frontend/redirect-to-final-url.php"; //This redirects the url to the correct one if the final url is different from what is being requested.
		if($commerce_installed)
		{
			include INSTALLATION_ROOT."/admin/commerce/frontend/logout-customer.php"; //This will logout a customer out if you inactivated or delete their account while they are logged in.
			include INSTALLATION_ROOT."/admin/commerce/frontend/cart-cookie.php"; //This checks to see if visitor has a cart cookie set. if not, set one.
			include INSTALLATION_ROOT."/admin/commerce/frontend/affiliate.php"; //This sets the cookie for an affiliate id if source=affailite_id is set in the url.
			include INSTALLATION_ROOT."/admin/commerce/frontend/ship-to-address.php"; //This checks to see if visitor is logged in or not. If logged in, get account shipping address, if not, get shipping address based on cookie id set.
			include INSTALLATION_ROOT."/admin/commerce/frontend/update-sub-products-data.php"; //This updates sub_products/collection products so prices load correct in categories.
		}
		include INSTALLATION_ROOT."/admin/cms/frontend/blocking-spam.php"; //This gets blocking spam settings.
		include INSTALLATION_ROOT."/admin/cms/frontend/meta-data.php"; //This gets the pages meta data like title tag, description, etc.
		include INSTALLATION_ROOT."/admin/cms/frontend/hreflang.php"; //If multiple pages are set with same url id for hreflang_url_id in urls table, get hreflang link markup.
		include INSTALLATION_ROOT."/admin/cms/frontend/template-files.php"; //This gets the template file data that is set on the page. This tells which code to run in load-page.php that is below.
		include INSTALLATION_ROOT."/admin/cms/frontend/load-page.php"; //This creates the page array so a page can load.
		include INSTALLATION_ROOT."/admin/cms/frontend/breadcrumbs.php"; //This creates the breadcrumbs that load within pages
		include INSTALLATION_ROOT."/admin/cms/frontend/site-search.php"; //This creates site search results when a visitor does a search.
		include INSTALLATION_ROOT."/admin/cms/frontend/forms.php"; //This creates forms where a user can enter there information for contact us, request a quote, etc.
		include INSTALLATION_ROOT."/admin/cms/frontend/analytics.php"; //This runs analytics data.
		include INSTALLATION_ROOT."/admin/cms/frontend/no-record-404.php"; //This loads the 404 page or no record found message if load-sites/pages-data.php cannot find a URL record in the database.
		include INSTALLATION_ROOT."/admin/cms/frontend/load-template-file.php"; //This loads the correct template file into the URL so the page loads as desired.
		
		//Start - if page is not cached, cache it.
		if(!isset($_SESSION['user_id']) && $load_pages_with_cached_results == 'Yes' && empty($_POST) && !in_array($path_url, $path_urls_to_never_cache) && !in_array($cache_path_url, $path_urls_to_never_cache) && $pages_data['page_not_found_404'] != 'Yes' && isset($cache_file_path) && !empty($cache_file_path) && strpos($url, '?') === false && strpos($url, '#') === false)
		{
			//Create cache directory structure if it doesn't exist for requested url.
			$cache_file_path_directory_levels = '';
			
			if(strpos($cache_file_path, '/') !== false)
			{
				$cache_file_path_array = explode('/', $cache_file_path);
			}
			else
			{
				$cache_file_path_array[] = $cache_file_path;
			}
			
			$cache_file_path_array = array_merge(array('storage'), array('cache'), array($site_id), array($active_template_path), $cache_file_path_array);
			
			//Drop the file name from the URL being requested as its not a directory to be created.
			array_pop($cache_file_path_array);
			
			foreach($cache_file_path_array as $cache_file_path_directory)
			{
				$cache_file_path_directory_levels .= '/'.$cache_file_path_directory;
				if(!is_dir(INSTALLATION_ROOT.$cache_file_path_directory_levels)) { mkdir(INSTALLATION_ROOT.$cache_file_path_directory_levels, 0755, true); }
			}
			
			//Save the page data to a file for cache.
			$cached_file = fopen($cached_file_path, 'w');
			
			//Get content of the page loading.
			$html_of_loaded_page = ob_get_contents();
			
			//START SWAP - SWAP HTML IN LOADING PAGE TO PHP/JS CODE THAT HAS TO RENDERS ON EVERY PAGE LOAD.
			//This ensures that PHP and JS code are preserved within the cached HTML page, allowing it to retrieve data from the database while still using the cached content.
			
			//Update the cookie id number so it gets a current avaliable cookie id.
			if(isset($cart_cookie) && !empty($cart_cookie))
			{
				$search_for_cookie_id = "#document.cookie = 'cart=".$cart_cookie."(.*?)SameSite=Lax';#s";  
				$replace_with_cookie_id = "document.cookie = 'cart=<?php echo \$cart_cookie; ?>;expires='+now.toUTCString()+';path=/;SameSite=Lax';";
				$html_of_loaded_page = preg_replace($search_for_cookie_id, $replace_with_cookie_id, $html_of_loaded_page);
			}
			
			//Remove header link to edit page in admin when logged into admin.
			$search_for_edit_admin_page = "#<!-- Start Edit Admin Page -->(.*?)<!-- End Edit Admin Page -->#s";
			$replace_with_edit_admin_page = "<!-- Start Edit Admin Page -->
			<?php if(isset(\$_SESSION['user_id'])) { echo '<div class=\"edit-page\"><a href=\"'.INSTALLATION_URL_PATH.'/'.\$_SESSION['admin_directory'].'/website/".$pages_data['table_name']."/edit/?rid=".$id."\" target=\"_blank\">Edit in Admin</a></div>'; } ?>
			<!-- End Edit Admin Page -->";
			$html_of_loaded_page = preg_replace($search_for_edit_admin_page, $replace_with_edit_admin_page, $html_of_loaded_page);
			
			//Update the header so its still dynamic to update cart qty and customer logged in data/name.
			$search_for_header = "#<!-- Start Header -->(.*?)<!-- End Header -->#s";
			$replace_with_header_include = "<?php \$data_array['active_template_includes'][] = 'header.php'; \$data_array['active_template_includes'][] = 'header-search-bar.php'; \$data_array['active_template_includes'][] = 'header-customer-account-menu.php'; \$data_array['active_template_includes'][] = 'header-cart-quantity-button.php'; include('sites/".$site_id."/templates/".$active_template_path."/header.php'); ?>";
			$html_of_loaded_page = preg_replace($search_for_header, $replace_with_header_include, $html_of_loaded_page);
			
			//Update Cart ID: NUMBER with correct number.
			$search_for_cart_id = "#<!-- Start Footer Cart ID -->(.*?)<!-- End Footer Cart ID -->#s";
			$replace_with_cart_id = "<!-- Start Footer Cart ID -->
			<?php if(isset(\$sql_get_cart_id_row['id']) && !empty(\$sql_get_cart_id_row['id'])) { ?><div class=\"cart-id\">Cart ID: <?php echo \$sql_get_cart_id_row['id']; ?></div><?php } ?>
			<!-- End Footer Cart ID -->";
			$html_of_loaded_page = preg_replace($search_for_cart_id, $replace_with_cart_id, $html_of_loaded_page);
			
			//Update the cookie notice banner so its still dynamic for all page loads.
			$search_for_cookie_notice_banner = "#<!-- Start Cookie Notice Banner -->(.*?)<!-- End Cookie Notice Banner -->#s";
			$replace_with_cookie_notice_banner_include = "<?php \$data_array['active_template_includes'][] = 'cookie-notice-banner.php'; include('sites/".$site_id."/templates/".$active_template_path."/cookie-notice-banner.php'); ?>";
			$html_of_loaded_page = preg_replace($search_for_cookie_notice_banner, $replace_with_cookie_notice_banner_include, $html_of_loaded_page);
			
			//Update the analytics cookie so its still dynamic for all page loads.
			$search_for_analytics_cookie = "#<!-- Start Analytics -->(.*?)<!-- End Analytics -->#s";
			$replace_with_analytics_cookie_include = "<?php \$data_array['active_template_includes'][] = 'analytics.php'; include('sites/".$site_id."/templates/".$active_template_path."/analytics.php'); ?>";
			$html_of_loaded_page = preg_replace($search_for_analytics_cookie, $replace_with_analytics_cookie_include, $html_of_loaded_page);
			
			//Update the affiliate cookie so its still dynamic for all page loads.
			$search_for_affiliate_cookie = "#<!-- Start Source -->(.*?)<!-- End Source -->#s";
			$replace_with_affiliate_cookie_include = "<?php \$data_array['active_template_includes'][] = 'affiliate.php'; include('sites/".$site_id."/templates/".$active_template_path."/affiliate.php'); ?>";
			$html_of_loaded_page = preg_replace($search_for_affiliate_cookie, $replace_with_affiliate_cookie_include, $html_of_loaded_page);
			
			//Update the header customer cart control so its still dynamic for all page loads.
			$search_for_customer_cart_control = "#<!-- Start Header Cart Control -->(.*?)<!-- End Header Cart Control -->#s";
			$replace_with_customer_cart_control_include = "<?php \$data_array['active_template_includes'][] = 'header-customer-account-control.php'; include('sites/".$site_id."/templates/".$active_template_path."/header-customer-account-control.php'); ?>";
			$html_of_loaded_page = preg_replace($search_for_customer_cart_control, $replace_with_customer_cart_control_include, $html_of_loaded_page);
			
			//Update xml to render correct.
			$html_of_loaded_page = str_ireplace('<?xml version="1.0" encoding="utf-8"?>', '<?php header("Content-type: text/xml"); header("Content-Security-Policy: style-src \'self\' \'unsafe-inline\';"); echo \'<?xml version="1.0" encoding="utf-8"?>\'; ?>', $html_of_loaded_page);
			$html_of_loaded_page = str_ireplace('<?xml version="1.1" encoding="utf-8"?>', '<?php header("Content-type: text/xml"); header("Content-Security-Policy: style-src \'self\' \'unsafe-inline\';"); echo \'<?xml version="1.1" encoding="utf-8"?>\'; ?>', $html_of_loaded_page);
			
			//Update nonce to render correct for CSP/Content Security Policy.
			$search_for_nonce = "#nonce=\"(.*?)\"#s";
			$replace_with_nonce = 'nonce="<?php echo NONCE; ?>"';
			$html_of_loaded_page = preg_replace($search_for_nonce, $replace_with_nonce, $html_of_loaded_page);
			
			//END SWAP - SWAP HTML IN LOADING PAGE TO PHP/JS CODE THAT HAS TO RENDERS ON EVERY PAGE LOAD.
			
			fwrite($cached_file, $html_of_loaded_page);
			fclose($cached_file);
			
			//Send output to browser.
			ob_end_flush();
		}
		//End - if page is not cached, cache it.
		
		//Start - display sql queries.
		//This will display all MySQL queries for the frontend of the site. To do so, go to /config.php and set $_SESSION['display_sql_queries'] = 'Yes'. To stop displaying them set $_SESSION['display_sql_queries'] = 'No'. You should not use this in a live environment if you would be concerned about visitors seeing them. Once set to Yes, refresh a page on the frontend of the site and scroll to the bottom to see all of the queries that were used to load that page.
		if($_SESSION['display_sql_queries'] == 'Yes' && !empty($_SESSION['all_queries']))
		{
			rsort($_SESSION['all_queries']);
			$sql_time = 0.00000000;
			foreach($_SESSION['all_queries'] as $all_queries)
			{
				$sql_time = $sql_time + $all_queries['MS_Time'];
			}
			
			echo 'Time to load all MySQL queries: '.$sql_time.' ms';
			echo '<pre>'; print_r($_SESSION['all_queries']); echo '</pre>';
		}
		//End - display sql queries.
	}
	
	unset($_SESSION['order_number']);
}

//clear database connection.
$pdo = null;
$pdo_schema = null;
$sql = null;
$stmt = null;
die(); 