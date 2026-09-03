<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(!defined('INSTALLATION_ROOT'))
{
	define('INSTALLATION_ROOT', dirname(__DIR__, 5));
}
require_once(INSTALLATION_ROOT.'/core/installation-paths.php');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once(INSTALLATION_ROOT.'/core/session-check-admin.php');

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/ajax/displaying-in.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/ajax/displaying-in.php');
}
else
{
	//Enable/Disable displaying-in.php items.
	if($_POST['type'] == 'displayingInStatus')
	{
		$id = htmlspecialchars($_POST['id'] ?? '');
		$value = htmlspecialchars($_POST['value'] ?? '');
		$counter = htmlspecialchars(trim($_POST['counter'] ?? ''));
		$assignment_table = htmlspecialchars($_POST['assignment'] ?? '');
		
		if($assignment_table == '1' && $commerce_installed)
		{
			$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_products', '`status` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$_POST['value'], $_POST['id'], $_SESSION["site_set_for_editing"]]);
		}
		elseif($assignment_table == '2')
		{
			$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_design_blocks', '`status` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$_POST['value'], $_POST['id'], $_SESSION["site_set_for_editing"]]);
		}
		
		if($value == 1)
		{
			echo '<span class="displayingInStatus" data-click="'.$id.','.$value.','.$counter.','.$assignment_table.'">Enabled</span>';
		}
		elseif($value == 2)
		{
			echo '<span class="displayingInStatus" data-click="'.$id.','.$value.','.$counter.','.$assignment_table.'">Disabled</span>';
		}
		
		//Clear cache on save.
		if($_SESSION['admin_site_id_global'] == 'No')
		{
			clearSiteCache($_SESSION['site_set_for_editing']);
		}
		else
		{
			clearAllSiteCache();
		}
		
		exit;
	}
	
	//Remove displaying-in.php items.
	if(isset($_POST['id']) && !empty($_POST['id']) && $_POST['type'] == 'displayingInRemove')
	{
		$assignment_table = $_POST['assignment'];
		
		if($assignment_table == '1' && $commerce_installed)
		{
			$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_products', 'WHERE `id` = ? AND `site_id` = ?', [$_POST['id'], $_SESSION["site_set_for_editing"]]);
		}
		elseif($assignment_table == '2')
		{
			$results->getDeleteRecord(__LINE__, __FILE__, 'assignments_design_blocks', 'WHERE `id` = ? AND `site_id` = ?', [$_POST['id'], $_SESSION["site_set_for_editing"]]);
		}
		
		//Clear cache on save.
		if($_SESSION['admin_site_id_global'] == 'No')
		{
			clearSiteCache($_SESSION['site_set_for_editing']);
		}
		else
		{
			clearAllSiteCache();
		}
		
		exit;
	}
}