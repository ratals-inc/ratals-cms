<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/ajax/sub-items.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/ajax/sub-items.php');
}
else
{
	//Enable/Disable sub_items.php groups.
	if($_POST['type'] == 'groupStatus')
	{
		$editing = htmlspecialchars($_POST['editing'] ?? '');
		$group_id_count = htmlspecialchars($_POST['group_id_count'] ?? '');
		$group_id = htmlspecialchars($_POST['group_id'] ?? '');
		$value = htmlspecialchars($_POST['value'] ?? '');
		
		$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`updated_by` = ?,`updated_date` = UTC_TIMESTAMP()', 'WHERE `id` = ? AND `site_id` = ?', [$_SESSION['user_first_last_name'], $_POST['editing'], $_SESSION["site_set_for_editing"]]);
		
		$results->getUpdateRecord(__LINE__, __FILE__, 'page_groups', '`status` = ?', 'WHERE `site_id` = ? AND `urls_id` = ? AND `id` = ?', [$_POST['value'], $_SESSION["site_set_for_editing"], $_POST['editing'], $_POST['group_id']]);
		
		if($value == 1)
		{
			echo '<span class="groupStatus" data-click="'.$editing.','.$group_id_count.','.$group_id.','.$value.'"><i><svg viewBox="0 0 512 512"><path d="M507 256S413 83 256 83 5 256 5 256 99 429 256 429 507 256 507 256M41 256A409 409 0 0 1 94 192C134 151 189 115 256 115S378 151 418 192A409 409 0 0 1 471 256Q468 260 465 265C454 280 438 300 418 320 378 361 323 397 256 397S134 361 94 320A409 409 0 0 1 41 256ZM256 177A79 79 0 1 0 256 335 79 79 0 0 0 256 177M146 256A110 110 0 1 1 366 256 110 110 0 0 1 146 256"></path></svg></i></span>';
		}
		elseif($value == 2)
		{
			echo '<span class="groupStatus" data-click="'.$editing.','.$group_id_count.','.$group_id.','.$value.'"><i><svg viewBox="0 0 512 512"><path d="M424 358C478 310 507 256 507 256s-94-173-251-173a220 220 0 0 0-88 18l24 24A189 189 0 0 1 256 115c67 0 122 37 162 77A409 409 0 0 1 471 256q-3 4-6 9c-11 15-26 35-46 55q-8 8-16 15zM360 293a110 110 0 0 0-141-141l26 26a79 79 0 0 1 89 89zm-92 41 26 26a110 110 0 0 1-141-141l26 26a79 79 0 0 0 89 89M110 176q-8 8-16 15A409 409 0 0 0 41 256l6 9c11 15 26 35 46 55C134 361 189 397 256 397c22 0 44-4 63-11l24 24A220 220 0 0 1 256 429C99 429 5 256 5 256s30-54 83-102l22 22zm324 279-377-377 22-22 377 377z"></path></svg></i></span>';
		}
		exit;
	}
	
	//Enable/Disable sub_items.php items.
	if($_POST['type'] == 'changeActive')
	{
		$id = htmlspecialchars($_POST['id'] ?? '');
		$editing = htmlspecialchars($_POST['editing'] ?? '');
		$field = htmlspecialchars($_POST['field'] ?? '');
		$value = htmlspecialchars($_POST['value'] ?? '');
		
		$results->getUpdateRecord(__LINE__, __FILE__, $_SESSION['admin_table_name'], '`updated_by` = ?,`updated_date` = UTC_TIMESTAMP()', 'WHERE `id` = ? AND `site_id` = ?', [$_SESSION['user_first_last_name'], $_POST['id'], $_SESSION["site_set_for_editing"]]);
		
		$results->getUpdateRecord(__LINE__, __FILE__, 'assignments_sub_items', '`status` = ?', 'WHERE `id` = ? AND `site_id` = ?', [$_POST['value'], $_POST['field'], $_SESSION["site_set_for_editing"]]);
		
		if($value == 1)
		{
			echo '<span class="changeActive" data-click="'.$id.','.$editing.','.$field.','.$value.'">Enabled</span>';
		}
		elseif($value == 2)
		{
			echo '<span class="changeActive" data-click="'.$id.','.$editing.','.$field.','.$value.'">Disabled</span>';
		}
		exit;
	}
}