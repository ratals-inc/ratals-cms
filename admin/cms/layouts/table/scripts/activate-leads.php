<?php
//Copyright (c) 2025-2026 Ratals Inc.
//Licensed under the Apache License, Version 2.0
//Full License & Terms: https://www.ratals.com/license/

if(file_exists(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/activate-leads.php'))
{
	require_once(INSTALLATION_ROOT.'/hooks/admin/cms/layouts/table/scripts/activate-leads.php');
}
else
{
?>
	<script nonce="<?php echo NONCE; ?>">
    //Activate Leads
    $(document).ready(function()
    {
        $(document).on('click', '.activateLeads', function()
        {
            var activateRow = new Array();
            $("[id='delete']:checked").each(function ()
            {
                activateRow.push(this.value);
            });
            
            if(activateRow.length>0 && confirm("Are you sure you want to permanently activate the selected rows?"))
            {
                $(".pending-ajax-inner-container span").html("Updating... Hang tight.")
                $("body").addClass("body-pending-ajax");
                $(".pending-ajax").show();
                
                $.post("/<?php echo $_SESSION['admin_directory']; ?>/cms/layouts/table/ajax/activate-leads.php",{activateRow:activateRow,type:'activateLeads'},
                function(data)
                {
                    if(data == 1)
                    {
                        location.reload();
                        $(".deleteCheckBox").attr('checked', false); 
                    }
                });
            }
            else if(activateRow.length==0)
            {
                alert('Please select at least 1 row to activate.');
            }
        });
    });
    </script>
<?php } ?>