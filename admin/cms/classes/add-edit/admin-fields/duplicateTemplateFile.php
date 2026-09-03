<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/duplicateTemplateFile.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/duplicateTemplateFile.php');
}
else
{
	if(!class_exists('duplicateTemplateFileAeaf'))
	{
		class duplicateTemplateFileAeaf
		{
			public function duplicateTemplateFileAeaf($table_name, $admin_field, $field_value)
			{
				if($_SESSION['admin_type'] == 'edit')
				{
					$field_required = '';
					if($admin_field["required"] == 'Yes')
					{
						$field_required = ' <span class="required-asterisk">*</span>';
					}
					
					echo '<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').$field_required.'</div>
					<div class="edit-field text">';
					echo '<button type="button" class="duplicate-template-file-button" data-click="'.htmlspecialchars($_GET["rid"] ?? '').'">Copy</button>';
					echo '<div class="small-text">'.$admin_field["notes"].'</div>';
					echo '</div>
					</div>';
				}
			}
		}
		
		$class_duplicateTemplateFileAeaf = new duplicateTemplateFileAeaf();
	}
	
	$class_duplicateTemplateFileAeaf->duplicateTemplateFileAeaf($table_name, $admin_field, $field_value);
}