<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(isset($install_template) && $install_template == 'Yes')
{
	$template_to_install = 'default';
	$template_name = ucwords(str_replace(array('-','_'), '', $template_to_install));
	$template_status = 1;
}

$column_names = '`id`, `site_id`, `status`, `name`, `sub_items`, `media`, `directory_folder_name`, `custom_fields`, `created_date`, `created_by`, `updated_date`, `updated_by`';
$placeholders = '?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$parameters = [NULL, $site_id, $template_status, $template_name, 0, ($_SESSION['template-screenshot-default.png'] ?? 1).'~||~Template Screenshot', $template_to_install, '', $first_last_name, $first_last_name];

$template_id = $results->getInsertRecord(__LINE__, __FILE__, 'templates', $column_names, $placeholders, $parameters);