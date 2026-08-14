<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/jquery-draggable.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/jquery-draggable.php');
}
else
{
?>
	<script nonce="<?php echo NONCE; ?>">
    //Make jquery draggable work on mobile with touch
    </script>
<?php } ?>