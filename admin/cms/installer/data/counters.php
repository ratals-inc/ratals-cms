<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Get last site id.
$sql_sites_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '`id`', 'sites', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_sites_row)) 
{
	$site_id = $sql_sites_row['id'] + 1;
}
else
{
	$site_id = 1;
}

//Get last url id
$sql_last_url_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_last_url_id_row)) 
{
	$last_url_id = $sql_last_url_id_row['id'];
}
else
{
	$last_url_id = 0;
}

//Get last pages id
$sql_last_pages_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'pages', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_last_pages_id_row)) 
{
	$last_pages_id = $sql_last_pages_id_row['id'];
}
else
{
	$last_pages_id = 0;
}

//Get last categories id
$sql_last_categories_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'categories', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_last_categories_id_row)) 
{
	$last_categories_id = $sql_last_categories_id_row['id'];
}
else
{
	$last_categories_id = 0;
}

//Get last template_files id
$sql_last_template_files_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'template_files', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_last_template_files_id_row)) 
{
	$last_template_file_id = $sql_last_template_files_id_row['id'];
}
else
{
	$last_template_file_id = 0;
}

//Get last menu id
$sql_last_menu_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '`id`', 'menus', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_last_menu_id_row)) 
{
	$last_menu_id = $sql_last_menu_id_row['id'];
}
else
{
	$last_menu_id = 0;
}

//Get last slider id
$sql_last_slider_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '`id`', 'sliders', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_last_slider_id_row)) 
{
	$last_slider_id = $sql_last_slider_id_row['id'];
}
else
{
	$last_slider_id = 0;
}

//Get last custom_fields id
$sql_last_custom_field_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '`id`', 'custom_fields', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_last_custom_field_id_row)) 
{
	$last_custom_field_id = $sql_last_custom_field_id_row['id'];
}
else
{
	$last_custom_field_id = 0;
}

//Get last design_blocks id
$sql_last_design_block_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '`id`', 'design_blocks', 'ORDER BY `id` DESC LIMIT 1', []);
if(!empty($sql_last_design_block_id_row)) 
{
	$last_design_block_id = $sql_last_design_block_id_row['id'];
}
else
{
	$last_design_block_id = 0;
}