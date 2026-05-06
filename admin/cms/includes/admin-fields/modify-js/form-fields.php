<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/modify-js/form-fields.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/modify-js/form-fields.php');
}
else
{
	if($_SESSION['admin_table_name'] == 'form_fields')
	{
	?>
		<?php if($_SESSION['admin_type'] == 'add') { ?>
		<script nonce="<?php echo NONCE; ?>">
		$( document ).ready(function()
		{
			$('#form_fields_frontend_name').on('input', function() 
			{
				var frontendName = $("#form_fields_frontend_name").val().toLowerCase().replace(/[^a-z0-9]/g, "_");
				$("#form_fields_admin_name").val(frontendName);
			});
		});
		</script>
		<?php } ?>
	<?php } ?>
<?php } ?>