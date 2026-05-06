<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/scripts/category-heirarchy-flat-url.php'))
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/layouts/table/scripts/category-heirarchy-flat-url.php');
}
else
{
?>
	<script nonce="<?php echo NONCE; ?>">
    $(document).ready(function () 
    {
        //redirect to category view for all urls or heirarchy urls
        $(document).on('change', '#layout_type', function()
        {
            if(this.value == "1")
            {
                window.location.href = "/<?php echo $_SESSION['admin_url']; ?>/";
            }
            else if(this.value == "2")
            {
                window.location.href = "/<?php echo $_SESSION['admin_url']; ?>/?layout=hierarchy&path-ids=0";
            }
        });	
    });
    </script>
<?php } ?>
