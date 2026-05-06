<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$column_names = '`id`, `site_id`, `status`, `notice_subject`, `notice_message`, `notice_url`, `notice_update_software`, `notice_upgrade_from`, `notice_upgrade_to`, `notice_software_version`, `required_php_version`, `required_mysql_version`, `system_code`, `custom_fields`, `created_date`';
$placeholders = '?,?,?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP()';

$parameters = array();
$parameters[] = [NULL, 0, 1, 'Welcome to Ratals!', '<div class=""><p>We are excited to support your business growth like never before. Ratals is built to scale with you - from simple websites to fully integrated business systems.</p><p>With the CMS, you can easily create and manage content, build pages, and launch your online presence with ease. As your needs expand, Ratals grows with you. Upgrade anytime to unlock powerful features like Commerce for selling products and managing orders, along with inventory, accounting, CRM, analytics, and more.</p><p><strong>Getting Started</strong></p><p>If you are new or want step-by-step guidance, follow <a href="https://www.ratals.com/tutorials/installation/setting-up-ratals-cms/" target="_blank">this tutorial</a> to set up and customize your site. When you\'re ready for more features, you can <a href="https://www.ratals.com/pricing/" target="_blank">upgrade your plan</a> at any time.</p><p>Our mission is to deliver the world\'s most advanced open-source business platform - helping you streamline operations, scale faster, and run your business with confidence.</p></div>', '', 'No', '', '', '', '', '', 'welcome_to_ratals', '{}'];

$results->getinsertMultipleRecords(__LINE__, __FILE__, 'notices', $column_names, $placeholders, $parameters);