<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/scripts/form-fields-assigned.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/scripts/form-fields-assigned.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'form_fields_assigned')
	{
	?>
	<script nonce="<?php echo NONCE; ?>">
	//Make Popup List Sortable for Form Fields
	$(function() { $("#sort-form_fields").sortable({ cursor: 'move', handle: "#sort-fields-handle" }); });
	
	//Show List Popup for Form Fields
	$(function()
	{
		$("#show-fields").click(function()
		{ 	
			$("#form_fields").show(); 
			$("body").addClass("popup-overflow-hidden"); 
		});
	});
	
	//Hide List Popup for Form Fields
	$(function()
	{
		$("#hide-form_fields").click(function()
		{ 	
			$("#form_fields").hide(); 
			$("body").removeClass("popup-overflow-hidden"); 
		});
	});
	</script>
	<?php } ?>
<?php } ?>