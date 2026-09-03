<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/embedMedia.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/embedMedia.php');
}
else
{
	if(!class_exists('embedMediaAeaf'))
	{
		class embedMediaAeaf
		{
			public function embedMediaAeaf($table_name, $admin_field, $field_value)
			{
				if($_SESSION['admin_type'] == 'edit')
				{
					$field_required = '';
					if($admin_field["required"] == 'Yes')
					{
						$field_required = ' <span class="required-asterisk">*</span>';
					}
					
					echo '<div class="margin-bottom-13"><span class="color-f00"><strong>Important:</strong></span> When using the embed media tags below, always use the media ID of the "Original Media" file. This ensures all variant images are available. If you use a variant image ID, the image will not load. To confirm you\'re using the correct file, check the "Original Media" field above - it should be set to "Yes".</div>
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').' in Main Content, Bottom Content, or Table of Contents'.$field_required.'</div>
					<div class="edit-field text embed">';
					?>
                        If this embed media is a large image and will load high up in the page, you should use this with High Fetch Priority:<br>
						<strong>Embed Code:</strong> mediaId(<?php echo trim($_GET["rid"] ?? ''); ?>, lazyLoadNo, fetchPriorityHigh, maxDisplayPixelWidth(""), altTitleTag(""));
                        <br><br>
						If this embed media will load high up in the page, you should use this with No Lazy Loading:<br>
                        <strong>Embed Code:</strong> mediaId(<?php echo trim($_GET["rid"] ?? ''); ?>, lazyLoadNo, fetchPriorityAuto, maxDisplayPixelWidth(""), altTitleTag(""));
						<br><br>
                        If this embed media will load after you scroll some, you should use this with Lazy Loading:<br>
						<strong>Embed Code:</strong> mediaId(<?php echo trim($_GET["rid"] ?? ''); ?>, lazyLoadYes, fetchPriorityAuto, maxDisplayPixelWidth(""), altTitleTag(""));
					<?php 
					echo '</div>
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').' in Code'.$field_required.'</div>
					<div class="edit-field text embed">';
					?>
                        If this embed media is a large image and will load high up in the page, you should use this with High Fetch Priority:<br>
						<strong>Embed Code:</strong> &lt;?php echo mediaId(<?php echo trim($_GET["rid"] ?? ''); ?>, 'lazyLoadNo', 'fetchPriorityHigh', '', ''); ?&gt;
                        <br><br>
                        If this embed media will load high up in the page, you should use this with No Lazy Loading:<br>
						<strong>Embed Code:</strong> &lt;?php echo mediaId(<?php echo trim($_GET["rid"] ?? ''); ?>, 'lazyLoadNo', 'fetchPriorityAuto', '', ''); ?&gt;
						<br><br>
						If this embed media will load after you scroll some, you should use this with Lazy Loading:<br>
                        <strong>Embed Code:</strong> &lt;?php echo mediaId(<?php echo trim($_GET["rid"] ?? ''); ?>, 'lazyLoadYes', 'fetchPriorityAuto', '', ''); ?&gt;
					<?php 
					echo '</div>
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').' in Code using an Array</div>
					<div class="edit-field text embed">';
					?>
						&lt;?php $media_id_<?php echo trim($_GET["rid"] ?? ''); ?>_array = mediaIdArray(<?php echo trim($_GET["rid"] ?? ''); ?>); ?&gt; &lt;?php echo '&lt;pre&gt;'; print_r($media_id_<?php echo trim($_GET["rid"] ?? ''); ?>_array); echo '&lt;/pre&gt;'; ?&gt;
					<?php 
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
		
		$class_embedMediaAeaf = new embedMediaAeaf();
	}
	
	$class_embedMediaAeaf->embedMediaAeaf($table_name, $admin_field, $field_value);
}