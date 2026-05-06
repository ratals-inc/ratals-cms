<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/admin-field-lists-swap.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/admin-field-lists-swap.php');
}
else
{
	//This is used in admin area to swap fields.
	if(!function_exists('adminFieldsListsSwap'))
	{
		function adminFieldsListsSwap($main_field_id, $posted_main_list_value, $posted_swap_list_value, $main_admin_field_css_id, $swap_admin_field_css_id)
		{
			$all = array();
			$swap_jquery = '';
			
			//Parent list definition
			$main_field_data = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `id` = ?', [$main_field_id]);
			
			if(!empty($main_field_data))
			{
				//Field being swapped (ex: state)
				$admin_field_to_swap = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields', 'WHERE `id` = ?', [$main_field_data['swap_admin_field']]);
				
				//Controlling options (ex: countries)
				$main_fields_lists = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$main_field_data['system_code']]);
				
				if(!empty($main_fields_lists))
				{
					foreach($main_fields_lists as $main_fields_list)
					{
						//Label metadata for .edit-label
						$swap_list_meta = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'admin_fields_lists', 'WHERE `system_code` = ?', [$main_fields_list['swap_admin_field_to']]);
						
						$swap_label = !empty($swap_list_meta['name']) ? $swap_list_meta['name'] : '';
						
						//Fetch swap values
						$swap_fields_lists = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', [$main_fields_list['swap_admin_field_to']]);
						
						//SELECT MODE
						if(!empty($swap_fields_lists))
						{
							$swap_field_records = '';
							
							foreach($swap_fields_lists as $swap_fields_list)
							{
								$swap_field_selected = '';
								
								if(isset($posted_swap_list_value[$admin_field_to_swap['column_name']]) && $posted_swap_list_value[$admin_field_to_swap['column_name']] == $swap_fields_list['value'])
								{
									$swap_field_selected = ' selected';
								}
								
								$swap_field_records .= '<option value="'.$swap_fields_list['value'].'"'.$swap_field_selected.'>'.$swap_fields_list['label'].'</option>';
							}
		
							$all[$main_fields_list['value']] = array('type' => 'select', 'html' => $swap_field_records, 'label' => $swap_label);
						}
						else
						{
							//TEXT MODE (no values exist)
							$posted_text_value = '';
							
							if(!empty($posted_swap_list_value[$admin_field_to_swap['column_name']]))
							{
								$posted_text_value = $posted_swap_list_value[$admin_field_to_swap['column_name']];
							}
							
							$all[$main_fields_list['value']] = array('type' => 'text', 'label' => $swap_label, 'value' => $posted_text_value);
						}
					}
				}
			}
			
			//JS SWAP HANDLER
			if(!empty($all))
			{
				$fieldClass = str_replace('_', '-', $admin_field_to_swap['column_name']);
				
				$swap_jquery .= '
				<script nonce="'.NONCE.'">
				jQuery(function($)
				{
					function renderSwap(mode, labelText, htmlOrValue, existingValue)
					{
						var wrapper   = $(".edit.'.$fieldClass.'");
						var labelNode = wrapper.find(".edit-label");
						var fieldNode = wrapper.find(".edit-field");
						
						labelNode.text(labelText);
						
						if(mode === "text")
						{
							fieldNode.html(\'<input type="text" name="'.$swap_admin_field_css_id.'['.$admin_field_to_swap['column_name'].']" id="'.$swap_admin_field_css_id.'_'.$admin_field_to_swap['column_name'].'" value="\' + (htmlOrValue !== "" ? htmlOrValue : existingValue) + \'" /><div class="small-text"></div>\');
						}
						else
						{
							fieldNode.html(\'<select name="'.$swap_admin_field_css_id.'['.$admin_field_to_swap['column_name'].']" id="'.$swap_admin_field_css_id.'_'.$admin_field_to_swap['column_name'].'">\' + htmlOrValue + \'</select><div class="small-text"></div>\');
							
							if(existingValue !== "")
							{
								$("#'.$swap_admin_field_css_id.'_'.$admin_field_to_swap['column_name'].'").val(existingValue);
							}
						}
					}
					
					function applySwap(selected)
					{
						//Do nothing if no parent value selected
						if(selected === "" || selected === null || selected === undefined)
						{
							return;
						}
						
						var existingValue = "";
						
						if($("#'.$swap_admin_field_css_id.'_'.$admin_field_to_swap['column_name'].'").length)
						{
							existingValue = $("#'.$swap_admin_field_css_id.'_'.$admin_field_to_swap['column_name'].'").val();
						}
				';
				
						foreach($all as $key => $value)
						{
							$label = $value['label'];
							
							if($value['type'] === 'text')
							{
								$swap_jquery .= '
								if(selected == "'.$key.'")
								{
									renderSwap("text", "'.$label.'", "'.$value['value'].'", existingValue);
								}';
							}
							else
							{
								$swap_jquery .= '
								if(selected == "'.$key.'")
								{
									renderSwap("select", "'.$label.'", \''.$value['html'].'\', existingValue);
								}';
							}
						}
						
					$swap_jquery .= '
					}
					
					//run on load
					applySwap($("#'.$main_admin_field_css_id.'").val());
				
					//run on change
					$("#'.$main_admin_field_css_id.'").change(function()
					{
						applySwap($(this).val());
					});
				});
				</script>';
			}
			
			return array('swap_jquery' => $swap_jquery);
		}
	}
	
	//This is used in /load-sites/forms.php to swap fields or in dynamic forms created in admin and embeded in templates.
	if(!function_exists('dynamicFormFieldsSwap'))
	{
		function dynamicFormFieldsSwap($main_field_id, $posted_main_list_value, $posted_swap_list_value, $main_admin_field_css_id, $swap_admin_field_css_id)
		{
			$all = [];
			$swap_jquery = '';
			
			//Normalize submitted swap value
			if($posted_swap_list_value === 'undefined' || $posted_swap_list_value === 'null' || $posted_swap_list_value === false)
			{
				$posted_swap_list_value = '';
			}
			
			$submitted_value = !empty($posted_swap_list_value) ? $posted_swap_list_value : '';
			
			//Load main field
			$main_field_data = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [$main_field_id]);
			
			if(!empty($main_field_data))
			{
				$main_fields_lists = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$main_field_data['id'], 1]);
				
				foreach((array)$main_fields_lists as $main_fields_list)
				{
					$swap_field_meta = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [$main_fields_list['swap_form_field_to']]);
					
					$frontend_label = $swap_field_meta['frontend_name'] ?? '';
					
					$swap_fields_lists = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$main_fields_list['swap_form_field_to'], 1]);
					
					if(!empty($swap_fields_lists))
					{
						$options = '';
						foreach($swap_fields_lists as $swap_fields_list)
						{
							$sel = ($submitted_value === $swap_fields_list['value']) ? ' selected' : '';
							$options .= '<option value="'.$swap_fields_list['value'].'"'.$sel.'>'.$swap_fields_list['label'].'</option>';
						}
						
						$all[$main_fields_list['value']] = ['type'  => 'select', 'html'  => $options, 'label' => $frontend_label];
					}
					else
					{
						$all[$main_fields_list['value']] = ['type'  => 'text', 'label' => $frontend_label];
					}
				}
			}
			
			if(empty($all))
			{
				return ['swap_jquery' => ''];
			}
			
			$fieldClass = 'field-' . $swap_admin_field_css_id;
			
			$swap_jquery .= '
			<script nonce="'.NONCE.'">
			$(document).ready(function()
			{
				var submittedValue = "'.addslashes($submitted_value).'";
				var lastMode = "";
				var lastMainValue = "";
				
				function getSwapValue(mode)
				{
					var field = $("#'.$swap_admin_field_css_id.'");
					if(!field.length) return "";
					
					var v = field.val();
					if(mode === "select")
					{
						return (v !== undefined && v !== null) ? v : "";
					}
					return (v !== undefined && v !== null && v !== "") ? v : submittedValue;
				}
				
				function updateLabel(text)
				{
					var labelNode = $(".' . $fieldClass . ' .text");
					if(!labelNode.length) return;
					
					if(labelNode.find(".dynamic_form_validation").length)
					{
						labelNode.find(".dynamic_form_validation").text(text);
					}
					else
					{
						labelNode.text(text);
					}
				}
				
				function replaceField(markup, value, label, mode, mainVal)
				{
					var container = $(".' . $fieldClass . ' label");
					updateLabel(label);
					
					container.find("#'.$swap_admin_field_css_id.'").remove();
					container.append(markup);
					
					if(mode === "text" && value !== "")
					{
						$("#'.$swap_admin_field_css_id.'").val(value);
					}
					
					lastMode = mode;
					lastMainValue = mainVal;
				}
				
				function render()
				{
					var mainVal = $("#'.$main_admin_field_css_id.'").val();
					var currentTag = $("#'.$swap_admin_field_css_id.'").prop("tagName");
					currentTag = currentTag ? currentTag.toLowerCase() : "";
			';
			
			foreach($all as $key => $value)
			{
				$label = addslashes($value['label']);
				
				if($value['type'] === 'text')
				{
					$swap_jquery .= '
					if(mainVal == "'.$key.'")
					{
						var v = getSwapValue("text");
						if(currentTag === "input" && lastMainValue === mainVal)
						{
							updateLabel("'.$label.'");
							return;
						}
						replaceField(\'<input type="text" name="'.$swap_admin_field_css_id.'" id="'.$swap_admin_field_css_id.'" value="" />\', v, "'.$label.'", "text", mainVal);
						return;
					}';
				}
				else
				{
					$swap_jquery .= '
					if(mainVal == "'.$key.'")
					{
						var v = getSwapValue("select");
						if(currentTag === "select" && lastMainValue === mainVal)
						{
							updateLabel("'.$label.'");
							return;
						}
						replaceField(\'<select name="'.$swap_admin_field_css_id.'" id="'.$swap_admin_field_css_id.'">'.$value['html'].'</select>\', "", "'.$label.'", "select", mainVal);
						return;
					}';
				}
			}
			
			$swap_jquery .= '
				}
				
				render();
				$("#'.$main_admin_field_css_id.'").change(render);
			});
			</script>';
			
			return ['swap_jquery' => $swap_jquery];
		}
	}
	
	//This is used on place like cart and checkout templates to swap states for selected country.
	if(!function_exists('formFieldsSwap'))
	{
		function formFieldsSwap($main_field_id, $form_main_field_name, $posted_main_list_value, $form_swap_field_name, $posted_swap_list_value, $form_swap_text_anchor_class, $form_swap_attributes, $checkout_mode = '')
		{
			$all = array();
			$main_field = '';
			$swap_field = '';
			$swap_jquery = '';
			
			//Only get countries and states for allowed checkout.
			$checkout = '';
			if($checkout_mode == 'checkout_billing_countries' && isset($_SESSION['eligible_countries']) && count($_SESSION['eligible_countries']) > 1)
			{
				$checkout = 'checkout_countries';
			}
			
			if($checkout_mode == 'checkout_ship_to_countries' && isset($_SESSION['shipping_carriers_countries']) && count($_SESSION['shipping_carriers_countries']) > 1)
			{
				$checkout = 'checkout_ship_to_countries';
			}
			
			if($checkout_mode == 'checkout_credit_card_types' && isset($_SESSION['allowed_card_types']) && count($_SESSION['allowed_card_types']) > 1)
			{
				$checkout = 'checkout_credit_card_types';
			}
			
			//normalize submitted swap value
			if($posted_swap_list_value === 'undefined' || $posted_swap_list_value === 'null' || $posted_swap_list_value === false)
			{
				$posted_swap_list_value = '';
			}
			
			$submitted_value = !empty($posted_swap_list_value) ? $posted_swap_list_value : '';
			
			//Load main field & swap map
			$main_field_data = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [$main_field_id]);
			
			if(!empty($main_field_data))
			{
				$main_fields_lists = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$main_field_data['id'], 1]);
		
				if(!empty($main_fields_lists))
				{
					foreach($main_fields_lists as $main_fields_list)
					{
						if($checkout == 'checkout_countries' && !in_array($main_fields_list['value'], $_SESSION['eligible_countries'], true))
						{
							continue;
						}
						
						if($checkout == 'checkout_ship_to_countries' && !in_array($main_fields_list['value'], $_SESSION['shipping_carriers_countries'], true))
						{
							continue;
						}
						
						if($checkout == 'checkout_credit_card_types' && !in_array($main_fields_list['value'], $_SESSION['allowed_card_types'], true))
						{
							continue;
						}
						
						//Lookup swap field meta to fetch frontend_name
						$swap_field_meta = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [$main_fields_list['swap_form_field_to']]);
						
						$frontend_label = '';
						
						if(!empty($swap_field_meta['frontend_name']))
						{
							$frontend_label = $swap_field_meta['frontend_name'];
						}
						
						//Lookup swap values
						$swap_fields_lists = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$main_fields_list['swap_form_field_to'], 1]);
						
						//Build main select options
						$selected = (!empty($posted_main_list_value) && $posted_main_list_value == $main_fields_list['value']) ? ' selected' : '';
						
						$main_field .= '<option value="'.$main_fields_list['value'].'"'.$selected.'>'.$main_fields_list['label'].'</option>';
						
						//Build swap field map
						$swap_field_records = '';
						
						if(!empty($swap_fields_lists))
						{
							foreach($swap_fields_lists as $swap_fields_list)
							{
								$swap_sel = (!empty($posted_swap_list_value) && $posted_swap_list_value == $swap_fields_list['value']) ? ' selected' : '';
		
								$swap_field_records .= '<option value="'.$swap_fields_list['value'].'"'.$swap_sel.'>'.$swap_fields_list['label'].'</option>';
							}
							
							$all[$main_fields_list['value']] = array('type' => 'select','html' => $swap_field_records,'label' => $frontend_label);
						}
						else
						{
							$all[$main_fields_list['value']] = array('type' => 'text','html' => '','label' => $frontend_label);
						}
					}
				}
			}
			
			//Initial render selection behavior
			if(!empty($posted_main_list_value) && isset($all[$posted_main_list_value]))
			{
				if($all[$posted_main_list_value]['type'] === 'select')
				{
					$swap_field = '<select name="'.$form_swap_field_name.'" id="'.$form_swap_field_name.'" '.$form_swap_attributes.'>'.$all[$posted_main_list_value]['html'].'</select>';
				}
				else
				{
					$swap_field = '<input type="text" name="'.$form_swap_field_name.'" id="'.$form_swap_field_name.'" value="'.$submitted_value.'" '.$form_swap_attributes.' />';
				}
			}
			else
			{
				$first = reset($all);
				
				if(!empty($first))
				{
					if($first['type'] === 'select')
					{
						$swap_field = '<select name="'.$form_swap_field_name.'" id="'.$form_swap_field_name.'" '.$form_swap_attributes.'>'.$first['html'].'</select>';
					}
					else
					{
						$swap_field = '<input type="text" name="'.$form_swap_field_name.'" id="'.$form_swap_field_name.'" value="'.$submitted_value.'" '.$form_swap_attributes.' />';
					}
				}
			}
			
			//Runtime JS (front-end swapper)
			if(!empty($all))
			{
				$swap_jquery .= '
				$(document).ready(function()
				{
					var submittedValue = "'.addslashes($submitted_value).'";
					var lastMode = "";
					var lastMainValue = "";
					
					function getSwapValue(mode)
					{
						var container = $(".'.$form_swap_field_name.'");
						if(!container.length) return "";
						var field = container.find("input, select, textarea").first();
						if(!field.length) return "";
						var v = field.val();
						if(mode === "select"){return (v !== undefined && v !== null) ? v : "";}
						return (v !== undefined && v !== null && v !== "") ? v : submittedValue;
					}
					
					function updateFrontendLabel(textValue)
					{
						var labelNode = $(".'.$form_swap_field_name.' .'.$form_swap_text_anchor_class.'");
						if(!labelNode.length) return;
						var target = labelNode;
						labelNode.find("*").each(function()
						{
							var hasText = $(this).contents().filter(function(){return this.nodeType === 3 && $.trim(this.nodeValue).length > 0;}).length;
							if(hasText){target=$(this);}
						});
						var replaced = false;
						target.contents().filter(function(){return this.nodeType === 3;}).each(function(){this.nodeValue=textValue;replaced=true;});
						if(!replaced){target.append(document.createTextNode(textValue));}
					}
					
					function replaceSwapField(newMarkup, valueToSet, newLabel, mode, mainValue)
					{
						var container = $(".'.$form_swap_field_name.'");
						updateFrontendLabel(newLabel);
						container.find("select#'.$form_swap_field_name.', input#'.$form_swap_field_name.', textarea#'.$form_swap_field_name.'").remove();
						var label = container.find("label").first();
						if(label.length){label.append(newMarkup);} 
						else{container.append(newMarkup);}
						if(mode === "text" && valueToSet !== ""){container.find("#'.$form_swap_field_name.'").val(valueToSet);}
						lastMode = mode;
						lastMainValue = mainValue;
					}
					
					function renderSwapField()
					{
						var mainVal = $("#'.$form_main_field_name.'").val();
						//if(mainVal === ""){return;}
			
						var currentTag = $("#'.$form_swap_field_name.'").prop("tagName");
						currentTag = currentTag ? currentTag.toLowerCase() : "";
				';
			
				foreach($all as $key => $value)
				{
					$label = addslashes($value['label']);
					
					if($value['type'] === 'text')
					{
						$swap_jquery .= '
						if(mainVal == "'.$key.'")
						{
							var value = getSwapValue("text");
							if(currentTag === "input" && lastMainValue === mainVal){updateFrontendLabel("'.$label.'");return;}
							replaceSwapField(\'<input type="text" name="'.$form_swap_field_name.'" id="'.$form_swap_field_name.'" value="" '.$form_swap_attributes.' />\', value, "'.$label.'", "text", mainVal);
							return;
						}';
					}
					else
					{
						$swap_jquery .= '
						if(mainVal == "'.$key.'")
						{
							var value = getSwapValue("select");
							if(currentTag === "select" && lastMainValue === mainVal){updateFrontendLabel("'.$label.'");return;}
							replaceSwapField(\'<select name="'.$form_swap_field_name.'" id="'.$form_swap_field_name.'" '.$form_swap_attributes.'>'.$value['html'].'</select>\', "", "'.$label.'", "select", mainVal);
							return;
						}';
					}
				}
				
				$swap_jquery .= '
						return;
					}
					
					renderSwapField();
					
					$("#'.$form_main_field_name.'").change(function()
					{
						renderSwapField();
					});
				});';
			}
			
			return array("swap_jquery" => $swap_jquery, "main_field" => $main_field, "swap_field" => $swap_field);
		}
	}
	
	//Returns a single form field's option list for rendering in a <select>. Use this when you only need the main field values (e.g., country list) without any dependent swap fields (like states).
	if(!function_exists('formSingleField'))
	{
		function formSingleField($main_field_id, $posted_main_list_value, $checkout_mode = '')
		{
			$main_field = '';
			
			//Only get countries and states for allowed checkout.
			$checkout = '';
			if($checkout_mode == 'checkout_billing_countries' && isset($_SESSION['eligible_countries']) && count($_SESSION['eligible_countries']) > 1)
			{
				$checkout = 'checkout_countries';
			}
			
			if($checkout_mode == 'checkout_ship_to_countries' && isset($_SESSION['shipping_carriers_countries']) && count($_SESSION['shipping_carriers_countries']) > 1)
			{
				$checkout = 'checkout_ship_to_countries';
			}
			
			if($checkout_mode == 'checkout_credit_card_types' && isset($_SESSION['allowed_card_types']) && count($_SESSION['allowed_card_types']) > 1)
			{
				$checkout = 'checkout_credit_card_types';
			}
			
			//Load main field & values
			$main_field_data = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'form_fields', 'WHERE `id` = ?', [$main_field_id]);
			
			if(!empty($main_field_data))
			{
				$main_fields_lists = $_SESSION['results']->getSelectMultipleRecords(__LINE__, __FILE__, '*', 'form_values', 'WHERE `form_fields_id` = ? AND `status` = ? ORDER BY `sort` ASC', [$main_field_data['id'], 1]);
				
				if(!empty($main_fields_lists))
				{
					foreach($main_fields_lists as $main_fields_list)
					{
						if($checkout == 'checkout_countries' && !in_array($main_fields_list['value'], $_SESSION['eligible_countries'], true))
						{
							continue;
						}
						
						if($checkout == 'checkout_ship_to_countries' && !in_array($main_fields_list['value'], $_SESSION['shipping_carriers_countries'], true))
						{
							continue;
						}
						
						if($checkout == 'checkout_credit_card_types' && !in_array($main_fields_list['value'], $_SESSION['allowed_card_types'], true))
						{
							continue;
						}
						
						$selected = '';
						if(!empty($posted_main_list_value) && $posted_main_list_value == $main_fields_list['value'])
						{
							$selected = ' selected';
						}
						
						$main_field .= '<option value="'.$main_fields_list['value'].'"'.$selected.'>'.$main_fields_list['label'].'</option>';
					}
				}
			}
			
			return $main_field;
		}
	}
}