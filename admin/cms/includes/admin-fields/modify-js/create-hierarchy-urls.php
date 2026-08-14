<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/create-hierarchy-urls.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/create-hierarchy-urls.php');
}
else
{
	if($admin_fields_has_url == 'Yes' && $_SESSION['admin_type'] == 'edit')
	{
	?>
	<script nonce="<?php echo NONCE; ?>">
	//Start Ajax to get sub categories
	$(document).on('change', '.pathLevelUrls', function()
	{
		var edit_id = $(this).attr('data-click');
		var last_id = $(this).val();
		
		var all_ids = [];	
		$("select[name='urls[path_level][]']").each(function() 
		{
			if($(this).val() == '')
			{
				return false; //break;
			}
			else
			{
				all_ids.push($(this).val());
			}
		});
		
		var fetch = 'edit_id=' + edit_id + '&last_id=' + last_id + '&all_ids=' + all_ids + '&type=<?php echo $current_values['urls']['table_name']; ?>' + '&fetch_categories=yes'; 
		$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/admin-fields/ajax/create-hierarchy-urls.php", fetch, function(theResponse)
		{
			//alert(theResponse);
			$("#display-sub-categories").html(theResponse);
		}); 
		
		var fetch2 = 'last_id=' + last_id + '&all_ids=' + all_ids + '&type=<?php echo $current_values['urls']['table_name']; ?>' + '&fetch_url=yes'; 
		$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/admin-fields/ajax/create-hierarchy-urls.php", fetch2, function(theResponse)
		{
			//alert(theResponse);
			$("#display-hierarchy-url").html(theResponse);
			
			var hierarchyURL = $("#urls_hierarchy_url").val();
			var hierarchyURLPath = theResponse;
			var hierarchyURLExtension = $('#display_url_extension').text();
			var hrefHierarchyURL = '<?php echo $domain; ?>/'+hierarchyURLPath+hierarchyURL+hierarchyURLExtension;
			$("#href_hierarchy_url").attr('href', hrefHierarchyURL);
		});
	});
	//End Ajax to get sub categories
	
	//Start Open/Close URL Fields
	$(function()
	{ 
		$("#toogle-url-data").click(function()
		{
			if($("#url-data").is(":hidden"))
			{
				$("#url-data").slideDown();
				$('html, body').animate({ scrollTop: $("#url-data").offset().top - 20}, 700);	
			}
			else
			{
				$("#url-data").slideUp();
			}
		}); 
	});
	//End Open/Close URL Fields
	
	//Start show datepicker
	$(function() 
	{
		$( "#datepicker" ).datepicker({dateFormat: "yy-mm-dd"});
	});
	//End show datepicker
	
	//Start document ready
	$( document ).ready(function()
	{
		//Start status = scheduled | show schedule_date field with calendar
		var status = $("#urls_url_status").val();
		if(status == '4')
		{
			$(".scheduled-date").show();
		}
		else
		{
			<?php if(!isset($errors['urls']['scheduled_date'])) { ?>$(".scheduled-date").hide(); <?php } ?>
		}
		
		$('#urls_url_status').on('change', function() 
		{
			var status = $("#urls_url_status").val();
			if(status == '4')
			{
				$(".scheduled-date").slideDown();
			}
			else
			{
				$(".scheduled-date").slideUp();
			}
		});
		//End status = scheduled | show schedule_date field with calendar
		
		//Start set correct Flat URL name
		$('#urls_flat_url').on('input', function() 
		{
			var flatURL = $("#urls_flat_url").val();
			var flatURL = flatURL.toLowerCase().replace(/[^a-z0-9]/g, "-");
			$("#display-flat-url").text(flatURL);
			$("#urls_flat_url").val(flatURL);
			
			var flatURLExtension = $('#display_flat_url_extension').text();
			var hrefFlatURL = '<?php echo $domain; ?>/'+flatURL+flatURLExtension;
			$("#href_flat_url").attr('href', hrefFlatURL);
		});
		//End set correct Flat URL name
		
		//Start set correct URL name
		$('#urls_hierarchy_url').on('input', function() 
		{
			var hierarchyURL = $("#urls_hierarchy_url").val();
			var hierarchyURL = hierarchyURL.toLowerCase().replace(/[^a-z0-9]/g, "-");
			
			$("#display-url-name").text(hierarchyURL);
			$("#urls_hierarchy_url").val(hierarchyURL);
			
			var hierarchyURLPath = $('#display-hierarchy-url').text();
			var hierarchyURLExtension = $('#display_url_extension').text();
			var hrefHierarchyURL = '<?php echo $domain; ?>/'+hierarchyURLPath+hierarchyURL+hierarchyURLExtension;
			$("#href_hierarchy_url").attr('href', hrefHierarchyURL);
		});
		//End set correct URL name
		
		//Start set correct end URL
		$('#urls_url_extension').on('input', function() 
		{
			var url_extension = $("#urls_url_extension").val();
			var url_extension = url_extension.toLowerCase().replace(/[^a-z]/g, "");
		
			if(url_extension.charAt(0) != '.' && url_extension != '')
			{
			   var concatPeriod = ".";
			   url_extension = concatPeriod.concat(url_extension);
			   $("#display_url_extension").text(url_extension);
			   $("#display_flat_url_extension").text(url_extension);
			   $("#urls_url_extension").val(url_extension);
			   
			   var flatURL = $("#urls_flat_url").val();
			   var flatURLExtension = $('#display_flat_url_extension').text();
			   var hrefFlatURL = '<?php echo $domain; ?>/'+flatURL+flatURLExtension;
			   $("#href_flat_url").attr('href', hrefFlatURL);
			   
			   var hierarchyURL = $("#urls_hierarchy_url").val();
			   var hierarchyURLPath = $('#display-hierarchy-url').text();
			   var hierarchyURLExtension = $('#display_url_extension').text();
			   var hrefHierarchyURL = '<?php echo $domain; ?>/'+hierarchyURLPath+hierarchyURL+hierarchyURLExtension;
			   $("#href_hierarchy_url").attr('href', hrefHierarchyURL);
			}
			else if(url_extension.length == 0)
			{
			   $("#display_url_extension").text("<?php echo $sites["global_url_extension"]; ?>");
			   $("#display_flat_url_extension").text("<?php echo $sites["global_url_extension"]; ?>");
			}
			else
			{
			   $("#display_url_extension").text(url_extension);
			   $("#display_flat_url_extension").text(url_extension);
			   $("#urls_url_extension").val(url_extension);
			}
		});
		//End set correct end URL
	});
	//end document ready
	</script>
	<?php } if($admin_fields_has_url == 'Yes' && $_SESSION['admin_type'] == 'add') {  ?>
	<script nonce="<?php echo NONCE; ?>">
	//Start Ajax to get sub categories
	$(document).on('change', '.pathLevelUrls', function()
	{
		var edit_id = $(this).attr('data-click');
		var last_id = $(this).val();
		
		var all_ids = [];
		$("select[name='urls[path_level][]']").each(function() 
		{
			if($(this).val() == '')
			{
				return false; //break;
			}
			else
			{
				all_ids.push($(this).val());
			}
		});
		
		var fetch = 'edit_id=' + edit_id + '&last_id=' + last_id + '&all_ids=' + all_ids + '&type=<?php echo $_SESSION['admin_table_name']; ?>' + '&fetch_categories=yes'; 
		$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/admin-fields/ajax/create-hierarchy-urls.php", fetch, function(theResponse)
		{
			//alert(theResponse);
			$("#display-sub-categories").html(theResponse);
		}); 
		
		var fetch2 = 'last_id=' + last_id + '&all_ids=' + all_ids + '&type=<?php echo $_SESSION['admin_table_name']; ?>' + '&fetch_url=yes'; 
		$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/includes/admin-fields/ajax/create-hierarchy-urls.php", fetch2, function(theResponse)
		{
			//alert(theResponse);
			$("#display-hierarchy-url").html(theResponse);
			
			var hierarchyURL = $("#urls_hierarchy_url").val();
			var hierarchyURLPath = theResponse;
			var hierarchyURLExtension = $('#display_url_extension').text();
			var hrefHierarchyURL = '<?php echo $domain; ?>/'+hierarchyURLPath+hierarchyURL+hierarchyURLExtension;
			$("#href_hierarchy_url").attr('href', hrefHierarchyURL);
		});
	});
	//End Ajax to get sub categories
	
	//Start Open/Close URL Fields
	$(function()
	{ 
		$("#toogle-url-data").click(function()
		{
			if($("#url-data").is(":hidden"))
			{
				$("#url-data").slideDown();
				$('html, body').animate({ scrollTop: $("#url-data").offset().top - 20}, 700);	
			}
			else
			{
				$("#url-data").slideUp();
			}
		}); 
	});
	//End Open/Close URL Fields
	
	//Start show datepicker
	$(function() 
	{
		$( "#datepicker" ).datepicker({dateFormat: "yy-mm-dd"});
	});
	//End show datepicker
	
	//Start document ready
	$( document ).ready(function()
	{
		//Start status = scheduled | show schedule_date field with calendar
		var status = $("#urls_url_status").val();
		if(status == '4')
		{
			$(".scheduled-date").show();
		}
		else
		{
			<?php if(!isset($errors['urls']['scheduled_date'])) { ?>$(".scheduled-date").hide(); <?php } ?>
		}
		
		$('#urls_url_status').on('change', function() 
		{
			var status = $("#urls_url_status").val();
			if(status == '4')
			{
				$(".scheduled-date").slideDown();
			}
			else
			{
				$(".scheduled-date").slideUp();
			}
		});
		//End status = scheduled | show schedule_date field with calendar
		
		//Start set correct Flat URL name
		$('#urls_flat_url').on('input', function() 
		{
			var flatURL = $("#urls_flat_url").val();
			var flatURL = flatURL.toLowerCase().replace(/[^a-z0-9]/g, "-");
			$("#display-flat-url").text(flatURL);
			$("#urls_flat_url").val(flatURL);
			
			var flatURLExtension = $('#display_flat_url_extension').text();
			var hrefFlatURL = '<?php echo $domain; ?>/'+flatURL+flatURLExtension;
			$("#href_flat_url").attr('href', hrefFlatURL);
		});
		//End set correct Flat URL name
		
		//Start set correct URL name
		$('#urls_hierarchy_url').on('input', function() 
		{
			var hierarchyURL = $("#urls_hierarchy_url").val();
			var hierarchyURL = hierarchyURL.toLowerCase().replace(/[^a-z0-9]/g, "-");
			
			$("#display-url-name").text(hierarchyURL);
			$("#urls_hierarchy_url").val(hierarchyURL);
			
			var hierarchyURLPath = $('#display-hierarchy-url').text();
			var hierarchyURLExtension = $('#display_url_extension').text();
			var hrefHierarchyURL = '<?php echo $domain; ?>/'+hierarchyURLPath+hierarchyURL+hierarchyURLExtension;
			$("#href_hierarchy_url").attr('href', hrefHierarchyURL);
		});
		//End set correct URL name
		
		//Start set correct end URL
		$('#urls_url_extension').on('input', function() 
		{
			var url_extension = $("#urls_url_extension").val();
			var url_extension = url_extension.toLowerCase().replace(/[^a-z]/g, "");
		
			if(url_extension.charAt(0) != '.' && url_extension != '')
			{
			   var concatPeriod = ".";
			   url_extension = concatPeriod.concat(url_extension);
			   $("#display_url_extension").text(url_extension);
			   $("#display_flat_url_extension").text(url_extension);
			   $("#urls_url_extension").val(url_extension);
			   
			   var flatURL = $("#urls_flat_url").val();
			   var flatURLExtension = $('#display_flat_url_extension').text();
			   var hrefFlatURL = '<?php echo $domain; ?>/'+flatURL+flatURLExtension;
			   $("#href_flat_url").attr('href', hrefFlatURL);
			   
			   var hierarchyURL = $("#urls_hierarchy_url").val();
			   var hierarchyURLPath = $('#display-hierarchy-url').text();
			   var hierarchyURLExtension = $('#display_url_extension').text();
			   var hrefHierarchyURL = '<?php echo $domain; ?>/'+hierarchyURLPath+hierarchyURL+hierarchyURLExtension;
			   $("#href_hierarchy_url").attr('href', hrefHierarchyURL);
			}
			else if(url_extension.length == 0)
			{
			   $("#display_url_extension").text("<?php echo $sites["global_url_extension"]; ?>");
			   $("#display_flat_url_extension").text("<?php echo $sites["global_url_extension"]; ?>");
			}
			else
			{
			   $("#display_url_extension").text(url_extension);
			   $("#display_flat_url_extension").text(url_extension);
			   $("#urls_url_extension").val(url_extension);
			}
		});
		//End set correct end URL
		
		$('#urls_meta_title').on('input', function() 
		{
			var titleName = $("#urls_meta_title").val().toLowerCase().replace(/[^a-z0-9]/g, "-");
			$("#urls_flat_url").val(titleName);
			$("#display-flat-url").text(titleName);
			$("#urls_hierarchy_url").val(titleName);
			$("#display-url-name").text(titleName);
		});
	});
	//end document ready
	</script>
	<?php } ?>
<?php } ?>
