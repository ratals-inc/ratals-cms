<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/scripts/form-media-swatches.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/scripts/form-media-swatches.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'form_media_swatches')
	{
	?>
	<script nonce="<?php echo NONCE; ?>">
	//Sort option rows
	$(function() 
	{
		$(".multiple-media_forms").sortable({ cursor: 'move', handle: ".move-handle" });
	});
	</script>
	<?php } ?>
<?php } ?>