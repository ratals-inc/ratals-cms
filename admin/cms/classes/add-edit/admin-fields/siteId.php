<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/siteId.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/siteId.php');
}
else
{
	if(!class_exists('siteIdAeaf'))
	{
		class siteIdAeaf
		{
			public function siteIdAeaf($table_name, $admin_field, &$post_values)
			{
				//If a custom field option is being added, we have to look at the custom field to see if its a global attribute for site_id = 0 or not.
				if($_SESSION['admin_table_name'] == "custom_fields_options" && !empty(trim($_GET['sub-page-rid'] ?? '')))
				{
					$site_or_global_custom_field = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ?', [trim($_GET['sub-page-rid'] ?? '')]);
				}
				elseif($_SESSION['admin_table_name'] == "custom_fields_options" && !empty(trim($_GET["rid"] ?? '')))
				{
					$site_or_global_custom_field = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
				}
				
				if((isset($post_values['custom_fields']['field_type']) && $post_values['custom_fields']['field_type'] == 'Inventory Attribute') 
					|| (isset($site_or_global_custom_field['field_type']) && $site_or_global_custom_field['field_type'] == 'Inventory Attribute')
					|| (isset($post_values['custom_fields']['field_type']) && $post_values['custom_fields']['field_type'] == 'Product Option') 
					|| (isset($site_or_global_custom_field['field_type']) && $site_or_global_custom_field['field_type'] == 'Product Option')
					|| ($_SESSION['admin_table_name'] != "custom_fields" && $_SESSION['admin_site_id_global'] == 'Yes')
					)
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="0">';
				}
				else
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($_SESSION["site_set_for_editing"] ?? '').'">';
				}
			}
		}
		
		$class_siteIdAeaf = new siteIdAeaf();
	}
	
	$class_siteIdAeaf->siteIdAeaf($table_name, $admin_field, $post_values);
}