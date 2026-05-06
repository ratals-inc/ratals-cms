<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/mediaUrl.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/admin-fields/mediaUrl.php');
}
else
{
	if(!class_exists('mediaUrlAeaf'))
	{
		class mediaUrlAeaf
		{
			public function mediaUrlAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $current_values, $domain)
			{
				if($_SESSION['admin_type'] == 'add' && $_SESSION['admin_class'] == 'add-video-embed')
				{
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">Video Embed URL</div>
					<div class="edit-field">
					<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>';
				}
				elseif($_SESSION['admin_type'] == 'add')
				{
					echo '<div id="status"></div>
					<div class="edit-wrapper media-upload">
						<label for="files">
							<div class="text">
								<p>Select up to <strong>'.ini_get('max_file_uploads').'</strong> media files, or simply drag and drop them onto the \'Choose Files\' button.</p>
							</div>
							<input type="file" id="files" name="files[]" multiple>
							<div class="text">
								<p class="server-max"><strong>Your current server limits:</strong> Upload Max Filesize: <strong>'.ini_get('upload_max_filesize').'B</strong> | Post Max Size: <strong>'.ini_get('post_max_size').'B</strong> | Max File Uploads: <strong>'.ini_get('max_file_uploads').'</strong>.<br>If you need to upload larger files or more files, increase these limits in your .htaccess file / PHP configuration.</p>
							</div>
						</label>
						<progress class="progressbar" id="progressBar" value="0" max="100"></progress>
						<div class="statuss" id="statuss"></div>
					</div>
					<div class="idea-media-sizes">
					<div class="important-note">
						<strong>Important Note:</strong> The media images you upload should always be 2 times larger than the display area\'s size. This ensures optimal quality on devices with a "Device Pixel Ratio" of 2X, which require higher-resolution images. Additionally, please ensure the "Create smaller images" checkbox below is checked, so we can generate the appropriate image sizes for all devices and browsers based on the very large original image.
					</div>
						<strong>Enhance Site Performance</strong><br><br>
						<div class="media-upload-checkbox"><label><input name="create_smaller_images" id="create_smaller_images" type="checkbox" value="" checked="checked" /> <strong>Create smaller images</strong> at 2560px, 1920px, 1400px, 1024px, 744px, 640px, 372px, 320px, and 240px when uploading .gif, .jpg, .jpeg, or .png files. Smaller images are only created if the original image is larger than the variant width.</label></div>
						<div class="media-upload-checkbox"><label><input name="create_avif" id="create_avif" type="checkbox" value="" checked="checked" /> Create an <strong>.avif</strong> version of the media file when uploading .gif, .jpg, .jpeg, or .png files.</label></div>
						<div class="media-upload-checkbox"><label><input name="create_webp" id="create_webp" type="checkbox" value="" checked="checked" /> Create a <strong>.webp</strong> version of the media file when uploading .gif, .jpg, .jpeg, or .png files.</label></div>
						<div class="media-sizes-text">
							<strong>Ideal Media Sizes</strong>
							<ul>
								<li>Product image: 1500x1500 px</li>
								<li>Blog post image: 1500x1500 px</li>
								<li>Blog post sub-item image: 1500x1500 px</li>
								<li>Desktop slider image: 1500x300 px</li>
								<li>Tablet slider image: 1025x300 px</li>
								<li>Mobile slider image: 600x300 px</li>
								<li>Profile photo: 500x500 px</li>
							</ul>
							<strong>Favicon</strong>
							<p>Only upload the exact sizes listed below. Ensure that checkboxes for smaller images, .avif images, or .webp images are unchecked. Favicons should ideally be in .png format at the specified sizes:</p>
							<ul>
								<li>Favicon icon 1: 16x16 px - Standard monitors</li>
								<li>Favicon icon 2: 32x32 px - High-resolution monitors</li>
								<li>Favicon icon 3: 180x180 px - Apple Touch</li>
							</ul>
						</div>
					</div>
					';
					
					echo '
					<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">';
				}
				else
				{
					$media_data_type = explode('.', $current_values[$table_name][$admin_field["column_name"]]);
					
					if($current_values['media']['media_type'] == 'Image')
					{
						
						$edit_media_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `media_url` = ? LIMIT 1', [$current_values[$table_name][$admin_field["column_name"]]]);
						$original_media_id = $edit_media_row['original_media_id'];
						
						$media_directory_name = $_SESSION['view_frontend_of_site'].'/sites/media/images/'.$original_media_id.'/'.htmlspecialchars($current_values[$table_name][$admin_field["column_name"]] ?? '');
						$display_media = '<img src="/sites/media/images/'.$original_media_id.'/'.htmlspecialchars($current_values[$table_name][$admin_field["column_name"]] ?? '').'">';
					}
					elseif($current_values['media']['media_type'] == 'File')
					{
						$media_directory_name = $_SESSION['view_frontend_of_site'].'/sites/media/files/'.htmlspecialchars($current_values[$table_name][$admin_field["column_name"]] ?? '');
						$display_media = '<object class="display-width-max-height-aspect" data="/sites/media/files/'.htmlspecialchars($current_values[$table_name][$admin_field["column_name"]] ?? '').'" type="application/'.$media_data_type[1].'" width="100%" height="100%"></object>';
					}
					elseif($current_values['media']['media_type'] == 'Video')
					{
						$media_directory_name = $_SESSION['view_frontend_of_site'].'/sites/media/videos/'.htmlspecialchars($current_values[$table_name][$admin_field["column_name"]] ?? '');
						$display_media = '<video controls="" class="display-width-max-height-aspect"><source src="/sites/media/videos/'.htmlspecialchars($current_values[$table_name][$admin_field["column_name"]] ?? '').'" type="video/'.$media_data_type[1].'"></video>';
					}
					elseif($current_values['media']['media_type'] == 'Video Embed')
					{
						$media_directory_name = htmlspecialchars($current_values[$table_name][$admin_field["column_name"]] ?? '');
						$display_media = '<iframe class="display-width-max-height-aspect" src="'.htmlspecialchars($current_values[$table_name][$admin_field["column_name"]] ?? '').'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>';
					}
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">Media</div>
					<div class="edit-field display-media text">
					'.$display_media.'
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					echo '</div>'; 
					
					echo '
					<div class="edit '.htmlspecialchars($admin_field["url_name"] ?? '').'">
					<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
					<div class="edit-field text">
					<input type="text" name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" value="'.htmlspecialchars($field_value ?? '').'" id="'.htmlspecialchars($table_name.'_'.$admin_field["column_name"] ?? '').'">
					<div class="small-text"><a href="'.$media_directory_name.'" target="_blank">'.$media_directory_name.'</a></div>
					<div class="small-text">'.$admin_field["notes"].'</div>
					</div>';
					if(isset($errors[$table_name][$admin_field["column_name"]])) { echo '<div class="validation">'.htmlspecialchars($errors[$table_name][$admin_field["column_name"]] ?? '').'</div>'; }
					echo '</div>'; 
				}
			}
		}
		
		$class_mediaUrlAeaf = new mediaUrlAeaf();
	}
	
	$class_mediaUrlAeaf->mediaUrlAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $current_values, $domain);
}