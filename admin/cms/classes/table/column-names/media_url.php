<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/media_url.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/table/column-names/media_url.php');
}
else
{
	if(!class_exists('media_url_tcn'))
	{
		class media_url_tcn
		{
			public function media_url_tcn($sql_custom_fields_rows, $sql_account_columns_active)
			{
				//Media Library
				if($_SESSION['admin_table_name'] == "media")
				{
					$media_output_data = explode('.', $sql_custom_fields_rows["media_url"]);
					if($sql_custom_fields_rows["media_type"] == 'Image')
					{
						$original_media_id = $sql_custom_fields_rows["original_media_id"];
						
						echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><img src="/sites/media/images/'.$original_media_id.'/'.$sql_custom_fields_rows["media_url"].'"/><div>File Name:<br><a href="'.$_SESSION['view_frontend_of_site'].'/sites/media/images/'.$original_media_id.'/'.$sql_custom_fields_rows["media_url"].'" target="_blank">'.$sql_custom_fields_rows["media_url"].'</a></div></li>';
					}
					elseif($sql_custom_fields_rows["media_type"] == 'File')
					{
						echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><object class="width-max-height-aspect-margin" data="/sites/media/files/'.$sql_custom_fields_rows["media_url"].'" type="application/'.$media_output_data[1].'"></object><div>File Name:<br><a href="'.$_SESSION['view_frontend_of_site'].'/sites/media/files/'.$sql_custom_fields_rows["media_url"].'" target="_blank">'.$sql_custom_fields_rows["media_url"].'</a></div></li>';
					}
					elseif($sql_custom_fields_rows["media_type"] == 'Video')
					{
						echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><video controls preload="none"><source src="/sites/media/videos/'.$sql_custom_fields_rows["media_url"].'" type="video/'.$media_output_data[1].'"></video><div>File Name:<br><a href="'.$_SESSION['view_frontend_of_site'].'/sites/media/videos/'.$sql_custom_fields_rows["media_url"].'" target="_blank">'.$sql_custom_fields_rows["media_url"].'</a></div></li>';
					}
					elseif($sql_custom_fields_rows["media_type"] == 'Video Embed')
					{
						echo '<li class="table-cell-results '.$sql_account_columns_active["css_class"].'"><iframe class="width-max-height-aspect-margin" src="'.$sql_custom_fields_rows["media_url"].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe><div>Video Embed URL:<br><a href="'.$sql_custom_fields_rows["media_url"].'" target="_blank">'.$sql_custom_fields_rows["media_url"].'</a></div></li>';
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
		
		$class_media_url_tcn = new media_url_tcn();
	}
	
	$class_media_url_tcn->media_url_tcn($sql_custom_fields_rows, $sql_account_columns_active);
}