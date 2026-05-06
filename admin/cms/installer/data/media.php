<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

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

$media_column_names = '`site_id`, `media_type`, `original_media`, `original_media_id`, `media_url`, `media_tag`, `media_size`, `width`, `height`, `video_poster`, `embed_media`, `custom_fields`, `updated_date`, `updated_by`, `created_date`, `created_by`';
$media_placeholders = '0,?,?,?,?,?,?,?,?,?,?,?,UTC_TIMESTAMP(),?,UTC_TIMESTAMP(),?';

$media_to_install = array(
	array('template-screenshot-default.png', 'Template Screenshot - Default', '1518', '880'), 
	array('photo-coming-soon-250-250.gif', 'Photo Coming Soon - 250px x 250px', '250', '250'), 
	array('image-coming-soon-650-650.gif', 'Image Coming Soon - 650px x 650px', '650', '650'), 
	array('image-coming-soon-600-300.gif', 'Image Coming Soon - 600px x 300px', '600', '300'), 
	array('image-coming-soon-375-375.gif', 'Image Coming Soon - 375px - 375px', '375', '375'), 
	array('image-coming-soon-1500-300.gif', 'Image Coming Soon - 1500px x 300px', '1500', '300'), 
	array('image-coming-soon-1050-500.gif', 'Image Coming Soon - 1050px x 500px', '1050', '500'), 
	array('image-coming-soon-1025-300.gif', 'Image Coming Soon - 1025px x 300px', '1025', '300'), 
	array('video-icon.gif', 'Video Icon', '100', '100'), 
	array('file-icon.gif', 'File Icon', '100', '100'), 
	array('favicon-16x16.png', 'Favicon - 16px x 16px', '16', '16'), 
	array('favicon-32x32.png', 'Favicon - 32px x 32px', '32', '32'), 
	array('favicon-180x180.png', 'Favicon - 180px x 180px', '180', '180')
);

foreach($media_to_install as $media_file_array)
{
	$file_name_and_extenstion = $media_file_array[0];
	$file_name_alt_tag = $media_file_array[1];
	$file_width = $media_file_array[2];
	$file_height = $media_file_array[3];
	
	$exploded_file_name = explode('.', $file_name_and_extenstion);
	$file_name_only = $exploded_file_name[0];
	$file_extenstion_only = $exploded_file_name[1];
	
	$sql_media_id_row_exists = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `media_url` = ? LIMIT 1', [$file_name_and_extenstion]);
	if(empty($sql_media_id_row_exists))
	{
		//Insert original media recorded into db.
		$inserted_media_id_row = $results->getInsertRecord(__LINE__, __FILE__, 'media', $media_column_names, $media_placeholders,					  
		['Image', 'Yes', NULL, $file_name_and_extenstion, $file_name_alt_tag, '', $file_width, $file_height, '', '', '{}', $first_last_name, $first_last_name]);
		$_SESSION[$file_name_and_extenstion] = $inserted_media_id_row;
		
		//Create media folder using original media id.
		if(!is_dir($_SERVER['DOCUMENT_ROOT']."/sites/media/images/".$inserted_media_id_row)) { mkdir($_SERVER['DOCUMENT_ROOT']."/sites/media/images/".$inserted_media_id_row, 0755, true); }
		
		if(!file_exists($_SERVER['DOCUMENT_ROOT']."/sites/media/images/".$inserted_media_id_row."/".$file_name_and_extenstion))
		{
			//Make sure avif is fully enabled if admin user asking to create them.
			if(!isset($flag_run_once))
			{
				$flag_run_once = true;
				$avif_enabled = avif_enabled($_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/templates/".$template_to_install."/images/".$file_name_and_extenstion);
			}
			
			//Update media recordwith original media id.
			copy($_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/templates/".$template_to_install."/images/".$file_name_and_extenstion, $_SERVER['DOCUMENT_ROOT']."/sites/media/images/".$inserted_media_id_row."/".$file_name_and_extenstion);
			$media_size = filesize($_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$inserted_media_id_row.'/'.$file_name_and_extenstion);
			if(isset($media_size)) { $media_size = convertBytesToSize($media_size); } else { $media_size = ''; }
			
			//Update media record just inserted with original media id and media size.
			$results->getUpdateRecord(__LINE__, __FILE__, 'media', '`original_media_id` = ?, `media_size` = ?', 'WHERE `id` = ?', [$inserted_media_id_row, $media_size, $inserted_media_id_row]);
			
			//Prepare creation of .webp and .avif images.
			if($file_extenstion_only == 'gif')
			{
				$image = imagecreatefromgif($_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/templates/".$template_to_install."/images/".$file_name_and_extenstion);
			}
			elseif($file_extenstion_only == 'jpg' || $file_extenstion_only == 'jpeg')
			{
				$image = imagecreatefromjpeg($_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/templates/".$template_to_install."/images/".$file_name_and_extenstion);
			}
			elseif($file_extenstion_only == 'png')
			{
				$image = imagecreatefrompng($_SERVER['DOCUMENT_ROOT']."/admin/cms/installer/templates/".$template_to_install."/images/".$file_name_and_extenstion);
			}
			else
			{
				continue; //unsupported format
			}
			imagepalettetotruecolor($image);
			
			//Create .webp image format.
			//Make sure imagewebp is enabled before allowing create.
			if(function_exists('imagewebp'))
			{
				$webp_image_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$inserted_media_id_row.'/'.$file_name_only.'.webp';
				$webp_result = imagewebp($image, $webp_image_path);
				if($webp_result == true && file_exists($webp_image_path) && filesize($webp_image_path) > 0)
				{
					//Save to database if created properly.
					$media_size_webp = filesize($webp_image_path);
					$size_webp = convertBytesToSize($media_size_webp);
					$results->getInsertRecord(__LINE__, __FILE__, 'media', $media_column_names, $media_placeholders,					  
					['Image', 'No', $inserted_media_id_row, $file_name_only.'.webp', $file_name_alt_tag, $size_webp, $file_width, $file_height, '', '', '{}', $first_last_name, $first_last_name]);
				}
				elseif(file_exists($webp_image_path))
				{
					//Delete image if not created properly.
					unlink($webp_image_path);
				}
			}
			
			//Create .avif image format.
			//Some PHP builds include imageavif() but disable AVIF encoding at compile time.
			//In those cases the function exists but will produce failed/empty outputs.
			if($avif_enabled && function_exists('imageavif'))
			{
				$avif_image_path = $_SERVER['DOCUMENT_ROOT'].'/sites/media/images/'.$inserted_media_id_row.'/'.$file_name_only.'.avif';
				$avif_result = imageavif($image, $avif_image_path);
				if($avif_result == true && file_exists($avif_image_path) && filesize($avif_image_path) > 0)
				{
					//Save to database if created properly.
					$media_size_avif = filesize($avif_image_path);
					$size_avif = convertBytesToSize($media_size_avif);
					$results->getInsertRecord(__LINE__, __FILE__, 'media', $media_column_names, $media_placeholders,					  
					['Image', 'No', $inserted_media_id_row, $file_name_only.'.avif', $file_name_alt_tag, $size_avif, $file_width, $file_height, '', '', '{}', $first_last_name, $first_last_name]);
				}
				elseif(file_exists($avif_image_path))
				{
					//Delete image if not created properly.
					unlink($avif_image_path);
				}
			}
			
			//Free memory used by the image resource
			imagedestroy($image);
		}
	}
}