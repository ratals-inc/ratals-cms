<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//To display errors on pages, uncomment the four lines below.
//error_reporting(E_ALL);
//ini_set('ignore_repeated_errors', TRUE);
//ini_set('display_errors', TRUE);
//ini_set('log_errors', TRUE);

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/core/config.php'))
{
	include($_SERVER['DOCUMENT_ROOT'].'/hooks/core/config.php');
}
else
{
	//Prevent direct access to config.php.
	if(basename($_SERVER['PHP_SELF']) === basename(__FILE__))
	{
		http_response_code(403);
		exit('Access denied.');
	}
	
	//PHP analytics_hash_secret for this install. When Installed '[ SET_HASH_SECRET ]' is updated to your unique Hash Secret.
	$hash_secret = '[SET_HASH_SECRET]';
	//hash user IP Address for privacy.
	$masked_ip_hash = hash_hmac('sha256', $_SERVER['REMOTE_ADDR'], $hash_secret);
	
	$current_software_version = '1.01';
	
	//Check to make sure /core/database/DbCredentials.php file exist from setting up an account.
	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/core/database/DbCredentials.php'))
	{
		//Check to make sure an account was setup by looking at the database name in /core/database/DbCredentials.php. If name is [DATABASE_NAME], this means an account has not been setup yet and load create account page.
		$database_connection_file = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/core/database/DbCredentials.php');
		if(strpos($database_connection_file, '[DATABASE_NAME]') !== false)
		{
			//Database has not been installed yet so display create account form.
			header("HTTP/1.1 404"); 
			include_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/index.php');
			die();
		}
	}
	else
	{
		//Database has not been installed yet so display create account form.
		header("HTTP/1.1 404"); 
		include_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/index.php');
		die();
	}
	
	//Get all classes
	//If the software cannot connect to the database, /core/database/DbCredentials.php will show the error meassage of "Database connection error".
	require_once "database/index.php";
	
	//Set $_SESSION['display_sql_queries'] = 'Yes' to see all sql queries used to load any page within the site.
	$_SESSION['display_sql_queries'] = 'No';
	$_SESSION['all_queries'] = array();
	function all_sql_queries($query_start, $query_end, $sql_queries, $parameters, $line_number, $file_path)
	{
		$all_sql_queries['MS_Time'] = number_format((float)$query_end - $query_start, 8, '.', '') * 1000;
		$all_sql_queries['Query'] = preg_replace('/\s+/S', " ", $sql_queries);
		$all_sql_queries['Query_Parameters'] = $parameters;
		$all_sql_queries['Line'] = $line_number;
		$all_sql_queries['File'] = $file_path;
		
		$_SESSION['all_queries'][] = $all_sql_queries;
		return $_SESSION['all_queries'];
	}
	
	//Check to make sure the database table of sites is installed before trying to load the site. If its not installed, display the the create account page.
	$existing_database_tables = $results_schema->getSchemaSelectMultipleRecordsOneColumn(__LINE__, __FILE__, 'TABLE_NAME', 'tables', 'WHERE `table_schema` = ?', [$_SESSION['site_db_name']], 'TABLE_NAME');
	if(!in_array('sites', $existing_database_tables))
	{
		//Database has not been installed yet so display create account form.
		header("HTTP/1.1 404"); 
		include_once($_SERVER['DOCUMENT_ROOT'].'/admin/cms/installer/index.php');
		die();
	}
	
	//Set level of install based on existing tables.
	$ai_installed = in_array('ai', $existing_database_tables);
	$_SESSION['ai_exist'] = $ai_installed;
	
	//purchase_orders_inventory is the last table installed for erp so use it to make sure erp is installed.
	$erp_installed = in_array('purchase_orders_inventory', $existing_database_tables);
	$_SESSION['erp_exist'] = $erp_installed;
	
	//vendors is the last table installed for commerce so use it to make sure commerce is installed.
	$commerce_installed = in_array('vendors', $existing_database_tables);
	$_SESSION['commerce_installed'] = $commerce_installed;
	
	//Get license
	$license = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'license', 'WHERE `site_id` = ? AND `license_status` = ? LIMIT 1', [0, 'Active']);
	$current_license_type = $license['license_type'];
	
	$level = 1;
	if(isset($license) && !empty($license))
	{
		if(strtolower($license['license_type']) == 'commerce')
		{
			$level = 2;
		}
		elseif(strtolower($license['license_type']) == 'erp')
		{
			$level = 3;
		}
		elseif(strtolower($license['license_type']) == 'ai')
		{
			$level = 4;
		}
	}
	
	//Make sure site id is assigned to user before allowing access to site.
	if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) && isset($_POST["change_site"])) 
	{
		$sql_all_sites_in_account_security = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'sites', 'WHERE `id` = ?', [$_POST["change_site"]]);
		
		if(!empty($sql_all_sites_in_account_security)) 
		{
			if(empty($_SESSION['user_site_permissions_id']) || (!empty($_SESSION['user_site_permissions_id']) && strpos($_SESSION['user_site_permissions_id'], ','.$_POST["change_site"].',') !== false))
			{
				$_SESSION["site_set_for_editing"] = $_POST["change_site"]; 
				header("Location: ".$_SERVER['REQUEST_URI']); 
				exit;
			}
		}
	}
	
	$admin_fields_urls_id_array = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `column_name` = ?', ['urls_id']);
	if(!empty($admin_fields_urls_id_array))
	{
		$admin_fields_urls_id = $admin_fields_urls_id_array['id'];
	}
	
	//If URL is encoded, redirect to the decoded URL.
	//$_GET['search'] is used within site searches. This cleans up search URLs when it is used.
	//$_GET['filter'] are used on categories. This cleans up category URLs when filters are used.
	if($url != urldecode($url) && !isset($_SESSION['flag_redirect_once']) && (isset($_GET['search']) || isset($_GET['filter'])))
	{
		header("Location: ".urldecode($url)); 
		$_SESSION['flag_redirect_once'] = '';
		exit();
	}
	else
	{
		//If set becuase it had to redirect to decoded url, unset when the page reloads so it does not get caught in a redirect loop.
		unset($_SESSION['flag_redirect_once']);
	}
	
	//Get all sites.
	include $_SERVER['DOCUMENT_ROOT']."/admin/cms/frontend/site.php";
	
	if(empty($license['last_seen']) || strtotime($license['last_seen']) < strtotime('-7 days')) 
	{
		//Connect to API messages.
		if(file_exists(rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/admin/cms/api/connect.php'))
		{
			require(rtrim($_SERVER['DOCUMENT_ROOT'], '/').'/admin/cms/api/connect.php');
		}
	}
	
	//Create Content Security Policy Nonce.
	//When an AJAX request includes the variable ajax=nonce, it indicates that a user is interacting with attributes on a product page. Since attributes don't trigger a full page reload and only initiate an AJAX request, we need to store the nonce value for subsequent AJAX requests. However, if the page is fully reloaded, the ajax=nonce variable will no longer be present, and the nonce will be reset as the page reloads, as described in the else statement below.
	if(((isset($_GET['add_to_cart']) && $_GET['add_to_cart'] == 'yes') || (isset($_GET['ajax']) && $_GET['ajax'] == 'nonce')) && isset($_SESSION['nonce_url_keys']) && array_key_exists($path_url, $_SESSION['nonce_url_keys'])
	)
	{
		define('NONCE', $_SESSION['nonce_url_keys'][$path_url]);
		$_SESSION['nonce_token'] = $_SESSION['nonce_url_keys'][$path_url];
	}
	else
	{
		//Set nonce for a regular page load.
		try
		{
			//Preferred: php cryptographically secure.
			$random_nonce = bin2hex(random_bytes(16)); //32 chars
		}
		catch(Exception $e)
		{
			//Fallback if php cryptographically secure fails.
			$random_nonce = '0123456789abcdefghijklmnopqrstuvwxyz';
			$random_nonce = substr(str_shuffle($random_nonce), 0, 32);
		}
		
		define('NONCE', $random_nonce);
		$_SESSION['nonce_token'] = $random_nonce;
		$_SESSION['nonce_url_keys'][$path_url] = $random_nonce;
	}
	
	//Get all admin field values so we can turn values into clean label names when needed.
	$all_admin_fields_values = $results->getSelectMultipleRecordsKeyNameOneColumn(__LINE__, __FILE__, '*', 'admin_fields_values', '', [], 'value', 'label');
	//unset empty keys so they are not used by accident.
	unset($all_admin_fields_values['']);
	unset($all_admin_fields_values[' ']);
	if(session_status() === PHP_SESSION_ACTIVE)
	{
		$_SESSION['all_admin_fields_values'] = $all_admin_fields_values;
	}
	
	//For customer login area to make sure its a real request and not an external / cross-site requests
	include $_SERVER['DOCUMENT_ROOT']."/admin/cms/functions/csrf-form-submit-token.php"; //This sets Cross-Site Request Forgery form submit tokens.
	csrfFormSubmitToken();
	
	//Load site if found in db
	if(!empty($sites))
	{
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/modules.php'; //This gets modules that are enabled or not.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/currency.php'; //This makes the site load with the set currency type.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/date-time-format.php'; //This formats dates.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/admin-field-lists-swap.php'; //This gets Admin Field Lists. It swaps the values in a sub field. Example: Change states in states field when country is changed.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/assignments-insert-record.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/change-active.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/custom-fields.php'; //This gets all custom fields and values and adds them to the pages array.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/device-viewport.php'; //This gets the device type viewport window size.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/media.php'; //This gets Media - multiple functions in this file.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/menus.php'; //This loads menus.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/nonce.php'; //This updates nonce="nonce" with the correct nonce number.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/page-by-url-id.php'; //This gets the page data by the url id.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/password-validation.php'; //This validates passwords when accounts are created or passwords are updated.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/sliders.php'; //This loads sliders.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/smtp-send-email.php'; //This function send out the site emails with SMTP.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/sub-items.php'; //This gets sub items or load assignments_sub_items tables.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/update-leads-from-junk-to-active.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/urls.php'; //This gets URLs - multiple functions in this file.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/currency-format.php'; //This formats prices / currencies.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/items-data.php'; //This gets the data for sub_items and products to load for things like prices, image, review score, URL, etc.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/functions/build-database-table-create-query.php';
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/site-settings.php'; //This gets site settings.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/contact-info.php'; //This get the site contact information.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/site-security.php'; //This gets site security settings.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/sql-injection-tracking.php'; //This watches every POST[] pushed into the site and will email if any have SQL terms that might be trying to do injection.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/ddos-tracking.php'; //This gets every pageview and saves for ddos tracking. Also blocks visitors once blocked ip is set.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/search-engines.php'; //This gets search engine settings.
		include $_SERVER['DOCUMENT_ROOT'].'/admin/cms/frontend/active-template.php'; //This gets the active template/theme folder name so it loads with correct template/theme.
		
		if($commerce_installed && is_dir($_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions'))
		{
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/assign-all-inventory-to-category.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/assign-inventory-to-product.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/assign-products-to-category.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/assign-sub-products.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-clear-checkout-data.php'; //Unset cart / checkout data.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-create-license-keys.php'; //Check if license key items are in the cart to set up keys for an inventory item
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-create-subscription.php'; //Check if recurring items are in the cart to set up a recurring charge / subscription
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-delete-cart-and-cart-items.php'; //Delete cart and cart items from database.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-email-order-confirmation.php'; //Send order confirmation email to customer.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-email-order-license-keys.php'; //Send license keys confirmation email to customer.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-generate-unique-order-string.php'; //Generate unique order number string.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-payment-methods.php'; //This gets the payment methods that can be used when checking out.
			if(in_array('customer_accounts', $existing_database_tables) && in_array('gateway_settings', $existing_database_tables))
			{
				checkoutPaymentMethods(); //Load this early as any forms that need to load countries setup for payments will need this.
			}
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-shipping-carriers.php'; //This gets the shipping carriers that can be used when checking out.
			if(in_array('shipping_carriers', $existing_database_tables))
			{
				checkoutShippingCarriers(); //Load this early as any forms that need to load countries setup for carriers will need this.
			}
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-with-card-helper.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-with-card.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-with-check-by-mail.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-with-paypal.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/checkout-submit-order.php'; //Submit order.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/enable-disable-product-or-inventory.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/get-sub-product-ids.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/main-product-inventory-status.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/product-inventory-status-cateogry-level.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/save-category-order.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/save-products-inventory-order.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/sub-products-status.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/unassign-inventory-attached-to-product.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/unassign-sub-products.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/functions/update-assignment-tables.php';
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/frontend/accounting-settings.php'; //This gets accounting settings.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/commerce/frontend/subscriptions-settings.php'; //This gets the subscription setting so recurring charges can get the default settings.
		}
		
		if($erp_installed && is_dir($_SERVER['DOCUMENT_ROOT'].'/admin/erp/functions'))
		{
			include $_SERVER['DOCUMENT_ROOT'].'/admin/erp/functions/chart-of-accounts.php'; //This created the dropdown for Chart of Accounts in admin.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/erp/functions/checkout-submit-journal-sales-entries.php'; //Submit accounting journal entries for order.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/erp/functions/get-inventory-quantities.php'; //This get inventory quantities like available_posted, available_unposted, on_hand, on_order, allocated, and allocated_on_order.
			include $_SERVER['DOCUMENT_ROOT'].'/admin/erp/functions/set-new-landed-cost.php'; //Update landed cost when PO is posted or inventory is purchased or returned.
		}
	}
	
	if(file_exists($_SERVER['DOCUMENT_ROOT'].'/sites/'.$site_id.'/templates/'.$active_template_path.'/content-security-policy.php'))
	{
		include_once $_SERVER['DOCUMENT_ROOT'].'/sites/'.$site_id.'/templates/'.$active_template_path.'/content-security-policy.php';
	}
}