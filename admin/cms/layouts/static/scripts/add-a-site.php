<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/scripts/add-a-site.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/static/scripts/add-a-site.php');
}
else
{
	if($_SESSION['admin_assigned_type'] == 'add_a_site')
	{
	?>
	<script nonce="<?php echo NONCE; ?>">
	$(document).on('input', '.tld', function()
	{
		var domainName = $(".tld").val()
		$(".new-site-name").val(domainName);
	});
	</script>
	<?php } ?>
<?php } ?>