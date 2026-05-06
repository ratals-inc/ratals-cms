<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

$warp_with_email_template = 'Yes';

$subject = "Possible SQL Injection Attempt";

$message = '<p>A POST/GET was just submitted on '.$site_name.' that might be trying to do SQL Injection from IP Address '.$_SERVER['REMOTE_ADDR'].'.</p>
<p>Data was Posted to this URL or Form: <a href="'.$url.'" target="_blank" style="word-break: break-all;">'.$url.'</a></p>
<p>Post/GET data was converted to JSON so you can see all content the user was trying to post/get.</p>
<p><strong>Post/GET Data:</strong></p>'.$posted_string_raw;
?>