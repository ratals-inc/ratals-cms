<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/nonce.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/nonce.php');
}
else
{
	if(!function_exists('getNonce'))
	{
		function getNonce($content_to_update_nonce)
		{
			$updated_content_with_nonce = str_replace(array('nonce="NONCE"', "nonce='NONCE'", 'nonce="nonce"', "nonce='nonce'"), 'nonce="'.$_SESSION['nonce_token'].'"', $content_to_update_nonce ?? '');
			
			return $updated_content_with_nonce;
		}
	}
}