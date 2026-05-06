<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Get existing menu ids.
$existing_menu_ids = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'menus', 'WHERE `site_id` = ?', [$site_id], 'system_code');

//New menu ids
if(!isset($_SESSION['last_menu_id']))
{
	$_SESSION['last_menu_id'] = $last_menu_id + 1;
}
$_SESSION['install_ids'][$site_id]['header_menu_id'] = $existing_menu_ids['header']['id'] ?? $_SESSION['install_ids'][$site_id]['header_menu_id'] ?? $_SESSION['last_menu_id']++;
$_SESSION['install_ids'][$site_id]['footer_catetories_menu_id'] = $existing_menu_ids['footer_company']['id'] ?? $_SESSION['install_ids'][$site_id]['footer_catetories_menu_id'] ?? $_SESSION['last_menu_id']++;
$_SESSION['install_ids'][$site_id]['connect_on_social_menu_id'] = $existing_menu_ids['connect_on_social']['id'] ?? $_SESSION['install_ids'][$site_id]['connect_on_social_menu_id'] ?? $_SESSION['last_menu_id']++;
$_SESSION['install_ids'][$site_id]['footer_bottom_menu_id'] = $existing_menu_ids['footer_links']['id'] ?? $_SESSION['install_ids'][$site_id]['footer_bottom_menu_id'] ?? $_SESSION['last_menu_id']++;

//New slider ids
if(!isset($_SESSION['last_slider_id']))
{
	$_SESSION['last_slider_id'] = $last_slider_id + 1;
}
$_SESSION['install_ids'][$site_id]['slider_id'] = $_SESSION['install_ids'][$site_id]['slider_id'] ?? $_SESSION['last_slider_id']++;

//New custom field ids
if(!isset($_SESSION['last_custom_field_id']))
{
	$_SESSION['last_custom_field_id'] = $last_custom_field_id + 1;
}
$_SESSION['install_ids'][$site_id]['company_image'] = $_SESSION['install_ids'][$site_id]['company_image'] ?? $_SESSION['last_custom_field_id']++;
$_SESSION['install_ids'][$site_id]['company_title'] = $_SESSION['install_ids'][$site_id]['company_title'] ?? $_SESSION['last_custom_field_id']++;
$_SESSION['install_ids'][$site_id]['company_text'] = $_SESSION['install_ids'][$site_id]['company_text'] ?? $_SESSION['last_custom_field_id']++;

//Get existing page and URL ids.
$existing_page_url_ids = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'urls', 'WHERE `site_id` = ? AND `table_name` = ?', [$site_id, 'pages'], 'flat_url');

//New page ids
if(!isset($_SESSION['last_pages_id']))
{
	$_SESSION['last_pages_id'] = $last_pages_id + 1;
}
$_SESSION['install_ids'][$site_id]['homepage_id'] = $existing_page_url_ids['homepage']['record_id'] ?? $_SESSION['install_ids'][$site_id]['homepage_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['blog_page_id'] = $existing_page_url_ids['blog']['record_id'] ?? $_SESSION['install_ids'][$site_id]['blog_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['about_us_page_id'] = $existing_page_url_ids['about-us']['record_id'] ?? $_SESSION['install_ids'][$site_id]['about_us_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['contact_us_page_id'] = $existing_page_url_ids['contact-us']['record_id'] ?? $_SESSION['install_ids'][$site_id]['contact_us_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['site_search_page_id'] = $existing_page_url_ids['search']['record_id'] ?? $_SESSION['install_ids'][$site_id]['site_search_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['cookie_policy_page_id'] = $existing_page_url_ids['cookie-policy']['record_id'] ?? $_SESSION['install_ids'][$site_id]['cookie_policy_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['privacy_policy_page_id'] = $existing_page_url_ids['privacy-policy']['record_id'] ?? $_SESSION['install_ids'][$site_id]['privacy_policy_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['terms_of_use_page_id'] = $existing_page_url_ids['terms-of-use']['record_id'] ?? $_SESSION['install_ids'][$site_id]['terms_of_use_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['clients_page_id'] = $existing_page_url_ids['clients']['record_id'] ?? $_SESSION['install_ids'][$site_id]['clients_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['portfolio_page_id'] = $existing_page_url_ids['portfolio']['record_id'] ?? $_SESSION['install_ids'][$site_id]['portfolio_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['our_story_page_id'] = $existing_page_url_ids['our-story']['record_id'] ?? $_SESSION['install_ids'][$site_id]['our_story_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['faqs_page_id'] = $existing_page_url_ids['faqs']['record_id'] ?? $_SESSION['install_ids'][$site_id]['faqs_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['a404_page_id'] = $existing_page_url_ids['404']['record_id'] ?? $_SESSION['install_ids'][$site_id]['a404_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['message_confirmation_page_id'] = $existing_page_url_ids['message-confirmation']['record_id'] ?? $_SESSION['install_ids'][$site_id]['message_confirmation_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['quote_confirmation_page_id'] = $existing_page_url_ids['quote-confirmation']['record_id'] ?? $_SESSION['install_ids'][$site_id]['quote_confirmation_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['sitemap_html_page_id'] = $existing_page_url_ids['site-map']['record_id'] ?? $_SESSION['install_ids'][$site_id]['sitemap_html_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['sitemap_html_section_page_id'] = $existing_page_url_ids['site-map-section']['record_id'] ?? $_SESSION['install_ids'][$site_id]['sitemap_html_section_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['sitemap_xml_page_id'] = $existing_page_url_ids['sitemap']['record_id'] ?? $_SESSION['install_ids'][$site_id]['sitemap_xml_page_id'] ?? $_SESSION['last_pages_id']++;
$_SESSION['install_ids'][$site_id]['robots_page_id'] = $existing_page_url_ids['robots']['record_id'] ?? $_SESSION['install_ids'][$site_id]['robots_page_id'] ?? $_SESSION['last_pages_id']++;

//New url ids
if(!isset($_SESSION['last_url_id']))
{
	$_SESSION['last_url_id'] = $last_url_id + 1;
}
$_SESSION['install_ids'][$site_id]['homepage_url_id'] = $existing_page_url_ids['homepage']['id'] ?? $_SESSION['install_ids'][$site_id]['homepage_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['blog_page_url_id'] = $existing_page_url_ids['blog']['id'] ?? $_SESSION['install_ids'][$site_id]['blog_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['about_us_page_url_id'] = $existing_page_url_ids['about-us']['id'] ?? $_SESSION['install_ids'][$site_id]['about_us_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['contact_us_page_url_id'] = $existing_page_url_ids['contact-us']['id'] ?? $_SESSION['install_ids'][$site_id]['contact_us_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['site_search_page_url_id'] = $existing_page_url_ids['search']['id'] ?? $_SESSION['install_ids'][$site_id]['site_search_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['cookie_policy_page_url_id'] = $existing_page_url_ids['cookie-policy']['id'] ?? $_SESSION['install_ids'][$site_id]['cookie_policy_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['privacy_policy_page_url_id'] = $existing_page_url_ids['privacy-policy']['id'] ?? $_SESSION['install_ids'][$site_id]['privacy_policy_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['terms_of_use_page_url_id'] = $existing_page_url_ids['terms-of-use']['id'] ?? $_SESSION['install_ids'][$site_id]['terms_of_use_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['clients_page_url_id'] = $existing_page_url_ids['clients']['id'] ?? $_SESSION['install_ids'][$site_id]['clients_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['portfolio_page_url_id'] = $existing_page_url_ids['portfolio']['id'] ?? $_SESSION['install_ids'][$site_id]['portfolio_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['our_story_page_url_id'] = $existing_page_url_ids['our-story']['id'] ?? $_SESSION['install_ids'][$site_id]['our_story_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['faqs_page_url_id'] = $existing_page_url_ids['faqs']['id'] ?? $_SESSION['install_ids'][$site_id]['faqs_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['a404_page_url_id'] = $existing_page_url_ids['404']['id'] ?? $_SESSION['install_ids'][$site_id]['a404_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['message_confirmation_page_url_id'] = $existing_page_url_ids['message-confirmation']['id'] ?? $_SESSION['install_ids'][$site_id]['message_confirmation_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['quote_confirmation_page_url_id'] = $existing_page_url_ids['quote-confirmation']['id'] ?? $_SESSION['install_ids'][$site_id]['quote_confirmation_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['sitemap_html_page_url_id'] = $existing_page_url_ids['site-map']['id'] ?? $_SESSION['install_ids'][$site_id]['sitemap_html_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['sitemap_html_section_page_url_id'] = $existing_page_url_ids['site-map-section']['id'] ?? $_SESSION['install_ids'][$site_id]['sitemap_html_section_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['sitemap_xml_page_url_id'] = $existing_page_url_ids['sitemap']['id'] ?? $_SESSION['install_ids'][$site_id]['sitemap_xml_page_url_id'] ?? $_SESSION['last_url_id']++;
$_SESSION['install_ids'][$site_id]['robots_page_url_id'] = $existing_page_url_ids['robots']['id'] ?? $_SESSION['install_ids'][$site_id]['robots_page_url_id'] ?? $_SESSION['last_url_id']++;

if(!function_exists('createTemplateFile'))
{
	function createTemplateFile($template_file, $site_file)
	{
		global $template_to_install, $site_id;
		
		if(!empty($temp_extract_dir) && file_exists($temp_extract_dir."/admin/cms/installer/templates/".$template_to_install."/".$template_file))
		{
			//For updates.
			$text = file_get_contents($temp_extract_dir."/admin/cms/installer/templates/".$template_to_install."/".$template_file);
		}
		elseif(file_exists($_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/templates/".$template_to_install."/".$template_file))
		{
			//For new account creation.
			$text = file_get_contents($_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/templates/".$template_to_install."/".$template_file);
		}
		else
		{
			$text = "Could not find template file. Checked: ".$_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/templates/".$template_to_install."/".$template_file." and ".$_SERVER['DOCUMENT_ROOT']."/admin/temp_extract/admin/cms/installer/templates/".$template_to_install."/".$template_file;
		}
		
		//Update file path urls for site being created in templates while installing.
		$old_file_path = "[FILE_PATH]"; 
		$new_file_path = "/sites/".$site_id."/templates/".$template_to_install."/"; 
		$text = str_replace($old_file_path, $new_file_path, $text);
		
		//Update file image urls for site being created in templates while installing.
		$old_image_path = "[IMAGE_PATH]"; 
		$new_image_path = "/sites/media/images/"; 
		$text = str_replace($old_image_path, $new_image_path, $text);
		
		//Update menu ids to correct id in templates while installing.
		$old_header_menu = "[HEADER_MENU]"; 
		$new_header_menu = $_SESSION['install_ids'][$site_id]['header_menu_id']; 
		$text = str_replace($old_header_menu, $new_header_menu, $text);
		
		$old_footer_categories = "[FOOTER_CATEGORIES]"; 
		$new_footer_categories = $_SESSION['install_ids'][$site_id]['footer_catetories_menu_id'];
		$text = str_replace($old_footer_categories, $new_footer_categories, $text);
		
		$old_connect_on_social = "[CONNECT_ON_SOCIAL]"; 
		$new_connect_on_social = $_SESSION['install_ids'][$site_id]['connect_on_social_menu_id']; 
		$text = str_replace($old_connect_on_social, $new_connect_on_social, $text);
		
		$old_footer_bottom = "[FOOTER_BOTTOM]"; 
		$new_footer_bottom = $_SESSION['install_ids'][$site_id]['footer_bottom_menu_id'];
		$text = str_replace($old_footer_bottom, $new_footer_bottom, $text);
		
		//Update slider ids to correct id in templates while installing.
		$old_slider = "[SLIDER_ID]"; 
		$new_slider = $_SESSION['install_ids'][$site_id]['slider_id'];
		$text = str_replace($old_slider, $new_slider, $text);
		
		//Update custom_fields ids to correct id in templates while installing.
		$old_company_image = "[COMPANY_IMAGE]"; 
		$new_company_image = $_SESSION['install_ids'][$site_id]['company_image']; 
		$text = str_replace($old_company_image, $new_company_image, $text);
		
		$old_company_title = "[COMPANY_TITLE]"; 
		$new_company_title = $_SESSION['install_ids'][$site_id]['company_title'];
		$text = str_replace($old_company_title, $new_company_title, $text);
		
		$old_company_text = "[COMPANY_TEXT]"; 
		$new_company_text = $_SESSION['install_ids'][$site_id]['company_text'];
		$text = str_replace($old_company_text, $new_company_text, $text);
		
		//Update urlId() to correct id in templates while installing.
		$old_homepage = "[HOMEPAGE_PAGE]";
		$new_homepage = $_SESSION['install_ids'][$site_id]['homepage_url_id'];
		$text = str_replace($old_homepage, $new_homepage, $text);
		
		$old_blog_page = "[BLOG_PAGE]";
		$new_blog_page = $_SESSION['install_ids'][$site_id]['blog_page_url_id'];
		$text = str_replace($old_blog_page, $new_blog_page, $text);
		
		$old_about_us_page = "[ABOUT_US_PAGE]";
		$new_about_us_page = $_SESSION['install_ids'][$site_id]['about_us_page_url_id'];
		$text = str_replace($old_about_us_page, $new_about_us_page, $text);
		
		$old_contact_us_page = "[CONTACT_US_PAGE]";
		$new_contact_us_page = $_SESSION['install_ids'][$site_id]['contact_us_page_url_id'];
		$text = str_replace($old_contact_us_page, $new_contact_us_page, $text);
		
		$old_site_search_page = "[SITE_SEARCH_PAGE]";
		$new_site_search_page = $_SESSION['install_ids'][$site_id]['site_search_page_url_id'];
		$text = str_replace($old_site_search_page, $new_site_search_page, $text);
		
		$old_cookie_policy_page = "[COOKIE_POLICY_PAGE]";
		$new_cookie_policy_page = $_SESSION['install_ids'][$site_id]['cookie_policy_page_url_id'];
		$text = str_replace($old_cookie_policy_page, $new_cookie_policy_page, $text);
		
		$old_privacy_policy_page = "[PRIVACY_POLICY_PAGE]"; 
		$new_privacy_policy_page = $_SESSION['install_ids'][$site_id]['privacy_policy_page_url_id'];
		$text = str_replace($old_privacy_policy_page, $new_privacy_policy_page, $text);
		
		$old_terms_of_use_page = "[TERMS_OF_USE_PAGE]"; 
		$new_terms_of_use_page = $_SESSION['install_ids'][$site_id]['terms_of_use_page_url_id'];
		$text = str_replace($old_terms_of_use_page, $new_terms_of_use_page, $text);
		
		$old_clients_page = "[CLIENTS_PAGE]"; 
		$new_clients_page = $_SESSION['install_ids'][$site_id]['clients_page_url_id'];
		$text = str_replace($old_clients_page, $new_clients_page, $text);
		
		$old_portfolio_page = "[PORTFOLIO_PAGE]"; 
		$new_portfolio_page = $_SESSION['install_ids'][$site_id]['portfolio_page_url_id'];
		$text = str_replace($old_portfolio_page, $new_portfolio_page, $text);
		
		$old_our_story_page = "[OUR_STORY_PAGE]"; 
		$new_our_story_page = $_SESSION['install_ids'][$site_id]['our_story_page_url_id'];
		$text = str_replace($old_our_story_page, $new_our_story_page, $text);
		
		$old_faqs_page = "[FAQS_PAGE]"; 
		$new_faqs_page = $_SESSION['install_ids'][$site_id]['faqs_page_url_id'];
		$text = str_replace($old_faqs_page, $new_faqs_page, $text);
		
		$old_404_page = "[404_PAGE]"; 
		$new_404_page = $_SESSION['install_ids'][$site_id]['a404_page_url_id'];
		$text = str_replace($old_404_page, $new_404_page, $text);
		
		$old_message_confirmation_page = "[MESSAGE_CONFIRMATION_PAGE]"; 
		$new_message_confirmation_page = $_SESSION['install_ids'][$site_id]['message_confirmation_page_url_id'];
		$text = str_replace($old_message_confirmation_page, $new_message_confirmation_page, $text);
		
		$old_quote_confirmation_page = "[QUOTE_CONFIRMATION_PAGE]"; 
		$new_quote_confirmation_page = $_SESSION['install_ids'][$site_id]['quote_confirmation_page_url_id'];
		$text = str_replace($old_quote_confirmation_page, $new_quote_confirmation_page, $text);
		
		$old_sitemap_html_page = "[SITEMAP_HTML_PAGE]"; 
		$new_sitemap_html_page = $_SESSION['install_ids'][$site_id]['sitemap_html_page_url_id'];
		$text = str_replace($old_sitemap_html_page, $new_sitemap_html_page, $text);
		
		$old_sitemap_html_section_page = "[SITEMAP_HTML_SECTION_PAGE]"; 
		$new_sitemap_html_section_page = $_SESSION['install_ids'][$site_id]['sitemap_html_section_page_url_id'];
		$text = str_replace($old_sitemap_html_section_page, $new_sitemap_html_section_page, $text);
		
		$old_sitemap_xml_page = "[SITEMAP_XML_PAGE]"; 
		$new_sitemap_xml_page = $_SESSION['install_ids'][$site_id]['sitemap_xml_page_url_id'];
		$text = str_replace($old_sitemap_xml_page, $new_sitemap_xml_page, $text);
		
		$old_robots_page = "[ROBOTS_PAGE]"; 
		$new_robots_page = $_SESSION['install_ids'][$site_id]['robots_page_url_id'];
		$text = str_replace($old_robots_page, $new_robots_page, $text);
	
		clearstatcache(); //Clear file cache to make sure its writting to the real file and not buffer version/cache.
		
		$myfile = fopen($_SERVER['DOCUMENT_ROOT']."/sites/".$site_id."/templates/".$template_to_install."/".$site_file, "w");
		fwrite($myfile, $text);
		fclose($myfile);
	}
}

$installed_template_files = $results->getSelectMultipleRecordsKeyNameOneColumn(__LINE__, __FILE__, '*', 'template_files', 'WHERE `templates_id` = ?', [$template_id], 'filename', 'id');

$template_files_column_names = '`site_id`, `templates_id`, `status`, `name`, `filename`, `php_array`, `file_code`, `template_type`, `assigned_type`, `default_file`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$template_files_placeholders = '?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

//Pages -> 404.php
$_SESSION['a404_template_id'][$site_id] = $installed_template_files['404.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('404.php', $installed_template_files)))
{
	createTemplateFile('404.php', '404.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', '404', '404.php', '', '', 'pages', 'Pages > 404 Error', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['a404_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/404.php"');
	}
}

//Pages -> authors.php
$_SESSION['authors_template_id'][$site_id] = $installed_template_files['authors.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('authors.php', $installed_template_files)))
{
	createTemplateFile('authors.php', 'authors.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Authors', 'authors.php', '', '', 'authors', 'Authors > Bio', 'Yes', '{}', $first_last_name, $first_last_name]);
	$_SESSION['authors_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/authors.php"');
	}
}

//Pages -> blank-canvas.php
$_SESSION['blank_canvas_template_id'][$site_id] = $installed_template_files['blank-canvas.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('blank-canvas.php', $installed_template_files)))
{
	createTemplateFile('blank-canvas.php', 'blank-canvas.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Blank Canvas', 'blank-canvas.php', '', '', 'all', 'All > Blank Canvas', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['blank_canvas_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/blank-canvas.php"');
	}
}

//Pages -> blog.php
$_SESSION['blog_template_id'][$site_id] = $installed_template_files['blog.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('blog.php', $installed_template_files)))
{
	createTemplateFile('blog.php', 'blog.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Blog', 'blog.php', '', '', 'pages', 'Pages > Blog', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['blog_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/blog.php"');
	}
}

//Pages -> contact-us.php
$_SESSION['contact_us_template_id'][$site_id] = $installed_template_files['contact-us.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('contact-us.php', $installed_template_files)))
{
	createTemplateFile('contact-us.php', 'contact-us.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Contact Us', 'contact-us.php', '', '', 'pages', 'Pages > Contact Us', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['contact_us_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/contact-us.php"');
	}
}

//Pages -> homepage-blog.php
$_SESSION['homepage_blog_template_id'][$site_id] = $installed_template_files['homepage-blog.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('homepage-blog.php', $installed_template_files)))
{
	createTemplateFile('homepage-blog.php', 'homepage-blog.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Home Page - Blog', 'homepage-blog.php', '', '', 'pages', 'Pages > Homepage - Blog', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['homepage_blog_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/homepage-blog.php"');
	}
}

//Pages -> message-confirmation.php
$_SESSION['message_confirmation_template_id'][$site_id] = $installed_template_files['message-confirmation.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('message-confirmation.php', $installed_template_files)))
{
	createTemplateFile('message-confirmation.php', 'message-confirmation.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Message Confirmation', 'message-confirmation.php', '', '', 'pages', 'Pages > Message Confirmation', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['message_confirmation_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/message-confirmation.php"');
	}
}

//Pages -> pages-one-column.php
$_SESSION['one_column_template_id'][$site_id] = $installed_template_files['pages-one-column.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('pages-one-column.php', $installed_template_files)))
{
	createTemplateFile('pages-one-column.php', 'pages-one-column.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Pages - One Column', 'pages-one-column.php', '', '', 'pages', 'Pages > One Column', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['one_column_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/pages-one-column.php"');
	}
}

//Pages -> pages-one-column-gallery.php
$_SESSION['one_column_gallery_template_id'][$site_id] = $installed_template_files['pages-one-column-gallery.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('pages-one-column-gallery.php', $installed_template_files)))
{
	createTemplateFile('pages-one-column-gallery.php', 'pages-one-column-gallery.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Pages - One Column Gallery', 'pages-one-column-gallery.php', '', '', 'pages', 'Pages > One Column Gallery', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['one_column_gallery_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/pages-one-column-gallery.php"');
	}
}

//Pages -> pages-two-column.php
$_SESSION['two_column_template_id'][$site_id] = $installed_template_files['pages-two-column.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('pages-two-column.php', $installed_template_files)))
{
	createTemplateFile('pages-two-column.php', 'pages-two-column.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Pages - Two Column', 'pages-two-column.php', '', '', 'pages', 'Pages > Two Column', 'Yes', '{}', $first_last_name, $first_last_name]);
	$_SESSION['two_column_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/pages-two-column.php"');
	}
}

//Pages -> pages-two-column-gallery.php
$_SESSION['two_column_gallery_template_id'][$site_id] = $installed_template_files['pages-two-column-gallery.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('pages-two-column-gallery.php', $installed_template_files)))
{
	createTemplateFile('pages-two-column-gallery.php', 'pages-two-column-gallery.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Pages - Two Column Gallery', 'pages-two-column-gallery.php', '', '', 'pages', 'Pages > Two Column Gallery', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['two_column_gallery_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/pages-two-column-gallery.php"');
	}
}

//Pages -> quote-confirmation.php
$_SESSION['quote_confirmation_template_id'][$site_id] = $installed_template_files['quote-confirmation.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('quote-confirmation.php', $installed_template_files)))
{
	createTemplateFile('quote-confirmation.php', 'quote-confirmation.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Quote Confirmation', 'quote-confirmation.php', '', '', 'pages', 'Pages > Quote Confirmation', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['quote_confirmation_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/quote-confirmation.php"');
	}
}

//Pages -> robots.php
$_SESSION['robots_template_id'][$site_id] = $installed_template_files['robots.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('robots.php', $installed_template_files)))
{
	createTemplateFile('robots.php', 'robots.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Robots Text File', 'robots.php', '', '', 'pages', 'Pages > Robots Text File', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['robots_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/robots.php"');
	}
}

//Pages -> search.php
$_SESSION['search_template_id'][$site_id] = $installed_template_files['search.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('search.php', $installed_template_files)))
{
	createTemplateFile('search.php', 'search.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Search', 'search.php', '', '', 'pages', 'Pages > Site Search', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['search_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/search.php"');
	}
}

//Pages -> sitemap-html.php
$_SESSION['sitemap_html_template_id'][$site_id] = $installed_template_files['sitemap-html.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('sitemap-html.php', $installed_template_files)))
{
	createTemplateFile('sitemap-html.php', 'sitemap-html.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'SiteMap HTML File', 'sitemap-html.php', '', '', 'pages', 'Pages > Sitemap - HTML', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['sitemap_html_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/sitemap-html.php"');
	}
}

//Pages -> sitemap-html-section.php
$_SESSION['sitemap_html_section_template_id'][$site_id] = $installed_template_files['sitemap-html-section.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('sitemap-html-section.php', $installed_template_files)))
{
	createTemplateFile('sitemap-html-section.php', 'sitemap-html-section.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'SiteMap HTML Section File', 'sitemap-html-section.php', '', '', 'pages', 'Pages > Sitemap - HTML Section', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['sitemap_html_section_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/sitemap-html-section.php"');
	}
}

//Pages -> sitemap-xml.php
$_SESSION['sitemap_xml_template_id'][$site_id] = $installed_template_files['sitemap-xml.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('sitemap-xml.php', $installed_template_files)))
{
	createTemplateFile('sitemap-xml.php', 'sitemap-xml.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'SiteMap XML File', 'sitemap-xml.php', '', '', 'pages', 'Pages > Sitemap - XML', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['sitemap_xml_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/sitemap-xml.php"');
	}
}

//Categories -> categories-blog.php
$_SESSION['categories_blog_template_id'][$site_id] = $installed_template_files['categories-blog.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('categories-blog.php', $installed_template_files)))
{
	createTemplateFile('categories-blog.php', 'categories-blog.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Categories - Blog Layout', 'categories-blog.php', '', '', 'categories', 'Category > Blog', 'No', '{}', $first_last_name, $first_last_name]);
	$_SESSION['categories_blog_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/categories-blog.php"');
	}
}

//Posts -> posts.php
$_SESSION['posts_template_id'][$site_id] = $installed_template_files['posts.php'] ?? 0;
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('posts.php', $installed_template_files)))
{
	createTemplateFile('posts.php', 'posts.php');
	$template_file_id = $results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Posts', 'posts.php', '', '', 'posts', 'Posts > Posts', 'Yes', '{}', $first_last_name, $first_last_name]);
	$_SESSION['posts_template_id'][$site_id] = $template_file_id;
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/posts.php"');
	}
}

//Includes -> analytics.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('analytics.php', $installed_template_files)))
{
	createTemplateFile('analytics.php', 'analytics.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Analytics Cookie', 'analytics.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/analytics.php"');
	}
}

//Includes -> author-and-dates-top.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('author-and-dates-top.php', $installed_template_files)))
{
	createTemplateFile('author-and-dates-top.php', 'author-and-dates-top.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Author and Dates Top', 'author-and-dates-top.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/author-and-dates-top.php"');
	}
}

//Includes -> breadcrumbs.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('breadcrumbs.php', $installed_template_files)))
{
	createTemplateFile('breadcrumbs.php', 'breadcrumbs.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Breadcrumbs', 'breadcrumbs.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/breadcrumbs.php"');
	}
}

//Includes -> content-security-policy.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('content-security-policy.php', $installed_template_files)))
{
	createTemplateFile('content-security-policy.php', 'content-security-policy.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Content Security Policy', 'content-security-policy.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/content-security-policy.php"');
	}
}

//Includes -> cookie-notice-banner.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('cookie-notice-banner.php', $installed_template_files)))
{
	createTemplateFile('cookie-notice-banner.php', 'cookie-notice-banner.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Cookie Notice Banner', 'cookie-notice-banner.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/cookie-notice-banner.php"');
	}
}

//Includes -> footer.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('footer.php', $installed_template_files)))
{
	createTemplateFile('footer.php', 'footer.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Footer', 'footer.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/footer.php"');
	}
}

//Includes -> head-files.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('head-files.php', $installed_template_files)))
{
	createTemplateFile('head-files.php', 'head-files.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Head Files', 'head-files.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/head-files.php"');
	}
}

//Includes -> header.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('header.php', $installed_template_files)))
{
	createTemplateFile('header.php', 'header.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Header', 'header.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/header.php"');
	}
}

//Includes -> header-search-bar.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('header-search-bar.php', $installed_template_files)))
{
	createTemplateFile('header-search-bar.php', 'header-search-bar.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Header - Search Bar', 'header-search-bar.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/header-search-bar.php"');
	}
}

//Includes -> scripts.js
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('scripts.js', $installed_template_files)))
{
	createTemplateFile('scripts.js', 'scripts.js');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Scripts', 'scripts.js', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/scripts.js"');
	}
}

//Includes -> sidebar-about.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('sidebar-about.php', $installed_template_files)))
{
	createTemplateFile('sidebar-about.php', 'sidebar-about.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Sidebar - About', 'sidebar-about.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/sidebar-about.php"');
	}
}

//Includes -> sidebar-blog-categories.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('sidebar-blog-categories.php', $installed_template_files)))
{
	createTemplateFile('sidebar-blog-categories.php', 'sidebar-blog-categories.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Sidebar - Blog Categories', 'sidebar-blog-categories.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/sidebar-blog-categories.php"');
	}
}

//Includes -> sidebar-contact-us.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('sidebar-contact-us.php', $installed_template_files)))
{
	createTemplateFile('sidebar-contact-us.php', 'sidebar-contact-us.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Sidebar - Contact Us', 'sidebar-contact-us.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/sidebar-contact-us.php"');
	}
}

//Includes -> sidebar-table-of-contents.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('sidebar-table-of-contents.php', $installed_template_files)))
{
	createTemplateFile('sidebar-table-of-contents.php', 'sidebar-table-of-contents.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Sidebar - Table of Contents', 'sidebar-table-of-contents.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/sidebar-table-of-contents.php"');
	}
}

//Includes -> slider.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('slider.php', $installed_template_files)))
{
	createTemplateFile('slider.php', 'slider.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Slider', 'slider.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/slider.php"');
	}
}

//Includes -> stylesheet.css
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('stylesheet.css', $installed_template_files)))
{
	createTemplateFile('stylesheet.css', 'stylesheet.css');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Stylesheet', 'stylesheet.css', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/stylesheet.css"');
	}
}

//Includes -> sub-items.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('sub-items.php', $installed_template_files)))
{
	createTemplateFile('sub-items.php', 'sub-items.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Sub Items', 'sub-items.php', '', '', 'includes', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/sub-items.php"');
	}
}

//Email templates -> email-template.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('email-template.php', $installed_template_files)))
{
	createTemplateFile('email-template.php', 'email-template.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Email Template - Main Wrapper', 'email-template.php', '', '', 'email_templates', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/email-template.php"');
	}
}

//Email templates -> email-template-max-failed-login-attempts.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('email-template-max-failed-login-attempts.php', $installed_template_files)))
{
	createTemplateFile('email-template-max-failed-login-attempts.php', 'email-template-max-failed-login-attempts.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Email Template - Max Failed Login Attempts', 'email-template-max-failed-login-attempts.php', '', '', 'email_templates', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/email-template-max-failed-login-attempts.php"');
	}
}

//Email templates -> email-template-password-reset-admin.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('email-template-password-reset-admin.php', $installed_template_files)))
{
	createTemplateFile('email-template-password-reset-admin.php', 'email-template-password-reset-admin.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Email Template - Password Reset - Admin', 'email-template-password-reset-admin.php', '', '', 'email_templates', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/email-template-password-reset-admin.php"');
	}
}

//Email templates -> email-template-possible-ddos-attack.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('email-template-possible-ddos-attack.php', $installed_template_files)))
{
	createTemplateFile('email-template-possible-ddos-attack.php', 'email-template-possible-ddos-attack.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Email Template - Possible DDOS Attack', 'email-template-possible-ddos-attack.php', '', '', 'email_templates', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/email-template-possible-ddos-attack.php"');
	}
}

//Email templates -> email-template-possible-sql-injection-attempt.php
if((isset($install_template) && $install_template == 'Yes') || (isset($update_template) && $update_template == 'Yes' && !array_key_exists('email-template-possible-sql-injection-attempt.php', $installed_template_files)))
{
	createTemplateFile('email-template-possible-sql-injection-attempt.php', 'email-template-possible-sql-injection-attempt.php');
	$results->getInsertRecord(__LINE__, __FILE__, 'template_files', $template_files_column_names, $template_files_placeholders, 
	[$site_id, $template_id, '1', 'Email Template - Possible SQL Injection Attempt', 'email-template-possible-sql-injection-attempt.php', '', '', 'email_templates', '', 'No', '{}', $first_last_name, $first_last_name]);
	
	if(function_exists('writeToInstallLog'))
	{
		writeToInstallLog('Installed template file: "/sites/'.$site_id.'/templates/'.$template_to_install.'/email-template-possible-sql-injection-attempt.php"');
	}
}

//Update the number of template files on the template sub_items count
$all_template_files_installed = $results->getSelectMultipleRecords(__LINE__, __FILE__, '`id`', 'template_files', 'WHERE `templates_id` = ?', [$template_id]);
$template_files_count = count($all_template_files_installed);

$results->getUpdateRecord(__LINE__, __FILE__, 'templates', '`sub_items` = ?', 'WHERE `id` = ?', [$template_files_count, $template_id]);