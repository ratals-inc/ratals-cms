<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/urls.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/functions/urls.php');
}
else
{
	//Create url with urls table data.
	if(!function_exists('getUrl'))
	{
		function getUrl($custom_link, $page_extension, $settings_extension, $url_structure, $domain, $hierarchy_url, $flat_url, $inventory_attribute_url, $url_id, $home_page)
		{
			if(empty($custom_link)) 
			{ 
				if(!empty($page_extension))
				{
				   $url_end_url_with = $page_extension;
				}
				else
				{
				   $url_end_url_with = $settings_extension;
				}
				
				if($url_structure == 'Hierarchy')
				{
				   $url = $domain."/".$hierarchy_url.$url_end_url_with.$inventory_attribute_url; 
				}
				elseif($url_structure == 'Flat')
				{
				   $url = $domain."/".$flat_url.$url_end_url_with.$inventory_attribute_url; 
				}
			}
			else
			{
			   $url = $custom_link;
			}
			
			if($home_page == $url_id && empty($custom_link))
			{
			   $url = $domain."/";
			}
			elseif(!empty($custom_link))
			{
			   $url = $custom_link;
			}
			
			return $url;
		}
	}
	
	//Get url by urlId
	if(!function_exists('urlId'))
	{
		function urlId($get_url_from_id, $db_results = NULL) 
		{
			global $site_id, $home_page, $domain, $url_structure, $sites_end_urls_with;
			
			$results = $_SESSION['results'] ?? $db_results;
			
			if(strpos($get_url_from_id, "urlId(") !== false)
			{
				//Get urls for content embed code. Explode/get urls for these, echo urlId(14);, in content.
				$url_results = explode("[?!!?]", str_replace(array("urlId(",");"), "[?!!?]", $get_url_from_id));
				
				$get_enbeded_urls = array();
				
				if(count($url_results) > 0)
				{
					foreach($url_results as $url_result)
					{
						if(is_numeric($url_result))
						{
							$correct_url_result = $url_result;
							
							if($url_result == 0)
							{
								$url_result = $home_page;
							}
							
							$sql_fetch_url_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ? AND `site_id` = ? AND `url_status` = ? LIMIT 1', [$url_result, $site_id, '1']);
							
							if(!empty($sql_fetch_url_rows))
							{
								if(empty($sql_fetch_url_rows["custom_link"])) 
								{ 
									if(!empty($sql_fetch_url_rows["url_extension"])) 
									{ 
										$url_end_url_with = $sql_fetch_url_rows["url_extension"]; 
									} 
									else 
									{ 
										$url_end_url_with = $sites_end_urls_with; 
									}
									
									if($url_structure == 'Hierarchy') 
									{ 
										$url_url_results["fetch_url"] = $domain."/".$sql_fetch_url_rows["hierarchy_url"].$url_end_url_with; 
									}
									elseif($url_structure == 'Flat') 
									{
										$url_url_results["fetch_url"] = $domain."/".$sql_fetch_url_rows["flat_url"].$url_end_url_with; 
									}
								} 
								else 
								{ 
									$url_url_results["fetch_url"] = $sql_fetch_url_rows["custom_link"] == ""; 
								}
								
								if($home_page == $sql_fetch_url_rows["id"] && empty($sql_fetch_url_rows["custom_link"])) 
								{
									$url_url_results["fetch_url"] = $domain."/";
								}
								else
								{
									if(!empty($sql_fetch_url_rows["custom_link"]))
									{
										$url_url_results["fetch_url"] = $sql_fetch_url_rows["custom_link"];
									}
								}
								
								$get_enbeded_urls[] = array("replace" => "urlId(".$correct_url_result.");", "url" => $url_url_results["fetch_url"]);
							}
							else
							{
								//If the page/url is not enabled set the link to the homepage or the domain.
								$get_enbeded_urls[] = array("replace" => "urlId(".$correct_url_result.");", "url" => $domain.'/?url-id-disabled-or-deleted='.$correct_url_result);
							}
						}
					}
				}
				if(!empty($get_enbeded_urls))
				{
					foreach($get_enbeded_urls as $get_enbeded_url)
					{
						$get_url_from_id = str_replace($get_enbeded_url["replace"], $get_enbeded_url["url"], $get_url_from_id);
					}
				}
			}
			elseif(is_numeric($get_url_from_id))
			{
				//Get urls for template embed code
				global $site_id, $home_page, $domain, $url_structure, $sites_end_urls_with;
				
				if($get_url_from_id == 0)
				{
					$get_url_from_id = $home_page;
				}
				
				$sql_fetch_url_rows = $results->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ? AND `site_id` = ? AND `url_status` = ? LIMIT 1', [$get_url_from_id, $site_id, '1']);
				
				if(!empty($sql_fetch_url_rows))
				{
					if(empty($sql_fetch_url_rows["custom_link"])) 
					{ 
					
						if(!empty($sql_fetch_url_rows["hierarchy_url"])) 
						{
							$pages_hierarchy_url_value = $sql_fetch_url_rows["hierarchy_url"]; 
						}
						else
						{
							$pages_hierarchy_url_value = '';
						}
						
						if(!empty($sql_fetch_url_rows["url_extension"]))
						{
							$url_end_url_with = $sql_fetch_url_rows["url_extension"];
						}
						else
						{
							$url_end_url_with = $sites_end_urls_with;
						}
						
						if(!empty($sql_fetch_url_rows["id"]))
						{
							$pages_unique_id_value = $sql_fetch_url_rows["id"];
						}
						else
						{
							$pages_unique_id_value = '';
						}
						
						if($url_structure == 'Hierarchy')
						{
							$url_url_results["fetch_url"] = $domain."/".$pages_hierarchy_url_value.$url_end_url_with; 
						}
						elseif($url_structure == 'Flat')
						{
							$url_url_results["fetch_url"] = $domain."/".$sql_fetch_url_rows["flat_url"].$url_end_url_with; 
						}
					}
					else
					{
						$url_url_results["fetch_url"] = $sql_fetch_url_rows["custom_link"] == "";
					}
					
					if($home_page == $pages_unique_id_value && empty($sql_fetch_url_rows["custom_link"]))
					{
						$url_url_results["fetch_url"] = $domain."/";
					}
					else
					{
						if(!empty($sql_fetch_url_rows["custom_link"]))
						{
							$url_url_results["fetch_url"] = $sql_fetch_url_rows["custom_link"];
						}
					}
					
					$get_url_from_id = $url_url_results["fetch_url"];
				}
				else
				{
					$get_url_from_id = $domain.'/?url-id-disabled-or-deleted='.$get_url_from_id;
				}
			}
			
			return $get_url_from_id;
		}
	}
	
	//Get url by adminUrlId
	if(!function_exists('adminUrlId'))
	{
		function adminUrlId($get_admin_url_from_id) 
		{
			//Get urls for template embed code
			global $site_id, $home_page, $domain, $url_structure, $sites_end_urls_with, $view_frontend_of_site;
			
			if($get_admin_url_from_id == 0)
			{
				$get_admin_url_from_id = $home_page;
			}
			
			$sql_fetch_url_rows = $_SESSION['results']->getSelectSingleRecord(__LINE__, __FILE__, '*', 'urls', 'WHERE `id` = ? AND `site_id` = ? AND `url_status` = ? LIMIT 1', [$get_admin_url_from_id, $_SESSION["site_set_for_editing"], '1']);
			
			$pages_unique_id_value = '';
			if(!isset($view_frontend_of_site)) { $view_frontend_of_site = ''; }
			$pages_url_path = '';
			$url_end_url_with = '';
			$admin_final_url = '';
			
			$admin_url_id = array('id' => $pages_unique_id_value, 'domain' => $view_frontend_of_site, 'url_path' => $pages_url_path, 'url_extension' => $url_end_url_with, 'final_url' => $admin_final_url);
			
			if(!empty($sql_fetch_url_rows))
			{
				if(empty($sql_fetch_url_rows["custom_link"])) 
				{
					if(!empty($sql_fetch_url_rows["id"]))
					{
						$pages_unique_id_value = $sql_fetch_url_rows["id"];
					}
					
					if(!empty($sql_fetch_url_rows["hierarchy_url"])) 
					{
						$pages_url_path = $sql_fetch_url_rows["hierarchy_url"]; 
					}
					else
					{
						$pages_url_path = $sql_fetch_url_rows["flat_url"]; 
					}
					
					if(!empty($sql_fetch_url_rows["url_extension"]))
					{
						$url_end_url_with = $sql_fetch_url_rows["url_extension"];
					}
					else
					{
						$url_end_url_with = $sites_end_urls_with;
					}
					
					if($url_structure == 'Hierarchy')
					{
						$admin_final_url = $view_frontend_of_site."/".$pages_url_path.$url_end_url_with; 
					}
					elseif($url_structure == 'Flat')
					{
						$admin_final_url = $view_frontend_of_site."/".$sql_fetch_url_rows["flat_url"].$url_end_url_with; 
					}
				}
				
				$admin_url_id = array('id' => $pages_unique_id_value, 'domain' => $view_frontend_of_site, 'url_path' => $pages_url_path, 'url_extension' => $url_end_url_with, 'final_url' => $admin_final_url);
			}
			
			return $admin_url_id;
		}
	}
}