<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/functions/custom-fields.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/functions/custom-fields.php');
}
else
{
	if(!function_exists('custom_field'))
	{
		function customField($custom_field_id, $record_id)
		{
			global $site_id, $final_url_home_page;
			
			$sql_custom_field_data_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE (`site_id` = ? OR `site_id` = ?) AND `id` = ? AND `status` = ?', [$site_id, '0', $custom_field_id, '1']);
			
			if(!empty($sql_custom_field_data_rows))
			{
				//Retrieve admin_pages data to determine whether to process it as a single-record admin page or a multi-record page that requires querying the table with a record_id.
				$sql_global_one_record_or_not = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_pages', 'WHERE `table_name` = ?', [$sql_custom_field_data_rows["assigned_to"]]);
				$global_record_or_not = '';
				if($sql_global_one_record_or_not['one_record'] == 'No')
				{
					$global_record_or_not = " AND `id` = '".$record_id."'"; 
				} 
				
				$sql_custom_field_value_data_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '`custom_fields`', $sql_custom_field_data_rows["assigned_to"], 'WHERE (`site_id` = ? OR `site_id` = 0) '.$global_record_or_not, [$site_id]);
				
				$sql_custom_field_value_data_rows = JSON_DECODE($sql_custom_field_value_data_rows['custom_fields'] ?? '', true);
				
				if($sql_custom_field_data_rows["cf_display_as"] == "singleMedia")
				{
					$sql_custom_field_media_url_rows = array();
					
					if(!empty($sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]))
					{
						$custom_field_media_id = array();
						if(strpos($sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']], '*||*') !== false)
						{
							$custom_field_media_array = explode('*||*',$sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]);
							$custom_field_media = $custom_field_media_array[0];
							$custom_field_media_data = explode('~||~', $custom_field_media);
							$custom_field_media_id = $custom_field_media_data[0];
							$custom_field_media_tag = $custom_field_media_data[1];
						}
						elseif(!empty($sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]))
						{
							$custom_field_media_data = explode('~||~', $sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]);
							$custom_field_media_id = $custom_field_media_data[0];
							$custom_field_media_tag = $custom_field_media_data[1];
						}
						
						return $custom_field_media_data;
					}
				}
				elseif($sql_custom_field_data_rows["cf_display_as"] == "dropdownId")
				{
					$custom_field_dropdown_label = '';
					
					if(isset($sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]) && !empty($sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]))
					{
						$sql_get_dropdown_label = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields_options', 'WHERE `id` = ? AND (`site_id` = ? OR `site_id` = 0)', [$sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']], $site_id]);
						
						if(isset($sql_get_dropdown_label['option_data']))
						{
							$sql_option_data = JSON_DECODE($sql_get_dropdown_label['option_data'] ?? '', true);
							$custom_field_dropdown_label = $sql_option_data[$_SESSION['site_language']]['label'];
						}
					}
					
					return $custom_field_dropdown_label;
				}
				else
				{
					//For custom fields set up with a textarea, textareaWithEditor,or textfield
					if(!empty($sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]))
					{
						$custom_field_content = '';
						
						if(isset($sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]) && !empty($sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']]))
						{
							//Set value incase it does not run through !is_numeric conditions below.
							$custom_field_content = $sql_custom_field_value_data_rows[$sql_custom_field_data_rows['column_name']];
							
							//We use the !is_numeric condition to ensure that if a user enters "100" in a field, it remains as "100" rather than being processed differently. If we were searching for a URL or media using functions like urlId() or mediaId(), those functions would treat the input as text, making !is_numeric.
							
							if(!is_numeric($custom_field_content))
							{
								$custom_field_content = urlId($custom_field_content);
							}
							
							if(!is_numeric($custom_field_content))
							{
								$custom_field_content = mediaId($custom_field_content, '', '', '');
							}
						}
						
						return $custom_field_content;
					}
				}
			}
		}
	}
	//customField(2, $rid);
}
