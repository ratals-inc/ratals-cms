<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/seoScore.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/classes/add-edit/admin-fields/seoScore.php');
}
else
{
	if(!class_exists('seoScoreAeaf'))
	{
		class seoScoreAeaf
		{
			public function seoScoreAeaf($table_name, $admin_field, $field_value, &$errors, &$post_values, $current_values, $sites, $domain)
			{
				//Calculates the SEO score.
				$seo_title = 'close';
				$seo_top_content_100 = 'close';
				$seo_h1 = 'close';
				$seo_url = 'close';
				$seo_top_content = 'close';
				$seo_bottom_content = 'close';
				$seo_description = 'close';
				$seo_alt_tag = 'close';
				
				$seo_keyword_for_score = '';
				if(isset($post_values[$table_name]['seo_keyword']) && !empty($post_values[$table_name]['seo_keyword']))
				{
					$seo_keyword_for_score = $post_values[$table_name]['seo_keyword'];
				}
				elseif(isset($current_values[$table_name]['seo_keyword']) && !empty($current_values[$table_name]['seo_keyword']))
				{
					$seo_keyword_for_score = $current_values[$table_name]['seo_keyword'];
				}
				
				//Calculate SEO Score
				$seo_score = 0;
				if(!empty($seo_keyword_for_score))
				{
					$seo_keyword_focus = strtolower($seo_keyword_for_score);
					
					if(!empty($_POST) && isset($post_values['urls']['meta_title']) && !empty($post_values['urls']['meta_title']) && strpos(strtolower($post_values['urls']['meta_title']), $seo_keyword_focus) !== false) 
					{
						$seo_score = $seo_score + 25; 
						$title_checkmark = "Yes";
					}
					elseif(empty($_POST) && isset($current_values['urls']['meta_title']) && !empty($current_values['urls']['meta_title']) && strpos(strtolower($current_values['urls']['meta_title']), $seo_keyword_focus) !== false) 
					{
						$seo_score = $seo_score + 25; 
						$title_checkmark = "Yes";
					}
					else
					{
						$title_checkmark = "No";
					}
					
					if(!empty($_POST) && isset($post_values[$table_name]['top_content']) && !empty($post_values[$table_name]['top_content']) && strpos(implode(' ', array_slice(explode(' ', strtolower($post_values[$table_name]['top_content'])), 0, 99)), $seo_keyword_focus) !== false) 
					{
						$seo_score = $seo_score + 20; 
						$top_content_100_checkmark = "Yes";
					}
					elseif(empty($_POST) && isset($current_values[$table_name]['top_content']) && !empty($current_values[$table_name]['top_content']) && strpos(implode(' ', array_slice(explode(' ', strtolower($current_values[$table_name]['top_content'])), 0, 99)), $seo_keyword_focus) !== false) 
					{
						$seo_score = $seo_score + 20; 
						$top_content_100_checkmark = "Yes";
					}
					else
					{
						$top_content_100_checkmark = "No";
					}
					
					if(!empty($_POST) && isset($post_values[$table_name]['content_title']) && !empty($post_values[$table_name]['content_title']) && strpos(strtolower($post_values[$table_name]['content_title']), $seo_keyword_focus) !== false) 
					{
						$seo_score = $seo_score + 15;
						$content_title_checkmark = "Yes";
					}
					elseif(empty($_POST) && isset($current_values[$table_name]['content_title']) && !empty($current_values[$table_name]['content_title']) && strpos(strtolower($current_values[$table_name]['content_title']), $seo_keyword_focus) !== false) 
					{
						$seo_score = $seo_score + 15;
						$content_title_checkmark = "Yes";
					}
					else
					{
						$content_title_checkmark = "No";
					}
					
					if(!empty($_POST) && isset($post_values[$table_name]['top_content']) && !empty($post_values[$table_name]['top_content']) && strpos(strtolower($post_values[$table_name]['top_content']), $seo_keyword_focus) !== false)
					{
						$seo_score = $seo_score + 10;
						$top_content_checkmark = "Yes";
					}
					elseif(empty($_POST) && isset($current_values[$table_name]['top_content']) && !empty($current_values[$table_name]['top_content']) && strpos(strtolower($current_values[$table_name]['top_content']), $seo_keyword_focus) !== false)
					{
						$seo_score = $seo_score + 10;
						$top_content_checkmark = "Yes";
					}
					else
					{
						$top_content_checkmark = "No";
					}
					
					if(!empty($_POST) && isset($post_values[$table_name]['bottom_content']) && !empty($post_values[$table_name]['bottom_content']) && strpos(strtolower($post_values[$table_name]['bottom_content']), $seo_keyword_focus) !== false)
					{
						$seo_score = $seo_score + 5;
						$bottom_content_checkmark = "Yes";
					}
					elseif(empty($_POST) && isset($current_values[$table_name]['bottom_content']) && !empty($current_values[$table_name]['bottom_content']) && strpos(strtolower($current_values[$table_name]['bottom_content']), $seo_keyword_focus) !== false)
					{
						$seo_score = $seo_score + 5;
						$bottom_content_checkmark = "Yes";
					}
					else
					{
						$bottom_content_checkmark = "No";
					}
					
					if(!empty($_POST) && isset($post_values['urls']['meta_description']) && !empty($post_values['urls']['meta_description']) && strpos(strtolower($post_values['urls']['meta_description']), $seo_keyword_focus) !== false) 
					{
						$seo_score = $seo_score + 5; 
						$description_checkmark = "Yes";
					}
					elseif(empty($_POST) && isset($current_values['urls']['meta_description']) && !empty($current_values['urls']['meta_description']) && strpos(strtolower($current_values['urls']['meta_description']), $seo_keyword_focus) !== false) 
					{
						$seo_score = $seo_score + 5; 
						$description_checkmark = "Yes";
					}
					else
					{
						$description_checkmark = "No";
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
					elseif(empty($_POST) && isset($current_values[$table_name]['single_media']) && !empty($current_values[$table_name]['single_media']))
					{
						$seo_media_data = $current_values[$table_name]['single_media'];
					}
					elseif(empty($_POST) && isset($current_values[$table_name]['media']) && !empty($current_values[$table_name]['media']))
					{
						$seo_media_data = $current_values[$table_name]['media'];
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
								$alt_tag_checkmark = "Yes";
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
										$alt_tag_checkmark = "Yes";
										break;
									}
								}
							}
						}
					}
					
					if(!empty($current_values['urls']['url_extension']))
					{
						$display_url_extension = $current_values['urls']['url_extension'];
					}
					else
					{
						$display_url_extension = $sites["global_url_extension"];
					}
					
					if(isset($post_values['urls']['hierarchy_url']))
					{
						$hierarchy_url_value = $post_values['urls']['hierarchy_url'];
					}
					else
					{
						$hierarchy_url_value = $current_values['urls']['hierarchy_url'];
					}
					
					$hierarchy_url = '';
					$hierarchy_url_path = '';
					if(isset($hierarchy_url_value))
					{
						$hierarchy_url_array = explode("/", $hierarchy_url_value);
						$hierarchy_url = end($hierarchy_url_array);
						
						$hierarchy_url_path = implode('/', explode('/', $hierarchy_url_value, -1)).'/';
						if($hierarchy_url_path == '/')
						{
							$hierarchy_url_path = '';
						}
					}
					
					//SEO Keyword in URL
					if($sites["url_structure"] == 'Hierarchy')
					{
						if(strpos(strtolower(str_replace(array("/","-",":","."), " ", $domain.'/'.$hierarchy_url_path.$hierarchy_url.$display_url_extension)), $seo_keyword_focus) !== false) 
						{ 
							$seo_score = $seo_score + 15;
							$url_checkmark = "Yes";
						}
						else
						{
							$url_checkmark = "No";
						}
					}
					else
					{
						if(strpos(strtolower(str_replace(array("/","-",":","."), " ", $domain.'/'.$field_value.$display_url_extension)), $seo_keyword_focus) !== false) 
						{ 
							$seo_score = $seo_score + 15;
							$url_checkmark = "Yes";
						}
						else
						{
							$url_checkmark = "No";
						}
					}
					
					$field_value = $seo_score;
					
					if($title_checkmark == "Yes") { $seo_title = 'check'; }
					if($top_content_100_checkmark == "Yes") { $seo_top_content_100 = 'check'; }
					if($content_title_checkmark == "Yes") { $seo_h1 = 'check'; }
					if($url_checkmark == "Yes") { $seo_url = 'check'; }
					if($top_content_checkmark == "Yes") { $seo_top_content = 'check'; }
					if($bottom_content_checkmark == "Yes") { $seo_bottom_content = 'check'; }
					if($description_checkmark == "Yes") { $seo_description = 'check'; }
					if($alt_tag_checkmark == "Yes") { $seo_alt_tag = 'check'; }
				}
				
				echo '<div class="edit">
				<div class="edit-label">'.htmlspecialchars($admin_field["name"] ?? '').'</div>
				<div class="edit-field">
				<div class="seo-score">'.htmlspecialchars($seo_score ?? '').' out of 100%</div>
				<div class="seo-score-breakdown">
				<ul>
				  <li class="'.htmlspecialchars($seo_title ?? '').'">25% - SEO Keyword in Title</li>
				  <li class="'.htmlspecialchars($seo_top_content_100 ?? '').'">20% - SEO Keyword in First 100 Words of Main Content</li>
				  <li class="'.htmlspecialchars($seo_h1 ?? '').'">15% - SEO Keyword in Content Title/H1</li>
				  <li class="'.htmlspecialchars($seo_url ?? '').'">15% - SEO Keyword in URL</li>
				  <li class="'.htmlspecialchars($seo_top_content ?? '').'">10% - SEO Keyword in Main Content</li>
				  <li class="'.htmlspecialchars($seo_bottom_content ?? '').'">5% - SEO Keyword in Bottom Content</li>
				  <li class="'.htmlspecialchars($seo_description ?? '').'">5% - SEO Keyword in Meta Description</li>
				  <li class="'.htmlspecialchars($seo_alt_tag ?? '').'">5% - SEO Keyword in Media ALT Tag</li>
				</ul>
				</div>
				</div>
				</div>
				<input name="'.htmlspecialchars($table_name.'['.$admin_field["column_name"].']' ?? '').'" type="hidden" value="'.htmlspecialchars($field_value ?? '').'">
				';
			}
		}
		
		$class_seoScoreAeaf = new seoScoreAeaf();
	}
	
	$class_seoScoreAeaf->seoScoreAeaf($table_name, $admin_field, $field_value, $errors, $post_values, $current_values, $sites, $domain);
}