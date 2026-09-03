<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/cf-display-as/singleMedia.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/cf-display-as/singleMedia.php');
}
else
{
	if(!class_exists('singleMediaTcfda'))
	{
		class singleMediaTcfda
		{
			public function singleMediaTcfda($sql_custom_fields_rows, $sql_account_columns_active)
			{
				$sql_get_custom_media_id = '';
				$media_tag_items_array = array();
				$media_tag_item_array = array();
				$sql_media_tag_row = '';
				
				if(!empty($sql_custom_fields_rows['custom_fields']))
				{
					$custom_field_array = JSON_DECODE($sql_custom_fields_rows['custom_fields'] ?? '', true);
					
					if(isset($custom_field_array[$sql_account_columns_active["column_name"]]))
					{
						$sql_get_custom_media_id = $custom_field_array[$sql_account_columns_active["column_name"]];
						
						if(strpos($sql_get_custom_media_id, '*||*') !== false)
						{
							$media_tag_items_array = explode('*||*', $sql_get_custom_media_id);
							$media_tag_item_array = explode('~||~', $media_tag_items_array[0]);
						}
						elseif(!empty($sql_get_custom_media_id))
						{
							$media_tag_item_array = explode('~||~', $sql_get_custom_media_id);
						}	
						
						if(!empty($media_tag_item_array))
						{
							$sql_media_tag_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$media_tag_item_array[0]]);
						}
							
						if(!empty($sql_media_tag_row))
						{
							$media_data = '';
							if(!empty($media_tag_items_array))
							{
								foreach($media_tag_items_array as $media_items)
								{
									$media_item_id = explode('~||~', $media_items);
									$media_data .= '<a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.$media_item_id[0].'" target="_blank">'.$media_item_id[0].'</a>, ';
								}
								$media_data = trim($media_data ?? '', ', ');
							}
							elseif(!empty($media_tag_item_array))
							{
								$media_data = '<a href="'.INSTALLATION_URL_PATH.'/'.$_SESSION['admin_directory'].'/media/?textfield-id='.$media_tag_item_array[0].'" target="_blank">'.$media_tag_item_array[0].'</a>';
							}
							
							if($sql_media_tag_row["media_type"] == 'Image')
							{
								$original_media_id = $sql_media_tag_row["original_media_id"];
								
								echo '<li class="table-cell-results media-center"><img src="'.INSTALLATION_URL_PATH.'/sites/media/images/'.$original_media_id.'/'.$sql_media_tag_row["media_url"].'" /><div>Media IDs: '.$media_data.'</div></li>';
							}
							elseif($sql_media_tag_row["media_type"] == 'File')
							{
								$media_data_type = explode('.', $sql_media_tag_row["media_url"]);
								echo '<li class="table-cell-results media-center"><object class="width-max-height-aspect-16-16-margin" data="'.INSTALLATION_URL_PATH.'/sites/media/files/'.$sql_media_tag_row["media_url"].'" type="application/'.$media_data_type[1].'"></object><div>Media IDs: '.$media_data.'</div></li>';
							}
							elseif($sql_media_tag_row["media_type"] == 'Video')
							{
								$media_data_type = explode('.', $sql_media_tag_row["media_url"]);
								echo '<li class="table-cell-results media-center"><video controls preload="none"><source src="'.INSTALLATION_URL_PATH.'/sites/media/videos/'.$sql_media_tag_row["media_url"].'" type="video/'.$media_data_type[1].'"></video><div>Media IDs: '.$media_data.'</div></li>';
							}
							elseif($sql_media_tag_row["media_type"] == 'Video Embed')
							{
								echo '<li class="table-cell-results media-center"><iframe class="width-max-height-aspect-16-16-margin" src="'.$sql_media_tag_row["media_url"].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe><div>Media IDs: '.$media_data.'</div></li>';
							}
							else 
							{ 
								echo '<li class="table-cell-results"></li>'; 
							}
						}
						else 
						{ 
							echo '<li class="table-cell-results"></li>'; 
						}
					}
					else
					{
						echo '<li class="table-cell-results"></li>';
					}
				}
				else
				{
					echo '<li class="table-cell-results"></li>'; 
				}
			}
		}
		
		$class_singleMediaTcfda = new singleMediaTcfda();
	}
	
	$class_singleMediaTcfda->singleMediaTcfda($sql_custom_fields_rows, $sql_account_columns_active);
}