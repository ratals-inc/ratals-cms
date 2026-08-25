<?php
if(!defined('RATALS_INSTALLER'))
{
	//Block direct access to this file in case an Nginx user has not yet configured the server to block access to the /core/ directory.
	http_response_code(403);
	die('Forbidden');
}

$install_token = '';