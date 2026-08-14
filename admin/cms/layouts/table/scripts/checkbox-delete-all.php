<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/checkbox-delete-all.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/checkbox-delete-all.php');
}
else
{
?>
	<script nonce="<?php echo NONCE; ?>">
    $(document).ready(function () 
    {
        $(document).on('click', '#deleteAll', function()
        {
            $(".deleteCheckBox").prop('checked', $(this).prop('checked'));
        });
        
        $(document).on('click', '#CheckAllColumns', function()
        {
            $(".CheckAllColumns").prop('checked', $(this).prop('checked'));
        });
    });
    </script>
<?php } ?>