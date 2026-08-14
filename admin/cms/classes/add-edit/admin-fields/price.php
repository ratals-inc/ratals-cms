<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/price.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/price.php');
}
else
{
	if(!class_exists('priceAeaf'))
	{
		class priceAeaf
		{
			public function priceAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$currency_zeros_after_separator = $_SESSION['currency_zeros_after_separator'];
				if($admin_field["financial_field"] == 'Yes')
				{
					$currency_zeros_after_separator = 6;
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">
				<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.number_format((float)htmlspecialchars($field_value ?? ''), $currency_zeros_after_separator, $_SESSION['currency_fractional_separator'], '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>'; 
			}
		}
		
		$class_priceAeaf = new priceAeaf();
	}
	
	$class_priceAeaf->priceAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}