<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/edit/includes/urls-header.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/edit/includes/urls-header.php');
}
else
{
	$display_url_data = ' display-as-none';
	
	if($_POST)
	{
		//Keep Edit URL toggle div open if url changed or empty on post 
		if(empty($post_values['urls']['hierarchy_url']) || empty($post_values['urls']['flat_url']) || !empty($errors['urls']['flat_url']) || !empty($errors['urls']['hierarchy_url']))
		{
			$display_url_data = ' display-as-block';
		}
		elseif($post_values['urls']['hierarchy_url'] != $current_values['urls']['hierarchy_url'])
		{
			$display_url_data = ' display-as-block';
		}
		elseif($post_values['urls']['flat_url'] != $current_values['urls']['flat_url'])
		{
			$display_url_data = ' display-as-block';
		}
	}
	
	if($sites["url_structure"] == 'Hierarchy')
	{
		$display_url_link = $current_values['urls']['hierarchy_url'];
	}
	else
	{
		$display_url_link = $current_values['urls']['flat_url'];
	}
	
	if(!empty($current_values['urls']['url_extension']))
	{
	   $display_url_link .= $current_values['urls']['url_extension'];
	}
	else
	{
		$display_url_link .= $sites['global_url_extension'];
	}
	?>
	<div class="url-field-edit">
	<?php if(trim($_GET["rid"] ?? '') == $sites['homepage']) { $url_link_id = 0; } else { $url_link_id = trim($_GET["rid"] ?? ''); } ?>
	<a href="<?php echo $view_frontend_of_site.'/'.$display_url_link; ?>" target="_blank"><?php echo $view_frontend_of_site.'/'.$display_url_link; ?></a> 
	| 
	<span class="link" id="toogle-url-data">Edit URL</span> 
	| 
	Enbed URL in Content: <strong>urlId(<?php echo $url_link_id;?>);</strong> 
	| 
	Enbed URL in Template Code: <strong>&lt;?php echo urlId(<?php echo $url_link_id;?>); ?&gt;</strong>
	</div>
<?php } ?>
