<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/seo_score.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/classes/add-edit/column-names/seo_score.php');
}
else
{
	if(!class_exists('seo_score_aecn'))
	{
		class seo_score_aecn
		{
			public function seo_score_aecn($table_name, $admin_field, &$post_values, &$errors, $sites, $domain)
			{
				if(isset($_POST[$table_name][$admin_field["column_name"]]) && !empty($_POST[$table_name][$admin_field["column_name"]]))
				{
					//Calculate SEO score based on values submitted.
					$seo_keyword_for_score = $post_values[$table_name]['seo_keyword'];
					
					$seo_score = 0;
					if(!empty($seo_keyword_for_score))
					{
						$seo_keyword_focus = strtolower($seo_keyword_for_score);
						
						if(!empty($_POST) && isset($post_values['urls']['meta_title']) && !empty($post_values['urls']['meta_title']) && strpos(strtolower($post_values['urls']['meta_title']), $seo_keyword_focus) !== false) 
						{
							$seo_score = $seo_score + 25; 
						}
						
						if(!empty($_POST) && isset($post_values[$table_name]['top_content']) && !empty($post_values[$table_name]['top_content']) && strpos(implode(' ', array_slice(explode(' ', strtolower($post_values[$table_name]['top_content'])), 0, 99)), $seo_keyword_focus) !== false) 
						{
							$seo_score = $seo_score + 20; 
						}
						
						if(!empty($_POST) && isset($post_values[$table_name]['content_title']) && !empty($post_values[$table_name]['content_title']) && strpos(strtolower($post_values[$table_name]['content_title']), $seo_keyword_focus) !== false) 
						{
							$seo_score = $seo_score + 15;
						}
						
						if(!empty($_POST) && isset($post_values[$table_name]['top_content']) && !empty($post_values[$table_name]['top_content']) && strpos(strtolower($post_values[$table_name]['top_content']), $seo_keyword_focus) !== false)
						{
							$seo_score = $seo_score + 10;
						}
						
						if(!empty($_POST) && isset($post_values[$table_name]['bottom_content']) && !empty($post_values[$table_name]['bottom_content']) && strpos(strtolower($post_values[$table_name]['bottom_content']), $seo_keyword_focus) !== false)
						{
							$seo_score = $seo_score + 5;
						}
						
						if(!empty($_POST) && isset($post_values['urls']['meta_description']) && !empty($post_values['urls']['meta_description']) && strpos(strtolower($post_values['urls']['meta_description']), $seo_keyword_focus) !== false) 
						{
							$seo_score = $seo_score + 5; 
						}
						
						$seo_media_data = '';
						if(!empty($_POST) && isset($post_values[$table_name]['single_media']) && !empty($post_values[$table_name]['single_media']))
						{
							$seo_media_data = $post_values[$table_name]['single_media'];
						}
						elseif(!empty($_POST) && isset($post_values[$table_name]['media']) && !empty($post_values[$table_name]['media']))
						{
							$seo_media_data = $post_values[$table_name]['media'];
						}
						
						$assigned_media_array = array();
						if(!empty($seo_media_data))
						{
							if(strpos($seo_media_data, '*||*'))
							{
								$assigned_media_array = explode('*||*',$seo_media_data);
							}
							elseif(!empty($seo_media_data))
							{
								$assigned_media_array[] = $seo_media_data;
							}
						}
						
						$alt_tag_checkmark = "No";
						if(!empty($assigned_media_array))
						{
							foreach($assigned_media_array as $assigned_media)
							{
								$assigned_media_array = explode('~||~', $assigned_media);
								$assigned_media_id = $assigned_media_array[0];
								$assigned_media_tag = $assigned_media_array[1];
								
								if(!empty($assigned_media_tag) && strpos(strtolower($assigned_media_tag), $seo_keyword_focus) !== false) 
								{
									$seo_score = $seo_score + 5;
									break;
								}
								else
								{
									$sql_media_tag_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'media', 'WHERE `id` = ?', [$assigned_media_id]);
									
									if($sql_media_tag_rows > 0)
									{
										if(strpos(strtolower($sql_media_tag_rows['media_tag']), $seo_keyword_focus) !== false)
										{
											$seo_score = $seo_score + 5; 
											break;
										}
									}
								}
							}
						}
						
						//SEO Keyword in URL
						if($sites["url_structure"] == 'Hierarchy')
						{
							if(strpos(strtolower(str_replace(array("/","-",":","."), " ", $post_values['urls']['hierarchy_url'].$post_values['urls']['url_extension'])), $seo_keyword_focus) !== false) 
							{ 
								$seo_score = $seo_score + 15;
							}
						}
						else
						{
							if(strpos(strtolower(str_replace(array("/","-",":","."), " ", $post_values['urls']['flat_url'].$post_values['urls']['url_extension'])), $seo_keyword_focus) !== false) 
							{ 
								$seo_score = $seo_score + 15;
							}
						}
					}
					
					$post_values[$table_name][$admin_field["column_name"]] = $seo_score;
				}
			}
		}
		
		$class_seo_score_aecn = new seo_score_aecn();
	}
	
	if($_SESSION['admin_type'] == 'add' || $_SESSION['admin_type'] == 'edit')
	{
		$class_seo_score_aecn->seo_score_aecn($table_name, $admin_field, $post_values, $errors, $sites, $domain);
	}
}