<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/column-names/mobile_media.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/table/column-names/mobile_media.php');
}
else
{
	if(!class_exists('mobile_media_tcn'))
	{
		class mobile_media_tcn
		{
			public function mobile_media_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				//Media Sliders
				if($_SESSION['admin_table_name'] == "slider_items")
				{
					$get_media_id = '';
					
					if(!empty($sql_custom_fields_rows["mobile_media"]))
					{
						$media_data = explode('~||~', $sql_custom_fields_rows["mobile_media"]);
						$get_media_id = $media_data[0];
					}
					
					if(!empty($get_media_id))
					{
						$sql_get_assigned_media_url_row = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$get_media_id]);
					
						if(!empty($sql_get_assigned_media_url_row))
						{
							$media_library_links = '<a href="/'.$_SESSION['admin_directory'].'/media/?textfield-id='.$get_media_id.'" target="_blank">'.$get_media_id.'</a>';
							
							if($sql_get_assigned_media_url_row["media_type"] == 'Image')
							{
								$original_media_id = $sql_get_assigned_media_url_row["original_media_id"];
								
								echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><img src="/sites/media/images/'.$original_media_id.'/'.$sql_get_assigned_media_url_row["media_url"].'" /><div>Media Type: Image</div><div>Media ID: '.$media_library_links.'</div></li>';
							}
							elseif($sql_get_assigned_media_url_row["media_type"] == 'File')
							{
								$media_data_type = explode('.', $sql_get_assigned_media_url_row["media_url"]);
							echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><object data="/sites/media/files/'.$sql_get_assigned_media_url_row["media_url"].'" type="application/'.$media_data_type[1].'" width="150px" height="75px"></object><div>Media Type: File</div><div>Media ID: '.$media_library_links.'</div></li>';
							}
							elseif($sql_get_assigned_media_url_row["media_type"] == 'Video')
							{
								$media_data_type = explode('.', $sql_get_assigned_media_url_row["media_url"]);
								echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><video controls preload="none"><source src="/sites/media/videos/'.$sql_get_assigned_media_url_row["media_url"].'" type="video/'.$media_data_type[1].'"></video><div>Media Type: Video</div><div>Media ID: '.$media_library_links.'</div></li>';
							}
							elseif($sql_get_assigned_media_url_row["media_type"] == 'Video Embed')
							{
								echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><iframe width="150px" height="75px" src="'.$sql_get_assigned_media_url_row["media_url"].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe><div>Media Type: Video Embed</div><div>Media ID: '.$media_library_links.'</div></li>';
							}
						}
						else 
						{ 
							echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">Cannot find media id <a href="/'.$_SESSION['admin_directory'].'/media/?textfield-id='.$get_media_id.'">'.$get_media_id.'</a></li>'; 
						}
					}
					else
					{
						echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"></li>'; 
					}
				}
				else
				{
					$else_table_content = ''; 
					if(!empty(substr($sql_custom_fields_rows[$sql_account_columns_active["column_name"]] ?? '', 0, 100)))
					{
						$else_table_content = substr($sql_custom_fields_rows[$sql_account_columns_active["column_name"]] ?? '', 0, 100);
					}
					echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'">'.htmlspecialchars($else_table_content ?? '').'</li>';
				}
			}
		}
		
		$class_mobile_media_tcn = new mobile_media_tcn();
	}
	
	$class_mobile_media_tcn->mobile_media_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}