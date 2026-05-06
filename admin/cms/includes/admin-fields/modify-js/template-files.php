<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/modify-js/template-files.php')) 
{
	require_once($_SERVER['DOCUMENT_ROOT'].'/hooks/admin/cms/includes/admin-fields/modify-js/template-files.php');
}
else
{
	if($_SESSION['admin_table_name'] == 'template_files')
	{
	?>
		<script nonce="<?php echo NONCE; ?>">
        <?php if($_SESSION['admin_type'] == 'add') { ?>
        $( document ).ready(function() 
        {
            $("#template_files_name").on("input", function() 
            {
                var fileName = $("#template_files_name").val();
                var fileName = fileName.toLowerCase().replace(/[^a-z0-9.]/g, "-");
                $("#template_files_filename").val(fileName+'.php');
            });
        });
        <?php } ?>
        </script>
	<?php } ?>
<?php } ?>