<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/phpArrayForTemplate.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/phpArrayForTemplate.php');
}
else
{
	if(!class_exists('phpArrayForTemplateAeaf'))
	{
		class phpArrayForTemplateAeaf
		{
			public function phpArrayForTemplateAeaf($table_name, $admin_field, $field_value)
			{
				$field_required = '';
				if($admin_field["required"] == 'Yes')
				{
					$field_required = ' <span class="required-asterisk">*</span>';
				}
				
				echo '
				<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
				<div class="edit-field text embed">';
				?>
					&lt;?php echo '&lt;pre&gt;'; print_r($data_array); echo '&lt;/pre&gt;'; ?&gt;
				<?php 
				echo '</div>
				<div class="small-text">'.$admin_field["notes"].'</div>
				</div>';
				
				echo '
				<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
			}
		}
		
		$class_phpArrayForTemplateAeaf = new phpArrayForTemplateAeaf();
	}
	
	$class_phpArrayForTemplateAeaf->phpArrayForTemplateAeaf($table_name, $admin_field, $field_value);
}