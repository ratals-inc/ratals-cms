<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//Content Security Policy Rules
if(isset($domain_only)) { $set_base_uri = ' base-uri *.'.$domain_only.';'; } else { $set_base_uri = ''; }
header("Content-Security-Policy: script-src 'self' 'nonce-".NONCE."'; style-src 'self' 'nonce-".NONCE."'; img-src 'self'; object-src 'self';".$set_base_uri." default-src 'self' *.googleapis.com *.gstatic.com *.youtube.com;");
