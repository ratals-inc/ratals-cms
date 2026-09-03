<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/customFieldsOptionPrice.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/customFieldsOptionPrice.php');
}
else
{
	if(!class_exists('customFieldsOptionPriceAeaf'))
	{
		class customFieldsOptionPriceAeaf
		{
			public function customFieldsOptionPriceAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				if(!empty(trim($_GET['sub-page-rid'] ?? '')))
				{
					$custom_field_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ?', [trim($_GET['sub-page-rid'] ?? '')]);
					$custom_field_type['search_as'] = $custom_field_type['cf_search_as'];
					$custom_field_type['display_as'] = $custom_field_type['cf_display_as'];
				}
				elseif(!empty(trim($_GET["rid"] ?? '')))
				{
					$custom_field_type = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'custom_fields', 'WHERE `id` = ?', [trim($_GET["rid"] ?? '')]);
					$custom_field_type['search_as'] = $custom_field_type['cf_search_as'];
					$custom_field_type['display_as'] = $custom_field_type['cf_display_as'];
				}
				
				if(isset($custom_field_type) && $custom_field_type['field_type'] == 'Product Option' && ($custom_field_type['display_as'] == 'boxes' || $custom_field_type['display_as'] == 'dropdownId' || $custom_field_type['display_as'] == 'swatch'))
				{
					$currency_zeros_after_separator = $_SESSION['currency_zeros_after_separator'];
					if($admin_field["financial_field"] == 'Yes')
					{
						$currency_zeros_after_separator = 6;
					}
					
					$field_required = '';
					if($admin_field["required"] == 'Yes')
					{
						$field_required = ' <span class="required-asterisk">*</span>';
					}
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="edit-field">
					<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.number_format((float)htmlspecialchars($field_value ?? ''), $currency_zeros_after_separator, $_SESSION['currency_fractional_separator'], '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>'; 
				}
				else
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="0.00">';
				}
			}
		}
		
		$class_customFieldsOptionPriceAeaf = new customFieldsOptionPriceAeaf();
	}
	
	$class_customFieldsOptionPriceAeaf->customFieldsOptionPriceAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}