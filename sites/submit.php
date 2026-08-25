<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__));
}

require_once(INSTALLATION_ROOT.'/core/session-check-frontend.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/sites/submit.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/sites/submit.php');
}
else
{
	//Start Submit Review
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'product_page_submit_review')
	{
		if(!empty($_POST['one']) && !empty($_POST['two']) && !empty($_POST['name']) && !empty($_POST['state']) && !empty($_POST['score']) && !empty($_POST['review']))
		{
			$site_id = trim($_POST['one'] ?? '');
			$page_id = trim($_POST['two'] ?? '');
			$name = trim($_POST['name'] ?? '');
			$state = trim($_POST['state'] ?? '');
			$review_score = trim($_POST['score'] ?? '');
			$review = trim($_POST['review'] ?? '');
			
			//Start getting blocking spam settings
			$blocking_spam = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'blocking_spam', 'WHERE `site_id` = ? LIMIT 1', [$_SESSION['site_id']]);
			//End getting blocking spam settings
			
			$all_review_content = '';
			$all_review_content = $name.' '.$state.' '.$review_score.' '.$review;
			
			$has_blocked_keyword = '';
			if(!empty($blocking_spam["reviews_blocked_keywords"]))
			{
				$reviews_blocked_keywords_array = array_map('trim', explode(',', strtolower($blocking_spam["reviews_blocked_keywords"])));
				
				foreach($reviews_blocked_keywords_array as $keyword)
				{
					if(!empty($keyword) && strpos(strtolower($all_review_content), $keyword) !== false)
					{
						$has_blocked_keyword = 'Yes';
						break;
					}
				}
			}
			
			if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']))
			{
				$customer_id = $_SESSION['customer_id'];
			}
			elseif(!isset($_SESSION['customer_id']) && empty($_SESSION['customer_id']) && isset($_SESSION['cart_cookie_id']) && !empty($_SESSION['cart_cookie_id']))
			{
				//Check if cookie id is set and assigned to a customer account. This will save the review with the customer account id so they can see it when they login to their account.
				$customer_account_date = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? AND customer_account_id != ? LIMIT 1', [$_SESSION['cart_cookie_id'], '']);
				
				if(isset($customer_account_date['customer_account_id']) && !empty($customer_account_date['customer_account_id']))
				{
					$customer_id = $customer_account_date['customer_account_id'];
				}
				else
				{
					$customer_id = NULL;
				}
			}
			else
			{
				$customer_id = NULL;
			}
			
			if($blocking_spam["reviews_block_links"] == 'Yes')
			{
				if(empty($has_blocked_keyword) && strpos(trim(strtolower($all_review_content)), 'http') === false && strpos(trim(strtolower($all_review_content)), 'href') === false)
				{
					$column_names = '`site_id`, `product_url_id`, `status`, `score`, `review`, `customer_account_id`, `reviews_order_id`, `name`, `state`, `created_date`, `approved_by`, `approved_date`';
					$placeholders = '?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,NULL';
					$parameters = array($_SESSION['site_id'], $page_id, '2', $review_score, $review, $customer_id, NULL, $name, $state, '');
					
					$results->getInsertRecord(__LINE__, __FILE__, 'reviews', $column_names, $placeholders, $parameters);
				}
			}
			else
			{
				if(empty($has_blocked_keyword))
				{
					$column_names = '`site_id`, `product_url_id`, `status`, `score`, `review`, `customer_account_id`, `reviews_order_id`, `name`, `state`, `created_date`, `approved_by`, `approved_date`';
					$placeholders = '?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,NULL';
					$parameters = array($_SESSION['site_id'], $page_id, '2', $review_score, $review, $customer_id, NULL, $name, $state, '');
					
					$results->getInsertRecord(__LINE__, __FILE__, 'reviews', $column_names, $placeholders, $parameters);
				}
			}
			
			echo '1';
			exit;
		}
		
		echo '2';
		exit;
	}
	//End Submit Review
	
	//Start Submit  Q & A
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'product_page_submit_question')
	{
		if(!empty($_POST['one']) && !empty($_POST['two']) && !empty($_POST['name']) && !empty($_POST['state']) && !empty($_POST['email']) && strpos($_POST['email'], '@') !== false && strpos($_POST['email'], '.') !== false && !empty($_POST['question']))
		{
			$site_id = trim($_POST['one'] ?? '');
			$page_id = trim($_POST['two'] ?? '');
			$name = trim($_POST['name'] ?? '');
			$state = trim($_POST['state'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$question = trim($_POST['question'] ?? '');
			
			//Start getting blocking spam settings
			$blocking_spam = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'blocking_spam', 'WHERE `site_id` = ? LIMIT 1', [$_SESSION['site_id']]);
			//End getting blocking spam settings
			
			$all_question_content = '';
			$all_question_content = $name.' '.$state.' '.$email.' '.$question;
			
			$has_blocked_keyword = '';
			if(!empty($blocking_spam["q_and_a_blocked_keywords"]))
			{
				$q_and_a_blocked_keywords_array = array_map('trim', explode(',', strtolower($blocking_spam["q_and_a_blocked_keywords"])));
				
				foreach($q_and_a_blocked_keywords_array as $keyword)
				{
					if(!empty($keyword) && strpos(strtolower($all_question_content), $keyword) !== false)
					{
						$has_blocked_keyword = 'Yes';
						break;
					}
				}
			}
			
			if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']))
			{
				$customer_id = $_SESSION['customer_id'];
			}
			elseif(!isset($_SESSION['customer_id']) && empty($_SESSION['customer_id']) && isset($_SESSION['cart_cookie_id']) && !empty($_SESSION['cart_cookie_id']))
			{
				//Check if cookie id is set and assigned to a customer account. This will save the review with the customer account id so they can see it when they login to their account.
				$customer_account_date = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? AND customer_account_id != ? LIMIT 1', [$_SESSION['cart_cookie_id'], '']);
				
				if(isset($customer_account_date['customer_account_id']) && !empty($customer_account_date['customer_account_id']))
				{
					$customer_id = $customer_account_date['customer_account_id'];
				}
				else
				{
					$customer_id = NULL;
				}
			}
			else
			{
				$customer_id = NULL;
			}
			
			if($blocking_spam["q_and_a_block_links"] == 'Yes')
			{
				if(empty($has_blocked_keyword) && strpos(trim(strtolower($all_question_content)), 'http') === false && strpos(trim(strtolower($all_question_content)), 'href') === false)
				{
					$column_names = '`site_id`, `product_url_id`, `customer_account_id`, `status`, `name`, `state`, `email`, `question`, `answer`, `sales_message`, `email_answer`, `email_sent`, `created_date`, `answered_by`, `answered_date`';
					$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,NULL';
					$parameters = array($_SESSION['site_id'], $page_id, $customer_id, '2', $name, $state, $email, $question, '', '', '', 'No', '');
					
					$results->getInsertRecord(__LINE__, __FILE__, 'q_and_a', $column_names, $placeholders, $parameters);
				}
			}
			else
			{
				if(empty($has_blocked_keyword))
				{
					$column_names = '`site_id`, `product_url_id`, `customer_account_id`, `status`, `name`, `state`, `email`, `question`, `answer`, `sales_message`, `email_answer`,  `email_sent`, `created_date`, `answered_by`, `answered_date`';
					$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,NULL';
					$parameters = array($_SESSION['site_id'], $page_id, $customer_id, '2', $name, $state, $email, $question, '', '', '', 'No', '');
					
					$results->getInsertRecord(__LINE__, __FILE__, 'q_and_a', $column_names, $placeholders, $parameters);
				}
			}
			
			echo '1';
			exit;
		}
		
		echo '2';
		exit;
	}
	//End Submit Q & A
	
	//Start Submit Comment on Post Page
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'post_page_submit_comment' && isset($_SESSION['allow_comments']) && $_SESSION['allow_comments'] == 'Yes')
	{ 
		if(!empty($_POST['one']) && !empty($_POST['two']) && isset($_POST['three']) && !empty($_POST['name']) && !empty($_POST['email']) && strpos($_POST['email'], '@') !== false && strpos($_POST['email'], '.') !== false && !empty($_POST['comment']))
		{
			$site_id = trim($_POST['one'] ?? '');
			$pages_id = trim($_POST['two'] ?? '');
			if($_POST['three'] == 0)
			{
				$comment_parent_id = NULL;
			}
			else
			{
				$comment_parent_id = $_POST['three'];
			}
			$name = trim($_POST['name'] ?? '');
			$email = trim($_POST['email'] ?? '');
			$comment = trim($_POST['comment'] ?? '');
			
			//Start getting blocking spam settings
			$blocking_spam = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'blocking_spam', 'WHERE `site_id` = ? LIMIT 1', [$_SESSION['site_id']]);
			//End getting blocking spam settings
			
			$all_comment_content = '';
			$all_comment_content = $name.' '.$email.' '.$comment;
			
			$has_blocked_keyword = '';
			if(!empty($blocking_spam["comments_blocked_keywords"]))
			{
				$comments_blocked_keywords_array = array_map('trim', explode(',', strtolower($blocking_spam["comments_blocked_keywords"])));
				
				foreach($comments_blocked_keywords_array as $keyword)
				{
					if(!empty($keyword) && strpos(strtolower($all_comment_content), $keyword) !== false)
					{
						$has_blocked_keyword = 'Yes';
						break;
					}
				}
			}
			
			if($blocking_spam["comments_block_links"] == 'Yes')
			{
				if(empty($has_blocked_keyword) && strpos(trim(strtolower($all_comment_content)), 'http') === false && strpos(trim(strtolower($all_comment_content)), 'href') === false)
				{
					$column_names = '`site_id`, `post_url_id`, `status`, `name`, `email`, `comment`, `comment_parent_id`, `created_date`, `created_by`, `approved_date`, `approved_by`';
					$placeholders = '?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,NULL,?';
					$parameters = array($_SESSION['site_id'], $pages_id, '2', $name, $email, $comment, $comment_parent_id, $name, '');
					
					$results->getInsertRecord(__LINE__, __FILE__, 'comments', $column_names, $placeholders, $parameters);
				}
			}
			else
			{
				if(empty($has_blocked_keyword))
				{
					$column_names = '`site_id`, `post_url_id`, `status`, `name`, `email`, `comment`, `comment_parent_id`, `created_date`, `created_by`, `approved_date`, `approved_by`';
					$placeholders = '?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,NULL,?';
					$parameters = array($_SESSION['site_id'], $pages_id, '2', $name, $email, $comment, $comment_parent_id, $name, '');
					
					$results->getInsertRecord(__LINE__, __FILE__, 'comments', $column_names, $placeholders, $parameters);
				}
			}
			
			echo '1';
			exit;
		}
		
		echo '2';
		exit;
	}
	//End Submit Comment on Post Page
	
	//Start Remove Cart Item
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'remove_cart_item')
	{
		if(isset($_POST['cart_item_id']) && !empty($_POST['cart_item_id']))
		{
			$cart_item_id = trim($_POST['cart_item_id'] ?? '');
			
				if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']))
				{
					//If customer is logged in, find the customers cart with $_SESSION['customer_id'].
					$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `customer_account_id` = ? LIMIT 1', [$_SESSION['customer_id']]);
					
					if(!empty($sql_get_cart_id_row))
					{
						$results->getDeleteRecord(__LINE__, __FILE__,'cart_items', 'WHERE `id` = ? AND `cart_id` = ?', [$cart_item_id,  $sql_get_cart_id_row['id']]);
						$results->getDeleteRecord(__LINE__, __FILE__,'cart_items_custom_fields', 'WHERE `cart_id` = ? AND `cart_items_id` = ?', [$sql_get_cart_id_row['id'], $cart_item_id]);
					}
				}
				elseif(isset($_SESSION['cart_cookie_id']) && !empty($_SESSION['cart_cookie_id']))
				{
					//If customer is not logged in, find the customers cart with $_SESSION['cart_cookie_id'].
					$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? LIMIT 1', [$_SESSION['cart_cookie_id']]);
					
					if(!empty($sql_get_cart_id_row))
					{
						$results->getDeleteRecord(__LINE__, __FILE__,'cart_items', 'WHERE `id` = ? AND `cart_id` = ?', [$cart_item_id,  $sql_get_cart_id_row['id']]);
						$results->getDeleteRecord(__LINE__, __FILE__,'cart_items_custom_fields', 'WHERE `cart_id` = ? AND `cart_items_id` = ?', [$sql_get_cart_id_row['id'], $cart_item_id]);
					}
				}
		}
		
		exit;
	}
	//End Remove Cart Item
	
	//Start Save For Later Cart Item
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'save_for_later_cart_item')
	{
		if(isset($_POST['cart_item_id']) && !empty($_POST['cart_item_id']))
		{
			$cart_item_id = trim($_POST['cart_item_id'] ?? '');
			
			//If customer is logged in, find the customers cart with $_SESSION['customer_id'].
			if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']))
			{
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `customer_account_id` = ? LIMIT 1', [$_SESSION['customer_id']]);
				
				if(!empty($sql_get_cart_id_row))
				{
					$results->getUpdateRecord(__LINE__, __FILE__, 'cart_items', '`save_for_later` = ?', 'WHERE `id` = ? AND `cart_id` = ?', ['Yes', $cart_item_id, $sql_get_cart_id_row['id']]);
				}
			}
			elseif(isset($_SESSION['cart_cookie_id']) && !empty($_SESSION['cart_cookie_id']))
			{
				//If customer is not logged in, find the customers cart with $_SESSION['cart_cookie_id'].
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? LIMIT 1', [$_SESSION['cart_cookie_id']]);
				
				if(!empty($sql_get_cart_id_row))
				{
					$results->getUpdateRecord(__LINE__, __FILE__, 'cart_items', '`save_for_later` = ?', 'WHERE `id` = ? AND `cart_id` = ?', ['Yes', $cart_item_id, $sql_get_cart_id_row['id']]);
				}
			}

		}
		
		exit;
	}
	//End Save For Later Cart Item
	
	//Start Update Cart Item QTY
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'change_cart_item_qty')
	{
		if(!empty($_POST['one']) && !empty($_POST['two']))
		{
			$cart_item_id = trim($_POST['one'] ?? '');
			$cart_item_qty = trim($_POST['two'] ?? '');
			
			//If customer is logged in, use $_SESSION['customer_id'] to get cart id.
			if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']))
			{
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `customer_account_id` = ? LIMIT 1', [$_SESSION['customer_id']]);
			}
			else
			{
				//If customer is not logged in, use $_SESSION['cart_cookie_id'] to get cart id.
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? LIMIT 1', [$_SESSION['cart_cookie_id']]);
			}
			
			if(!empty($sql_get_cart_id_row))
			{
				$sql_get_cart_item_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart_items', 'WHERE `id` = ? AND `cart_id` = ? LIMIT 1', [$cart_item_id, $sql_get_cart_id_row['id']]);
				
				if(!empty($sql_get_cart_item_row))
				{
					$sql_get_inventory_item_data_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'inventory', 'WHERE `id` = ? LIMIT 1', [$sql_get_cart_item_row['inventory_id']]);
					
					$total_instock = 0;
					$has_dropship_warehouse = 'No';
					
					if(isset($sql_get_inventory_item_data_rows['shipping_centers']) && !empty($sql_get_inventory_item_data_rows['shipping_centers']))
					{
						$shipping_warehouse_data = explode(',', $sql_get_inventory_item_data_rows['shipping_centers']);
						$warehouse_and_qty_array = array();
						
						if(!empty($sql_get_inventory_item_data_rows['shipping_centers_available']) && strpos($sql_get_inventory_item_data_rows['shipping_centers_available'], ',') !== false)
						{
							$shipping_warehouse_ids = explode(',', $sql_get_inventory_item_data_rows['shipping_centers_available']);
							
							foreach($shipping_warehouse_ids as $shipping_warehouse_id)
							{
								$shipping_warehouse_ids_and_qty = explode('=', $shipping_warehouse_id);
								
								if(in_array($shipping_warehouse_ids_and_qty[0], $shipping_warehouse_data))
								{
									$warehouse_and_qty_array[$shipping_warehouse_ids_and_qty[0]] = $shipping_warehouse_ids_and_qty[1];
								}
							}
						}
						elseif(!empty($sql_get_inventory_item_data_rows['shipping_centers_available']))
						{
							$shipping_warehouse_ids_and_qty = explode('=', $sql_get_inventory_item_data_rows['shipping_centers_available']);
							
							if(in_array($shipping_warehouse_ids_and_qty[0], $shipping_warehouse_data))
							{
								$warehouse_and_qty_array[$shipping_warehouse_ids_and_qty[0]] = $shipping_warehouse_ids_and_qty[1];
							}
						}
						
						$total_instock = array_sum($warehouse_and_qty_array);
						$has_dropship_warehouse = 'No';
						
						foreach($shipping_warehouse_data as $shipping_warehouse_data_item)
						{
							$sql_get_shipping_center_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'shipping_centers', 'WHERE `id` = ? AND `status` = ? LIMIT 1', [$shipping_warehouse_data_item, '1']);
							
							if($sql_get_shipping_center_rows['track_inventory'] == 'No')
							{
								$has_dropship_warehouse = 'Yes';
								break;
							}
						}
					}
					
					if($cart_item_qty > $_SESSION['cart_line_item_max_qty'])
					{
						$results->getUpdateRecord(__LINE__, __FILE__, 'cart_items', '`qty` = ?', 'WHERE `id` = ? AND `cart_id` = ?', [$_SESSION['cart_line_item_max_qty'], $cart_item_id, $sql_get_cart_id_row['id']]);
						
						$_SESSION['left_'.$cart_item_id] = '<script nonce="'.NONCE.'">setTimeout(function() {$(".left-'.$cart_item_id.'").fadeOut("slow"); }, 5000);</script><span class="left-'.$cart_item_id.'" class="font-color-margin-font">Max quantity is '.$_SESSION['cart_line_item_max_qty'].'. Give us a call if you need more.</span>';
					}
					elseif($total_instock >= $cart_item_qty || $sql_get_inventory_item_data_rows['allow_backorders'] == 'Yes' || $sql_get_inventory_item_data_rows['shipping_method'] == 'no_shipping_required' || $has_dropship_warehouse == 'Yes')
					{
						$results->getUpdateRecord(__LINE__, __FILE__, 'cart_items', '`qty` = ?', 'WHERE `id` = ? AND `cart_id` = ?', [$cart_item_qty, $cart_item_id, $sql_get_cart_id_row['id']]);
					}
					else
					{
						$results->getUpdateRecord(__LINE__, __FILE__, 'cart_items', '`qty` = ?', 'WHERE `id` = ? AND `cart_id` = ?', [$total_instock, $cart_item_id, $sql_get_cart_id_row['id']]);
						
						$_SESSION['left_'.$cart_item_id] = '<script nonce="'.NONCE.'">setTimeout(function() {$(".left-'.$cart_item_id.'").fadeOut("slow"); }, 3000);</script><span class="left-'.$cart_item_id.'" class="font-color-margin-font">Only '.$total_instock.' In Stock</span>';
					}
				}
			}
		}
		
		exit;
	}
	//End Update Cart Item QTY
	
	//Start Add Save For later Cart Item Back to Cart
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'add_save_for_later_cart_item')
	{ 
		if(isset($_POST['cart_item_id']) && !empty($_POST['cart_item_id']))
		{
			$cart_item_id = trim($_POST['cart_item_id'] ?? '');
			
			//If customer is logged in, find the customers cart with $_SESSION['customer_id'].
			if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']))
			{
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `customer_account_id` = ? LIMIT 1', [$_SESSION['customer_id']]);
				
				if(!empty($sql_get_cart_id_row))
				{
					$results->getUpdateRecord(__LINE__, __FILE__, 'cart_items', '`save_for_later` = ?', 'WHERE `id` = ? AND `cart_id` = ?', ['No', $cart_item_id, $sql_get_cart_id_row['id']]);
				}
			}
			//If customer is not logged in, find the customers cart with $_SESSION['cart_cookie_id'].
			elseif(isset($_SESSION['cart_cookie_id']) && !empty($_SESSION['cart_cookie_id']))
			{
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? LIMIT 1', [$_SESSION['cart_cookie_id']]);
				
				if(!empty($sql_get_cart_id_row))
				{
					$results->getUpdateRecord(__LINE__, __FILE__, 'cart_items', '`save_for_later` = ?', 'WHERE `id` = ? AND `cart_id` = ?', ['No', $cart_item_id, $sql_get_cart_id_row['id']]);
				}
			}
		}
		
		exit;
	}
	//End Add Save For later Cart Item Back to Cart
	
	//Start Delete Save For later Cart Item
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'remove_save_for_later_cart_item')
	{
		if(isset($_POST['cart_item_id']) && !empty($_POST['cart_item_id']))
		{
			$cart_item_id = trim($_POST['cart_item_id'] ?? '');
			
			//If customer is logged in, find the customers cart with $_SESSION['customer_id'].
			if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']))
			{
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `customer_account_id` = ? LIMIT 1', [$_SESSION['customer_id']]);
				
				if(!empty($sql_get_cart_id_row))
				{
					$results->getDeleteRecord(__LINE__, __FILE__, 'cart_items', 'WHERE `id` = ? AND `cart_id` = ?', [$cart_item_id, $sql_get_cart_id_row['id']]);
					$results->getDeleteRecord(__LINE__, __FILE__, 'cart_items_custom_fields', 'WHERE `cart_id` = ? AND `cart_items_id` = ?', [$sql_get_cart_id_row['id'], $cart_item_id]);
				}
			}
			//If customer is not logged in, find the customers cart with $_SESSION['cart_cookie_id'].
			elseif(isset($_SESSION['cart_cookie_id']) && !empty($_SESSION['cart_cookie_id']))
			{
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? LIMIT 1', [$_SESSION['cart_cookie_id']]);
				
				if(!empty($sql_get_cart_id_row))
				{
					$results->getDeleteRecord(__LINE__, __FILE__, 'cart_items', 'WHERE `id` = ? AND `cart_id` = ?', [$cart_item_id, $sql_get_cart_id_row['id']]);
					$results->getDeleteRecord(__LINE__, __FILE__, 'cart_items_custom_fields', 'WHERE `cart_id` = ? AND `cart_items_id` = ?', [$sql_get_cart_id_row['id'], $cart_item_id]);
				}
			}
		}
		
		exit;
	}
	//End Delete Save For later Cart Item
	
	//Start Submit Ship To Address When Customer Not Logged In
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'submit_ship_to_address')
	{
		if(!empty(trim($_POST['email'] ?? '')))
		{
			$customer_email_exist = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'customer_accounts', 'WHERE `email` = ? AND `site_id` = ?', [trim($_POST['email'] ?? ''), $_SESSION['site_id']]);
			
			if(!empty($customer_email_exist))
			{
				echo '3';
				exit;
			}
		}
		
		if(isset($_SESSION['cart_cookie_id']) && !empty($_SESSION['cart_cookie_id']) && !empty($_POST['first']) && !empty($_POST['last']) && !empty($_POST['email']) && !empty($_POST['addressOne']) && !empty($_POST['city']) && !empty($_POST['country']) && !empty($_POST['state']) && !empty($_POST['postalCode']) && !empty($_POST['phoneNumber']) && !empty($_POST['addressType']) && !empty($_POST['defaultBillingCountry']) && !empty($_POST['taxExempt']))
		{
			if(($_POST['addressType'] == 'Residential') || ($_POST['addressType'] == 'Business' && !empty($_POST['loadingDock'])))
			{
				$first_name = trim($_POST['first'] ?? '');
				$last_name = trim($_POST['last'] ?? '');
				$company_name = trim($_POST['company'] ?? '');
				$email_address = trim($_POST['email'] ?? '');
				$address_one = trim($_POST['addressOne'] ?? '');
				$address_two = trim($_POST['addressTwo'] ?? '');
				$city = trim($_POST['city'] ?? '');
				$country = trim($_POST['country'] ?? '');
				$state = trim($_POST['state'] ?? '');
				$postal_code = trim($_POST['postalCode'] ?? '');
				$phone_number = trim($_POST['phoneNumber'] ?? '');
				$phone_ext = trim($_POST['phoneExt'] ?? '');
				$address_type = trim($_POST['addressType'] ?? '');
				$loading_dock = trim($_POST['loadingDock'] ?? '');
				$default_billing_country = trim($_POST['defaultBillingCountry'] ?? '');
				$tax_exempt = trim($_POST['taxExempt'] ?? '');
				
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? LIMIT 1', [$_SESSION['cart_cookie_id']]);
				
				if(!empty($sql_get_cart_id_row))
				{
					try
					{
						//Preferred: php cryptographically secure.
						$recovery_email_token = bin2hex(random_bytes(32)); //64 chars
					}
					catch(Exception $e)
					{
						//Fallback if php cryptographically secure fails.
						$recovery_email_token = '0123456789abcdefghijklmnopqrstuvwxyz';
						$recovery_email_token = substr(str_shuffle($recovery_email_token), 0, 64);
					}
					
					$column_names = '`site_id`, `cart_id`, `first_name`, `last_name`, `company_name`, `email`, `street_address_1`, `street_address_2`, `city`, `country`, `state`, `postal_code`, `phone_number`, `phone_number_ext`, `address_type`, `loading_dock`, `default_billing_country`, `tax_exempt`, `cookie_id`, `referer_source`, `referer_url`, `recovery_emails_sent`, `recovery_email_token`, `updated_date`, `created_date`';
					$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),UTC_TIMESTAMP()';
					
					$parameters = array($_SESSION['site_id'], $sql_get_cart_id_row['id'], $first_name, $last_name, $company_name, $email_address, $address_one, $address_two, $city, $country, $state, $postal_code, $phone_number, $phone_ext, $address_type, $loading_dock, $default_billing_country, $tax_exempt, $_SESSION['cart_cookie_id'], $_SESSION['referer_domain'] ?? '', $_SESSION['referer_url'] ?? '', '0', $recovery_email_token);
					
					$results->getInsertRecord(__LINE__, __FILE__, 'abandonment_cart_leads', $column_names, $placeholders, $parameters);
					
					$_SESSION['abandonment_cart_lead'] = 'Yes';
					
					echo '1';
					exit;
				}
			}
			else
			{
				echo '2';
				exit;
			}
		}
		else
		{
			echo '2';
			exit;
		}
	}
	//End Submit Ship To Address When Customer Not Logged In
	
	//Start Submit Ship To Address When Customer Logged In And No Address In Account
	if(isset($_POST['type']) && !empty($_POST['type']) &&  $_POST['type'] == 'submit_ship_to_address_account' && isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']))
	{
		if(isset($_SESSION['site_id']) && !empty($_SESSION['site_id']) && !empty($_POST['first']) && !empty($_POST['last']) && !empty($_POST['email']) && !empty($_POST['addressOne']) && !empty($_POST['city']) && !empty($_POST['country']) && !empty($_POST['state']) && !empty($_POST['postalCode']) && !empty($_POST['phoneNumber']) && !empty($_POST['addressType']) && !empty($_POST['defaultBillingCountry']) && !empty($_POST['taxExempt']))
		{
			if(($_POST['addressType'] == 'Residential') || ($_POST['addressType'] == 'Business' && !empty($_POST['loadingDock'])))
			{
				$first_name = trim($_POST['first'] ?? '');
				$last_name = trim($_POST['last'] ?? '');
				$company_name = trim($_POST['company'] ?? '');
				$email_address = trim($_POST['email'] ?? '');
				$address_one = trim($_POST['addressOne'] ?? '');
				$address_two = trim($_POST['addressTwo'] ?? '');
				$city = trim($_POST['city'] ?? '');
				$country = trim($_POST['country'] ?? '');
				$state = trim($_POST['state'] ?? '');
				$postal_code = trim($_POST['postalCode'] ?? '');
				$phone_number = trim($_POST['phoneNumber'] ?? '');
				$phone_ext = trim($_POST['phoneExt'] ?? '');
				$address_type = trim($_POST['addressType'] ?? '');
				$loading_dock = trim($_POST['loadingDock'] ?? '');
				$default_billing_country = trim($_POST['defaultBillingCountry'] ?? '');
				$tax_exempt = trim($_POST['taxExempt'] ?? '');
				
				//Update default_billing_country on customer account profile
				$set_names = '`default_billing_country` = ?';
				$where_clause = 'WHERE `id` = ?';
				$parameters = array($default_billing_country, $_SESSION['customer_id']);
				
				$results->getUpdateRecord(__LINE__, __FILE__, 'customer_accounts', $set_names, $where_clause, $parameters);
				
				//Insert new added address
				$column_names = '`site_id`, `customer_accounts_id`, `first_name`, `last_name`, `company_name`, `email`, `street_address_1`, `street_address_2`, `city`, `country`, `state`, `postal_code`, `phone_number`, `phone_number_ext`,`address_type`, `loading_dock`, `tax_exempt`, `is_primary`, `created_date`, `updated_date`';
				$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),UTC_TIMESTAMP()';
				$parameters = array($_SESSION['site_id'], $_SESSION['customer_id'], $first_name, $last_name, $company_name, $email_address, $address_one, $address_two, $city, $country, $state, $postal_code, $phone_number, $phone_ext, $address_type, $loading_dock, $tax_exempt, 'Yes');
				
				$results->getInsertRecord(__LINE__, __FILE__, 'customer_addresses', $column_names, $placeholders, $parameters);
				
				$_SESSION['abandonment_cart_lead'] = 'Yes';
				$_SESSION['ship_to_address'] = array();
				unset($_SESSION['ship_to_address_flag']);
				
				echo '1';
				exit;
			}
			else
			{
				echo '2';
				exit;
			}
		}
		else
		{
			echo '2';
			exit;
		}
	}
	//End Submit Ship To Address When Customer Logged In And No Address In Account
	
	//Start Update Ship To Address When Customer Not Logged In
	if(isset($_POST['type']) && !empty($_POST['type']) && $_POST['type'] == 'change_ship_to_address')
	{
		if(!empty(trim($_POST['email'] ?? '')))
		{
			$customer_email_exist = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'customer_accounts', 'WHERE `email` = ? AND `site_id` = ?', [trim($_POST['email'] ?? ''), $_SESSION['site_id']]);
			
			if(!empty($customer_email_exist))
			{
				echo '3';
				exit;
			}
		}
		
		if(isset($_SESSION['cart_cookie_id']) && !empty($_SESSION['cart_cookie_id']) && !empty($_POST['ship_to_id']) && !empty($_POST['first']) && !empty($_POST['last']) && !empty($_POST['email']) && !empty($_POST['addressOne']) && !empty($_POST['city']) && !empty($_POST['country']) && !empty($_POST['state']) && !empty($_POST['postalCode']) && !empty($_POST['phoneNumber']) && !empty($_POST['addressType']) && !empty($_POST['defaultBillingCountry']) && !empty($_POST['taxExempt']))
		{
			if(($_POST['addressType'] == 'Residential') || ($_POST['addressType'] == 'Business' && !empty($_POST['loadingDock'])))
			{
				$cart_items_ship_to_id = trim($_POST['ship_to_id'] ?? '');
				$first_name = trim($_POST['first'] ?? '');
				$last_name = trim($_POST['last'] ?? '');
				$company_name = trim($_POST['company'] ?? '');
				$email_address = trim($_POST['email'] ?? '');
				$address_one = trim($_POST['addressOne'] ?? '');
				$address_two = trim($_POST['addressTwo'] ?? '');
				$city = trim($_POST['city'] ?? '');
				$country = trim($_POST['country'] ?? '');
				$state = trim($_POST['state'] ?? '');
				$postal_code = trim($_POST['postalCode'] ?? '');
				$phone_number = trim($_POST['phoneNumber'] ?? '');
				$phone_ext = trim($_POST['phoneExt'] ?? '');
				$address_type = trim($_POST['addressType'] ?? '');
				$loading_dock = trim($_POST['loadingDock'] ?? '');
				$default_billing_country = trim($_POST['defaultBillingCountry'] ?? '');
				$tax_exempt = trim($_POST['taxExempt'] ?? '');
				
				$sql_get_cart_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'cart', 'WHERE `cookie_id` = ? LIMIT 1', [$_SESSION['cart_cookie_id']]);
				
				if(!empty($sql_get_cart_id_row))
				{
					$set_names = '`first_name` = ?, `last_name` = ?, `company_name` = ?, `email` = ?, `street_address_1` = ?, `street_address_2` = ?, `city` = ?, `country` = ?, `state` = ?, `postal_code` = ?, `phone_number` = ?, `phone_number_ext` = ?, `address_type` = ?, `loading_dock` = ?, `default_billing_country` = ?, `tax_exempt` = ?, `updated_date` = UTC_TIMESTAMP()';
					$where_clause = 'WHERE `id` = ? AND `cart_id` = ?';
					$parameters = array($first_name, $last_name, $company_name, $email_address, $address_one, $address_two, $city, $country, $state, $postal_code, $phone_number, $phone_ext, $address_type, $loading_dock, $default_billing_country, $tax_exempt, $cart_items_ship_to_id, $sql_get_cart_id_row['id']);
					
					$results->getUpdateRecord(__LINE__, __FILE__, 'abandonment_cart_leads', $set_names, $where_clause, $parameters);
				}
				
				echo '1';
				exit;
			}
			else
			{
				echo '2';
				exit;
			}
		}
		else
		{
			echo '2';
			exit;
		}
	}
	//End Update Ship To Address When Customer Not Logged In
	
	//Start Change Shipping Delivery Address
	if(isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']) && isset($_POST['type']) && !empty($_POST['type']) && $_POST['type'] == 'change_shipping_delivery_address')
	{ 
		if(!empty($_POST['one']))
		{
			$new_ship_to_id = trim($_POST['one'] ?? '');
			
			$get_customer_accounts_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'customer_accounts', 'WHERE `id` = ? AND `site_id` = ?', [$_SESSION['customer_id'], $_SESSION['site_id']]);
			
			$get_ship_to_address_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'customer_addresses', 'WHERE `id` = ? AND `customer_accounts_id` = ? LIMIT 1', [$new_ship_to_id, $_SESSION['customer_id']]);
			
			if(!empty($get_customer_accounts_row) && !empty($get_ship_to_address_row))
			{
				$_SESSION['ship_to_address'] = array();
				$_SESSION['ship_to_address'] = array_merge($get_ship_to_address_row, array('default_billing_country' => $get_customer_accounts_row['default_billing_country']));
			}
		}
		
		exit;
	}
	//End Change Shipping Delivery Address
	
	//Start - set shipping rate value for carrier service selected
	if(isset($_POST['type']) && $_POST['type'] == 'change_ship_type')
	{
		if(!empty($_POST['one']) && !empty($_POST['carrier']))
		{
			$carrier = $_POST['carrier'];
			
			$_SESSION['shipping_rate_id'][$carrier] = $_POST['one'];
			include(INSTALLATION_ROOT.'/admin/commerce/frontend/shipping/calculate-rates.php');
		}
		
		exit;
	}
	//End - set shipping rate value for carrier service selected	
	
	//Start Calculate Shipping Rates
	if(isset($_POST['type']) && !empty($_POST['type']) && $_POST['type'] == 'get_shipping_rates')
	{ 
		include(INSTALLATION_ROOT.'/admin/commerce/frontend/shipping/calculate-rates.php');
		exit;
	}
	//End Calculate Shipping Rates
	
	//Start logout customer cart control that can be started in admin here: customers/abandonment-cart-leads.
	if(isset($_POST['type']) && !empty($_POST['type']) && $_POST['type'] == 'logoutCustomerCartControl')
	{
		unset($_SESSION['admin_admin_customer_id_control']);
		unset($_SESSION['admin_customer_cookie_control']);
		unset($_SESSION['customer_id']);
		unset($_SESSION['cart_cookie_id']);
		unset($_SESSION['customer_first_name']);
		unset($_SESSION['customer_last_name']);
		unset($_SESSION['gateway_profile_id']);
		unset($_SESSION['packages_to_submit']);
		unset($_SESSION['last_packages_to_submit']);
		unset($_SESSION['ship_to_address_flag']);

		exit;
	}
	//End logout customer cart control that can be started in admin here: customers/abandonment-cart-leads.
}