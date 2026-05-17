<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

header('Content-Type: application/json; charset=utf-8');

//This file is accessed directly via HTTP (AJAX/cURL) and does not inherit session or authentication context.
//We must explicitly include the admin session check to initialize the session, load config, and enforce that the user is authenticated.
require_once($_SERVER['DOCUMENT_ROOT'].'/core/session-check-admin.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/ajax/add-media.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/ajax/add-media.php');
}
else
{
	if(isset($_POST['admin_table_name']) && $_POST['admin_table_name'] == 'media' && isset($_POST['admin_type']) && $_POST['admin_type'] == 'add' && isset($_POST['admin_class']) && $_POST['admin_class'] != 'add-video-embed')
	{
		if(isset($_FILES['files']['name']))
		{
			function convertBytesToSize($byte_size)
			{
				if($byte_size >= 1073741824)
				{
					$byte_size= number_format(($byte_size / 1073741824), 2).' GB';
				}
				elseif($byte_size >= 1048576)
				{
					$byte_size = number_format(($byte_size / 1048576), 2).' MB';
				}
				elseif($byte_size >= 1024)
				{
					$byte_size = number_format(($byte_size / 1024), 2).' KB';
				}
				elseif($byte_size > 1)
				{
					$byte_size = $byte_size.' Bytes';
				}
				elseif($byte_size == 1)
				{
					$byte_size = $byte_size.' Byte';
				}
				else
				{
					$byte_size = '0 Bytes';
				}
				
				return $byte_size;
			}
			
			//Makes sure avif is fully enabled.
			$avif_enabled = false;
			function avif_enabled($path)
			{
				$avif_enabled = false;	
				if(function_exists('gd_info') && function_exists('imageavif'))
				{
					$gd_info = gd_info();
					if(!empty($gd_info['AVIF Support']) && file_exists($path))
					{
						//Detect image type.
						$image_info = getimagesize($path);
						if($image_info === false)
						{
							return false;
						}
						
						//Load correct image type.
						if($image_info[2] === IMAGETYPE_GIF)
						{
							$image = imagecreatefromgif($path);
						}
						elseif($image_info[2] === IMAGETYPE_JPEG)
						{
							$image = imagecreatefromjpeg($path);
						}
						elseif($image_info[2] === IMAGETYPE_PNG)
						{
							$image = imagecreatefrompng($path);
						}
						else
						{
							return false;
						}
						
						if($image !== false)
						{
							imagepalettetotruecolor($image);
							$avif_path = $_SERVER['DOCUMENT_ROOT'].'/test-avif-image-creation.avif';
							$avif_result = @imageavif($image, $avif_path, 75);
							if($avif_result && is_file($avif_path) && filesize($avif_path) > 0)
							{
								$avif_enabled = true;
							}
							if(is_file($avif_path))
							{
								unlink($avif_path);
							}
							imagedestroy($image);
						}
					}
				}
				
				return $avif_enabled;
			}
			
			$countfiles = count($_FILES['files']['name']);
			$path = '';
			$media_type = array();
			$upload_success = array();
			$upload_exists = array();
			$extension_not_valid = array();
			//The smallest allowed variant size for creation is in pixels. By default, images smaller than 50 pixels will not be created.
			$smallest_variant_size = 50;
			//Important: Using values higher than the defaults will increase image quality, but also file size. Speed testing tools (like Lighthouse) may then suggest lowering quality to improve load times.
			$jpg_quality = 75; //default is 75
			$webp_quality = 80; //default is 80
			$avif_quality = 52; //default is 52
			
			//Accepted Image Extension Types.
			$accepted_image_extension_types = $_SESSION['results']->getSelectMultipleRecordsKeyNameOneColumn(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', ['accepted_image_extension_types'], 'label', 'value');
			$accepted_image_extension_types = array_map('strtolower', $accepted_image_extension_types);
			
			//Accepted Video Extension Types.
			$accepted_video_extension_types = $_SESSION['results']->getSelectMultipleRecordsKeyNameOneColumn(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', ['accepted_video_extension_types'], 'label', 'value');
			$accepted_video_extension_types = array_map('strtolower', $accepted_video_extension_types);
			
			//Accepted File Extension Types.
			$accepted_file_extension_types = $_SESSION['results']->getSelectMultipleRecordsKeyNameOneColumn(__LINE__, __FILE__, '*', 'admin_fields_values', 'WHERE `admin_fields_lists_parent_code` = ? ORDER BY `sort` ASC', ['accepted_file_extension_types'], 'label', 'value');
			$accepted_file_extension_types = array_map('strtolower', $accepted_file_extension_types);
			
			for($index = 0;$index < $countfiles;$index++)
			{
				if(isset($_FILES['files']['name'][$index]) && !empty($_FILES['files']['name'][$index]))
				{
					$cleaned_media_url = strtolower(str_replace(array(' ', '_', '[', ']', '(', ')'), '-', $_FILES['files']['name'][$index]));
					$cleaned_media_url = preg_replace('/-+/', '-', $cleaned_media_url);
					$cleaned_media_url = str_replace('-.', '.', $cleaned_media_url);
					
					$media_type = explode('.', $cleaned_media_url);
					$media_extension = strtolower(end($media_type));
					$meta_tag = ucwords(str_replace('-', ' ',$media_type[0]));
					
					//Check if media file name exist in database.
					$media_exists_in_db = $_SESSION['results']->getSelectCountRecords(__LINE__, __FILE__, '*', 'media', 'WHERE `media_url` = ? LIMIT 1', [$cleaned_media_url]);
					
					$media_data = getimagesize($_FILES['files']['tmp_name'][$index]);
					
					//Get MIME type of uploaded file
					$finfo = finfo_open(FILEINFO_MIME_TYPE);
					$mime_file_type = finfo_file($finfo, $_FILES['files']['tmp_name'][$index]);
					finfo_close($finfo);
					
					$media_size_other = filesize($_FILES['files']['tmp_name'][$index]);
					if(isset($media_size_other)) { $size_other = convertBytesToSize($media_size_other); } else { $size_other = ''; }
					
					//If image uploaded.
					if($media_data !== false && !empty($media_extension) && !empty($mime_file_type) && array_key_exists($media_extension, $accepted_image_extension_types) && in_array($mime_file_type, $accepted_image_extension_types))
					{
						//Insert Image or PDF
						$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Image', 'Yes', NULL, $cleaned_media_url, $meta_tag, $size_other, $media_data[0], $media_data[1], '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
						//Get media id just created.
						$get_media_just_added = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `original_media` = ? AND `media_url` = ? ORDER BY `id` DESC LIMIT 1', ['Yes', $cleaned_media_url]);
						//Update media that was just created with its id in the column of original_media_id.					
						$original_media_id = $get_media_just_added['id'];
						$_SESSION['results']->getUpdateRecord(__LINE__, __FILE__, 'media', '`original_media_id` = ?', 'WHERE `id` = ?', [$original_media_id, $original_media_id]);
						
						$path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$cleaned_media_url;
						$display = '/sites/media/images/'.$original_media_id.'/'.$cleaned_media_url;
						
						//If the media file name exist, remove the image that was just added and continue the loop to the next media image to upload.
						if(file_exists($path))
						{
							$_SESSION['results']->getDeleteRecord(__LINE__, __FILE__, 'media', 'WHERE `id` = ?', [$original_media_id]);
							$upload_exists[] = array('image', $path);
							continue;
						}
						
						//If media directory name does NOT exist, create it to put the images in.
						if(!is_dir($_SERVER['DOCUMENT_ROOT']."/sites/media/images/".$original_media_id)) { mkdir($_SERVER['DOCUMENT_ROOT']."/sites/media/images/".$original_media_id, 0755, true); }
						
						move_uploaded_file($_FILES['files']['tmp_name'][$index], $path);
						$upload_success[] = array('image', $display);
						
						//Variables to create smaller image sizes.
						$all_image_resizes = array();
						if(isset($_POST['create_smaller_images']) && $_POST['create_smaller_images'] == 'Yes' && !empty($media_data[0]) && !empty($media_data[1]))
						{
							//Fixed breakpoints that lighthouse likes
							$fixed_widths = array(2560, 1920, 1400, 1024, 744, 640, 372, 320, 240);
							$all_image_resizes = array();
							
							foreach($fixed_widths as $target_width)
							{
								if($media_data[0] > $target_width)
								{
									$percentage = $target_width / $media_data[0];
									$all_image_resizes[] = array('width' => round($media_data[0] * $percentage), 'height' => round($media_data[1] * $percentage));
								}
							}
						}
						
						//Make sure avif is fully enabled if admin user asking to create them.
						if(!isset($flag_run_once) && isset($_POST['create_avif']) && $_POST['create_avif'] == 'Yes')
						{
							$flag_run_once = true;
							$avif_enabled = avif_enabled($path);
						}
						
						if($media_extension == 'gif')
						{
							//Loop through each resize for GIF images
							if(!empty($all_image_resizes))
							{
								foreach($all_image_resizes as $image_resize)
								{
									$media_width = $image_resize['width'];
									$media_height = $image_resize['height'];
									
									if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
									{
										$image = imagecreatefromgif($path);
										imagepalettetotruecolor($image);
										$resized_image = imagecreatetruecolor($media_width, $media_height);
										$transIndex = imagecolortransparent($image);
										if($transIndex >= 0) {
											$transColor = imagecolorsforindex($image, $transIndex);
											$transIndexNew = imagecolorallocate($resized_image, $transColor['red'], $transColor['green'], $transColor['blue']);
											imagefill($resized_image, 0, 0, $transIndexNew);
											imagecolortransparent($resized_image, $transIndexNew);
										}
										imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
										imagegif($resized_image, $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.gif');
										$media_size_gif = filesize($_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.gif');
										if($media_size_gif !== false) { $size_gif = convertBytesToSize($media_size_gif); } else { $size_gif = ''; }
										
										//Insert Image
										$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.gif', $meta_tag, $size_gif, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
										
										imagedestroy($image);
										imagedestroy($resized_image);
									}
								}
							}
							
							//Create WebP image.
							if(function_exists('imagewebp') && isset($_POST['create_webp']) && $_POST['create_webp'] == 'Yes')
							{
								$image = imagecreatefromgif($path);
								imagepalettetotruecolor($image);
							
								$webp_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'.webp';
								$webp_result = imagewebp($image, $webp_path, $webp_quality);
							
								if($webp_result == true && file_exists($webp_path) && filesize($webp_path) > 0)
								{
									//Insert Image webp
									$media_size_webp = filesize($webp_path);
									$size_webp = convertBytesToSize($media_size_webp);
							
									$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media',
									'`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`',
									'?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?',
									[$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'.webp', $meta_tag, $size_webp, $media_data[0], $media_data[1], '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
								}
								elseif(file_exists($webp_path))
								{
									unlink($webp_path);
								}
							
								imagedestroy($image);
							
								//Resizes
								if(!empty($all_image_resizes))
								{
									foreach($all_image_resizes as $image_resize)
									{
										$media_width = $image_resize['width'];
										$media_height = $image_resize['height'];
							
										if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
										{
											$image = imagecreatefromgif($path);
											$resized_image = imagecreatetruecolor($media_width, $media_height);
											imagealphablending($resized_image, false);
											imagesavealpha($resized_image, true);
											$transparent = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
											imagefill($resized_image, 0, 0, $transparent);
											imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
							
											$webp_resize_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.webp';
											$webp_result = imagewebp($resized_image, $webp_resize_path, $webp_quality);
							
											if($webp_result == true && file_exists($webp_resize_path) && filesize($webp_resize_path) > 0)
											{
												$media_size_webp = filesize($webp_resize_path);
												$size_webp = convertBytesToSize($media_size_webp);
							
												$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media',
												'`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`',
												'?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?',
												[$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.webp', $meta_tag, $size_webp, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
											}
											elseif(file_exists($webp_resize_path))
											{
												unlink($webp_resize_path);
											}
							
											imagedestroy($image);
											imagedestroy($resized_image);
										}
									}
								}
							}
							
							//Create AVIF image.
							if($avif_enabled && function_exists('imageavif') && isset($_POST['create_avif']) && $_POST['create_avif'] == 'Yes')
							{
								$image = imagecreatefromgif($path);
								imagepalettetotruecolor($image);
							
								$avif_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'.avif';
								$avif_result = imageavif($image, $avif_path, $avif_quality);
							
								if($avif_result == true && file_exists($avif_path) && filesize($avif_path) > 0)
								{
									//Insert Image avif
									$media_size_avif = filesize($avif_path);
									$size_avif = convertBytesToSize($media_size_avif);
							
									$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media',
									'`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`',
									'?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?',
									[$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'.avif', $meta_tag, $size_avif, $media_data[0], $media_data[1], '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
								}
								elseif(file_exists($avif_path))
								{
									unlink($avif_path);
								}
							
								imagedestroy($image);
							
								//Resizes
								if(!empty($all_image_resizes))
								{
									foreach($all_image_resizes as $image_resize)
									{
										$media_width = $image_resize['width'];
										$media_height = $image_resize['height'];
							
										if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
										{
											$image = imagecreatefromgif($path);
											$resized_image = imagecreatetruecolor($media_width, $media_height);
											imagealphablending($resized_image, false);
											imagesavealpha($resized_image, true);
											$transparent = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
											imagefill($resized_image, 0, 0, $transparent);
											imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
							
											$avif_resize_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.avif';
											$avif_result = imageavif($resized_image, $avif_resize_path, $avif_quality);
							
											if($avif_result == true && file_exists($avif_resize_path) && filesize($avif_resize_path) > 0)
											{
												$media_size_avif = filesize($avif_resize_path);
												$size_avif = convertBytesToSize($media_size_avif);
							
												$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media',
												'`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`',
												'?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?',
												[$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.avif', $meta_tag, $size_avif, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
											}
											elseif(file_exists($avif_resize_path))
											{
												unlink($avif_resize_path);
											}
							
											imagedestroy($image);
											imagedestroy($resized_image);
										}
									}
								}
							}
						}
						elseif($media_extension == 'jpg' || $media_extension == 'jpeg')
						{
							//Loop through each resize for JPG images
							if(!empty($all_image_resizes))
							{
								foreach($all_image_resizes as $image_resize)
								{
									$media_width = $image_resize['width'];
									$media_height = $image_resize['height'];
									
									if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
									{
										$image = imagecreatefromjpeg($path);
										$resized_image = imagecreatetruecolor($media_width, $media_height);
										imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
										imagejpeg($resized_image, $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.'.$media_extension, $jpg_quality);
										$media_size_jpeg = filesize($_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.'.$media_extension);
										if(isset($media_size_jpeg)) { $size_jpeg = convertBytesToSize($media_size_jpeg); } else { $size_jpeg = ''; }
										
										//Insert Image
										$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.'.$media_extension, $meta_tag, $size_jpeg, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
										
										imagedestroy($image);
										imagedestroy($resized_image);
									}
								}
							}
							
							//Create WebP image.
							if(function_exists('imagewebp') && isset($_POST['create_webp']) && $_POST['create_webp'] == 'Yes')
							{
								$image = imagecreatefromjpeg($path);
								imagepalettetotruecolor($image);
							
								$webp_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'.webp';
								$webp_result = imagewebp($image, $webp_path, $webp_quality);
							
								if($webp_result == true && file_exists($webp_path) && filesize($webp_path) > 0)
								{
									//Insert Image webp
									$media_size_webp = filesize($webp_path);
									$size_webp = convertBytesToSize($media_size_webp);
							
									$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media',
									'`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`',
									'?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?',
									[$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'.webp', $meta_tag, $size_webp, $media_data[0], $media_data[1], '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
								}
								elseif(file_exists($webp_path))
								{
									unlink($webp_path);
								}
							
								imagedestroy($image);
							
								//Resizes
								if(!empty($all_image_resizes))
								{
									foreach($all_image_resizes as $image_resize)
									{
										$media_width = $image_resize['width'];
										$media_height = $image_resize['height'];
							
										if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
										{
											$image = imagecreatefromjpeg($path);
											$resized_image = imagecreatetruecolor($media_width, $media_height);
											imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
							
											$webp_resize_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.webp';
											$webp_result = imagewebp($resized_image, $webp_resize_path, $webp_quality);
							
											if($webp_result == true && file_exists($webp_resize_path) && filesize($webp_resize_path) > 0)
											{
												$media_size_webp = filesize($webp_resize_path);
												$size_webp = convertBytesToSize($media_size_webp);
							
												$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media',
												'`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`',
												'?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?',
												[$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.webp', $meta_tag, $size_webp, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
											}
											elseif(file_exists($webp_resize_path))
											{
												unlink($webp_resize_path);
											}
							
											imagedestroy($image);
											imagedestroy($resized_image);
										}
									}
								}
							}
							
							//Create AVIF image.
							if($avif_enabled && function_exists('imageavif') && isset($_POST['create_avif']) && $_POST['create_avif'] == 'Yes')
							{
								$image = imagecreatefromjpeg($path);
								imagepalettetotruecolor($image);
							
								$avif_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'.avif';
								$avif_result = imageavif($image, $avif_path, $avif_quality);
							
								if($avif_result == true && file_exists($avif_path) && filesize($avif_path) > 0)
								{
									//Insert Image avif
									$media_size_avif = filesize($avif_path);
									$size_avif = convertBytesToSize($media_size_avif);
							
									$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media',
									'`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`',
									'?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?',
									[$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'.avif', $meta_tag, $size_avif, $media_data[0], $media_data[1], '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
								}
								elseif(file_exists($avif_path))
								{
									unlink($avif_path);
								}
							
								imagedestroy($image);
							
								//Resizes
								if(!empty($all_image_resizes))
								{
									foreach($all_image_resizes as $image_resize)
									{
										$media_width = $image_resize['width'];
										$media_height = $image_resize['height'];
							
										if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
										{
											$image = imagecreatefromjpeg($path);
											$resized_image = imagecreatetruecolor($media_width, $media_height);
											imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
							
											$avif_resize_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.avif';
											$avif_result = imageavif($resized_image, $avif_resize_path, $avif_quality);
							
											if($avif_result == true && file_exists($avif_resize_path) && filesize($avif_resize_path) > 0)
											{
												$media_size_avif = filesize($avif_resize_path);
												$size_avif = convertBytesToSize($media_size_avif);
							
												$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media',
												'`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`',
												'?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?',
												[$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.avif', $meta_tag, $size_avif, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
											}
											elseif(file_exists($avif_resize_path))
											{
												unlink($avif_resize_path);
											}
							
											imagedestroy($image);
											imagedestroy($resized_image);
										}
									}
								}
							}
						}
						elseif($media_extension == 'png')
						{
							//Loop through each resize for JPG images
							if(!empty($all_image_resizes))
							{
								foreach($all_image_resizes as $image_resize)
								{
									$media_width = $image_resize['width'];
									$media_height = $image_resize['height'];
									
									if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
									{
										$image = imagecreatefrompng($path);
										$resized_image = imagecreatetruecolor($media_width, $media_height);
										imagealphablending($resized_image, false);
										imagesavealpha($resized_image, true);
										$transparent = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
										imagefill($resized_image, 0, 0, $transparent);
										$transIndex = imagecolortransparent($image);
										imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
										imagepng($resized_image, $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.png', 6);
										$media_size_png = filesize($_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.png');
										if(isset($media_size_png)) { $size_png = convertBytesToSize($media_size_png); } else { $size_png = ''; }
										
										//Insert Image
										$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.png', $meta_tag, $size_png, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
										
										imagedestroy($image);
										imagedestroy($resized_image);
									}
								}
							}
							
							//Create WebP image.
							if(function_exists('imagewebp') && isset($_POST['create_webp']) && $_POST['create_webp'] == 'Yes')
							{
								$image = imagecreatefrompng($path);
								imagepalettetotruecolor($image);
								
								$webp_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'.webp';
								$webp_result = imagewebp($image, $webp_path, $webp_quality);
								
								if($webp_result == true && file_exists($webp_path) && filesize($webp_path) > 0)
								{
									//Insert Image webp
									$media_size_webp = filesize($webp_path);
									$size_webp = convertBytesToSize($media_size_webp);
									
									$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'.webp', $meta_tag, $size_webp, $media_data[0], $media_data[1], '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
								}
								elseif(file_exists($webp_path))
								{
									unlink($webp_path);
								}
								
								imagedestroy($image);
								
								//Loop through each resize from PNG to WEBP images
								if(!empty($all_image_resizes))
								{
									foreach($all_image_resizes as $image_resize)
									{
										$media_width = $image_resize['width'];
										$media_height = $image_resize['height'];
										
										if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
										{
											$image = imagecreatefrompng($path);
											$resized_image = imagecreatetruecolor($media_width, $media_height);
											imagealphablending($resized_image, false);
											imagesavealpha($resized_image, true);
											$transparent = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
											imagefill($resized_image, 0, 0, $transparent);
											imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
											$webp_resize_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.webp';
											$webp_result = imagewebp($resized_image, $webp_resize_path, $webp_quality);
											
											if($webp_result == true && file_exists($webp_resize_path) && filesize($webp_resize_path) > 0)
											{
												$media_size_webp = filesize($webp_resize_path);
												$size_webp = convertBytesToSize($media_size_webp);
												
												$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.webp', $meta_tag, $size_webp, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
											}
											elseif(file_exists($webp_resize_path))
											{
												unlink($webp_resize_path);
											}
											
											imagedestroy($image);
											imagedestroy($resized_image);
										}
									}
								}
							}
							
							//Create AVIF image.
							if($avif_enabled && function_exists('imageavif') && isset($_POST['create_avif']) && $_POST['create_avif'] == 'Yes')
							{
								$image = imagecreatefrompng($path);
								imagepalettetotruecolor($image);
								
								$avif_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'.avif';
								$avif_result = imageavif($image, $avif_path, $avif_quality);
								
								if($avif_result == true && file_exists($avif_path) && filesize($avif_path) > 0)
								{
									//Insert Image avif
									$media_size_avif = filesize($avif_path);
									$size_avif = convertBytesToSize($media_size_avif);
									
									$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'.avif', $meta_tag, $size_avif, $media_data[0], $media_data[1], '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
								}
								elseif(file_exists($avif_path))
								{
									unlink($avif_path);
								}
								
								imagedestroy($image);
								
								//Loop through each resize from PNG to AVIF images
								if(!empty($all_image_resizes))
								{
									foreach($all_image_resizes as $image_resize)
									{
										$media_width = $image_resize['width'];
										$media_height = $image_resize['height'];
										
										if($media_width >= $smallest_variant_size && $media_height >= $smallest_variant_size)
										{
											$image = imagecreatefrompng($path);
											$resized_image = imagecreatetruecolor($media_width, $media_height);
											imagealphablending($resized_image, false);
											imagesavealpha($resized_image, true);
											$transparent = imagecolorallocatealpha($resized_image, 0, 0, 0, 127);
											imagefill($resized_image, 0, 0, $transparent);
											imagecopyresampled($resized_image, $image, 0, 0, 0, 0, $media_width, $media_height, $media_data[0], $media_data[1]);
											$avif_resize_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$original_media_id.'/'.$media_type[0].'-'.$media_width.'w-'.$media_height.'h.avif';
											$avif_result = imageavif($resized_image, $avif_resize_path, $avif_quality);
											
											if($avif_result == true && file_exists($avif_resize_path) && filesize($avif_resize_path) > 0)
											{
												$media_size_avif = filesize($avif_resize_path);
												$size_avif = convertBytesToSize($media_size_avif);
												
												$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Image', 'No', $original_media_id, $media_type[0].'-'.$media_width.'w-'.$media_height.'h.avif', $meta_tag, $size_avif, $media_width, $media_height, '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
											}
											elseif(file_exists($avif_resize_path))
											{
												unlink($avif_resize_path);
											}
											
											imagedestroy($image);
											imagedestroy($resized_image);
										}
									}
								}
							}
						}
					}
					//If video uploaded.
					elseif(!empty($media_extension) && !empty($mime_file_type) && array_key_exists($media_extension, $accepted_video_extension_types) && in_array($mime_file_type, $accepted_video_extension_types))
					{
						$path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/videos/'.$cleaned_media_url;
						
						$display = '';
						$display = '/sites/media/videos/'.$cleaned_media_url;
						
						if(!file_exists($path) && empty($media_exists_in_db))
						{
							move_uploaded_file($_FILES['files']['tmp_name'][$index], $path);
							$upload_success[] = array('video', $display);
							$media_size_video = filesize($path);
							if(isset($media_size_video) && !empty($media_size_video)) { $size_video = convertBytesToSize($media_size_video); } else { $size_video = ''; }
							
							//Insert Video
							$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'Video', 'Yes', NULL, $cleaned_media_url, $meta_tag, $size_video, '', '', '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
						}
						else
						{
							$upload_exists[] = array('video', $display);
						}
					}
					//If file uploaded.
					elseif(!empty($media_extension) && !empty($mime_file_type) && array_key_exists($media_extension, $accepted_file_extension_types) && in_array($mime_file_type, $accepted_file_extension_types))
					{
						
						$path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/files/'.$cleaned_media_url;
						
						$display = '';
						$display = '/sites/media/files/'.$cleaned_media_url;
						
						if(!file_exists($path) && empty($media_exists_in_db))
						{
							move_uploaded_file($_FILES['files']['tmp_name'][$index], $path);
							$upload_success[] = array('file', $display);
							$media_size_file = filesize($path);
							if(isset($media_size_file) && !empty($media_size_file)) { $size_file = convertBytesToSize($media_size_file); } else { $size_file = ''; }
							
							//Insert Video
							$_SESSION['results']->getInsertRecord(__LINE__, __FILE__, 'media', '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`', '?,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?', [$_SESSION["site_set_for_editing"], 'File', 'Yes', NULL, $cleaned_media_url, $meta_tag, $size_file, '', '', '', '', '{}', $_SESSION['user_first_last_name'], $_SESSION['user_first_last_name']]);
						}
						else
						{
							$upload_exists[] = array('file', $display);
						}
					}
					else
					{
						if(!in_array($media_extension, $extension_not_valid))
						{
							$extension_not_valid[] = $media_extension;
						}
					}
				}
			}
			
			$extension_not_valid = array_values(array_unique($extension_not_valid));
			
			if(!empty($upload_success) && empty($upload_exists) && empty($extension_not_valid))
			{
				$status = 'completed';
			}
			elseif(!empty($upload_success))
			{
				$status = 'partial';
			}
			else
			{
				$status = 'failed';
			}
			
			$response = ['status' => $status, 'success' => $upload_success, 'duplicates' => $upload_exists, 'invalid_extensions' => $extension_not_valid];
			
			echo json_encode($response);
			exit();
		}
		else
		{
			echo json_encode(array('error' => 'Invalid upload request.'));
			exit();
		}
	}
}