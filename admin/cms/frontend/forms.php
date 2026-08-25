<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/forms.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/frontend/forms.php');
}
else
{
	//Start timer for how long user has been on site.
	if(!isset($_SESSION['form_timer']))
	{
		$_SESSION['form_timer'] = time();
	}
	$form_timer = time() - $_SESSION['form_timer'];
	
	//Start pageview counter for how many pages a user went to.
	if(!isset($_SESSION['form_pageviews']) && $_SERVER['REQUEST_URI'] != '/favicon.ico' && $_SERVER['REQUEST_URI'] != '/favicon/')
	{
		$_SESSION['form_pageviews'] = 0;
	}
	$form_pageviews = $_SESSION['form_pageviews'];
	
	$display_submitted_message = '';
	
	//Get form data from database to submit lead
	if(isset($_POST["form_id"]))
	{
		$sql_form_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'forms', 'WHERE `id` = ? LIMIT 1', [$_POST["form_id"]]);
		
		$field_ids_array = array();
		$form_fields = array();
		
		if(!empty($sql_form_rows))
		{
			if(!empty($sql_form_rows['form_fields_ids']) && strpos($sql_form_rows['form_fields_ids'], ',') !== false)
			{
				$field_ids_array['form_field_ids'] = explode(',', $sql_form_rows['form_fields_ids']);
			}
			else
			{
				$field_ids_array['form_field_ids'][] = $sql_form_rows['form_fields_ids'];
			}
		}
		
		if(!empty($field_ids_array))
		{
			foreach($field_ids_array['form_field_ids'] as $form_field_ids)
			{
				$sql_form_fields_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ? LIMIT 1', [$form_field_ids]);
				
				if(!empty($sql_form_fields_rows))
				{
					$sql_form_field_values_rows = array();
					
					if($sql_form_fields_rows['form_field_type'] == 'Dropdown' || $sql_form_fields_rows['form_field_type'] == 'Swatch')
					{
						$sql_form_field_values = $results->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$sql_form_fields_rows['id'], 1]);
						
						if(!empty($sql_form_field_values))
						{
							foreach($sql_form_field_values as $sql_form_fields_values_rows)
							{
								$sql_form_field_values_rows['values'][] = $sql_form_fields_values_rows;
							}
						}
					}
					$form_fields['all_form_fields'][$sql_form_fields_rows['id']] = $sql_form_fields_rows + $sql_form_field_values_rows;
				}
			}
		}
	}
	
	$full_lead_string = '';
	$full_lead_string_raw = '';
	if(isset($sql_form_rows) && isset($_POST[$sql_form_rows['admin_form_name']]) && isset($_POST["form_id"])) 
	{
		$errors = '';
		
		if(!empty($form_fields['all_form_fields']))
		{
			foreach($form_fields['all_form_fields'] as $build_form_submit)
			{
				//Set value for what was submitted
				$form_values_submitted['form_'.$build_form_submit['admin_name']] = $_POST[$build_form_submit['admin_name']];
				$full_lead_string .= strtolower($_POST[$build_form_submit['admin_name']] ?? '').' ';
				$full_lead_string_raw .= strtolower($_POST[$build_form_submit['admin_name']] ?? '').' ';
				
				//if field required and empty set validation text to display
				if(($build_form_submit['form_field_type'] == 'Dropdown' || $build_form_submit['form_field_type'] == 'Swatch') && empty($form_values_submitted['form_'.$build_form_submit['admin_name']]) && $build_form_submit['required'] == 'Yes')
				{				
					$errors = 'Yes';
					${'validation_'.$build_form_submit['admin_name']} = 'Please select:  '.$build_form_submit['frontend_name'];
				}
				elseif(($build_form_submit['form_field_type'] == 'Textfield' || $build_form_submit['form_field_type'] == 'Textarea') && empty($form_values_submitted['form_'.$build_form_submit['admin_name']]) && $build_form_submit['required'] == 'Yes')
				{
					$errors = 'Yes';
					${'validation_'.$build_form_submit['admin_name']} = 'Please enter: '.$build_form_submit['frontend_name'];
				}
			}
		}
		
		$form_has_link = 'No';
		if($forms_block_links == 'Yes' && !empty($full_lead_string) && (strpos($full_lead_string, 'http') !== FALSE || strpos($full_lead_string, 'href') !== FALSE))
		{
			$form_has_link = 'Yes';
		}
		
		$form_has_keyword = 'No';
		if(!empty($forms_blocked_keywords) && !empty($full_lead_string))
		{
			$forms_blocked_keywords_array = array_map('trim', explode(',', strtolower(trim($forms_blocked_keywords, ','))));
			
			if(!empty($forms_blocked_keywords_array))
			{
				foreach($forms_blocked_keywords_array as $forms_blocked_keyword)
				{
					if(!empty($forms_blocked_keyword) && strpos($full_lead_string, $forms_blocked_keyword) !== FALSE)
					{
						$form_has_keyword = 'Yes';
						break;
					}
				}
			}
		}	
		
		if(empty($errors))
		{
			if(isset($_SESSION['click_path']))
			{
				$form_click_path = implode(', ', $_SESSION['click_path']);
			}
			else
			{
				$form_click_path = $_SERVER['REQUEST_URI'];
			}
			
			if(!empty($forms_time_on_site_set) && is_numeric($forms_time_on_site_set)) 
			{ 
				$forms_time_on_site_set = $forms_time_on_site_set; 
			} 
			else 
			{ 
				$forms_time_on_site_set = 0; 
			}
			
			if(!empty($forms_pageviews_set) && is_numeric($forms_pageviews_set)) 
			{ 
				$forms_pageviews_set = $forms_pageviews_set; 
			} 
			else 
			{ 
				$forms_pageviews_set = 0; 
			}
			
			if($form_timer < $forms_time_on_site_set || $form_pageviews < $forms_pageviews_set || $form_has_link == 'Yes' || $form_has_keyword == 'Yes')
			{
				$form_status = 'Junk';
			}
			else
			{
				$form_status = 'Active';
			}
			
			$html_characters_submitted = 'No';
			if(strpos($full_lead_string_raw, '<') === false && strpos($full_lead_string_raw, '>') === false)
			{
				$set_lead_affiliate_id = NULL;
				$set_lead_commission_amount = NULL;
				if(isset($sql_affiliate_data['pay_commission_on_leads']) && $sql_affiliate_data['pay_commission_on_leads'] == 'Yes')
				{
					$set_lead_affiliate_id = $_SESSION['affiliate_id'];
					$set_lead_commission_amount = $_SESSION['affiliate_lead_commission_amount'];
				}
				
				$column_names = '`site_id`, `lead_status`, `affiliate_account_id`, `affiliate_lead_commission_amount`, `valid_lead`, `follow_up_date`, `lead_value_amount`, `notes`, `lead`, `forms_frontend_name`, `forms_id`, `submitted_from_url`, `timer`, `pageviews`, `click_path`, `referer_source`, `referer_url`, `form_conversion_value`, `ip_address`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
				$placeholders = '?, ?, ?, ?, ?, NULL, NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, UTC_TIMESTAMP(), ?, UTC_TIMESTAMP(), ?';
				$parameters = array($site_id, $form_status, $set_lead_affiliate_id, $set_lead_commission_amount, '', '', '', $sql_form_rows['frontend_name'], $sql_form_rows['id'], $final_url_with_question_mark, $form_timer, $form_pageviews, $form_click_path, $_SESSION['referer_domain'] ?? '', $_SESSION['referer_url'] ?? '', $sql_form_rows['form_conversion_value'], $_SERVER['REMOTE_ADDR'], '[]', 'Customer', 'Customer');
				$results->getInsertRecord(__LINE__, __FILE__, 'leads', $column_names, $placeholders, $parameters);
				
				$last_lead_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'leads', 'ORDER BY `id` DESC LIMIT 1', []);
				
				$email_form_message = '';
				
				if(!empty($form_fields['all_form_fields']))
				{
					foreach($form_fields['all_form_fields'] as $build_form_submit)
					{
						$results->getInsertRecord(__LINE__, __FILE__, 'leads_values', '`site_id`, `leads_id`, `label`, `values`', '?, ?, ?, ?', [$site_id, $last_lead_id_row['id'], $build_form_submit['frontend_name'], $form_values_submitted['form_'.$build_form_submit['admin_name']]]);
						
						$email_form_message .= '<br>'.$build_form_submit['frontend_name'].': '.htmlspecialchars($form_values_submitted['form_'.$build_form_submit['admin_name']] ?? '');
					}
					
					if(isset($_POST['form_media_id']) && !empty($_POST['form_media_id']))
					{
						$form_media_id_row = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_media', 'WHERE id = ? LIMIT 1', [$_POST['form_media_id']]);
						
						if(!empty($form_media_id_row))
						{
							$first_media = '';
							if(strpos($form_media_id_row['media'], '*||*') !== false)
							{
								$media_id_array = explode('*||*', $form_media_id_row['media']);
								$first_media_data = explode('~||~', $media_id_array[0]);
								$first_media = $first_media_data[0];
							}
							elseif(strpos($form_media_id_row['media'], '~||~') !== false)
							{
								$media_id_array = explode('~||~', $form_media_id_row['media']);
								$first_media = $media_id_array[0];
							}
							
							if(!empty($first_media))
							{
								$form_media_data = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE id = ? LIMIT 1', [$first_media]);
								
								if($form_media_data['media_type'] == 'Image')
								{
									$original_media_id = $form_media_data["original_media_id"];
									
									$first_media = $domain.'/sites/media/images/'.$original_media_id.'/'.$form_media_data['media_url'];
								}
								elseif($form_media_data['media_type'] == 'Video')
								{
									$first_media = $domain.'/sites/media/videos/'.$form_media_data['media_url'];
								}
							}
							
							if(!empty($first_media))
							{
								$results->getInsertRecord(__LINE__, __FILE__, 'leads_values', '`site_id`, `leads_id`, `label`, `values`', '?, ?, ?, ?', [$site_id, $last_lead_id_row['id'], 'Media URL', $first_media]);
								$email_form_message .= '<br><strong>Media URL:</strong> '.htmlspecialchars($first_media ?? '');
							}
							
							if(!empty($form_media_id_row['manufacturer']))
							{
								$results->getInsertRecord(__LINE__, __FILE__, 'leads_values', '`site_id`, `leads_id`, `label`, `values`', '?, ?, ?, ?', [$site_id, $last_lead_id_row['id'], 'Manufacturer', $form_media_id_row['manufacturer']]);
								$email_form_message .= '<br><strong>Manufacturer:</strong> '.htmlspecialchars($form_media_id_row['manufacturer'] ?? '');
							}
							
							if(!empty($form_media_id_row['item_number']))
							{
								$results->getInsertRecord(__LINE__, __FILE__, 'leads_values', '`site_id`, `leads_id`, `label`, `values`', '?, ?, ?, ?', [$site_id, $last_lead_id_row['id'], 'Item Number', $form_media_id_row['item_number']]);
								$email_form_message .= '<br><strong>Item Number:</strong> '.htmlspecialchars($form_media_id_row['item_number'] ?? '');
							}
							
							if(!empty($form_media_id_row['notes']))
							{
								$results->getInsertRecord(__LINE__, __FILE__, 'leads_values', '`site_id`, `leads_id`, `label`, `values`', '?, ?, ?, ?', [$site_id, $last_lead_id_row['id'], 'Notes', $form_media_id_row['notes']]);
								$email_form_message .= '<br><strong>Notes:</strong> '.htmlspecialchars($form_media_id_row['notes'] ?? '');
							}
						}
					}
					
					$results->getUpdateRecord(__LINE__, __FILE__, 'leads', '`lead` = ?', 'WHERE `id` = ?', [substr($email_form_message, 4), $last_lead_id_row['id']]);
				}
			}
			else
			{
				$html_characters_submitted = 'Yes';
				$display_submitted_message = '<div class="in-form-thank-you-failed">Please remove all html characters to submit successfully. Example: "<", ">"</div>';
			}
			
			if($html_characters_submitted == 'No')
			{
				if($form_status == 'Active')
				{
					$_SESSION['form_lead_id'] = $last_lead_id_row['id'];
					$_SESSION['form_conversion'] = 1;
					$_SESSION['form_conversion_value'] = $sql_form_rows['form_conversion_value'];
				}
				else
				{
					unset($_SESSION['form_lead_id']);
					unset($_SESSION['form_conversion']);
					unset($_SESSION['form_conversion_value']);
				}
				
				if(!empty($sql_form_rows['thank_you_url']))
				{
					$url_page_id_redirect = intval($sql_form_rows['thank_you_url']);
					header("Location: ".urlId($url_page_id_redirect), true, 303); exit();
				}
				elseif(strpos($url, '?submitted=yes') === false)
				{
					$_SESSION['form_submitted_successfuly'] = $sql_form_rows['in_form_thank_you'];
					header("Location: ?submitted=yes", true, 303); exit();
				}
				else
				{
					$_SESSION['form_submitted_successfuly'] = $sql_form_rows['in_form_thank_you'];
				}
			}
		}
	}
	
	$data_array_media = $data_array['media'];
	if(isset($product_type) && !empty($data_array_media))
	{
		$product_media_array_form = array();
		
		$media_ids_array = explode('*||*', $data_array_media);
		
		foreach($media_ids_array as $media_ids)
		{
			$media_array = explode('~||~', $media_ids);
			
			$get_product_media = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$media_array[0]]);
			
			if(!empty($media_array[1]))
			{
				$get_product_media['media_tag'] = $media_array[1];
			}
			
			if(!empty($get_product_media))
			{
				$product_media_array_form[] = array('id' => $get_product_media['id'], 'media_url' => $get_product_media['media_url'], 'media_tag' => $get_product_media['media_tag'], 'media_width' => $get_product_media['width'], 'media_height' => $get_product_media['height'], 'media_type' => $get_product_media['media_type'], 'video_poster' => $get_product_media['video_poster']);
			}
		}
	}
	else
	{
		$product_media_array_form = array();
	}
	
	//Get form data from data base to build form on site
	function form_id($get_from_id) 
	{
		global $domain, $data_array_media, $product_media_array_form, $display_submitted_message, $form_values_submitted, $data_array;
		
		$sql_form_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'forms', 'WHERE `id` = ? AND `status` = ? LIMIT 1', [$get_from_id, '1']);
		
		$field_ids_array = array();
		$form_fields = array();
		
		if(!empty($sql_form_rows))
		{
			if(!empty($sql_form_rows['form_fields_ids']) && strpos($sql_form_rows['form_fields_ids'], ',') !== false)
			{
				$field_ids_array['form_field_ids'] = explode(',', $sql_form_rows['form_fields_ids']);
			}
			else
			{
				$field_ids_array['form_field_ids'][] = $sql_form_rows['form_fields_ids'];
			}
			
			if(!empty($field_ids_array['form_field_ids']))
			{
				foreach($field_ids_array['form_field_ids'] as $form_field_ids)
				{
					$sql_form_fields_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ? LIMIT 1', [$form_field_ids]);
					
					if(!empty($sql_form_fields_rows))
					{
						global ${'validation_'.$sql_form_fields_rows['admin_name']};
						
						$sql_form_field_values_rows = array();
						
						if($sql_form_fields_rows['form_field_type'] == 'Dropdown' || $sql_form_fields_rows['form_field_type'] == 'Swatch')
						{
							$sql_form_field_values = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$sql_form_fields_rows['id'], 1]);
							
							if(!empty($sql_form_field_values))
							{
								foreach($sql_form_field_values as $sql_form_fields_values_rows)
								{
									$sql_form_field_values_rows['values'][] = $sql_form_fields_values_rows;
								}
							}
						}
						$form_fields['all_form_fields'][$sql_form_fields_rows['id']] = $sql_form_fields_rows + $sql_form_field_values_rows;
					}
				}
			}
			
			if($sql_form_rows['form_auto_complete'] == 'On')
			{
				$form_auto_complete = ' autocomplete="on"';
			}
			elseif($sql_form_rows['form_auto_complete'] == 'Off')
			{
				$form_auto_complete = ' autocomplete="off"';
			}
			else
			{
				$form_auto_complete = '';
			}
		}
		
		if(!empty($sql_form_rows))
		{
			$create_array_for_jquery = array();
			$jquery_fields = '';
			$jquery_variables = '';
			$jquery_remove_class = '';
			$jquery_selection = '';
			$selected_ids = '';
			$jquery_variables_matcher = '';
			
			if(isset($_SESSION['form_submitted_successfuly'])) { $display_submitted_message = '<div class="'.$sql_form_rows['in_form_thank_you_class'].'">'.$_SESSION['form_submitted_successfuly'].'</div>'; }
			unset($_SESSION['form_submitted_successfuly']);
			
			$form_output = '
			<form method="POST" class="'.$sql_form_rows['form_name_class'].'"'.$form_auto_complete.'>
			'.$display_submitted_message.'
			<div class="'.$sql_form_rows['frontend_name_class'].'">'.$sql_form_rows['frontend_name'].'</div>
			<ul>
			';
			
			if(!empty($form_fields['all_form_fields']))
			{
				foreach($form_fields['all_form_fields'] as $form_fields_builder)
				{
					$validate = '';
					$submitted_value_set = '';
					$multiple_values = '';
					$first_item_active = 'No';
					
					$field_auto_complete = '';
					
					if(!empty($form_fields_builder['auto_complete']))
					{
						$field_auto_complete = ' autocomplete="'.$form_fields_builder['auto_complete'].'"';
					}
					
					if($form_fields_builder['form_field_type'] == 'Textfield')
					{
						if(isset($form_values_submitted['form_'.$form_fields_builder['admin_name']])) { $submitted_value_set = $form_values_submitted['form_'.$form_fields_builder['admin_name']]; }
						
						if(!empty(${'validation_'.$form_fields_builder['admin_name']})) { $validate = '<div class="dynamic_form_validation">'.${'validation_'.$form_fields_builder['admin_name']}.'</div>'; } else { $validate = $form_fields_builder['frontend_name']; }
						
						$form_output .= '<li class="field-'.$form_fields_builder['admin_name'].'">
						<label>
						<div class="'.$form_fields_builder['name_class'].'">'.$validate.'</div>
						<input name="'.$form_fields_builder['admin_name'].'" type="text" value="'.$submitted_value_set.'"'.$field_auto_complete.' />
						</label>
						</li>
						';
					}
					elseif($form_fields_builder['form_field_type'] == 'Textarea')
					{
						if(isset($form_values_submitted['form_'.$form_fields_builder['admin_name']])) { $submitted_value_set = $form_values_submitted['form_'.$form_fields_builder['admin_name']]; }
						
						if(!empty(${'validation_'.$form_fields_builder['admin_name']})) { $validate = '<div class="dynamic_form_validation">'.${'validation_'.$form_fields_builder['admin_name']}.'</div>'; } else { $validate = $form_fields_builder['frontend_name']; }
						
						$form_output .= '<li class="textarea-full-width field-'.$form_fields_builder['admin_name'].'">
						<label>
						<div class="'.$form_fields_builder['name_class'].'">'.$validate.'</div>
						<textarea name="'.$form_fields_builder['admin_name'].'"'.$field_auto_complete.'>'.$submitted_value_set.'</textarea>
						</label>
						</li>
						';
					}
					elseif($form_fields_builder['form_field_type'] == 'Dropdown')
					{
						if(!empty(${'validation_'.$form_fields_builder['admin_name']})) { $validate = '<div class="dynamic_form_validation">'.${'validation_'.$form_fields_builder['admin_name']}.'</div>'; } else { $validate = $form_fields_builder['frontend_name']; }
						
						if(!empty($form_fields_builder['values']))
						{
							foreach($form_fields_builder['values'] as $form_fields_values_builder)
							{
								$submitted_value_set = '';
								if(isset($form_fields_builder['admin_name']) && isset($form_values_submitted['form_'.$form_fields_builder['admin_name']]) && $form_values_submitted['form_'.$form_fields_builder['admin_name']] == $form_fields_values_builder['value']) 
								{ 
									$submitted_value_set = ' selected'; 
								}
								
								$multiple_values .= '<option value="'.$form_fields_values_builder['value'].'"'.$submitted_value_set.'>'.$form_fields_values_builder['label'].'</option>';
							}
						}
						
						if(isset($form_fields_builder['swap_form_field']) && isset($form_fields_builder['swap_form_field']) && isset($form_fields['all_form_fields'][$form_fields_builder['swap_form_field']]) && !empty($form_fields['all_form_fields'][$form_fields_builder['swap_form_field']]))
						{
							$swap_fields = dynamicFormFieldsSwap($form_fields_builder['id'], $form_values_submitted['form_'.$form_fields_builder['admin_name']] ?? '', $form_values_submitted['form_'.$form_fields['all_form_fields'][$form_fields_builder['swap_form_field']]['admin_name']] ?? '', $form_fields_builder['admin_name'], $form_fields['all_form_fields'][$form_fields_builder['swap_form_field']]['admin_name']);
							
							echo $swap_fields['swap_jquery'];
						}
						
						$form_output .= '<li class="field-'.$form_fields_builder['admin_name'].'">
						<label>
						<div class="'.$form_fields_builder['name_class'].'">'.$validate.'</div>
						<select name="'.$form_fields_builder['admin_name'].'" id="'.$form_fields_builder['admin_name'].'"'.$field_auto_complete.'>
						'.$multiple_values.'
						</select>
						</label>
						</li>
						';
					}
					elseif($form_fields_builder['form_field_type'] == 'Swatch')
					{
						$full_width = ' class="swatch-full-width"';
						
						if(!empty(${'validation_'.$form_fields_builder['admin_name']})) { $validate = '<div class="dynamic_form_validation">'.${'validation_'.$form_fields_builder['admin_name']}.'</div>'; } else { $validate = $form_fields_builder['frontend_name']; }
						
						if(!empty($form_fields_builder['values']))
						{
							foreach($form_fields_builder['values'] as $form_fields_values_builder)
							{
								$submitted_value_set = '';
								$submitted_value_checked = '';
								
								if(isset($_POST["form_id"]) && isset($form_fields_builder['admin_name']) && isset($form_values_submitted['form_'.$form_fields_builder['admin_name']]) && $form_values_submitted['form_'.$form_fields_builder['admin_name']] == $form_fields_values_builder['value']) 
								{ 
									$submitted_value_set = ' form-field-selected'; 
									$submitted_value_checked = ' checked';
									$selected_ids .= $form_fields_builder['id'].'|'.$form_fields_values_builder['id'].',';
								}
								elseif(!isset($_POST["form_id"]) && $first_item_active == 'No')
								{
									$submitted_value_set = ' form-field-selected'; 
									$submitted_value_checked = ' checked';
									$selected_ids .= $form_fields_builder['id'].'|'.$form_fields_values_builder['id'].',';
									$first_item_active = 'Yes';
								}
								
								if(strpos($jquery_fields, '.'.$sql_form_rows['form_name_class'].' input[name='.$form_fields_builder['admin_name'].'], ') === false)
								{
									$jquery_fields .= '.'.$sql_form_rows['form_name_class'].' input[name='.$form_fields_builder['admin_name'].'], ';
									
									$jquery_variables .= "var ".$form_fields_builder['admin_name']."_selected = $('.".$sql_form_rows['form_name_class']." input[name=".$form_fields_builder['admin_name']."]:checked').data('ids'); ";
									
									$jquery_variables_matcher .= $form_fields_builder['admin_name']."_selected+','+";
									
									$jquery_remove_class .= "$('.".$sql_form_rows['form_name_class']." .".$form_fields_builder['admin_name']."').removeClass('form-field-selected'); ";
								}
								
								if($form_fields_values_builder['display'] == 'Color Number')
								{
									$multiple_values .= '
									<div class="swatch-wrapper">
									<label>
									
									<div class="swatch-size '.$form_fields_builder['admin_name'].' '.$form_fields_builder['admin_name'].'-'.$form_fields_values_builder['value'].''.$submitted_value_set.'">
									<style nonce="'.NONCE.'">.form-dynamic-color-number-'.$form_fields_builder['id'].'-'.$form_fields_values_builder['id'].' { background-color: '.$form_fields_values_builder['color_number'].'; }</style>
									<div class="color-border form-dynamic-color-number-'.$form_fields_builder['id'].'-'.$form_fields_values_builder['id'].'">
									<input class="display-none" name="'.$form_fields_builder['admin_name'].'" id="'.$form_fields_builder['admin_name'].'|'.$form_fields_builder['id'].'|'.$form_fields_values_builder['id'].'" type="radio" value="'.$form_fields_values_builder['value'].'"'.$field_auto_complete.' data-ids="'.$form_fields_builder['id'].'|'.$form_fields_values_builder['id'].'"'.$submitted_value_checked.' />
									</div>
									</div>
									<div class="swatch-text">'.$form_fields_values_builder['label'].'</div>
									</label>
									</div>';
								}
								elseif($form_fields_values_builder['display'] == 'Media Swatch')
								{
									//Start - get media for swatch.
									if(isset($form_fields_values_builder['media']) && !empty($form_fields_values_builder['media']))
									{
										$media_array = explode('~||~', $form_fields_values_builder['media']);
										
										$get_media_swatch = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$media_array[0]]);
										
										if(!empty($media_array[1]))
										{
											$get_media_swatch['media_tag'] = $media_array[1];
										}
										
										if(empty($get_media_swatch))
										{
											$get_media_swatch = array('media' => 'image-coming-soon-375-375.gif', 'media_tag' => 'Image Coming Soon - 375px - 375px');
										}
									}
									else
									{
										$get_media_swatch = array('media' => 'image-coming-soon-375-375.gif', 'media_tag' => 'Image Coming Soon - 375px - 375px');
									}
									//End - get media for swatch.
									
									if($get_media_swatch['media_type'] == 'Image')
									{
										$swatch_media = mediaId($get_media_swatch['id'], 'lazyLoadNo', 'fetchPriorityAuto', $get_media_swatch['media_tag']);
									}
									elseif($get_media_swatch['media_type'] == 'File')
									{
										$swatch_media = $_SESSION['media_file_icon'];
									}
									elseif($get_media_swatch['media_type'] == 'Video')
									{
										$swatch_media = $_SESSION['media_video_icon'];
									}
									elseif($get_media_swatch['media_type'] == 'Video Embed')
									{
										$swatch_media = $_SESSION['media_video_icon'];
									}
									
									$multiple_values .= '
									<div class="swatch-wrapper">
									<label>
										<div class="swatch-size '.$form_fields_builder['admin_name'].' '.$form_fields_builder['admin_name'].'-'.$form_fields_values_builder['value'].''.$submitted_value_set.'">
											<div class="color-border">
												'.$swatch_media.'
												<input class="display-none" name="'.$form_fields_builder['admin_name'].'" id="'.$form_fields_builder['admin_name'].'|'.$form_fields_builder['id'].'|'.$form_fields_values_builder['id'].'" type="radio" value="'.$form_fields_values_builder['value'].'"'.$field_auto_complete.' data-ids="'.$form_fields_builder['id'].'|'.$form_fields_values_builder['id'].'"'.$submitted_value_checked.' />
											</div>
										</div>
										<div class="swatch-text">'.$form_fields_values_builder['label'].'</div>
									</label>
									</div>';
								}
								
								$create_array_for_jquery[$form_fields_builder['admin_name']][','.$form_fields_builder['id'].'|'.$form_fields_values_builder['id'].','] = $form_fields_values_builder;
							}
						}
						
						$form_output .= '<li'.$full_width.'>
						<div class="'.$form_fields_builder['name_class'].'">'.$validate.'</div>
						<div>'.$multiple_values.'</div>
						</li>
						';
					}
				}
				
				$jquery_fields = trim($jquery_fields, ', ');
				
				$selected_ids = ','.$selected_ids;
				
				$jquery_variables_matcher = "','+".trim($jquery_variables_matcher,'+');
				
				$all_variants = array();
				$first_item = '';
				$manufacturer = '';
				$item_number = '';
				$notes = '';
				if(!empty($create_array_for_jquery))
				{
					function variants($form_values, $selected_ids, $form_id, $data_array_media) 
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
									$get_form_media = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_media', 'WHERE `form_id` = ? AND `value_ids` = ?', [$form_id, $final_key]);
									
									if(!empty($get_form_media))
									{
										$append_values[$final_key]['swatch_data_array'] = $get_form_media;
										
										if(!empty($get_form_media['media']))
										{
											$get_form_media['media'] = $get_form_media['media'];
										}
										elseif(empty($get_form_media['media']) && !empty($data_array_media))
										{
											$get_form_media['media'] = $data_array_media;
											$append_values[$final_key]['swatch_data_array']['media'] = $data_array_media;
										}
										else
										{
											$get_form_media['media'] = '7~||~No Media Set';
											$append_values[$final_key]['swatch_data_array']['media'] = '7~||~No Media Set';
										}
										
										$first_media_id = '';
										if(strpos($get_form_media['media'], '*||*') !== false)
										{
											$media_id_array = explode('*||*', $get_form_media['media']);
											$first_media_data = explode('~||~', $media_id_array[0]);
											$first_media_id = $first_media_data[0];
										}
										elseif(strpos($get_form_media['media'], '~||~') !== false)
										{
											$media_id_array = explode('~||~', $get_form_media['media']);
											$first_media_id = $media_id_array[0];
										}
										
										if(isset($_POST["form_id"]) && $final_key == $selected_ids)
										{
											$append_values['selected_item_data']['first_media_id'] = $first_media_id; 
											$append_values['selected_item_data']['form_media_id'] = $get_form_media['id'];
										}
										elseif(!isset($_POST["form_id"]) && empty($first_item))
										{
											$first_item = 'Yes'; 
											$append_values['selected_item_data']['first_media_id'] = $first_media_id; 
											$append_values['selected_item_data']['form_media_id'] = $get_form_media['id'];
										}
									}
									//If NO media set on form swatch, get media from product page and use on slider.
									elseif(!empty($data_array_media))
									{
										$append_values[$final_key]['swatch_data_array']['id'] = '';
										$append_values[$final_key]['swatch_data_array']['form_id'] = $form_id;
										$append_values[$final_key]['swatch_data_array']['value_ids'] = $final_key;
										$append_values[$final_key]['swatch_data_array']['media'] = $data_array_media;
										$append_values[$final_key]['swatch_data_array']['form_media_id'] = '';
									}
									else
									{
										$append_values[$final_key]['swatch_data_array']['id'] = '';
										$append_values[$final_key]['swatch_data_array']['form_id'] = $form_id;
										$append_values[$final_key]['swatch_data_array']['value_ids'] = $final_key;
										$append_values[$final_key]['swatch_data_array']['media'] = '7~||~No Media Set';
										$append_values[$final_key]['swatch_data_array']['form_media_id'] = '';
									}
									//End - get swatch image id.
								}
							}
							$result = $append_values;
						}
						return $result;
					}
					
					$all_variants = variants($create_array_for_jquery, $selected_ids, $sql_form_rows['id'], $data_array_media);
				}
				
				$media_ids_array = array();
				$get_media = array();
				
				if(!empty($all_variants))
				{
					$sliders_counter = 20000;
					foreach($all_variants as $main_key => $main_values)
					{
						if($main_key != 'selected_item_data')	
						{
							$jquery_selection .= "else if('".$main_key."' == ".$jquery_variables_matcher.") { 
							";
							
							foreach($main_values as $key => $values)
							{
								if($key != 'swatch_data_array')
								{
									if(strpos($jquery_selection, "$('#slider-".$sliders_counter."').removeClass('hide-slider');") === false)
									{
										$form_media_id = '';
										if(isset($main_values['swatch_data_array']['id']) && !empty($main_values['swatch_data_array']['id']))
										{
											$form_media_id = htmlspecialchars($main_values['swatch_data_array']['id']);
										}
										
										$jquery_selection .= "
									$('#slider-".$sliders_counter."').removeClass('hide-slider');
									$('.".$sql_form_rows['form_name_class']." input[name=form_media_id]').val('".$form_media_id."');
									";
									}
									
									$first_media_id = '';
									if(strpos($main_values['swatch_data_array']['media'], '*||*') !== false)
									{
										$media_id_array = explode('*||*', $main_values['swatch_data_array']['media']);
										$first_media_data = explode('~||~', $media_id_array[0]);
										$first_media_id = $first_media_data[0];
									}
									elseif(strpos($main_values['swatch_data_array']['media'], '~||~') !== false)
									{
										$media_id_array = explode('~||~', $main_values['swatch_data_array']['media']);
										$first_media_id = $media_id_array[0];
									}
									
									$jquery_selection .= "$('.".$sql_form_rows['form_name_class']." .".$key."-".$values['value']."').addClass('form-field-selected');
									//Recalculate slider widths after showing hidden slider
									window.dispatchEvent(new Event('resize'));
									";
								}
								elseif($key == 'swatch_data_array')
								{
									//Start - get media for each variant.
									$media_ids_array = explode('*||*', $values['media']);
									
									foreach($media_ids_array as $media_ids)
									{
										$media_array = explode('~||~', $media_ids);
										
										$sql_get_media = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$media_array[0]]);
										
										if(!empty($media_array[1]))
										{
											$sql_get_media['media_tag'] = $media_array[1];
										}
										
										if(!empty($sql_get_media))
										{
											$get_media[$values['value_ids']][] = $sql_get_media;
										}
									}
									//End - get media for each variant.
								}
							}
							
							$jquery_selection .= "
							}
							";
							
							$sliders_counter++;
						
						}
					}
				}
				
				$jquery_selection = trim($jquery_selection, 'else ');
				
				$slider_media = '';
				
				if(!empty($get_media))
				{
					$slider_counter = 20000;
					foreach($get_media as $main_key => $main_value)
					{
						$slider_media_items = '';
						$slider_media_pagers = '';
						$pager_counter = 0;
						
						///////////////Start slider settings///////////////
						$data_array['slider_settings']['slides_in_view'] = $data_array['slides_in_view'] ?? 1; //Max number of slides to show at once in viewport
						$data_array['slider_settings']['slide_all_at_once'] = $data_array['slide_all_at_once'] ?? 'No'; //New variable: "yes" to slide all at once, "no" to slide one at a time
						$data_array['slider_settings']['min_slide_width'] = $data_array['slide_minimum_width'] ?? 200; //Min slide width for responsive to display less slides in viewport (in pixels)
						$data_array['slider_settings']['should_auto_slide'] = $data_array['auto_slide_media'] ?? 'No'; //Auto slide slideshow (yes or no)
						$data_array['slider_settings']['pause_time'] = $data_array['pause_time'] ?? 8000; //Slide pause time between each slide
						$data_array['slider_settings']['slide_speed'] = $data_array['slide_speed'] ?? 500; //Slide transition speed
						$data_array['slider_settings']['slide_margin'] = $data_array['slide_margin'] ?? 0; //Slider gap or marin for left and right gap
						$data_array['slider_settings']['use_pagination'] = $data_array['display_pagination'] ?? 'Yes'; //Controls whether to display pagination (thumbnails or bullets) - yes or no
						$data_array['slider_settings']['pagination_align'] = $data_array['pagination_alignment'] ?? 'left'; //CSS - Pagination left, center, right
						$data_array['slider_settings']['pagination_over_image'] = $data_array['pagination_over_image'] ?? 'No'; //CSS - Display pagination over slide image. yes or no
						$data_array['slider_settings']['use_thumbnails'] = $data_array['display_thumbnails'] ?? 'Yes'; //Controls whether to display thumbnails or bullets - "yes" for Thumbnails, "no" for bullets
						$data_array['slider_settings']['thumbnail_width'] = $data_array['pagination_thumbnail_width'] ?? 40; //Pager thumbnail width
						$data_array['slider_settings']['pagination_margin'] = $data_array['pagination_margin'] ?? 5; //Pager margin
						
						//Get correct count of slides to show based on device type. This is important for Cumulative Layout Shifts.
						$slides_in_viewport = getDeviceType($data_array['slider_settings']['min_slide_width'], $data_array['slider_settings']['slides_in_view']);
						
						//If mobile, only use bullets, no thumnails as screen is small.
						if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(iphone|ipod|android.*mobile|windows.*phone|blackberry|webos)/i', $_SERVER['HTTP_USER_AGENT']))
						{
							$data_array['display_thumbnails'] = 'No';
							$data_array['slider_settings']['use_thumbnails'] = 'No';
						}
						
						//Adjust bottom and padding if thumbnails vers bullets.
						$set_pagination_over_image = '';
						if($data_array['slider_settings']['use_pagination'] == 'Yes' && $data_array['slider_settings']['pagination_over_image'] == 'Yes')
						{
							$set_pagination_over_image = ' .slider_'.$slider_counter.' .slider-pager { position: absolute; bottom: 0px; width: 100%; }';
						}
						elseif($data_array['slider_settings']['use_pagination'] == 'No')
						{
							$set_pagination_over_image = ' .slider_'.$slider_counter.' .slider-pager { display: none; }';
						}
						
						//Align thumbnails.
						$set_pagination_center = '';
						if($data_array['slider_settings']['pagination_align'] == 'center')
						{
							$set_pagination_center = ' .slider_'.$slider_counter.' .slider-pager { justify-content: center; }';
						}
						elseif($data_array['slider_settings']['pagination_align'] == 'right')
						{
							$set_pagination_center = ' .slider_'.$slider_counter.' .slider-pager { justify-content: flex-end; }';
						}
						
						//Set pager gap / margin.
						$set_pagination_gap = '';
						if($data_array['slider_settings']['pagination_margin'] !== NULL)
						{
							$set_pagination_gap = ' .slider_'.$slider_counter.' .slider-pager { gap: '.$data_array['slider_settings']['pagination_margin'].'px; }';
						}
						
						//Set pager with and height is auto.
						$set_thumbnail_width = '';
						if($data_array['slider_settings']['thumbnail_width'] !== NULL)
						{
							$set_thumbnail_width = ' .slider_'.$slider_counter.' .thumbnail { width: '.$data_array['slider_settings']['thumbnail_width'].'px; }';
						}
						
						$slider_media_count = 1; //If both inventory and product page media empty, set count of 1 for default media.
						
						//Get media count on product.
						if(!empty($main_value))
						{
							$slider_media_count = count($main_value);
						}
						///////////////End slider settings///////////////
						
						foreach($main_value as $key => $value)
						{
							$slider_media_output = mediaId($value['id'], 'lazyLoadNo', 'fetchPriorityAuto', $value['media_tag']);
							
							$media_output = str_replace(' class="max-width-height-display"', '', $slider_media_output);
							$slider_media_items .= '<div class="container">'.$media_output.'</div>';
							
							if($data_array['slider_settings']['use_pagination'] == 'Yes')
							{
								if($data_array['slider_settings']['use_thumbnails'] == 'No')
								{
									$slider_media_pagers .= '<span class="pager" data-index="'.$pager_counter.'"></span>';
								}
								elseif($value['media_type'] == 'Image')
								{
									$slider_media_pagers .= str_replace('<picture>', '<picture class="thumbnail" data-index="'.$pager_counter.'">', $media_output);
								}
								elseif($value['media_type'] == 'File')
								{
									$slider_media_pagers .= str_replace('<picture>', '<picture class="thumbnail" data-index="'.$pager_counter.'">', $_SESSION['media_file_icon']);
								}
								elseif($value['media_type'] == 'Video')
								{
									$slider_media_pagers .= str_replace('<picture>', '<picture class="thumbnail" data-index="'.$pager_counter.'">', $_SESSION['media_video_icon']);
								}
								elseif($value['media_type'] == 'Video Embed')
								{
									$slider_media_pagers .= str_replace('<picture>', '<picture class="thumbnail" data-index="'.$pager_counter.'">', $_SESSION['media_video_icon']);
								}
								
								$pager_counter ++;
							}
						}
						
						if(isset($_POST["form_id"]) && $main_key != $selected_ids) { $hide_slier_class = ' hide-slider'; }
						elseif(!isset($_POST["form_id"]) && $slider_counter != 20000) { $hide_slier_class = ' hide-slider'; }
						else { $hide_slier_class = ''; }
						
						if(count($main_value) > 1)
						{
							//Set css and call slider js based on slider settings.
							$slider_media .= "
							<style nonce=\"".NONCE."\">
							.slider_".$slider_counter." .slider-holder { gap: ".($data_array['slider_settings']['slide_margin'] ?? 0)."px; } .slider_".$slider_counter." .container { width: calc(100% / ".$slides_in_viewport."); } .slider_".$slider_counter." .slider-pager { padding: ".($data_array['slider_settings']['pagination_margin'])."px 0px; }".$set_pagination_over_image.$set_pagination_center.$set_thumbnail_width.$set_pagination_gap."
							</style>
							<script nonce=\"".NONCE."\">
							var videoIconSrc = `".$_SESSION['media_video_icon_no_picture_tag']."`;
							var FileIconSrc = `".$_SESSION['media_file_icon_no_picture_tag']."`;
							if(document.readyState === 'loading')
							{
								document.addEventListener('DOMContentLoaded', function () {
									slider('.slider_".$slider_counter."', '".strtolower($data_array['slider_settings']['should_auto_slide'])."', '".strtolower($data_array['slider_settings']['use_pagination'])."', '".strtolower($data_array['slider_settings']['use_thumbnails'])."', '".strtolower($data_array['slider_settings']['slide_all_at_once'])."', ".$slides_in_viewport.", ".$data_array['slider_settings']['slide_speed'].", ".$data_array['slider_settings']['pause_time'].", ".$data_array['slider_settings']['slide_margin'].", ".$data_array['slider_settings']['min_slide_width'].", ".$slides_in_viewport.", ".$slider_media_count.", videoIconSrc, FileIconSrc);
								});
							}
							else
							{
								slider('.slider_".$slider_counter."', '".strtolower($data_array['slider_settings']['should_auto_slide'])."', '".strtolower($data_array['slider_settings']['use_pagination'])."', '".strtolower($data_array['slider_settings']['use_thumbnails'])."', '".strtolower($data_array['slider_settings']['slide_all_at_once'])."', ".$slides_in_viewport.", ".$data_array['slider_settings']['slide_speed'].", ".$data_array['slider_settings']['pause_time'].", ".$data_array['slider_settings']['slide_margin'].", ".$data_array['slider_settings']['min_slide_width'].", ".$slides_in_viewport.", ".$slider_media_count.", videoIconSrc, FileIconSrc);
							}
							</script>";
						
							$slider_media .= '<!-- Start Slider -->
							<div id="slider-'.$slider_counter.'" class="slider_'.$slider_counter.' slider-wrapper sliders'.$hide_slier_class.'">
								<div class="slider">
									<div class="prev">&#8249;</div>
									<div class="next">&#8250;</div>
									<div class="slider-holder">
										'.$slider_media_items.'
									</div>
									<div class="slider-pager">
										'.$slider_media_pagers.'
									</div>
								</div>
							</div>
							<!-- End Slider -->';
						}
						else
						{
							$slider_media .= '<!-- Start Slider -->
							<div id="slider-'.$slider_counter.'" class="slider_'.$slider_counter.' slider-wrapper sliders'.$hide_slier_class.'">
								<div class="slider">
								
									<div class="slider-holder">
										'.$slider_media_items.'
									</div>
								</div>
							</div>
							<!-- End Slider -->';
						}
	
						$slider_counter++;
					}
				}
				
				include_once(INSTALLATION_ROOT.'/sites/slider-js.php');
				
				echo "
				<script nonce=\"".NONCE."\">
						
						$(document).ready(function()
						{
						  $('".$jquery_fields."').on('change', function()
						  {
							 ".$jquery_variables."
							  
							  //alert(".$jquery_variables_matcher.");
							  $('.sliders').addClass('hide-slider');
							  
							  ".$jquery_remove_class."
							  
							  ".$jquery_selection."
						  });
						  
						});
						</script>
						";
			}
			
			$form_call_phone_number = '';
			if(!empty($sql_form_rows['call_phone_number']))
			{
				$form_call_phone_number = '<span class="'.$sql_form_rows['call_phone_number_text_class'].'">'.$sql_form_rows['call_phone_number_text'].' <a href="tel:'.$sql_form_rows['call_phone_number'].'" class="'.$sql_form_rows['call_phone_number_class'].'">'.$sql_form_rows['display_call_phone_number'].'</a></span>';
			}
			
			$form_text_phone_number = '';
			if(!empty($sql_form_rows['sms_phone_number']))
			{
				$form_text_phone_number = '<span class="'.$sql_form_rows['sms_phone_number_text_class'].'">'.$sql_form_rows['sms_phone_number_text'].' <a href="sms:+'.$sql_form_rows['sms_phone_number'].'" class="'.$sql_form_rows['sms_phone_number_class'].'">'.$sql_form_rows['display_sms_phone_number'].'</a></span>';
			}
			
			$form_sub_text = '';
			if(!empty($sql_form_rows['sub_text']))
			{
				$form_sub_text = '<div class="'.$sql_form_rows['sub_text_class'].'">'.$sql_form_rows['sub_text'].'</div>';
			}
			
			$product_data = '';
			if(isset($all_variants['selected_item_data'])) { $product_data = '<input name="form_media_id" type="hidden" value="'.$all_variants['selected_item_data']['form_media_id'].'" />'; }
			
			$form_output .= 
			'</ul>
			'.$product_data.'
			<div class="'.$sql_form_rows['submit_button_text_class'].'"><button name="'.$sql_form_rows['admin_form_name'].'" type="submit">'.$sql_form_rows['submit_button_text'].'</button></div>'.$form_call_phone_number.''.$form_text_phone_number.''.$form_sub_text.'<input name="form_id" type="hidden" value="'.$sql_form_rows['id'].'" />
			</form>
			';
			
			//if form has swatch media set, use that media as product images.
			if(!empty($slider_media))
			{
				$form_output = '
				<div class="offer-wrap container-width">
					<div class="offer-inner-wrap"> 
						<div class="flex-row">
							<!-- Start Product Media -->
							<div class="product-media">'.$slider_media.'</div> 
							<!-- End Product Media -->
							<!-- Start Product Selection -->
							<div class="product-selection">'.$form_output.'</div> 
							<!-- End Product Selection -->
						</div>
					</div>
				</div>
				';
			}
			//if product has media set, use that media as product images.
			elseif(!empty($product_media_array_form))
			{
				$slider_media_items = '';
				$slider_media_pagers = '';
				$pager_counter = 0;
				
				///////////////Start slider settings///////////////
				$data_array['slider_settings']['slides_in_view'] = $data_array['slides_in_view'] ?? 1; //Max number of slides to show at once in viewport
				$data_array['slider_settings']['slide_all_at_once'] = $data_array['slide_all_at_once'] ?? 'No'; //New variable: "yes" to slide all at once, "no" to slide one at a time
				$data_array['slider_settings']['min_slide_width'] = $data_array['slide_minimum_width'] ?? 200; //Min slide width for responsive to display less slides in viewport (in pixels)
				$data_array['slider_settings']['should_auto_slide'] = $data_array['auto_slide_media'] ?? 'No'; //Auto slide slideshow (yes or no)
				$data_array['slider_settings']['pause_time'] = $data_array['pause_time'] ?? 8000; //Slide pause time between each slide
				$data_array['slider_settings']['slide_speed'] = $data_array['slide_speed'] ?? 500; //Slide transition speed
				$data_array['slider_settings']['slide_margin'] = $data_array['slide_margin'] ?? 0; //Slider gap or marin for left and right gap
				$data_array['slider_settings']['use_pagination'] = $data_array['display_pagination'] ?? 'Yes'; //Controls whether to display pagination (thumbnails or bullets) - yes or no
				$data_array['slider_settings']['pagination_align'] = $data_array['pagination_alignment'] ?? 'left'; //CSS - Pagination left, center, right
				$data_array['slider_settings']['pagination_over_image'] = $data_array['pagination_over_image'] ?? 'No'; //CSS - Display pagination over slide image. yes or no
				$data_array['slider_settings']['use_thumbnails'] = $data_array['display_thumbnails'] ?? 'Yes'; //Controls whether to display thumbnails or bullets - "yes" for Thumbnails, "no" for bullets
				$data_array['slider_settings']['thumbnail_width'] = $data_array['pagination_thumbnail_width'] ?? '40'; //Pager thumbnail width
				$data_array['slider_settings']['pagination_margin'] = $data_array['pagination_margin'] ?? 5; //Pager margin
				
				//Get correct count of slides to show based on device type. This is important for Cumulative Layout Shifts.
				$slides_in_viewport = getDeviceType($data_array['slider_settings']['min_slide_width'], $data_array['slider_settings']['slides_in_view']);
				
				//If mobile, only use bullets, no thumnails as screen is small.
				if(isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(iphone|ipod|android.*mobile|windows.*phone|blackberry|webos)/i', $_SERVER['HTTP_USER_AGENT']))
				{
					$data_array['display_thumbnails'] = 'No';
					$data_array['slider_settings']['use_thumbnails'] = 'No';
				}
				
				//Adjust bottom and padding if thumbnails vers bullets.
				$set_pagination_over_image = '';
				if($data_array['slider_settings']['use_pagination'] == 'Yes' && $data_array['slider_settings']['pagination_over_image'] == 'Yes')
				{
					$set_pagination_over_image = ' .slider_'.$data_array['record_id'].' .slider-pager { position: absolute; bottom: 0px; width: 100%; }';
				}
				elseif($data_array['slider_settings']['use_pagination'] == 'No')
				{
					$set_pagination_over_image = ' .slider_'.$data_array['record_id'].' .slider-pager { display: none; }';
				}
				
				//Align thumbnails.
				$set_pagination_center = '';
				if($data_array['slider_settings']['pagination_align'] == 'center')
				{
					$set_pagination_center = ' .slider_'.$data_array['record_id'].' .slider-pager { justify-content: center; }';
				}
				elseif($data_array['slider_settings']['pagination_align'] == 'right')
				{
					$set_pagination_center = ' .slider_'.$data_array['record_id'].' .slider-pager { justify-content: flex-end; }';
				}
				
				//Set pager gap / margin.
				$set_pagination_gap = '';
				if($data_array['slider_settings']['pagination_margin'] !== NULL)
				{
					$set_pagination_gap = ' .slider_'.$data_array['record_id'].' .slider-pager { gap: '.$data_array['slider_settings']['pagination_margin'].'px; }';
				}
				
				//Set pager with and height is auto.
				$set_thumbnail_width = '';
				if($data_array['slider_settings']['thumbnail_width'] !== NULL)
				{
					$set_thumbnail_width = ' .slider_'.$data_array['record_id'].' .thumbnail { width: '.$data_array['slider_settings']['thumbnail_width'].'px; }';
				}
				
				$slider_media_count = 1; //If both inventory and product page media empty, set count of 1 for default media.
				
				//Get media count on product.
				if(!empty($product_media_array_form))
				{
					$slider_media_count = count($product_media_array_form);
				}
				///////////////End slider settings///////////////
				
				foreach($product_media_array_form as $product_media_array)
				{
					$slider_media_output = mediaId($product_media_array['id'], 'lazyLoadNo', 'fetchPriorityAuto', $product_media_array['media_tag']);
					
					$media_output = str_replace(' class="max-width-height-display"', '', $slider_media_output);
					$slider_media_items .= '<div class="container">'.$media_output.'</div>';
					
					if($data_array['slider_settings']['use_pagination'] == 'Yes')
					{
						if($data_array['slider_settings']['use_thumbnails'] == 'No')
						{
							$slider_media_pagers .= '<span class="pager" data-index="'.$pager_counter.'"></span>';
						}
						elseif($product_media_array['media_type'] == 'Image')
						{
							$slider_media_pagers .= str_replace('<picture>', '<picture class="thumbnail" data-index="'.$pager_counter.'">', $media_output);
						}
						elseif($product_media_array['media_type'] == 'File')
						{
							$slider_media_pagers .= str_replace('<picture>', '<picture class="thumbnail" data-index="'.$pager_counter.'">', $_SESSION['media_file_icon']);
						}
						elseif($product_media_array['media_type'] == 'Video')
						{
							$slider_media_pagers .= str_replace('<picture>', '<picture class="thumbnail" data-index="'.$pager_counter.'">', $_SESSION['media_video_icon']);
						}
						elseif($product_media_array['media_type'] == 'Video Embed')
						{
							$slider_media_pagers .= str_replace('<picture>', '<picture class="thumbnail" data-index="'.$pager_counter.'">', $_SESSION['media_video_icon']);
						}
						
						$pager_counter ++;
					}
				}
				
				if(count($product_media_array_form) > 1)
				{
					//Set css and call slider js based on slider settings.
					$slider_media = "
					<style nonce=\"".NONCE."\">
					.slider_".$data_array['record_id']." .slider-holder { gap: ".($data_array['slider_settings']['slide_margin'] ?? 0)."px; } .slider_".$data_array['record_id']." .container { width: calc(100% / ".$slides_in_viewport."); } .slider_".$data_array['record_id']." .slider-pager { padding: ".($data_array['slider_settings']['pagination_margin'])."px 0px; }".$set_pagination_over_image.$set_pagination_center.$set_thumbnail_width.$set_pagination_gap."
					</style>
					<script nonce=\"".NONCE."\">
					var videoIconSrc = `".$_SESSION['media_video_icon_no_picture_tag']."`;
					var FileIconSrc = `".$_SESSION['media_file_icon_no_picture_tag']."`;
					if(document.readyState === 'loading')
					{
						document.addEventListener('DOMContentLoaded', function () {
							slider('.slider_".$data_array['record_id']."', '".strtolower($data_array['slider_settings']['should_auto_slide'])."', '".strtolower($data_array['slider_settings']['use_pagination'])."', '".strtolower($data_array['slider_settings']['use_thumbnails'])."', '".strtolower($data_array['slider_settings']['slide_all_at_once'])."', ".$slides_in_viewport.", ".$data_array['slider_settings']['slide_speed'].", ".$data_array['slider_settings']['pause_time'].", ".$data_array['slider_settings']['slide_margin'].", ".$data_array['slider_settings']['min_slide_width'].", ".$slides_in_viewport.", ".$slider_media_count.", videoIconSrc, FileIconSrc);
						});
					}
					else
					{
						slider('.slider_".$data_array['record_id']."', '".strtolower($data_array['slider_settings']['should_auto_slide'])."', '".strtolower($data_array['slider_settings']['use_pagination'])."', '".strtolower($data_array['slider_settings']['use_thumbnails'])."', '".strtolower($data_array['slider_settings']['slide_all_at_once'])."', ".$slides_in_viewport.", ".$data_array['slider_settings']['slide_speed'].", ".$data_array['slider_settings']['pause_time'].", ".$data_array['slider_settings']['slide_margin'].", ".$data_array['slider_settings']['min_slide_width'].", ".$slides_in_viewport.", ".$slider_media_count.", videoIconSrc, FileIconSrc);
					}
					</script>";
					
					$slider_media .= '<!-- Start Slider -->
					<div class="slider_'.$data_array['record_id'].' slider-wrapper">
						<div class="slider">
							<div class="prev">&#8249;</div>
							<div class="next">&#8250;</div>
							<div class="slider-holder container-width">
								'.$slider_media_items.'
							</div>
							<div class="slider-pager">
								'.$slider_media_pagers.'
							</div>
						</div>
					</div>
					<!-- End Slider -->';
				}
				else
				{
					$slider_media = '<!-- Start Slider -->
					<div class="slider-wrapper">
						<div class="slider">
							<div class="slider-holder container-width">
								'.$slider_media_items.'
							</div>
						</div>
					</div>
					<!-- End Slider -->';
				}
				
				$form_output = '
				<div class="offer-wrap container-width">
					<div class="offer-inner-wrap"> 
						<div class="flex-row">
							<!-- Start Product Media -->
							<div class="product-media">'.$slider_media.'</div> 
							<!-- End Product Media -->
							<!-- Start Product Selection -->
							<div class="product-selection">'.$form_output.'</div> 
							<!-- End Product Selection -->
						</div>
					</div>
				</div>
				';
			}
			//if no media set, show form only 100% wide.
			else
			{
				$form_output = '
				<div class="container-width">
					<!-- Start Product Selection -->
					'.$form_output.'
					<!-- End Product Selection -->
				</div>
				';
			}
		
			return $form_output;
		}
		else
		{
			$no_form_field_in_db = 'Form id '.htmlspecialchars($get_from_id ?? '').' is disabled or has been deleted in database.';
			return $no_form_field_in_db;
		}
	}
	//echo form_id(1);
	
	if($_SERVER['REQUEST_URI'] != '/favicon.ico' && $_SERVER['REQUEST_URI'] != '/favicon/')
	{
		//Increment pageviews
		$_SESSION['form_pageviews'] ++;
		
		//Add click path
		$_SESSION['click_path'][] = $_SERVER['REQUEST_URI'];
	}
}