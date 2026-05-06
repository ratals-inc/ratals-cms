<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/priceAsText.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/priceAsText.php');
}
else
{
	if(!class_exists('priceAsTextAeaf'))
	{
		class priceAsTextAeaf
		{
			public function priceAsTextAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values)
			{
				$currency_zeros_after_separator = $_SESSION['currency_zeros_after_separator'];
				if($admin_field["financial_field"] == 'Yes')
				{
					$currency_zeros_after_separator = 6;
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field text">
				'.number_format((float)htmlspecialchars($field_value ?? ''), $currency_zeros_after_separator, $_SESSION['currency_fractional_separator'], '').'
				<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.number_format((float)htmlspecialchars($field_value ?? ''), $currency_zeros_after_separator, $_SESSION['currency_fractional_separator'], '').'">
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
				echo '</div>';
			}
		}
		
		$class_priceAsTextAeaf = new priceAsTextAeaf();
	}
	
	$class_priceAsTextAeaf->priceAsTextAeaf($table_name, $admin_field, $field_value, $errors, $post_values);
}