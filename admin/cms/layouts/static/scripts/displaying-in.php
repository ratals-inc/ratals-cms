<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/scripts/displaying-in.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/static/scripts/displaying-in.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'displaying_in')
	{
	?>
	<script nonce="<?php echo NONCE; ?>">
	$(document).ready(function()
	{
		$(document).on('click', '.displayingInStatus', function()
		{
			var dataValues = $(this).attr('data-click');
			var dataArray = dataValues.split(',');
			
			var id = dataArray[0];
			var value = dataArray[1];
			var counter = dataArray[2];
			var assignment = dataArray[3];
			
			if(value == 1)
			{
				value = 2;
			}
			else
			{
				value = 1;
			}
			
			$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/displaying-in.php",{type:'displayingInStatus',id:id,value:value,counter:counter,assignment:assignment},
			function(theResponse)
			{
				$(".status_"+counter).html(theResponse);
			});
		});
		
		$(document).on('click', '.displayingInRemove', function()
		{
			var dataValues = $(this).attr('data-click');
			var dataArray = dataValues.split(',');
			
			var id = dataArray[0];
			var pId = dataArray[1];
			var assignment = dataArray[2];
			
			if(confirm('Are you sure you want to remove ID '+pId+'?') == true)
			{
				$.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/static/ajax/displaying-in.php",{type:'displayingInRemove',id:id,assignment:assignment},
				function(theResponse)
				{
					location.reload();
				});
			}
		});
	});
	</script>
	<?php } ?>
<?php } ?>
