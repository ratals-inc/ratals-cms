<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/sites/webhooks/paypal.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/sites/webhooks/paypal.php');
}
else
{
	//PayPal Webhook Endpoint.
	//This file must remain static. (no sessions, no CMS routing)
	
	if(!file_exists($_SERVER['DOCUMENT_ROOT'].'/core/database/index.php') || !file_exists($_SERVER['DOCUMENT_ROOT'].'/admin/commerce/api/paypal.php'))
	{
		http_response_code(204);
		exit;
	}
	
	//Connect to Database so handler can run without sessions. /classes/index.php is a raw connection with no sessions started.
	require_once($_SERVER['DOCUMENT_ROOT'].'/core/database/index.php');
	
	//Enforce POST only.
	if($_SERVER['REQUEST_METHOD'] !== 'POST')
	{
		http_response_code(405);
		header('Allow: POST');
		exit('Method Not Allowed');
	}
	
	//Enforce JSON payload as that is what PayPal sends.
	$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
	if(stripos($contentType, 'application/json') === false)
	{
		http_response_code(415);
		exit('Unsupported Media Type');
	}
	
	//Load PayPal webhook handler.
	require_once($_SERVER['DOCUMENT_ROOT'].'/admin/commerce/api/paypal.php');
	
	//Process webhook.
	payPalWebhookHandler();
}