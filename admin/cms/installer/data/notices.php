<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$column_names = '`id`, `site_id`, `status`, `notice_subject`, `notice_message`, `notice_url`, `notice_update_software`, `notice_upgrade_from`, `notice_upgrade_to`, `notice_software_version`, `required_php_version`, `required_mysql_version`, `system_code`, `custom_fields`, `created_date`';
$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [NULL, 0, 1, 'Welcome to Ratals!', '<div class=""><p>Your installation is complete. Now it\'s time to set up your website and start building. If this is your first time using Ratals, we recommend following the <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-cms/" target="_blank">Getting Started Tutorial</a>. It walks you through the initial setup and customization of your website so you can become familiar with how Ratals works and where to begin.</p><p>Once you understand the basics, you can begin building your site and <a href="https://www.ratals.com/pricing/" target="_blank">explore additional Ratals features</a> as needed.</p></div>', '', 'No', '', '', '', '', '', 'welcome_to_ratals', '{}'];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'notices', $column_names, $placeholders, $parameters);