<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/enable-disable-toggle.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/enable-disable-toggle.php');
}
else
{
?>
	<script nonce="<?php echo NONCE; ?>">
    //Toggle status
    $(document).ready(function()
    {
        $(document).on('click', '.changeActive', function()
        {
            var dataValues = $(this).attr('data-click');
            var dataArray = dataValues.split(',');
            
            var row_id = dataArray[0];
            var value = dataArray[1];
			var table_name = dataArray[2];
            
            if(value == 1)
            {
                value = 2;
            }
            else
            {
                value = 1;
            }
            var assignInventoryValue = "<?php if($_SESSION['admin_assigned_type'] == 'inventory_assigned') { echo 'yes'; } ?>";
            var subItem = "<?php if(!empty(trim($_GET["rid"] ?? ''))) { echo trim($_GET["rid"] ?? ''); } ?>";
            
            $(".pending-ajax-inner-container span").html("Updating... Hang tight.")
            $("body").addClass("body-pending-ajax");
            $(".pending-ajax").show();
            
            $.post("<?php echo INSTALLATION_URL_PATH; ?>/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/table/ajax/enable-disable-toggle.php",{type:'changeActive',field:row_id,value:value,tableName:table_name,subItem:subItem,assignInventory:assignInventoryValue},
            function(data)
            {
                if(data == 1)
                {
                    location.reload();
                }
                else
                {
                    alert(data);
                }
            });
        });
    });
    </script>
<?php } ?>
