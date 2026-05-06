<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/scripts/unassigned-urls-status.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/scripts/unassigned-urls-status.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'unassigned_categories' || $_SESSION['admin_assigned_type'] == 'unassigned_pages' || $_SESSION['admin_assigned_type'] == 'unassigned_posts' || $_SESSION['admin_assigned_type'] == 'unassigned_products')
	{
	?><script nonce="<?php echo NONCE; ?>">
	$(document).ready(function()
	{
		$(document).on('click', '.unassignedStatus', function()
		{
			var dataValues = $(this).attr('data-click');
			var dataArray = dataValues.split(',');
			
			var recordId = dataArray[0];
			var urlId = dataArray[1];
			var value = dataArray[2];
			
			if(value == 1)
			{
				value = 2;
			}
			else
			{
				value = 1;
			}
			
			$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/unassigned-urls-status.php",{type:'unassignedStatus',recordId:recordId,urlId:urlId,value:value},
			function(theResponse)
			{
				//alert(theResponse);
				location.reload();
			});
		});
	});
	</script>
	<?php } ?>
<?php } ?>
