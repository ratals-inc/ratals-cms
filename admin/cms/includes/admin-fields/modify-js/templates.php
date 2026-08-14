<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/templates.php')) 
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/includes/admin-fields/modify-js/templates.php');
}
else
{
	if($_SESSION['admin_table_name'] == 'templates')
	{
	?>
		<?php if($_SESSION['admin_type'] == 'add') { ?>
		<script nonce="<?php echo NONCE; ?>">
		$( document ).ready(function() 
		{
			$("#templates_name").on("input", function() 
			{
				var fileName = $("#templates_name").val();
				var fileName = fileName.toLowerCase().replace(/[^a-z0-9]/g, "-");
				$("#templates_directory_folder_name").val(fileName);
			});
		});
		</script>
		<?php } ?>
	<?php } ?>
<?php } ?>