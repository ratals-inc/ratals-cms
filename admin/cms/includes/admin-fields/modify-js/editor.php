<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/editor.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/editor.php');
}
else
{
	echo '<script type="text/javascript" src="/'.$_SESSION['admin_directory'].'/cms/includes/editor/editor.js" nonce="'.NONCE.'"></script>';
} ?>
