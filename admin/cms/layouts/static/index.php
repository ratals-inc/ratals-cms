<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/index.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/index.php');
}
else
{
	//Auto loader - headers
	$types_to_load = array();
	
	if(is_dir(INSTALLATION_ROOT.'/admin/cms/layouts/static/headers')) 
	{
		$types_to_load[] = 'cms'; 
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/commerce/layouts/static/headers')) 
	{
		$types_to_load[] = 'commerce';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/erp/layouts/static/headers'))
	{
		$types_to_load[] = 'erp';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/ai/layouts/static/headers')) 
	{
		$types_to_load[] = 'ai';
	}
	
	foreach($types_to_load as $type_to_load)
	{
		$existing_files = array();
		$directory_path = '/admin/'.$type_to_load.'/layouts/static/headers';
		$auto_loader_path = INSTALLATION_ROOT.$directory_path;
		$auto_loader_files = array_diff(scandir($auto_loader_path), array('.', '..'));
		if(!empty($auto_loader_files))
		{
			foreach($auto_loader_files as $auto_loader_file)
			{
				$existing_files[] = $auto_loader_file;
				
				if(file_exists(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file))
				{
					include_once(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file);
				}
				else
				{
					include_once(INSTALLATION_ROOT.$directory_path.'/'.$auto_loader_file);
				}
			}
		}
	}
	?><!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php if(!empty($head_title_name)) { echo $head_title_name.' '; } echo $_SESSION['admin_title']; ?></title>
	<?php include_once(INSTALLATION_ROOT.'/admin/cms/includes/head-files.php'); ?>
	<?php
	//Auto loader - scripts
	$types_to_load = array();
	
	if(is_dir(INSTALLATION_ROOT.'/admin/cms/layouts/static/scripts')) 
	{
		$types_to_load[] = 'cms'; 
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/commerce/layouts/static/scripts')) 
	{
		$types_to_load[] = 'commerce';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/erp/layouts/static/scripts'))
	{
		$types_to_load[] = 'erp';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/ai/layouts/static/scripts')) 
	{
		$types_to_load[] = 'ai';
	}
	
	foreach($types_to_load as $type_to_load)
	{
		$existing_files = array();
		$directory_path = '/admin/'.$type_to_load.'/layouts/static/scripts';
		$auto_loader_path = INSTALLATION_ROOT.$directory_path;
		$auto_loader_files = array_diff(scandir($auto_loader_path), array('.', '..'));
		if(!empty($auto_loader_files))
		{
			foreach($auto_loader_files as $auto_loader_file)
			{
				$existing_files[] = $auto_loader_file;
				
				if(file_exists(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file))
				{
					include_once(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file);
				}
				else
				{
					include_once(INSTALLATION_ROOT.$directory_path.'/'.$auto_loader_file);
				}
			}
		}
	}
	?>
	</head>
	
	<body>
	<!-- Start Pending Ajax Overlay -->
	<style nonce="<?php echo NONCE; ?>">.pending-ajax { display: none; }</style>
	<div class="pending-ajax">
		<div class="pending-ajax-outer-container">
			<div class="pending-ajax-inner-container">
				<span>Updating...</span>
			</div>
		</div>
	</div>
	<!-- End Pending Ajax Overlay -->
	<!-- Start Left Column -->
	<?php include_once(INSTALLATION_ROOT.'/admin/cms/includes/navigation.php');?>
	<!-- End Left Column -->
	
	<!-- Start Right Column -->
	<div class="right">
    <!-- Start Notices -->
    <?php 
	include(INSTALLATION_ROOT.'/admin/cms/includes/notices/index.php');
	echo $display_message; 
	?>
    <!-- End Notices -->
    <?php if($level >= $_SESSION['admin_page_level']) { ?>
    <?php 
	if(isset($_GET["edited"]) && $_GET["edited"] == "success")
	{
		echo '<div class="changes-saved">Edited successfully.</div>';
	} 
	
	if(isset($_GET["created"]) && $_GET["created"] == "success")
	{
		echo '<div class="changes-saved">Created successfully.</div>';
	}
	
	if(isset($errors) && !empty($errors))
	{
		echo '<div class="changes-error">Oops! It looks like you missed something.</div>';
	}
	?>
    
	<!-- Start Header -->
	<div class="header-text">
	  <div class="text"><?php echo $_SESSION['admin_title']; ?></div>
	  <div class="header-right">
		<?php if(!empty($_SESSION['admin_help_video_url'])) { ?><a href="<?php echo $_SESSION['admin_help_video_url']; ?>" target="_blank"><div class="header-video"><i><svg viewBox="0 0 512 512"><path d="M4 162a63 63 0 0 1 63-63h236a63 63 0 0 1 62 55l98-44A31 31 0 0 1 508 139v235a31 31 0 0 1-44 29l-98-44A63 63 0 0 1 303 413H67a63 63 0 0 1-63-63z"></path></svg></i> Tutorial</div></a><?php } ?>
		<div class="toggle-results">Results</div>
	  </div>
	</div>
	<!-- End Header -->
	<?php 
	include_once INSTALLATION_ROOT.'/admin/cms/includes/sub-navigation.php';
	if(!empty($sub_menu)) { echo $sub_menu; }
	?>
	<?php 
	//Auto loader - addons
	$types_to_load = array();
	
	if(is_dir(INSTALLATION_ROOT.'/admin/cms/layouts/static/addons')) 
	{
		$types_to_load[] = 'cms';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/commerce/layouts/static/addons')) 
	{
		$types_to_load[] = 'commerce';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/erp/layouts/static/addons'))
	{
		$types_to_load[] = 'erp';
	}
	
	if(is_dir(INSTALLATION_ROOT.'/admin/ai/layouts/static/addons')) 
	{
		$types_to_load[] = 'ai';
	}
	
	foreach($types_to_load as $type_to_load)
	{
		$existing_files = array();
		$directory_path = '/admin/'.$type_to_load.'/layouts/static/addons';
		$auto_loader_path = INSTALLATION_ROOT.$directory_path;
		$auto_loader_files = array_diff(scandir($auto_loader_path), array('.', '..'));
		if(!empty($auto_loader_files))
		{
			foreach($auto_loader_files as $auto_loader_file)
			{
				$existing_files[] = $auto_loader_file;
				
				if(file_exists(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file))
				{
					include_once(INSTALLATION_ROOT.'/hooks'.$directory_path.'/'.$auto_loader_file);
				}
				else
				{
					include_once(INSTALLATION_ROOT.$directory_path.'/'.$auto_loader_file);
				}
			}
		}
	}
	?>
    <?php 
    } 
    else
    {
    ?>
    <div class="header-text">
    <div class="text"><?php echo $_SESSION['admin_title']; ?></div>
    </div>
    <!-- End Header -->
    <?php
    echo $account_message;
    }
    ?>
	</div>
	<!-- End Right Column -->
	</body>
	</html>
<?php } ?>