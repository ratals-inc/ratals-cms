<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$column_names = '`id`, `site_id`, `admin_fields_lists_id`, `label`, `value`, `swap_admin_field_to`, `admin_fields_lists_parent_code`, `system_code`, `sort`';
$placeholders = 'NULL,0,?,?,?,?,?,?,?';

$parameter = array();
$values_counter = 1;
$parameter[] = [0, 'Yes', 'Yes', '', 'yes_no', 'yes', $values_counter++];
$parameter[] = [0, 'No', 'No', '', 'yes_no', 'no', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Enabled', '1', '', 'status_main', 'status_main_enabled', $values_counter++];
$parameter[] = [0, 'Disabled', '2', '', 'status_main', 'status_main_disabled', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Index, Follow', 'index, follow', '', 'meta_robots', 'index_follow', $values_counter++];
$parameter[] = [0, 'Index, NoFollow', 'index, nofollow', '', 'meta_robots', 'index_nofollow', $values_counter++];
$parameter[] = [0, 'NoIndex, Follow', 'noindex, follow', '', 'meta_robots', 'noindex_follow', $values_counter++];
$parameter[] = [0, 'NoIndex, NoFollow', 'noindex, nofollow', '', 'meta_robots', 'noindex_nofollow', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'File', 'File', '', 'media_types', 'file', $values_counter++];
$parameter[] = [0, 'Image', 'Image', '', 'media_types', 'image', $values_counter++];
$parameter[] = [0, 'Video', 'Video', '', 'media_types', 'video', $values_counter++];
$parameter[] = [0, 'Video Embed', 'Video Embed', '', 'media_types', 'video_embed', $values_counter++];

$values_counter = 1;
$parameter[] = [0, '301 - Permanent', '301', '', 'urls_redirect_types', '301_permanent', $values_counter++];
$parameter[] = [0, '302 - Temporary', '302', '', 'urls_redirect_types', '302_temporary', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Content Field', 'Content Field', '', 'custom_fields_field_type', 'content_field', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Boxes', 'boxes', '', 'custom_fields_display_as', 'boxes', $values_counter++];
$parameter[] = [0, 'Dropdown', 'dropdownId', '', 'custom_fields_display_as', 'dropdown', $values_counter++];
$parameter[] = [0, 'Single Media', 'singleMedia', '', 'custom_fields_display_as', 'single_media', $values_counter++];
$parameter[] = [0, 'Swatch', 'swatch', '', 'custom_fields_display_as', 'swatch', $values_counter++];
$parameter[] = [0, 'Textarea', 'textarea', '', 'custom_fields_display_as', 'textarea', $values_counter++];
$parameter[] = [0, 'Textarea With Editor', 'textareaWithEditor', '', 'custom_fields_display_as', 'textarea_with_editor', $values_counter++];
$parameter[] = [0, 'Textfield', 'textfield', '', 'custom_fields_display_as', 'textfield', $values_counter++];

$values_counter = 1;
$parameter[] = [0, '0 - 65,500 Text Characters - Data Type: Text', 'text(65535)', '', 'custom_fields_data_type', 'custom_fields_data_type_text', $values_counter++];
$parameter[] = [0, 'Integer - 1234 - Data Type: INT', 'int', '', 'custom_fields_data_type', 'custom_fields_data_type_int', $values_counter++];
$parameter[] = [0, 'Float - 0.0000 - Data Type: Float(16,6)', 'float(16,6)', '', 'custom_fields_data_type', 'custom_fields_data_type_float_16_6', $values_counter++];
$parameter[] = [0, 'Date - Data Type: Date', 'date', '', 'custom_fields_data_type', 'custom_fields_data_type_date', $values_counter++];
$parameter[] = [0, 'DateTime - Data Type: DateTime', 'datetime', '', 'custom_fields_data_type', 'custom_fields_data_type_datetime', $values_counter++];
$parameter[] = [0, 'TimeStamp - Data Type: TimeStamp', 'timestamp', '', 'custom_fields_data_type', 'custom_fields_data_type_timestamp', $values_counter++];
$parameter[] = [0, 'Time - Data Type: Time', 'time', '', 'custom_fields_data_type', 'custom_fields_data_type_time', $values_counter++];
$parameter[] = [0, 'Year - Data Type: Year', 'year', '', 'custom_fields_data_type', 'custom_fields_data_type_year', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'All > Blank Canvas', 'All > Blank Canvas', '', 'template_files_assigned_type', 'all_blank_canvas', $values_counter++];
$parameter[] = [0, 'Authors > Bio', 'Authors > Bio', '', 'template_files_assigned_type', 'authors_bio', $values_counter++];
$parameter[] = [0, 'Category > Blog', 'Category > Blog', '', 'template_files_assigned_type', 'category_blog', $values_counter++];
$parameter[] = [0, 'Pages > 404 Error', 'Pages > 404 Error', '', 'template_files_assigned_type', 'pages_404_error', $values_counter++];
$parameter[] = [0, 'Pages > Blog', 'Pages > Blog', '', 'template_files_assigned_type', 'pages_blog', $values_counter++];
$parameter[] = [0, 'Pages > Contact Us', 'Pages > Contact Us', '', 'template_files_assigned_type', 'pages_contact_us', $values_counter++];
$parameter[] = [0, 'Pages > Homepage - Blog', 'Pages > Homepage - Blog', '', 'template_files_assigned_type', 'pages_homepage_blog', $values_counter++];
$parameter[] = [0, 'Pages > Message Confirmation', 'Pages > Message Confirmation', '', 'template_files_assigned_type', 'pages_message_confirmation', $values_counter++];
$parameter[] = [0, 'Pages > One Column Gallery', 'Pages > One Column Gallery', '', 'template_files_assigned_type', 'pages_one_column_gallery', $values_counter++];
$parameter[] = [0, 'Pages > One Column', 'Pages > One Column', '', 'template_files_assigned_type', 'pages_one_column', $values_counter++];
$parameter[] = [0, 'Posts > Posts', 'Posts > Posts', '', 'template_files_assigned_type', 'posts_posts', $values_counter++];
$parameter[] = [0, 'Pages > Quote Confirmation', 'Pages > Quote Confirmation', '', 'template_files_assigned_type', 'pages_quote_confirmation', $values_counter++];
$parameter[] = [0, 'Pages > Robots Text File', 'Pages > Robots Text File', '', 'template_files_assigned_type', 'pages_robots_text_file', $values_counter++];
$parameter[] = [0, 'Pages > Site Search', 'Pages > Site Search', '', 'template_files_assigned_type', 'pages_site_search', $values_counter++];
$parameter[] = [0, 'Pages > Sitemap - HTML Section', 'Pages > Sitemap - HTML Section', '', 'template_files_assigned_type', 'pages_sitemap_html_section', $values_counter++];
$parameter[] = [0, 'Pages > Sitemap - HTML', 'Pages > Sitemap - HTML', '', 'template_files_assigned_type', 'pages_sitemap_html', $values_counter++];
$parameter[] = [0, 'Pages > Sitemap - XML', 'Pages > Sitemap - XML', '', 'template_files_assigned_type', 'pages_sitemap_xml', $values_counter++];
$parameter[] = [0, 'Pages > Two Column Gallery', 'Pages > Two Column Gallery', '', 'template_files_assigned_type', 'Pages_two_column_gallery', $values_counter++];
$parameter[] = [0, 'Pages > Two Column', 'Pages > Two Column', '', 'template_files_assigned_type', 'pages_two_column', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Follow', 'follow', '', 'urls_link_type', 'follow', $values_counter++];
$parameter[] = [0, 'No Follow', 'nofollow', '', 'urls_link_type', 'no_follow', $values_counter++];

$values_counter = 1;
$parameter[] = [0, '1 Column / List View', '1', '', 'css_grid_columns', '1_column_list_view', $values_counter++];
$parameter[] = [0, '2 Columns', '2', '', 'css_grid_columns', '2_columns', $values_counter++];
$parameter[] = [0, '3 Columns', '3', '', 'css_grid_columns', '3_columns', $values_counter++];
$parameter[] = [0, '4 Columns', '4', '', 'css_grid_columns', '4_columns', $values_counter++];
$parameter[] = [0, '5 Columns', '5', '', 'css_grid_columns', '5_columns', $values_counter++];
$parameter[] = [0, '6 Columns', '6', '', 'css_grid_columns', '6_columns', $values_counter++];
$parameter[] = [0, '7 Columns', '7', '', 'css_grid_columns', '7_columns', $values_counter++];
$parameter[] = [0, '8 Columns', '8', '', 'css_grid_columns', '8_columns', $values_counter++];
$parameter[] = [0, '9 Columns', '9', '', 'css_grid_columns', '9_columns', $values_counter++];
$parameter[] = [0, '10 Columns', '10', '', 'css_grid_columns', '10_columns', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Main Menu', 'Main Menu', '', 'admin_menu_type', 'main_menu', $values_counter++];
$parameter[] = [0, 'Sub Menu', 'Sub Menu', '', 'admin_menu_type', 'sub_menu', $values_counter++];

$values_counter = 1;
$parameter[] = [0, '_blank', '_blank', '', 'links_target_types', '_blank', $values_counter++];
$parameter[] = [0, '_parent', '_parent', '', 'links_target_types', '_parent', $values_counter++];
$parameter[] = [0, '_self', '_self', '', 'links_target_types', '_self', $values_counter++];
$parameter[] = [0, '_top', '_top', '', 'links_target_types', '_top', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Date Range', 'dateRange', '', 'custom_fields_search_as', 'custom_fields_search_as_date_range', $values_counter++];
$parameter[] = [0, 'Dropdown', 'dropdownId', '', 'custom_fields_search_as', 'custom_fields_search_as_dropdown', $values_counter++];
$parameter[] = [0, 'Price', 'price', '', 'custom_fields_search_as', 'custom_fields_search_as_price', $values_counter++];
$parameter[] = [0, 'Textfield', 'textfield', '', 'custom_fields_search_as', 'custom_fields_search_as_textfield', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Color Number', 'Color Number', '', 'custom_field_options_display_color_swatch', 'color_number', $values_counter++];
$parameter[] = [0, 'Media Swatch', 'Media Swatch', '', 'custom_field_options_display_color_swatch', 'media_swatch', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'On', 'On', '', 'forms_auto_complete', 'on', $values_counter++];
$parameter[] = [0, 'Off', 'Off', '', 'forms_auto_complete', 'off', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Full Name: All in one field', 'name', '', 'forms_auto_complete_type', 'full_name_all_in_one_field', $values_counter++];
$parameter[] = [0, 'Mrs., Mr., Miss, Ms., Dr.', 'honorific-prefix', '', 'forms_auto_complete_type', 'mrs_mr_miss_ms_dr.', $values_counter++];
$parameter[] = [0, 'First Name', 'given-name', '', 'forms_auto_complete_type', 'first_name', $values_counter++];
$parameter[] = [0, 'Middle Name', 'additional-name', '', 'forms_auto_complete_type', 'middle_name', $values_counter++];
$parameter[] = [0, 'Last Name', 'family-name', '', 'forms_auto_complete_type', 'last_name', $values_counter++];
$parameter[] = [0, 'Suffix: Jr., B.Sc., PhD., MBASW', 'honorific-suffix', '', 'forms_auto_complete_type', 'suffix_jr_bsc_phd_mbasw', $values_counter++];
$parameter[] = [0, 'Nickname', 'nickname', '', 'forms_auto_complete_type', 'nickname', $values_counter++];
$parameter[] = [0, 'Email', 'email', '', 'forms_auto_complete_type', 'email', $values_counter++];
$parameter[] = [0, 'Job Title', 'organization-title', '', 'forms_auto_complete_type', 'job_title', $values_counter++];
$parameter[] = [0, 'Company or Organization Name', 'organization', '', 'forms_auto_complete_type', 'company_or_organization_name', $values_counter++];
$parameter[] = [0, 'Street Address', 'address-line1', '', 'forms_auto_complete_type', 'street_address', $values_counter++];
$parameter[] = [0, 'Street Address 2', 'address-line2', '', 'forms_auto_complete_type', 'street_address_2', $values_counter++];
$parameter[] = [0, 'Street Address 3', 'address-line3', '', 'forms_auto_complete_type', 'street_address_3', $values_counter++];
$parameter[] = [0, 'City', 'address-level2', '', 'forms_auto_complete_type', 'city', $values_counter++];
$parameter[] = [0, 'State / Province', 'address-level1', '', 'forms_auto_complete_type', 'state_province', $values_counter++];
$parameter[] = [0, 'Postal Code', 'postal-code', '', 'forms_auto_complete_type', 'postal_code', $values_counter++];
$parameter[] = [0, 'Country', 'country-name', '', 'forms_auto_complete_type', 'country', $values_counter++];
$parameter[] = [0, 'Full Phone Number: All in one field', 'tel', '', 'forms_auto_complete_type', 'full_phone_number_all_in_one_field', $values_counter++];
$parameter[] = [0, 'Phone Number Country Code Only', 'tel-country-code', '', 'forms_auto_complete_type', 'phone_number_country_code_only', $values_counter++];
$parameter[] = [0, 'Phone Number Area Code Only', 'tel-area-code', '', 'forms_auto_complete_type', 'phone_number_area_code_only', $values_counter++];
$parameter[] = [0, 'Phone Number Prefix Code Only', 'tel-local-prefix', '', 'forms_auto_complete_type', 'phone_number_prefix_code_only', $values_counter++];
$parameter[] = [0, 'Phone Number Suffix Code Only', 'tel-local-suffix', '', 'forms_auto_complete_type', 'phone_number_suffix_code_only', $values_counter++];
$parameter[] = [0, 'Phone Number Extesntion', 'tel-extension', '', 'forms_auto_complete_type', 'phone_number_extesntion', $values_counter++];
$parameter[] = [0, 'Website URL', 'url', '', 'forms_auto_complete_type', 'website_url', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Dropdown', 'Dropdown', '', 'forms_field_type', 'forms_field_type_dropdown', $values_counter++];
$parameter[] = [0, 'Swatch', 'Swatch', '', 'forms_field_type', 'forms_field_type_swatch', $values_counter++];
$parameter[] = [0, 'Textarea', 'Textarea', '', 'forms_field_type', 'forms_field_type_textarea', $values_counter++];
$parameter[] = [0, 'Textfield', 'Textfield', '', 'forms_field_type', 'forms_field_type_textfield', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Afghanistan', 'AF', 'state_province_region', 'countries', 'countries_afghanistan', $values_counter++];
$parameter[] = [0, 'Albania', 'AL', 'state_province_region', 'countries', 'countries_albania', $values_counter++];
$parameter[] = [0, 'Algeria', 'DZ', 'state_province_region', 'countries', 'countries_algeria', $values_counter++];
$parameter[] = [0, 'Andorra', 'AD', 'state_province_region', 'countries', 'countries_andorra', $values_counter++];
$parameter[] = [0, 'Angola', 'AO', 'state_province_region', 'countries', 'countries_angola', $values_counter++];
$parameter[] = [0, 'Antigua and Barbuda', 'AG', 'state_province_region', 'countries', 'countries_antigua_and_barbuda', $values_counter++];
$parameter[] = [0, 'Argentina', 'AR', 'state_province_region', 'countries', 'countries_argentina', $values_counter++];
$parameter[] = [0, 'Armenia', 'AM', 'state_province_region', 'countries', 'countries_armenia', $values_counter++];
$parameter[] = [0, 'Australia', 'AU', 'state_province_region', 'countries', 'countries_australia', $values_counter++];
$parameter[] = [0, 'Austria', 'AT', 'state_province_region', 'countries', 'countries_austria', $values_counter++];
$parameter[] = [0, 'Azerbaijan', 'AZ', 'state_province_region', 'countries', 'countries_azerbaijan', $values_counter++];
$parameter[] = [0, 'Bahamas', 'BS', 'state_province_region', 'countries', 'countries_bahamas', $values_counter++];
$parameter[] = [0, 'Bahrain', 'BH', 'state_province_region', 'countries', 'countries_bahrain', $values_counter++];
$parameter[] = [0, 'Bangladesh', 'BD', 'state_province_region', 'countries', 'countries_bangladesh', $values_counter++];
$parameter[] = [0, 'Barbados', 'BB', 'state_province_region', 'countries', 'countries_barbados', $values_counter++];
$parameter[] = [0, 'Belarus', 'BY', 'state_province_region', 'countries', 'countries_belarus', $values_counter++];
$parameter[] = [0, 'Belgium', 'BE', 'state_province_region', 'countries', 'countries_belgium', $values_counter++];
$parameter[] = [0, 'Belize', 'BZ', 'state_province_region', 'countries', 'countries_belize', $values_counter++];
$parameter[] = [0, 'Benin', 'BJ', 'state_province_region', 'countries', 'countries_benin', $values_counter++];
$parameter[] = [0, 'Bhutan', 'BT', 'state_province_region', 'countries', 'countries_bhutan', $values_counter++];
$parameter[] = [0, 'Bolivia', 'BO', 'state_province_region', 'countries', 'countries_bolivia', $values_counter++];
$parameter[] = [0, 'Bosnia and Herzegovina', 'BA', 'state_province_region', 'countries', 'countries_bosnia_and_herzegovina', $values_counter++];
$parameter[] = [0, 'Botswana', 'BW', 'state_province_region', 'countries', 'countries_botswana', $values_counter++];
$parameter[] = [0, 'Brazil', 'BR', 'state_province_region', 'countries', 'countries_brazil', $values_counter++];
$parameter[] = [0, 'Brunei', 'BN', 'state_province_region', 'countries', 'countries_brunei', $values_counter++];
$parameter[] = [0, 'Bulgaria', 'BG', 'state_province_region', 'countries', 'countries_bulgaria', $values_counter++];
$parameter[] = [0, 'Burkina Faso', 'BF', 'state_province_region', 'countries', 'countries_burkina_faso', $values_counter++];
$parameter[] = [0, 'Burundi', 'BI', 'state_province_region', 'countries', 'countries_burundi', $values_counter++];
$parameter[] = [0, 'Cote d\'Ivoire', 'CI', 'state_province_region', 'countries', 'countries_Cote_dIvoire', $values_counter++];
$parameter[] = [0, 'Cabo Verde', 'CV', 'state_province_region', 'countries', 'countries_cabo_verde', $values_counter++];
$parameter[] = [0, 'Cambodia', 'KH', 'state_province_region', 'countries', 'countries_cambodia', $values_counter++];
$parameter[] = [0, 'Cameroon', 'CM', 'state_province_region', 'countries', 'countries_cameroon', $values_counter++];
$parameter[] = [0, 'Canada', 'CA', 'ca_province', 'countries', 'countries_canada', $values_counter++];
$parameter[] = [0, 'Central African Republic', 'CF', 'state_province_region', 'countries', 'countries_central_african_republic', $values_counter++];
$parameter[] = [0, 'Chad', 'TD', 'state_province_region', 'countries', 'countries_chad', $values_counter++];
$parameter[] = [0, 'Chile', 'CL', 'state_province_region', 'countries', 'countries_chile', $values_counter++];
$parameter[] = [0, 'China', 'CN', 'state_province_region', 'countries', 'countries_china', $values_counter++];
$parameter[] = [0, 'Colombia', 'CO', 'state_province_region', 'countries', 'countries_colombia', $values_counter++];
$parameter[] = [0, 'Comoros', 'KM', 'state_province_region', 'countries', 'countries_comoros', $values_counter++];
$parameter[] = [0, 'Congo (Congo-Brazzaville)', 'CG', 'state_province_region', 'countries', 'countries_congo_congo_brazzaville)', $values_counter++];
$parameter[] = [0, 'Costa Rica', 'CR', 'state_province_region', 'countries', 'countries_costa_rica', $values_counter++];
$parameter[] = [0, 'Croatia', 'HR', 'state_province_region', 'countries', 'countries_croatia', $values_counter++];
$parameter[] = [0, 'Cuba', 'CU', 'state_province_region', 'countries', 'countries_cuba', $values_counter++];
$parameter[] = [0, 'Cyprus', 'CY', 'state_province_region', 'countries', 'countries_cyprus', $values_counter++];
$parameter[] = [0, 'Czechia (Czech Republic)', 'CZ', 'state_province_region', 'countries', 'countries_czechia_czech_republic', $values_counter++];
$parameter[] = [0, 'Democratic Republic of the Congo', 'CD', 'state_province_region', 'countries', 'countries_democratic_republic_of_the_congo', $values_counter++];
$parameter[] = [0, 'Denmark', 'DK', 'state_province_region', 'countries', 'countries_denmark', $values_counter++];
$parameter[] = [0, 'Djibouti', 'DJ', 'state_province_region', 'countries', 'countries_djibouti', $values_counter++];
$parameter[] = [0, 'Dominica', 'DM', 'state_province_region', 'countries', 'countries_dominica', $values_counter++];
$parameter[] = [0, 'Dominican Republic', 'DO', 'state_province_region', 'countries', 'countries_dominican_republic', $values_counter++];
$parameter[] = [0, 'Ecuador', 'EC', 'state_province_region', 'countries', 'countries_ecuador', $values_counter++];
$parameter[] = [0, 'Egypt', 'EG', 'state_province_region', 'countries', 'countries_egypt', $values_counter++];
$parameter[] = [0, 'El Salvador', 'SV', 'state_province_region', 'countries', 'countries_el_salvador', $values_counter++];
$parameter[] = [0, 'Equatorial Guinea', 'GQ', 'state_province_region', 'countries', 'countries_equatorial_guinea', $values_counter++];
$parameter[] = [0, 'Eritrea', 'ER', 'state_province_region', 'countries', 'countries_eritrea', $values_counter++];
$parameter[] = [0, 'Estonia', 'EE', 'state_province_region', 'countries', 'countries_estonia', $values_counter++];
$parameter[] = [0, 'Eswatini (fmr. "Swaziland")', 'SZ', 'state_province_region', 'countries', 'countries_eswatini_fmr_swaziland)', $values_counter++];
$parameter[] = [0, 'Ethiopia', 'ET', 'state_province_region', 'countries', 'countries_ethiopia', $values_counter++];
$parameter[] = [0, 'Fiji', 'FJ', 'state_province_region', 'countries', 'countries_fiji', $values_counter++];
$parameter[] = [0, 'Finland', 'FI', 'state_province_region', 'countries', 'countries_finland', $values_counter++];
$parameter[] = [0, 'France', 'FR', 'state_province_region', 'countries', 'countries_france', $values_counter++];
$parameter[] = [0, 'Gabon', 'GA', 'state_province_region', 'countries', 'countries_gabon', $values_counter++];
$parameter[] = [0, 'Gambia', 'GM', 'state_province_region', 'countries', 'countries_gambia', $values_counter++];
$parameter[] = [0, 'Georgia', 'GE', 'state_province_region', 'countries', 'countries_georgia', $values_counter++];
$parameter[] = [0, 'Germany', 'DE', 'state_province_region', 'countries', 'countries_germany', $values_counter++];
$parameter[] = [0, 'Ghana', 'GH', 'state_province_region', 'countries', 'countries_ghana', $values_counter++];
$parameter[] = [0, 'Greece', 'GR', 'state_province_region', 'countries', 'countries_greece', $values_counter++];
$parameter[] = [0, 'Grenada', 'GD', 'state_province_region', 'countries', 'countries_grenada', $values_counter++];
$parameter[] = [0, 'Guatemala', 'GT', 'state_province_region', 'countries', 'countries_guatemala', $values_counter++];
$parameter[] = [0, 'Guinea', 'GN', 'state_province_region', 'countries', 'countries_guinea', $values_counter++];
$parameter[] = [0, 'Guinea-Bissau', 'GW', 'state_province_region', 'countries', 'countries_guinea_bissau', $values_counter++];
$parameter[] = [0, 'Guyana', 'GY', 'state_province_region', 'countries', 'countries_guyana', $values_counter++];
$parameter[] = [0, 'Haiti', 'HT', 'state_province_region', 'countries', 'countries_haiti', $values_counter++];
$parameter[] = [0, 'Holy See', 'VA', 'state_province_region', 'countries', 'countries_holy_see', $values_counter++];
$parameter[] = [0, 'Honduras', 'HN', 'state_province_region', 'countries', 'countries_honduras', $values_counter++];
$parameter[] = [0, 'Hong Kong SAR', 'HK', 'state_province_region', 'countries', 'countries_hong_kong_sar', $values_counter++];
$parameter[] = [0, 'Hungary', 'HU', 'state_province_region', 'countries', 'countries_hungary', $values_counter++];
$parameter[] = [0, 'Iceland', 'IS', 'state_province_region', 'countries', 'countries_iceland', $values_counter++];
$parameter[] = [0, 'India', 'IN', 'state_province_region', 'countries', 'countries_india', $values_counter++];
$parameter[] = [0, 'Indonesia', 'ID', 'state_province_region', 'countries', 'countries_indonesia', $values_counter++];
$parameter[] = [0, 'Iraq', 'IQ', 'state_province_region', 'countries', 'countries_iraq', $values_counter++];
$parameter[] = [0, 'Iran', 'IR', 'state_province_region', 'countries', 'countries_iran', $values_counter++];
$parameter[] = [0, 'Ireland', 'IE', 'state_province_region', 'countries', 'countries_ireland', $values_counter++];
$parameter[] = [0, 'Israel', 'IL', 'state_province_region', 'countries', 'countries_israel', $values_counter++];
$parameter[] = [0, 'Italy', 'IT', 'state_province_region', 'countries', 'countries_italy', $values_counter++];
$parameter[] = [0, 'Jamaica', 'JM', 'state_province_region', 'countries', 'countries_jamaica', $values_counter++];
$parameter[] = [0, 'Japan', 'JP', 'state_province_region', 'countries', 'countries_japan', $values_counter++];
$parameter[] = [0, 'Jordan', 'JO', 'state_province_region', 'countries', 'countries_jordan', $values_counter++];
$parameter[] = [0, 'Kazakhstan', 'KZ', 'state_province_region', 'countries', 'countries_kazakhstan', $values_counter++];
$parameter[] = [0, 'Kenya', 'KE', 'state_province_region', 'countries', 'countries_kenya', $values_counter++];
$parameter[] = [0, 'Kiribati', 'KI', 'state_province_region', 'countries', 'countries_kiribati', $values_counter++];
$parameter[] = [0, 'Kosovo', 'XK', 'state_province_region', 'countries', 'countries_kosovo', $values_counter++];
$parameter[] = [0, 'Kuwait', 'KW', 'state_province_region', 'countries', 'countries_kuwait', $values_counter++];
$parameter[] = [0, 'Kyrgyzstan', 'KG', 'state_province_region', 'countries', 'countries_kyrgyzstan', $values_counter++];
$parameter[] = [0, 'Laos', 'LA', 'state_province_region', 'countries', 'countries_laos', $values_counter++];
$parameter[] = [0, 'Latvia', 'LV', 'state_province_region', 'countries', 'countries_latvia', $values_counter++];
$parameter[] = [0, 'Lebanon', 'LB', 'state_province_region', 'countries', 'countries_lebanon', $values_counter++];
$parameter[] = [0, 'Lesotho', 'LS', 'state_province_region', 'countries', 'countries_lesotho', $values_counter++];
$parameter[] = [0, 'Liberia', 'LR', 'state_province_region', 'countries', 'countries_liberia', $values_counter++];
$parameter[] = [0, 'Libya', 'LY', 'state_province_region', 'countries', 'countries_libya', $values_counter++];
$parameter[] = [0, 'Liechtenstein', 'LI', 'state_province_region', 'countries', 'countries_liechtenstein', $values_counter++];
$parameter[] = [0, 'Lithuania', 'LT', 'state_province_region', 'countries', 'countries_lithuania', $values_counter++];
$parameter[] = [0, 'Luxembourg', 'LU', 'state_province_region', 'countries', 'countries_luxembourg', $values_counter++];
$parameter[] = [0, 'Macao SAR', 'MO', 'state_province_region', 'countries', 'countries_macao_sar', $values_counter++];
$parameter[] = [0, 'Madagascar', 'MG', 'state_province_region', 'countries', 'countries_madagascar', $values_counter++];
$parameter[] = [0, 'Malawi', 'MW', 'state_province_region', 'countries', 'countries_malawi', $values_counter++];
$parameter[] = [0, 'Malaysia', 'MY', 'state_province_region', 'countries', 'countries_malaysia', $values_counter++];
$parameter[] = [0, 'Maldives', 'MV', 'state_province_region', 'countries', 'countries_maldives', $values_counter++];
$parameter[] = [0, 'Mali', 'ML', 'state_province_region', 'countries', 'countries_mali', $values_counter++];
$parameter[] = [0, 'Malta', 'MT', 'state_province_region', 'countries', 'countries_malta', $values_counter++];
$parameter[] = [0, 'Marshall Islands', 'MH', 'state_province_region', 'countries', 'countries_marshall_islands', $values_counter++];
$parameter[] = [0, 'Mauritania', 'MR', 'state_province_region', 'countries', 'countries_mauritania', $values_counter++];
$parameter[] = [0, 'Mauritius', 'MU', 'state_province_region', 'countries', 'countries_mauritius', $values_counter++];
$parameter[] = [0, 'Mexico', 'MX', 'state_province_region', 'countries', 'countries_mexico', $values_counter++];
$parameter[] = [0, 'Micronesia', 'FM', 'state_province_region', 'countries', 'countries_micronesia', $values_counter++];
$parameter[] = [0, 'Moldova', 'MD', 'state_province_region', 'countries', 'countries_moldova', $values_counter++];
$parameter[] = [0, 'Monaco', 'MC', 'state_province_region', 'countries', 'countries_monaco', $values_counter++];
$parameter[] = [0, 'Mongolia', 'MN', 'state_province_region', 'countries', 'countries_mongolia', $values_counter++];
$parameter[] = [0, 'Montenegro', 'ME', 'state_province_region', 'countries', 'countries_montenegro', $values_counter++];
$parameter[] = [0, 'Morocco', 'MA', 'state_province_region', 'countries', 'countries_morocco', $values_counter++];
$parameter[] = [0, 'Mozambique', 'MZ', 'state_province_region', 'countries', 'countries_mozambique', $values_counter++];
$parameter[] = [0, 'Myanmar (formerly Burma)', 'MM', 'state_province_region', 'countries', 'countries_myanmar_formerly_burma', $values_counter++];
$parameter[] = [0, 'Namibia', 'NA', 'state_province_region', 'countries', 'countries_namibia', $values_counter++];
$parameter[] = [0, 'Nauru', 'NR', 'state_province_region', 'countries', 'countries_nauru', $values_counter++];
$parameter[] = [0, 'Nepal', 'NP', 'state_province_region', 'countries', 'countries_nepal', $values_counter++];
$parameter[] = [0, 'Netherlands', 'NL', 'state_province_region', 'countries', 'countries_netherlands', $values_counter++];
$parameter[] = [0, 'New Zealand', 'NZ', 'state_province_region', 'countries', 'countries_new_zealand', $values_counter++];
$parameter[] = [0, 'Nicaragua', 'NI', 'state_province_region', 'countries', 'countries_nicaragua', $values_counter++];
$parameter[] = [0, 'Niger', 'NE', 'state_province_region', 'countries', 'countries_niger', $values_counter++];
$parameter[] = [0, 'Nigeria', 'NG', 'state_province_region', 'countries', 'countries_nigeria', $values_counter++];
$parameter[] = [0, 'North Korea', 'KP', 'state_province_region', 'countries', 'countries_north_korea', $values_counter++];
$parameter[] = [0, 'North Macedonia', 'MK', 'state_province_region', 'countries', 'countries_north_macedonia', $values_counter++];
$parameter[] = [0, 'Norway', 'NO', 'state_province_region', 'countries', 'countries_norway', $values_counter++];
$parameter[] = [0, 'Oman', 'OM', 'state_province_region', 'countries', 'countries_oman', $values_counter++];
$parameter[] = [0, 'Pakistan', 'PK', 'state_province_region', 'countries', 'countries_pakistan', $values_counter++];
$parameter[] = [0, 'Palau', 'PW', 'state_province_region', 'countries', 'countries_palau', $values_counter++];
$parameter[] = [0, 'Palestine State', 'PS', 'state_province_region', 'countries', 'countries_palestine_state', $values_counter++];
$parameter[] = [0, 'Panama', 'PA', 'state_province_region', 'countries', 'countries_panama', $values_counter++];
$parameter[] = [0, 'Papua New Guinea', 'PG', 'state_province_region', 'countries', 'countries_papua_new_guinea', $values_counter++];
$parameter[] = [0, 'Paraguay', 'PY', 'state_province_region', 'countries', 'countries_paraguay', $values_counter++];
$parameter[] = [0, 'Peru', 'PE', 'state_province_region', 'countries', 'countries_peru', $values_counter++];
$parameter[] = [0, 'Philippines', 'PH', 'state_province_region', 'countries', 'countries_philippines', $values_counter++];
$parameter[] = [0, 'Poland', 'PL', 'state_province_region', 'countries', 'countries_poland', $values_counter++];
$parameter[] = [0, 'Portugal', 'PT', 'state_province_region', 'countries', 'countries_portugal', $values_counter++];
$parameter[] = [0, 'Qatar', 'QA', 'state_province_region', 'countries', 'countries_qatar', $values_counter++];
$parameter[] = [0, 'Romania', 'RO', 'state_province_region', 'countries', 'countries_romania', $values_counter++];
$parameter[] = [0, 'Russia', 'RU', 'state_province_region', 'countries', 'countries_russia', $values_counter++];
$parameter[] = [0, 'Rwanda', 'RW', 'state_province_region', 'countries', 'countries_rwanda', $values_counter++];
$parameter[] = [0, 'Saint Kitts and Nevis', 'KN', 'state_province_region', 'countries', 'countries_saint_kitts_and_nevis', $values_counter++];
$parameter[] = [0, 'Saint Lucia', 'LC', 'state_province_region', 'countries', 'countries_saint_lucia', $values_counter++];
$parameter[] = [0, 'Saint Vincent and the Grenadines', 'VC', 'state_province_region', 'countries', 'countries_saint_vincent_and_the_grenadines', $values_counter++];
$parameter[] = [0, 'Samoa', 'WS', 'state_province_region', 'countries', 'countries_samoa', $values_counter++];
$parameter[] = [0, 'San Marino', 'SM', 'state_province_region', 'countries', 'countries_san_marino', $values_counter++];
$parameter[] = [0, 'Sao Tome and Principe', 'ST', 'state_province_region', 'countries', 'countries_sao_tome_and_principe', $values_counter++];
$parameter[] = [0, 'Saudi Arabia', 'SA', 'state_province_region', 'countries', 'countries_saudi_arabia', $values_counter++];
$parameter[] = [0, 'Senegal', 'SN', 'state_province_region', 'countries', 'countries_senegal', $values_counter++];
$parameter[] = [0, 'Serbia', 'RS', 'state_province_region', 'countries', 'countries_serbia', $values_counter++];
$parameter[] = [0, 'Seychelles', 'SC', 'state_province_region', 'countries', 'countries_seychelles', $values_counter++];
$parameter[] = [0, 'Sierra Leone', 'SL', 'state_province_region', 'countries', 'countries_sierra_leone', $values_counter++];
$parameter[] = [0, 'Singapore', 'SG', 'state_province_region', 'countries', 'countries_singapore', $values_counter++];
$parameter[] = [0, 'Slovakia', 'SK', 'state_province_region', 'countries', 'countries_slovakia', $values_counter++];
$parameter[] = [0, 'Slovenia', 'SI', 'state_province_region', 'countries', 'countries_slovenia', $values_counter++];
$parameter[] = [0, 'Solomon Islands', 'SB', 'state_province_region', 'countries', 'countries_solomon_islands', $values_counter++];
$parameter[] = [0, 'Somalia', 'SO', 'state_province_region', 'countries', 'countries_somalia', $values_counter++];
$parameter[] = [0, 'South Africa', 'ZA', 'state_province_region', 'countries', 'countries_south_africa', $values_counter++];
$parameter[] = [0, 'South Korea', 'KR', 'state_province_region', 'countries', 'countries_south_korea', $values_counter++];
$parameter[] = [0, 'South Sudan', 'SS', 'state_province_region', 'countries', 'countries_south_sudan', $values_counter++];
$parameter[] = [0, 'Spain', 'ES', 'state_province_region', 'countries', 'countries_spain', $values_counter++];
$parameter[] = [0, 'Sri Lanka', 'LK', 'state_province_region', 'countries', 'countries_sri_lanka', $values_counter++];
$parameter[] = [0, 'Sudan', 'SD', 'state_province_region', 'countries', 'countries_sudan', $values_counter++];
$parameter[] = [0, 'Suriname', 'SR', 'state_province_region', 'countries', 'countries_suriname', $values_counter++];
$parameter[] = [0, 'Sweden', 'SE', 'state_province_region', 'countries', 'countries_sweden', $values_counter++];
$parameter[] = [0, 'Switzerland', 'CH', 'state_province_region', 'countries', 'countries_switzerland', $values_counter++];
$parameter[] = [0, 'Syria', 'SY', 'state_province_region', 'countries', 'countries_syria', $values_counter++];
$parameter[] = [0, 'Taiwan', 'TW', 'state_province_region', 'countries', 'countries_taiwan', $values_counter++];
$parameter[] = [0, 'Tajikistan', 'TJ', 'state_province_region', 'countries', 'countries_tajikistan', $values_counter++];
$parameter[] = [0, 'Tanzania', 'TZ', 'state_province_region', 'countries', 'countries_tanzania', $values_counter++];
$parameter[] = [0, 'Thailand', 'TH', 'state_province_region', 'countries', 'countries_thailand', $values_counter++];
$parameter[] = [0, 'Timor-Leste', 'TL', 'state_province_region', 'countries', 'countries_timor_leste', $values_counter++];
$parameter[] = [0, 'Togo', 'TG', 'state_province_region', 'countries', 'countries_togo', $values_counter++];
$parameter[] = [0, 'Tonga', 'TO', 'state_province_region', 'countries', 'countries_tonga', $values_counter++];
$parameter[] = [0, 'Trinidad and Tobago', 'TT', 'state_province_region', 'countries', 'countries_trinidad_and_tobago', $values_counter++];
$parameter[] = [0, 'Tunisia', 'TN', 'state_province_region', 'countries', 'countries_tunisia', $values_counter++];
$parameter[] = [0, 'Turkey', 'TR', 'state_province_region', 'countries', 'countries_turkey', $values_counter++];
$parameter[] = [0, 'Turkmenistan', 'TM', 'state_province_region', 'countries', 'countries_turkmenistan', $values_counter++];
$parameter[] = [0, 'Tuvalu', 'TV', 'state_province_region', 'countries', 'countries_tuvalu', $values_counter++];
$parameter[] = [0, 'Uganda', 'UG', 'state_province_region', 'countries', 'countries_uganda', $values_counter++];
$parameter[] = [0, 'Ukraine', 'UA', 'state_province_region', 'countries', 'countries_ukraine', $values_counter++];
$parameter[] = [0, 'United Arab Emirates', 'AE', 'state_province_region', 'countries', 'countries_united_arab_emirates', $values_counter++];
$parameter[] = [0, 'United Kingdom', 'GB', 'state_province_region', 'countries', 'countries_united_kingdom', $values_counter++];
$parameter[] = [0, 'United States', 'US', 'us_states', 'countries', 'countries_united_states', $values_counter++];
$parameter[] = [0, 'Uruguay', 'UY', 'state_province_region', 'countries', 'countries_uruguay', $values_counter++];
$parameter[] = [0, 'Uzbekistan', 'UZ', 'state_province_region', 'countries', 'countries_uzbekistan', $values_counter++];
$parameter[] = [0, 'Vanuatu', 'VU', 'state_province_region', 'countries', 'countries_vanuatu', $values_counter++];
$parameter[] = [0, 'Venezuela', 'VE', 'state_province_region', 'countries', 'countries_venezuela', $values_counter++];
$parameter[] = [0, 'Vietnam', 'VN', 'state_province_region', 'countries', 'countries_vietnam', $values_counter++];
$parameter[] = [0, 'Yemen', 'YE', 'state_province_region', 'countries', 'countries_yemen', $values_counter++];
$parameter[] = [0, 'Zambia', 'ZM', 'state_province_region', 'countries', 'countries_zambia', $values_counter++];
$parameter[] = [0, 'Zimbabwe', 'ZW', 'state_province_region', 'countries', 'countries_zimbabwe', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Only email me - do not block the IP Address', 'Email Me', '', 'site_security_email_block_ip', 'only_email_me', $values_counter++];
$parameter[] = [0, 'Email me and automatically block the IP Address', 'Email Me and Block IP', '', 'site_security_email_block_ip', 'email_me_and_block', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Hierarchy', 'Hierarchy', '', 'site_settings_url_structure', 'hierarchy', $values_counter++];
$parameter[] = [0, 'Flat', 'Flat', '', 'site_settings_url_structure', 'flat', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Active', 'Active', '', 'customer_leads_lead_status', 'active', $values_counter++];
$parameter[] = [0, 'Junk', 'Junk', '', 'customer_leads_lead_status', 'junk', $values_counter++];

$values_counter = 1;
$parameter[] = [0, ', (comma)', ',', '', 'currency_fractional_separator', 'comma_fractional_separator', $values_counter++];
$parameter[] = [0, '. (decimal point)', '.', '', 'currency_fractional_separator', 'decimal_point_fractional_separator', $values_counter++];

$values_counter = 1;
$parameter[] = [0, ', (comma)', ',', '', 'currency_thousand_separator', 'comma_thousand_separator', $values_counter++];
$parameter[] = [0, '. (decimal point)', '.', '', 'currency_thousand_separator', 'decimal_point_thousand_separator', $values_counter++];
$parameter[] = [0, "' (single quote)", "'", '', 'currency_thousand_separator', 'single_quote_thousand_separator', $values_counter++];
$parameter[] = [0, '" " (space)', ' ', '', 'currency_thousand_separator', 'space_thousand_separator', $values_counter++];

$values_counter = 1;
$parameter[] = [0, '1', '1', '', 'currency_zeros_after_fractional_separator', '1_zeros_after_fractional_separator', $values_counter++];
$parameter[] = [0, '2', '2', '', 'currency_zeros_after_fractional_separator', '2_zeros_after_fractional_separator', $values_counter++];
$parameter[] = [0, '3', '3', '', 'currency_zeros_after_fractional_separator', '3_zeros_after_fractional_separator', $values_counter++];
$parameter[] = [0, '4', '4', '', 'currency_zeros_after_fractional_separator', '4_zeros_after_fractional_separator', $values_counter++];
$parameter[] = [0, '5', '5', '', 'currency_zeros_after_fractional_separator', '5_zeros_after_fractional_separator', $values_counter++];
$parameter[] = [0, '6', '6', '', 'currency_zeros_after_fractional_separator', '6_zeros_after_fractional_separator', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Enabled', '1', '', 'urls_status', 'enabled_urls', $values_counter++];
$parameter[] = [0, 'Disabled', '2', '', 'urls_status', 'disabled_urls', $values_counter++];
$parameter[] = [0, 'Draft', '3', '', 'urls_status', 'draft_urls', $values_counter++];
$parameter[] = [0, 'Scheduled', '4', '', 'urls_status', 'scheduled_urls', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Select State', '', '', 'us_states', 'us_select_state', $values_counter++];
$parameter[] = [0, 'Alabama', 'AL', '', 'us_states', 'us_alabama', $values_counter++];
$parameter[] = [0, 'Alaska', 'AK', '', 'us_states', 'us_alaska', $values_counter++];
$parameter[] = [0, 'Arizona', 'AZ', '', 'us_states', 'us_arizona', $values_counter++];
$parameter[] = [0, 'Arkansas', 'AR', '', 'us_states', 'us_arkansas', $values_counter++];
$parameter[] = [0, 'California', 'CA', '', 'us_states', 'us_california', $values_counter++];
$parameter[] = [0, 'Colorado', 'CO', '', 'us_states', 'us_colorado', $values_counter++];
$parameter[] = [0, 'Connecticut', 'CT', '', 'us_states', 'us_connecticut', $values_counter++];
$parameter[] = [0, 'Delaware', 'DE', '', 'us_states', 'us_delaware', $values_counter++];
$parameter[] = [0, 'Florida', 'FL', '', 'us_states', 'us_florida', $values_counter++];
$parameter[] = [0, 'Georgia', 'GA', '', 'us_states', 'us_georgia', $values_counter++];
$parameter[] = [0, 'Hawaii', 'HI', '', 'us_states', 'us_hawaii', $values_counter++];
$parameter[] = [0, 'Idaho', 'ID', '', 'us_states', 'us_idaho', $values_counter++];
$parameter[] = [0, 'Illinois', 'IL', '', 'us_states', 'us_illinois', $values_counter++];
$parameter[] = [0, 'Indiana', 'IN', '', 'us_states', 'us_indiana', $values_counter++];
$parameter[] = [0, 'Iowa', 'IA', '', 'us_states', 'us_iowa', $values_counter++];
$parameter[] = [0, 'Kansas', 'KS', '', 'us_states', 'us_kansas', $values_counter++];
$parameter[] = [0, 'Kentucky', 'KY', '', 'us_states', 'us_kentucky', $values_counter++];
$parameter[] = [0, 'Louisiana', 'LA', '', 'us_states', 'us_louisiana', $values_counter++];
$parameter[] = [0, 'Maine', 'ME', '', 'us_states', 'us_maine', $values_counter++];
$parameter[] = [0, 'Maryland', 'MD', '', 'us_states', 'us_maryland', $values_counter++];
$parameter[] = [0, 'Massachusetts', 'MA', '', 'us_states', 'us_massachusetts', $values_counter++];
$parameter[] = [0, 'Michigan', 'MI', '', 'us_states', 'us_michigan', $values_counter++];
$parameter[] = [0, 'Minnesota', 'MN', '', 'us_states', 'us_minnesota', $values_counter++];
$parameter[] = [0, 'Mississippi', 'MS', '', 'us_states', 'us_mississippi', $values_counter++];
$parameter[] = [0, 'Missouri', 'MO', '', 'us_states', 'us_missouri', $values_counter++];
$parameter[] = [0, 'Montana', 'MT', '', 'us_states', 'us_montana', $values_counter++];
$parameter[] = [0, 'Nebraska', 'NE', '', 'us_states', 'us_nebraska', $values_counter++];
$parameter[] = [0, 'Nevada', 'NV', '', 'us_states', 'us_nevada', $values_counter++];
$parameter[] = [0, 'New Hampshire', 'NH', '', 'us_states', 'us_new_hampshire', $values_counter++];
$parameter[] = [0, 'New Jersey', 'NJ', '', 'us_states', 'us_new_jersey', $values_counter++];
$parameter[] = [0, 'New Mexico', 'NM', '', 'us_states', 'us_new_mexico', $values_counter++];
$parameter[] = [0, 'New York', 'NY', '', 'us_states', 'us_new_york', $values_counter++];
$parameter[] = [0, 'North Carolina', 'NC', '', 'us_states', 'us_north_carolina', $values_counter++];
$parameter[] = [0, 'North Dakota', 'ND', '', 'us_states', 'us_north_dakota', $values_counter++];
$parameter[] = [0, 'Ohio', 'OH', '', 'us_states', 'us_ohio', $values_counter++];
$parameter[] = [0, 'Oklahoma', 'OK', '', 'us_states', 'us_oklahoma', $values_counter++];
$parameter[] = [0, 'Oregon', 'OR', '', 'us_states', 'us_oregon', $values_counter++];
$parameter[] = [0, 'Pennsylvania', 'PA', '', 'us_states', 'us_pennsylvania', $values_counter++];
$parameter[] = [0, 'Rhode Island', 'RI', '', 'us_states', 'us_rhode_island', $values_counter++];
$parameter[] = [0, 'South Carolina', 'SC', '', 'us_states', 'us_south_carolina', $values_counter++];
$parameter[] = [0, 'South Dakota', 'SD', '', 'us_states', 'us_south_dakota', $values_counter++];
$parameter[] = [0, 'Tennessee', 'TN', '', 'us_states', 'us_tennessee', $values_counter++];
$parameter[] = [0, 'Texas', 'TX', '', 'us_states', 'us_texas', $values_counter++];
$parameter[] = [0, 'Utah', 'UT', '', 'us_states', 'us_utah', $values_counter++];
$parameter[] = [0, 'Vermont', 'VT', '', 'us_states', 'us_vermont', $values_counter++];
$parameter[] = [0, 'Virginia', 'VA', '', 'us_states', 'us_virginia', $values_counter++];
$parameter[] = [0, 'Washington', 'WA', '', 'us_states', 'us_washington', $values_counter++];
$parameter[] = [0, 'West Virginia', 'WV', '', 'us_states', 'us_west_virginia', $values_counter++];
$parameter[] = [0, 'Wisconsin', 'WI', '', 'us_states', 'us_wisconsin', $values_counter++];
$parameter[] = [0, 'Wyoming', 'WY', '', 'us_states', 'us_wyoming', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Amharic (am)', 'am', '', 'languages', 'languages_am', $values_counter++];
$parameter[] = [0, 'Arabic (ar)', 'ar', '', 'languages', 'languages_ar', $values_counter++];
$parameter[] = [0, 'Basque (eu)', 'eu', '', 'languages', 'languages_eu', $values_counter++];
$parameter[] = [0, 'Bengali (bn)', 'bn', '', 'languages', 'languages_bn', $values_counter++];
$parameter[] = [0, 'Bulgarian (bg)', 'bg', '', 'languages', 'languages_bg', $values_counter++];
$parameter[] = [0, 'Catalan (ca)', 'ca', '', 'languages', 'languages_ca', $values_counter++];
$parameter[] = [0, 'Cherokee (chr)', 'chr', '', 'languages', 'languages_chr', $values_counter++];
$parameter[] = [0, 'Chinese (PRC) (zh-CN)', 'zh-CN', '', 'languages', 'languages_zh_cn', $values_counter++];
$parameter[] = [0, 'Chinese (Taiwan) (zh-TW)', 'zh-TW', '', 'languages', 'languages_zh_tw', $values_counter++];
$parameter[] = [0, 'Croatian (hr)', 'hr', '', 'languages', 'languages_hr', $values_counter++];
$parameter[] = [0, 'Czech (cs)', 'cs', '', 'languages', 'languages_cs', $values_counter++];
$parameter[] = [0, 'Danish (da)', 'da', '', 'languages', 'languages_da', $values_counter++];
$parameter[] = [0, 'Dutch (nl)', 'nl', '', 'languages', 'languages_nl', $values_counter++];
$parameter[] = [0, 'English (UK) (en-GB)', 'en-GB', '', 'languages', 'languages_en_gb', $values_counter++];
$parameter[] = [0, 'English (US) (en)', 'en', '', 'languages', 'languages_en_us', $values_counter++];
$parameter[] = [0, 'Estonian (et)', 'et', '', 'languages', 'languages_et', $values_counter++];
$parameter[] = [0, 'Filipino (fil)', 'fil', '', 'languages', 'languages_fil', $values_counter++];
$parameter[] = [0, 'Finnish (fi)', 'fi', '', 'languages', 'languages_fi', $values_counter++];
$parameter[] = [0, 'French (fr)', 'fr', '', 'languages', 'languages_fr', $values_counter++];
$parameter[] = [0, 'German (de)', 'de', '', 'languages', 'languages_de', $values_counter++];
$parameter[] = [0, 'Greek (el)', 'el', '', 'languages', 'languages_el', $values_counter++];
$parameter[] = [0, 'Gujarati (gu)', 'gu', '', 'languages', 'languages_gu', $values_counter++];
$parameter[] = [0, 'Hebrew (iw)', 'iw', '', 'languages', 'languages_iw', $values_counter++];
$parameter[] = [0, 'Hindi (hi)', 'hi', '', 'languages', 'languages_hi', $values_counter++];
$parameter[] = [0, 'Hungarian (hu)', 'hu', '', 'languages', 'languages_hu', $values_counter++];
$parameter[] = [0, 'Icelandic (is)', 'is', '', 'languages', 'languages_is', $values_counter++];
$parameter[] = [0, 'Indonesian (id)', 'id', '', 'languages', 'languages_id', $values_counter++];
$parameter[] = [0, 'Italian (it)', 'it', '', 'languages', 'languages_it', $values_counter++];
$parameter[] = [0, 'Japanese (ja)', 'ja', '', 'languages', 'languages_ja', $values_counter++];
$parameter[] = [0, 'Kannada (kn)', 'kn', '', 'languages', 'languages_kn', $values_counter++];
$parameter[] = [0, 'Korean (ko)', 'ko', '', 'languages', 'languages_ko', $values_counter++];
$parameter[] = [0, 'Latvian (lv)', 'lv', '', 'languages', 'languages_lv', $values_counter++];
$parameter[] = [0, 'Lithuanian (lt)', 'lt', '', 'languages', 'languages_lt', $values_counter++];
$parameter[] = [0, 'Malay (ms)', 'ms', '', 'languages', 'languages_ms', $values_counter++];
$parameter[] = [0, 'Malayalam (ml)', 'ml', '', 'languages', 'languages_ml', $values_counter++];
$parameter[] = [0, 'Marathi (mr)', 'mr', '', 'languages', 'languages_mr', $values_counter++];
$parameter[] = [0, 'Norwegian (no)', 'no', '', 'languages', 'languages_no', $values_counter++];
$parameter[] = [0, 'Polish (pl)', 'pl', '', 'languages', 'languages_pl', $values_counter++];
$parameter[] = [0, 'Portuguese (Brazil) (pt-BR)', 'pt-BR', '', 'languages', 'languages_pt_br', $values_counter++];
$parameter[] = [0, 'Portuguese (Portugal) (pt-PT)', 'pt-PT', '', 'languages', 'languages_pt_pt', $values_counter++];
$parameter[] = [0, 'Romanian (ro)', 'ro', '', 'languages', 'languages_ro', $values_counter++];
$parameter[] = [0, 'Serbian (sr)', 'sr', '', 'languages', 'languages_sr', $values_counter++];
$parameter[] = [0, 'Slovak (sk)', 'sk', '', 'languages', 'languages_sk', $values_counter++];
$parameter[] = [0, 'Slovenian (sl)', 'sl', '', 'languages', 'languages_sl', $values_counter++];
$parameter[] = [0, 'Spanish (es)', 'es', '', 'languages', 'languages_es', $values_counter++];
$parameter[] = [0, 'Swahili (sw)', 'sw', '', 'languages', 'languages_sw', $values_counter++];
$parameter[] = [0, 'Swedish (sv)', 'sv', '', 'languages', 'languages_sv', $values_counter++];
$parameter[] = [0, 'Tamil (ta)', 'ta', '', 'languages', 'languages_ta', $values_counter++];
$parameter[] = [0, 'Telugu (te)', 'te', '', 'languages', 'languages_te', $values_counter++];
$parameter[] = [0, 'Thai (th)', 'th', '', 'languages', 'languages_th', $values_counter++];
$parameter[] = [0, 'Turkish (tr)', 'tr', '', 'languages', 'languages_tr', $values_counter++];
$parameter[] = [0, 'Ukrainian (uk)', 'uk', '', 'languages', 'languages_uk', $values_counter++];
$parameter[] = [0, 'Urdu (ur)', 'ur', '', 'languages', 'languages_ur', $values_counter++];
$parameter[] = [0, 'Vietnamese (vi)', 'vi', '', 'languages', 'languages_vi', $values_counter++];
$parameter[] = [0, 'Welsh (cy)', 'cy', '', 'languages', 'languages_cy', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'jpg', 'image/jpeg', '', 'accepted_image_extension_types', 'jpg', $values_counter++];
$parameter[] = [0, 'jpeg', 'image/jpeg', '', 'accepted_image_extension_types', 'jpeg', $values_counter++];
$parameter[] = [0, 'gif', 'image/gif', '', 'accepted_image_extension_types', 'gif', $values_counter++];
$parameter[] = [0, 'png', 'image/png', '', 'accepted_image_extension_types', 'png', $values_counter++];
$parameter[] = [0, 'svg', 'image/svg+xml', '', 'accepted_image_extension_types', 'svg', $values_counter++];
$parameter[] = [0, 'icon', 'image/x-icon', '', 'accepted_image_extension_types', 'icon', $values_counter++];
$parameter[] = [0, 'tif', 'image/tiff', '', 'accepted_image_extension_types', 'tif', $values_counter++];
$parameter[] = [0, 'tiff', 'image/tiff', '', 'accepted_image_extension_types', 'tiff', $values_counter++];
$parameter[] = [0, 'webp', 'image/webp', '', 'accepted_image_extension_types', 'webp', $values_counter++];
$parameter[] = [0, 'avif', 'image/avif', '', 'accepted_image_extension_types', 'avif', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'mp3', 'audio/mpeg', '', 'accepted_video_extension_types', 'mp3', $values_counter++];
$parameter[] = [0, 'mp4', 'video/mp4', '','accepted_video_extension_types', 'mp4', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'doc', 'application/msword', '', 'accepted_file_extension_types', 'file_extension_types_doc', $values_counter++];
$parameter[] = [0, 'docx', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', '', 'accepted_file_extension_types', 'file_extension_types_docx', $values_counter++];
$parameter[] = [0, 'pdf', 'application/pdf', '', 'accepted_file_extension_types', 'file_extension_types_pdf', $values_counter++];
$parameter[] = [0, 'ppt', 'application/vnd.ms-powerpoint', '', 'accepted_file_extension_types', 'ppt', $values_counter++];
$parameter[] = [0, 'pptx', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', '', 'accepted_file_extension_types', 'file_extension_types_pptx', $values_counter++];
$parameter[] = [0, 'txt', 'text/plain', '', 'accepted_file_extension_types', 'file_extension_types_txt', $values_counter++];
$parameter[] = [0, 'xls', 'application/vnd.ms-excel', '', 'accepted_file_extension_types', 'file_extension_types_xls', $values_counter++];
$parameter[] = [0, 'xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', '', 'accepted_file_extension_types', 'file_extension_types_xlsx', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Sort', 'sort', '', 'sort_or_drag_and_drop', 'sort', $values_counter++];
$parameter[] = [0, 'Drag & Drop', 'dragdrop', '', 'sort_or_drag_and_drop', 'drag_and_drop', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Submit', 'submit', '', 'forms_submit_button_type', 'type_submit', $values_counter++];
$parameter[] = [0, 'Button', 'button', '', 'forms_submit_button_type', 'type_button', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Table', 'table', '', 'admin_pages_type', 'admin_pages_type_table', $values_counter++];
$parameter[] = [0, 'Add', 'add', '', 'admin_pages_type', 'admin_pages_type_add', $values_counter++];
$parameter[] = [0, 'Edit', 'edit', '', 'admin_pages_type', 'admin_pages_type_edit', $values_counter++];
$parameter[] = [0, 'Static', 'static', '', 'admin_pages_type', 'admin_pages_type_static', $values_counter++];
$parameter[] = [0, 'Blank', 'blank', '', 'admin_pages_type', 'admin_pages_type_blank', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Admin Directory URL Name', 'adminDirectoryUrlName', '', 'admin_fields_display_as', 'display_as_admin_directory_url_name', $values_counter++];
$parameter[] = [0, 'Admin Fields IDs', 'adminFieldsIds', '', 'admin_fields_display_as', 'display_as_admin_fields_ids', $values_counter++];
$parameter[] = [0, 'Assigned To', 'assignedTo', '', 'admin_fields_display_as', 'display_as_assigned_to', $values_counter++];
$parameter[] = [0, 'Canonical URL', 'canonicalUrl', '', 'admin_fields_display_as', 'display_as_canonical_url', $values_counter++];
$parameter[] = [0, 'Checkbox/ID', 'checkboxId', '', 'admin_fields_display_as', 'display_as_checkbox_id', $values_counter++];
$parameter[] = [0, 'Checkbox/Value', 'checkboxValue', '', 'admin_fields_display_as', 'display_as_checkbox_value', $values_counter++];
$parameter[] = [0, 'Child Table Name', 'childTableName', '', 'admin_fields_display_as', 'display_as_child_table_name', $values_counter++];
$parameter[] = [0, 'Click Path', 'clickPath', '', 'admin_fields_display_as', 'display_as_click_path', $values_counter++];
$parameter[] = [0, 'Color Number', 'colorNumber', '', 'admin_fields_display_as', 'display_as_color_number', $values_counter++];
$parameter[] = [0, 'Created By', 'createdBy', '', 'admin_fields_display_as', 'display_as_created_by', $values_counter++];
$parameter[] = [0, 'Created Date', 'createdDate', '', 'admin_fields_display_as', 'display_as_created_date', $values_counter++];
$parameter[] = [0, 'Custom Field Name', 'customFieldName', '', 'admin_fields_display_as', 'display_as_custom_field_name', $values_counter++];
$parameter[] = [0, 'Custom Fields Option Cost', 'customFieldsOptionCost', '', 'admin_fields_display_as', 'display_as_custom_fields_option_cost', $values_counter++];
$parameter[] = [0, 'Custom Fields Option Price', 'customFieldsOptionPrice', '', 'admin_fields_display_as', 'display_as_custom_fields_option_price', $values_counter++];
$parameter[] = [0, 'Custom Field Type', 'customFieldType', '', 'admin_fields_display_as', 'display_as_custom_field_type', $values_counter++];
$parameter[] = [0, 'Custom Link', 'customLink', '', 'admin_fields_display_as', 'display_as_custom_link', $values_counter++];
$parameter[] = [0, 'Database Table Name', 'databaseTableName', '', 'admin_fields_display_as', 'display_as_database_table_name', $values_counter++];
$parameter[] = [0, 'Date', 'dateField', '', 'admin_fields_display_as', 'display_as_date', $values_counter++];
$parameter[] = [0, 'Date From', 'dateFrom', '', 'admin_fields_display_as', 'display_as_date_from', $values_counter++];
$parameter[] = [0, 'Date To', 'dateTo', '', 'admin_fields_display_as', 'display_as_date_to', $values_counter++];
$parameter[] = [0, 'Days Of The Week Open', 'daysOfTheWeekOpen', '', 'admin_fields_display_as', 'display_as_days_of_the_week_open', $values_counter++];
$parameter[] = [0, 'Display Color Swatch', 'displayColorSwatch', '', 'admin_fields_display_as', 'display_as_color_swatch', $values_counter++];
$parameter[] = [0, 'Display Post In', 'displayPostIn', '', 'admin_fields_display_as', 'display_as_display_post_in', $values_counter++];
$parameter[] = [0, 'Dropdown/ID', 'dropdownId', '', 'admin_fields_display_as', 'display_as_dropdown_id', $values_counter++];
$parameter[] = [0, 'Dropdown/ID with Edit as Text', 'dropdownIdWithEditAsText', '', 'admin_fields_display_as', 'display_as_dropdown_id_with_edit_as_text', $values_counter++];
$parameter[] = [0, 'Dropdown/Value', 'dropdownValue', '', 'admin_fields_display_as', 'display_as_dropdown_value', $values_counter++];
$parameter[] = [0, 'Dropdown/Value with Edit as Text', 'dropdownValueWithEditAsText', '', 'admin_fields_display_as', 'display_as_dropdown_value_with_edit_as_text', $values_counter++];
$parameter[] = [0, 'Embed Custom Field', 'embedCustomField', '', 'admin_fields_display_as', 'display_as_embed_custom_field', $values_counter++];
$parameter[] = [0, 'Embed Form', 'embedForm', '', 'admin_fields_display_as', 'display_as_embed_form', $values_counter++];
$parameter[] = [0, 'Embed Media', 'embedMedia', '', 'admin_fields_display_as', 'display_as_embed_media', $values_counter++];
$parameter[] = [0, 'Embed Menu', 'embedMenu', '', 'admin_fields_display_as', 'display_as_embed_menu', $values_counter++];
$parameter[] = [0, 'Embed Slider', 'embedSlider', '', 'admin_fields_display_as', 'display_as_embed_slider', $values_counter++];
$parameter[] = [0, 'File Code', 'fileCode', '', 'admin_fields_display_as', 'display_as_file_code', $values_counter++];
$parameter[] = [0, 'Flat URL', 'flatUrl', '', 'admin_fields_display_as', 'display_as_flat_url', $values_counter++];
$parameter[] = [0, 'Global URL Extension', 'globalUrlExtension', '', 'admin_fields_display_as', 'display_as_global_url_extension', $values_counter++];
$parameter[] = [0, 'Height', 'height', '', 'admin_fields_display_as', 'display_as_height', $values_counter++];
$parameter[] = [0, 'Hidden', 'hidden', '', 'admin_fields_display_as', 'display_as_hidden', $values_counter++];
$parameter[] = [0, 'Hidden On Add Textfield On Edit', 'hiddenOnAddTextfieldOnEdit', '', 'admin_fields_display_as', 'display_as_hidden_on_add_textfield_on_edit', $values_counter++];
$parameter[] = [0, 'Hierarchy URL / URL Dropdown', 'hierarchyUrl', '', 'admin_fields_display_as', 'display_as_hierarchy_url_url_dropdown', $values_counter++];
$parameter[] = [0, 'Item Number', 'itemNumber', '', 'admin_fields_display_as', 'display_as_item_number', $values_counter++];
$parameter[] = [0, 'Lead', 'lead', '', 'admin_fields_display_as', 'lead', $values_counter++];
$parameter[] = [0, 'Links To', 'linksTo', '', 'admin_fields_display_as', 'display_as_links_to', $values_counter++];
$parameter[] = [0, 'Link Type', 'linkType', '', 'admin_fields_display_as', 'display_as_link_type', $values_counter++];
$parameter[] = [0, 'License Billing Line Items', 'licenseBillingLineItems', '', 'admin_fields_display_as', 'display_as_license_billing_line_items', $values_counter++];
$parameter[] = [0, 'Media Tag', 'mediaTag', '', 'admin_fields_display_as', 'display_as_media_tag', $values_counter++];
$parameter[] = [0, 'Media Type', 'mediaType', '', 'admin_fields_display_as', 'display_as_media_type', $values_counter++];
$parameter[] = [0, 'Media URL', 'mediaUrl', '', 'admin_fields_display_as', 'display_as_media_url', $values_counter++];
$parameter[] = [0, 'Meta Robots', 'metaRobots', '', 'admin_fields_display_as', 'display_as_meta_robots', $values_counter++];
$parameter[] = [0, 'Media', 'multipleMedia', '', 'admin_fields_display_as', 'display_as_media', $values_counter++];
$parameter[] = [0, 'Option Data', 'optionData', '', 'admin_fields_display_as', 'display_as_option_data', $values_counter++];
$parameter[] = [0, 'Original Media', 'originalMedia', '', 'admin_fields_display_as', 'display_as_original_media', $values_counter++];
$parameter[] = [0, 'Original Media ID', 'originalMediaId', '', 'admin_fields_display_as', 'display_as_original_media_id', $values_counter++];
$parameter[] = [0, 'Parent ID', 'parentId', '', 'admin_fields_display_as', 'display_as_parent_id', $values_counter++];
$parameter[] = [0, 'Parent Table ID', 'parentTableId', '', 'admin_fields_display_as', 'display_as_parent_table_id', $values_counter++];
$parameter[] = [0, 'Parent Table Name', 'parentTableName', '', 'admin_fields_display_as', 'display_as_parent_table_name', $values_counter++];
$parameter[] = [0, 'Password', 'password', '', 'admin_fields_display_as', 'display_as_password', $values_counter++];
$parameter[] = [0, 'Password and Confirm Password', 'passwordAndConfirmPassword', '', 'admin_fields_display_as', 'display_as_password_and_confirm_password', $values_counter++];
$parameter[] = [0, 'Permissions', 'permissions', '', 'admin_fields_display_as', 'display_as_permissions', $values_counter++];
$parameter[] = [0, 'PHP Array For Template', 'phpArrayForTemplate', '', 'admin_fields_display_as', 'display_as_php_array_for_template', $values_counter++];
$parameter[] = [0, 'Price', 'price', '', 'admin_fields_display_as', 'display_as_price', $values_counter++];
$parameter[] = [0, 'Price As Text', 'priceAsText', '', 'admin_fields_display_as', 'display_as_price_as_text', $values_counter++];
$parameter[] = [0, 'Scheduled Date', 'scheduledDate', '', 'admin_fields_display_as', 'display_as_scheduled_date', $values_counter++];
$parameter[] = [0, 'Select File', 'selectFile', '', 'admin_fields_display_as', 'display_as_select_file', $values_counter++];
$parameter[] = [0, 'SEO Score', 'seoScore', '', 'admin_fields_display_as', 'display_as_seo_score', $values_counter++];
$parameter[] = [0, 'Single Media', 'singleMedia', '', 'admin_fields_display_as', 'display_as_single_media', $values_counter++];
$parameter[] = [0, 'Site ID', 'siteId', '', 'admin_fields_display_as', 'display_as_site_id', $values_counter++];
$parameter[] = [0, 'Sub Items', 'subItems', '', 'admin_fields_display_as', 'display_as_sub_items', $values_counter++];
$parameter[] = [0, 'Table Name', 'tableName', '', 'admin_fields_display_as', 'display_as_table_name', $values_counter++];
$parameter[] = [0, 'Template', 'template', '', 'admin_fields_display_as', 'display_as_template', $values_counter++];
$parameter[] = [0, 'Text', 'text', '', 'admin_fields_display_as', 'display_as_text', $values_counter++];
$parameter[] = [0, 'Textarea', 'textarea', '', 'admin_fields_display_as', 'display_as_textarea', $values_counter++];
$parameter[] = [0, 'Textarea With Editor', 'textareaWithEditor', '', 'admin_fields_display_as', 'display_as_textarea_with_wditor', $values_counter++];
$parameter[] = [0, 'Text As Percentage', 'textAsPercentage', '', 'admin_fields_display_as', 'display_as_text_as_percentage', $values_counter++];
$parameter[] = [0, 'Textfield', 'textfield', '', 'admin_fields_display_as', 'display_as_textfield', $values_counter++];
$parameter[] = [0, 'Textfield No Edit', 'textfieldNoEdit', '', 'admin_fields_display_as', 'display_as_textfield_no_edit', $values_counter++];
$parameter[] = [0, 'Thank You Page URL', 'thankYouPageUrl', '', 'admin_fields_display_as', 'display_as_thank_you_page_url', $values_counter++];
$parameter[] = [0, 'Timezone', 'timezone', '', 'admin_fields_display_as', 'display_as_timezone', $values_counter++];
$parameter[] = [0, 'Top IP Addresses', 'topIpAddresses', '', 'admin_fields_display_as', 'display_as_top_ip_addresses', $values_counter++];
$parameter[] = [0, 'Updated By', 'updatedBy', '', 'admin_fields_display_as', 'display_as_updated_by', $values_counter++];
$parameter[] = [0, 'Updated Date', 'updatedDate', '', 'admin_fields_display_as', 'display_as_updated_date', $values_counter++];
$parameter[] = [0, 'URL Extension', 'urlExtension', '', 'admin_fields_display_as', 'display_as_url_extension', $values_counter++];
$parameter[] = [0, 'Width', 'width', '', 'admin_fields_display_as', 'display_as_width', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Dropdown/ID', 'dropdownId', '', 'admin_fields_search_as', 'search_as_dropdown_id', $values_counter++];
$parameter[] = [0, 'Dropdown/Value', 'dropdownValue', '', 'admin_fields_search_as', 'search_as_dropdown_value', $values_counter++];
$parameter[] = [0, 'Date Range', 'dateRange', '', 'admin_fields_search_as', 'search_as_date_range', $values_counter++];
$parameter[] = [0, 'Links To', 'linksTo', '', 'admin_fields_search_as', 'search_as_links_to', $values_counter++];
$parameter[] = [0, 'Price', 'price', '', 'admin_fields_search_as', 'search_as_price', $values_counter++];
$parameter[] = [0, 'Table Name', 'tableName', '', 'admin_fields_search_as', 'search_as_table_name', $values_counter++];
$parameter[] = [0, 'Template', 'template', '', 'admin_fields_search_as', 'search_as_template', $values_counter++];
$parameter[] = [0, 'Textfield', 'textfield', '', 'admin_fields_search_as', 'search_as_textfield', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'char(32)', 'char(32)', '', 'admin_fields_data_type', 'admin_fields_data_type_char_32', $values_counter++];
$parameter[] = [0, 'char(64)', 'char(64)', '', 'admin_fields_data_type', 'admin_fields_data_type_char_64', $values_counter++];
$parameter[] = [0, 'date', 'date', '', 'admin_fields_data_type', 'admin_fields_data_type_date', $values_counter++];
$parameter[] = [0, 'datetime', 'datetime', '', 'admin_fields_data_type', 'admin_fields_data_type_datetime', $values_counter++];
$parameter[] = [0, 'decimal(2,1)', 'decimal(2,1)', '', 'admin_fields_data_type', 'admin_fields_data_type_decimal_2_1', $values_counter++];
$parameter[] = [0, 'decimal(10,2)', 'decimal(10,2)', '', 'admin_fields_data_type', 'admin_fields_data_type_decimal_10_2', $values_counter++];
$parameter[] = [0, 'decimal(16,6)', 'decimal(16,6)', '', 'admin_fields_data_type', 'admin_fields_data_type_decimal_16_6', $values_counter++];
$parameter[] = [0, 'int', 'int', '', 'admin_fields_data_type', 'admin_fields_data_type_int', $values_counter++];
$parameter[] = [0, 'longtext', 'longtext', '', 'admin_fields_data_type', 'admin_fields_data_type_longtext', $values_counter++];
$parameter[] = [0, 'tinyint(1)', 'tinyint(1)', '', 'admin_fields_data_type', 'admin_fields_data_type_tinyint_1', $values_counter++];
$parameter[] = [0, 'text', 'text', '', 'admin_fields_data_type', 'admin_fields_data_type_text', $values_counter++];
$parameter[] = [0, 'varchar(3)', 'varchar(3)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_3', $values_counter++];
$parameter[] = [0, 'varchar(5)', 'varchar(5)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_5', $values_counter++];
$parameter[] = [0, 'varchar(10)', 'varchar(10)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_10', $values_counter++];
$parameter[] = [0, 'varchar(20)', 'varchar(20)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_20', $values_counter++];
$parameter[] = [0, 'varchar(25)', 'varchar(25)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_25', $values_counter++];
$parameter[] = [0, 'varchar(30)', 'varchar(30)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_30', $values_counter++];
$parameter[] = [0, 'varchar(40)', 'varchar(40)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_40', $values_counter++];
$parameter[] = [0, 'varchar(50)', 'varchar(50)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_50', $values_counter++];
$parameter[] = [0, 'varchar(64)', 'varchar(64)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_64', $values_counter++];
$parameter[] = [0, 'varchar(100)', 'varchar(100)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_100', $values_counter++];
$parameter[] = [0, 'varchar(128)', 'varchar(128)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_128', $values_counter++];
$parameter[] = [0, 'varchar(255)', 'varchar(255)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_255', $values_counter++];
$parameter[] = [0, 'varchar(512)', 'varchar(512)', '', 'admin_fields_data_type', 'admin_fields_data_type_varchar_512', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', 'CHARACTER SET `utf8mb4` COLLATE `utf8mb4_0900_ai_ci`', '', 'database_character_set_and_collate', 'character_set_utf8mb4_collate_utf8mb4_0900_ai_ci', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'AUD', 'AUD', '', 'currency_types', 'aud', $values_counter++];
$parameter[] = [0, 'BRL', 'BRL', '', 'currency_types', 'brl', $values_counter++];
$parameter[] = [0, 'CAD', 'CAD', '', 'currency_types', 'cad', $values_counter++];
$parameter[] = [0, 'CHF', 'CHF', '', 'currency_types', 'chf', $values_counter++];
$parameter[] = [0, 'CNY', 'CNY', '', 'currency_types', 'cny', $values_counter++];
$parameter[] = [0, 'DKK', 'DKK', '', 'currency_types', 'dkk', $values_counter++];
$parameter[] = [0, 'EUR', 'EUR', '', 'currency_types', 'eur', $values_counter++];
$parameter[] = [0, 'GBP', 'GBP', '', 'currency_types', 'gbp', $values_counter++];
$parameter[] = [0, 'HKD', 'HKD', '', 'currency_types', 'hkd', $values_counter++];
$parameter[] = [0, 'IDR', 'IDR', '', 'currency_types', 'idr', $values_counter++];
$parameter[] = [0, 'INR', 'INR', '', 'currency_types', 'inr', $values_counter++];
$parameter[] = [0, 'JPY', 'JPY', '', 'currency_types', 'jpy', $values_counter++];
$parameter[] = [0, 'KRW', 'KRW', '', 'currency_types', 'krw', $values_counter++];
$parameter[] = [0, 'MXN', 'MXN', '', 'currency_types', 'mxn', $values_counter++];
$parameter[] = [0, 'MYR', 'MYR', '', 'currency_types', 'myr', $values_counter++];
$parameter[] = [0, 'NOK', 'NOK', '', 'currency_types', 'nok', $values_counter++];
$parameter[] = [0, 'NZD', 'NZD', '', 'currency_types', 'nzd', $values_counter++];
$parameter[] = [0, 'PLN', 'PLN', '', 'currency_types', 'pln', $values_counter++];
$parameter[] = [0, 'RUB', 'RUB', '', 'currency_types', 'rub', $values_counter++];
$parameter[] = [0, 'SAR', 'SAR', '', 'currency_types', 'sar', $values_counter++];
$parameter[] = [0, 'SEK', 'SEK', '', 'currency_types', 'sek', $values_counter++];
$parameter[] = [0, 'SGD', 'SGD', '', 'currency_types', 'sgd', $values_counter++];
$parameter[] = [0, 'THB', 'THB', '', 'currency_types', 'thb', $values_counter++];
$parameter[] = [0, 'TRY', 'TRY', '', 'currency_types', 'try', $values_counter++];
$parameter[] = [0, 'USD', 'USD', '', 'currency_types', 'usd', $values_counter++];
$parameter[] = [0, 'ZAR', 'ZAR', '', 'currency_types', 'zar', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Select Province', '', '', 'ca_province', 'ca_select_province', $values_counter++];
$parameter[] = [0, 'Alberta', 'AB', '', 'ca_province', 'ca_alberta', $values_counter++];
$parameter[] = [0, 'British Columbia', 'BC', '', 'ca_province', 'ca_british_columbia', $values_counter++];
$parameter[] = [0, 'Manitoba', 'MB', '', 'ca_province', 'ca_manitoba', $values_counter++];
$parameter[] = [0, 'New Brunswick', 'NB', '', 'ca_province', 'ca_new_brunswick', $values_counter++];
$parameter[] = [0, 'Newfoundland and Labrador', 'NL', '', 'ca_province', 'ca_newfoundland_and_labrador', $values_counter++];
$parameter[] = [0, 'Northwest Territories', 'NT', '', 'ca_province', 'ca_northwest_territories', $values_counter++];
$parameter[] = [0, 'Nova Scotia', 'NS', '', 'ca_province', 'ca_nova_scotia', $values_counter++];
$parameter[] = [0, 'Nunavut', 'NU', '', 'ca_province', 'ca_nunavut', $values_counter++];
$parameter[] = [0, 'Ontario', 'ON', '', 'ca_province', 'ca_ontario', $values_counter++];
$parameter[] = [0, 'Prince Edward Island', 'PE', '', 'ca_province', 'ca_prince_edward_island', $values_counter++];
$parameter[] = [0, 'Quebec', 'QC', '', 'ca_province', 'ca_quebec', $values_counter++];
$parameter[] = [0, 'Saskatchewan', 'SK', '', 'ca_province', 'ca_saskatchewan', $values_counter++];
$parameter[] = [0, 'Yukon', 'YT', '', 'ca_province', 'ca_yukon', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'All', 'all', '', 'templates_types', 'templates_types_all', $values_counter++];
$parameter[] = [0, 'Authors', 'authors', '', 'templates_types', 'templates_types_authors', $values_counter++];
$parameter[] = [0, 'Categories', 'categories', '', 'templates_types', 'templates_types_categories', $values_counter++];
$parameter[] = [0, 'Email Templates', 'email_templates', '', 'templates_types', 'templates_types_email_templates', $values_counter++];
$parameter[] = [0, 'Includes', 'includes', '', 'templates_types', 'templates_types_includes', $values_counter++];
$parameter[] = [0, 'Pages', 'pages', '', 'templates_types', 'templates_types_pages', $values_counter++];
$parameter[] = [0, 'Posts', 'posts', '', 'templates_types', 'templates_types_posts', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Google Shopping', 'google-shopping', '', 'analytics_traffic_sources', 'google_shopping', $values_counter++];
$parameter[] = [0, 'Bing Shopping', 'bing-shopping', '', 'analytics_traffic_sources', 'bing_shopping', $values_counter++];
$parameter[] = [0, 'Yahoo Shopping', 'yahoo-shopping', '', 'analytics_traffic_sources', 'yahoo_shopping', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'delete-404-errors', 'delete-404-errors', '', 'forms_javascript_names', 'js_names_delete_404_errors', $values_counter++];
$parameter[] = [0, 'delete-admin-fields', 'delete-admin-fields', '', 'forms_javascript_names', 'js_names_delete_admin_fields', $values_counter++];
$parameter[] = [0, 'delete-admin-menus', 'delete-admin-menus', '', 'forms_javascript_names', 'js_names_delete_admin_menus', $values_counter++];
$parameter[] = [0, 'delete-admin-users', 'delete-admin-users', '', 'forms_javascript_names', 'js_names_delete_admin_user', $values_counter++];
$parameter[] = [0, 'delete-custom-fields', 'delete-custom-fields', '', 'forms_javascript_names', 'js_names_delete_custom_fields', $values_counter++];
$parameter[] = [0, 'delete-database-tables', 'delete-database-tables', '', 'forms_javascript_names', 'js_names_delete_database_tables', $values_counter++];
$parameter[] = [0, 'delete-form-field-options', 'delete-form-field-options', '', 'forms_javascript_names', 'js_names_delete_form_field_options', $values_counter++];
$parameter[] = [0, 'delete-form-fields', 'delete-form-fields', '', 'forms_javascript_names', 'js_names_delete_form_fields', $values_counter++];
$parameter[] = [0, 'delete-forms-and-swatches', 'delete-forms-and-swatches', '', 'forms_javascript_names', 'js_names_delete_forms_and_swatches', $values_counter++];
$parameter[] = [0, 'delete-leads', 'delete-leads', '', 'forms_javascript_names', 'js_names_delete_leads', $values_counter++];
$parameter[] = [0, 'delete-media', 'delete-media', '', 'forms_javascript_names', 'js_names_delete_media', $values_counter++];
$parameter[] = [0, 'delete-menus', 'delete-menus', '', 'forms_javascript_names', 'js_names_delete_menus', $values_counter++];
$parameter[] = [0, 'delete-records', 'delete-records', '', 'forms_javascript_names', 'js_names_delete_records', $values_counter++];
$parameter[] = [0, 'delete-records-with-url-id', 'delete-records-with-url-id', '', 'forms_javascript_names', 'js_names_delete_records_with_url_id', $values_counter++];
$parameter[] = [0, 'delete-site-searches', 'delete-site-searches', '', 'forms_javascript_names', 'js_names_delete_site_searches', $values_counter++];
$parameter[] = [0, 'delete-sliders', 'delete-sliders', '', 'forms_javascript_names', 'js_names_delete_sliders', $values_counter++];
$parameter[] = [0, 'delete-sub-item-records', 'delete-sub-item-records', '', 'forms_javascript_names', 'js_names_delete_sub_item_records', $values_counter++];
$parameter[] = [0, 'delete-templates', 'delete-templates', '', 'forms_javascript_names', 'js_names_delete_templates', $values_counter++];
$parameter[] = [0, 'hide-delete-option', 'hide-delete-option', '', 'forms_javascript_names', 'js_names_hide_delete_option', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'add_a_site', 'add_a_site', '', 'admin_pages_assigned_type', 'assigned_type_add_a_site', $values_counter++];
$parameter[] = [0, 'analytics_pageviews', 'analytics_pageviews', '', 'admin_pages_assigned_type', 'assigned_type_analytics_pageviews', $values_counter++];
$parameter[] = [0, 'analytics_unique_visitors', 'analytics_unique_visitors', '', 'admin_pages_assigned_type', 'assigned_type_analytics_unique_visitors', $values_counter++];
$parameter[] = [0, 'categories', 'categories', '', 'admin_pages_assigned_type', 'assigned_type_categories', $values_counter++];
$parameter[] = [0, 'custom_field_options', 'custom_field_options', '', 'admin_pages_assigned_type', 'assigned_type_custom_field_options', $values_counter++];
$parameter[] = [0, 'dashboard', 'dashboard', '', 'admin_pages_assigned_type', 'assigned_type_dashboard', $values_counter++];
$parameter[] = [0, 'displaying_in', 'displaying_in', '', 'admin_pages_assigned_type', 'assigned_type_displaying_in', $values_counter++];
$parameter[] = [0, 'form_field_options', 'form_field_options', '', 'admin_pages_assigned_type', 'assigned_type_form_field_options', $values_counter++];
$parameter[] = [0, 'form_fields_assigned', 'form_fields_assigned', '', 'admin_pages_assigned_type', 'assigned_type_form_fields_assigned', $values_counter++];
$parameter[] = [0, 'form_media_swatches', 'form_media_swatches', '', 'admin_pages_assigned_type', 'assigned_type_form_media_swatches', $values_counter++];
$parameter[] = [0, 'pages', 'pages', '', 'admin_pages_assigned_type', 'assigned_type_pages', $values_counter++];
$parameter[] = [0, 'posts', 'posts', '', 'admin_pages_assigned_type', 'assigned_type_posts', $values_counter++];
$parameter[] = [0, 'posts_comments', 'posts_comments', '', 'admin_pages_assigned_type', 'assigned_type_posts_comments', $values_counter++];
$parameter[] = [0, 'redirects', 'redirects', '', 'admin_pages_assigned_type', 'assigned_type_redirects', $values_counter++];
$parameter[] = [0, 'sub_items', 'sub_items', '', 'admin_pages_assigned_type', 'assigned_type_sub_items', $values_counter++];
$parameter[] = [0, 'unassigned_categories', 'unassigned_categories', '', 'admin_pages_assigned_type', 'assigned_type_unassigned_categories', $values_counter++];
$parameter[] = [0, 'unassigned_pages', 'unassigned_pages', '', 'admin_pages_assigned_type', 'assigned_type_unassigned_pages', $values_counter++];
$parameter[] = [0, 'unassigned_posts', 'unassigned_posts', '', 'admin_pages_assigned_type', 'assigned_type_unassigned_posts', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'Left', 'left', '', 'sliders_pager_alignments', 'sliders_pager_alignments_left', $values_counter++];
$parameter[] = [0, 'Center', 'center', '', 'sliders_pager_alignments', 'sliders_pager_alignments_center', $values_counter++];
$parameter[] = [0, 'Right', 'right', '', 'sliders_pager_alignments', 'sliders_pager_alignments_right', $values_counter++];

$values_counter = 1;
$parameter[] = [0, 'CMS', '1', '', 'admin_page_level', 'admin_page_level_cms', $values_counter++];
$parameter[] = [0, 'Commerce', '2', '', 'admin_page_level', 'admin_page_level_ecommerce', $values_counter++];
$parameter[] = [0, 'ERP', '3', '', 'admin_page_level', 'admin_page_level_erp', $values_counter++];
$parameter[] = [0, 'AI', '4', '', 'admin_page_level', 'admin_page_level_erp', $values_counter++];

//Get admin_fields_lists to map admin_fields_values and set admin_fields_lists_id.
$current_admin_fields_lists = $results->getSelectMultipleRecordsKeyName(__LINE__, __FILE__, '*', 'admin_fields_lists', '', [], 'system_code');

//Set admin_fields_lists_id
$parameters = array();
if(!empty($parameter))
{
	foreach($parameter as $values)
	{
		//$values[4] in admin_fields_values is 'admin_fields_lists_parent_code'.
		if(!empty($values[4]) && isset($current_admin_fields_lists[$values[4]]['id']))
		{
			//Set the correct admin_fields_lists_id.
			$admin_fields_lists_id = $current_admin_fields_lists[$values[4]]['id'];
			
			//$values[0] is admin_fields_lists_id.
			$values[0] = $admin_fields_lists_id;
		}
		
		//Store the updated row
		$parameters[] = $values;
	}
}

if(!isset($update_admin_fields_values))
{
	$results->getinsertMultipleRecords(__LINE__, __FILE__, 'admin_fields_values', $column_names, $placeholders, $parameters);
}
else
{
	$update_parameters = $parameters;
	$parameters = array();
	
	foreach($update_parameters as $param)
	{
		$parameters[] = ['admin_fields_lists_id' => $param[0], 
						 'label' => $param[1], 
						 'value' => $param[2], 
						 'swap_admin_field_to' => $param[3], 
						 'admin_fields_lists_parent_code' => $param[4], 
						 'system_code' => $param[5], 
						 'sort' => $param[6]];
	}
	
	
}