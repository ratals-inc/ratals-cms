<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/embedCustomField.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/embedCustomField.php');
}
else
{
	if(!class_exists('embedCustomFieldAeaf'))
	{
		class embedCustomFieldAeaf
		{
			public function embedCustomFieldAeaf($table_name, $admin_field, $field_value, $current_values)
			{
				if($_SESSION['admin_type'] == 'edit' && $current_values['custom_fields']['cf_display_as'] != 'swatch' && $current_values['custom_fields']['field_type'] == 'Content Field')
				{
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
						<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
						<div class="edit-field text embed">';
						
							if($current_values['custom_fields']['cf_display_as'] == "singleMedia")
							{ 
							?>
								If this embed media will load high up in the page, you should use this with No Lazy Loading:<br>
                                <strong>Embed Code:</strong> &lt;?php $image_data = customField('<?php echo trim($_GET["rid"] ?? ''); ?>', $rid); if(!empty($image_data)) { $media_output = mediaId($image_data[0], 'lazyLoadNo', 'fetchPriorityAuto', $image_data[1]); echo $media_output; } ?&gt;
                                <br><br>
                                If this embed media will load after you scroll some, you should use this with Lazy Loading:<br>
                                <strong>Embed Code:</strong> &lt;?php $image_data = customField('<?php echo trim($_GET["rid"] ?? ''); ?>', $rid); if(!empty($image_data)) { $media_output = mediaId($image_data[0], 'lazyLoadYes', 'fetchPriorityAuto', $image_data[1]); echo $media_output; } ?&gt;
                                
                                If this embed media is a large image and will load high up in the page, you should use this with High Fetch Priority:<br>
                                <strong>Embed Code:</strong> &lt;?php $image_data = customField('<?php echo trim($_GET["rid"] ?? ''); ?>', $rid); if(!empty($image_data)) { $media_output = mediaId($image_data[0], 'lazyLoadNo', 'fetchPriorityHigh', $image_data[1]); echo $media_output; } ?&gt;
							<?php 
							}
							else
							{ ?>
								&lt;?php echo customField('<?php echo trim($_GET["rid"] ?? ''); ?>', $rid); ?&gt;
							<?php 
							}
						echo '</div>
						<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
				else
				{
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
			}
		}
		
		$class_embedCustomFieldAeaf = new embedCustomFieldAeaf();
	}
	
	$class_embedCustomFieldAeaf->embedCustomFieldAeaf($table_name, $admin_field, $field_value, $current_values);
}