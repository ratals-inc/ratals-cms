<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/sort-table-rows.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/sort-table-rows.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'assigned_inventory' || $_SESSION['admin_assigned_type'] == 'sub_products_assigned' || $_SESSION['admin_assigned_type'] == 'products_and_inventory_assigned' || $_SESSION['admin_sort_or_dragdrop'] == 'dragdrop')
	{
		$results_per_page = 10;	
		if(isset($_GET['results-per-page']) && !empty($_GET['results-per-page']) && $_SESSION['admin_id'] != 42 && $_SESSION['admin_id'] != 66 && $_SESSION['admin_id'] != 67)
		{
			$results_per_page = $_GET['results-per-page'];
		}
		elseif(isset($_GET['assigned-results-per-page']) && !empty($_GET['assigned-results-per-page']))
		{
			$results_per_page = $_GET['assigned-results-per-page'];
		}
		
		$page_number = 1;
		if(isset($_GET['page-number']) && !empty($_GET['page-number']))
		{
			$page_number = $_GET['page-number'];
		}
		elseif(isset($_GET['assigned-page-number']) && !empty($_GET['assigned-page-number']))
		{
			$page_number = $_GET['assigned-page-number'];
		}
		
		//Have to get starting point for page pagination
		$sort_counter_starting_point = (($results_per_page * $page_number) - $results_per_page) + 1;
	?>
	<script nonce="<?php echo NONCE; ?>">
	//Sort table rows
	$(function() 
	{
		$("#sortrows").sortable(
		{
			cursor: 'move', handle: ".table-row-sort", update: function() 
			{
				//For Inventory Assigned to Products
				if('<?php if(isset($_SESSION['admin_assigned_type'])) { echo $_SESSION['admin_assigned_type']; } else { echo 'None'; }  ?>' == 'assigned_inventory')
				{
					var update = 'assigned_inventory';
				}
				//For Sub Products Assigned to Products
				else if('<?php if(isset($_SESSION['admin_assigned_type'])) { echo $_SESSION['admin_assigned_type']; } else { echo 'None'; }  ?>' == 'sub_products_assigned')
				{
					var update = 'sub_products_assigned';
				}
				//For all other admin pages set with Drag and Drop
				else if('<?php if(isset($_SESSION['admin_sort_or_dragdrop'])) { echo $_SESSION['admin_sort_or_dragdrop']; } else { echo 'None'; }  ?>' == 'dragdrop')
				{
					var update = 'dragdrop';
				}
				else
				{
					var update = '';
				}
				
				$(".pending-ajax-inner-container span").html("Updating... Hang tight.")
				$("body").addClass("body-pending-ajax");
				$(".pending-ajax").show();
				
				var order = $(this).sortable("serialize") + '&sub_rid=<?php if(isset($_GET["rid"])) { echo trim($_GET["rid"] ?? ''); } ?>' + '&sortrows_update=' + update + '&sort_counter=<?php echo $sort_counter_starting_point; ?>'; 
				$.post("<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/table/ajax/sort-table-rows.php", order, function(theResponse)
				{
					//alert(theResponse);
					location.reload();
				}); 															 
			}								  
		});
	});
	</script>
	<?php } ?>
<?php } ?>
