<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/scripts/assigned-results-per-page.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/scripts/assigned-results-per-page.php');
}
else
{
?>
	<script nonce="<?php echo NONCE; ?>">
    $(document).ready(function()
    {
        $(document).on('change', '.assigned-results-per-page', function()
        {
            this.form.submit();
        });
    });
    </script>
<?php } ?>