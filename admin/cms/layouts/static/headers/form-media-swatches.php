<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/form-media-swatches.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/headers/form-media-swatches.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'form_media_swatches')
	{
		$order_counter_swatches = 10000;
		$order_counter_media = 20000;
		
		$sql_record_data_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'forms', 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
		
		$db_form_fields_ids = $sql_record_data_rows["form_fields_ids"];
		
		$form_fields_ids_array = array();
		if(!empty($db_form_fields_ids))
		{
			if(strpos($db_form_fields_ids, ',') !== false)
			{
				$form_fields_ids = explode(',', $db_form_fields_ids);
				
				foreach($form_fields_ids as $form_fields_id)
				{
					$form_fields_ids_array[] = $form_fields_id;
				}
			}
			else
			{
				$form_fields_ids_array[] = $form_fields_id;
			}
		}
		
		$form_fields_assigned_array = array();
		$form_fields_array = array();
		
		if(!empty($form_fields_ids_array))
		{
			//Get sub_menu count for Form Fields(COUNT)
			foreach($form_fields_ids_array as $form_fields_id)
			{
				$sql_get_form_fields = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [$form_fields_id]);
				
				if(!empty($sql_get_form_fields))
				{
					$form_fields_assigned_array[] = $sql_get_form_fields;	
				}
			}
			
			//Get form fields that are a dropdown to create list
			foreach($form_fields_ids_array as $form_fields_id)
			{
				$sql_get_form_fields = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ? AND `form_field_type` = ?', [$form_fields_id, 'Swatch']);
				
				if(!empty($sql_get_form_fields))
				{
					$form_fields_array[] = $sql_get_form_fields;	
				}
			}
		}
		
		$form_values_assigned_array = array();
		if(!empty($form_fields_array))
		{
			foreach($form_fields_array as $form_fields_assigned)
			{
				$sql_get_form_values = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `status` = ?', [$form_fields_assigned['id'], 1]);
				
				if(!empty($sql_get_form_values))
				{
					foreach($sql_get_form_values as $get_form_values)
					{
						$form_values_assigned_array[$form_fields_assigned['frontend_name']][','.$form_fields_assigned['id'].'|'.$get_form_values['id'].','] = $get_form_values;	
					}
				}
			}
		}
		//echo '<pre>'; print_r($form_values_assigned_array); echo '</pre>';
		
		$all_variants = array();
		if(!empty($form_values_assigned_array))
		{
			function variants($form_values) 
			{
				$result = array(array());
				foreach($form_values as $key => $values)
				{
					$append_values = array();
					
					foreach($result as $key_2 => $product)
					{
						foreach($values as $key_3 => $item)
						{
							$product[$key] = $item;
							$final_key = ltrim(str_replace(',,', ',', $key_2.$key_3), '0');
							$append_values[$final_key] = $product;
							
							//Start - get swatch image id.
							$sql_get_form_media = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_media', 'WHERE `form_id` = ? AND `value_ids` = ?', [trim($_GET["rid"] ?? ''), $final_key]);
							
							if(!empty($sql_get_form_media))
							{
								$append_values[$final_key]['swatch_data_array'] = $sql_get_form_media;
							}
							else
							{
								$append_values[$final_key]['swatch_data_array'] = array('media' => '');
							}
							//End - get swatch image id.
						}
					}
					$result = $append_values;
				}
				return $result;
			}
			
			$all_variants = variants($form_values_assigned_array);
		}
		//echo '<pre>'; print_r($all_variants); echo '</pre>';
		
		$sql_get_swatches_already_set = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_media', 'WHERE `form_id` = ?', [trim($_GET["rid"] ?? '')]);
		
		$swatches_already_set_array = array();
		
		if(!empty($sql_get_swatches_already_set))
		{
			foreach($sql_get_swatches_already_set as $get_swatches_already_set)
			{
				$swatches_already_set_array[] = $get_swatches_already_set['value_ids'];
			}
		}
		
		//print_r($swatches_already_set_array);
		
		$db_id = $sql_record_data_rows["id"];
		$db_updated_by = $sql_record_data_rows["updated_by"];
		$db_last_updated = $sql_record_data_rows["updated_date"];
		$db_created_date_by = $sql_record_data_rows["created_by"];
		$db_created_date = $sql_record_data_rows["created_date"];
		
		$errors = array();
		if(isset($_POST['submit']) && isset($_POST["swatches"])) 
		{
			$posted_media_ids_with_media_set = array();
			
			foreach($_POST["swatches"] as $post_swatch_media)
			{
				//echo '<pre>'; print_r($post_swatch_media); echo '</pre>';
				
				$media_ids_set_string = '';
				
				if(!empty($post_swatch_media['swatch_media_id']) || !empty($post_swatch_media['swatch_manufacturer']) || !empty($post_swatch_media['swatch_item_number']) || !empty($post_swatch_media['swatch_notes']))
				{
					if(!empty($post_swatch_media['swatch_media_id']))
					{
						foreach($post_swatch_media['swatch_media_id'] as $media_ids_set)
						{
							if(!empty($media_ids_set))
							{
								$media_ids_set_string .= $media_ids_set[0].'~||~'.$media_ids_set[1].'*||*';
							}
						}
						
						$media_ids_set_string = trim($media_ids_set_string, '*||*');
					}
					
					$media_ids_set_string = trim($media_ids_set_string, ',');
					
					if(!empty($media_ids_set_string) || !empty($post_swatch_media['swatch_manufacturer']) || !empty($post_swatch_media['swatch_item_number']) || !empty($post_swatch_media['swatch_notes']))
					{
						$posted_media_ids_with_media_set[] = $post_swatch_media['swatch_id'];
					}
				}
				
				//Update if there is an id - if id, it means record exist.
				if(!empty($post_swatch_media['swatch_id']) && (!empty($media_ids_set_string) || !empty($post_swatch_media['swatch_manufacturer']) || !empty($post_swatch_media['swatch_item_number']) || !empty($post_swatch_media['swatch_notes'])) && in_array($post_swatch_media['swatch_id'], $swatches_already_set_array))
				{
					$results->getUpdateRecord(__LINE__, __FILE__, 'form_media', '`media` = ?, `manufacturer` = ?, `item_number` = ?, `notes` = ?', 'WHERE `form_id` = ? AND `value_ids` = ?', [$media_ids_set_string, $post_swatch_media["swatch_manufacturer"], $post_swatch_media["swatch_item_number"], $post_swatch_media["swatch_notes"], trim($_GET["rid"] ?? ''), $post_swatch_media['swatch_id']]);
				}
				//Insert if no id - No id means its a new record.
				elseif(!empty($media_ids_set_string) || !empty($post_swatch_media['swatch_manufacturer']) || !empty($post_swatch_media['swatch_item_number']) || !empty($post_swatch_media['swatch_notes']))
				{
					$results->getInsertRecord(__LINE__, __FILE__, 'form_media', '`site_id`, `form_id`, `value_ids`, `media`, `manufacturer`, `item_number`, `notes`', '?,?,?,?,?,?,?', [0, trim($_GET["rid"] ?? ''), $post_swatch_media['swatch_id'], $media_ids_set_string, $post_swatch_media['swatch_manufacturer'], $post_swatch_media['swatch_item_number'], $post_swatch_media['swatch_notes']]);
				}
			}
				
			////Delete swatch records removed.	
			$removed_swatch_ids = array();
			$removed_swatch_ids = array_diff($swatches_already_set_array, $posted_media_ids_with_media_set);
			
			if(!empty($removed_swatch_ids))
			{
				foreach($removed_swatch_ids as $removed_swatch_id)
				{
					$results->getDeleteRecord(__LINE__, __FILE__, 'form_media', 'WHERE `form_id` = ? AND `value_ids` = ?', [trim($_GET["rid"] ?? ''), $removed_swatch_id]);
				}
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
			
			header("Location: /".$_SESSION['admin_save_url']."/?rid=".trim($_GET["rid"] ?? '')."&updated=success"); exit();
		}
	}
}