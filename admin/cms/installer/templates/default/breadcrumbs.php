<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(in_array('breadcrumbs.php', $data_array['active_template_includes'])) { ?>
<ul>
<?php 
$breadcurmbs_item_counter = 1;
$json_breadcrumbs_list = '';
if($pages_data["display_breadcrumbs"] == 'Yes')
{
	foreach($breadcrumbs as $breadcrumbs_list)
	{
		$breadcrumbs_name = '';
		if(!empty($breadcrumbs_list['url_data']['breadcrumbs_label']))
		{
			$breadcrumbs_name = $breadcrumbs_list['url_data']['breadcrumbs_label'];
		}
		else
		{
			$breadcrumbs_name = $breadcrumbs_list["url_data"]["meta_title"];
		}
		echo '<li><a href="'.$breadcrumbs_list["url_data"]["url"].'">'.$breadcrumbs_name.'</a></li><li><i><svg viewBox="0 0 512 512"><path d="M127 500A19 19 0 0 0 154 500L385 270A19 19 0 0 0 385 242L154 12A19 19 0 0 0 127 39L344 256 127 473A19 19 0 0 0 127 500"></path></svg></i></li>';
		
		$json_breadcrumbs_list .= '{ "@type": "ListItem", "position": '.$breadcurmbs_item_counter.', "name": "'.$breadcrumbs_name.'", "item": "'.$breadcrumbs_list["url_data"]["url"].'" },';
		
		$breadcurmbs_item_counter ++;
	}
}
elseif(empty($pages_data["display_breadcrumbs"]) && $site_settings["display_breadcrumbs"] == 'Yes')
{
	foreach($breadcrumbs as $breadcrumbs_list)
	{
		$breadcrumbs_name = '';
		if(!empty($breadcrumbs_list['url_data']['breadcrumbs_label']))
		{
			$breadcrumbs_name = $breadcrumbs_list['url_data']['breadcrumbs_label'];
		}
		else
		{
			$breadcrumbs_name = $breadcrumbs_list['url_data']["meta_title"];
		}
		echo '<li><a href="'.$breadcrumbs_list["url_data"]["url"].'">'.$breadcrumbs_name.'</a></li><li><i><svg viewBox="0 0 512 512"><path d="M127 500A19 19 0 0 0 154 500L385 270A19 19 0 0 0 385 242L154 12A19 19 0 0 0 127 39L344 256 127 473A19 19 0 0 0 127 500"></path></svg></i></li>';
		
		$json_breadcrumbs_list .= '{ "@type": "ListItem", "position": '.$breadcurmbs_item_counter.', "name": "'.$breadcrumbs_name.'", "item": "'.$breadcrumbs_list["url_data"]["url"].'" },';
		
		$breadcurmbs_item_counter ++;
	}
}
?>
</ul>
<?php 
if(!empty($json_breadcrumbs_list))
{
?>
<script nonce="<?php echo NONCE; ?>" type="application/ld+json">
{
	"@context": "https://schema.org/", 
	"@type": "BreadcrumbList", 
	"itemListElement": [<?php echo trim($json_breadcrumbs_list, ','); ?>]
}
</script>
<?php } ?>
<?php } ?>
