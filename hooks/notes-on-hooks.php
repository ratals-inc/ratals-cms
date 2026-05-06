<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

/*
Hooks allow you to override or customize core system files without modifying the original source code.

To use a hook, recreate the same file path inside the /hooks directory for the file you want to override.
When the system loads files, it will check the /hooks directory first. If a matching file exists there,
it will be used instead of the core file.

Example:

Core file:
    /admin/cms/functions/media.php

To override this file, create:
    /hooks/admin/cms/functions/media.php

You can copy the contents of the core file into the hooks file and then modify it as needed.
Your version will be used while the original core file remains unchanged.

This allows you to customize behavior while keeping the core system upgrade-safe.
*/