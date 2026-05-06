<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/scripts/sub-items.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/scripts/sub-items.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'sub_items')
	{
	?>
	<script nonce="<?php echo NONCE; ?>">
	//Start show datepicker
	$(function() 
	{
		$("#datepicker-product").datepicker({dateFormat: "yy-mm-dd"});
		$("#datepicker-product-from").datepicker({dateFormat: "yy-mm-dd"});
		$("#datepicker-product-to").datepicker({dateFormat: "yy-mm-dd"});
		$("#datepicker-product-search").datepicker({dateFormat: "yy-mm-dd"});
		$("#datepicker-product-search-from").datepicker({dateFormat: "yy-mm-dd"});
		$("#datepicker-product-search-to").datepicker({dateFormat: "yy-mm-dd"});
	});
	
	//Remove groups
	$(function() { $(".sortGroups").sortable({ cursor: 'move', handle: ".order-group" }); });
	$(function() { $(".sortCategories").sortable({ cursor: 'move', handle: ".order-row" }); });
	
	$(document).ready(function()
	{
		$(document).on('change', '#groups, #item-type, #items', function()
		{
			var groupId = $("#groups").val();	
			var GroupName = $("#groups option:selected").text();
			
			var productsId = $('#items').find('input[type="checkbox"]:checked').length;
			$('#items-group-id').val(groupId);
			
			if(groupId != '')
			{
				$('#categories-toggle').hide();
				$('#pages-toggle').hide();
				$('#posts-toggle').hide();
				$('#items-toggle').show();
				
				if(productsId != '')
				{
					$('#items-submit').show();
				}
				else
				{
					$('#items-submit').hide();
				}
			}
			else
			{
				$('#items-toggle').hide();
			}
		});
		
		//Display sub item options on load if not loading a file include
		//$(document).on('load', '.sub-item-type', function()
		$('.sub-item-type').each(function() 
		{
			var subItemId = $(this).data('sub-item-id');
			var displaySection = $(this).find("option:selected").text();
			
			if(displaySection == 'Code (html/css)')
			{
				$('.include-file-'+subItemId).hide();
				$('.sub-items-file-include-'+subItemId).hide();
				$('.code-'+subItemId).slideDown();
			}
			else if(displaySection == 'Include File')
			{
				$('.code-'+subItemId).hide();
				$('.sub-items-file-include-'+subItemId).hide();
				$('.include-file-'+subItemId).slideDown();
			}
			else if(displaySection == 'Sub Items')
			{
				$('.code-'+subItemId).hide();
				$('.include-file-'+subItemId).hide();
				$('.sub-items-file-include-'+subItemId).slideDown();
			}
			else
			{
				$('.code-'+subItemId).hide();
				$('.include-file-'+subItemId).hide();
				$('.sub-items-file-include-'+subItemId).hide();
			}
		});
		
		//Display sub item options on change if not loading a file include
		$(document).on('change', '.sub-item-type', function()
		{
			var subItemId = $(this).data('sub-item-id');
			var displaySection = $(this).find("option:selected").text();
			
			if(displaySection == 'Code (html/css)')
			{
				$('.include-file-'+subItemId).hide();
				$('.sub-items-file-include-'+subItemId).hide();
				$('.code-'+subItemId).slideDown();
			}
			else if(displaySection == 'Include File')
			{
				$('.code-'+subItemId).hide();
				$('.sub-items-file-include-'+subItemId).hide();
				$('.include-file-'+subItemId).slideDown();
			}
			else if(displaySection == 'Sub Items')
			{
				$('.code-'+subItemId).hide();
				$('.include-file-'+subItemId).hide();
				$('.sub-items-file-include-'+subItemId).slideDown();
			}
			else
			{
				$('.code-'+subItemId).hide();
				$('.include-file-'+subItemId).hide();
				$('.sub-items-file-include-'+subItemId).hide();
			}
		});
		
		//Display slider options on load if Display group as a slider: Yes
		$('.display-as-slider').each(function() 
		{
			var sliderId = $(this).data('slider-id');
			var displaySlider = $(this).find("option:selected").text();
			
			if (displaySlider == 'Yes')
			{
				$('.sub-items-slider-' + sliderId).show();
			}
			else
			{
				$('.sub-items-slider-' + sliderId).hide();
			}
		});
		
		//Display slider options on change if Display group as a slider: Yes
		$(document).on('change', '.display-as-slider', function()
		{
			var sliderId = $(this).data('slider-id');
			var displaySlider = $(this).find("option:selected").text();
			
			if(displaySlider == 'Yes')
			{
				$('.sub-items-slider-'+sliderId).slideDown();
			}
			else
			{
				$('.sub-items-slider-'+sliderId).slideUp();
			}
			
		});
		
		//Remove groups
		$(document).on('click', '.removeGroup', function()
		{
			var id = $(this).attr('data-click');
			
			$(".group_"+id).remove();
		});
		
		//Remove rows
		$(document).on('click', '.removeRow', function()
		{
			var id = $(this).attr('data-click');
			
			$(".row_"+id).remove();
		});
		
		//Chnage active status on items in groups
		$(document).on('click', '.changeActive', function()
		{
			var dataValues = $(this).attr('data-click');
			var dataArray = dataValues.split(',');
			
			var id = dataArray[0];
			var editing = dataArray[1];
			var row_id = dataArray[2];
			var value = dataArray[3];
			
			if(value == 1)
			{
				value = 2;
			}
			else
			{
				value = 1;
			}
			
			$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/sub-items.php",{type:'changeActive',id:id,editing:editing,field:row_id,value:value},
			function(theResponse)
			{
				//alert(theResponse);
				$(".status_"+editing).html(theResponse);
				$('#item_status_'+editing).val(value);
			});
		});
		
		//Change status on whole group. This is the eye and eye slash icon.
		$(document).on('click', '.groupStatus', function()
		{
			var dataValues = $(this).attr('data-click');
			var dataArray = dataValues.split(',');
			var editing = dataArray[0];
			var group_id_count = dataArray[1];
			var group_id = dataArray[2];
			var value = dataArray[3];
			
			if(value == 1)
			{
				value = 2;
			}
			else
			{
				value = 1;
			}
			
			$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/sub-items.php",{type:'groupStatus',editing:editing,group_id_count:group_id_count,group_id:group_id,value:value},
			function(theResponse)
			{
				//alert(theResponse);
				$("#status-group-"+group_id_count).html(theResponse);
				$('#group-status-'+group_id_count).val(value);
			});
		});
	});
	</script>
	<?php } ?>
<?php } ?>