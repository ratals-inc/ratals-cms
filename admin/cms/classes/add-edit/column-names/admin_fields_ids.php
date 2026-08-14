<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/admin_fields_ids.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/column-names/admin_fields_ids.php');
}
else
{
	if(!class_exists('admin_fields_ids_aecn'))
	{
		class admin_fields_ids_aecn
		{
			public function admin_fields_ids_aecn($table_name, $admin_field, &$post_values, &$errors, &$database_tables_data)
			{
				if($_SESSION['admin_table_name'] == 'database_tables' && isset($_POST[$table_name]['admin_fields_ids']))
				{
					if($_SESSION['admin_type'] == 'edit' && isset($_POST[$table_name]['id']) && !empty($_POST[$table_name]['id']))
					{
						$database_tables_data = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'database_tables', 'WHERE `id` = ?', [$_POST[$table_name]['id']]);
					}
					
					if(!empty($_POST[$table_name]['admin_fields_ids']))
					{
						$posts_admin_fields_ids = '';
						$admin_fields_id_array = array();
						
						foreach($_POST[$table_name]['admin_fields_ids'] as $admin_fields_id)
						{
							if(strpos(','.$posts_admin_fields_ids, ','.$admin_fields_id.',') === false)
							{
								$posts_admin_fields_ids .= $admin_fields_id.',';
							}
							else
							{
								$posts_admin_fields_ids .= $admin_fields_id.',';
								$errors[$table_name]['admin_fields_ids'] = 'Column ID "'.$admin_fields_id.'" is in this list more than once. You cannot have the same column name in a table more than once.';
							}
						}
						
						$_POST[$table_name]['admin_fields_ids'] = ','.$posts_admin_fields_ids;
						$post_values[$table_name]['admin_fields_ids'] = ','.$posts_admin_fields_ids;
					}
					else
					{
						$_POST[$table_name]['admin_fields_ids'] = '';
						$post_values[$table_name]['admin_fields_ids'] = '';
					}
				}
			}
		}
		
		$class_admin_fields_ids_aecn = new admin_fields_ids_aecn();
	}
	
	$database_tables_data = array();
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_admin_fields_ids_aecn->admin_fields_ids_aecn($table_name, $admin_field, $post_values, $errors, $database_tables_data);
	}
}