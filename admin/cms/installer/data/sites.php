<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Sites
$column_names = '`id`, `site_language`, `homepage`, `load_on`, `domain`, `subdomain`, `https_in_url`, `www_in_url`, `url_structure`, `redirect_to_opposite_url`, `auto_generate_canonical_url`, `global_url_extension`, `admin_directory`, `custom_fields`, `created_by`, `created_date`, `updated_by`, `updated_date`';
$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP()';

$parameters = [$site_id, $site_language, $_SESSION['install_ids'][$site_id]['homepage_url_id'] ?? 0, 'Domain', $tld, $subdomain, $https_in_url, $www_in_url, 'Hierarchy', $redirect_to_opposite_url, $auto_generate_canonical_url, $url_extension, $admin_directory, '{}', $first_last_name, $first_last_name];

$site_id = $results->getInsertRecord(__LINE__, __FILE__, 'sites', $column_names, $placeholders, $parameters);